<?php

namespace App\Services;

use App\Services\Interfaces\{ModuleTemplate}ServiceInterface;
use App\Repositories\Interfaces\{ModuleTemplate}RepositoryInterface as {ModuleTemplate}Repository;
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
use App\Services\BaseService;//tiến hành chèn dữ liệu vào bảng ngoài cụ thể là {pivotTable}
use App\Classes\Nestedsetbie;
use Spatie\LaravelIgnition\Exceptions\CannotExecuteSolutionForNonLocalIp;
use Illuminate\Support\Str;

use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use App\Repositories\Interfaces\{ModuleTemplate}LanguageRepositoryInterface as {ModuleTemplate}LanguageRepository;


/**
 * Class UserService
 * @package App\Services
 */
class {ModuleTemplate}Service extends BaseService implements {ModuleTemplate}ServiceInterface
{
    protected ${moduleTemplate}Repository;
    protected $language;    
    protected $routerRepository;
    protected ${moduleTemplate}LanguageRepository;
    protected $controllerName = '{ModuleTemplate}Controller';

    public function __construct({ModuleTemplate}Repository ${moduleTemplate}Repository, RouterRepository $routerRepository, {ModuleTemplate}LanguageRepository ${moduleTemplate}LanguageRepository){
        $this->{moduleTemplate}Repository=${moduleTemplate}Repository;
        $this->language=$this->currentLanguage();
        $this->nestedset=new Nestedsetbie([
            'table'=>'{tableNames}',
            'foreignkey'=>'{relation}_catalogue_id',
            'language_id'=>$this->currentLanguage(),
        ]);
        $this->routerRepository=$routerRepository;
        $this->{moduleTemplate}LanguageRepository=${moduleTemplate}LanguageRepository;
    }

    public function paginate($request, $languageId){//$request để tiến hành chức năng tìm kiếm
        //dd($request);
        //echo 123; die();
        $condition['keyword']=addslashes($request->input('keyword'));
        $condition['publish']=$request->input('publish');
        // Kiểm tra nếu giá trị publish là 0, thì gán lại thành null
        if ($condition['publish'] == '0') {
            $condition['publish'] = null;
        }
        $condition['where']=[
            ['tb2.language_id', '=', $languageId],
        ];
        //dd($condition);
        $perpage=$request->integer('perpage', 20);
        ${moduleTemplate}s=$this->{moduleTemplate}Repository->pagination(
            $this->paginateSelect(),
            $condition,
            $perpage,
            ['path'=> '{relation}/catalogue/index'],
            [
                '{tableNames}.lft', 'ASC'
            ],
            [
                ['{pivotTable} as tb2','tb2.{moduleKey}','=','{tableNames}.id']
            ]
  
        );
        //dd(${moduleTemplate}s);
        return ${moduleTemplate}s;
    }
    public function create{ModuleTemplate}($request, $languageId){
        DB::beginTransaction();
        try{
            ${moduleTemplate} = $this->createCatalogue($request);
            if(${moduleTemplate}->id>0){
                $this->updateLanguageForCatalogue($request, ${moduleTemplate}, $languageId);
                $this->createRouter($request, ${moduleTemplate}, $this->controllerName);
                $this->nestedset();
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }

    public function update{ModuleTemplate}($id, $request, $languageId){
        DB::beginTransaction();
        try{
            ${moduleTemplate}=$this->{moduleTemplate}Repository->findById($id);
            $flag = $this->updateCatalogue($request, $id);
            if($flag==TRUE){
                $this->updateLanguageForCatalogue($request, ${moduleTemplate}, $languageId);
                $this->updateRouter($request, ${moduleTemplate}, $this->controllerName);
                $this->nestedset();
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
   
    public function delete{ModuleTemplate}($id, $languageId){
        DB::beginTransaction();
        try{
            //echo '123'; die();
            //Đầu tiền xóa đi bản dịch đó khỏi {pivotTable}
            $where=[
                ['{moduleKey}', '=', $id],
                ['language_id', '=', $languageId]
            ];
            $this->{moduleTemplate}LanguageRepository->deleteByWhere($where);

            //Sau khi xóa xong thì nó tiếp tục kiểm tra xem thử là còn cái {relation}_id đó trong {pivotTable} không
            $condition=[
                ['{moduleKey}', '=', $id]
            ];
            $flag = $this->{moduleTemplate}LanguageRepository->findByCondition($condition);

            //Nếu không tìm thấy nữa thì ta mới tiến hành xóa đi {relation} và router
            if(!$flag){
                ${relation}=$this->{moduleTemplate}Repository->forceDelete($id);

                $conditionByRouter=[
                    ['module_id', '=', $id]
                ];
                $router=$this->routerRepository->findByCondition($conditionByRouter);
                //dd($router->id);
                $this->routerRepository->forceDelete($router->id);//router chỉ hiện những cái canonical đang tồn tại sẽ không có xóa mềm
            }
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
            ${moduleTemplate}=$this->{moduleTemplate}Repository->update($post['modelId'], $payload);
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
            ${moduleTemplate}s=$this->{moduleTemplate}Repository->updateByWhereIn('id', $post['id'], $payload);
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
            ${moduleTemplate}s=$this->{moduleTemplate}Repository->deleteByWhereIn('id',$post['id']);
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
            '{tableNames}.id',
            '{tableNames}.publish',
            '{tableNames}.image',
            '{tableNames}.level',
            '{tableNames}.order',
            'tb2.name',
            'tb2.canonical'
        ];
    }
    private function payload(){
        return [
            'parent_id',
            'follow',
            'publish',
            'image',
            'album'
        ];
    }

    private function payloadLanguage(){
        return [
            'name',
            'description',
            'content',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'canonical'
        ];
    }
    
    private function createCatalogue($request){
        $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
        //dd($payload);
        //vì chúng ta có khóa ngoại khi thêm bảng này mà khóa ngoại này là user_id thì đó là tài khoản đã đăng nhập thì
        $payload['user_id']=Auth::id();
        $payload['album']=$this->formatAlbum($request);
        if($payload['publish'] == null || $payload['publish'] == 0){
            $payload['publish'] = 1;
        }
        //dd($payload);
        ${moduleTemplate}=$this->{moduleTemplate}Repository->create($payload);
        //dd($language);
        //echo -1; die();
        //echo ${moduleTemplate}->id; die();
        return ${moduleTemplate};
    }
    private function updateCatalogue($request, $id){
        $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
        $payload['album']=$this->formatAlbum($request);
        //dd($payload);
        $flag=$this->{moduleTemplate}Repository->update($id,$payload);
        return $flag;
    }
    private function updateLanguageForCatalogue($request, ${moduleTemplate}, $languageId){
        $payloadLanguage=$this->formatLanguagePayload($request, ${moduleTemplate}, $languageId);
        ${moduleTemplate}->languages()->detach($languageId, ${moduleTemplate}->id);
        $language = $this->{moduleTemplate}Repository->createPivot(${moduleTemplate},$payloadLanguage,'languages');
        //dd($language); die();
        return $language;
    }
    private function formatLanguagePayload($request, ${moduleTemplate}, $languageId){
        $payloadLanguage = $request->only($this->payloadLanguage());
        //dd($payloadLanguage);
        //dd($this->currentLanguage());
        $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
        $payloadLanguage['language_id']=$languageId;
        $payloadLanguage['{moduleKey}']=${moduleTemplate}->id;
        //dd($payloadLanguage);
        return $payloadLanguage;
    }
    
}

