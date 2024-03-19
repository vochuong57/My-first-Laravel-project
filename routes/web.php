<?php

use Illuminate\Support\Facades\Route;
//chèm thêm thêm thư viện từ tạo từ lớp controller/backend
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
//chèn thêm thư viện để kiểm tra việc đã đăng xuất (tự làm)
use App\Http\Middleware\AuthenticateMiddleware;
//chèn thêm thư viện để kiểm tra việc vẫn còn đăng nhập (tự làm)
use App\Http\Middleware\LoginMiddleware;

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
//AuthController
Route::get('admin',[AuthController::class, 'index'])->name('auth.admin')->middleware(LoginMiddleware::class);//hiển thị form đăng nhập (giao diện)
Route::post('login',[AuthController::class, 'login'])->name('auth.login');//dùng trong action form login.blade.php (xử lý)
Route::get('logout',[AuthController::class, 'logout'])->name('auth.logout');//dùng để logout (xử lý)

//DashboardController
Route::get('dashboard/index',[DashboardController::class, 'index'])->name('dashboard.index')->middleware(AuthenticateMiddleware::class);//hiển thị form dashboard khi đăng nhập thành công