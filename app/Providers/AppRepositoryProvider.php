<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
    */
    protected $repositoryBindings=[//phương thức tự tạo
        'App\Repositories\Interfaces\UserRepositoryInterface' => 'App\Repositories\UserRepository',

        'App\Repositories\Interfaces\ProvinceRepositoryInterface' => 'App\Repositories\ProvinceRepository',
        'App\Repositories\Interfaces\DistrictRepositoryInterface' => 'App\Repositories\DistrictRepository',
        'App\Repositories\Interfaces\WardRepositoryInterface' => 'App\Repositories\WardRepository',

        'App\Repositories\Interfaces\UserCatalogueRepositoryInterface' => 'App\Repositories\UserCatalogueRepository',

        'App\Repositories\Interfaces\LanguageRepositoryInterface' => 'App\Repositories\LanguageRepository',

        'App\Repositories\Interfaces\PostCatalogueRepositoryInterface' => 'App\Repositories\PostCatalogueRepository',

        'App\Repositories\Interfaces\PostCatalogueLanguageRepositoryInterface' => 'App\Repositories\PostCatalogueLanguageRepository',

        'App\Repositories\Interfaces\PostRepositoryInterface' => 'App\Repositories\PostRepository',

        'App\Repositories\Interfaces\PostLanguageRepositoryInterface' => 'App\Repositories\PostLanguageRepository',
        
        //xử lí quản lý routers
        'App\Repositories\Interfaces\RouterRepositoryInterface' => 'App\Repositories\RouterRepository',

        //phân quyền
        'App\Repositories\Interfaces\PermissionRepositoryInterface' => 'App\Repositories\PermissionRepository',

        //quản lý Module
        'App\Repositories\Interfaces\GenerateRepositoryInterface' => 'App\Repositories\GenerateRepository',
    
        'App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface' => 'App\Repositories\AttributeCatalogueRepository',

        'App\Repositories\Interfaces\AttributeCatalogueLanguageRepositoryInterface' => 'App\Repositories\AttributeCatalogueLanguageRepository',
    
        'App\Repositories\Interfaces\AttributeRepositoryInterface' => 'App\Repositories\AttributeRepository',

        'App\Repositories\Interfaces\AttributeLanguageRepositoryInterface' => 'App\Repositories\AttributeLanguageRepository',
    ];
    public function register(): void
    {
        foreach($this->repositoryBindings as $key => $val){
            $this->app->bind($key,$val);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
