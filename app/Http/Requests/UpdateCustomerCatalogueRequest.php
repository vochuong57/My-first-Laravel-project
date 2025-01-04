<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerCatalogueRequest extends FormRequest
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
            'name'=>'required|string|regex:/^[^\d]+$/',
            
        ];
    }

    public function messages(): array
    {
        return [
          
            'name.required'=>'Bạn chưa nhập tên nhóm khách hàng',
            'name.string'=>'Tên nhóm khách hàng phải là dạng ký tự',
            'name.regex'=>'Tên nhóm khách hàng không được chứa ký tự số',
         
        ];
    }
}
