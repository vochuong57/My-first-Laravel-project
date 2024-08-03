<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\MenuRepositoryInterface;
use App\Repositories\BaseRepository;
//chèn thêm thư viện để lấy thông tin Menu từ DB
use App\Models\Menu;

/**
 * Class MenuService
 * @package App\Services
 */
class MenuRepository extends BaseRepository implements MenuRepositoryInterface
{
    protected $model;
    public function __construct(Menu $model){
        $this->model=$model;
    }
    
}
