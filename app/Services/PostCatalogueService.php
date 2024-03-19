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

/**
 * Class UserService
 * @package App\Services
 */
class PostCatalogueService extends BaseService implements PostCatalogueServiceInterface
{
    protected $postCatalogueRepository;
    //protected $userRepository;
    protected $nestedset;

    public function __construct(PostCatalogueRepository $postCatalogueRepository){
        $this->postCatalogueRepository=$postCatalogueRepository;
        //$this->userRepository=$userRepository;
        $this->nestedset=new Nestedsetbie([
            'table'=>'post_catalogues',
            'foreignkey'=>'post_catalogue_id',
            'language_id'=>$this->currentLanguage(),
        ]);
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
        $postCatalogues=$this->postCatalogueRepository->pagination(
            $this->paginateSelect(),
            $condition,
            [
                ['post_catalogue_language as tb2','tb2.post_catalogue_id','=','post_catalogues.id']
            ], 
            ['path'=> 'post/catalogue/index'], 
            $perpage,
            [],
            [
                'post_catalogues.lft', 'ASC'
            ]
        );
        //dd($postCatalogues);
        return $postCatalogues;
    }
    public function createPostCatalogue($request){
        DB::beginTransaction();
        try{
            $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            //dd($payload);
            //vì chúng ta có khóa ngoại khi thêm bảng này mà khóa ngoại này là user_id thì đó là tài khoản đã đăng nhập thì
            $payload['user_id']=Auth::id();
            //dd($payload);
            $postCatalogue=$this->postCatalogueRepository->create($payload);
            //dd($language);
            //echo -1; die();
            //echo $postCatalogue->id; die();
            if($postCatalogue->id>0){
                $payloadLanguage = $request->only($this->payloadLanguage());
                //dd($payloadLanguage);
                //dd($this->currentLanguage());
                $payloadLanguage['language_id']=$this->currentLanguage();
                $payloadLanguage['post_catalogue_id']=$postCatalogue->id;
                //dd($payloadLanguage);

                $language = $this->postCatalogueRepository->createLanguagePivot($postCatalogue,$payloadLanguage);
                //dd($language); die();
            }
            //sử dụng nested set
            //dd($this->nestedset);
            $this->nestedset->Get();//gọi Get để lấy dữ liệu
            $this->nestedset->Recursive(0, $this->nestedset->Set());//gọi Recursive để tính toán lại các giá trị của từng node
            $this->nestedset->Action();//gọi đến Action để cập nhật lại các giá trị lft rgt
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }

    public function updatePostCatalogue($id, $request){
        DB::beginTransaction();
        try{
            $postCatalogue=$this->postCatalogueRepository->findById($id);
            //dd($postCatalogue);
            $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            //dd($payload);
            $flag=$this->postCatalogueRepository->update($id,$payload);
            if($flag==TRUE){
                $payloadLanguage = $request->only($this->payloadLanguage());
                //dd($payloadLanguage);
                //dd($this->currentLanguage());
                $payloadLanguage['language_id']=$this->currentLanguage();
                $payloadLanguage['post_catalogue_id']=$postCatalogue->id;
                //dd($payloadLanguage);
                //: Loại bỏ mối quan hệ giữa mục hiện tại và ngôn ngữ của nó.
                $postCatalogue->languages()->detach([$payloadLanguage['language_id'],$id]);
                // Tạo lại mối quan hệ giữa mục và ngôn ngữ dựa trên dữ liệu trong $payloadLanguage
                $reponse=$this->postCatalogueRepository->createLanguagePivot($postCatalogue,$payloadLanguage);
                $this->nestedset->Get();//gọi Get để lấy dữ liệu
                $this->nestedset->Recursive(0, $this->nestedset->Set());//gọi Recursive để tính toán lại các giá trị của từng node
                $this->nestedset->Action();//gọi đến Action để cập nhật lại các giá trị lft rgt
            }
            

            //$postCatalogue=$this->postCatalogueRepository->update($id, $payload);
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
   
    public function deletePostCatalogue($id){
        DB::beginTransaction();
        try{
            $postCatalogue=$this->postCatalogueRepository->delete($id);
            $this->nestedset->Get();//gọi Get để lấy dữ liệu
            $this->nestedset->Recursive(0, $this->nestedset->Set());//gọi Recursive để tính toán lại các giá trị của từng node
            $this->nestedset->Action();//gọi đến Action để cập nhật lại các giá trị lft rgt
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
    // private function changeUserStatus($post, $value){
       
    //     DB::beginTransaction();
    //     try{
    //         //dd($post);
    //         $array=[];
    //         if(isset($post['modelId'])){
    //             $array[]=$post['modelId'];
    //         }else{
    //             $array=$post['id'];
    //         }//push vào trong mảng để update theo kiểu by where in
    //         //dd($post);
    //         $payload[$post['field']]=$value;
    //         $this->userRepository->updateByWhereIn('user_catalogue_id', $array, $payload);
    //         //echo 123; die();
    //         DB::commit();
    //         return true;
    //     }catch(\Exception $ex){
    //         DB::rollBack();
    //         echo $ex->getMessage();//die();
    //         return false;
    //     }
    // }
    
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
            'image'
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
}

