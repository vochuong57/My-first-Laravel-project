<?php

use Illuminate\Support\Facades\Route;
//chèm thêm thêm thư viện tự tạo từ lớp controller/backend
use App\Http\Controllers\Backend\AuthController;//dùng để hiện forrm login và xử lí.
use App\Http\Controllers\Backend\DashboardController;//dùng để hiển thị trang admin khi đăng nhập thành công

//chèm thêm thêm thư viện tự tạo từ lớp Middleware
use App\Http\Middleware\AuthenticateMiddleware;//kiểm tra việc đã đăng xuất (tự làm)
use App\Http\Middleware\LoginMiddleware;//kiểm tra việc vẫn còn đăng nhập (tự làm)

// chèn thêm thư viện tự tạo
use App\Http\Controllers\Backend\UserController;//hiện thị trang người dùng và xử lí

use App\Http\Controllers\Ajax\LocationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//định nghĩa được một cái đưuòng dẫn và đưa cái đường dẫn đó vào route tương ứng
//chạy nó bằng http://127.0.0.1:8000/login

//AuthController (Trang đăng nhập)
Route::get('admin',[AuthController::class, 'index'])->name('auth.admin')->middleware(LoginMiddleware::class);//Trang hiển thị form đăng nhập (giao diện) | Nếu ở đây mà người dùng đã đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('dashboard.index')
Route::post('login',[AuthController::class, 'login'])->name('auth.login');//Trang xử lí dùng trong action form login.blade.php (xử lý nhập liệu, kiểm tra dữ liệu và truy cập tới trang admin)
Route::get('logout',[AuthController::class, 'logout'])->name('auth.logout');//Trang hiển thị dùng để logout (xử lý việc huỷ dữ liệu Auth và chuyển trang về auth.admin)

//DashboardController (Trang chủ khi đăng nhập thành công)
Route::get('dashboard/index',[DashboardController::class, 'index'])->name('dashboard.index')->middleware(AuthenticateMiddleware::class);//hiển thị form dashboard khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

//UserController (Trang quản lí người dùng)
Route::group(['prefix'=>'user'], function(){
    Route::get('index',[UserController::class, 'index'])->name('user.index')->middleware(AuthenticateMiddleware::class);//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::get('create',[UserController::class, 'create'])->name('user.create')->middleware(AuthenticateMiddleware::class);//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('store',[UserController::class, 'store'])->name('user.store')->middleware(AuthenticateMiddleware::class);//thực thi form xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::get('update',[UserController::class, 'update'])->name('user.update')->middleware(AuthenticateMiddleware::class);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::get('destroy',[UserController::class, 'destroy'])->name('user.destroy')->middleware(AuthenticateMiddleware::class);//Thực thi form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
});

// AJAX
Route::get('ajax/location/getLocation',[LocationController::class, 'getLocation'])->name('ajax.location.index')->middleware(AuthenticateMiddleware::class);//Trang thực thi chuyển đổi ... đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

