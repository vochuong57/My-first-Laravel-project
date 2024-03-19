<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\DistrictRepositoryInterface;
use App\Repositories\BaseRepository;
//chèn thêm thư viện để lấy thông tin District từ DB
use App\Models\District;

/**
 * Class DistrictService
 * @package App\Services
 */
class DistrictRepository extends BaseRepository implements DistrictRepositoryInterface
{
    protected $model;
    public function __construct(District $model){
        $this->model=$model;
    }
    //Nó sẽ giúp lọc huyện theo thành phố/tỉnh
    public function findDistrictByProvinceId(int $province_id = 0){
        return $this->model->where('province_code','=',$province_id)->get();
    }
}
