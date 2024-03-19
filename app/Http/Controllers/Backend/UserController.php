<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//chèn thêm thư viện userInterface tự tạo để lấy thông tin user từ DB vào form
use App\Services\Interfaces\UserServiceInterface as UserService;
//chèn thêm tự viện ProvinceServiceInterface tự tạo để lấy thông tin province từ DB vào form
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;

class UserController extends Controller
{
    protected $userService;
    protected $provinceRepository;

    public function __construct(UserService $userService, ProvinceRepository $provinceRepository){
        $this->userService=$userService;//định nghĩa  $this->userService=$userService để biến này nó có thể trỏ tới các phương tức của UserService
        $this->provinceRepository=$provinceRepository;
    }
    public function index(){
        //$users=User::paginate(15);//từ khóa tìm kiếm eloquent

        //Lấy dữ liệu các mảng là các đường dẫn js và css ở function config phía dưới lưu vào biến $config
        $config=$this->configIndex();

        //biến template là nới lưu đường dẫn main của từng dao diện
        $template='Backend.user.index';

        //chèn thêm mảng 'seo' vào biến config để mảng 'seo' này lấy toàn bộ giá trị của folder config/apps/user.php
        $config['seo']=config('apps.user');

        //Đổ dữ liệu User từ DB vào form theo mô hình service và repository
        $users = $this->userService->paginate();

        return view('Backend.dashboard.layout', compact('template','config','users'));
    }

    
    public function create(){   
        $template='Backend.user.create';

        $config=$this->configCreate();

        $config['seo']=config('apps.user');

        // //test nhanh việc lấy được dữ liệu hay không?
        // $location=[
        //     'province'=>$this->provinceRepository->all()
        // ];
        // dd($location);

        //lấy dữ liệu địa chỉ địa lý VN
        $provinces=$this->provinceRepository->all();
        //dd($provinces);

        return view('Backend.dashboard.layout', compact('template','config','provinces'));
    }


    private function configIndex(){
        return[
            'js'=>[
                'Backend/js/plugins/switchery/switchery.js'
            ],
            'css'=>[
                'Backend/css/plugins/switchery/switchery.css'
            ]
        ];
    }

    private function configCreate(){
        return[
            'js'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'Backend/libary/location.js'
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }

}
