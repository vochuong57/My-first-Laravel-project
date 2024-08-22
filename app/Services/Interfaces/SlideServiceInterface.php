<?php

namespace App\Services\Interfaces;

/**
 * Interface SlideServiceInterface
 * @package App\Services\Interfaces
 */
interface SlideServiceInterface
{
    public function paginate($request, $languageId);//load dữ liệu user Catalogue theo trang
    public function createSlide($request, $languageId);//xử thêm user Catalogue từ view
    public function updateSlide($id, $request, $languageId);//xử lý cập nhật user Catalogue
    public function deleteSlide($id, $languageId);//xử lý xóa user Catalogue
    public function updateStatus($slide=[]);//xử lý cập nhật tình trạng user Catalogue
    public function updateStatusAll($slide=[]);//xử lý cập nhật tình trạng user Catalogue hàng loạt ở toolbox
}
