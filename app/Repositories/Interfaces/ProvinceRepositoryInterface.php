<?php

namespace App\Repositories\Interfaces;

/**
 * Interface ProvinceServiceInterface
 * @package App\Services\Interfaces
 */
interface ProvinceRepositoryInterface
{
    public function all();
    public function findById(array $column=['*'], array $relation =[],int $id);//để tìm ra huyện (c2)

}
