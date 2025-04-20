<?php
use Illuminate\Support\Facades\Http;

if(!function_exists('convert_price')){
    function convert_price(string $price = '', $flag = false){
        return ($flag == false) ? str_replace('.','',$price) : number_format($price, 0, ',', '.');
    }
}

//V59
if(!function_exists('convert_array')){
    function convert_array($system = null, $keyword = '', $value = ''){
        $temp = [];
        if(is_array($system)){
            foreach($system as $key => $val){
                $temp[$val[$keyword]] = $val[$value];
            }
        }
        if(is_object($system)){
            foreach($system as $key => $val){
                $temp[$val->{$keyword}] = $val->{$value};
            }
        }

        return $temp;
    }
}

// V58
if(!function_exists('renderSystemInput')){
    function renderSystemInput(string $name = '', $systems = null){
        return '<input 
                type="text"
                name="config['.$name.']"
                value="'.old($name, ($systems[$name]) ?? '').'"
                class="form-control"
                placeholder=""
                autocomplete="off"
                >';
    }
}

if(!function_exists('renderSystemImages')){
    function renderSystemImages(string $name = '', $systems = null){
        return '<input 
                type="text"
                name="config['.$name.']"
                value="'.old($name, ($systems[$name]) ?? '').'"
                class="form-control upload-image"
                placeholder=""
                autocomplete="off"
                >';
    }
}

if(!function_exists('renderSystemTextarea')){
    function renderSystemTextarea(string $name = '', $systems = null){
        return '<textarea name="config['.$name.']"  class="form-control system-textarea">'.old($name, ($systems[$name]) ?? '').'</textarea>';
    }
}

if(!function_exists('renderSystemLink')){
    function renderSystemLink(array $item = []){
        return (isset($item['link'])) ? '<a class="system-link" target="'.$item['link']['target'].'" href="'.$item['link']['href'].'">'.$item['link']['text'].'</a>' : '';
    }
}

if(!function_exists('renderSystemTitle')){
    function renderSystemTitle(array $item = []){
        return (isset($item['title'])) ? '<span class="system-link text-danger">'.$item['title'].'</span>' : '';
    }
}

if(!function_exists('renderSystemSelect')){
    function renderSystemSelect(array $item = [], string $name = '', $systems = null){
        $html = '';
        $html = '<select name="config['.$name.']" class="form-control">';
                foreach($item['option'] as $key => $val){
                    $html .= '<option '.((isset($systems[$name]) && $key == $systems[$name]) ? 'selected' : '' ).' value="'.$key.'">'.$val.'</option>';
                }
        $html .= '</select>';
        return $html;
    }
}

if(!function_exists('renderSystemEditor')){
    function renderSystemEditor(string $name = '', $systems = null){
        return '<textarea name="config['.$name.']" id="'.$name.'" class="form-control system-textarea ck-editor">'.old($name, ($systems[$name]) ?? '').'</textarea>';
    }
}

// V69
if(!function_exists('recursive')){ 
    function recursive($data, $parentId = 0){ // data chạy từ 28, 29 ,49, 50, 51
        $temp=[];
        if(!is_null($data) && count($data)){
            foreach($data as $key => $val){
                if($val->parent_id == $parentId){ // lần đầu chạy ở parentId 0 ra được 2 mảng là của 28, 29. Lần chạy thứ hai parentId 28 ra được 3 mảng là 49, 50, 51 và pareetId 29 thì không mảng nào được tạo ra
                    $temp[]=[
                        'item' => $val,  
                        'children' => recursive($data, $val->id) // lúc này lần chạy thứ 2 parentId là 28, 29, lần chạy thứ 3 thì parent id là 49, 50, 51
                    ];
                }
            }
        }
        return $temp;
    }
}

// <ol class="dd-list">
//     @foreach($menus as $key => $val)
//     <li class="dd-item" data-id="{{ $val->id }}">
//         <div class="dd-handle">
//             <span class="label label-info"><i class="fa fa-arrows"></i></span> {{ $val->languages->first()->getOriginal('pivot_name') }}
//         </div>
//         <a class="create-children-menu" href="{{ route('menu.children', $val->id) }}"> Quản lý menu con </a>
//         <ol class="dd-list">
//             <li class="dd-item" data-id="2">
//                 <div class="dd-handle">
//                     <span class="pull-right"> 12:00 pm </span>
//                     <span class="label label-info"><i class="fa fa-arrows"></i></span> Vivamus vestibulum nulla nec ante.
//                 </div>
//             </li>
//             <li class="dd-item" data-id="3">
//                 <div class="dd-handle">
//                     <span class="pull-right"> 11:00 pm </span>
//                     <span class="label label-info"><i class="fa fa-arrows"></i></span> Nunc dignissim risus id metus.
//                 </div>
//             </li>
//             <li class="dd-item" data-id="4">
//                 <div class="dd-handle">
//                     <span class="pull-right"> 11:00 pm </span>
//                     <span class="label label-info"><i class="fa fa-arrows"></i></span> Vestibulum commodo
//                 </div>
//             </li>
//         </ol>
//     </li>
//     @endforeach
// </ol>

// V69
if(!function_exists('recursive_menu')){
    function recursive_menu($data){
        $html = '';
        if(count($data)){
            foreach($data as $key => $val){
                $itemId = $val['item']->id;
                $itemName = $val['item']->languages->first()->getOriginal('pivot_name');
                $itemUrl = route('menu.children', ['id' => $itemId]);

                $html .= "<li class='dd-item' data-id='$itemId'>";
                $html .=    "<div class='dd-handle'>";
                $html .=        "<span class='label label-info'><i class='fa fa-arrows'></i></span> $itemName";
                $html .=    "</div>";
                $html .=    "<a class='create-children-menu' href='$itemUrl'> Quản lý menu con </a>";
                if(count($val['children'])){
                    $html .= "<ol class='dd-list'>";
                    $html .=    recursive_menu($val['children']);
                    $html .= "</ol>";
                }
                $html .= "</li>";

            }
        }
        return $html;
    }
}

//V74
if(!function_exists('buildMenu')){
    function buildMenu($menus = null, $parentId = 0, $prefix = ''){
        $output = [];
        $count = 1;

        if(count($menus)){
            foreach($menus as $key => $val){ // 28, 50, 29, 49, 51
                if($val->parent_id == $parentId){
                    $val->position = $prefix.$count;
                    $output[] = $val;
                    $output = array_merge($output, buildMenu($menus , $val->id, $val->position . '.')); //[v1 28: 1, v2 50: 1.1, v3 x, v2 49: 1.2, v3 51: 1.2.1, v4 x, v1 29: 2 ]
                    $count++;
                }
            }
        }
        return $output;
    }
}

//V90
if(!function_exists('loadClassInterface')){
    function loadClassInterface(string $model = '', $folder = 'Repositories', $interface = 'Repository'){
        $repositoryInterfaceNamespace='\App\\'.$folder.'\\'.ucfirst($model).$interface;
        if(class_exists($repositoryInterfaceNamespace)){
            $repositoryInstance=app($repositoryInterfaceNamespace);
        }
        return $repositoryInstance;
    }
}

//V120
if(!function_exists('convertDateTime')){
    function convertDateTime(string $date = '', string $format = 'd/m/Y H:i', $inputFormat = 'Y-m-d H:i:s'){
        $carbonDate = \Carbon\Carbon::createFromFormat($inputFormat, $date);
        return $carbonDate->format($format);
    }
}

//V120
if(!function_exists('renderDiscountInformation')){
    function renderDiscountInformation($promotion = []){
        if($promotion->method == 'product_and_quantity'){
            $discountValue = $promotion->discountInformation['info']['discountValue'];
            $discountType = ($promotion->discountInformation['info']['discountType'] == 'percent') ? '%' : 'đ';
            
            return '<span class="label label-success">Giảm: '.$discountValue.$discountType.'</span>';
        }
        return '<div><a href="'.route('promotion.edit', $promotion->id).'">Xem chi tiết</a></div>';
    }
}

// V127
if(!function_exists('frontend_recursive_menu')){
    function frontend_recursive_menu($data, $count = 2){
        $html = '';
        if(count($data)){
            foreach($data as $key => $val){
                $name = $val['item']->languages->first()->getOriginal('pivot_name');
                $canonical = write_url($val['item']->languages->first()->getOriginal('pivot_canonical'));
                
                $ulClass = 'menu-level__'.$count;

                if (count($val['children']) && $count - 1 == 1) {
                    $class = 'children';
                } elseif (count($val['children']) && $count - 1 > 1) {
                    $class = 'sub-children';
                } else {
                    $class = '';
                }
                
                $html .= '<li ' . (!empty($class) ? 'class="' . $class . '"' : '') . '>';
                $html .=    '<a href="'.$canonical.'" title="'.$name.'">'.$name.'</a>';
                if(count($val['children'])){
    $html .= ($count == 2 ? '<div class="dropdown-menu">' : '');
                    $html .=    '<ul class="uk-list uk-clearfix menu-style '.$ulClass.'">';
                    $html .=        frontend_recursive_menu($val['children'], $count+1);
                    $html .=    '</ul>';
    $html .= ($count == 2 ? '</div>' : '');
                }
                $html .= '</li>';
            }
        }
        return $html;
    }
}

// V127
if(!function_exists('write_url')){
    function write_url(string $canonical = '', bool $fullDomain = true, $suffix = true){
        if(strpos($canonical, 'http') == true){
            return $canonical;
        }
        $url = (($fullDomain == true) ? config('app.url') : '').$canonical.(($suffix == true) ? config('apps.general.suffix') : '');
        return $url;
    }
}

// V135
if(!function_exists('getCategory')){
    function getCategory($val, string $model_catalogues = ''){
        $category = [];
        if(count($val[$model_catalogues])){
            $category['name'] = $val[$model_catalogues][0]['languages'][0]['pivot']['name'];
            $category['canonical'] = write_url($val[$model_catalogues][0]['languages'][0]['pivot']['canonical']);
        }
        return $category;
    }
}

use Illuminate\Support\Str;
if(!function_exists('getProduct')){
    function getProduct($val){
        $product = [];
        $product['name'] = Str::limit($val['languages'][0]['pivot']['name'], 50);
        $product['canonical'] = write_url($val['languages'][0]['pivot']['canonical']);
        $product['description'] = Str::limit($val['languages'][0]['pivot']['description'], 120);
        return $product;
    }
}


if(!function_exists('image')){
    function image($image = ''){
        $image = $image ?? 'Backend/img/not-found.png';
        return $image;
    }
}

if(!function_exists('getPrice')){
    function getPrice($val){
        $price = [];
        $price['price_old'] = number_format($val['price']);
        // dd($price);
        // dd($val['promotions']);
        if(!empty($val['promotions'])){
            $price['price_sale'] = (number_format($val['promotions'][0]['discount']) <= 0) ? '0' : number_format($val['promotions'][0]['discount']);
        }else{
            $price['price_sale'] = $price['price_old'];
        }
        // dd($price);
        return $price;
    }
}

if(!function_exists('getPromotion')){
    function getPromotion($val){

        if(!empty($val['promotions'])){
            $promotion = ($val['promotions'][0]['discountType'] == 'cash') ? '-'.number_format($val['promotions'][0]['discountValue']).'đ' : '-'.$val['promotions'][0]['discountValue'].'%';
        }else{
            $promotion = '';
        }
    
        return $promotion;
    }
}

if(!function_exists('getReview')){
    function getReview($val){
        return [
            'star' => rand(1, 5),
            'count' => rand(0, 100),
        ];
    }
}

// V136
if(!function_exists('renderQuickBuy')){
    function renderQuickBuy($product, string $canonical = '', string $name = ''){
        // dd($product);
        $class = 'addToCart';
        $openModal = '';
        if(isset($product['product_variants']) && count($product['product_variants'])){
            $class = 'detailShowPopup';
            $canonical = '#popup';
            $openModal = 'data-uk-modal';
        }
        $html = 
        '<a href="'.$canonical.'" '.$openModal.' title="'.$name.'" class="'.$class.'" data-id="'.$product['id'].'">
            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g>
                <path d="M24.4941 3.36652H4.73614L4.69414 3.01552C4.60819 2.28593 4.25753 1.61325 3.70863 1.12499C3.15974 0.636739 2.45077 0.366858 1.71614 0.366516L0.494141 0.366516V2.36652H1.71614C1.96107 2.36655 2.19748 2.45647 2.38051 2.61923C2.56355 2.78199 2.68048 3.00626 2.70914 3.24952L4.29414 16.7175C4.38009 17.4471 4.73076 18.1198 5.27965 18.608C5.82855 19.0963 6.53751 19.3662 7.27214 19.3665H20.4941V17.3665H7.27214C7.02705 17.3665 6.79052 17.2764 6.60747 17.1134C6.42441 16.9505 6.30757 16.7259 6.27914 16.4825L6.14814 15.3665H22.3301L24.4941 3.36652ZM20.6581 13.3665H5.91314L4.97214 5.36652H22.1011L20.6581 13.3665Z" fill="#253D4E"></path>
                <path d="M7.49414 24.3665C8.59871 24.3665 9.49414 23.4711 9.49414 22.3665C9.49414 21.2619 8.59871 20.3665 7.49414 20.3665C6.38957 20.3665 5.49414 21.2619 5.49414 22.3665C5.49414 23.4711 6.38957 24.3665 7.49414 24.3665Z" fill="#253D4E"></path>
                <path d="M17.4941 24.3665C18.5987 24.3665 19.4941 23.4711 19.4941 22.3665C19.4941 21.2619 18.5987 20.3665 17.4941 20.3665C16.3896 20.3665 15.4941 21.2619 15.4941 22.3665C15.4941 23.4711 16.3896 24.3665 17.4941 24.3665Z" fill="#253D4E"></path>
                </g>
                <defs>
                <clipPath>
                <rect width="24" height="24" fill="white" transform="translate(0.494141 0.366516)"></rect>
                </clipPath>
                </defs>
            </svg>
        </a>';
        return $html;
    }
}

// V140
if(!function_exists('cutnchar')){
    function cutnchar($str = null, $n = 200){
        if(strlen($str) < $n) return $str;
        $html = substr($str, 0, $n);
        $html = substr($html, 0, strpos($html, ' '));
        return $html.'...';
    }
}

if(!function_exists('cut_string_and_decode')){
    function cut_string_and_decode($str = null, $n = 200){
        $str = html_entity_decode($str);
        $str = strip_tags($str);
        $str = cutnchar($str, $n);
        return $str;
    }
}

// V141
if(!function_exists('seo')){
    function seo($model = null, $page = 1){
        $canonical = ($page == 1) ? write_url($model->languages->first()->pivot->canonical) : write_url($model->languages->first()->pivot->canonical, true, false).'/trang-'.$page.config('apps.general.suffix');
        return [
            'meta_title' => (!is_null($model->languages->first()->pivot->meta_title)) ? $model->languages->first()->pivot->meta_title : $model->languages->first()->pivot->name,
            'meta_keyword' => $model->languages->first()->pivot->meta_keyword ?? '',
            'meta_description' => (!is_null($model->languages->first()->pivot->meta_description)) ? $model->languages->first()->pivot->meta_description : cut_string_and_decode($model->description, 168),
            'canonical' => $canonical,
            'meta_images' => (!is_null($model->image)) ? $model->image : 'Backend/img/not-found.png',
        ];
    }
}

// V145
if(!function_exists('modelCount')){
    function modelCount($model, $relation = ''){
        // Đếm số lượng sản phẩm của danh mục hiện tại với điều kiện publish = 2
        $modelCount = 0;
        if (isset($model['item']->$relation)) {
            foreach ($model['item']->$relation as $item) {
                if ($item->publish == 2) {
                    $modelCount++;
                }
            }
        }

        // Nếu danh mục có danh mục con
        if (isset($model['children']) && !empty($model['children'])) {
            foreach ($model['children'] as $key => $val) {
                // Đếm sản phẩm của từng danh mục con với điều kiện publish = 2
                if (isset($val['item']->$relation)) {
                    foreach ($val['item']->$relation as $item) {
                        if ($item->publish == 2) {
                            $modelCount++;
                        }
                    }
                }

                // Nếu danh mục con có danh mục con nữa, đệ quy tiếp tục
                if (!empty($val['children'])) {
                    $modelCount += modelCount($val, $relation); // Đệ quy vào danh mục con
                }
            }
        }

        // Trả về tổng số sản phẩm
        return $modelCount;
    }
}

if(!function_exists('getVariantPrice')){
    function getVariantPrice($variant, $variantPromotion){
        if(!is_null($variantPromotion)){
            $priceSale = $variant->price - $variantPromotion->discount;
            $result = [
                'priceOld' => number_format($variant->price).'đ',
                'priceSale' => number_format($priceSale).'đ',
                'discountValue' => $variantPromotion->discountType == 'percent' ? $variantPromotion->discountValue.'%' : number_format($variantPromotion->discountValue).'đ',
                'promotion' => true,
            ];
            return $result;
        }
        return $result = [
            'priceOld' => number_format($variant->price).'đ',
            'promotion' => false,
        ];
    }
}

if(!function_exists('vnpayConfig')){
    function vnpayConfig(){
        return [
            'vnp_Url' => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
            'vnp_Returnurl' => write_url('return/vnpay'),
            'vnp_TmnCode' => 'W262GW7Y',
            'vnp_HashSecret' => 'LUOFH1YA9VXOQEC7LLBKX9A4U3IG8SRI',
            'vnp_apiUrl' => 'http://sandbox.vnpayment.vn/merchant_webapi/merchant.html',
            'apiUrl' => 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction',
        ];
    }
}
if(!function_exists('momoConfig')){
    function momoConfig(){
        return [
            'partnerCode' => "MOMOBKUN20180529",
            'accessKey' => "klm05TvNBzhg7h7j",
            'secretKey' => "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa",
            'redirectUrl' => write_url('return/momo'),
            'ipnUrl' => write_url('return/momo_ipn'),
        ];
    }
}
if(!function_exists('execPostRequest')){
    function execPostRequest($url, $data){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
}
if(!function_exists('convertVndToUsd')){
    function convertVndToUsd($vnd){
        $response = Http::withOptions([
            'verify' => false,
            'http_errors' => false,
        ])->get('https://open.er-api.com/v6/latest/USD');
    
        if ($response->successful()) {
            $data = $response->json();
    
            if (isset($data['rates']['VND']) && $data['rates']['VND'] != 0) {
                return round($vnd / $data['rates']['VND'], 2); 
            }
    
            dd('Không có tỷ giá VND trong phản hồi:', $data);
        }
    
        dd('Lỗi khi gọi API:', $response->status(), $response->body());
        return null;
    }
    
}