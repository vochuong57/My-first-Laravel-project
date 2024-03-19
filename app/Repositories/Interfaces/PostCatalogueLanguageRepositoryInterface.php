<?php

namespace App\Repositories\Interfaces;

/**
 * Interface PostCatalogueLanguageServiceInterface
 * @package App\Services\Interfaces
 */
interface PostCatalogueLanguageRepositoryInterface
{
    public function all();
    public function updateByWhere(array $condition=[], array $payload=[]);//dùng để cập cột canonical về null trong PostCatalogueService
}
