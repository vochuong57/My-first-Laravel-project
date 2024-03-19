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
        $config['seo']=config('apps.language.index');

        //Đổ dữ liệu User từ DB vào form theo mô hình service và repository
        $languages = $this->languageService->paginate($request);//$request để tiến hành chức năng tìm kiếm
        //dd($userCatalogues);
        return view('Backend.dashboard.layout', compact('template','config','languages'));
    }

    //giao diện thêm user
    public function store(){   
        $template='Backend.language.store';

        $config=$this->configCUD();

        $config['seo']=config('apps.language.create');

        $config['method']='create';

        // //test nhanh việc lấy được dữ liệu hay không?
        // $location=[
        //     'province'=>$this->provinceRepository->all()
        // ];
        // dd($location);

       
        //dd($provinces);

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

        $config['seo']=config('apps.language.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $language=$this->languageRepository->findById($id);
        //dd($user); die();

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

        $config['seo']=config('apps.language.delete');

        //truy vấn thông tin
        $language=$this->languageRepository->findById($id);
        //dd($user); die();

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
                'Backend/libary/finder.js'
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }

    public function swithBackendLanguage($id){
        //echo $id; die();
        $language = $this->languageRepository->findById($id);
        if($this->languageService->switch($id)){
            session(['app_locale' => $language->canonical]);
            \App::setLocale($language->canonical);
        }
        return redirect()->back();
    }
}
