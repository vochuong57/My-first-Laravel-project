<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdatePostCatalogueRequest extends FormRequest
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
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'=>'Bạn chưa nhập tiêu đề nhóm bài viết',
            'name.string'=>'Tiêu đề nhóm bài viết phải là dạng ký tự',
            'canonical.required'=>'Bạn chưa nhập vào ô đường dẫn',
            'canonical.unique'=>'Đường dẫn đã tồn tại, hãy chọn đường dẫn khác'
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'canonical' => Str::slug($this->canonical)
        ]);
    }
}
