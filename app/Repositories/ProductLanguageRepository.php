<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\ProductLanguageRepositoryInterface;
use App\Repositories\BaseRepository;
//chèn thêm thư viện để lấy thông tin ProductLanguage từ DB
use App\Models\ProductLanguage;

/**
 * Class ProductLanguageService
 * @package App\Services
 */
class ProductLanguageRepository extends BaseRepository implements ProductLanguageRepositoryInterface
{
    protected $model;
    public function __construct(ProductLanguage $model){
        $this->model=$model;
    }
    
}
