<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
//chèn thêm thư viện userInterface tự tạo để lấy thông tin user từ DB vào form
use App\Services\Interfaces\UserServiceInterface as UserService;
//chèn thêm tự viện ProvinceServiceInterface tự tạo để lấy thông tin province từ DB vào form
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm user
use App\Http\Requests\StoreUserRequest;
//chèn thêm viện userRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit user
use App\Http\Requests\UpdateUserRequest;
//use App\Models\User;
//chén thêm thư viện của userCatalogueRepository để lấy thông tin nhóm thành viên cho form thêm
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;



class UserController extends Controller
{
    protected $userService;
    protected $provinceRepository;
    protected $userRepository;
    protected $userCatalogueRepository;

    public function __construct(UserService $userService, ProvinceRepository $provinceRepository, UserRepository $userRepository, UserCatalogueRepository $userCatalogueRepository){
        $this->userService=$userService;//định nghĩa  $this->userService=$userService để biến này nó có thể trỏ tới các phương tức của UserService
        $this->provinceRepository=$provinceRepository;
        $this->userRepository=$userRepository;
        $this->userCatalogueRepository=$userCatalogueRepository;
    }
    //giao diện tổng
    public function index(Request $request){//Request $request để tiến hành chức năng tìm kiếm
        //$users=User::paginate(20);//từ khóa tìm kiếm eloquent

        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config = $this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.user.user.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/user.php
        $config['seo'] = config('apps.user.index');

        //Đổ dữ liệu User từ DB vào form theo mô hình service và repository
        $users = $this->userService->paginate($request);//$request để tiến hành chức năng tìm kiếm

        $userCatalogues=$this->userCatalogueRepository->all();

        return view('Backend.dashboard.layout', compact('template','config','users','userCatalogues'));
    }

    //giao diện thêm user
    public function store(){   
        $template='Backend.user.user.store';

        $config=$this->configCUD();

        $config['seo']=config('apps.user.create');

        $config['method']='create';

        // //test nhanh việc lấy được dữ liệu hay không?
        // $location=[
        //     'province'=>$this->provinceRepository->all()
        // ];
        // dd($location);

        //lấy dữ liệu địa chỉ địa lý VN
        $provinces=$this->provinceRepository->all();
        //dd($provinces);

        $userCatalogues=$this->userCatalogueRepository->all();

        return view('Backend.dashboard.layout', compact('template','config','provinces','userCatalogues'));
    }

    //xử lý thêm user
    public function create(StoreUserRequest $request){
        if($this->userService->createUser($request)){
            return redirect()->route('user.index')->with('success','Thêm mới thành viên thành công');
        }
           return redirect()->route('user.index')->with('error','Thêm mới thành viên thất bại. Hãy thử lại');
        
    }
    //giao diện sửa user
    public function edit($id){
        //echo $id;
        $template='Backend.user.user.store';

        $config=$this->configCUD();

        $config['seo']=config('apps.user.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        $provinces=$this->provinceRepository->all();
        //dd($provinces);

        //truy vấn thông tin
        $user=$this->userRepository->findById($id);
        //dd($user); die();

        $userCatalogues=$this->userCatalogueRepository->all();

        return view('Backend.dashboard.layout', compact('template','config','provinces','user', 'userCatalogues'));
    }
    //xử lý sửa user
    public function update($id, UpdateUserRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->userService->updateUser($id, $request)){
            return redirect()->route('user.index')->with('success','Cập nhật thành viên thành công');
        }
           return redirect()->route('user.index')->with('error','Cập nhật thành viên thất bại. Hãy thử lại');
    }
    //giao diện xóa user
    public function destroy($id){
        $template='Backend.user.user.destroy';

        $config=$this->configCUD();

        $config['seo']=config('apps.user.delete');

        //truy vấn thông tin
        $user=$this->userRepository->findById($id);
        //dd($user); die();

        return view('Backend.dashboard.layout', compact('template','config','user'));
    }
    //xử lý xóa user
    public function delete($id){
        //echo $id;
        if($this->userService->deleteUser($id)){
            return redirect()->route('user.index')->with('success','Xóa thành viên thành công');
        }
           return redirect()->route('user.index')->with('error','Xóa thành viên thất bại. Hãy thử lại');
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
                'Backend/plugins/ckfinder/ckfinder.js',
                'Backend/libary/finder.js'
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }

}
