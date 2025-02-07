<?php

namespace App\Services;

use App\Services\Interfaces\WidgetServiceInterface;
use App\Repositories\Interfaces\WidgetRepositoryInterface as WidgetRepository;
//thêm thư viện cho việc xử lý INSERT
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//thêm thư viện xử lý xử lý DATE
use Illuminate\Support\Carbon;
//thêm thư viện xử lý password
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


/**
 * Class WidgetService
 * @package App\Services
 */
class WidgetService extends BaseService implements WidgetServiceInterface
{
    protected $widgetRepository;

    public function __construct(WidgetRepository $widgetRepository){
        $this->widgetRepository=$widgetRepository;
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
        $widgets=$this->widgetRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perpage,
            ['path'=> 'widget/index', 'groupBy' => $this->paginateSelect()],
            ['widgets.id', 'DESC']
        );
        // dd($widgets);
        
        return $widgets;
    }
    public function createWidget($request, $languageId){
        DB::beginTransaction();
        try{
            $payload = $request->only('name', 'keyword', 'description', 'short_code', 'model');
            // dd($payload);
            $payload['model_id'] = $this->formatJson($request, 'widget.id');
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

            $widget = $this->widgetRepository->create($payload);
            // echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }

    public function updateWidget($id, $request, $languageId){
        DB::beginTransaction();
        try{
            $payload = $request->only('name', 'keyword', 'description', 'short_code', 'model');
            // dd($payload);
            $payload['model_id'] = $this->formatJson($request, 'widget.id');
            // dd($payload);
            $payload['album'] = $this->formatJson($request, 'album');
            // dd($payload);

            $widget = $this->widgetRepository->findById($id);
            // dd($widget);
            $widgetItem = $widget->description;
            // dd($widgetItem);
            // dd($languageId);
            unset($widgetItem[$languageId]);

            $payload['description'] = [
                $languageId => $payload['description']
            ];
            $payload['description'] = json_encode($payload['description'] + $widgetItem);
            // dd($payload);

            $payload['user_id']=Auth::id();
            // dd($payload);

            $widget = $this->widgetRepository->update($id, $payload);
            // echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }
    public function deleteWidget($id, $languageId){
        DB::beginTransaction();
        try{
            $widget = $this->widgetRepository->findById($id);
            // dd($widget);
            $widgetItem = $widget->description;
            // dd($widgetItem);
            // dd($languageId);
            unset($widgetItem[$languageId]);
            // dd($widgetItem);

            $payload['description'] = json_encode($widgetItem);
            $this->widgetRepository->update($id, $payload);

            if(empty($widgetItem)){
                $widget=$this->widgetRepository->forceDelete($id);
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
    public function convertWidget($menuArray = null):array{
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
    public function saveTranslateWidget($request, $languageTranslateId, $id){
        DB::beginTransaction();
        try{
            $payload = $request->only('translate_description');
            // dd($payload);

            $widget = $this->widgetRepository->findById($id);
            // dd($widget);
            $widgetItem = $widget->description;
            // dd($widgetItem);
            // dd($languageTranslateId);
            unset($widgetItem[$languageTranslateId]);
            // dd($languageTranslateId);
            // dd($widgetItem);

            $widgets = $this->handleWidgetItem($payload['translate_description'], $languageTranslateId)+$widgetItem;
            // dd($widgets);

            $payload['description'] = json_encode($widgets);
            // dd($payload);
    
            $widget = $this->widgetRepository->update($id, $payload);
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
    private function handleWidgetItem($widget, $languageTranslateId){
        // dd($slides);
        $temp = [
            $languageTranslateId => $widget
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
            $widget=$this->widgetRepository->update($post['modelId'], $payload);
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
            $widget=$this->widgetRepository->updateByWhereIn('id', $post['id'], $payload);
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
            $widget=$this->widgetRepository->deleteByWhereIn('id',$post['id']);
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

    // fontend widget
    public function findWidgetByKeyword(string $keyword = '', int $language, $extend = false){
        $condition = [
            ['keyword', '=', $keyword],
            ['publish', '=', 2],
        ];

        $widget = $this->widgetRepository->findByCondition($condition);
        // dd($widget->model);
       
        $repositoryInstance = loadClassInterface($widget->model);
        // dd($repositoryInstance);

        $condition = [
            ['publish', '=', 2]
        ];

        $paramRelation = explode('_', Str::snake($widget->model))[0].'s';
        // dd($paramRelation);

        if($widget->model == 'ProductCatalogue' && isset($extend) && $extend == true){
            $relation = [
                'languages' => function($query) use ($language){
                    $query->where('language_id', $language);
                },
                $paramRelation => function($query) use ($language) {
                    $this->applyLanguagesAndPromotions($query, $language);
                }
            ];
        }elseif(strpos($widget->model, 'Catalogue') && isset($extend) && $extend == true){
            $relation = [
                'languages' => function($query) use ($language){
                    $query->where('language_id', $language);
                },
                $paramRelation => function($query) use ($language) {
                    $query->where('publish', '=', 2)
                    ->with(['languages' => function($query) use ($language) {
                        $query->where('language_id', $language);
                    }]);
                }
            ];
        }
        else{
            $relation = [
                'languages' => function($query) use ($language){
                    $query->where('language_id', $language);
                },
            ];
        }

        $widgetItems = $repositoryInstance->findWhereInWithRelation('id', $widget->model_id, $condition, $relation);
        // dd($widgetItems->toArray());

        if($widget->model == 'ProductCatalogue' && isset($extend) && $extend == true){
            foreach($widgetItems as $widgetItem){
                // dd($widgetItem->id);
                $widgetItem->childrens = $this->getAllChildrenCatalogue($widgetItem, $language, $widget->model);
            }
        }
        // dd($widgetItems->toArray());

        // Tính toán products_count cho từng widgetItem và các children của nó
        if($widget->model == 'ProductCatalogue' && isset($extend) && $extend == true){
            foreach ($widgetItems as $widgetItem) {            
                $widgetItem->products_count = $this->productsCount($widgetItem);
            }
        }

        // dd($widgetItems->toArray());
        
        return $widgetItems;
    }


    private function productsCount($widgetItem){
        $productsCount = isset($widgetItem->products) ? count($widgetItem->products) : 0;
        if (isset($widgetItem->childrens)) {
            foreach ($widgetItem->childrens as $key => $val) {
                $productsCount += isset($val[0]->products) ? count($val[0]->products) : 0;
            }
        }
        return $productsCount;
    }


    private function getAllChildrenCatalogue($widgetItem, $language, string $model = ''){
        $repositoryInstance = loadClassInterface($model);
        // dd($repositoryInstance);

        $condition = [
            ['publish', '=', 2],
            ['lft', '>', $widgetItem->lft],
            ['rgt', '<', $widgetItem->rgt],
        ];
        $childrens = $repositoryInstance->findByConditions($condition);
        // dd($childrens);

        $temp = [];
        foreach($childrens as $children){
            // dd($children->id);
            $condition = [
                ['publish', '=', 2],
                ['id', '=', $children->id],
            ];
    
            $paramRelation = explode('_', Str::snake($model))[0].'s';
            // dd($paramRelation);
            $relation = [
                $paramRelation => function($query) use ($language) {
                    $this->applyLanguagesAndPromotions($query, $language);
                }
            ];
    
            $widgetItems = $repositoryInstance->findByConditionsWithRelation($condition, $relation);
            $temp[] = $widgetItems;
        }
        // dd($temp);
        return $temp;
    }


    private function applyLanguagesAndPromotions($query, $language)
    {
        $query->where('publish', '=', 2)
        ->with(['languages' => function($query) use ($language) {
            $query->where('language_id', $language);
        }])
        ->with('promotions', function($query) {
            $query->select(
                'promotions.id',
                'promotions.maxDiscountValue',
                'promotions.discountValue',
                'promotions.discountType',
                DB::raw(
                    "CASE
                        WHEN promotions.discountType = 'cash' THEN (SELECT price FROM products WHERE products.id = promotion_object_variant.object_id) - promotions.discountValue
                        WHEN promotions.discountType = 'percent' THEN (SELECT price FROM products WHERE products.id = promotion_object_variant.object_id) - LEAST((SELECT price FROM products WHERE products.id = promotion_object_variant.object_id) * promotions.discountValue / 100, promotions.maxDiscountValue)
                        ELSE (SELECT price FROM products WHERE products.id = promotion_object_variant.object_id)
                    END as discount"
                )
            );
            $query->where('publish', '=', 2);
            $query->where(function($query) {
                $query->where('endDate', '>', now())
                    ->orWhereNull('endDate');
            });
            $query->orderBy('discount', 'ASC');
            // $query->limit(1);
        });
    }
}
