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
    public function findById(array $column=['*'], array $relation =[], int $id){
        return $this->model->select($column)->with($relation)->findOrFail($id);
        // select(các cột cần hiển thị) với những cột này thuộc về bảng with(bảng x) tìm được dữ liệu hay không dựa vào id khóa ngoại (của bảng y)
        // trường hợp ở đây bảng y là provinces và bảng x là districts. Như vậy gọi phương thức này ở interface của bảng y provinces
    }

    //phương thức thêm (INSERT)
    public function create(array $payload =[]){
        $model= $this->model->create($payload);
        return $model->fresh();
    }
}
