<?php

namespace App\Services\Interfaces;

/**
 * Interface PostServiceInterface
 * @package App\Services\Interfaces
 */
interface PostServiceInterface
{
    public function paginate($request, $languageId);//load dữ liệu user Catalogue theo trang
    public function createPost($request, $languageId);//xử thêm user Catalogue từ view
    public function updatePost($id, $request, $languageId);//xử lý cập nhật user Catalogue
    public function deletePost($id, $languageId);//xử lý xóa user Catalogue
    public function updateStatus($post=[]);//xử lý cập nhật tình trạng user Catalogue
    public function updateStatusAll($post=[]);//xử lý cập nhật tình trạng user Catalogue hàng loạt ở toolbox
}
