<?php

namespace App\Services\Interfaces;

/**
 * Interface PromotionServiceInterface
 * @package App\Services\Interfaces
 */
interface PromotionServiceInterface
{
    public function paginate($request);//load dữ liệu promotion Catalogue theo trang
    public function createPromotion($request, $languageId);//xử thêm promotion Catalogue từ view
    public function updatePromotion($id, $request, $languageId);//xử lý cập nhật promotion Catalogue
    public function deletePromotion($id, $languageId);//xử lý xóa promotion Catalogue
    public function updateStatus($promotion=[]);//xử lý cập nhật tình trạng promotion Catalogue
    public function updateStatusAll($promotion=[]);//xử lý cập nhật tình trạng promotion Catalogue hàng loạt ở toolbox
}
