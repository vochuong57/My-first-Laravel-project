<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\GenerateRepositoryInterface;
use App\Repositories\BaseRepository;
//chèn thêm thư viện để lấy thông tin Generate từ DB
use App\Models\Generate;

/**
 * Class GenerateService
 * @package App\Services
 */
class GenerateRepository extends BaseRepository implements GenerateRepositoryInterface
{
    protected $model;
    public function __construct(Generate $model){
        $this->model=$model;
    }
    
}
