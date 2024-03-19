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
    public function pagination(array $column = ['*'], array $condition = [], array $join = [], array $extend = [], int $perpage = 0, array $relations=[]) {
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
        })->with('user_catalogues');
        if(isset($relations)&&!empty($relations)){
            foreach($relations as $relation){
                $query->withCount($relation);
            }
        }

        if (!empty($join)) {
            $query->join(...$join);
        }

        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }

}
