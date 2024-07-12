<?php

namespace App\Services\Interfaces;

/**
 * Interface BaseServiceInterface
 * @package App\Services\Interfaces
 */
interface BaseServiceInterface
{
   public function currentLanguage();
   public function formatAlbum($request);
   public function nestedset();
   public function formatRouterPayload($request, $model, $controller, $languageId);
   public function createRouter($request, $model, $controllerName, $languageId);
   public function updateRouter($request, $model, $controllerName, $languageId);
}
