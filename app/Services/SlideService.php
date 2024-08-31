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
            $payload = $request->only('name', 'keyword', 'setting', 'short_code');
            // dd($payload);
            $payload['setting'] = $this->formatJson($request, 'setting');
            // dd($payload);
           
            $payload['user_id']=Auth::id();
            // dd($payload);
    
            $slide = $this->slideRepository->findById($id);
            // dd($slide);
            $slideItem = $slide->album;
            // dd($slideItem);
            unset($slideItem[$languageId]);
            // dd($slideItem);
            $payload['album'] = json_encode($this->handleSlideItem($request->input('slide'), $languageId)+$slideItem);
            // dd($payload);

            $slide = $this->slideRepository->update($id, $payload);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }
   
    // V83
    public function deleteSlide($id, $languageId){
        DB::beginTransaction();
        try{
            // echo $id; die();
            $slide = $this->slideRepository->findById($id);
            // dd($slide);
            $slideItem = $slide->album;
            // dd($slideItem);
            unset($slideItem[$languageId]);
            // dd($slideItem);
            
            $payload['album'] = json_encode($slideItem);

            $slide = $this->slideRepository->update($id, $payload);
            // echo 1; die();

            if(empty($slideItem)){
                $slide = $this->slideRepository->forceDelete($id);
            }

            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
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
    
    // V82 convert mảng 2 chiều từng cột thành mảng 3 chiều gộp cột (xử lý thêm mới, cập nhật)
    private function handleSlideItem($slides, $languageId){
        // dd($slides);
        $temp = [];
        foreach($slides['image'] as $key => $val){
            $temp[$languageId][$slides['id'][$key]][] = [
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

    // // V78 convert mảng 2 chiều gộp cột thành mảng 2 chiều từng cột (giao diện cập nhật)
    // public function convertSlideArray(array $slide = []):array{
    //     // dd($slide);
    //     $temp = [];
    //     $fields = ['image', 'description', 'window', 'canonical', 'name', 'alt'];
    //     foreach($slide as $key => $val){
    //         foreach($fields as $field){
    //             $temp[$field][]=$val[$field];
    //         }
    //     }
    //     // dd($temp);
    //     return $temp;
    // }

    // V82 convert mảng 2 chiều gộp cột thành mảng 2 chiều truyền thống bỏ key thừa 0 đi (giao diện cập nhật)
    public function convertSlideArray(array $slide = []):array{
        // dd($slide);
        $temp = [];
        $fields = ['image', 'description', 'window', 'canonical', 'name', 'alt'];
        foreach($slide as $key => $val){
            foreach($fields as $field){
                $temp[$field]=$val[$field];
            }
        }
        // dd($temp);
        return $temp;
    }

    // 79 ajax
    public function updateDrag($slideId, $items, $languageSessionId){
        // dd($payload);

        $slide = $this->slideRepository->findById($slideId);
        // dd($slide);
        $slideItem = $slide->album;
        // dd($slideItem);
        unset($slideItem[$languageSessionId]);
        // dd($slideItem);
        $payload['album'] = json_encode($this->joinParentLanguageIdInArray($items, $languageSessionId)+$slideItem);
        // dd($payload);

        $slide = $this->slideRepository->update($slideId, $payload);
    }

    // V79 convert thêm mảng cha languageId vào cho mảng slide[][]
    private function joinParentLanguageIdInArray($items, $languageSessionId){
        $temp = [];
        // dd($items);
        // Duyệt qua mỗi phần tử của mảng $items
        foreach($items as $item){
            // dd($item);
            // Kiểm tra xem $item có phải là một mảng không
            if (is_array($item)) {
                $temp[$languageSessionId][$item['id']][] = [
                    'image' => $item['image'], // Truy cập phần tử của mảng
                    'description' => $item['description'],
                    'canonical' => $item['canonical'],
                    'name' => $item['name'],
                    'alt' => $item['alt'],
                    'window' => $item['window'],
                ];
            }
        }
        // dd($temp);
        // Trả về mảng đã được tạo
        return $temp;
    }

    // V81
    public function saveTranslateSlide($request, $languageTranslateId, $id){
        DB::beginTransaction();
        try{
            $payload = $request->only('translate');
            // dd($payload['translate']);

            $slide = $this->slideRepository->findById($id);
            // dd($slide);
            $slideItem = $slide->album;
            // dd($slideItem);
            // dd($languageTranslateId);
            unset($slideItem[$languageTranslateId]);
            // dd($languageTranslateId);
            // dd($slideItem);

            $slides = $this->handleSlideItem($payload['translate'], $languageTranslateId)+$slideItem;
            // dd($slides);
            $payload['album'] = json_encode($slides);
            // dd($payload);
    
            $slide = $this->slideRepository->update($id, $payload);
            // echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return false;
        }
    }

    // V82
    public function findSlideItemTranslate($slide, $languageSessionId, $languageTranslateId)
    {
        $output = [];
        $slideItem = $slide->album;

        if (isset($slideItem[$languageSessionId])) {
            // Lấy mảng cha thứ nhất để làm thứ tự chuẩn
            $sessionItems = $slideItem[$languageSessionId];
            $translateItems = $slideItem[$languageTranslateId] ?? null;

            if (!is_null($translateItems)) {
                // Sắp xếp các phần tử trong mảng $translateItems theo thứ tự của $sessionItems
                $sortedTranslateItems = [];
                $remainingTranslateItems = $translateItems; // Giữ lại các phần tử không tìm thấy trong sessionItems

                foreach ($sessionItems as $parentId => $items) {
                    if (isset($translateItems[$parentId])) {
                        $sortedTranslateItems[$parentId] = $translateItems[$parentId];
                        unset($remainingTranslateItems[$parentId]); // Loại bỏ phần tử đã được sử dụng
                    }
                }
                // dd($sortedTranslateItems);

                // Kết hợp $sessionItems với $sortedTranslateItems và loại bỏ mảng cha số 0
                foreach ($sessionItems as $parentId => &$items) {
                    $combinedItems = $items[0]; // Lấy mảng con đầu tiên
                    if (isset($sortedTranslateItems[$parentId])) {
                        foreach ($sortedTranslateItems[$parentId][0] as $key => $value) {
                            $combinedItems['translate_' . $key] = $value;
                        }
                    }
                    // Lưu kết quả vào output, không có mảng cha số 0
                    $output[$parentId] = $combinedItems;
                }
                // dd($combinedItems);
                // dd($output);

                // Xếp các phần tử còn lại (không tìm thấy theo parentId) vào cuối cùng
                // dd($remainingTranslateItems);
                foreach ($remainingTranslateItems as $parentId => $items) {
                    $combinedItems = [];
                    foreach ($items[0] as $key => $value) {
                        $combinedItems['translate_' . $key] = $value;
                    }
                    $output[$parentId] = $combinedItems;
                }
                // dd($output);
            } else {
                // Chuyển các phần tử từ $sessionItems vào output mà không có mảng cha số 0
                foreach ($sessionItems as $parentId => $items) {
                    $output[$parentId] = $items[0];
                }
            }
            // dd($output);
        }

        return $output;
    }

}

