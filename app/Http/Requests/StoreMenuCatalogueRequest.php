<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuCatalogueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required|string',
            'keyword'=>'required|unique:menu_catalogues|string'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'=>'Bạn chưa nhập tên menu',
            'name.string'=>'Tên menu phải là dạng ký tự',
            'keyword.required'=>'Bạn chưa nhập từ khóa',
            'keyword.string'=>'Từ khóa menu phải là dạng ký tự',
            'keyword.unique'=>'Từ khóa menu đã tồn tại, hãy nhập từ khóa khác',
        ];
    }
}
