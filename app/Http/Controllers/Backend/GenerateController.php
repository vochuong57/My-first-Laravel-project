<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//chèn thêm thư viện userInterface tự tạo để lấy thông tin user từ DB vào form
use App\Services\Interfaces\GenerateServiceInterface as GenerateService;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm user
use App\Http\Requests\StoreGenerateRequest;
//chèn thêm viện userRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\GenerateRepositoryInterface as GenerateRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit user
use App\Http\Requests\UpdateGenerateRequest;
//use App\Models\User;
use App\Http\Requests\TranslateRequest;


class GenerateController extends Controller
{
    protected $generateService;
    protected $generateRepository;

    public function __construct(GenerateService $generateService, GenerateRepository $generateRepository){
        $this->generateService=$generateService;//định nghĩa  $this->userService=$userCatalogueService để biến này nó có thể trỏ tới các phương tức của UserCatalogueService
        $this->generateRepository=$generateRepository;
    }
    //giao diện tổng
    public function index(Request $request){//Request $request để tiến hành chức năng tìm kiếm
        //$users=User::paginate(20);//từ khóa tìm kiếm eloquent

        //echo 123; die();

        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config=$this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.generate.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/user.php
        $config['seo']=__('messages.generate');

        //Đổ dữ liệu User từ DB vào form theo mô hình service và repository
        $generates = $this->generateService->paginate($request);//$request để tiến hành chức năng tìm kiếm
        //dd($userCatalogues);

        $this->authorize('modules', 'generate.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','generates'));
    }

    //giao diện thêm user
    public function store(){   
        $template='Backend.generate.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.generate.create');

        $config['method']='create';

        // //test nhanh việc lấy được dữ liệu hay không?
        // $location=[
        //     'province'=>$this->provinceRepository->all()
        // ];
        // dd($location);

       
        //dd($provinces);

        $this->authorize('modules', 'generate.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config'));
    }

    //xử lý thêm user
    public function create(StoreGenerateRequest $request){
        if($this->generateService->createGenerate($request)){
            return redirect()->route('generate.index')->with('success','Thêm mới module thành công');
        }
           return redirect()->route('generate.index')->with('error','Thêm mới module thất bại. Hãy thử lại');
        
    }
    //giao diện sửa user
    public function edit($id){
        //echo $id;
        $template='Backend.generate.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.generate.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $generate=$this->generateRepository->findById($id);
        //dd($user); die();

        $this->authorize('modules', 'generate.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','generate'));
    }
    //xử lý sửa user
    public function update($id, UpdateGenerateRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->generateService->updateGenerate($id, $request)){
            return redirect()->route('generate.index')->with('success','Cập nhật ngôn ngữ thành công');
        }
           return redirect()->route('generate.index')->with('error','Cập nhật ngôn ngữ thất bại. Hãy thử lại');
    }
    //giao diện xóa user
    public function destroy($id){
        $template='Backend.generate.destroy';

        $config=$this->configCUD();

        $config['seo']=__('messages.generate.delete');

        //truy vấn thông tin
        $generate=$this->generateRepository->findById($id);
        //dd($user); die();

        $this->authorize('modules', 'generate.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','generate'));
    }
    //xử lý xóa user
    public function delete($id){
        //echo $id;
        if($this->generateService->deleteGenerate($id)){
            return redirect()->route('generate.index')->with('success','Xóa ngôn ngữ thành công');
        }
           return redirect()->route('generate.index')->with('error','Xóa ngôn ngữ thất bại. Hãy thử lại');
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
            'model'=>'Generate'
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
}
