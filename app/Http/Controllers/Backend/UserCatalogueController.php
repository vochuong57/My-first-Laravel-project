<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//chèn thêm thư viện userInterface tự tạo để lấy thông tin user từ DB vào form
use App\Services\Interfaces\UserCatalogueServiceInterface as UserCatalogueService;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm user
use App\Http\Requests\StoreUserCatalogueRequest;
//chèn thêm viện userRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit user
use App\Http\Requests\UpdateUserCatalogueRequest;
//use App\Models\User;


class UserCatalogueController extends Controller
{
    protected $userCatalogueService;
    protected $userCatalogueRepository;

    public function __construct(UserCatalogueService $userCatalogueService, UserCatalogueRepository $userCatalogueRepository){
        $this->userCatalogueService=$userCatalogueService;//định nghĩa  $this->userService=$userCatalogueService để biến này nó có thể trỏ tới các phương tức của UserCatalogueService
        $this->userCatalogueRepository=$userCatalogueRepository;
    }
    //giao diện tổng
    public function index(Request $request){//Request $request để tiến hành chức năng tìm kiếm
        //$users=User::paginate(20);//từ khóa tìm kiếm eloquent

        //echo 123; die();

        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config=$this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.user.catalogue.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/user.php
        $config['seo']=config('apps.userCatalogue.index');

        //Đổ dữ liệu User từ DB vào form theo mô hình service và repository
        $userCatalogues = $this->userCatalogueService->paginate($request);//$request để tiến hành chức năng tìm kiếm
        //dd($userCatalogues);
        return view('Backend.dashboard.layout', compact('template','config','userCatalogues'));
    }

    //giao diện thêm user
    public function store(){   
        $template='Backend.user.catalogue.store';

        $config=$this->configCUD();

        $config['seo']=config('apps.userCatalogue.create');

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
    public function create(StoreUserCatalogueRequest $request){
        if($this->userCatalogueService->createUserCatalogue($request)){
            return redirect()->route('user.catalogue.index')->with('success','Thêm mới nhóm thành viên thành công');
        }
           return redirect()->route('user.catalogue.index')->with('error','Thêm mới nhóm thành viên thất bại. Hãy thử lại');
        
    }
    //giao diện sửa user
    public function edit($id){
        //echo $id;
        $template='Backend.user.catalogue.store';

        $config=$this->configCUD();

        $config['seo']=config('apps.user.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $userCatalogue=$this->userCatalogueRepository->findById($id);
        //dd($user); die();

        return view('Backend.dashboard.layout', compact('template','config','userCatalogue'));
    }
    //xử lý sửa user
    public function update($id, UpdateUserCatalogueRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->userCatalogueService->updateUserCatalogue($id, $request)){
            return redirect()->route('user.catalogue.index')->with('success','Cập nhật nhóm thành viên thành công');
        }
           return redirect()->route('user.catalogue.index')->with('error','Cập nhật nhóm thành viên thất bại. Hãy thử lại');
    }
    //giao diện xóa user
    public function destroy($id){
        $template='Backend.user.catalogue.destroy';

        $config=$this->configCUD();

        $config['seo']=config('apps.user.delete');

        //truy vấn thông tin
        $userCatalogue=$this->userCatalogueRepository->findById($id);
        //dd($user); die();

        return view('Backend.dashboard.layout', compact('template','config','userCatalogue'));
    }
    //xử lý xóa user
    public function delete($id){
        //echo $id;
        if($this->userCatalogueService->deleteUserCatalogue($id)){
            return redirect()->route('user.catalogue.index')->with('success','Xóa nhóm thành viên thành công');
        }
           return redirect()->route('user.catalogue.index')->with('error','Xóa nhóm thành viên thất bại. Hãy thử lại');
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
            ]
        ];
    }

    private function configCUD(){
        return[
            'js'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'Backend/libary/location.js',
                'Backend/plugin/ckfinder/ckfinder.js',
                'Backend/libary/finder.js'
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }

}
