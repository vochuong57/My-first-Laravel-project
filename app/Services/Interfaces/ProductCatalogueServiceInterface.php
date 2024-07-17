<?php

namespace App\Services\Interfaces;

/**
 * Interface ProductCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface ProductCatalogueServiceInterface
{
    public function paginate($request, $languageId);//load dữ liệu user Catalogue theo trang
    public function createProductCatalogue($request, $languageId);//xử thêm user Catalogue từ view
    public function updateProductCatalogue($id, $request, $languageId);//xử lý cập nhật user Catalogue
    public function deleteProductCatalogue($id, $languageId);//xử lý xóa user Catalogue
    public function updateStatus($post=[]);//xử lý cập nhật tình trạng user Catalogue
    public function updateStatusAll($post=[]);//xử lý cập nhật tình trạng user Catalogue hàng loạt ở toolbox
}
