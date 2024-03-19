<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\RouterRepositoryInterface;
use App\Repositories\BaseRepository;
//chèn thêm thư viện để lấy thông tin Router từ DB
use App\Models\Router;

/**
 * Class RouterService
 * @package App\Services
 */
class RouterRepository extends BaseRepository implements RouterRepositoryInterface
{
    protected $model;
    public function __construct(Router $model){
        $this->model=$model;
    }
    
}
