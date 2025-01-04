<?php

namespace App\Services\Interfaces;

/**
 * Interface CustomerServiceInterface
 * @package App\Services\Interfaces
 */
interface CustomerServiceInterface
{
    public function paginate($request);//load dữ liệu customer theo trang
    public function createCustomer($request);//xử thêm customer từ view
    public function updateCustomer($id, $request);//xử lý cập nhật customer
    public function deleteCustomer($id);//xử lý xóa customer
    public function updateStatus($post=[]);//xử lý cập nhật tình trạng customer
    public function updateStatusAll($post=[]);//xử lý cập nhật tình trạng customer hàng loạt ở toolbox
}
