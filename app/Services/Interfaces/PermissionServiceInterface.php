<?php

namespace App\Services\Interfaces;

/**
 * Interface PermissionServiceInterface
 * @package App\Services\Interfaces
 */
interface PermissionServiceInterface
{
    public function paginate($request);//load dữ liệu user Catalogue theo trang
    public function createPermission($request);//xử thêm user Catalogue từ view
    public function updatePermission($id, $request);//xử lý cập nhật user Catalogue
    public function deletePermission($id);//xử lý xóa user Catalogue
}
