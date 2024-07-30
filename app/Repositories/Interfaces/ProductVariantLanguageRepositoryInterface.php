<?php

namespace App\Repositories\Interfaces;

/**
 * Interface ProductVariantLanguageServiceInterface
 * @package App\Services\Interfaces
 */
interface ProductVariantLanguageRepositoryInterface
{
    public function all();
    public function pagination(
        array $column=['*'],
        array $condition=[],
        int $perpage=0, 
        array $extend=[],
        array $orderBy=['id', 'DESC'],
        array $join=[],
        array $relations=[],
        array $rawQuery = []
    );
    public function create(array $payload =[]);
    public function createBatch(array $payload = []);
    public function findById(int $id, array $column=['*'], array $relation =[]);
    public function update(int $id=0, array $payload=[]);
    public function delete(int $id=0);
    public function forceDelete(int $id=0);
    public function updateByWhereIn(string $whereInField='', array $whereIn=[], array $payload=[]);//dùng khi ở toolbox thay đổi hàng loạt trạng thái user
    public function deleteByWhereIn(string $whereInField = '', array $whereIn = [], int $languageId = null);
    public function createPivot($model, array $payload=[], string $relation='');//xử lý logic thêm 2 bảng
}
