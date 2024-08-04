<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    // File AppServiceProvider.php bạn đã cung cấp chứa các cài đặt cho các dịch vụ (services) trong ứng dụng Laravel của bạn. Đối với các đường dẫn như 'App\Services\Interfaces\UserServiceInterface' và 'App\Repositories\Interfaces\UserRepositoryInterface', Laravel sử dụng các đường dẫn này để định rõ các interfaces và implementations của services mà bạn muốn đăng ký trong container của Laravel.
    protected $serviceBindings=[//phương thức tự tạo
        'App\Services\Interfaces\UserServiceInterface' => 'App\Services\UserService',
        
        'App\Services\Interfaces\UserCatalogueServiceInterface' => 'App\Services\UserCatalogueService',

        'App\Services\Interfaces\LanguageServiceInterface' => 'App\Services\LanguageService',

        'App\Services\Interfaces\PostCatalogueServiceInterface' => 'App\Services\PostCatalogueService',

        'App\Services\Interfaces\PostServiceInterface' => 'App\Services\PostService',
    
        //phân quyền
        'App\Services\Interfaces\PermissionServiceInterface' => 'App\Services\PermissionService',

        //quản lý Module
        'App\Services\Interfaces\GenerateServiceInterface' => 'App\Services\GenerateService',
    
        'App\Services\Interfaces\AttributeCatalogueServiceInterface' => 'App\Services\AttributeCatalogueService',
    
        'App\Services\Interfaces\AttributeServiceInterface' => 'App\Services\AttributeService',
    
        'App\Services\Interfaces\ProductCatalogueServiceInterface' => 'App\Services\ProductCatalogueService',
    
        'App\Services\Interfaces\ProductServiceInterface' => 'App\Services\ProductService',

        'App\Services\Interfaces\SystemServiceInterface' => 'App\Services\SystemService',

        'App\Services\Interfaces\MenuServiceInterface' => 'App\Services\MenuService',

        'App\Services\Interfaces\MenuCatalogueServiceInterface' => 'App\Services\MenuCatalogueService',
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach($this->serviceBindings as $key => $val){
            $this->app->bind($key,$val);
        }

        $this->app->register(AppRepositoryProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
