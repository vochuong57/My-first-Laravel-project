<?php

namespace App\Services\Interfaces;

/**
 * Interface UserServiceInterface
 * @package App\Services\Interfaces
 */
interface UserServiceInterface
{
    public function paginate($request);//load dữ liệu user theo trang
    public function createUser($request);//xử thêm user từ view
    public function updateUser($id, $request);//xử lý cập nhật user
    public function deleteUser($id);//xử lý xóa user
    public function updateStatus($post=[]);//xử lý cập nhật tình trạng user
    public function updateStatusAll($post=[]);//xử lý cập nhật tình trạng user hàng loạt ở toolbox
}
