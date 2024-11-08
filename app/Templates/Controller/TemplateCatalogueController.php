<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//chèn thêm thư viện userInterface tự tạo để lấy thông tin user từ DB vào form
use App\Services\Interfaces\{ModuleTemplate}ServiceInterface as {ModuleTemplate}Service;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm user
use App\Http\Requests\Store{ModuleTemplate}Request;
//chèn thêm viện userRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\{ModuleTemplate}RepositoryInterface as {ModuleTemplate}Repository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit user
use App\Http\Requests\Update{ModuleTemplate}Request;
//use App\Models\User;
use App\Classes\Nestedsetbie;
use App\Http\Requests\Delete{ModuleTemplate}Request;
use App\Models\Language;


class {ModuleTemplate}Controller extends Controller
{
    protected ${moduleTemplate}Service;
    protected ${moduleTemplate}Repository;
    protected $nestedset;
    protected $language;//được lấy từ extends Controller

    public function __construct({ModuleTemplate}Service ${moduleTemplate}Service, {ModuleTemplate}Repository ${moduleTemplate}Repository){
        $this->{moduleTemplate}Service=${moduleTemplate}Service;//định nghĩa  $this->userService=$userCatalogueService để biến này nó có thể trỏ tới các phương tức của UserCatalogueService
        $this->{moduleTemplate}Repository=${moduleTemplate}Repository;
        
        $this->middleware(function($request, $next) {
            try {
                $locale = app()->getLocale(); // vn cn en
                $language = Language::where('canonical', $locale)->first();

                if (!$language) {
                    throw new \Exception('Vui lòng chọn ngôn ngữ trước khi truy cập.');
                }

                $this->language = $language->id;
                $this->initialize();
            } catch (\Exception $e) {
                return redirect()->route('dashboard.index')->with('error', $e->getMessage());
            }
            return $next($request);
        });
    }

    private function initialize(){
        $this->nestedset=new Nestedsetbie([
            'table'=>'{tableNames}',
            'foreignkey'=>'{foreignKey}',
            'language_id'=>$this->language,
        ]);
    }

    //giao diện tổng
    public function index(Request $request){//Request $request để tiến hành chức năng tìm kiếm
        //dd(session('app_locale'));

        //echo 123; die();

        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config=$this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.{moduleView}.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/user.php
        $config['seo']=__('messages.{moduleTemplate}');
        //dd($config['seo']);
        //Đổ dữ liệu User từ DB vào form theo mô hình service và repository
        ${moduleTemplate}s = $this->{moduleTemplate}Service->paginate($request, $this->language);//$request để tiến hành chức năng tìm kiếm
        //dd(${moduleTemplate}s);

        $this->authorize('modules', '{moduleView}.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','{moduleTemplate}s'));
    }

    //giao diện thêm user
    public function store(){   
        $template='Backend.{moduleView}.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.{moduleTemplate}.create');

        $config['method']='create';

        // //test nhanh việc lấy được dữ liệu hay không?
        // $location=[
        //     'province'=>$this->provinceRepository->all()
        // ];
        // dd($location);

       
        //dd($provinces);

        $dropdown= $this->nestedset->Dropdown();
        //dd($dropdown);

        $this->authorize('modules', '{moduleView}.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','dropdown'));
    }

    //xử lý thêm user
    public function create(Store{ModuleTemplate}Request $request){
        if($this->{moduleTemplate}Service->create{ModuleTemplate}($request, $this->language)){
            return redirect()->route('{moduleView}.index')->with('success','Thêm mới nhóm bào viết thành công');
        }
           return redirect()->route('{moduleView}.index')->with('error','Thêm mới nhóm bài viết thất bại. Hãy thử lại');
        
    }
    //giao diện sửa user
    public function edit($id){
        //echo $id;
        $template='Backend.{moduleView}.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.{moduleTemplate}.edit');//config('apps.{moduleTemplate}.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        ${moduleTemplate}=$this->{moduleTemplate}Repository->get{ModuleTemplate}ById($id,$this->language);

        if(!${moduleTemplate}){
            return redirect()->route('{moduleView}.index')->with('error', 'Nhóm bài viết này chưa có bản dịch của ngôn ngữ được chọn');
        }
        
        //dd(${moduleTemplate});

        $dropdown= $this->nestedset->Dropdown();

        $album = json_decode(${moduleTemplate}->album);

        $this->authorize('modules', '{moduleView}.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','{moduleTemplate}','dropdown','album'));
    }
    //xử lý sửa user
    public function update($id, Update{ModuleTemplate}Request $request){
        //echo $id; die();
        //dd($request);
        if($this->{moduleTemplate}Service->update{ModuleTemplate}($id, $request, $this->language)){
            return redirect()->route('{moduleView}.index')->with('success','Cập nhật nhóm bài viết thành công');
        }
           return redirect()->route('{moduleView}.index')->with('error','Cập nhật nhóm bài viết thất bại. Hãy thử lại');
    }
    //giao diện xóa user
    public function destroy($id){
        $template='Backend.{moduleView}.destroy';

        $config=$this->configCUD();

        $config['seo']=__('messages.{moduleTemplate}.delete');

        //truy vấn thông tin
        ${moduleTemplate}=$this->{moduleTemplate}Repository->get{ModuleTemplate}ById($id,$this->language);
        
        //dd(${moduleTemplate});

        $dropdown= $this->nestedset->Dropdown();

        $this->authorize('modules', '{moduleView}.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','{moduleTemplate}'));
    }
    //xử lý xóa user
    public function delete($id, Delete{ModuleTemplate}Request $request){
        //echo $id;
        //echo 123; die();
        if($this->{moduleTemplate}Service->delete{ModuleTemplate}($id, $this->language)){
            return redirect()->route('{moduleView}.index')->with('success','Xóa nhóm bài viết thành công');
        }
           return redirect()->route('{moduleView}.index')->with('error','Xóa nhóm bài viết thất bại. Hãy thử lại');
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
            'model'=>'{ModuleTemplate}'
        ];
    }

    private function configCUD(){
        return[
            'js'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
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

}
