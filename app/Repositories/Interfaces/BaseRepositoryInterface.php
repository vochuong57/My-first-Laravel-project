<?php

namespace App\Repositories\Interfaces;

/**
 * Interface BaseServiceInterface
 * @package App\Services\Interfaces
 */
interface BaseRepositoryInterface
{
    public function all();
    public function findById(array $column=['*'], array $relation =[],int $id);
    public function create(array $payload =[]);
}
