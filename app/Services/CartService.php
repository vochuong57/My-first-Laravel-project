<?php

namespace App\Services;

use App\Services\Interfaces\CartServiceInterface;
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
use App\Services\BaseService;//tiến hành chèn dữ liệu vào bảng ngoài cụ thể là attribute_catalogue_language
use Cart;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface as ProductVariantRepository;
use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;
use App\Services\Interfaces\ProductServiceInterface as ProductService;

/**
 * Class UserService
 * @package App\Services
 */
class CartService extends BaseService implements CartServiceInterface
{
    protected $productRepository;
    protected $productVariantRepository;
    protected $promotionRepository;
    protected $productService;

    public function __construct(ProductRepository $productRepository, ProductVariantRepository $productVariantRepository, PromotionRepository $promotionRepository, ProductService $productService){
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->promotionRepository = $promotionRepository;
        $this->productService = $productService;
    }

    public function create($request, $languageId){
        try {
            // dd($this->language);
            $get = $request->input();
            // dd($get);
            $relation = $this->productService->getProductRelation($languageId);
            $product = $this->productRepository->findById($get['id'], ['*'], $relation);
            // dd($product);
            $price = getPrice($product->toArray());
            // dd($price);
           
            $data = [
                'id' => $product->id,
                'name' => $product->languages[0]->pivot->name,
                'qty' => $get['quantity'],
                'price' => (int) preg_replace('/\D/', '', $price['price_sale']),
            ];
            if(isset($get['attribute_id']) && count($get['attribute_id'])){
                $code = implode(', ', $get['attribute_id']);
                $product_id = $get['id'];
    
                $variant = $this->productVariantRepository->findVariant($code, $product_id, $languageId);
                // dd($variant);
                $data['id'] = $product->id.'_'.$variant->variant_uuid;
                $data['name'] = $product->languages[0]->pivot->name.' - '.$variant->languages[0]->pivot->name;
                $variantPromotion = $this->promotionRepository->findPromotionByVariantUuid($variant->variant_uuid);
                // dd($variantPromotion);
                $variantPrice = getVariantPrice($variant, $variantPromotion);
                // dd($variantPrice);
                if($variantPrice['promotion']){
                    $data['price'] = (int) preg_replace('/\D/', '', $variantPrice['priceSale']);
                }else{
                    $data['price'] = (int) preg_replace('/\D/', '', $variantPrice['priceOld']);
                }
    
                $data['options'] = [
                    'attribute' => $get['attribute_id'],
                ];
            }
            // dd($data);
            // Cart::add($data);
            Cart::instance('shopping')->add($data);
            // dd(Cart::content());
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage(); die();
            return false;
        }
    }
    // $data = ['id' => '293ad', 'name' => 'Product 1', 'qty' => 1, 'price' => 9.99, 'options' => ['size' => 'large']];
    // Cart::add($data);

    // $cart = Cart::content();
    // dd($cart);
}

