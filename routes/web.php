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
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\Backend\PermissionController;


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

Route::group(['middleware' => ['admin','locale']], function (){
    //DashboardController (Trang chủ khi đăng nhập thành công)
    Route::get('dashboard/index',[DashboardController::class, 'index'])->name('dashboard.index');//hiển thị form dashboard khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

    //UserController (Trang quản lí người dùng)
    Route::group(['prefix'=>'user'], function(){
        Route::get('index',[UserController::class, 'index'])->name('user.index');//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('store',[UserController::class, 'store'])->name('user.store');//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('create',[UserController::class, 'create'])->name('user.create');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('{id}/edit',[UserController::class, 'edit'])->name('user.edit')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/update',[UserController::class, 'update'])->name('user.update')->where(['id'=>'[0-9]+']);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/destroy',[UserController::class, 'destroy'])->name('user.destroy')->where(['id'=>'[0-9]+']);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/delete',[UserController::class, 'delete'])->name('user.delete')->where(['id'=>'[0-9]+']);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    });

    // AJAX get để sử dụng cơ sở dữ liệu và post để thay đổi cơ sở dữ liệu
    Route::get('ajax/location/getLocation',[LocationController::class, 'getLocation'])->name('ajax.location.getLocation');//Trang thực thi chuyển đổi ... đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('ajax/dashboard/changeStatus',[AjaxDashboardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus');//Trang thực thi chuyển đổi ... đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('ajax/dashboard/changeStatusAll',[AjaxDashboardController::class, 'changeStatusAll'])->name('ajax.dashboard.changeStatusAll');//Trang thực thi chuyển đổi ... đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('ajax/dashboard/deleteAll',[AjaxDashboardController::class, 'deleteAll'])->name('ajax.dashboard.deleteAll');//Trang thực thi chuyển đổi ... đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

    //UserCatalogueController (Trang quản lí nhóm người dùng)
    Route::group(['prefix'=>'user/catalogue'], function(){
        Route::get('index',[UserCatalogueController::class, 'index'])->name('user.catalogue.index');//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('store',[UserCatalogueController::class, 'store'])->name('user.catalogue.store');//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('create',[UserCatalogueController::class, 'create'])->name('user.catalogue.create');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('{id}/edit',[UserCatalogueController::class, 'edit'])->name('user.catalogue.edit')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/update',[UserCatalogueController::class, 'update'])->name('user.catalogue.update')->where(['id'=>'[0-9]+']);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/destroy',[UserCatalogueController::class, 'destroy'])->name('user.catalogue.destroy')->where(['id'=>'[0-9]+']);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/delete',[UserCatalogueController::class, 'delete'])->name('user.catalogue.delete')->where(['id'=>'[0-9]+']);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    
        Route::get('permission',[UserCatalogueController::class, 'permission'])->name('user.catalogue.permission')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('updatePermission',[UserCatalogueController::class, 'updatePermission'])->name('user.catalogue.updatePermission')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    });


    //LanguageController (Trang quản lí ngôn ngữ)
    Route::group(['prefix'=>'language'], function(){
        Route::get('index',[LanguageController::class, 'index'])->name('language.index')->middleware(['admin', 'locale']);//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('store',[LanguageController::class, 'store'])->name('language.store');//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('create',[LanguageController::class, 'create'])->name('language.create');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('{id}/edit',[LanguageController::class, 'edit'])->name('language.edit')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/update',[LanguageController::class, 'update'])->name('language.update')->where(['id'=>'[0-9]+']);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/destroy',[LanguageController::class, 'destroy'])->name('language.destroy')->where(['id'=>'[0-9]+']);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/delete',[LanguageController::class, 'delete'])->name('language.delete')->where(['id'=>'[0-9]+']);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/switch',[LanguageController::class, 'swithBackendLanguage'])->name('language.switch')->where(['id'=>'[0-9]+']);//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    });


    //PostCatalogueController (Trang quản lí nhóm bài viết)
    Route::group(['prefix'=>'post/catalogue'], function(){
        Route::get('index',[PostCatalogueController::class, 'index'])->name('post.catalogue.index');//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('store',[PostCatalogueController::class, 'store'])->name('post.catalogue.store');//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('create',[PostCatalogueController::class, 'create'])->name('post.catalogue.create');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('{id}/edit',[PostCatalogueController::class, 'edit'])->name('post.catalogue.edit')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/update',[PostCatalogueController::class, 'update'])->name('post.catalogue.update')->where(['id'=>'[0-9]+']);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/destroy',[PostCatalogueController::class, 'destroy'])->name('post.catalogue.destroy')->where(['id'=>'[0-9]+']);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/delete',[PostCatalogueController::class, 'delete'])->name('post.catalogue.delete')->where(['id'=>'[0-9]+']);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    });

    //PostCatalogueController (Trang quản lí bài viết)
    Route::group(['prefix'=>'post'], function(){
        Route::get('index',[PostController::class, 'index'])->name('post.index');//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('store',[PostController::class, 'store'])->name('post.store');//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('create',[PostController::class, 'create'])->name('post.create');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('{id}/edit',[PostController::class, 'edit'])->name('post.edit')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/update',[PostController::class, 'update'])->name('post.update')->where(['id'=>'[0-9]+']);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/destroy',[PostController::class, 'destroy'])->name('post.destroy')->where(['id'=>'[0-9]+']);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/delete',[PostController::class, 'delete'])->name('post.delete')->where(['id'=>'[0-9]+']);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    });

    //LanguageController (Trang quản lí quyền)
    Route::group(['prefix'=>'permission'], function(){
        Route::get('index',[PermissionController::class, 'index'])->name('permission.index')->middleware(['admin', 'locale']);//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('store',[PermissionController::class, 'store'])->name('permission.store');//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('create',[PermissionController::class, 'create'])->name('permission.create');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('{id}/edit',[PermissionController::class, 'edit'])->name('permission.edit')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/update',[PermissionController::class, 'update'])->name('permission.update')->where(['id'=>'[0-9]+']);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/destroy',[PermissionController::class, 'destroy'])->name('permission.destroy')->where(['id'=>'[0-9]+']);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/delete',[PermissionController::class, 'delete'])->name('permission.delete')->where(['id'=>'[0-9]+']);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    });
});

