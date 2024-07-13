<?php

namespace App\Services;

use App\Services\Interfaces\AttributeServiceInterface;
use App\Repositories\Interfaces\AttributeRepositoryInterface as AttributeRepository;
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
use App\Services\BaseService;//tiến hành chèn dữ liệu vào bảng ngoài cụ thể là attribute_catalogue_language
use Request;
use Spatie\LaravelIgnition\Exceptions\CannotExecuteSolutionForNonLocalIp;
use Illuminate\Support\Str;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use App\Repositories\Interfaces\AttributeLanguageRepositoryInterface as AttributeLanguageRepository;

/**
 * Class UserService
 * @package App\Services
 */
class AttributeService extends BaseService implements AttributeServiceInterface
{
    protected $attributeRepository;
    protected $routerRepository;
    protected $attributeLanguageRepository;
    protected $controllerName = 'AttributeController';

    public function __construct(AttributeRepository $attributeRepository, RouterRepository $routerRepository, AttributeLanguageRepository $attributeLanguageRepository){
        $this->attributeRepository=$attributeRepository;
        $this->routerRepository=$routerRepository;
        $this->attributeLanguageRepository=$attributeLanguageRepository;
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
        $attributes=$this->attributeRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perpage,
            ['path'=> 'attribute/index', 'groupBy' => $this->paginateSelect()],
            ['attributes.id', 'DESC'],
            [
                ['attribute_language as tb2','tb2.attribute_id','=','attributes.id'],//dùng cho hiển thị nội dung table
                ['attribute_catalogue_attribute as tb3','attributes.id', '=', 'tb3.attribute_id']//dùng cho whereRaw lọc tìm kiếm bài viết theo nhóm bài viêt
            ],
            ['attribute_catalogues'],//là function attribute_catalogues của Model/Attribute
            $this->whereRaw($request),
        );
        //dd($attributes);
        
        return $attributes;
    }
    public function createAttribute($request, $languageId){
        DB::beginTransaction();
        try{
            $attribute = $this->createTableAttribute($request);
            
            if($attribute->id>0){
                $this->updateLanguageForAttribute($request, $attribute, $languageId);
                $this->createRouter($request, $attribute, $this->controllerName, $languageId);
                
                //xử lí add dữ liệu vào attribute_catalogue_attribute
                $catalogue=$this->mergeCatalogue($request);
                //dd($catalogue);
                $attribute->attribute_catalogues()->sync($catalogue);//attribute_catalogues() là function của Model/Attribute
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }

    public function updateAttribute($id, $request, $languageId){
        DB::beginTransaction();
        try{
            $attribute=$this->attributeRepository->findById($id);
            $flag=$this->updateTableAttribute($request, $id);
            //dd($flag);
            if($flag==TRUE){
                $this->updateLanguageForAttribute($request, $attribute, $languageId);
                $this->updateRouter($request, $attribute, $this->controllerName, $languageId);

                $catalogue=$this->mergeCatalogue($request);
                //dd($catalogue);
                $attribute->attribute_catalogues()->sync($catalogue);
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
   
    public function deleteAttribute($id, $languageId){
        DB::beginTransaction();
        try{
            //echo '123'; die();
            //Đầu tiền xóa đi bản dịch đó khỏi attribute_language
            $where=[
                ['attribute_id', '=', $id],
                ['language_id', '=', $languageId]
            ];
            $this->attributeLanguageRepository->deleteByWhere($where);

            //Tiếp theo xóa đi canonical của bản dịch đó khỏi routers
            $findRouter=[
                ['module_id', '=', $id],
                ['language_id', '=', $languageId],
                ['controller', '=', 'App\Http\Controllers\Frontend\AttributeController'],
            ];
            $this->routerRepository->deleteByWhere($findRouter);

            //Sau khi xóa xong thì nó tiếp tục kiểm tra xem thử là còn cái attribute_id đó trong attribute_language không
            $condition=[
                ['attribute_id', '=', $id]
            ];
            $flag = $this->attributeLanguageRepository->findByCondition($condition);

            //Nếu không tìm thấy nữa thì ta mới tiến hành xóa đi Attribute
            if(!$flag){
                $attribute=$this->attributeRepository->forceDelete($id);
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
            $attribute=$this->attributeRepository->update($post['modelId'], $payload);
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
            $attributes=$this->attributeRepository->updateByWhereIn('id', $post['id'], $payload);
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
            $attributeLanguage=$this->attributeLanguageRepository->deleteByWhereIn('attribute_id',$post['id'],$post['languageId']);
            //echo 1; die();

            $languageId = $post['languageId'];
            
            foreach($post['id'] as $id){

                // Tiếp tục xóa tiếp canonical ở bảng routers của từng id được chọn 
                $findRouter=[
                    ['module_id', '=', $id],
                    ['language_id', '=', $languageId],
                    ['controller', '=', 'App\Http\Controllers\Frontend\AttributeController'],
                ];
                $this->routerRepository->deleteByWhere($findRouter);

                // Sau khi xóa xong thì nó tiếp tục kiểm tra xem thử là còn cái attribute_id đó trong attribute_language không
                $condition=[
                    ['attribute_id', '=', $id]
                ];
                $flag = $this->attributeLanguageRepository->findByCondition($condition);
                
                // Nếu không tìm thấy nữa thì ta mới tiến hành xóa đi attributes
                if(!$flag){
                    $attribute=$this->attributeRepository->forceDelete($id);
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
            'attributes.id',
            'attributes.publish',
            'attributes.image',
            'attributes.order',
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
            'attribute_catalogue_id'
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
            return array_unique(array_merge($catalogueInput, [$request->attribute_catalogue_id]));
        } else {
            // Nếu không tồn tại hoặc rỗng, trả về chỉ mảng chứa $request->attribute_catalogue_id
            return [$request->attribute_catalogue_id];
        }
    }
    

    //whereRaw tìm kiếm bài viết theo nhóm bài viết mở rộng
    private function whereRaw($request){
        $rawCondition = [];
        if($request->integer('attribute_catalogue_id')>0){
            $rawCondition['whereRaw']=[
                [
                    'tb3.attribute_catalogue_id IN (
                        SELECT id
                        FROM attribute_catalogues
                        WHERE lft >= (SELECT lft FROM attribute_catalogues as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM attribute_catalogues as pc WHERE pc.id = ?)
                    )',
                    [$request->integer('attribute_catalogue_id'), $request->integer('attribute_catalogue_id')]
                ]
            ];
        }
        return $rawCondition;
    }
    //----TỐI ƯU SOURCE CODE
    private function createTableAttribute($request){
        $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
        //dd($payload);
        //vì chúng ta có khóa ngoại khi thêm bảng này mà khóa ngoại này là user_id thì đó là tài khoản đã đăng nhập thì
        $payload['user_id']=Auth::id();
        $payload['album']=$this->formatAlbum($request);
        if($payload['publish'] == null || $payload['publish'] == 0){
            $payload['publish'] = 1;
        }
        //dd($payload);
        $attribute=$this->attributeRepository->create($payload);
        //dd($language);
        //echo -1; die();
        //echo $attribute->id; die();
        return $attribute;
    }
    private function updateTableAttribute($request, $id){
        $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
        $payload['album']=$this->formatAlbum($request);
        //dd($payload);
        $flag=$this->attributeRepository->update($id,$payload);
        return $flag;
    }
    //Cho bảng attribute_language
    private function updateLanguageForAttribute($request, $attribute, $languageId){
        $payloadLanguage=$this->formatLanguagePayload($request, $attribute, $languageId);
        $attribute->languages()->detach($languageId, $attribute->id);
        $language = $this->attributeRepository->createPivot($attribute,$payloadLanguage,'languages');
        //dd($language); die();
        return $language;
    }
    private function formatLanguagePayload($request, $attribute, $languageId){
        $payloadLanguage = $request->only($this->payloadLanguage());
        //dd($payloadLanguage);
        //dd($this->currentLanguage());
        $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
        $payloadLanguage['language_id']=$languageId;
        $payloadLanguage['attribute_id']=$attribute->id;
        //dd($payloadLanguage);
        return $payloadLanguage;
    }
}

