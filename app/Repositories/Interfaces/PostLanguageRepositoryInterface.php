<?php

namespace App\Repositories\Interfaces;

/**
 * Interface PostLanguageServiceInterface
 * @package App\Services\Interfaces
 */
interface PostLanguageRepositoryInterface
{
    public function all();
    public function updateByWhere(array $condition=[], array $payload=[]);
}
