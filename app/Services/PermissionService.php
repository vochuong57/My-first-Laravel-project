<?php

namespace App\Services;

use App\Services\Interfaces\PermissionServiceInterface;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;
//thêm thư viện cho việc xử lý INSERT
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//thêm thư viện xử lý xử lý DATE
use Illuminate\Support\Carbon;
//thêm thư viện xử lý password
use Illuminate\Support\Facades\Hash;
//gọi thư viện userRepository để cập nhật trạng thái khi đã chọn thay đổi trạng thái của userCatalogue
//use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserService
 * @package App\Services
 */
class PermissionService implements PermissionServiceInterface
{
    protected $permissionRepository;
    //protected $userRepository;

    public function __construct(PermissionRepository $permissionRepository){
        $this->permissionRepository=$permissionRepository;
        //$this->userRepository=$userRepository;
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
        $permissions=$this->permissionRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perpage,
            ['path'=> 'permission/index'],
            ['id','DESC']
        );
        //dd($userCatalogues);
        return $permissions;
    }
    public function createPermission($request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send');//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            
            //vì chúng ta có khóa ngoại khi thêm bảng này mà khóa ngoại này là user_id thì đó là tài khoản đã đăng nhập thì
            $payload['user_id']=Auth::id();
            //dd($payload);
            $permission=$this->permissionRepository->create($payload);
            //dd($permission);
            //echo -1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }

    public function updatePermission($id, $request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send');//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            //dd($payload);

            $permission=$this->permissionRepository->update($id, $payload);
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
   
    public function deletePermission($id){
        DB::beginTransaction();
        try{
            $where=[
                ['id', '=', $id]
            ];
            $payload=['canonical'=>null];
            $this->permissionRepository->updateByWhere($where,$payload);
            $permission=$this->permissionRepository->delete($id);
           
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
            $permissions=$this->permissionRepository->deleteByWhereIn('id',$post['id']);
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
        return[
            'id',
            'name',
            'canonical'
        ];
    }
    
}
