<?php

namespace App\Repositories\Interfaces;

/**
 * Interface WardServiceInterface
 * @package App\Services\Interfaces
 */
interface WardRepositoryInterface
{
    public function all();
    public function findWardByDistrictId(int $district_id = 0);//để tìm ra chính nó (tìm ra xã) dựa vào khóa ngoại của nó (c1) 

}
