<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\AttributeLanguageRepositoryInterface;
use App\Repositories\BaseRepository;
//chèn thêm thư viện để lấy thông tin AttributeLanguage từ DB
use App\Models\AttributeLanguage;

/**
 * Class AttributeLanguageService
 * @package App\Services
 */
class AttributeLanguageRepository extends BaseRepository implements AttributeLanguageRepositoryInterface
{
    protected $model;
    public function __construct(AttributeLanguage $model){
        $this->model=$model;
    }
    
}