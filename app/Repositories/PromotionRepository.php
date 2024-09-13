<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\PromotionRepositoryInterface;
//chèn thêm thư viện tự tạo để lấy thông tin user từ DB
use App\Models\Promotion;
use App\Repositories\BaseRepository;
use SebastianBergmann\CodeCoverage\Report\Html\CustomCssFile;

/**
 * Class PromotionService
 * @package App\Services
 */
class PromotionRepository extends BaseRepository implements PromotionRepositoryInterface
{
    protected $model;
    public function __construct(Promotion $model){
        $this->model=$model;//chúng ta định nghĩa $this->model=User để đưa nó qua lớp kế thừa BaseRepository để lúc này phương thức create() của nó sẽ thành userRepository->create() ở lớp UserService để từ lớp UserService sẽ gọi nó lên controller để thực hiện việc thêm dữ liệu 
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
        ) 
    {
        $query = $this->model->select($column);
        
        return $query
        ->Keyword($condition['keyword'] ?? null)
        ->Publish($condition['publish'] ?? null)
        ->RelationCount($relations ?? null)
        ->CustomWhereRaw($rawQuery['whereRaw'] ?? null)
        ->CustomJoin($join ?? null)
        ->CustomGroupBy($extend['groupBy'] ?? null)
        ->CustomOrderBy($orderBy ?? null)
        ->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);

        //echo $query->toSql(); die();
    }    

}
