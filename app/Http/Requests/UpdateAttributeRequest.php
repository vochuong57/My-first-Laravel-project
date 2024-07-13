<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateAttributeRequest extends FormRequest
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
            'canonical'=>'required|unique:routers,canonical, '.$this->id.',module_id',
            'attribute_catalogue_id'=>'gt:0',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'=>'Bạn chưa nhập tiêu đề nhóm',
            'name.string'=>'Tiêu đề nhóm phải là dạng ký tự',
            'canonical.required'=>'Bạn chưa nhập vào ô đường dẫn',
            'canonical.unique'=>'Đường dẫn đã tồn tại, hãy chọn đường dẫn khác',
            'attribute_catalogue_id.gt'=>'Bạn chưa chọn danh mục cha',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'canonical' => Str::slug($this->canonical)
        ]);
    }
}
