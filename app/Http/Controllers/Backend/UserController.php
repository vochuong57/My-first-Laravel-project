<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//chèn thêm thư viện userInterface tự tạo
use App\Services\Interfaces\UserServiceInterface as UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService){
        $this->userService=$userService;
    }
    public function index(){
        $users = $this->userService->paginate();
        //$users=User::paginate(15);//từ khóa tìm kiếm eloquent

        $config=$this->config();
        $template='Backend.user.index';
        return view('Backend.dashboard.layout', compact('template','config','users'));
    }

    private function config(){
        return[
            'js'=>[
                'Backend/js/plugins/switchery/switchery.js'
            ],
            'css'=>[
                'Backend/css/plugins/switchery/switchery.css'
            ]
        ];
    }
}
