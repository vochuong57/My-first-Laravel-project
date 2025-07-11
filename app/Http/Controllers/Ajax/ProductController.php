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

    public function filter(Request $request)
    {
        $get = $request->input();
        // dd($get);
        $languageId = $this->language;
        $products = $this->productService->filter($get, $languageId);
        // dd($products);
        if($products->count() > 0){
            $products = $this->productService->combineProductWithPromotion($products->pluck('id')->toArray(), $languageId, $products);
            $pagination = $this->renderPagination($products);
            $products = $this->renderFilterProduct($products);
        }else{
            $products = '<div class="no-data" style="margin-bottom: 20px">Không có sản phẩm nào</div>';
        }
        // dd($products);
        return response()->json([
            'data' => $products,
            'pagination' => $pagination ?? '',
        ]);
    }

    public function renderFilterProduct($products)
    {
        $html = '';
        $html .= '<div class="uk-grid uk-grid-medium">';

        foreach ($products->items() as $key => $val) {
            $category = getCategory($val, 'product_catalogues');
            $product = getProduct($val);
            $image = image($val['image']);
            $price = getPrice($val);
            $promotion = getPromotion($val);
            $review = getReview($val);

            $html .= '<div class="uk-width-1-2 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-5 mb20">';
            $html .= '<div class="product-item product">';

            if (!empty($promotion)) {
                $html .= '<div class="badge badge-bg' . rand(1, 3) . '">' . $promotion . '</div>';
            }

            $html .= '<a href="' . $product['canonical'] . '" class="image img-cover">';
            $html .= '<img src="' . (!empty($image) ? $image : 'Backend/img/not-found.png') . '" alt="' . $product['name'] . '">';
            $html .= '</a>';

            $html .= '<div class="info">';
            $html .= '<div class="category-title"><a href="' . $category['canonical'] . '" title="' . $category['name'] . '">' . $category['name'] . '</a></div>';
            $html .= '<h3 class="title"><a href="' . $product['canonical'] . '" title="' . $product['name'] . '">' . $product['name'] . '</a></h3>';

            $html .= '<div class="rating">';
            $html .= '<div class="uk-flex uk-flex-middle">';
            $html .= '<div class="star-rating" style="--star-width: ' . $review['starPercent'] . '%;">';
            $html .= '<div class="stars"></div>';
            $html .= '</div>';
            $html .= '<span class="rate-number">(' . $review['count'] . ')</span>';
            $html .= '</div>';
            $html .= '</div>';

            $html .= '<div class="product-group">';
            $html .= '<div class="uk-flex uk-flex-middle uk-flex-space-between">';

            $html .= '<div class="price uk-flex uk-flex-bottom">';
            $html .= '<div class="price-sale">' . $price['price_sale'] . 'đ</div>';
            $html .= '<div class="price-old">' . (($price['price_old'] != $price['price_sale']) ? $price['price_old'] . 'đ' : '') . '</div>';
            $html .= '</div>';

            $html .= '<div class="addcart">';
            $html .= renderQuickBuy($val, $product['canonical'], $product['name']);
            $html .= '</div>';

            $html .= '</div>'; // end .uk-flex
            $html .= '</div>'; // end .product-group

            $html .= '</div>'; // end .info

            $html .= '<div class="tools">';
            $html .= '<a href="#" title=""><img src="Frontend/resources/img/trend.svg" alt=""></a>';
            $html .= '<a href="#" title=""><img src="Frontend/resources/img/wishlist.svg" alt=""></a>';
            $html .= '<a href="#" title=""><img src="Frontend/resources/img/compare.svg" alt=""></a>';
            $html .= '<a href="#popup" data-uk-modal title=""><img src="Frontend/resources/img/view.svg" alt=""></a>';
            $html .= '</div>'; // end .tools

            $html .= '</div>'; // end .product-item
            $html .= '</div>'; // end grid item
        }

        $html .= '</div>'; // end grid

        return $html;
    }

    public function renderPagination($products)
    {
        $html = '';
        $html .= '<div class="uk-flex uk-flex-center pagination-ajax">';
        $html .= $products->links('pagination::bootstrap-4'); // dùng mặc định của Laravel
        // Nếu bạn muốn sử dụng một view tùy chỉnh, hãy bỏ comment dòng dưới đây
        // và tạo file resources/views/Frontend/component/basic-pagination.blade.php (nếu chưa có)
        // $html .= $products->links('Frontend.component.basic-pagination', ['model' => $products]);
        $html .= '</div>';
        return $html;
    }

}
