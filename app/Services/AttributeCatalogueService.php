<?php

namespace App\Services;

use App\Services\Interfaces\AttributeCatalogueServiceInterface;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
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
use App\Classes\Nestedsetbie;
use Spatie\LaravelIgnition\Exceptions\CannotExecuteSolutionForNonLocalIp;
use Illuminate\Support\Str;

use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use App\Repositories\Interfaces\AttributeCatalogueLanguageRepositoryInterface as AttributeCatalogueLanguageRepository;
use App\Repositories\Interfaces\AttributeRepositoryInterface as AttributeRepository;
use App\Repositories\Interfaces\AttributeLanguageRepositoryInterface as AttributeLanguageRepository;


/**
 * Class UserService
 * @package App\Services
 */
class AttributeCatalogueService extends BaseService implements AttributeCatalogueServiceInterface
{
    protected $attributeCatalogueRepository;
    protected $language;    
    protected $routerRepository;
    protected $attributeCatalogueLanguageRepository;
    protected $attributeRepository;
    protected $attributeLanguageRepository;
    protected $controllerName = 'AttributeCatalogueController';

    public function __construct(AttributeCatalogueRepository $attributeCatalogueRepository, RouterRepository $routerRepository, AttributeCatalogueLanguageRepository $attributeCatalogueLanguageRepository, AttributeRepository $attributeRepository, AttributeLanguageRepository $attributeLanguageRepository){
        $this->attributeCatalogueRepository=$attributeCatalogueRepository;
        $this->language=$this->currentLanguage();
        $this->nestedset=new Nestedsetbie([
            'table'=>'attribute_catalogues',
            'foreignkey'=>'attribute_catalogue_id',
            'language_id'=>$this->currentLanguage(),
        ]);
        $this->routerRepository=$routerRepository;
        $this->attributeCatalogueLanguageRepository=$attributeCatalogueLanguageRepository;
        $this->attributeRepository=$attributeRepository;
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
        $attributeCatalogues=$this->attributeCatalogueRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perpage,
            ['path'=> 'attribute/catalogue/index'],
            [
                'attribute_catalogues.lft', 'ASC'
            ],
            [
                ['attribute_catalogue_language as tb2','tb2.attribute_catalogue_id','=','attribute_catalogues.id']
            ]
  
        );
        //dd($attributeCatalogues);
        return $attributeCatalogues;
    }
    public function createAttributeCatalogue($request, $languageId){
        DB::beginTransaction();
        try{
            $attributeCatalogue = $this->createCatalogue($request);
            if($attributeCatalogue->id>0){
                $this->updateLanguageForCatalogue($request, $attributeCatalogue, $languageId);
                $this->createRouter($request, $attributeCatalogue, $this->controllerName, $languageId);
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

    public function updateAttributeCatalogue($id, $request, $languageId){
        DB::beginTransaction();
        try{
            $attributeCatalogue=$this->attributeCatalogueRepository->findById($id);
            $flag = $this->updateCatalogue($request, $id);
            if($flag==TRUE){
                $this->updateLanguageForCatalogue($request, $attributeCatalogue, $languageId);
                $this->updateRouter($request, $attributeCatalogue, $this->controllerName, $languageId);
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
   
    public function deleteAttributeCatalogue($id, $languageId){
        DB::beginTransaction();
        try{
            //echo '123'; die();
            //Đầu tiền xóa đi bản dịch đó khỏi attribute_catalogue_language
            $where=[
                ['attribute_catalogue_id', '=', $id],
                ['language_id', '=', $languageId]
            ];
            $this->attributeCatalogueLanguageRepository->deleteByWhere($where);

            //Tiếp theo xóa đi canonical của bản dịch đó khỏi routers
            $findRouter=[
                ['module_id', '=', $id],
                ['language_id', '=', $languageId],
                ['controller', '=', 'App\Http\Controllers\Frontend\AttributeCatalogueController'],
            ];
            $this->routerRepository->deleteByWhere($findRouter);

            //Sau khi xóa xong thì nó tiếp tục kiểm tra xem thử là còn cái attribute_catalogue_id đó trong attribute_catalogue_language không
            $condition=[
                ['attribute_catalogue_id', '=', $id]
            ];
            $flag = $this->attributeCatalogueLanguageRepository->findByCondition($condition);

            //Nếu không tìm thấy nữa thì ta mới tiến hành xóa đi AttributeCatalogue
            if(!$flag){
                $attributeCatalogue=$this->attributeCatalogueRepository->forceDelete($id);
            }

            //--------------------------Xóa cho module chi tiết--------------------------
            $attributes = $this->attributeRepository->findByConditions([
                ['attribute_catalogue_id', '=', $id],
            ]);

            // dd($attributes);
            foreach ($attributes as $attribute) {
                $whereDetail=[
                    ['attribute_id', '=', $attribute->id],
                    ['language_id', '=', $languageId]
                ];
                //Xóa đi dữ liệu tương ứng của bảng attributes, attribute_language theo attribute_id và language_id đang chọn
                $this->attributeLanguageRepository->deleteByWhere($whereDetail);

                //Tiếp theo xóa đi canonical của bản dịch đó khỏi routers
                $findRouterDetail=[
                    ['module_id', '=', $attribute->id],
                    ['language_id', '=', $languageId],
                    ['controller', '=', 'App\Http\Controllers\Frontend\AttributeController'],
                ];
                $this->routerRepository->deleteByWhere($findRouterDetail);

                //Sau khi xóa xong thì nó tiếp tục kiểm tra xem thử là còn cái attribute_id đó trong attribute_language không
                $conditionDetail=[
                    ['attribute_id', '=', $attribute->id]
                ];
                $flag = $this->attributeLanguageRepository->findByCondition($conditionDetail);

                //Nếu không tìm thấy nữa thì ta mới tiến hành xóa đi attribute
                if(!$flag){
                    $this->attributeRepository->forceDelete($attribute->id);
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
    public function updateStatus($post=[]){
        //echo 123; die();
        DB::beginTransaction();
        try{
            $payload[$post['field']]=(($post['value']==1)?2:1);
            
            //dd($payload);
            $attributeCatalogue=$this->attributeCatalogueRepository->update($post['modelId'], $payload);
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
            $attributeCatalogues=$this->attributeCatalogueRepository->updateByWhereIn('id', $post['id'], $payload);
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
            $attributeCatalogues=$this->attributeCatalogueRepository->deleteByWhereIn('id',$post['id']);
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
            'attribute_catalogues.id',
            'attribute_catalogues.publish',
            'attribute_catalogues.image',
            'attribute_catalogues.level',
            'attribute_catalogues.order',
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
        $attributeCatalogue=$this->attributeCatalogueRepository->create($payload);
        //dd($language);
        //echo -1; die();
        //echo $attributeCatalogue->id; die();
        return $attributeCatalogue;
    }
    private function updateCatalogue($request, $id){
        $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
        $payload['album']=$this->formatAlbum($request);
        //dd($payload);
        $flag=$this->attributeCatalogueRepository->update($id,$payload);
        return $flag;
    }
    private function updateLanguageForCatalogue($request, $attributeCatalogue, $languageId){
        $payloadLanguage=$this->formatLanguagePayload($request, $attributeCatalogue, $languageId);
        $attributeCatalogue->languages()->detach($languageId, $attributeCatalogue->id);
        $language = $this->attributeCatalogueRepository->createPivot($attributeCatalogue,$payloadLanguage,'languages');
        //dd($language); die();
        return $language;
    }
    private function formatLanguagePayload($request, $attributeCatalogue, $languageId){
        $payloadLanguage = $request->only($this->payloadLanguage());
        //dd($payloadLanguage);
        //dd($this->currentLanguage());
        $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
        $payloadLanguage['language_id']=$languageId;
        $payloadLanguage['attribute_catalogue_id']=$attributeCatalogue->id;
        //dd($payloadLanguage);
        return $payloadLanguage;
    }
    
}

