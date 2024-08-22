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
    
        'App\Repositories\Interfaces\ProductCatalogueRepositoryInterface' => 'App\Repositories\ProductCatalogueRepository',

        'App\Repositories\Interfaces\ProductCatalogueLanguageRepositoryInterface' => 'App\Repositories\ProductCatalogueLanguageRepository',
    
        'App\Repositories\Interfaces\ProductRepositoryInterface' => 'App\Repositories\ProductRepository',

        'App\Repositories\Interfaces\ProductLanguageRepositoryInterface' => 'App\Repositories\ProductLanguageRepository',

        'App\Repositories\Interfaces\ProductVariantRepositoryInterface' => 'App\Repositories\ProductVariantRepository',

        'App\Repositories\Interfaces\ProductVariantLanguageRepositoryInterface' => 'App\Repositories\ProductVariantLanguageRepository',

        'App\Repositories\Interfaces\ProductVariantAttributeRepositoryInterface' => 'App\Repositories\ProductVariantAttributeRepository',

        'App\Repositories\Interfaces\SystemRepositoryInterface' => 'App\Repositories\SystemRepository',

        'App\Repositories\Interfaces\MenuRepositoryInterface' => 'App\Repositories\MenuRepository',

        'App\Repositories\Interfaces\MenuCatalogueRepositoryInterface' => 'App\Repositories\MenuCatalogueRepository',

        'App\Repositories\Interfaces\MenuLanguageRepositoryInterface' => 'App\Repositories\MenuLanguageRepository',

        'App\Repositories\Interfaces\SlideRepositoryInterface' => 'App\Repositories\SlideRepository',
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
