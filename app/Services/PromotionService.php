<?php

namespace App\Services;

use App\Services\Interfaces\PromotionServiceInterface;
use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;
//thêm thư viện cho việc xử lý INSERT
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//thêm thư viện xử lý xử lý DATE
use Illuminate\Support\Carbon;
//thêm thư viện xử lý password
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


/**
 * Class PromotionService
 * @package App\Services
 */
class PromotionService extends BaseService implements PromotionServiceInterface
{
    protected $promotionRepository;

    public function __construct(PromotionRepository $promotionRepository){
        $this->promotionRepository=$promotionRepository;
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
            $payload = $request->only('name', 'keyword', 'description', 'short_code', 'model');
            // dd($payload);
            $payload['model_id'] = $this->formatJson($request, 'promotion.id');
            // dd($payload);
            $payload['album'] = $this->formatJson($request, 'album');
            // dd($payload);
            $payload['description'] = [
                $languageId => $payload['description']
            ];
            $payload['description'] = json_encode($payload['description']);
            // dd($payload);
            $payload['user_id']=Auth::id();
            // dd($payload);

            $promotion = $this->promotionRepository->create($payload);
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
            $payload = $request->only('name', 'keyword', 'description', 'short_code', 'model');
            // dd($payload);
            $payload['model_id'] = $this->formatJson($request, 'promotion.id');
            // dd($payload);
            $payload['album'] = $this->formatJson($request, 'album');
            // dd($payload);

            $promotion = $this->promotionRepository->findById($id);
            // dd($promotion);
            $promotionItem = $promotion->description;
            // dd($promotionItem);
            // dd($languageId);
            unset($promotionItem[$languageId]);

            $payload['description'] = [
                $languageId => $payload['description']
            ];
            $payload['description'] = json_encode($payload['description'] + $promotionItem);
            // dd($payload);

            $payload['user_id']=Auth::id();
            // dd($payload);

            $promotion = $this->promotionRepository->update($id, $payload);
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
            $promotion = $this->promotionRepository->findById($id);
            // dd($promotion);
            $promotionItem = $promotion->description;
            // dd($promotionItem);
            // dd($languageId);
            unset($promotionItem[$languageId]);
            // dd($promotionItem);

            $payload['description'] = json_encode($promotionItem);
            $this->promotionRepository->update($id, $payload);

            if(empty($promotionItem)){
                $promotion=$this->promotionRepository->forceDelete($id);
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }

    // V90
    public function convertPromotion($menuArray = null):array{
        $temp = [];
        $fields = ['name', 'canonical', 'image', 'id'];
        if(count($menuArray)){
            foreach($menuArray as $key => $val){
                foreach($fields as $field){
                    if($field == 'name' || $field == 'canonical'){
                        $temp[$field][] = $val->languages->first()->pivot->{$field};
                        // $temp[$field][] = $val->languages->first()->getOriginal('pivot_'.$field);
                    }else{
                        $temp[$field][] = $val->{$field};
                    }
                }
            }
        }
        return $temp;
    }

    // V92
    public function saveTranslatePromotion($request, $languageTranslateId, $id){
        DB::beginTransaction();
        try{
            $payload = $request->only('translate_description');
            // dd($payload);

            $promotion = $this->promotionRepository->findById($id);
            // dd($promotion);
            $promotionItem = $promotion->description;
            // dd($promotionItem);
            // dd($languageTranslateId);
            unset($promotionItem[$languageTranslateId]);
            // dd($languageTranslateId);
            // dd($promotionItem);

            $promotions = $this->handlePromotionItem($payload['translate_description'], $languageTranslateId)+$promotionItem;
            // dd($promotions);

            $payload['description'] = json_encode($promotions);
            // dd($payload);
    
            $promotion = $this->promotionRepository->update($id, $payload);
            // echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }

    // V92
    private function handlePromotionItem($promotion, $languageTranslateId){
        // dd($slides);
        $temp = [
            $languageTranslateId => $promotion
        ];
        
        // dd($temp);
        return $temp;
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
            'id','name','keyword','short_code','user_id', 'model' ,'publish', 'description'
        ];
    }
}
