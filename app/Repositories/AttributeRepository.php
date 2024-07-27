<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\AttributeRepositoryInterface;
//chèn thêm thư viện tự tạo để lấy thông tin user từ DB
use App\Models\Attribute;
use App\Repositories\BaseRepository;
use SebastianBergmann\CodeCoverage\Report\Html\CustomCssFile;

/**
 * Class AttributeCatalogueService
 * @package App\Services
 */
class AttributeRepository extends BaseRepository implements AttributeRepositoryInterface
{
    protected $model;
    public function __construct(Attribute $model){
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

    public function getAttributeById(int $id=0, $language_id=0){
        return $this->model->select([
            'attributes.id',
            'attributes.attribute_catalogue_id',
            'attributes.image',
            'attributes.icon',
            'attributes.album',
            'attributes.publish',
            'attributes.follow',
            'tb2.name',
            'tb2.description',
            'tb2.content',
            'tb2.meta_title',
            'tb2.meta_keyword',
            'tb2.meta_description',
            'tb2.canonical',
        ])
        ->join('attribute_language as tb2','tb2.attribute_id','=','attributes.id')
        ->with('attribute_catalogues')
        ->where('tb2.language_id','=',$language_id)
        ->find($id);
    }

    // Dùng để lấy ra id và name của attribute và attribute_language dựa vào attribute_catalogue_id (ajax) đã chọn
    public function searchAttributes(string $keyword = '', array $option = [], int $language_id = 0) {
        return $this->model->whereHas('attribute_catalogues', function($query) use($option) {
            $query->where('attribute_catalogue_id', $option['attributeCatalogueId']);
        })->whereHas('attribute_language', function($query) use($keyword, $language_id) {
            $query->where('language_id', $language_id)
                  ->where('name', 'like', '%'.$keyword.'%');
        })->with(['attribute_language' => function($query) use($language_id) {
            $query->where('language_id', $language_id)
                  ->select('attribute_id', 'name');
        }])->get();
    }
    
    // Dùng để lấy lại id và name của attribute và attribute_language dựa vào dữ liệu đã được submit form
    public function findAttributeByIdArray($attributeArray = [], int $language_id = 0){
        return $this->model->select([
            'attributes.id',
            'tb2.name'
        ])
        ->join('attribute_language as tb2','tb2.attribute_id', '=', 'attributes.id')
        ->where('tb2.language_id','=', $language_id)
        ->whereIn('attributes.id', $attributeArray)
        ->get();
    }
}
