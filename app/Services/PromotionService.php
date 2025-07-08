<?php

namespace App\Services;

use App\Services\Interfaces\PromotionServiceInterface;
use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;
use App\Repositories\Interfaces\PromotionObjectVariantRepositoryInterface as PromotionObjectVariantRepository;
//thêm thư viện cho việc xử lý INSERT
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//thêm thư viện xử lý xử lý DATE
use Illuminate\Support\Carbon;
//thêm thư viện xử lý password
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Enums\PromotionEnum;

/**
 * Class PromotionService
 * @package App\Services
 */
class PromotionService extends BaseService implements PromotionServiceInterface
{
    protected $promotionRepository;
    protected $promotionObjectVariantRepository;

    public function __construct(PromotionRepository $promotionRepository, PromotionObjectVariantRepository $promotionObjectVariantRepository){
        $this->promotionRepository=$promotionRepository;
        $this->promotionObjectVariantRepository=$promotionObjectVariantRepository;
    }

    public function paginate($request){//$request để tiến hành chức năng tìm kiếm
        // dd($request);
        // echo 123; die();
        $condition['keyword']=addslashes($request->input('keyword'));
        $condition['publish']=$request->input('publish');
        if ($condition['publish'] == '0') {
            $condition['publish'] = null;
        }
        // dd($condition);
        $perpage=$request->integer('perpage', 20);
        //  echo 123; die();
        $promotions=$this->promotionRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perpage,
            ['path'=> 'promotion/index', 'groupBy' => $this->paginateSelect()],
            ['promotions.id', 'DESC']
        );
        // dd($promotions);
        
        return $promotions;
    }
    public function createPromotion($request, $languageId){
        DB::beginTransaction();
        try{
            // dd($request->input());
            // dd(PromotionEnum::ORDER_AMOUNT_RANGE);
            $payload = $request->only('name', 'code', 'description', 'method', 'startDate', 'endDate', 'neverDate');
            $payload['startDate'] = Carbon::createFromFormat('d/m/Y H:i', $payload['startDate']);
            if(isset($payload['endDate'])){
                $payload['endDate'] = Carbon::createFromFormat('d/m/Y H:i', $payload['endDate']);
            }
            if(isset($payload['neverDate'])){
                $payload['neverEndDate'] = $payload['neverDate'];
                // unset($payload['neverDate']);
            }
            $payload['code'] = (empty($payload['code'])) ? time() : $payload['code']; 

            switch ($payload['method']) {
                case PromotionEnum::ORDER_AMOUNT_RANGE:
                    $payload['discountInformation'] = $this->orderAmountRange($request);
                    break;
                case PromotionEnum::PRODUCT_AND_QUANTITY:
                    $payload['discountInformation'] = $this->productAndQuantity($request);
                    break;
            }
            
            $payload['maxDiscountValue'] = intval(str_replace('.', '', $request->input(PromotionEnum::PRODUCT_AND_QUANTITY.'.maxDiscountValue')));
            $payload['discountValue'] = intval(str_replace('.', '', $request->input(PromotionEnum::PRODUCT_AND_QUANTITY.'.discountValue')));
            $payload['discountType'] = $request->input(PromotionEnum::PRODUCT_AND_QUANTITY.'.discountType');

            // dd($payload);
            $promotion = $this->promotionRepository->create($payload);

            // promotion_object_variant
            if($promotion->id > 0 && $payload['method'] == PromotionEnum::PRODUCT_AND_QUANTITY){
                $object = $request->input('object');
                $payloadRelation = [];
                foreach($object['id'] as $key => $val){
                    $payloadRelation[]=[
                        'promotion_id' => $promotion->id,
                        'object_id' => $val,
                        'object_variant_uuid' => ($object['variant_uuid'][$key] == 'null') ? null : $object['variant_uuid'][$key],
                        'model' => $request->input('chooseModule')
                    ];
                }
                // dd($payloadRelation);
                $this->promotionObjectVariantRepository->createBatch($payloadRelation);
            }
            

            // echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }

    public function updatePromotion($id, $request, $languageId){
        DB::beginTransaction();
        try{
            // dd(PromotionEnum::ORDER_AMOUNT_RANGE);
            $payload = $request->only('name', 'code', 'description', 'method', 'startDate', 'endDate', 'neverDate');
            $payload['startDate'] = Carbon::createFromFormat('d/m/Y H:i', $payload['startDate']);
            if(isset($payload['endDate'])){
                $payload['endDate'] = Carbon::createFromFormat('d/m/Y H:i', $payload['endDate']);
            }else{
                $payload['endDate'] = null;
            }
            if(isset($payload['neverDate'])){
                $payload['neverEndDate'] = $payload['neverDate'];
                // unset($payload['neverDate']);
            }
            $payload['code'] = (empty($payload['code'])) ? time() : $payload['code']; 

            switch ($payload['method']) {
                case PromotionEnum::ORDER_AMOUNT_RANGE:
                    $payload['discountInformation'] = $this->orderAmountRange($request);
                    break;
                case PromotionEnum::PRODUCT_AND_QUANTITY:
                    $payload['discountInformation'] = $this->productAndQuantity($request);
                    break;
            }
            $payload['maxDiscountValue'] = intval(str_replace('.', '', $request->input(PromotionEnum::PRODUCT_AND_QUANTITY.'.maxDiscountValue')));
            $payload['discountValue'] = intval(str_replace('.', '', $request->input(PromotionEnum::PRODUCT_AND_QUANTITY.'.discountValue')));
            $payload['discountType'] = $request->input(PromotionEnum::PRODUCT_AND_QUANTITY.'.discountType');
            // dd($payload);

            $promotion = $this->promotionRepository->updateReturn($id, $payload);

            $this->promotionObjectVariantRepository->deleteByWhere([
                ['promotion_id', '=', $promotion->id],
            ]);

            // promotion_object_variant
            if($promotion->id > 0 && $payload['method'] == PromotionEnum::PRODUCT_AND_QUANTITY){
                $object = $request->input('object');
                $payloadRelation = [];
                foreach($object['id'] as $key => $val){
                    $payloadRelation[]=[
                        'promotion_id' => $promotion->id,
                        'object_id' => $val,
                        'object_variant_uuid' => ($object['variant_uuid'][$key] == 'null') ? null : $object['variant_uuid'][$key],
                        'model' => $request->input('chooseModule')
                    ];
                }
                // dd($payloadRelation);
               
                $this->promotionObjectVariantRepository->createBatch($payloadRelation);
            }
            // echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }
    public function deletePromotion($id, $languageId){
        DB::beginTransaction();
        try{
            $promotion=$this->promotionRepository->forceDelete($id);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }

    // V119
    private function orderAmountRange($request){
        $data['info'] = $request->input('promotion_order_amount_range');
        // dd($data + $this->handleSourceAndCondition($request));
        return $data + $this->handleSourceAndCondition($request);
    }

    // V119
    private function productAndQuantity($request){
        $data['info'] = $request->input('product_and_quantity');
        $data['info']['model'] = $request->input('chooseModule');
        $data['info']['object'] = $request->input('object');
        return $data + $this->handleSourceAndCondition($request);
    }

    // V119
    private function handleSourceAndCondition($request){
        $data = [
            'source' => [
                'status' => $request->input('source'),
                'data' => $request->input('sourceValue'),
            ],
            'apply' => [
                'status' => $request->input('apply'),
                'data' => $request->input('applyValue'),
            ]
        ];
        if(!is_null($data['apply']['data'])){
            foreach($data['apply']['data'] as $key => $val){
                $data['apply']['condition'][$val] = $request->input($val);
            }
        }
        return $data;
    }

    public function updateStatus($post=[]){
        //echo 123; die();
        DB::beginTransaction();
        try{
            $payload[$post['field']]=(($post['value']==1)?2:1);
            
            //dd($payload);
            $promotion=$this->promotionRepository->update($post['modelId'], $payload);
            //echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
        
    }
    public function updateStatusAll($post=[]){
        //echo 123; die();
        DB::beginTransaction();
        try{
            //dd($post);
            $payload[$post['field']]=$post['value'];
            
            //dd($payload);
            $promotion=$this->promotionRepository->updateByWhereIn('id', $post['id'], $payload);
            //echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
    public function deleteAll($post=[]){
        DB::beginTransaction();
        try{
            $promotion=$this->promotionRepository->deleteByWhereIn('id',$post['id']);
            //echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
    private function paginateSelect(){
        return [
            'id','name', 'code', 'description', 'method', 'discountInformation', 'neverEndDate', 'startDate', 'endDate', 'publish'
        ];
    }
}
