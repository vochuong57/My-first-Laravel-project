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
}
