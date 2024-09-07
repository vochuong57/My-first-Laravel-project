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
    public function createWidget($request){
        DB::beginTransaction();
        try{
            $payload = $request->only('name', 'keyword', 'description', 'short_code', 'model');
            // dd($payload);
            $payload['model_id'] = $this->formatJson($request, 'widget.id');
            // dd($payload);
            $payload['album'] = $this->formatJson($request, 'album');
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

    public function updateWidget($id, $request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send');//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            //$payload['birthday']=$this->convertBirthdayDate($payload['birthday']);
            //dd($payload);

            $widget=$this->widgetRepository->update($id, $payload);
            //dd($widget);

            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
    public function deleteWidget($id){
        DB::beginTransaction();
        try{
            $widget=$this->widgetRepository->forceDelete($id);

            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
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
            'id','name','keyword','short_code','user_id', 'model' ,'publish'
        ];
    }
}
