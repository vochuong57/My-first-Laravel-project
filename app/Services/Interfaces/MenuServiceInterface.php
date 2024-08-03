<?php

namespace App\Services\Interfaces;

/**
 * Interface MenuServiceInterface
 * @package App\Services\Interfaces
 */
interface MenuServiceInterface
{
    public function paginate($request, $languageId);//load dữ liệu user Catalogue theo trang
    public function createMenu($request, $languageId);//xử thêm user Catalogue từ view
    public function updateMenu($id, $request, $languageId);//xử lý cập nhật user Catalogue
    public function deleteMenu($id, $languageId);//xử lý xóa user Catalogue
   
}
