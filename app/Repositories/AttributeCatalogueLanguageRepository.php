<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\AttributeCatalogueLanguageRepositoryInterface;
use App\Repositories\BaseRepository;
//chèn thêm thư viện để lấy thông tin AttributeCatalogueLanguage từ DB
use App\Models\AttributeCatalogueLanguage;

/**
 * Class AttributeCatalogueLanguageService
 * @package App\Services
 */
class AttributeCatalogueLanguageRepository extends BaseRepository implements AttributeCatalogueLanguageRepositoryInterface
{
    protected $model;
    public function __construct(AttributeCatalogueLanguage $model){
        $this->model=$model;
    }
    
}
