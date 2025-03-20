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

    public function findPromotionByVariantUuid($variant_uuid){
        return $this->model->select(
            'promotions.id',
            'promotions.maxDiscountValue',
            'promotions.discountValue',
            'promotions.discountType'
        )
        ->selectRaw(
            "
                IF(promotions.maxDiscountValue != 0,
                    LEAST(
                        CASE
                            WHEN discountType = 'cash' THEN 
                                discountValue
                            WHEN discountType = 'percent' THEN 
                                pv.price * discountValue / 100
                            ELSE 0
                        END,
                        promotions.maxDiscountValue
                    ),
                    CASE
                        WHEN discountType = 'cash' THEN 
                            discountValue
                        ELSE 0
                    END
                ) as discount"
        )
        ->join('promotion_object_variant as pov', 'pov.promotion_id', '=', 'promotions.id')
        ->join('product_variants as pv', 'pv.variant_uuid', '=', 'pov.object_variant_uuid')
        ->where('pov.object_variant_uuid', $variant_uuid)
        ->where('promotions.publish', 2)
        ->where('promotions.startDate', '<', now())
        ->where(function($query) { 
            $query->where('promotions.endDate', '>', now())
                ->orWhereNull('promotions.endDate');
        })
        ->orderBy('discount', 'desc')
        ->first();
    }
    
    public function getPromotionByCartTotal(){
        return $this->model
        ->where('promotions.publish', 2)
        ->where('promotions.method', 'order_amount_range')
        ->where('promotions.startDate', '<', now())
        ->where(function($query) { 
            $query->where('promotions.endDate', '>', now())
                ->orWhereNull('promotions.endDate');
        })
        ->get();
    }
}
