<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\ReviewRepositoryInterface;
use App\Repositories\BaseRepository;
//chèn thêm thư viện để lấy thông tin Review từ DB
use App\Models\Review;

/**
 * Class ReviewService
 * @package App\Services
 */
class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface
{
    protected $model;
    public function __construct(Review $model){
        $this->model=$model;
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
        ->Keyword($condition['keyword'] ?? null, ['fullname', 'phone', 'email', 'address', 'code'], ['field' => 'name', 'relation' => 'products'])
        ->CustomDropdownFilter($condition['dropdown'] ?? null)
        ->CustomCreatedAt($condition['created_at'] ?? null)
        ->Relation($relations ?? null)
        ->CustomWhereRaw($rawQuery['whereRaw'] ?? null)
        ->CustomJoin($join ?? null)
        ->CustomGroupBy($extend['groupBy'] ?? null)
        ->CustomOrderBy($orderBy ?? null)
        ->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);

        //echo $query->toSql(); die();
    }
}
