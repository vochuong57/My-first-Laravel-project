<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface;
//chèn thêm thư viện tự tạo để lấy thông tin user từ DB
use App\Models\AttributeCatalogue;
use App\Repositories\BaseRepository;

/**
 * Class AttributeCatalogueService
 * @package App\Services
 */
class AttributeCatalogueRepository extends BaseRepository implements AttributeCatalogueRepositoryInterface
{
    protected $model;
    public function __construct(AttributeCatalogue $model){
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
        ) {
        $query = $this->model->select($column)->where(function ($query) use ($condition) {
            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where(function ($query) use ($condition) {
                    $query->where('name', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('canonical', 'LIKE', '%' . $condition['keyword'] . '%');
                       
                });
            }
            
            // Thêm điều kiện kiểm tra publish nếu tồn tại
            if (isset($condition['publish'])) {
                $query->where('publish', '=', $condition['publish']);
            }

            if(isset($condition['where']) && count($condition['where'])){
                foreach($condition['where'] as $key => $val){
                    $query->where($val[0], $val[1], $val[2]);
                }
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
        if(isset($extend['groupBy']) && !empty($extend['groupBy'])){
            $query->groupBy($extend['groupBy']);
        }
        if(isset($orderBy)&&!empty($orderBy)){
            $query->orderBy($orderBy[0], $orderBy[1]);
        }
        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }    

    public function getAttributeCatalogueById(int $id=0, $language_id=0){
        return $this->model->select([
            'attribute_catalogues.id',
            'attribute_catalogues.parent_id',
            'attribute_catalogues.image',
            'attribute_catalogues.icon',
            'attribute_catalogues.album',
            'attribute_catalogues.publish',
            'attribute_catalogues.follow',
            'tb2.name',
            'tb2.description',
            'tb2.content',
            'tb2.meta_title',
            'tb2.meta_keyword',
            'tb2.meta_description',
            'tb2.canonical',
        ])
        ->join('attribute_catalogue_language as tb2','tb2.attribute_catalogue_id','=','attribute_catalogues.id')
        ->where('tb2.language_id','=',$language_id)
        ->find($id);
    }

    public function getAll(int $language_id = 0) {
        return $this->model->whereHas('attribute_catalogue_language', function($query) use($language_id) {
            $query->where('language_id', $language_id);
        })->with(['attribute_catalogue_language' => function($query) use($language_id) {
            $query->where('language_id', $language_id);
        }])->get();
    }
    
}
