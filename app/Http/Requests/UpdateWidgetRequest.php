<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateWidgetRequest extends FormRequest
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
            'name' => 'required',
            'keyword' => 'required|unique:widgets,keyword, '.$this->id.'',
            'widget.image' => [
                'required',
            ],
            // 'widget.description.*' => 'required|string',
            // 'widget.canonical.*' => ['required', 'distinct'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập tên widget',
            'keyword.required' => 'Bạn chưa nhập từ khóa',
            'keyword.unique'=>'Từ khóa đã tồn tại, hãy nhập từ khóa khác',
            'widget.image.required' => 'Bạn phải tạo ít nhất 1 widget',
            // 'widget.description.*.required' => 'Có {number} tên widget chưa được nhập',
            // 'widget.canonical.*.required' => 'Có {number} đường dẫn chưa được nhập',
            // 'widget.canonical.*.unique' => 'Có {number} đường dẫn bị trùng vui lòng kiểm tra lại',
            // 'widget.canonical.*.distinct' => 'Có {number} đường dẫn bị trùng lặp.',
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
    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {
    //         if ($this->input('type') === 'none') {
    //             $validator->errors()->add('type', 'Bạn chưa chọn kiểu widget');
    //         }
    //     });
    // }
}
