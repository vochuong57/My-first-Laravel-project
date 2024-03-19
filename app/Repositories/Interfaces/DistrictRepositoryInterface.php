<?php

namespace App\Repositories\Interfaces;

/**
 * Interface DistrictServiceInterface
 * @package App\Services\Interfaces
 */
interface DistrictRepositoryInterface
{
    public function all();
    public function findDistrictByProvinceId(int $province_id);//để tìm ra chính nó (c1)
    public function findById(array $column=['*'], array $relation =[],int $id);//để tìm ra xã (c2)


}
