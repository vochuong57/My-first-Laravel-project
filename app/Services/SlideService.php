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

    public function paginate($request){//$request để tiến hành chức năng tìm kiếm
        // dd($request);
        // echo 123; die();
        $condition['keyword']=addslashes($request->input('keyword'));
        $condition['publish']=$request->input('publish');
        if ($condition['publish'] == '0') {
            $condition['publish'] = null;
        }
        // dd($condition);
        $perpage=$request->integer('perpage', 20);
        //  echo 123; die();
        $slides=$this->slideRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perpage,
            ['path'=> 'slide/index', 'groupBy' => $this->paginateSelect()],
            ['slides.id', 'DESC']
        );
        // dd($slides);
        
        return $slides;
    }
    public function createSlide($request, $languageId){
        DB::beginTransaction();
        try{
            $payload = $request->only('name', 'keyword', 'setting', 'short_code');
            // dd($payload);
            $payload['setting'] = $this->formatJson($request, 'setting');
            // dd($payload);
            $payload['album'] = json_encode($this->handleSlideItem($request->input('slide'), $languageId));
            // dd($payload);
            $payload['user_id']=Auth::id();

            $slide = $this->slideRepository->create($payload);
            // echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
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

    private function paginateSelect(){
        return[
            'id',
            'name',
            'keyword',
            'publish',
            'album'
        ];
    }
    
    private function handleSlideItem($slides, $languageId){
        // dd($slides);
        $temp = [];
        foreach($slides['image'] as $key => $val){
            $temp[$languageId][] = [
                'image' => $val,
                'description' => $slides['description'][$key],
                'canonical' => $slides['canonical'][$key],
                'name' => $slides['name'][$key],
                'alt' => $slides['alt'][$key],
                'window' => (isset($slides['window'][$key])) ? $slides['window'][$key] : '',
            ];
        }
        // dd($temp);
        return $temp;
    }
}
