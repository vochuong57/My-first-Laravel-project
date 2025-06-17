<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
            'score'=>'required',
            'description'=>'required',
            'gender'=>'required',
            'fullname'=>'required|string|regex:/^[^\d]+$/',
            'phone'=>'required|string|regex:/^0[0-9]{9}$/',
            'email' => 'required|string|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'score.required'=>'Bạn chưa chọn đánh giá',
            'description.required'=>'Bạn chưa nhập nội dung đánh giá',
            'gender.required'=>'Bạn chưa chọn giới tính',
            'fullname.required'=>'Bạn chưa nhập họ tên',
            'fullname.string'=>'Tên phải là dạng ký tự',
            'fullname.regex'=>'Tên không được chứa ký tự số',
            'phone.required'=>'Bạn chưa nhập số điện thoại',
            'phone.regex'=>'Số điện không hợp lệ vui lòng nhập theo định dạng: 0xxxxxxxxx',
            'email.required'=>'Bạn chưa nhập email.',
            'email.email'=>'Email chưa đúng định dạng. VD: abc@gmail.com',
            'email.string'=>'Email phải là dạng ký tự',
            'email.max'=>'Độ dài email tối đa 255 ký tự',
        ];
    }
}
