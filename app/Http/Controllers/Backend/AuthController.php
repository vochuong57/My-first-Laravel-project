<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;//dùng để kiểm tra khi logout
//thêm thư viện hiện ra thông báo lỗi bằng tiếng việt (tự làm)
use App\Http\Requests\AuthRequest;
//thêm thư viện kiểm tra đăng nhập
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(){
        
    }

    public function index(){//giao diện
        //echo 123456; die(); //dd(Auth::id());
        // if(Auth::id()>0){//kiểm tra nếu auth đã có ít nhất 1 id thì lập tức về lại luôn trang quản trị
        //     return redirect()->route('dashboard.index');
        // }
        return view('Backend.auth.login');//Giao diện được lấy qua từ folder view
    }

    public function login(AuthRequest $request){//tiến hành kiểm tra validate
        $credentials=['email'=>$request->input('email'), 'password'=>$request->input('password')];//sau khi pass validate tiến hành xác thực tài khoản. Tìm authentication -> Login Throttling
        if(Auth::attempt($credentials)){
            return redirect()->route('dashboard.index')->with('success','Đăng nhập thành công');
        }
        else{
            return redirect()->route('auth.admin')->with('error','Email hoặc mật khẩu không chính xác');
        }
    }

    public function logout(Request $request){//từ khóa tìm kiếm Authentication -> Logging out
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.admin');
    }
}
