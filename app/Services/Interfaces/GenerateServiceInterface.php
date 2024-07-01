<?php

namespace App\Services\Interfaces;

/**
 * Interface GenerateServiceInterface
 * @package App\Services\Interfaces
 */
interface GenerateServiceInterface
{
    public function paginate($request);//load dữ liệu user Catalogue theo trang
    public function createGenerate($request);//xử thêm user Catalogue từ view
    public function updateGenerate($id, $request);//xử lý cập nhật user Catalogue
    public function deleteGenerate($id);//xử lý xóa user Catalogue
}
