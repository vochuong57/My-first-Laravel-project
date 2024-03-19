<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLanguageRequest extends FormRequest
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
            'canonical'=>'required|unique:languages|string|regex:/^[^\d]+$/'
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'=>'Bạn chưa nhập họ tên',
            'name.string'=>'Tên phải là dạng ký tự',
            'name.regex'=>'Tên không được chứa ký tự số',
            'canonical.required'=>'Bạn chưa nhập canonical',
            'canonical.unique'=>'Canonical này đã tồn tại hãy nhập lại canonical khác',
            'canonical.string'=>'Canonical phải là dạng ký tự',
            'canonical.regex'=>'Canonical không được chứa ký tự số'
        ];
    }
}
