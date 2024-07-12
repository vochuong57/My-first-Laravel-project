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
use App\Services\BaseService;//tiến hành chèn dữ liệu vào bảng ngoài cụ thể là {moduleTemplate}_catalogue_language
use Request;
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
    protected $routerRepository;
    protected ${moduleTemplate}LanguageRepository;
    protected $controllerName = '{ModuleTemplate}Controller';

    public function __construct({ModuleTemplate}Repository ${moduleTemplate}Repository, RouterRepository $routerRepository, {ModuleTemplate}LanguageRepository ${moduleTemplate}LanguageRepository){
        $this->{moduleTemplate}Repository=${moduleTemplate}Repository;
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
            ['path'=> '{moduleTemplate}/index', 'groupBy' => $this->paginateSelect()],
            ['{tableNames}.id', 'DESC'],
            [
                ['{pivotTable} as tb2','tb2.{moduleKey}','=','{tableNames}.id'],//dùng cho hiển thị nội dung table
                ['{relationTable2} as tb3','{tableNames}.id', '=', 'tb3.{moduleKey}']//dùng cho whereRaw lọc tìm kiếm bài viết theo nhóm bài viêt
            ],
            ['{relationCatalogue}s'],//là function {moduleTemplate}_catalogues của Model/{ModuleTemplate}
            $this->whereRaw($request),
        );
        //dd(${moduleTemplate}s);
        
        return ${moduleTemplate}s;
    }
    public function create{ModuleTemplate}($request, $languageId){
        DB::beginTransaction();
        try{
            ${moduleTemplate} = $this->createTable{ModuleTemplate}($request);
            
            if(${moduleTemplate}->id>0){
                $this->updateLanguageFor{ModuleTemplate}($request, ${moduleTemplate}, $languageId);
                $this->createRouter($request, ${moduleTemplate}, $this->controllerName, $languageId);
                
                //xử lí add dữ liệu vào {relationTable2}
                $catalogue=$this->mergeCatalogue($request);
                //dd($catalogue);
                ${moduleTemplate}->{relationCatalogue}s()->sync($catalogue);//{moduleTemplate}_catalogues() là function của Model/{ModuleTemplate}
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
            $flag=$this->updateTable{ModuleTemplate}($request, $id);
            //dd($flag);
            if($flag==TRUE){
                $this->updateLanguageFor{ModuleTemplate}($request, ${moduleTemplate}, $languageId);
                $this->updateRouter($request, ${moduleTemplate}, $this->controllerName, $languageId);

                $catalogue=$this->mergeCatalogue($request);
                //dd($catalogue);
                ${moduleTemplate}->{relationCatalogue}s()->sync($catalogue);
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

            //Tiếp theo xóa đi canonical của bản dịch đó khỏi routers
            $findRouter=[
                ['module_id', '=', $id],
                ['language_id', '=', $languageId],
                ['controller', '=', 'App\Http\Controllers\Frontend\{ModuleTemplate}Controller'],
            ];
            $this->routerRepository->deleteByWhere($findRouter);

            //Sau khi xóa xong thì nó tiếp tục kiểm tra xem thử là còn cái {moduleTemplate}_id đó trong {moduleTemplate}_language không
            $condition=[
                ['{moduleKey}', '=', $id]
            ];
            $flag = $this->{moduleTemplate}LanguageRepository->findByCondition($condition);

            //Nếu không tìm thấy nữa thì ta mới tiến hành xóa đi {ModuleTemplate}
            if(!$flag){
                ${moduleTemplate}=$this->{moduleTemplate}Repository->forceDelete($id);
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
            ${moduleTemplate}Language=$this->{moduleTemplate}LanguageRepository->deleteByWhereIn('{moduleTemplate}_id',$post['id'],$post['languageId']);
            //echo 1; die();

            $languageId = $post['languageId'];
            
            foreach($post['id'] as $id){

                // Tiếp tục xóa tiếp canonical ở bảng routers của từng id được chọn 
                $findRouter=[
                    ['module_id', '=', $id],
                    ['language_id', '=', $languageId],
                    ['controller', '=', 'App\Http\Controllers\Frontend\{ModuleTemplate}Controller'],
                ];
                $this->routerRepository->deleteByWhere($findRouter);

                // Sau khi xóa xong thì nó tiếp tục kiểm tra xem thử là còn cái {moduleTemplate}_id đó trong {moduleTemplate}_language không
                $condition=[
                    ['{moduleTemplate}_id', '=', $id]
                ];
                $flag = $this->{moduleTemplate}LanguageRepository->findByCondition($condition);
                
                // Nếu không tìm thấy nữa thì ta mới tiến hành xóa đi {moduleTemplate}s
                if(!$flag){
                    ${moduleTemplate}=$this->{moduleTemplate}Repository->forceDelete($id);
                }
            }
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
            '{tableNames}.order',
            'tb2.name',
            'tb2.canonical',
            'tb2.language_id',
        ];
    }
    private function payload(){
        return [
            'follow',
            'publish',
            'image',
            'album',
            '{foreignKey}'
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
            'canonical',
        ];
    }

    //merge dữ liệu từ hai mảng khác nhau vào chung một bảng
    private function mergeCatalogue($request){
        $catalogueInput = $request->input('catalogue');
        
        // Kiểm tra nếu $catalogueInput tồn tại và không rỗng
        if ($request->filled('catalogue') && is_array($catalogueInput)) {
            return array_unique(array_merge($catalogueInput, [$request->{moduleTemplate}_catalogue_id]));
        } else {
            // Nếu không tồn tại hoặc rỗng, trả về chỉ mảng chứa $request->{moduleTemplate}_catalogue_id
            return [$request->{moduleTemplate}_catalogue_id];
        }
    }
    

    //whereRaw tìm kiếm bài viết theo nhóm bài viết mở rộng
    private function whereRaw($request){
        $rawCondition = [];
        if($request->integer('{moduleTemplate}_catalogue_id')>0){
            $rawCondition['whereRaw']=[
                [
                    'tb3.{foreignKey} IN (
                        SELECT id
                        FROM {relationCatalogue}s
                        WHERE lft >= (SELECT lft FROM {relationCatalogue}s as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM {relationCatalogue}s as pc WHERE pc.id = ?)
                    )',
                    [$request->integer('{moduleTemplate}_catalogue_id'), $request->integer('{moduleTemplate}_catalogue_id')]
                ]
            ];
        }
        return $rawCondition;
    }
    //----TỐI ƯU SOURCE CODE
    private function createTable{ModuleTemplate}($request){
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
    private function updateTable{ModuleTemplate}($request, $id){
        $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
        $payload['album']=$this->formatAlbum($request);
        //dd($payload);
        $flag=$this->{moduleTemplate}Repository->update($id,$payload);
        return $flag;
    }
    //Cho bảng {moduleTemplate}_language
    private function updateLanguageFor{ModuleTemplate}($request, ${moduleTemplate}, $languageId){
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
        $payloadLanguage['{moduleTemplate}_id']=${moduleTemplate}->id;
        //dd($payloadLanguage);
        return $payloadLanguage;
    }
}

