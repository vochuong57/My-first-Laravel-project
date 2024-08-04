<?php

namespace App\Services\Interfaces;

/**
 * Interface MenuCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface MenuCatalogueServiceInterface
{
    public function paginate($request, $languageId);//load dữ liệu user Catalogue theo trang
    public function createMenuCatalogue($request);//xử thêm user Catalogue từ view
    public function updateMenuCatalogue($id, $request);//xử lý cập nhật user Catalogue
    public function deleteMenuCatalogue($id);//xử lý xóa user Catalogue
   
}
