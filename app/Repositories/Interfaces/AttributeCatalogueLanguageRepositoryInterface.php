<?php

namespace App\Repositories\Interfaces;

/**
 * Interface AttributeCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface AttributeCatalogueLanguageRepositoryInterface
{
    public function all();
    public function updateByWhere(array $condition=[], array $payload=[]);
    public function deleteByWhereIn(string $whereInField = '', array $whereIn = [], int $languageId = null);
}
