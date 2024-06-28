<?php

namespace App\Services;

use App\Services\Interfaces\LanguageServiceInterface;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;
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
class LanguageService implements LanguageServiceInterface
{
    protected $languageRepository;
    //protected $userRepository;

    public function __construct(LanguageRepository $languageRepository){
        $this->languageRepository=$languageRepository;
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
        $languagesIndex=$this->languageRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perpage,
            ['path'=> 'language/index']
        );
        //dd($userCatalogues);
        return $languagesIndex;
    }
    public function createLanguage($request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send');//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            
            //vì chúng ta có khóa ngoại khi thêm bảng này mà khóa ngoại này là user_id thì đó là tài khoản đã đăng nhập thì
            $payload['user_id']=Auth::id();
            //dd($payload);
            $language=$this->languageRepository->create($payload);
            //dd($language);
            //echo -1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }

    public function updateLanguage($id, $request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send');//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            //dd($payload);

            $language=$this->languageRepository->update($id, $payload);
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
   
    public function deleteLanguage($id){
        DB::beginTransaction();
        try{
            $language=$this->languageRepository->delete($id);

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
            $language=$this->languageRepository->update($post['modelId'], $payload);
            //echo 1; die();
            //$this->changeUserStatus($post, $payload[$post['field']]);
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
            $languages=$this->languageRepository->updateByWhereIn('id', $post['id'], $payload);
            //echo 1; die();
            //$this->changeUserStatus($post,$post['value']);
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
            $languages=$this->languageRepository->deleteByWhereIn('id',$post['id']);
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
            'id','name','canonical','publish','description','image'
        ];
    }
    
    //Cập nhật trạng thái của ngôn ngữ đang chọn
    public function switch($id){
        DB::beginTransaction();
        try{
            $language = $this->languageRepository->update($id, ['current' => 1]);
            $payload = ['current' => 0];
            $where = [
                ['id', '!=', $id],
            ];
            $this->languageRepository->updateByWhere($where, $payload);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
        
    }

    // Cập nhật bản dịch module
    public function saveTranslate($option, $request){
        DB::beginTransaction();
        try{
            $payload=[
                'name' => $request->input('translate_name'),
                'description' => $request->input('translate_description'),
                'content' => $request->input('translate_content'),
                'meta_title' => $request->input('translate_meta_title'),
                'meta_keyword' => $request->input('translate_meta_keyword'),
                'meta_description' => $request->input('translate_meta_description'),
                'canonical' => $request->input('translate_canonical'),
                $this->coverModelToField($option['model']) => $option['id'],
                'language_id' => $option['languageId'],
            ];

            // dd($payload);

            // Lấy ra đúng repository tương ứng theo từng Model
            $repositoryInterfaceNamespace='\App\Repositories\\'.ucfirst($option['model']).'Repository';
            // echo $repositoryInterfaceNamespace; die();
            if(class_exists($repositoryInterfaceNamespace)){
                $repositoryInstance=app($repositoryInterfaceNamespace);
            }

            $model = $repositoryInstance->findById($option['id']);
            // dd($model);
            $model->languages()->detach([$option['languageId'], $model->id]);
            $repositoryInstance->createPivot($model, $payload, 'languages');

            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }

    private function coverModelToField($model){
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model));
        return $temp.'_id';
    }
}
