<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\WardRepositoryInterface;
use App\Repositories\BaseRepository;
//chèn thêm thư viện để lấy thông tin Ward từ DB
use App\Models\Ward;

/**
 * Class WardService
 * @package App\Services
 */
class WardRepository extends BaseRepository implements WardRepositoryInterface
{
    protected $model;
    public function __construct(Ward $model){
        $this->model=$model;
    }
    //Nó sẽ giúp lọc huyện theo thành phố/tỉnh
    public function findWardByDistrictId(int $district_id = 0){
        return $this->model->where('district_code','=',$district_id)->get();
    }
}
