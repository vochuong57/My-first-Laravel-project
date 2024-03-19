<?php

namespace App\Services\Interfaces;

/**
 * Interface UserCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface UserCatalogueServiceInterface
{
    public function paginate($request);//load dữ liệu user Catalogue theo trang
    public function createUserCatalogue($request);//xử thêm user Catalogue từ view
    public function updateUserCatalogue($id, $request);//xử lý cập nhật user Catalogue
    public function deleteUserCatalogue($id);//xử lý xóa user Catalogue
    public function updateStatus($post=[]);//xử lý cập nhật tình trạng user Catalogue
    public function updateStatusAll($post=[]);//xử lý cập nhật tình trạng user Catalogue hàng loạt ở toolbox
    public function setPermission($request);//xử lý lưu dữ liệu phân quyền
}
