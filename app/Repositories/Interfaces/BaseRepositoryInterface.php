<?php

namespace App\Repositories\Interfaces;

/**
 * Interface BaseServiceInterface
 * @package App\Services\Interfaces
 */
interface BaseRepositoryInterface
{
    public function all();
    public function pagination(array $column=['*'],array $condition=[],array $join=[],int $perpage=20);
    public function findById(int $id, array $column=['*'], array $relation =[]);
    public function findTableById(int $id = 0);
    public function create(array $payload =[]);
    public function update(int $id=0, array $payload=[]);
    public function delete(int $id=0);
    public function forceDelete(int $id=0);
}
