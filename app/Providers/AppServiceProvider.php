<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    // File AppServiceProvider.php bạn đã cung cấp chứa các cài đặt cho các dịch vụ (services) trong ứng dụng Laravel của bạn. Đối với các đường dẫn như 'App\Services\Interfaces\UserServiceInterface' và 'App\Repositories\Interfaces\UserRepositoryInterface', Laravel sử dụng các đường dẫn này để định rõ các interfaces và implementations của services mà bạn muốn đăng ký trong container của Laravel.
    protected $serviceBindings=[//phương thức tự tạo
        'App\Services\Interfaces\UserServiceInterface' => 'App\Services\UserService',
        'App\Repositories\Interfaces\UserRepositoryInterface' => 'App\Repositories\UserRepository',

        'App\Repositories\Interfaces\ProvinceRepositoryInterface' => 'App\Repositories\ProvinceRepository',
        'App\Repositories\Interfaces\DistrictRepositoryInterface' => 'App\Repositories\DistrictRepository',
        'App\Repositories\Interfaces\WardRepositoryInterface' => 'App\Repositories\WardRepository',

        'App\Services\Interfaces\UserCatalogueServiceInterface' => 'App\Services\UserCatalogueService',
        'App\Repositories\Interfaces\UserCatalogueRepositoryInterface' => 'App\Repositories\UserCatalogueRepository',

        'App\Services\Interfaces\LanguageServiceInterface' => 'App\Services\LanguageService',
        'App\Repositories\Interfaces\LanguageRepositoryInterface' => 'App\Repositories\LanguageRepository',

        'App\Services\Interfaces\PostCatalogueServiceInterface' => 'App\Services\PostCatalogueService',
        'App\Repositories\Interfaces\PostCatalogueRepositoryInterface' => 'App\Repositories\PostCatalogueRepository',

        'App\Services\Interfaces\PostServiceInterface' => 'App\Services\PostService',
        'App\Repositories\Interfaces\PostRepositoryInterface' => 'App\Repositories\PostRepository',
        
        //xử lí quản lý routers
        'App\Repositories\Interfaces\RouterRepositoryInterface' => 'App\Repositories\RouterRepository',

        'App\Repositories\Interfaces\PostCatalogueLanguageRepositoryInterface' => 'App\Repositories\PostCatalogueLanguageRepository',//ở đây tôi muốn khi xóa thì dữ liệu canonical ở bảng này sẽ bị null để vẫn thấy được các thông tin cũ và để khi thêm thông tin mới nếu có bị trùng với canonical trước đó thì sẽ không bị báo lỗi unique

        'App\Repositories\Interfaces\PostLanguageRepositoryInterface' => 'App\Repositories\PostLanguageRepository',

        //phân quyền
        'App\Services\Interfaces\PermissionServiceInterface' => 'App\Services\PermissionService',
        'App\Repositories\Interfaces\PermissionRepositoryInterface' => 'App\Repositories\PermissionRepository',
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach($this->serviceBindings as $key => $val){
            $this->app->bind($key,$val);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
