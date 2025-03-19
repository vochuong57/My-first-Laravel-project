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

    public function remakeCart($carts){
        if(!is_null($carts)){
            $cartIds = $carts->pluck('id');
            // dd($cartIds);
            $temp = [];
            $objects = [];

            if(count($cartIds)){
                foreach($cartIds as $key => $val){
                    $extractId = explode('_', $val);
                    if(count($extractId) > 1){
                        $temp['variant'][] = $extractId[1];
                    }else{
                        $temp['product'][] = $extractId[0];
                    }
                }
                // dd($temp);

                if(isset($temp['variant'])){
                    $objects['variants'] = $this->productVariantRepository->findWhereIn('variant_uuid', $temp['variant'])->keyBy('variant_uuid');
                }
                if(isset($temp['product'])){
                    $objects['products'] = $this->productRepository->findWhereIn('id', $temp['product'])->keyBy('id');
                }
                // dd($objects);

                foreach($carts as $keyCart => $cart){
                    $explode = explode('_', $cart->id);
                    $objectId = $explode[1] ?? $explode[0];

                    if(isset($objects['variants'][$objectId])){
                        $variantItem = $objects['variants'][$objectId];
                        $variantImage = explode(',', $variantItem->album)[0] ?? 'Backend/img/not-found.png';
                        $cart->image = $variantImage;
                        $cart->priceOriginal = ($cart->price != $variantItem->price) ? $variantItem->price : null;
                    }elseif(isset($objects['products'][$objectId])){
                        $productItem = $objects['products'][$objectId];
                        $productImage = $productItem->image ?? 'Backend/img/not-found.png';
                        $cart->image = $productImage;
                        $cart->priceOriginal = ($cart->price != $productItem->price) ? $productItem->price : null;
                    }
                }
            }
        }else{
            $carts = [];
        }
        return $carts;
    }

    public function update($request){
        try {
            $payload = $request->input();
            // dd($payload);
            Cart::instance('shopping')->update($payload['rowId'], $payload['qty']);

            $carts = Cart::instance('shopping')->content();
            $total = 0;
            $totalItems = 0;
            foreach($carts as $cart){
                $total += $cart->price * $cart->qty;
                $totalItems += $cart->qty;
            }
            // dd($total);

            $cartItem = Cart::instance('shopping')->get($payload['rowId']);
            // dd($cartItem);
            $cartItemSubTotal = $cartItem->price * $cartItem->qty;
            // dd($cartItemSubTotal);
            $cartItemSubQuantity = $cartItem->qty;

            return [
                'cartTotal' => $total,
                'cartTotalItems' => $totalItems,
                'cartItemSubTotal' => $cartItemSubTotal,
                'cartItemSubQuantity' => $cartItemSubQuantity,
            ];
        }catch (\Exception $e) {
            echo $e->getMessage(); die();
            return false;
        }
    }
}

