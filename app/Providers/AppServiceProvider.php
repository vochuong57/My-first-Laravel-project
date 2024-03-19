<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    // File AppServiceProvider.php bạn đã cung cấp chứa các cài đặt cho các dịch vụ (services) trong ứng dụng Laravel của bạn. Đối với các đường dẫn như 'App\Services\Interfaces\UserServiceInterface' và 'App\Repositories\Interfaces\UserRepositoryInterface', Laravel sử dụng các đường dẫn này để định rõ các interfaces và implementations của services mà bạn muốn đăng ký trong container của Laravel.
    protected $serviceBindings=[//phương thức tự tạo
        'App\Services\Interfaces\UserServiceInterface' => 'App\Services\UserService',
        'App\Repositories\Interfaces\UserRepositoryInterface' => 'App\Repositories\UserRepository',

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
