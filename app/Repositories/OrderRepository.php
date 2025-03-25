<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\BaseRepository;
//chèn thêm thư viện để lấy thông tin Order từ DB
use App\Models\Order;

/**
 * Class OrderService
 * @package App\Services
 */
class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    protected $model;
    public function __construct(Order $model){
        $this->model=$model;
    }
    
}
