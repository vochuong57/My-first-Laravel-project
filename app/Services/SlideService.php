<?php

namespace App\Services;

use App\Services\Interfaces\SlideServiceInterface;
use App\Repositories\Interfaces\SlideRepositoryInterface as SlideRepository;
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
use App\Services\BaseService;//tiến hành chèn dữ liệu vào bảng ngoài cụ thể là slide_catalogue_language
use Request;
use Spatie\LaravelIgnition\Exceptions\CannotExecuteSolutionForNonLocalIp;
use Illuminate\Support\Str;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;

/**
 * Class UserService
 * @package App\Services
 */
class SlideService extends BaseService implements SlideServiceInterface
{
    protected $slideRepository;
    protected $routerRepository;
    protected $controllerName = 'SlideController';

    public function __construct(SlideRepository $slideRepository, RouterRepository $routerRepository){
        $this->slideRepository=$slideRepository;
        $this->routerRepository=$routerRepository;
    }

    public function paginate($request, $languageId){//$request để tiến hành chức năng tìm kiếm
        // //dd($request);
        // //echo 123; die();
        // $condition['keyword']=addslashes($request->input('keyword'));
        // // dd($condition);
        // $perpage=$request->integer('perpage', 20);
        // $slides=$this->slideRepository->pagination(
        //     $this->paginateSelect(),
        //     $condition,
        //     $perpage,
        //     ['path'=> 'slide/index', 'groupBy' => $this->paginateSelect()],
        //     ['slides.id', 'DESC'],
        //     [],
        // );
        // // dd($slides);
        
        return [];
    }
    public function createSlide($request, $languageId){
        DB::beginTransaction();
        try{
            $slide = $this->createTableSlide($request);
            
            if($slide->id>0){
                $this->updateLanguageForSlide($request, $slide, $languageId);
                $this->createRouter($request, $slide, $this->controllerName, $languageId);
                
                //xử lí add dữ liệu vào slide_catalogue_slide
                $catalogue=$this->mergeCatalogue($request);
                //dd($catalogue);
                $slide->slide_catalogues()->sync($catalogue);//slide_catalogues() là function của Model/Slide
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }

    public function updateSlide($id, $request, $languageId){
        DB::beginTransaction();
        try{
            $slide=$this->slideRepository->findById($id);
            $flag=$this->updateTableSlide($request, $id);
            //dd($flag);
            if($flag==TRUE){
                $this->updateLanguageForSlide($request, $slide, $languageId);
                $this->updateRouter($request, $slide, $this->controllerName, $languageId);

                $catalogue=$this->mergeCatalogue($request);
                //dd($catalogue);
                $slide->slide_catalogues()->sync($catalogue);
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
   
    public function deleteSlide($id, $languageId){
        DB::beginTransaction();
        try{
            //echo '123'; die();
            //Đầu tiền xóa đi bản dịch đó khỏi slide_language
            $where=[
                ['slide_id', '=', $id],
                ['language_id', '=', $languageId]
            ];
            $this->slideLanguageRepository->deleteByWhere($where);

            //Tiếp theo xóa đi canonical của bản dịch đó khỏi routers
            $findRouter=[
                ['module_id', '=', $id],
                ['language_id', '=', $languageId],
                ['controller', '=', 'App\Http\Controllers\Frontend\SlideController'],
            ];
            $this->routerRepository->deleteByWhere($findRouter);

            //Sau khi xóa xong thì nó tiếp tục kiểm tra xem thử là còn cái slide_id đó trong slide_language không
            $condition=[
                ['slide_id', '=', $id]
            ];
            $flag = $this->slideLanguageRepository->findByCondition($condition);

            //Nếu không tìm thấy nữa thì ta mới tiến hành xóa đi Slide
            if(!$flag){
                $slide=$this->slideRepository->forceDelete($id);
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
    public function updateStatus($slide=[]){
        //echo 123; die();
        DB::beginTransaction();
        try{
            $payload[$slide['field']]=(($slide['value']==1)?2:1);
            
            //dd($payload);
            $slide=$this->slideRepository->update($slide['modelId'], $payload);
            //echo 1; die();
            //$this->changeUserStatus($slide, $payload[$slide['field']]);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
        
    }
    public function updateStatusAll($slide=[]){
        //echo 123; die();
        DB::beginTransaction();
        try{
            //dd($slide);
            $payload[$slide['field']]=$slide['value'];
            
            //dd($payload);
            $slides=$this->slideRepository->updateByWhereIn('id', $slide['id'], $payload);
            //echo 1; die();
            //$this->changeUserStatus($slide,$slide['value']);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
    public function deleteAll($slide=[]){
        DB::beginTransaction();
        try{
            $slideLanguage=$this->slideLanguageRepository->deleteByWhereIn('slide_id',$slide['id'],$slide['languageId']);
            //echo 1; die();

            $languageId = $slide['languageId'];

            foreach($slide['id'] as $id){

                // Tiếp tục xóa tiếp canonical ở bảng routers của từng id được chọn 
                $findRouter=[
                    ['module_id', '=', $id],
                    ['language_id', '=', $languageId],
                    ['controller', '=', 'App\Http\Controllers\Frontend\SlideController'],
                ];
                $this->routerRepository->deleteByWhere($findRouter);

                // Sau khi xóa xong thì nó tiếp tục kiểm tra xem thử là còn cái slide_id đó trong slide_language không
                $condition=[
                    ['slide_id', '=', $id]
                ];
                $flag = $this->slideLanguageRepository->findByCondition($condition);

                // Nếu không tìm thấy nữa thì ta mới tiến hành xóa đi slide
                if(!$flag){
                    $slide=$this->slideRepository->forceDelete($id);
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
            'id',
            'name',
            'keyword',
            'description',
            'publish',
            'album'
        ];
    }
    private function payload(){
        return [
            'follow',
            'publish',
            'image',
            'album',
            'slide_catalogue_id'
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
            return array_unique(array_merge($catalogueInput, [$request->slide_catalogue_id]));
        } else {
            // Nếu không tồn tại hoặc rỗng, trả về chỉ mảng chứa $request->slide_catalogue_id
            return [$request->slide_catalogue_id];
        }
    }
    
    //----TỐI ƯU SOURCE CODE
    private function createTableSlide($request){
        $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
        //dd($payload);
        //vì chúng ta có khóa ngoại khi thêm bảng này mà khóa ngoại này là user_id thì đó là tài khoản đã đăng nhập thì
        $payload['user_id']=Auth::id();
        $payload['album']=$this->formatAlbum($request);
        if($payload['publish'] == null || $payload['publish'] == 0){
            $payload['publish'] = 1;
        }
        //dd($payload);
        $slide=$this->slideRepository->create($payload);
        //dd($language);
        //echo -1; die();
        //echo $slide->id; die();
        return $slide;
    }
    private function updateTableSlide($request, $id){
        $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
        $payload['album']=$this->formatAlbum($request);
        //dd($payload);
        $flag=$this->slideRepository->update($id,$payload);
        return $flag;
    }
    //Cho bảng slide_language
    private function updateLanguageForSlide($request, $slide, $languageId){
        $payloadLanguage=$this->formatLanguagePayload($request, $slide, $languageId);
        $slide->languages()->detach($languageId, $slide->id);
        $language = $this->slideRepository->createPivot($slide,$payloadLanguage,'languages');
        //dd($language); die();
        return $language;
    }
    private function formatLanguagePayload($request, $slide, $languageId){
        $payloadLanguage = $request->only($this->payloadLanguage());
        //dd($payloadLanguage);
        //dd($this->currentLanguage());
        $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
        $payloadLanguage['language_id']=$languageId;
        $payloadLanguage['slide_id']=$slide->id;
        //dd($payloadLanguage);
        return $payloadLanguage;
    }
}

