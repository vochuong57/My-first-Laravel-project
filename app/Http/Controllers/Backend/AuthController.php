<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;//dùng để xử lí khi logout
//thêm thư viện hiện ra thông báo lỗi bằng tiếng việt (tự làm)
use App\Http\Requests\AuthRequest;
//thêm thư viện kiểm tra đăng nhập
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;

class AuthController extends Controller
{

    protected $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository=$userRepository;
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
        
        $email = $request->input('email');
        $condition=[
            ['email', '=', $email]
        ];
        $user=$this->userRepository->findByCondition($condition);
        //dd($user);
        if($user->publish == 1){
            return redirect()->route('auth.admin')->with('error','Tài khoản này đã bị cấm');
        }
        else if(Auth::attempt($credentials)){
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
