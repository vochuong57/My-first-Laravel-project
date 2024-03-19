<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
//phần quyền
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('modules', function($user, $permissionName){
            if($user->publish == 1) return false;//nếu tài khoản đăng đăng nhập mà publish = 1 thì ko làm gì cả
            $permission = $user->user_catalogues->permissions;//nếu publish khác 1 thì kiểm tra quyền của tài khoản đó
            if($permission->contains('canonical', $permissionName)){
                return true;
            }
            return false;
        });
    }
}
