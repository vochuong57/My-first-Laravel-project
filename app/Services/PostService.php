<?php

namespace App\Services;

use App\Services\Interfaces\PostServiceInterface;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;
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
use Request;
use Spatie\LaravelIgnition\Exceptions\CannotExecuteSolutionForNonLocalIp;
use Illuminate\Support\Str;

/**
 * Class UserService
 * @package App\Services
 */
class PostService extends BaseService implements PostServiceInterface
{
    protected $postRepository;
    //protected $userRepository;

    public function __construct(PostRepository $postRepository){
        $this->postRepository=$postRepository;
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
        $posts=$this->postRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perpage,
            ['path'=> 'post/index'],
            ['posts.id', 'DESC'],
            [
                ['post_language as tb2','tb2.post_id','=','posts.id']
            ]
  
        );
        //dd($posts);
        return $posts;
    }
    public function createPost($request){
        DB::beginTransaction();
        try{
            $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            //dd($payload);
            //vì chúng ta có khóa ngoại khi thêm bảng này mà khóa ngoại này là user_id thì đó là tài khoản đã đăng nhập thì
            $payload['user_id']=Auth::id();
            if(isset($payload['album'])){
                $payload['album']=json_encode($payload['album']);
            }
            //dd($payload);
            $post=$this->postRepository->create($payload);
            //dd($language);
            //echo -1; die();
            //echo $post->id; die();
            if($post->id>0){
                $payloadLanguage = $request->only($this->payloadLanguage());
                //dd($payloadLanguage);
                //dd($this->currentLanguage());
                $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
                $payloadLanguage['language_id']=$this->currentLanguage();
                $payloadLanguage['post_id']=$post->id;
                //dd($payloadLanguage);

                $language = $this->postRepository->createPivot($post,$payloadLanguage,'languages');
                //dd($language); die();

                $catalogue=$this->catalogue($request);
                //dd($catalogue);
                $post->post_catalogues()->sync($catalogue);
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }

    public function updatePost($id, $request){
        DB::beginTransaction();
        try{
            $post=$this->postRepository->findById($id);
            //dd($post);
            $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            if(isset($payload['album'])){
                $payload['album']=json_encode($payload['album']);
            }
            //dd($payload);
            $flag=$this->postRepository->update($id,$payload);
            if($flag==TRUE){
                $payloadLanguage = $request->only($this->payloadLanguage());
                //dd($payloadLanguage);
                //dd($this->currentLanguage());
                $payloadLanguage['language_id']=$this->currentLanguage();
                $payloadLanguage['post_catalogue_id']=$post->id;
                //dd($payloadLanguage);
                //: Loại bỏ mối quan hệ giữa mục hiện tại và ngôn ngữ của nó.
                $post->languages()->detach([$payloadLanguage['language_id'],$id]);
                // Tạo lại mối quan hệ giữa mục và ngôn ngữ dựa trên dữ liệu trong $payloadLanguage
                $reponse=$this->postRepository->createPivot($post,$payloadLanguage,'languages');
                
            }
            

            //$post=$this->postRepository->update($id, $payload);
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
   
    public function deletePost($id){
        DB::beginTransaction();
        try{
            $post=$this->postRepository->delete($id);
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
            $post=$this->postRepository->update($post['modelId'], $payload);
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
            $posts=$this->postRepository->updateByWhereIn('id', $post['id'], $payload);
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
            $posts=$this->postRepository->deleteByWhereIn('id',$post['id']);
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
            'posts.id',
            'posts.publish',
            'posts.image',
            'posts.level',
            'posts.order',
            'tb2.name',
            'tb2.canonical'
        ];
    }
    private function payload(){
        return [
            'follow',
            'publish',
            'image',
            'album',
            'post_catalogue_id'
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

    private function catalogue($request){
        return array_unique(array_merge($request->input('catalogue'),[$request->post_catalogue_id]));
    }
}

