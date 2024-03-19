<?php

namespace App\Services\Interfaces;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface UserServiceInterface
{
    public function paginate();//load dữ liệu user theo trang
    public function createUser($request);//xử thêm user từ view
}
