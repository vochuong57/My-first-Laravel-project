<?php

namespace App\Services\Interfaces;

/**
 * Interface ReviewServiceInterface
 * @package App\Services\Interfaces
 */
interface ReviewServiceInterface
{
    public function paginate($request);
    public function create($request);
}
