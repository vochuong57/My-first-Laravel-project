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
    protected $language;

    public function __construct(PostRepository $postRepository){
        $this->postRepository=$postRepository;
        $this->language=$this->currentLanguage();
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
        $condition['where']=[
            ['tb2.language_id', '=', $this->language],
        ];
        //dd($condition);
        $perpage=$request->integer('perpage', 20);
        $posts=$this->postRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perpage,
            ['path'=> 'post/index', 'groupBy' => $this->paginateSelect()],
            ['posts.id', 'DESC'],
            [
                ['post_language as tb2','tb2.post_id','=','posts.id'],//dùng cho hiển thị nội dung table
                ['post_catalogue_post as tb3','posts.id', '=', 'tb3.post_id']//dùng cho whereRaw lọc tìm kiếm bài viết theo nhóm bài viêt
            ],
            ['post_catalogues'],//là function post_catalogues của Model/Post
            $this->whereRaw($request),
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
                $post->post_catalogues()->sync($catalogue);//là function post_catalogues của Model/Post
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
            //dd($flag);

            if($flag==TRUE){
                $payloadLanguage = $request->only($this->payloadLanguage());
                //dd($payloadLanguage);
                //dd($this->currentLanguage());
                $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
                $payloadLanguage['language_id']=$this->currentLanguage();
                $payloadLanguage['post_id']=$post->id;
                //dd($payloadLanguage);
                //: Loại bỏ mối quan hệ giữa mục hiện tại và ngôn ngữ của nó.
                $post->languages()->detach([$payloadLanguage['language_id'],$id]);
                //dd($post);
                // Tạo lại mối quan hệ giữa mục và ngôn ngữ dựa trên dữ liệu trong $payloadLanguage
                $reponse=$this->postRepository->createPivot($post,$payloadLanguage,'languages');
                //dd($reponse);

                $catalogue=$this->catalogue($request);
                //dd($catalogue);
                $post->post_catalogues()->sync($catalogue);
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

    private function paginateSelect(){
        return[
            'posts.id',
            'posts.publish',
            'posts.image',
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
            'canonical',
        ];
    }

    public function currentLanguage(){
        return 1;
    }

    //merge dữ liệu từ hai mảng khác nhau vào chung một bảng
    private function catalogue($request){
        $catalogueInput = $request->input('catalogue');
        
        // Kiểm tra nếu $catalogueInput tồn tại và không rỗng
        if ($request->filled('catalogue') && is_array($catalogueInput)) {
            return array_unique(array_merge($catalogueInput, [$request->post_catalogue_id]));
        } else {
            // Nếu không tồn tại hoặc rỗng, trả về chỉ mảng chứa $request->post_catalogue_id
            return [$request->post_catalogue_id];
        }
    }
    

    //whereRaw tìm kiếm bài viết theo nhóm bài viết
    private function whereRaw($request){
        $rawCondition = [];
        if($request->integer('post_catalogue_id')>0){
            $rawCondition['whereRaw']=[
                [
                    'tb3.post_catalogue_id IN (
                        SELECT id
                        FROM post_catalogues
                        WHERE lft >= (SELECT lft FROM post_catalogues as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM post_catalogues as pc WHERE pc.id = ?)
                    )',
                    [$request->integer('post_catalogue_id'), $request->integer('post_catalogue_id')]
                ]
            ];
        }
        return $rawCondition;
    }
}

