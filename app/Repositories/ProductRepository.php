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

    // V104 dùng để custom lại dữ liệu hiển thị product hoặc product có variant trong promotion trong trường hợp chiết khâu theo từng sản phẩm
    public function findProductPromotion($condition = [], $relation = []){
        $query = $this->model->newQuery();
        $query->select([
            'products.id',
            'products.image',
            'tb2.name',
            'tb3.variant_uuid',
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

    public function filter($joins, $leftJoins, $wheres, $whereRaws, $selectRaws, $havingRaws, $perpage, $path, $sort, $languageId){
        
        $query = $this->model->newQuery();
        $query->selectRaw('
            products.id,
            MAX(products.image) as image,
            MAX(products.price) as price,
            MAX(pl.name) as name,
            MAX(pl.canonical) as canonical,
            MAX(pv.sku) as sku,
            MAX(pv.variant_uuid) as variant_uuid
        ');
        if(count($joins)){
            foreach($joins as $join){
                if (count($join) === 4) {
                $query->join($join[0], $join[1], $join[2], $join[3]);
                } elseif (count($join) === 2 && is_callable($join[1])) {
                    // Dạng [table, function($join) { ... }]
                    $query->join($join[0], $join[1]);
                }
            }
        }
        if (count($leftJoins)) {
            foreach ($leftJoins as $val) {
                if (is_array($val)) {
                    if (count($val) === 4) {
                        // Dạng [table, col1, operator, col2]
                        $query->leftJoin($val[0], $val[1], $val[2], $val[3]);
                    } elseif (count($val) === 2 && is_callable($val[1])) {
                        // Dạng [table, function($join) { ... }]
                        $query->leftJoin($val[0], $val[1]);
                    }
                }
            }
        }

        // $query->with(['languages']);
        // $query->with(['product_catalogues.languages' => function($query) use ($languageId){
        //     $query->where('language_id', $languageId);
        // }]);
        // $query->with(['reviews']);

        if(count($wheres)){
            foreach($wheres as $key => $val){
                if(is_array($val) && count($val) == 3){
                    $query->where($val[0], $val[1], $val[2]);
                }else{
                    // Dang function ($query) use...
                    $query->where($val);
                }
            }
        }
        // dd($selectRaws);
        if(count($whereRaws)){
            foreach($whereRaws as $key => $val){
                $query->whereRaw($val[0], $val[1]);
            }
        }
        if (count($selectRaws)) {
            foreach ($selectRaws as $selectRaw) {
                $query->selectRaw($selectRaw);
            }
        }
        if (count($havingRaws)) {
            foreach ($havingRaws as $havingRaw) {
                $query->havingRaw($havingRaw['condition'], $havingRaw['bindings']);
            }
        }
        $query->groupBy('products.id');

        // dd($sort);
        $sort = explode(':', $sort);
        // dd($sort);
        if($sort[0] != ''){
            if($sort[0] == 'name'){
                $query->orderBy('pl.'.$sort[0], $sort[1]);
            }elseif($sort[0] == 'price'){
                $query->orderBy('products.'.$sort[0], $sort[1]);
            }
        }else{
            $query->orderBy('products.id', 'desc');
        }

        // dd($query->toSql());
        return $query->paginate($perpage)->withQueryString()->withPath($path);

    }

    
}
