<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\PostRepositoryInterface;
//chèn thêm thư viện tự tạo để lấy thông tin user từ DB
use App\Models\Post;
use App\Repositories\BaseRepository;
use SebastianBergmann\CodeCoverage\Report\Html\CustomCssFile;

/**
 * Class PostCatalogueService
 * @package App\Services
 */
class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    protected $model;
    public function __construct(Post $model){
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
        ->CustomWhere($condition['where'] ?? null)
        ->RelationCount($relations ?? null)
        ->CustomWhereRaw($rawQuery['whereRaw'] ?? null)
        ->CustomJoin($join ?? null)
        ->CustomGroupBy($extend['groupBy'] ?? null)
        ->CustomOrderBy($orderBy ?? null)
        ->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);

        //echo $query->toSql(); die();
    }    

    public function getPostById(int $id=0, $language_id=0){
        return $this->model->select([
            'posts.id',
            'posts.post_catalogue_id',
            'posts.image',
            'posts.icon',
            'posts.album',
            'posts.publish',
            'posts.follow',
            'tb2.name',
            'tb2.description',
            'tb2.content',
            'tb2.meta_title',
            'tb2.meta_keyword',
            'tb2.meta_description',
            'tb2.canonical',
        ])
        ->join('post_language as tb2','tb2.post_id','=','posts.id')
        ->with('post_catalogues')
        ->where('tb2.language_id','=',$language_id)
        ->find($id);
    }
}
