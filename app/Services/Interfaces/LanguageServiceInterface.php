<?php

namespace App\Services\Interfaces;

/**
 * Interface LanguageServiceInterface
 * @package App\Services\Interfaces
 */
interface LanguageServiceInterface
{
    public function paginate($request);//load dữ liệu user Catalogue theo trang
    public function createLanguage($request);//xử thêm user Catalogue từ view
    public function updateLanguage($id, $request);//xử lý cập nhật user Catalogue
    public function deleteLanguage($id);//xử lý xóa user Catalogue
    public function updateStatus($post=[]);//xử lý cập nhật tình trạng user Catalogue
    public function updateStatusAll($post=[]);//xử lý cập nhật tình trạng user Catalogue hàng loạt ở toolbox
}
