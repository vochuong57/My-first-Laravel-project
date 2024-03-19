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
   public function formatRouterPayload($request, $model, $controller);
   public function createRouter($request, $model, $controllerName);
   public function updateRouter($request, $model, $controllerName);
}
