<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//chèn thêm thư viện userInterface tự tạo để lấy thông tin user từ DB vào form
use App\Services\Interfaces\PermissionServiceInterface as PermissionService;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi thêm user
use App\Http\Requests\StorePermissionRequest;
//chèn thêm viện userRepositoryInterface để lấy function findById để truy xuất dữ liệu của id vừa nhập
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;
//chèn thêm thư viện tự tạo request để kiểm tra dữ liệu đầu vào khi edit user
use App\Http\Requests\UpdatePermissionRequest;
//use App\Models\User;


class PermissionController extends Controller
{
    protected $permissionService;
    protected $permissionRepository;

    public function __construct(PermissionService $permissionService, PermissionRepository $permissionRepository){
        $this->permissionService=$permissionService;//định nghĩa  $this->userService=$userCatalogueService để biến này nó có thể trỏ tới các phương tức của UserCatalogueService
        $this->permissionRepository=$permissionRepository;
    }
    //giao diện tổng
    public function index(Request $request){//Request $request để tiến hành chức năng tìm kiếm
        //$users=User::paginate(20);//từ khóa tìm kiếm eloquent

        //echo 123; die();

        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config=$this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.permission.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/user.php
        $config['seo']=__('messages.permission');

        //Đổ dữ liệu User từ DB vào form theo mô hình service và repository
        $permissions = $this->permissionService->paginate($request);//$request để tiến hành chức năng tìm kiếm
        //dd($userCatalogues);

        $this->authorize('modules', 'permission.index');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','permissions'));
    }

    //giao diện thêm user
    public function store(){   
        $template='Backend.permission.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.permission.create');

        $config['method']='create';

        // //test nhanh việc lấy được dữ liệu hay không?
        // $location=[
        //     'province'=>$this->provinceRepository->all()
        // ];
        // dd($location);

       
        //dd($provinces);

        $this->authorize('modules', 'permission.store');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config'));
    }

    //xử lý thêm user
    public function create(StorePermissionRequest $request){
        if($this->permissionService->createPermission($request)){
            return redirect()->route('permission.index')->with('success','Thêm mới quyền thành công');
        }
           return redirect()->route('permission.index')->with('error','Thêm mới quyền thất bại. Hãy thử lại');
        
    }
    //giao diện sửa user
    public function edit($id){
        //echo $id;
        $template='Backend.permission.store';

        $config=$this->configCUD();

        $config['seo']=__('messages.permission.edit');

        $config['method']='edit';//kiểm tra metho để thay đổi giao diện cho phù hợp

        //truy vấn thông tin
        $permission=$this->permissionRepository->findById($id);
        //dd($user); die();

        $this->authorize('modules', 'permission.edit');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','permission'));
    }
    //xử lý sửa user
    public function update($id, UpdatePermissionRequest $request){
        //echo $id; die();
        //dd($request);
        if($this->permissionService->updatePermission($id, $request)){
            return redirect()->route('permission.index')->with('success','Cập nhật quyền thành công');
        }
           return redirect()->route('permission.index')->with('error','Cập nhật quyền thất bại. Hãy thử lại');
    }
    //giao diện xóa user
    public function destroy($id){
        $template='Backend.permission.destroy';

        $config=$this->configCUD();

        $config['seo']=__('messages.permission.delete');

        //truy vấn thông tin
        $permission=$this->permissionRepository->findById($id);
        //dd($user); die();

        $this->authorize('modules', 'permission.destroy');//phân quyền

        return view('Backend.dashboard.layout', compact('template','config','permission'));
    }
    //xử lý xóa user
    public function delete($id){
        //echo $id;
        if($this->permissionService->deletePermission($id)){
            return redirect()->route('permission.index')->with('success','Xóa quyền thành công');
        }
           return redirect()->route('permission.index')->with('error','Xóa quyền thất bại. Hãy thử lại');
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
            'model'=>'Permission'
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
