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
use App\Http\Controllers\Backend\UserCatalogueController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\PostCatalogueController;


//thư viện AJAX
use App\Http\Controllers\Ajax\LocationController;
use App\Http\Controllers\Ajax\DashboardController as AjaxDashboardController;
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
    
    Route::get('store',[UserController::class, 'store'])->name('user.store')->middleware(AuthenticateMiddleware::class);//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('create',[UserController::class, 'create'])->name('user.create')->middleware(AuthenticateMiddleware::class);//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    
    Route::get('{id}/edit',[UserController::class, 'edit'])->name('user.edit')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('{id}/update',[UserController::class, 'update'])->name('user.update')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

    Route::get('{id}/destroy',[UserController::class, 'destroy'])->name('user.destroy')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('{id}/delete',[UserController::class, 'delete'])->name('user.delete')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
});

// AJAX get để sử dụng cơ sở dữ liệu và post để thay đổi cơ sở dữ liệu
Route::get('ajax/location/getLocation',[LocationController::class, 'getLocation'])->name('ajax.location.getLocation')->middleware(AuthenticateMiddleware::class);//Trang thực thi chuyển đổi ... đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
Route::post('ajax/dashboard/changeStatus',[AjaxDashboardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus')->middleware(AuthenticateMiddleware::class);//Trang thực thi chuyển đổi ... đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
Route::post('ajax/dashboard/changeStatusAll',[AjaxDashboardController::class, 'changeStatusAll'])->name('ajax.dashboard.changeStatusAll')->middleware(AuthenticateMiddleware::class);//Trang thực thi chuyển đổi ... đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
Route::post('ajax/dashboard/deleteAll',[AjaxDashboardController::class, 'deleteAll'])->name('ajax.dashboard.deleteAll')->middleware(AuthenticateMiddleware::class);//Trang thực thi chuyển đổi ... đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

//UserCatalogueController (Trang quản lí nhóm người dùng)
Route::group(['prefix'=>'user/catalogue'], function(){
    Route::get('index',[UserCatalogueController::class, 'index'])->name('user.catalogue.index')->middleware(AuthenticateMiddleware::class);//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    
    Route::get('store',[UserCatalogueController::class, 'store'])->name('user.catalogue.store')->middleware(AuthenticateMiddleware::class);//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('create',[UserCatalogueController::class, 'create'])->name('user.catalogue.create')->middleware(AuthenticateMiddleware::class);//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    
    Route::get('{id}/edit',[UserCatalogueController::class, 'edit'])->name('user.catalogue.edit')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('{id}/update',[UserCatalogueController::class, 'update'])->name('user.catalogue.update')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

    Route::get('{id}/destroy',[UserCatalogueController::class, 'destroy'])->name('user.catalogue.destroy')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('{id}/delete',[UserCatalogueController::class, 'delete'])->name('user.catalogue.delete')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
});


//LanguageController (Trang quản lí ngôn ngữ)
Route::group(['prefix'=>'language'], function(){
    Route::get('index',[LanguageController::class, 'index'])->name('language.index')->middleware(AuthenticateMiddleware::class);//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    
    Route::get('store',[LanguageController::class, 'store'])->name('language.store')->middleware(AuthenticateMiddleware::class);//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('create',[LanguageController::class, 'create'])->name('language.create')->middleware(AuthenticateMiddleware::class);//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    
    Route::get('{id}/edit',[LanguageController::class, 'edit'])->name('language.edit')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('{id}/update',[LanguageController::class, 'update'])->name('language.update')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

    Route::get('{id}/destroy',[LanguageController::class, 'destroy'])->name('language.destroy')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('{id}/delete',[LanguageController::class, 'delete'])->name('language.delete')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
});


//PostCatalogueController (Trang quản lí nhóm bài viết)
Route::group(['prefix'=>'post/catalogue'], function(){
    Route::get('index',[PostCatalogueController::class, 'index'])->name('post.catalogue.index')->middleware(AuthenticateMiddleware::class);//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    
    Route::get('store',[PostCatalogueController::class, 'store'])->name('post.catalogue.store')->middleware(AuthenticateMiddleware::class);//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('create',[PostCatalogueController::class, 'create'])->name('post.catalogue.create')->middleware(AuthenticateMiddleware::class);//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    
    Route::get('{id}/edit',[PostCatalogueController::class, 'edit'])->name('post.catalogue.edit')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('{id}/update',[PostCatalogueController::class, 'update'])->name('post.catalogue.update')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

    Route::get('{id}/destroy',[PostCatalogueController::class, 'destroy'])->name('post.catalogue.destroy')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('{id}/delete',[PostCatalogueController::class, 'delete'])->name('post.catalogue.delete')->where(['id'=>'[0-9]+'])->middleware(AuthenticateMiddleware::class);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
});
