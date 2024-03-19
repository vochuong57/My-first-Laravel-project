<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
//chèn thêm thư viện để lấy thông tin Base từ DB
use App\Models\Base;
//chèn thêm thư viện có sẵn
use illuminate\Database\Eloquent\Model;

/**
 * Class BaseService
 * @package App\Services
 */
class BaseRepository implements BaseRepositoryInterface
{
    protected $model;
    public function __construct(Model $model){
        $this->model=$model;
    }
    public function all(){
        return $this->model->all();
    }
   
}
