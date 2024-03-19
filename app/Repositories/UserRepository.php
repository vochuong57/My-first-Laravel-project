<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
//chèn thêm thư viện để lấy thông tin user từ DB
use App\Models\User;

/**
 * Class UserService
 * @package App\Services
 */
class UserRepository implements UserRepositoryInterface
{
    public function __construct(){

    }

    public function getAllPaginate(){
        return User::paginate(15);
    }
}
