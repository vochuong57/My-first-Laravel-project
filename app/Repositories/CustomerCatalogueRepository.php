<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface;
//chèn thêm thư viện tự tạo để lấy thông tin customer từ DB
use App\Models\CustomerCatalogue;
use App\Repositories\BaseRepository;

/**
 * Class CustomerService
 * @package App\Services
 */
class CustomerCatalogueRepository extends BaseRepository implements CustomerCatalogueRepositoryInterface
{
    protected $model;
    public function __construct(CustomerCatalogue $model){
        $this->model=$model;//chúng ta định nghĩa $this->model=Customer để đưa nó qua lớp kế thừa BaseRepository để lúc này phương thức create() của nó sẽ thành customerRepository->create() ở lớp customerService để từ lớp CustomerService sẽ gọi nó lên controller để thực hiện việc thêm dữ liệu 
    }
    public function pagination(
        array $column=['*'],
        array $condition=[],
        int $perpage=0, 
        array $extend=[],
        array $orderBy=[],
        array $join=[],
        array $relations=[],
        array $rawQuery = []
    ) {
        $query = $this->model->select($column)->where(function ($query) use ($condition) {
            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where(function ($query) use ($condition) {
                    $query->where('name', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('description', 'LIKE', '%' . $condition['keyword'] . '%');
                       
                });
            }
            
            // Thêm điều kiện kiểm tra publish nếu tồn tại
            if (isset($condition['publish'])) {
                $query->where('publish', '=', $condition['publish']);
            }
        });
        if(isset($relations) && !empty($relations)) {
            foreach($relations as $relation) {
                $query->withCount($relation);
            }
        }
        if(isset($join)&&is_array($join)&&count($join)){
            foreach($join as $key =>$val){
                $query->join($val[0],$val[1],$val[2],$val[3]);
            }
        }
        if(isset($orderBy)&&!empty($orderBy)){
            $query->orderBy($orderBy[0], $orderBy[1]);
        }
        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }    

}
