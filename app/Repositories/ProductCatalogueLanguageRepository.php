<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\ProductCatalogueLanguageRepositoryInterface;
use App\Repositories\BaseRepository;
//chèn thêm thư viện để lấy thông tin ProductCatalogueLanguage từ DB
use App\Models\ProductCatalogueLanguage;

/**
 * Class ProductCatalogueLanguageService
 * @package App\Services
 */
class ProductCatalogueLanguageRepository extends BaseRepository implements ProductCatalogueLanguageRepositoryInterface
{
    protected $model;
    public function __construct(ProductCatalogueLanguage $model){
        $this->model=$model;
    }
    
}
