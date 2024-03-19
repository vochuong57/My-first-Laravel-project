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
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use App\Repositories\Interfaces\PostLanguageRepositoryInterface as PostLanguageRepository;

/**
 * Class UserService
 * @package App\Services
 */
class PostService extends BaseService implements PostServiceInterface
{
    protected $postRepository;
    protected $language;
    protected $routerRepository;
    protected $postLanguageRepository;
    protected $controllerName = 'PostController';

    public function __construct(PostRepository $postRepository, RouterRepository $routerRepository, PostLanguageRepository $postLanguageRepository){
        $this->postRepository=$postRepository;
        $this->language=$this->currentLanguage();
        $this->routerRepository=$routerRepository;
        $this->postLanguageRepository=$postLanguageRepository;
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
            $post = $this->createTablePost($request);
            
            if($post->id>0){
                $this->updateLanguageForPost($request, $post);
                $this->createRouter($request, $post, $this->controllerName);
                
                //xử lí add dữ liệu vào post_catalogue_post
                $catalogue=$this->mergeCatalogue($request);
                //dd($catalogue);
                $post->post_catalogues()->sync($catalogue);//post_catalogues() là function của Model/Post
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
            $flag=$this->updateTablePost($request, $id);
            //dd($flag);
            if($flag==TRUE){
                $this->updateLanguageForPost($request, $post);
                $this->updateRouter($request, $post, $this->controllerName);

                $catalogue=$this->mergeCatalogue($request);
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
   
    public function deletePost($id){
        DB::beginTransaction();
        try{
            $post=$this->postRepository->delete($id);
            $conditionByRouter=[
                ['module_id', '=', $id]
            ];
            $router=$this->routerRepository->findByCondition($conditionByRouter);
            //dd($router->id);
            $this->routerRepository->forceDelete($router->id);//router chỉ hiện những cái canonical đang tồn tại sẽ không có xóa mềm
            //echo '123'; die();
            $where=[
                ['post_id', '=', $id]
            ];
            $payload=['canonical'=>null];
            $this->postLanguageRepository->updateByWhere($where,$payload);
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
    private function mergeCatalogue($request){
        $catalogueInput = $request->input('catalogue');
        
        // Kiểm tra nếu $catalogueInput tồn tại và không rỗng
        if ($request->filled('catalogue') && is_array($catalogueInput)) {
            return array_unique(array_merge($catalogueInput, [$request->post_catalogue_id]));
        } else {
            // Nếu không tồn tại hoặc rỗng, trả về chỉ mảng chứa $request->post_catalogue_id
            return [$request->post_catalogue_id];
        }
    }
    

    //whereRaw tìm kiếm bài viết theo nhóm bài viết mở rộng
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
    //----TỐI ƯU SOURCE CODE
    private function createTablePost($request){
        $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
        //dd($payload);
        //vì chúng ta có khóa ngoại khi thêm bảng này mà khóa ngoại này là user_id thì đó là tài khoản đã đăng nhập thì
        $payload['user_id']=Auth::id();
        $payload['album']=$this->formatAlbum($request);
        //dd($payload);
        $post=$this->postRepository->create($payload);
        //dd($language);
        //echo -1; die();
        //echo $post->id; die();
        return $post;
    }
    private function updateTablePost($request, $id){
        $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
        $payload['album']=$this->formatAlbum($request);
        //dd($payload);
        $flag=$this->postRepository->update($id,$payload);
        return $flag;
    }
    private function updateLanguageForPost($request, $post){
        $payloadLanguage=$this->formatLanguagePayload($request, $post);
        $post->languages()->detach([$this->language, $post->id]);
        $language = $this->postRepository->createPivot($post,$payloadLanguage,'languages');
        //dd($language); die();
        return $language;
    }
    private function formatLanguagePayload($request, $post){
        $payloadLanguage = $request->only($this->payloadLanguage());
        //dd($payloadLanguage);
        //dd($this->currentLanguage());
        $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
        $payloadLanguage['language_id']=$this->currentLanguage();
        $payloadLanguage['post_id']=$post->id;
        //dd($payloadLanguage);
        return $payloadLanguage;
    }
}

