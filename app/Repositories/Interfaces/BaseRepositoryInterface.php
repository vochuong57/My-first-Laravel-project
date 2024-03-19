<?php

namespace App\Repositories\Interfaces;

/**
 * Interface BaseServiceInterface
 * @package App\Services\Interfaces
 */
interface BaseRepositoryInterface
{
    public function all();
    public function pagination(
        array $column=['*'],
        array $condition=[],
        int $perpage=0, 
        array $extend=[],
        array $orderBy=['id', 'DESC'],
        array $join=[],
        array $relations=[]
    );
    public function findById(int $id, array $column=['*'], array $relation =[]);
    public function findTableById(int $id = 0);
    public function create(array $payload =[]);
    public function update(int $id=0, array $payload=[]);
    public function updateByWhereIn(string $whereInField='', array $whereIn=[], array $payload=[]);
    public function delete(int $id=0);
    public function forceDelete(int $id=0);
    public function deleteByWhereIn(string $whereInField = '', array $whereIn = []);
    public function createLanguagePivot($model, array $payload=[]);
}
