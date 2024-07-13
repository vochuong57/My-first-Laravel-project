<?php

namespace App\Services\Interfaces;

/**
 * Interface AttributeCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface AttributeCatalogueServiceInterface
{
    public function paginate($request, $languageId);//load dữ liệu user Catalogue theo trang
    public function createAttributeCatalogue($request, $languageId);//xử thêm user Catalogue từ view
    public function updateAttributeCatalogue($id, $request, $languageId);//xử lý cập nhật user Catalogue
    public function deleteAttributeCatalogue($id, $languageId);//xử lý xóa user Catalogue
    public function updateStatus($post=[]);//xử lý cập nhật tình trạng user Catalogue
    public function updateStatusAll($post=[]);//xử lý cập nhật tình trạng user Catalogue hàng loạt ở toolbox
}
