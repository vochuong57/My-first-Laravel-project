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
use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use App\Repositories\Interfaces\DistrictRepositoryInterface as DistrictRepository;
use App\Repositories\Interfaces\WardRepositoryInterface as WardRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;

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
    protected $orderRepository;
    protected $provinceRepository;
    protected $districtRepository;
    protected $wardRepository;

    public function __construct(ProductRepository $productRepository, ProductVariantRepository $productVariantRepository, PromotionRepository $promotionRepository, ProductService $productService, OrderRepository $orderRepository, ProvinceRepository $provinceRepository, DistrictRepository $districtRepository, WardRepository $wardRepository){
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->promotionRepository = $promotionRepository;
        $this->productService = $productService;
        $this->orderRepository = $orderRepository;
        $this->provinceRepository = $provinceRepository;
        $this->districtRepository = $districtRepository;
        $this->wardRepository = $wardRepository;
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

    public function remakeCart($carts, $languageId){
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
                        // dd($variantItem);
                        $albumArray = explode(',', $variantItem->album);
                        $variantImage = !empty($albumArray[0]) ? $albumArray[0] : 'Backend/img/not-found.png';
                        $cart->image = $variantImage;
                        $cart->priceOriginal = ($cart->price != $variantItem->price) ? $variantItem->price : null;
                        $variantPromotion = $this->promotionRepository->findPromotionByVariantUuid($variantItem->variant_uuid);
                        if(!is_null($variantPromotion)){
                            $cart->promotion = [
                                'id' => $variantPromotion->id,
                                'maxDiscountValue' => $variantPromotion->maxDiscountValue,
                                'discountValue' => $variantPromotion->discountValue,
                                'discountType' => $variantPromotion->discountType,
                                'discount' => $variantPromotion->discount,
                            ];
                        }else{
                            $cart->promotion = null;
                        }
                    }elseif(isset($objects['products'][$objectId])){
                        $productItem = $objects['products'][$objectId];
                        $productImage = $productItem->image ?? 'Backend/img/not-found.png';
                        $cart->image = $productImage;
                        $cart->priceOriginal = ($cart->price != $productItem->price) ? $productItem->price : null;
                        $relation = $this->productService->getProductRelation($languageId);
                        $product = $this->productRepository->findById($productItem->id, ['*'], $relation);
                        // dd($product);
                        if(isset($product->promotions[0])){
                            $productPromotion = $product->promotions[0];
                            // dd($productPromotion);
                            $cart->promotion = [
                                'id' => $productPromotion->id,
                                'maxDiscountValue' => $productPromotion->maxDiscountValue,
                                'discountValue' => $productPromotion->discountValue,
                                'discountType' => $productPromotion->discountType,
                                'discount' => $productPromotion->discount,
                            ];
                        }else{
                            $cart->promotion = null;
                        }
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

            $cartCaculate = $this->reCaculateCart();
            $cartPromotion = $this->cartPromotion($cartCaculate['cartTotal']);
            $cartCaculate['cartTotal'] = $cartCaculate['cartTotal'] - $cartPromotion['discount'];
            $cartCaculate['discount'] = $cartPromotion['discount'];

            $cartItem = Cart::instance('shopping')->get($payload['rowId']);
            // dd($cartItem);
            $cartCaculate['cartItemSubTotal'] = $cartItem->price * $cartItem->qty;
            // dd($cartItemSubTotal);
            $cartCaculate['cartItemSubQuantity'] = $cartItem->qty;

            return $cartCaculate;
        }catch (\Exception $e) {
            echo $e->getMessage(); die();
            return false;
        }
    }

    public function delete($request){
        try {
            $payload = $request->input();
            // dd($payload);
            Cart::instance('shopping')->remove($payload['rowId']);

            $cartCaculate = $this->reCaculateCart();
            $cartPromotion = $this->cartPromotion($cartCaculate['cartTotal']);
            $cartCaculate['cartTotal'] = $cartCaculate['cartTotal'] - $cartPromotion['discount'];
            $cartCaculate['discount'] = $cartPromotion['discount'];

            return $cartCaculate;
        }catch (\Exception $e) {
            echo $e->getMessage(); die();
            return false;
        }
    }

    public function reCaculateCart(){
        $carts = Cart::instance('shopping')->content();
        $total = 0;
        $totalItems = 0;
        foreach($carts as $cart){
            $total += $cart->price * $cart->qty;
            $totalItems += $cart->qty;
        }
        // dd($total);

        return [
            'cartPreTotal' => $total,
            'cartTotal' => $total,
            'cartTotalItems' => $totalItems,
        ];
    }

    public function cartPromotion($cartTotal){
        $maxDisount = 0;
        $selectedPromotion = null;
        $promotions = $this->promotionRepository->getPromotionByCartTotal();
        // dd($promotions);
        if(!is_null($promotions)){
            foreach($promotions as $key => $promotion){
                // dd($promotion);
                // echo 'promotion: '.$key;
                // echo '<br><br>';
                $discount = $promotion->discountInformation['info'];
                // dd($discount);
                $amountFrom = $discount['amountFrom'] ?? [];
                $amountTo = $discount['amountTo'] ?? [];
                $amountValue = $discount['amountValue'] ?? [];
                $amountType = $discount['amountType'] ?? [];

                if(!empty($amountFrom) && count($amountFrom) == count($amountTo) && count($amountTo) == count($amountValue)){
                    for($i = 0; $i < count($amountFrom); $i++){
                        $currentAmountFrom = convert_price($amountFrom[$i]);
                        // echo $currentAmountFrom.'<br>';
                        $currentAmountTo = convert_price($amountTo[$i]);
                        // echo $currentAmountTo.'<br>';
                        $currentAmountValue = convert_price($amountValue[$i]);
                        // echo $currentAmountValue.'<br>';
                        $currentAmountType = $amountType[$i];
                        // echo $currentAmountType.'<br><br>';

                        if($cartTotal > $currentAmountFrom && ($cartTotal <= $currentAmountTo || $cartTotal > $currentAmountTo)){
                            if($currentAmountType == 'cash'){
                                $maxDisount = max($maxDisount, $currentAmountValue);
                            }
                            $discountValue = 0;
                            if($currentAmountType == 'percent'){
                                $discountValue = ($currentAmountValue/100)*$cartTotal;
                                $maxDisount = max($maxDisount, $discountValue);
                            }
                            if($maxDisount != 0 && ($maxDisount ==  $currentAmountValue || $maxDisount == $discountValue)){
                                $selectedPromotion = $promotion;
                            }
                        }
                    }
                }
            }
            // echo $maxDisount;
            // dd($selectedPromotion);
            // die();

            return[
                'discount' => $maxDisount,
                'selectedPromotion' => $selectedPromotion,
            ];
        }
    }

    public function createDB($request, $languageId, $systems){        
        DB::beginTransaction();
        try{
            $payload = $this->requestOrderTable($request, $languageId);
            // dd($payload);
            $order = $this->orderRepository->create($payload);
            // dd($order);
            if($order->id > 0){
                $this->createOrderProduct($payload, $request, $order);
                $this->mail($order, $systems);
            }
            // die();
            DB::commit();
            return [
                'order' => $order,
                'flag' => true,
            ];
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return [
                'order' => null,
                'flag' => false,
            ];
        }

        // automicity
    }

    private function toArrayCartColumn($carts)
    {
        $result;
        foreach($carts as $key => $val){
            $result[] = [
                'rowId' => $val->rowId,
                'id' => $val->id,
                'qty' => $val->qty,
                'name' => $val->name,
                'price' => $val->price,
                'priceOriginal' => $val->priceOriginal,
                'image' => $val->image,
                'promotion' => $val->promotion,
                'options' => $val->options->toArray(),
            ];
        }
        return $result;
    }

    private function requestOrderTable($request, $languageId){
        $payload = $request->except(['_token', 'voucher', 'create']);
        // dd($payload);
        $carts = Cart::instance('shopping')->content();
        // dd($carts);
        $carts = $this->remakeCart($carts, $languageId);
        // dd($carts->toArray());
        $cartCaculate = $this->reCaculateCart();
        // dd($cartCaculate);
        $cartPromotion = $this->cartPromotion($cartCaculate['cartTotal']);
        // dd($cartPromotion);
        $payload['cart'] = $cartCaculate;
        $payload['cart']['detail'] = $this->toArrayCartColumn($carts);
        $payload['promotion']['discount'] = $cartPromotion['discount'];
        $payload['promotion']['name'] = $cartPromotion['selectedPromotion']->name;
        $payload['promotion']['code'] = $cartPromotion['selectedPromotion']->code;
        $payload['promotion']['startDate'] = $cartPromotion['selectedPromotion']->startDate;
        $payload['promotion']['endDate'] = $cartPromotion['selectedPromotion']->endDate;
        $payload['confirm'] = 'pending';
        $payload['payment'] = 'unpaid';
        $payload['delivery'] = 'pending';
        $payload['code'] = time();

        return $payload;
    }

    private function createOrderProduct($payload, $request, $order){
        // dd($payload);
        $temp = [];
        if(!is_null($payload['cart']['detail'])){
            foreach($payload['cart']['detail'] as $key => $val){
                $extract = explode('_', $val['id']);
                $temp[] = [
                    'product_id' => $extract[0],
                    'variant_uuid' => $extract[1] ?? null,
                    'name' => $val['name'],
                    'qty' => $val['qty'],
                    'price' => $val['price'],
                    'priceOriginal' => $val['priceOriginal'] ?? null,
                    'promotion' => !is_null($val['promotion']) ? json_encode($val['promotion']) : null,
                    'option' => count($val['options']) ? json_encode($val['options']) : null,
                ];
                
            }
            // dd($temp);
            $order->products()->sync($temp);
        }
    }


    public function getFullAddress($order){
        $arrAddress;
        $modelName = ['province', 'district', 'ward'];
        $arrCode = [$order->province_id, $order->district_id, $order->ward_id];
        for($i = 0; $i < count($modelName); $i++){
            $modelRepository = $modelName[$i].'Repository';
            $code = $arrCode[$i];
            $condition = [
                ['code', '=', $code],
            ];
            $arrAddress[$modelRepository] = $this->$modelRepository->findByCondition($condition)->name;
        }
        // dd($arrAddress);
        $addressPDW = $arrAddress['wardRepository'].', '.$arrAddress['districtRepository'].', '.$arrAddress['provinceRepository'];
        $order->fullAddress = $order->address.', '.$addressPDW;
        return $order;
    }

    private function mail($order, $systems){
        $to = $order->email;
        $cc = $systems['contact_email'];
        $order = $this->getFullAddress($order);
        $order->link = route('home.index');
        $data = $order;
        // dd($data);
        Mail::to($to)->cc($cc)->send(new OrderMail($data));
    }
}

