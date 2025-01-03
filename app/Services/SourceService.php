<?php

namespace App\Services;

use App\Services\Interfaces\SourceServiceInterface;
use App\Repositories\Interfaces\SourceRepositoryInterface as SourceRepository;
//thêm thư viện cho việc xử lý INSERT
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//thêm thư viện xử lý xử lý DATE
use Illuminate\Support\Carbon;
//thêm thư viện xử lý password
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


/**
 * Class SourceService
 * @package App\Services
 */
class SourceService extends BaseService implements SourceServiceInterface
{
    protected $sourceRepository;

    public function __construct(SourceRepository $sourceRepository){
        $this->sourceRepository=$sourceRepository;
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
        $sources=$this->sourceRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perpage,
            ['path'=> 'source/index', 'groupBy' => $this->paginateSelect()],
            ['sources.id', 'DESC']
        );
        // dd($sources);
        
        return $sources;
    }
    public function createSource($request){
        DB::beginTransaction();
        try{
            $payload = $request->only('name', 'keyword', 'description', 'publish');
            // dd($payload);

            if($payload['publish'] == null || $payload['publish'] == 0){
                $payload['publish'] = 1;
            }
    
            $payload['user_id']=Auth::id();
            // dd($payload);

            $source = $this->sourceRepository->create($payload);
            // echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }

    public function updateSource($id, $request){
        DB::beginTransaction();
        try{
            $payload = $request->only('name', 'keyword', 'description', 'publish');
            // dd($payload);
            
            if($payload['publish'] == null || $payload['publish'] == 0){
                $payload['publish'] = 1;
            }

            $payload['user_id']=Auth::id();
            // dd($payload);

            $source = $this->sourceRepository->update($id, $payload);
            // echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }
    public function deleteSource($id){
        DB::beginTransaction();
        try{
            $source = $this->sourceRepository->findById($id);
            // dd($source);
           
            if($source){
                // echo '123'; die();
                $source=$this->sourceRepository->forceDelete($id);
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }

    public function updateStatus($post=[]){
        //echo 123; die();
        DB::beginTransaction();
        try{
            $payload[$post['field']]=(($post['value']==1)?2:1);
            
            //dd($payload);
            $source=$this->sourceRepository->update($post['modelId'], $payload);
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
            $source=$this->sourceRepository->updateByWhereIn('id', $post['id'], $payload);
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
            $source=$this->sourceRepository->deleteByWhereIn('id',$post['id']);
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
            'id','name','keyword','user_id','publish', 'description'
        ];
    }
}
