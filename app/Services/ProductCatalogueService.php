<?php

namespace App\Services;

use App\Services\Interfaces\ProductCatalogueServiceInterface;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
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
use App\Classes\Nestedsetbie;
use Spatie\LaravelIgnition\Exceptions\CannotExecuteSolutionForNonLocalIp;
use Illuminate\Support\Str;

use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use App\Repositories\Interfaces\ProductCatalogueLanguageRepositoryInterface as ProductCatalogueLanguageRepository;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Repositories\Interfaces\ProductLanguageRepositoryInterface as ProductLanguageRepository;


/**
 * Class UserService
 * @package App\Services
 */
class ProductCatalogueService extends BaseService implements ProductCatalogueServiceInterface
{
    protected $productCatalogueRepository;
    protected $language;    
    protected $routerRepository;
    protected $productCatalogueLanguageRepository;
    protected $productRepository;
    protected $productLanguageRepository;
    protected $controllerName = 'ProductCatalogueController';

    public function __construct(ProductCatalogueRepository $productCatalogueRepository, RouterRepository $routerRepository, ProductCatalogueLanguageRepository $productCatalogueLanguageRepository, ProductRepository $productRepository, ProductLanguageRepository $productLanguageRepository){
        $this->productCatalogueRepository=$productCatalogueRepository;
        $this->language=$this->currentLanguage();
        $this->nestedset=new Nestedsetbie([
            'table'=>'product_catalogues',
            'foreignkey'=>'product_catalogue_id',
            'language_id'=>$this->currentLanguage(),
        ]);
        $this->routerRepository=$routerRepository;
        $this->productCatalogueLanguageRepository=$productCatalogueLanguageRepository;
        $this->productRepository=$productRepository;
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
        $productCatalogues=$this->productCatalogueRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perpage,
            ['path'=> 'product/catalogue/index'],
            [
                'product_catalogues.lft', 'ASC'
            ],
            [
                ['product_catalogue_language as tb2','tb2.product_catalogue_id','=','product_catalogues.id']
            ]
  
        );
        //dd($productCatalogues);
        return $productCatalogues;
    }
    public function createProductCatalogue($request, $languageId){
        DB::beginTransaction();
        try{
            $productCatalogue = $this->createCatalogue($request);
            if($productCatalogue->id>0){
                $this->updateLanguageForCatalogue($request, $productCatalogue, $languageId);
                $this->createRouter($request, $productCatalogue, $this->controllerName, $languageId);
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

    public function updateProductCatalogue($id, $request, $languageId){
        DB::beginTransaction();
        try{
            $productCatalogue=$this->productCatalogueRepository->findById($id);
            $flag = $this->updateCatalogue($request, $id);
            if($flag==TRUE){
                $this->updateLanguageForCatalogue($request, $productCatalogue, $languageId);
                $this->updateRouter($request, $productCatalogue, $this->controllerName, $languageId);
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
   
    public function deleteProductCatalogue($id, $languageId){
        DB::beginTransaction();
        try{
            //echo '123'; die();
            //Đầu tiền xóa đi bản dịch đó khỏi product_catalogue_language
            $where=[
                ['product_catalogue_id', '=', $id],
                ['language_id', '=', $languageId]
            ];
            $this->productCatalogueLanguageRepository->deleteByWhere($where);

            //Tiếp theo xóa đi canonical của bản dịch đó khỏi routers
            $findRouter=[
                ['module_id', '=', $id],
                ['language_id', '=', $languageId],
                ['controller', '=', 'App\Http\Controllers\Frontend\ProductCatalogueController'],
            ];
            $this->routerRepository->deleteByWhere($findRouter);

            //Sau khi xóa xong thì nó tiếp tục kiểm tra xem thử là còn cái product_catalogue_id đó trong product_catalogue_language không
            $condition=[
                ['product_catalogue_id', '=', $id]
            ];
            $flag = $this->productCatalogueLanguageRepository->findByCondition($condition);

            //Nếu không tìm thấy nữa thì ta mới tiến hành xóa đi ProductCatalogue
            if(!$flag){
                $productCatalogue=$this->productCatalogueRepository->forceDelete($id);
            }

            //--------------------------Xóa cho module chi tiết--------------------------
            $products = $this->productRepository->findByConditions([
                ['product_catalogue_id', '=', $id],
            ]);

            // dd($products);
            foreach ($products as $product) {
                $whereDetail=[
                    ['product_id', '=', $product->id],
                    ['language_id', '=', $languageId]
                ];
                //Xóa đi dữ liệu tương ứng của bảng products, product_language theo product_id và language_id đang chọn
                $this->productLanguageRepository->deleteByWhere($whereDetail);

                //Tiếp theo xóa đi canonical của bản dịch đó khỏi routers
                $findRouterDetail=[
                    ['module_id', '=', $product->id],
                    ['language_id', '=', $languageId],
                    ['controller', '=', 'App\Http\Controllers\Frontend\ProductController'],
                ];
                $this->routerRepository->deleteByWhere($findRouterDetail);

                //Sau khi xóa xong thì nó tiếp tục kiểm tra xem thử là còn cái product_id đó trong product_language không
                $conditionDetail=[
                    ['product_id', '=', $product->id]
                ];
                $flag = $this->productLanguageRepository->findByCondition($conditionDetail);

                //Nếu không tìm thấy nữa thì ta mới tiến hành xóa đi product
                if(!$flag){
                    $this->productRepository->forceDelete($product->id);
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
            $productCatalogue=$this->productCatalogueRepository->update($post['modelId'], $payload);
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
            $productCatalogues=$this->productCatalogueRepository->updateByWhereIn('id', $post['id'], $payload);
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
            $productCatalogues=$this->productCatalogueRepository->deleteByWhereIn('id',$post['id']);
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
            'product_catalogues.id',
            'product_catalogues.publish',
            'product_catalogues.image',
            'product_catalogues.level',
            'product_catalogues.order',
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
        $productCatalogue=$this->productCatalogueRepository->create($payload);
        //dd($language);
        //echo -1; die();
        //echo $productCatalogue->id; die();
        return $productCatalogue;
    }
    private function updateCatalogue($request, $id){
        $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
        $payload['album']=$this->formatAlbum($request);
        //dd($payload);
        $flag=$this->productCatalogueRepository->update($id,$payload);
        return $flag;
    }
    private function updateLanguageForCatalogue($request, $productCatalogue, $languageId){
        $payloadLanguage=$this->formatLanguagePayload($request, $productCatalogue, $languageId);
        $productCatalogue->languages()->detach($languageId, $productCatalogue->id);
        $language = $this->productCatalogueRepository->createPivot($productCatalogue,$payloadLanguage,'languages');
        //dd($language); die();
        return $language;
    }
    private function formatLanguagePayload($request, $productCatalogue, $languageId){
        $payloadLanguage = $request->only($this->payloadLanguage());
        //dd($payloadLanguage);
        //dd($this->currentLanguage());
        $payloadLanguage['canonical']=Str::slug($payloadLanguage['canonical']);
        $payloadLanguage['language_id']=$languageId;
        $payloadLanguage['product_catalogue_id']=$productCatalogue->id;
        //dd($payloadLanguage);
        return $payloadLanguage;
    }
    
}

