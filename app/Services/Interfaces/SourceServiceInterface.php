<?php

namespace App\Services\Interfaces;

/**
 * Interface SourceServiceInterface
 * @package App\Services\Interfaces
 */
interface SourceServiceInterface
{
    public function paginate($request);//load dữ liệu source Catalogue theo trang
    public function createSource($request);//xử thêm source Catalogue từ view
    public function updateSource($id, $request);//xử lý cập nhật source Catalogue
    public function deleteSource($id);//xử lý xóa source Catalogue
    public function updateStatus($source=[]);//xử lý cập nhật tình trạng source Catalogue
    public function updateStatusAll($source=[]);//xử lý cập nhật tình trạng source Catalogue hàng loạt ở toolbox
}
