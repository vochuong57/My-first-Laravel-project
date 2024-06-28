<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class TranslateRequest extends FormRequest
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
            'translate_name'=>'required|string',
            'translate_canonical'=>'required|unique:routers,canonical, '.$this->id.',module_id',
        ];
    }
    public function messages(): array
    {
        return [
            'translate_name.required'=>'Bạn chưa nhập tiêu đề nhóm bài viết',
            'translate_name.string'=>'Tiêu đề nhóm bài viết phải là dạng ký tự',
            'translate_canonical.required'=>'Bạn chưa nhập vào ô đường dẫn',
            'translate_canonical.unique'=>'Đường dẫn đã tồn tại, hãy chọn đường dẫn khác'
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'translate_canonical' => Str::slug($this->translate_canonical)
        ]);
    }
}
