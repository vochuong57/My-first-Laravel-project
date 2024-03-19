<?php

namespace App\Services\Interfaces;

/**
 * Interface PostCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface PostCatalogueServiceInterface
{
    public function paginate($request);//load dữ liệu user Catalogue theo trang
    public function createPostCatalogue($request);//xử thêm user Catalogue từ view
    public function updatePostCatalogue($id, $request);//xử lý cập nhật user Catalogue
    public function deletePostCatalogue($id);//xử lý xóa user Catalogue
    public function updateStatus($post=[]);//xử lý cập nhật tình trạng user Catalogue
    public function updateStatusAll($post=[]);//xử lý cập nhật tình trạng user Catalogue hàng loạt ở toolbox
}
