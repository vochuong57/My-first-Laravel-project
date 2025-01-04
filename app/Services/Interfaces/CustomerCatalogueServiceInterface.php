<?php

namespace App\Services\Interfaces;

/**
 * Interface CustomerCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface CustomerCatalogueServiceInterface
{
    public function paginate($request);//load dữ liệu customer Catalogue theo trang
    public function createCustomerCatalogue($request);//xử thêm customer Catalogue từ view
    public function updateCustomerCatalogue($id, $request);//xử lý cập nhật customer Catalogue
    public function deleteCustomerCatalogue($id);//xử lý xóa customer Catalogue
    public function updateStatus($post=[]);//xử lý cập nhật tình trạng customer Catalogue
    public function updateStatusAll($post=[]);//xử lý cập nhật tình trạng customer Catalogue hàng loạt ở toolbox
}
