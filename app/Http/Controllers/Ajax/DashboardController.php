<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
//chèn thêm thư viện có sẵn Request để lấy thông tin value của input mà ajax đã thiết lập
use Illuminate\Http\Request;
//thêm thư viện tự tạo
use App\Services\Interfaces\UserServiceInterface as UserService;
use App\Models\Language;


class DashboardController extends Controller
{
    protected $userService;
    protected $language;

    public function __construct(UserService $userService){
        $this->userService=$userService;

        $this->middleware(function($request, $next) {
            $locale = app()->getLocale(); // vn cn en
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
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

    // V63
    public function getMenu(Request $request){
        $model = $request->input('model');
        // dd($model);
        $serviceInterfaceNamespace='\App\Services\\'.ucfirst($model).'Service';
        if(class_exists($serviceInterfaceNamespace)){
            $serviceInstance=app($serviceInterfaceNamespace);
        }
        $object = $serviceInstance->paginate($request, $this->language);
        // dd($object);
        return response()->json($object);
    }
}
