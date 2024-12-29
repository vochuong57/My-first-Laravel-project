<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\ProductRepositoryInterface;
//chèn thêm thư viện tự tạo để lấy thông tin user từ DB
use App\Models\Product;
use App\Repositories\BaseRepository;
use SebastianBergmann\CodeCoverage\Report\Html\CustomCssFile;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductCatalogueService
 * @package App\Services
 */
class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected $model;
    public function __construct(Product $model){
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

    public function getProductById(int $id=0, $language_id=0){
        return $this->model->select([
            'products.id',
            'products.product_catalogue_id',
            'products.image',
            'products.icon',
            'products.album',
            'products.publish',
            'products.follow',
            'products.code',
            'products.made_id',
            'products.price',
            'products.attributeCatalogue',
            'products.attribute',
            'products.variant',
            'tb2.name',
            'tb2.description',
            'tb2.content',
            'tb2.meta_title',
            'tb2.meta_keyword',
            'tb2.meta_description',
            'tb2.canonical',
        ])
        ->join('product_language as tb2','tb2.product_id','=','products.id')
        ->with([
            'product_catalogues', 
            'product_variants' => function ($query) use ($language_id){
                $query->with(['attributes' => function ($query) use ($language_id){
                    $query->with(['attribute_language' => function ($query) use ($language_id){
                        $query->where('language_id', '=', $language_id);
                    }]);
                }]);
            }
        ])
        ->where('tb2.language_id','=',$language_id)
        ->find($id);
    }

    public function findProductPromotion($condition = [], $relation = []){
        $query = $this->model->newQuery();
        $query->select([
            'products.id',
            'products.image',
            'tb2.name',
            'tb3.id as product_variant_id',
            DB::raw('CONCAT(tb2.name, " - ", COALESCE(tb4.name, "Default")) as variant_name'),
            DB::raw('COALESCE(tb3.sku, products.code) as sku'),
            DB::raw('COALESCE(tb3.price, products.price) as price'),
        ]);
        $query->join('product_language as tb2','tb2.product_id','=','products.id');
        $query->leftjoin('product_variants as tb3','tb3.product_id','=','products.id');
        $query->leftjoin('product_variant_language as tb4','tb4.product_variant_id','=','tb3.id');

        foreach($condition as $key => $val){
            $query->where($val[0], $val[1], $val[2]);
        }
        if(count($relation)){
            $query->with($relation);
        }
        $query->orderBy('products.id', 'desc');
        return $query->paginate(20);
    }
}
