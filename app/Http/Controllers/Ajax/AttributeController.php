<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
//chèn thêm thư viện có sẵn Request để lấy thông tin value của input mà ajax đã thiết lập
use Illuminate\Http\Request;
//thêm thư viện tự tạo
use App\Repositories\Interfaces\AttributeRepositoryInterface as AttributeRepository;
use App\Models\Language;

class AttributeController extends Controller
{
    protected $attributeRepository;
    protected $language;

    public function __construct(AttributeRepository $attributeRepository){
       $this->attributeRepository=$attributeRepository;

        $this->middleware(function($request, $next) {
            $locale = app()->getLocale(); // vn cn en
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    //Dùng để lấy ra id và name của attribute và attribute_language dựa vào attribute_catalogue_id (ajax) đã chọn
    public function getAttribute(Request $request){
        $payload = $request->input();
        $attributes = $this->attributeRepository->searchAttributes($payload['search'], $payload['option'], $this->language);

        $attributeMapped = $attributes->map(function($item){
            return [
                'id' => $item->id,
                'text' => $item->attribute_language->first()->name,
            ];
        })->all();

        return response()->json(array('items' => $attributeMapped));
    }
    // V54
    public function loadAttribute(Request $request){
        $payload['attribute'] = json_decode(base64_decode($request->input('attribute')), TRUE);
        $payload['attributeCatalogueId'] = $request->input('attributeCatalogueId');
        // dd($payload);
        $attributeArray =  $payload['attribute'][$payload['attributeCatalogueId']];
        // dd($attributeArray);

        $attributes = [];
        if(count($attributeArray)){
            $attributes = $this->attributeRepository->findAttributeByIdArray($attributeArray, $this->language);
        }
        // dd($attributes);
        $temp = [];
        if(count($attributes)){
            foreach($attributes as $key => $val){
                $temp[] = [
                    'id' => $val->id,
                    'text' => $val->name
                ];
            }
        }
        // dd($temp);

        return response()->json(array('items' => $temp));
    }
}
