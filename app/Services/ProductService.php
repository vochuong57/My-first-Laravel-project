<?php

namespace App\Services;

use App\Services\Interfaces\ProductServiceInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
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
use App\Services\BaseService;//tiến hành chèn dữ liệu vào bảng ngoài cụ thể là product_catalogue_language
use Request;
use Spatie\LaravelIgnition\Exceptions\CannotExecuteSolutionForNonLocalIp;
use Illuminate\Support\Str;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use App\Repositories\Interfaces\ProductLanguageRepositoryInterface as ProductLanguageRepository;

/**
 * Class UserService
 * @package App\Services
 */
class ProductService extends BaseService implements ProductServiceInterface
{
    protected $productRepository;
    protected $routerRepository;
    protected $productLanguageRepository;
    protected $controllerName = 'ProductController';

    public function __construct(ProductRepository $productRepository, RouterRepository $routerRepository, ProductLanguageRepository $productLanguageRepository){
        $this->productRepository=$productRepository;
        $this->routerRepository=$routerRepository;
        $this->productLanguageRepository=$productLanguageRepository;
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
        $products=$this->productRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perpage,
            ['path'=> 'product/index', 'groupBy' => $this->paginateSelect()],
            ['products.id', 'DESC'],
            [
                ['product_language as tb2','tb2.product_id','=','products.id'],//dùng cho hiển thị nội dung table
                ['product_catalogue_product as tb3','products.id', '=', 'tb3.product_id']//dùng cho whereRaw lọc tìm kiếm bài viết theo nhóm bài viêt
            ],
            ['product_catalogues'],//là function product_catalogues của Model/Product
            $this->whereRaw($request),
        );
        //dd($products);
        
        return $products;
    }
    public function createProduct($request, $languageId){
        DB::beginTransaction();
        try{
            $product = $this->createTableProduct($request);
            
            if($product->id>0){
                $this->updateLanguageForProduct($request, $product, $languageId);
                $this->createRouter($request, $product, $this->controllerName, $languageId);
                
                //xử lí add dữ liệu vào product_catalogue_product
                $catalogue=$this->mergeCatalogue($request);
                //dd($catalogue);
                $product->product_catalogues()->sync($catalogue);//product_catalogues() là function của Model/Product
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }

    public function updateProduct($id, $request, $languageId){
        DB::beginTransaction();
        try{
            $product=$this->productRepository->findById($id);
            $flag=$this->updateTableProduct($request, $id);
            //dd($flag);
            if($flag==TRUE){
                $this->updateLanguageForProduct($request, $product, $languageId);
                $this->updateRouter($request, $product, $this->controllerName, $languageId);

                $catalogue=$this->mergeCatalogue($request);
                //dd($catalogue);
                $product->product_catalogues()->sync($catalogue);
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
   
    public function deleteProduct($id, $languageId){
        DB::beginTransaction();
        try{
            //echo '123'; die();
            //Đầu tiền xóa đi bản dịch đó khỏi product_language
            $where=[
                ['product_id', '=', $id],
                ['language_id', '=', $languageId]
            ];
            $this->productLanguageRepository->deleteByWhere($where);

            //Tiếp theo xóa đi canonical của bản dịch đó khỏi routers
            $findRouter=[
                ['module_id', '=', $id],
                ['language_id', '=', $languageId],
                ['controller', '=', 'App\Http\Controllers\Frontend\ProductController'],
            ];
            $this->routerRepository->deleteByWhere($findRouter);

            //Sau khi xóa xong thì nó tiếp tục kiểm tra xem thử là còn cái product_id đó trong product_language không
            $condition=[
                ['product_id', '=', $id]
            ];
            $flag = $this->productLanguageRepository->findByCondition($condition);

            //Nếu không tìm thấy nữa thì ta mới tiến hành xóa đi Product
            if(!$flag){
                $product=$this->productRepository->forceDelete($id);
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
            $product=$this->productRepository->update($post['modelId'], $payload);
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
            $products=$this->productRepository->updateByWhereIn('id', $post['id'], $payload);
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
            $productLanguage=$this->productLanguageRepository->deleteByWhereIn('product_id',$post['id'],$post['languageId']);
            //echo 1; die();

            $languageId = $post['languageId'];
            
            foreach($post['id'] as $id){

                // Tiếp tục xóa tiếp canonical ở bảng routers của từng id được chọn 
                $findRouter=[
                    ['module_id', '=', $id],
                    ['language_id', '=', $languageId],
                    ['controller', '=', 'App\Http\Controllers\Frontend\ProductController'],
                ];
                $this->routerRepository->deleteByWhere($findRouter);

                // Sau khi xóa xong thì nó tiếp tục kiểm tra xem thử là còn cái product_id đó trong product_language không
                $condition=[
                    ['product_id', '=', $id]
                ];
                $flag = $this->productLanguageRepository->findByCondition($condition);
                
                // Nếu không tìm thấy nữa thì ta mới tiến hành xóa đi products
                if(!$flag){
                    $product=$this->productRepository->forceDelete($id);
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
            'products.id',
            'products.publish',
            'products.image',
            'products.order',
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
            'product_catalogue_id'
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
            return array_unique(array_merge($catalogueInput, [$request->product_catalogue_id]));
        } else {
            // Nếu không tồn tại hoặc rỗng, trả về chỉ mảng chứa $request->product_catalogue_id
            return [$request->product_catalogue_id];
        }
    }
    

    //whereRaw tìm kiếm bài viết theo nhóm bài viết mở rộng
    private function whereRaw($request){
        $rawCondition = [];
        if($request->integer('product_catalogue_id')>0){
            $rawCondition['whereRaw']=[
                [
                    'tb3.product_catalogue_id IN (
                        SELECT id
                        FROM product_catalogues
                        WHERE lft >= (SELECT lft FROM product_catalogues as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM product_catalogues as pc WHERE pc.id = ?)
                    )',
                    [$request->integer('product_catalogue_id'), $request->integer('product_catalogue_id')]
                ]
            ];
        }
        return $rawCondition;
    }
    //----TỐI ƯU SOURCE CODE
    private function createTableProduct($request){
        $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
        //dd($payload);
        //vì chúng ta có khóa ngoại khi thêm bảng này mà khóa ngoại này là user_id thì đó là tài khoản đã đăng nhập thì
        $payload['user_id']=Auth::id();
        $payload['album']=$this->formatAlbum($request);
        if($payload['publish'] == null || $payload['publish'] == 0){
            $payload['publish'] = 1;
        }
        //dd($payload);
        $product=$this->productRepository->create($payload);
        //dd($language);
        //echo -1; die();
        //echo $product->id; die();
        return $product;
    }
    private function updateTableProduct($request, $id){
        $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
        $payload['album']=$this->formatAlbum($request);
        //dd($payload);
        $flag=$this->productRepository->update($id,$payload);
        return $flag;
    }
    //Cho bảng product_language
    private function updateLanguageForProduct($request, $product, $languageId){
        $payloadLanguage=$this->formatLanguagePayload($request, $product, $languageId);
        $product->languages()->detach($languageId, $product->id);
        $language = $this->productRepository->createPivot($product,$payloadLanguage,'languages');
        //dd($language); die();
        return $language;
    }
    private function formatLanguagePayload($request, $product, $languageId){
        $payloadLanguage = $request->only($this->payloadLanguage());
        //dd($payloadLanguage);
        //dd($this->currentLanguage());
        $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
        $payloadLanguage['language_id']=$languageId;
        $payloadLanguage['product_id']=$product->id;
        //dd($payloadLanguage);
        return $payloadLanguage;
    }
}

