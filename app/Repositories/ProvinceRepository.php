<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\ProvinceRepositoryInterface;
use App\Repositories\BaseRepository;
//chèn thêm thư viện để lấy thông tin Province từ DB
use App\Models\Province;

/**
 * Class ProvinceService
 * @package App\Services
 */
class ProvinceRepository extends BaseRepository implements ProvinceRepositoryInterface
{
    protected $model;
    public function __construct(Province $model){
        $this->model=$model;//chúng ta định nghĩa $this->model=Province để đưa nó qua lớp kế thừa BaseRepository để lúc này phương thức all() của nó sẽ thành thành Province->all()
    }
    //lúc này trong lớp ProvinceRepository không có phương thức all nữa mà nó sẽ được kế thừa từ BaseRepository 
    
}
