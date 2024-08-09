<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\MenuLanguageRepositoryInterface;
use App\Repositories\BaseRepository;
//chèn thêm thư viện để lấy thông tin MenuLanguage từ DB
use App\Models\MenuLanguage;

/**
 * Class MenuService
 * @package App\Services
 */
class MenuLanguageRepository extends BaseRepository implements MenuLanguageRepositoryInterface
{
    protected $model;
    public function __construct(MenuLanguage $model){
        $this->model=$model;
    }
    
}
