<?php

namespace App\Repositories\Interfaces;

/**
 * Interface WardServiceInterface
 * @package App\Services\Interfaces
 */
interface WardRepositoryInterface
{
    public function all();
    public function findWardByDistrictId(int $ward_id);//để tìm ra chính nó (c1)

}
