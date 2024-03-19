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

    public function paginate(){
        $users=$this->userRepository->pagination(['id','email','phone','address','name','image','publish_at']);
        return $users;
    }
    public function createUser($request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send','repassword');//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            $payload['birthday']=$this->convertBirthdayDate($payload['birthday']);
            $payload['password']=Hash::make($payload['password']);
            //dd($payload);

            $user=$this->userRepository->create($payload);
            //dd($user);

            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }

    public function updateUser($id, $request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send');//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            $payload['birthday']=$this->convertBirthdayDate($payload['birthday']);
            //dd($payload);

            $user=$this->userRepository->update($id, $payload);
            //dd($user);

            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
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
            echo $ex->getMessage();die();
            return false;
        }
    }
}
