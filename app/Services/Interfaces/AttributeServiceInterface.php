<?php

namespace App\Services\Interfaces;

/**
 * Interface AttributeServiceInterface
 * @package App\Services\Interfaces
 */
interface AttributeServiceInterface
{
    public function paginate($request, $languageId);//load dữ liệu user Catalogue theo trang
    public function createAttribute($request, $languageId);//xử thêm user Catalogue từ view
    public function updateAttribute($id, $request, $languageId);//xử lý cập nhật user Catalogue
    public function deleteAttribute($id, $languageId);//xử lý xóa user Catalogue
    public function updateStatus($post=[]);//xử lý cập nhật tình trạng user Catalogue
    public function updateStatusAll($post=[]);//xử lý cập nhật tình trạng user Catalogue hàng loạt ở toolbox
}
