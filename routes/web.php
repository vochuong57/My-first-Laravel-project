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
use App\Http\Controllers\Backend\GenerateController;
use App\Http\Controllers\Backend\AttributeCatalogueController;
use App\Http\Controllers\Backend\AttributeController;
use App\Http\Controllers\Backend\ProductCatalogueController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\SystemController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\SlideController;
use App\Http\Controllers\Backend\WidgetController;
use App\Http\Controllers\Backend\PromotionController;
//@@useController@@


//thư viện AJAX
use App\Http\Controllers\Ajax\LocationController;
use App\Http\Controllers\Ajax\DashboardController as AjaxDashboardController;
use App\Http\Controllers\Ajax\AttributeController as AjaxAttributeController;
use App\Http\Controllers\Ajax\MenuController as AjaxMenuController;
use App\Http\Controllers\Ajax\SlideController as AjaxSlideController;
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

Route::group(['middleware' => ['admin','locale','backend_default_locale']], function (){
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
    Route::get('ajax/attribute/getAttribute',[AjaxAttributeController::class, 'getAttribute'])->name('ajax.attribute.getAttribute');//Trang thực thi chuyển đổi ... đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::get('ajax/attribute/loadAttribute',[AjaxAttributeController::class, 'loadAttribute'])->name('ajax.attribute.loadAttribute');//Trang thực thi chuyển đổi ... đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('ajax/menu/createCatalogue',[AjaxMenuController::class, 'createCatalogue'])->name('ajax.menu.createCatalogue');//Trang thực thi chuyển đổi ... đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::get('ajax/dashboard/getMenu',[AjaxDashboardController::class, 'getMenu'])->name('ajax.dashboard.getMenu');//Trang thực thi chuyển đổi ... đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('ajax/menu/drag',[AjaxMenuController::class, 'drag'])->name('ajax.menu.drag');//Trang thực thi chuyển đổi ... đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::post('ajax/slide/drag',[AjaxSlideController::class, 'drag'])->name('ajax.slide.drag');//Trang thực thi chuyển đổi ... đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    Route::get('ajax/dashboard/getModelObject',[AjaxDashboardController::class, 'getModelObject'])->name('ajax.dashboard.getModelObject');//Trang thực thi chuyển đổi ... đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

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
    
        Route::get('{id}/{languageId}/{model}/translate',[LanguageController::class, 'translate'])->name('language.translate')->where(['id'=>'[0-9]+', 'languageId'=>'[0-9]+']);//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('storeTranslate',[LanguageController::class, 'storeTranslate'])->name('language.storeTranslate');
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

    //GenerateController (Trang quản lí Module)
    Route::group(['prefix'=>'generate'], function(){
        Route::get('index',[GenerateController::class, 'index'])->name('generate.index')->middleware(['admin', 'locale']);//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('store',[GenerateController::class, 'store'])->name('generate.store');//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('create',[GenerateController::class, 'create'])->name('generate.create');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('{id}/edit',[GenerateController::class, 'edit'])->name('generate.edit')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/update',[GenerateController::class, 'update'])->name('generate.update')->where(['id'=>'[0-9]+']);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/destroy',[GenerateController::class, 'destroy'])->name('generate.destroy')->where(['id'=>'[0-9]+']);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/delete',[GenerateController::class, 'delete'])->name('generate.delete')->where(['id'=>'[0-9]+']);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    });

    Route::group(['prefix'=>'attribute/catalogue'], function(){
        Route::get('index',[AttributeCatalogueController::class, 'index'])->name('attribute.catalogue.index');//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('store',[AttributeCatalogueController::class, 'store'])->name('attribute.catalogue.store');//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('create',[AttributeCatalogueController::class, 'create'])->name('attribute.catalogue.create');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/edit',[AttributeCatalogueController::class, 'edit'])->name('attribute.catalogue.edit')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/update',[AttributeCatalogueController::class, 'update'])->name('attribute.catalogue.update')->where(['id'=>'[0-9]+']);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/destroy',[AttributeCatalogueController::class, 'destroy'])->name('attribute.catalogue.destroy')->where(['id'=>'[0-9]+']);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/delete',[AttributeCatalogueController::class, 'delete'])->name('attribute.catalogue.delete')->where(['id'=>'[0-9]+']);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    });

    Route::group(['prefix'=>'attribute'], function(){
        Route::get('index',[AttributeController::class, 'index'])->name('attribute.index');//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('store',[AttributeController::class, 'store'])->name('attribute.store');//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('create',[AttributeController::class, 'create'])->name('attribute.create');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/edit',[AttributeController::class, 'edit'])->name('attribute.edit')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/update',[AttributeController::class, 'update'])->name('attribute.update')->where(['id'=>'[0-9]+']);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/destroy',[AttributeController::class, 'destroy'])->name('attribute.destroy')->where(['id'=>'[0-9]+']);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/delete',[AttributeController::class, 'delete'])->name('attribute.delete')->where(['id'=>'[0-9]+']);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    });

    Route::group(['prefix'=>'product/catalogue'], function(){
        Route::get('index',[ProductCatalogueController::class, 'index'])->name('product.catalogue.index');//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('store',[ProductCatalogueController::class, 'store'])->name('product.catalogue.store');//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('create',[ProductCatalogueController::class, 'create'])->name('product.catalogue.create');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/edit',[ProductCatalogueController::class, 'edit'])->name('product.catalogue.edit')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/update',[ProductCatalogueController::class, 'update'])->name('product.catalogue.update')->where(['id'=>'[0-9]+']);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/destroy',[ProductCatalogueController::class, 'destroy'])->name('product.catalogue.destroy')->where(['id'=>'[0-9]+']);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/delete',[ProductCatalogueController::class, 'delete'])->name('product.catalogue.delete')->where(['id'=>'[0-9]+']);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    });

    Route::group(['prefix'=>'product'], function(){
        Route::get('index',[ProductController::class, 'index'])->name('product.index');//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('store',[ProductController::class, 'store'])->name('product.store');//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('create',[ProductController::class, 'create'])->name('product.create');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/edit',[ProductController::class, 'edit'])->name('product.edit')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/update',[ProductController::class, 'update'])->name('product.update')->where(['id'=>'[0-9]+']);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/destroy',[ProductController::class, 'destroy'])->name('product.destroy')->where(['id'=>'[0-9]+']);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/delete',[ProductController::class, 'delete'])->name('product.delete')->where(['id'=>'[0-9]+']);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    });

    Route::group(['prefix'=>'system'], function(){
        Route::get('index',[SystemController::class, 'index'])->name('system.index')->middleware(['admin', 'locale']);//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('create',[SystemController::class, 'create'])->name('system.create');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('{languageId}/translate',[SystemController::class, 'translate'])->where(['languageId'=>'[0-9]+'])->name('system.translate');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{languageId}/saveTranslate',[SystemController::class, 'saveTranslate'])->where(['id'=>'[0-9]+'])->name('system.saveTranslate');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    });

    Route::group(['prefix'=>'menu'], function(){
        Route::get('index',[MenuController::class, 'index'])->name('menu.index');//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('store',[MenuController::class, 'store'])->name('menu.store');//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('create',[MenuController::class, 'create'])->name('menu.create');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/edit',[MenuController::class, 'edit'])->name('menu.edit')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/update',[MenuController::class, 'update'])->name('menu.update')->where(['id'=>'[0-9]+']);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/destroy',[MenuController::class, 'destroy'])->name('menu.destroy')->where(['id'=>'[0-9]+']);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/delete',[MenuController::class, 'delete'])->name('menu.delete')->where(['id'=>'[0-9]+']);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    
        Route::get('{id}/children',[MenuController::class, 'children'])->name('menu.children')->where(['id'=>'[0-9]+']);//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/saveChildren',[MenuController::class, 'saveChildren'])->name('menu.saveChildren')->where(['id'=>'[0-9]+']);//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    
        Route::get('{id}/editMenu',[MenuController::class, 'editMenu'])->name('menu.editMenu')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    
        Route::get('{languageId}/{menu_catalogue_id}/translate',[MenuController::class, 'translate'])->where(['languageId'=>'[0-9]+', 'menu_catalogue_id'=>'[0-9]+'])->name('menu.translate');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{languageId}/saveTranslate',[MenuController::class, 'saveTranslate'])->where(['languageId'=>'[0-9]+', 'menu_catalogue_id'=>'[0-9]+'])->name('menu.saveTranslate');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    });

    Route::group(['prefix'=>'slide'], function(){
        Route::get('index',[SlideController::class, 'index'])->name('slide.index');//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('store',[SlideController::class, 'store'])->name('slide.store');//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('create',[SlideController::class, 'create'])->name('slide.create');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('{id}/edit',[SlideController::class, 'edit'])->name('slide.edit')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/update',[SlideController::class, 'update'])->name('slide.update')->where(['id'=>'[0-9]+']);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/destroy',[SlideController::class, 'destroy'])->name('slide.destroy')->where(['id'=>'[0-9]+']);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/delete',[SlideController::class, 'delete'])->name('slide.delete')->where(['id'=>'[0-9]+']);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    
        Route::get('{languageId}/{id}/translate',[SlideController::class, 'translate'])->where(['languageId'=>'[0-9]+', 'id'=>'[0-9]+'])->name('slide.translate');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{languageId}/{id}/saveTranslate',[SlideController::class, 'saveTranslate'])->where(['languageId'=>'[0-9]+', 'id'=>'[0-9]+'])->name('slide.saveTranslate');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    });

    Route::group(['prefix'=>'widget'], function(){
        Route::get('index',[WidgetController::class, 'index'])->name('widget.index');//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('store',[WidgetController::class, 'store'])->name('widget.store');//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('create',[WidgetController::class, 'create'])->name('widget.create');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('{id}/edit',[WidgetController::class, 'edit'])->name('widget.edit')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/update',[WidgetController::class, 'update'])->name('widget.update')->where(['id'=>'[0-9]+']);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/destroy',[WidgetController::class, 'destroy'])->name('widget.destroy')->where(['id'=>'[0-9]+']);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/delete',[WidgetController::class, 'delete'])->name('widget.delete')->where(['id'=>'[0-9]+']);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    
        Route::get('{languageId}/{id}/translate',[WidgetController::class, 'translate'])->where(['languageId'=>'[0-9]+', 'id'=>'[0-9]+'])->name('widget.translate');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{languageId}/{id}/saveTranslate',[WidgetController::class, 'saveTranslate'])->where(['languageId'=>'[0-9]+', 'id'=>'[0-9]+'])->name('widget.saveTranslate');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
    });

    Route::group(['prefix'=>'promotion'], function(){
        Route::get('index',[PromotionController::class, 'index'])->name('promotion.index');//hiển thị form user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('store',[PromotionController::class, 'store'])->name('promotion.store');//hiển thị form thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('create',[PromotionController::class, 'create'])->name('promotion.create');//thực thi xử lý thêm user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        
        Route::get('{id}/edit',[PromotionController::class, 'edit'])->name('promotion.edit')->where(['id'=>'[0-9]+']);//hiển thị form cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/update',[PromotionController::class, 'update'])->name('promotion.update')->where(['id'=>'[0-9]+']);//Thực thi xử lý cập nhật user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

        Route::get('{id}/destroy',[PromotionController::class, 'destroy'])->name('promotion.destroy')->where(['id'=>'[0-9]+']);//hiển thị form xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')
        Route::post('{id}/delete',[PromotionController::class, 'delete'])->name('promotion.delete')->where(['id'=>'[0-9]+']);//Thực thi xử lý xóa user khi đăng nhập thành công | Nếu ở đây mà người dùng chưa đăng nhập trước đó thì dùng middleware này để chuyển người dùng qua route ('auth.admin')

    });

    //@@new-module@@

});

