<?php

namespace App\Repositories\Interfaces;

/**
 * Interface UserCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface UserCatalogueRepositoryInterface
{
    public function all();
    public function pagination(array $column=['*'],array $condition=[],array $join=[],array $extend=[],int $perpage=1, array $relations=[]);
    public function create(array $payload =[]);
    public function findById(int $id, array $column=['*'], array $relation =[]);
    public function update(int $id=0, array $payload=[]);
    public function delete(int $id=0);
    public function forceDelete(int $id=0);
    public function updateByWhereIn(string $whereInField='', array $whereIn=[], array $payload=[]);//dùng khi ở toolbox thay đổi hàng loạt trạng thái user
    public function deleteByWhereIn(string $whereInField = '', array $whereIn = []);

}
