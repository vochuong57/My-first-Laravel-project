<?php

namespace App\Repositories\Interfaces;

/**
 * Interface RouterServiceInterface
 * @package App\Services\Interfaces
 */
interface RouterRepositoryInterface
{
    public function create(array $payload =[]);
    public function findByCondition(array $condition = []);
    public function update(int $id=0, array $payload=[]);
    public function delete(int $id=0);
    public function forceDelete(int $id=0);
}
