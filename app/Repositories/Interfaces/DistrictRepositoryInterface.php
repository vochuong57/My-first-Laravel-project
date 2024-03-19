<?php

namespace App\Repositories\Interfaces;

/**
 * Interface DistrictServiceInterface
 * @package App\Services\Interfaces
 */
interface DistrictRepositoryInterface
{
    public function all();
    public function findDistrictByProvinceId(int $province_id = 0);//để tìm ra chính nó (tìm ra huyện) dựa vào khóa ngoại của nó (c1) 
    public function findById(int $id, array $column=['*'], array $relation =[]);//để tìm ra chính nó (tìm ra huyện) dựa vào khóa ngoại của nó (c2) 

}
