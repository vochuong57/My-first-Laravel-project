<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreMenuRequest extends FormRequest
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
            'menu.name' => [
                'required',
            ],
            'menu.name.*' => 'required|string',
            'menu.canonical.*' => 'required|unique:menu_language,canonical',
            'menu_catalogue_id'=>'gt:0',
        ];
    }
    public function messages(): array
    {
        return [
            'menu.name.required' => 'Bạn phải tạo ít nhất 1 menu',
            'menu.name.*.required' => 'Có {number} tên menu chưa được nhập',
            'menu.name.*.string' => 'Có {number} tên menu không ở dạng ký tự',
            'menu.canonical.*.required' => 'Có {number} đường dẫn chưa được nhập',
            'menu.canonical.*.unique' => 'Có {number} đường dẫn bị trùng vui lòng kiểm tra lại',
            'menu_catalogue_id.gt'=> 'Bạn chưa chọn vị trí hiển thị menu',
        ];
    }
    protected function prepareForValidation()
    {
        if (is_array($this->canonical)) {
            $this->merge([
                'canonical' => array_map(function($item) {
                    return Str::slug($item);
                }, $this->canonical),
            ]);
        } else {
            $this->merge([
                'canonical' => Str::slug($this->canonical),
            ]);
        }
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('type') === 'none') {
                $validator->errors()->add('type', 'Bạn chưa chọn kiểu menu');
            }
        });
    }
}
