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
use App\Repositories\Interfaces\ProductVariantLanguageRepositoryInterface as ProductVariantLanguageRepository;
use App\Repositories\Interfaces\ProductVariantAttributeRepositoryInterface as ProductVariantAttributeRepository;

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
    protected $productVariantLanguageRepository;
    protected $productVariantAttributeRepository;

    public function __construct(ProductRepository $productRepository, RouterRepository $routerRepository, ProductLanguageRepository $productLanguageRepository, ProductVariantLanguageRepository $productVariantLanguageRepository, ProductVariantAttributeRepository $productVariantAttributeRepository){
        $this->productRepository=$productRepository;
        $this->routerRepository=$routerRepository;
        $this->productLanguageRepository=$productLanguageRepository;
        $this->productVariantLanguageRepository = $productVariantLanguageRepository;
        $this->productVariantAttributeRepository = $productVariantAttributeRepository;
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


                if (!empty($request->input('attribute', []))) {
                    // Đảm bảo rằng hàm createVariant chỉ được gọi khi `attribute` không rỗng
                    if (!empty($request->input('attribute', []))) {
                        $this->createVariant($product, $request, $languageId);
                    }
                }
                
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
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

                $product->product_variants()->each(function($variant){
                    $variant->languages()->detach();
                    $variant->attributes()->detach();
                    $variant->delete();
                });
                if (!empty($request->input('attribute', []))) {
                    // Đảm bảo rằng hàm createVariant chỉ được gọi khi `attribute` không rỗng
                    if (!empty($request->input('attribute', []))) {
                        $this->createVariant($product, $request, $languageId);
                    }
                }
                
            }
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
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
            'product_catalogue_id',
            'code',
            'made_id',
            'price',
            'attributeCatalogue',
            'attribute',
            'variant'
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
        if($payload['price'] != 0){
            $payload['price'] = $this->convert_price($payload['price']);
        }

        // V57
        $payload['attributeCatalogue'] = $this->formatJson($request, 'attributeCatalogue');
        $payload['attribute'] = $this->formatJson($request, 'attribute');
        $payload['variant'] = $this->formatJson($request, 'variant');
        // dd($payload);
        $product=$this->productRepository->create($payload);
        //dd($language);
        //echo -1; die();
        //echo $product->id; die();
        return $product;
    }
    private function updateTableProduct($request, $id){
        $payload = $request->only($this->payload());//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
        //dd($payload);
        //vì chúng ta có khóa ngoại khi thêm bảng này mà khóa ngoại này là user_id thì đó là tài khoản đã đăng nhập thì
        $payload['user_id']=Auth::id();
        $payload['album']=$this->formatAlbum($request);
        if($payload['publish'] == null || $payload['publish'] == 0){
            $payload['publish'] = 1;
        }
        if($payload['price'] != 0){
            $payload['price'] = $this->convert_price($payload['price']);
        }

        // V57
        $payload['attributeCatalogue'] = $this->formatJson($request, 'attributeCatalogue');
        $payload['attribute'] = $this->formatJson($request, 'attribute');
        $payload['variant'] = $this->formatJson($request, 'variant');
        // dd($payload);
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

    // Tạo dữ liệu cho sản phẩm có nhiều phiên bản
    private function createVariant($product, $request, $languageId){
        $payload = $request->only(['variant', 'productVariant', 'attribute']);
        // dd($payload);
        
        // 1. Create product_variants
        $variant = $this->createVariantArray($payload);
        $variants = $product->product_variants()->createMany($variant);

        // 2. Create product_variant_language
        $variantsId = $variants->pluck('id');
        // dd($variantsId);
        $productVariantLanguage = [];
        if(count($variantsId)){
            foreach($variantsId as $key => $val){
                $productVariantLanguage[] = [
                    'product_variant_id' => $val,
                    'language_id' => $languageId,
                    'name' => $payload['productVariant']['name'][$key]
                ];
            }
        }
        // dd($productVariantLanguage);
        $variantLanguage = $this->productVariantLanguageRepository->createBatch($productVariantLanguage);
        // dd($variantLanguage);

        // 3. Create product_variant_attribute
        $attributeCombines = $this->combineAttribute(array_values($payload['attribute']));
        // dd($attributeCombines);

        $variantAttribute = [];
        if(count($variantsId)){
            foreach($variantsId as $key => $val){
                if(count($attributeCombines)){
                    foreach($attributeCombines[$key] as $attributeId){
                        $variantAttribute[]=[
                            'product_variant_id' => $val,
                            'attribute_id' => $attributeId,
                        ];
                    }
                }
            }
        }
        // dd($variantAttribute);
        $variantAttribute = $this->productVariantAttributeRepository->createBatch($variantAttribute);
    }

    private function createVariantArray(array $payload = []): array{
        $variant = [];
        if(isset($payload['variant']['sku']) && count($payload['variant']['sku'])){
            foreach($payload['variant']['sku'] as $key => $val){
                $variant[]=[
                    'code' => ($payload['productVariant']['id'][$key]) ?? '',
                    'quantity' => ($payload['variant']['quantity'][$key]) ?? 0,
                    'sku' => $val,
                    'price' => ($payload['variant']['price'][$key]) ? $this->convert_price($payload['variant']['price'][$key]) : 0.0,
                    'barcode' => ($payload['variant']['barcode'][$key]) ?? '',
                    'file_name' => ($payload['variant']['file_name'][$key]) ?? '',
                    'file_path' => ($payload['variant']['file_path'][$key]) ?? '',
                    'album' => ($payload['variant']['album'][$key]) ?? '',  
                    'user_id' => Auth::id(),                  
                ];
            }
            // dd($variant);
        }
        return $variant;
    }

    // Đệ quy để tiến hành tạo mảng dữ liệu cho bảng product_variant_attribute
    private function combineAttribute($attributes = [], $index = 0){
        if($index === count($attributes)) return [[]];
        
        $subCombines = $this->combineAttribute($attributes, $index + 1);
        $combines = [];
        foreach($attributes[$index] as $key => $val){
            foreach($subCombines as $keySub => $valSub){
                $combines[] = array_merge([$val], $valSub);
            }
        }
        return $combines;
    }

    private function convert_price($price) {
        // Sử dụng str_replace để thay thế dấu chấm bằng chuỗi rỗng
        $converted_price = str_replace('.', '', $price);
        return $converted_price;
    }
}

