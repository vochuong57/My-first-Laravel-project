<?php

namespace App\Repositories\Interfaces;

/**
 * Interface ProvinceServiceInterface
 * @package App\Services\Interfaces
 */
interface ProvinceRepositoryInterface
{
    public function all();
    public function findById(int $id, array $column=['*'], array $relation =[]);//để tìm ra chính nó (tìm ra huyện) dựa vào khóa ngoại của nó (c2)

}
