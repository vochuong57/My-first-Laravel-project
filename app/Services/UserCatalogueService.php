<?php

namespace App\Services;

use App\Services\Interfaces\UserCatalogueServiceInterface;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
//thêm thư viện cho việc xử lý INSERT
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//thêm thư viện xử lý xử lý DATE
use Illuminate\Support\Carbon;
//thêm thư viện xử lý password
use Illuminate\Support\Facades\Hash;
//gọi thư viện userRepository để cập nhật trạng thái khi đã chọn thay đổi trạng thái của userCatalogue
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;


/**
 * Class UserService
 * @package App\Services
 */
class UserCatalogueService implements UserCatalogueServiceInterface
{
    protected $userCatalogueRepository;
    protected $userRepository;

    public function __construct(UserCatalogueRepository $userCatalogueRepository, UserRepository $userRepository){
        $this->userCatalogueRepository=$userCatalogueRepository;
        $this->userRepository=$userRepository;
    }

    public function paginate($request){//$request để tiến hành chức năng tìm kiếm
        //dd($request);
        //echo 123; die();
        $condition['keyword']=addslashes($request->input('keyword'));
        $condition['publish']=$request->input('publish');
        // Kiểm tra nếu giá trị publish là 0, thì gán lại thành null
        if ($condition['publish'] == '0') {
            $condition['publish'] = null;
        }
        //dd($condition);
        $perpage=$request->integer('perpage', 20);
        $userCatalogues=$this->userCatalogueRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perpage, 
            ['path'=> 'user/catalogue/index'], 
            [],
            [], 
            ['users']
        );
        //dd($userCatalogues);
        return $userCatalogues;
    }
    public function createUserCatalogue($request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send');//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            //dd($payload);

            $userCatalogue=$this->userCatalogueRepository->create($payload);
            //dd($user);

            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }

    public function updateUserCatalogue($id, $request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send');//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            //$payload['birthday']=$this->convertBirthdayDate($payload['birthday']);
            //dd($payload);

            $userCatalogue=$this->userCatalogueRepository->update($id, $payload);
            //echo 1; die();
            //dd($user);

            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
    public function convertBirthdayDate($birthday=''){
        $carbonDate=Carbon::createFromFormat('Y-m-d', $birthday);
        $birthday=$carbonDate->format('Y-m-d H:i:s');
        return $birthday;
    }
    public function deleteUserCatalogue($id){
        DB::beginTransaction();
        try{
            $userCatalogue=$this->userCatalogueRepository->delete($id);

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
            $userCatalogue=$this->userCatalogueRepository->update($post['modelId'], $payload);
            //echo 1; die();
            $this->changeUserStatus($post, $payload[$post['field']]);
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
            $userCatalogues=$this->userCatalogueRepository->updateByWhereIn('id', $post['id'], $payload);
            //echo 1; die();
            $this->changeUserStatus($post,$post['value']);
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
            $userCatalogues=$this->userCatalogueRepository->deleteByWhereIn('id',$post['id']);
            //echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
    private function changeUserStatus($post, $value){
       
        DB::beginTransaction();
        try{
            //dd($post);
            $array=[];
            if(isset($post['modelId'])){
                $array[]=$post['modelId'];
            }else{
                $array=$post['id'];
            }//push vào trong mảng để update theo kiểu by where in
            //dd($post);
            $payload[$post['field']]=$value;
            $this->userRepository->updateByWhereIn('user_catalogue_id', $array, $payload);
            //echo 123; die();
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
            'id','name','description','publish'
        ];
    }
}
