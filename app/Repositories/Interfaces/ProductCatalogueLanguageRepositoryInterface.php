<?php

namespace App\Repositories\Interfaces;

/**
 * Interface ProductCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface ProductCatalogueLanguageRepositoryInterface
{
    public function all();
    public function updateByWhere(array $condition=[], array $payload=[]);
    public function deleteByWhereIn(string $whereInField = '', array $whereIn = [], int $languageId = null);
}
