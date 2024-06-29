<?php

namespace App\Services;

use App\Services\Interfaces\PostCatalogueServiceInterface;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
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
use App\Services\BaseService;//tiến hành chèn dữ liệu vào bảng ngoài cụ thể là post_catalogue_language
use App\Classes\Nestedsetbie;
use Spatie\LaravelIgnition\Exceptions\CannotExecuteSolutionForNonLocalIp;
use Illuminate\Support\Str;

use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use App\Repositories\Interfaces\PostCatalogueLanguageRepositoryInterface as PostCatalogueLanguageRepository;


/**
 * Class UserService
 * @package App\Services
 */
class PostCatalogueService extends BaseService implements PostCatalogueServiceInterface
{
    protected $postCatalogueRepository;
    protected $language;    
    protected $routerRepository;
    protected $postCatalogueLanguageRepository;
    protected $controllerName = 'PostCatalogueController';

    public function __construct(PostCatalogueRepository $postCatalogueRepository, RouterRepository $routerRepository, PostCatalogueLanguageRepository $postCatalogueLanguageRepository){
        $this->postCatalogueRepository=$postCatalogueRepository;
        $this->language=$this->currentLanguage();
        $this->nestedset=new Nestedsetbie([
            'table'=>'post_catalogues',
            'foreignkey'=>'post_catalogue_id',
            'language_id'=>$this->currentLanguage(),
        ]);
        $this->routerRepository=$routerRepository;
        $this->postCatalogueLanguageRepository=$postCatalogueLanguageRepository;
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
        $postCatalogues=$this->postCatalogueRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perpage,
            ['path'=> 'post/catalogue/index'],
            [
                'post_catalogues.lft', 'ASC'
            ],
            [
                ['post_catalogue_language as tb2','tb2.post_catalogue_id','=','post_catalogues.id']
            ]
  
        );
        //dd($postCatalogues);
        return $postCatalogues;
    }
    public function createPostCatalogue($request, $languageId){
        DB::beginTransaction();
        try{
            $postCatalogue = $this->createCatalogue($request);
            if($postCatalogue->id>0){
                $this->updateLanguageForCatalogue($request, $postCatalogue, $languageId);
                $this->createRouter($request, $postCatalogue, $this->controllerName);
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

    public function updatePostCatalogue($id, $request, $languageId){
        DB::beginTransaction();
        try{
            $postCatalogue=$this->postCatalogueRepository->findById($id);
            $flag = $this->updateCatalogue($request, $id);
            if($flag==TRUE){
                $this->updateLanguageForCatalogue($request, $postCatalogue, $languageId);
                $this->updateRouter($request, $postCatalogue, $this->controllerName);
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
   
    public function deletePostCatalogue($id, $languageId){
        DB::beginTransaction();
        try{
            //echo '123'; die();
            //Đầu tiền xóa đi bản dịch đó khỏi post_catalogue_language
            $where=[
                ['post_catalogue_id', '=', $id],
                ['language_id', '=', $languageId]
            ];
            $this->postCatalogueLanguageRepository->deleteByWhere($where);

            //Sau khi xóa xong thì nó tiếp tục kiểm tra xem thử là còn cái post_id đó trong post_catalogue_language không
            $condition=[
                ['post_catalogue_id', '=', $id]
            ];
            $flag = $this->postCatalogueLanguageRepository->findByCondition($condition);

            //Nếu không tìm thấy nữa thì ta mới tiến hành xóa đi post và router
            if(!$flag){
                $post=$this->postCatalogueRepository->forceDelete($id);

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
            $postCatalogue=$this->postCatalogueRepository->update($post['modelId'], $payload);
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
            $postCatalogues=$this->postCatalogueRepository->updateByWhereIn('id', $post['id'], $payload);
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
            $postCatalogues=$this->postCatalogueRepository->deleteByWhereIn('id',$post['id']);
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
            'post_catalogues.id',
            'post_catalogues.publish',
            'post_catalogues.image',
            'post_catalogues.level',
            'post_catalogues.order',
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
    public function currentLanguage(){
        return 1;
    }
    private function createCatalogue($request){
        $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
        //dd($payload);
        //vì chúng ta có khóa ngoại khi thêm bảng này mà khóa ngoại này là user_id thì đó là tài khoản đã đăng nhập thì
        $payload['user_id']=Auth::id();
        $payload['album']=$this->formatAlbum($request);
        //dd($payload);
        $postCatalogue=$this->postCatalogueRepository->create($payload);
        //dd($language);
        //echo -1; die();
        //echo $postCatalogue->id; die();
        return $postCatalogue;
    }
    private function updateCatalogue($request, $id){
        $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
        $payload['album']=$this->formatAlbum($request);
        //dd($payload);
        $flag=$this->postCatalogueRepository->update($id,$payload);
        return $flag;
    }
    private function updateLanguageForCatalogue($request, $postCatalogue, $languageId){
        $payloadLanguage=$this->formatLanguagePayload($request, $postCatalogue, $languageId);
        $postCatalogue->languages()->detach([$languageId, $postCatalogue->id]);
        $language = $this->postCatalogueRepository->createPivot($postCatalogue,$payloadLanguage,'languages');
        //dd($language); die();
        return $language;
    }
    private function formatLanguagePayload($request, $postCatalogue, $languageId){
        $payloadLanguage = $request->only($this->payloadLanguage());
        //dd($payloadLanguage);
        //dd($this->currentLanguage());
        $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
        $payloadLanguage['language_id']=$languageId;
        $payloadLanguage['post_catalogue_id']=$postCatalogue->id;
        //dd($payloadLanguage);
        return $payloadLanguage;
    }
    
}

