<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
//chèn thêm thư viện có sẵn Request để lấy thông tin value của input mà ajax đã thiết lập
use Illuminate\Http\Request;
//thêm thư viện tự tạo
use App\Services\Interfaces\UserServiceInterface as UserService;


class DashboardController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService){
       $this->userService=$userService;
    }

    public function changeStatus(Request $request){
        //echo 123; die();
        $post=$request->input();
        //dd($post);
        $serviceInterfaceNamespace='\App\Services\\'.ucfirst($post['model']).'Service';
        if(class_exists($serviceInterfaceNamespace)){
            $serviceInstance=app($serviceInterfaceNamespace);
        }
        $flag=$serviceInstance->updateStatus($post);

        //$flag=$this->userService->updateStatus($post);

        return response()->json(['flag'=>$flag]);
    }
    public function changeStatusAll(Request $request){
        $post=$request->input();
        $serviceInterfaceNamespace='\App\Services\\'.ucfirst($post['model']).'Service';
        if(class_exists($serviceInterfaceNamespace)){
            $serviceInstance=app($serviceInterfaceNamespace);
        }
        $flag=$serviceInstance->updateStatusAll($post);
        return response()->json(['flag'=>$flag]);
    }
    public function deleteAll(Request $request){
        $post=$request->input();
        //dd($post);
        $serviceInterfaceNamespace='\App\Services\\'.ucfirst($post['model']).'Service';
        if(class_exists($serviceInterfaceNamespace)){
            $serviceInstance=app($serviceInterfaceNamespace);
        }
        $flag=$serviceInstance->deleteAll($post);
        return response()->json(['flag'=>$flag]);
    }
    public function renderHTML(){

    }
}