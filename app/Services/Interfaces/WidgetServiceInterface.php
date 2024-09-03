<?php

namespace App\Services\Interfaces;

/**
 * Interface WidgetServiceInterface
 * @package App\Services\Interfaces
 */
interface WidgetServiceInterface
{
    public function paginate($request);//load dữ liệu widget Catalogue theo trang
    public function createWidget($request);//xử thêm widget Catalogue từ view
    public function updateWidget($id, $request);//xử lý cập nhật widget Catalogue
    public function deleteWidget($id);//xử lý xóa widget Catalogue
    public function updateStatus($widget=[]);//xử lý cập nhật tình trạng widget Catalogue
    public function updateStatusAll($widget=[]);//xử lý cập nhật tình trạng widget Catalogue hàng loạt ở toolbox
}
