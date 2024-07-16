<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//chèn thêm thư viện userInterface tự tạo để lấy thông tin user từ DB vào form
use App\Services\Interfaces\LanguageServiceInterface as LanguageService;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm user
use App\Http\Requests\StoreLanguageRequest;
//chèn thêm viện userRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit user
use App\Http\Requests\UpdateLanguageRequest;
//use App\Models\User;
use App\Http\Requests\TranslateRequest;


class LanguageController extends Controller
{
    protected $languageService;
    protected $languageRepository;

    public function __construct(LanguageService $languageService, LanguageRepository $languageRepository){
        $this->languageService=$languageService;//định nghĩa  $this->userService=$userCatalogueService để biến này nó có thể trỏ tới các phương tức của UserCatalogueService
        $this->languageRepository=$languageRepository;
    }
    //giao diện tổng
    public function index(Request $request){//Request $request để tiến hành chức năng tìm kiếm
        //$users=User::paginate(20);//từ khóa tìm kiếm eloquent

        //echo 123; die();

        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config=$this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.language.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/user.php
        $config['seo']=__('messages.language');

        //Đổ dữ liệu User từ DB vào form theo mô hình service và repository
        $languagesIndex = $this->languageService->paginate($request);//$request để tiến hành chức năng tìm kiếm
        //dd($userCatalogues);

        $this->authorize('modules', 'language.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','languagesIndex'));
    }

    //giao diện thêm user
    public function store(){   
        $template='Backend.language.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.language.create');

        $config['method']='create';

        // //test nhanh việc lấy được dữ liệu hay không?
        // $location=[
        //     'province'=>$this->provinceRepository->all()
        // ];
        // dd($location);

       
        //dd($provinces);

        $this->authorize('modules', 'language.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config'));
    }

    //xử lý thêm user
    public function create(StoreLanguageRequest $request){
        if($this->languageService->createLanguage($request)){
            return redirect()->route('language.index')->with('success','Thêm mới ngôn ngữ thành công');
        }
           return redirect()->route('language.index')->with('error','Thêm mới ngôn ngữ thất bại. Hãy thử lại');
        
    }
    //giao diện sửa user
    public function edit($id){
        //echo $id;
        $template='Backend.language.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.language.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $language=$this->languageRepository->findById($id);
        //dd($user); die();

        $this->authorize('modules', 'language.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','language'));
    }
    //xử lý sửa user
    public function update($id, UpdateLanguageRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->languageService->updateLanguage($id, $request)){
            return redirect()->route('language.index')->with('success','Cập nhật ngôn ngữ thành công');
        }
           return redirect()->route('language.index')->with('error','Cập nhật ngôn ngữ thất bại. Hãy thử lại');
    }
    //giao diện xóa user
    public function destroy($id){
        $template='Backend.language.destroy';

        $config=$this->configCUD();

        $config['seo']=__('messages.language.delete');

        //truy vấn thông tin
        $language=$this->languageRepository->findById($id);
        //dd($user); die();

        $this->authorize('modules', 'language.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','language'));
    }
    //xử lý xóa user
    public function delete($id){
        //echo $id;
        if($this->languageService->deleteLanguage($id)){
            return redirect()->route('language.index')->with('success','Xóa ngôn ngữ thành công');
        }
           return redirect()->route('language.index')->with('error','Xóa ngôn ngữ thất bại. Hãy thử lại');
    }
    private function configIndex(){
        return[
            'js'=>[
                'Backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css'=>[
                'Backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'model'=>'Language'
        ];
    }

    private function configCUD(){
        return[
            'js'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'Backend/libary/location.js',
                'Backend/plugins/ckfinder/ckfinder.js',
                'Backend/libary/finder.js',
                'Backend/plugins/ckeditor/ckeditor.js',
                'Backend/libary/seo.js',
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }

    //Dịch hệ thống
    public function swithBackendLanguage($id){
        //echo $id; die();
        $language = $this->languageRepository->findById($id);
        if($this->languageService->switch($id)){
            session(['app_locale' => $language->canonical]);
            \App::setLocale($language->canonical);
        }
        return redirect()->back();
    }

    // Đổ dữ liệu vào giao diện cho translate
    public function translate($id = 0, $LanguageId = 0, $model = ''){
        //echo $id;

        //echo $LanguageId;

        $repositoryInstance = $this->repositoryInstance($model);

        // dd($repositoryInstance);

        //Kiểm tra khi người dùng chưa tạo bản dịch cho module danh mục mà đã muốn tạo bản dịch cho module chi tiết
        if(strpos($model, 'Catalogue') == false){

            // Lấy ra bảng module_catalogue_module tương ứng để biết được có bao nhiêu module_catalogue_id thuộc module_id đó
            $moduleCatalogueModuleInterfaceNamespace='\App\Models\\'.ucfirst($model).'Catalogue'.ucfirst($model);
            // dd($moduleCatalogueModuleInterfaceNamespace);
            if(class_exists($moduleCatalogueModuleInterfaceNamespace)){
                $moduleCatalogueModuleInstance=app($moduleCatalogueModuleInterfaceNamespace);
                // dd($moduleCatalogueModuleInstance);
            }
            
            // Kiểm tra và lấy danh sách module_catalogue_id thuộc module_id
            $module_id = lcfirst($model).'_id';
            $module_catalogue_id = lcfirst($model).'_catalogue_id';
            $moduleCatalogueIds = $moduleCatalogueModuleInstance->where($module_id, $id)->pluck($module_catalogue_id);
            // dd($moduleCatalogueIds);


            // Sau khi biết được những module_catalogue_id của module_id đang chọn thì tiến hành kiểm tra trong bảng module_catalogue_language xem thử
            // với module_catalogue_id đó đã có bản dịch theo language_id đang chọn hay chưa
            foreach($moduleCatalogueIds as $key => $val){
                $catalogueLanguageRepositoryInterfaceNamespace='\App\Repositories\\'.ucfirst($model).'CatalogueLanguageRepository';
                // dd($catalogueLanguageRepositoryInterfaceNamespace);
                if(class_exists($catalogueLanguageRepositoryInterfaceNamespace)){
                    $catalogueLanguageRepositoryInstance=app($catalogueLanguageRepositoryInterfaceNamespace);
                    // dd($catalogueLanguageRepositoryInstance);
                }
                
                $conditionFindModuleCatalogue=[
                    ['language_id', '=', $LanguageId],
                    [$module_catalogue_id, '=', $val]
                ];
                $flag = $catalogueLanguageRepositoryInstance->findByCondition($conditionFindModuleCatalogue);
                // dd($flag);
                if(!$flag){
                    $routeName = lcfirst($model).'.index';
                    return redirect()->route($routeName)->with('error','Vui lòng tạo bản dịch cho '.$model.' Catalogue trước');
                }
            }
        }

        $methodName = 'get'.$model.'ById';

        $languageInstance = $this->repositoryInstance('Language');

        $currentLanguage = $languageInstance->findByCondition([
            ['canonical', '=', session('app_locale')]
        ]);

        // KQ: Ta sẽ lấy được thông tin dữ liệu của post hoặc catalogue truyền vào file giao diện translate.blade.php
        $object = $repositoryInstance->{$methodName}($id, $currentLanguage->id);
        // dd($object);

        $objectTranslate = $repositoryInstance->{$methodName}($id, $LanguageId);
        // dd($objectTranslate);

        // Dùng để đưa những dữ liệu này vào request cho phương thức xử lý cập nhật bản dịch
        $option=[
            'id' => $id,
            'languageId' => $LanguageId,
            'model' => $model
        ];

        $template='Backend.language.translate';

        $config=$this->configCUD();

        $config['seo']=__('messages.translate.index');

        $this->authorize('modules', 'language.translate');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config', 'object', 'objectTranslate', 'option'));
    }

    // Lấy ra ra đúng repository tương ứng theo model
    private function repositoryInstance($model){
        $repositoryInterfaceNamespace='\App\Repositories\\'.ucfirst($model).'Repository';
        if(class_exists($repositoryInterfaceNamespace)){
            $repositoryInstance=app($repositoryInterfaceNamespace);
        }
        return $repositoryInstance ?? null;
    }

    // Xử lý cập nhật bản dịch
    public function storeTranslate(TranslateRequest $request){
        $option = $request->input('option');
        if($this->languageService->saveTranslate($option, $request)){
            return redirect()->back()->with('success', 'Cập nhật bản dịch thành công');
        }
           return redirect()->back()->with('error', 'Cập nhật bản dịch thất bại');
    }
}
