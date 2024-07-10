<?php

namespace App\Repositories\Interfaces;

/**
 * Interface {ModuleTemplate}ServiceInterface
 * @package App\Services\Interfaces
 */
interface {ModuleTemplate}LanguageRepositoryInterface
{
    public function all();
    public function updateByWhere(array $condition=[], array $payload=[]);
    public function deleteByWhereIn(string $whereInField = '', array $whereIn = [], int $languageId = null);
}
