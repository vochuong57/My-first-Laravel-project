<?php

namespace App\Repositories\Interfaces;

/**
 * Interface BaseServiceInterface
 * @package App\Services\Interfaces
 */
interface BaseRepositoryInterface
{
    public function all(array $relation = []);
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
    public function findById(int $id, array $column=['*'], array $relation =[]);
    public function findWhereIn(string $column='', array $ids = []);
    public function findByCondition(array $condition = []);
    public function findByConditions(array $condition = []);
    public function findByConditionsWithRelation(array $condition = [], array $relation = []);
    public function create(array $payload =[]);
    public function createBatch(array $payload = []);
    public function update(int $id=0, array $payload=[]);
    public function updateReturn(int $id=0, array $payload=[]);
    public function updateByWhereIn(string $whereInField='', array $whereIn=[], array $payload=[]);
    public function updateByWhere(array $condition=[], array $payload=[]);
    public function updateOrInsert(array $payload = [], array $condition = []);
    public function delete(int $id=0);
    public function forceDelete(int $id=0);
    public function deleteByWhereIn(string $whereInField = '', array $whereIn = [], int $languageId = null);
    public function deleteByWhere(array $condition=[]);
    public function createPivot($model, array $payload=[], string $relation ='');
}
