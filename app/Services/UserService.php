<?php

namespace App\Services;

use App\Services\Interfaces\UserServiceInterface;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
//thêm thư viện cho việc xử lý INSERT
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//thêm thư viện xử lý xử lý DATE
use Illuminate\Support\Carbon;
//thêm thư viện xử lý password
use Illuminate\Support\Facades\Hash;


/**
 * Class UserService
 * @package App\Services
 */
class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository=$userRepository;
    }

    public function paginate($request){//$request để tiến hành chức năng tìm kiếm
        $condition['keyword']=addslashes($request->input('keyword'));
        $condition['publish']=$request->input('publish');
        // Kiểm tra nếu giá trị publish là 0, thì gán lại thành null
        if ($condition['publish'] == '0') {
            $condition['publish'] = null;
        }
        //dd($condition);
        $perpage=$request->integer('perpage', 20);
        $users=$this->userRepository->pagination(['id','email','phone','address','name','image','publish','user_catalogue_id'], $condition,[], ['path'=> 'user/index'], $perpage);
        return $users;
    }
    public function createUser($request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send','repassword');//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            //$payload['birthday']=$this->convertBirthdayDate($payload['birthday']);
            $payload['password']=Hash::make($payload['password']);
            //dd($payload);

            $user=$this->userRepository->create($payload);
            //dd($user);

            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }

    public function updateUser($id, $request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send');//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            //$payload['birthday']=$this->convertBirthdayDate($payload['birthday']);
            //dd($payload);

            $user=$this->userRepository->update($id, $payload);
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
    public function deleteUser($id){
        DB::beginTransaction();
        try{
            $user=$this->userRepository->forceDelete($id);

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
            $user=$this->userRepository->update($post['modelId'], $payload);
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
            $user=$this->userRepository->updateByWhereIn('id', $post['id'], $payload);
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
            $user=$this->userRepository->deleteByWhereIn('id',$post['id']);
            //echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
}
