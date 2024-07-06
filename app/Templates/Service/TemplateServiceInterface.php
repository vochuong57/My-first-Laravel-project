<?php

namespace App\Services\Interfaces;

/**
 * Interface {ModuleTemplate}ServiceInterface
 * @package App\Services\Interfaces
 */
interface {ModuleTemplate}ServiceInterface
{
    public function paginate($request, $languageId);//load dữ liệu user Catalogue theo trang
    public function create{ModuleTemplate}($request, $languageId);//xử thêm user Catalogue từ view
    public function update{ModuleTemplate}($id, $request, $languageId);//xử lý cập nhật user Catalogue
    public function delete{ModuleTemplate}($id, $languageId);//xử lý xóa user Catalogue
    public function updateStatus($post=[]);//xử lý cập nhật tình trạng user Catalogue
    public function updateStatusAll($post=[]);//xử lý cập nhật tình trạng user Catalogue hàng loạt ở toolbox
}
