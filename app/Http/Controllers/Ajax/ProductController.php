<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
//chèn thêm thư viện có sẵn Request để lấy thông tin value của input mà ajax đã thiết lập
use Illuminate\Http\Request;
//thêm thư viện tự tạo
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Models\Language;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface as ProductVariantRepository;
use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;
use App\Services\Interfaces\ProductServiceInterface as ProductService;

class ProductController extends Controller
{
    protected $productRepository;
    protected $language;
    protected $productVariantRepository;
    protected $promotionRepository;
    protected $productService;

    public function __construct(ProductRepository $productRepository, ProductVariantRepository $productVariantRepository, PromotionRepository $promotionRepository, ProductService $productService){
        $this->productRepository=$productRepository;
        $this->productVariantRepository=$productVariantRepository;
        $this->promotionRepository=$promotionRepository;
        $this->productService=$productService;

        $this->middleware(function($request, $next) {
            $locale = app()->getLocale(); // vn cn en
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });

    }

    public function loadProductPromotion(Request $request){
        $get = $request->input();
        // dd($get);
        $condition = [
            ['tb2.language_id', '=', $this->language]
        ];
        if(isset($get['keyword'])){
            $condition[] = ['tb2.name', 'like', '%'.$get['keyword'].'%'];
        }
        // dd($condition);
        $loadClass = loadClassInterface($get['model']);
        // dd($loadClass);
        if($get['model'] == 'Product'){
            $objects = $loadClass->findProductPromotion($condition);
        }elseif($get['model'] == 'ProductCatalogue'){

            $conditionArray['keyword'] = $get['keyword'] ?? null;
            $conditionArray['where'] = [
                ['tb2.language_id', '=', $this->language]
            ];

            $objects = $loadClass->pagination(
                [
                    'product_catalogues.id',
                    'tb2.name',
                ],
                $conditionArray,
                20,
                [
                    'path' => '/admin/ajax/product/loadProductPromotion'
                ],
                [
                    'product_catalogues.id', 'desc'
                ],
                [
                    ['product_catalogue_language as tb2', 'tb2.product_catalogue_id', '=', 'product_catalogues.id']
                ],
            );
            // dd($objects);
        }
        // dd($objects->toArray());

        return response()->json([
            'model' => $get['model'] ?? 'Product',
            'objects' => $objects
        ]);
    }

    public function loadVariant(Request $request){
        $get = $request->input();
        // dd($get);
        $code = implode(', ', $get['attribute_id']);
        // dd($code);
        $product_id = $get['product_id'];
        $language_id = $get['language_id'];

        $variant = $this->productVariantRepository->findVariant($code, $product_id, $language_id);
        // dd($variant);

        // dd($variant->variant_uuid);
        $variantPromotion = $this->promotionRepository->findPromotionByVariantUuid($variant->variant_uuid);
        // dd($variantPromotion);
        $variantPrice = getVariantPrice($variant, $variantPromotion);
        // dd($variantPrice);

        return response()->json([
            'variant' => $variant,
            'variantPrice' => $variantPrice,
        ]);
    }

    public function detailShowPopup(Request $request){
        // dd($request->input());
        // dd($this->language);
        $languageId = $this->language;
        $id = $request->input('product_id');

        $relation = $this->productService->getProductRelation($languageId);

        $product = $this->productRepository->findById($id, ['*'], $relation);
        // dd($product);

        $flag = true;
        if(!is_null($product) && $product->attribute != null){
            $product->attributeInfo = $this->productService->getAttributeInfo($product, $languageId);
        }else{
            $flag = false;
        }
        // dd($product);
       
        return response()->json([
            'product' => $product,
            'code' => $flag ? 200 : 500
        ]);
    }
}
