<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
//chèn thêm thư viện để lấy thông tin user từ DB
use App\Models\User;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;
    public function __construct(User $model){
        $this->model=$model;//chúng ta định nghĩa $this->model=User để đưa nó qua lớp kế thừa BaseRepository để lúc này phương thức create() của nó sẽ thành userRepository->create() ở lớp UserService để từ lớp UserService sẽ gọi nó lên controller để thực hiện việc thêm dữ liệu 
    }
    public function pagination(array $column = ['*'], array $condition = [], array $join = [], array $extend = [], int $perpage = 0, array $relations=[],array $orderBy=[]) {
        $query = $this->model->select($column)->where(function ($query) use ($condition) {
            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where(function ($query) use ($condition) {
                    $query->where('name', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('email', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('phone', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('address', 'LIKE', '%' . $condition['keyword'] . '%');
                });
            }

            // Thêm điều kiện kiểm tra publish nếu tồn tại
            if (isset($condition['publish'])) {
                $query->where('publish', '=', $condition['publish']);
            }

            // Thêm điều kiện kiểm tra user_catalogue_id nếu tồn tại
            if (isset($condition['user_catalogue_id'])) {
                $query->where('user_catalogue_id', '=', $condition['user_catalogue_id']);
            }
        })->with('user_catalogues');
        if(isset($relations)&&!empty($relations)){
            foreach($relations as $relation){
                $query->withCount($relation);
            }
        }

        if(isset($join)&&is_array($join)&&count($join)){
            foreach($join as $key =>$val){
                $query->join($val[0],$val[1],$val[2],$val[3]);
            }
        }

        if(isset($orderBy)&&is_array($orderBy)&&count($orderBy)){
            $query->orderBy($orderBy[0],$orderBy[1]);
        }
        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }

}
