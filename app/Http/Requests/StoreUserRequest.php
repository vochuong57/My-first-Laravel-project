<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'email' => 'required|string|email|unique:users|max:255',
            'name'=>'required|string|regex:/^[^\d]+$/',
            'user_catalogue_id'=>'required|integer|gt:0',
            'password' => 'required|string|min:6',
            'repassword'=>'required|string|same:password',
            'phone'=>'required|string|regex:/^0[0-9]{9}$/'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'=>'Bạn chưa nhập email.',
            'email.email'=>'Email chưa đúng định dạng. VD: abc@gmail.com',
            'email.string'=>'Email phải là dạng ký tự',
            'email.unique'=>'Email đã tồn tại. Hãy chọn email khác',
            'email.max'=>'Độ dài email tối đa 255 ký tự',
            'name.required'=>'Bạn chưa nhập họ tên',
            'name.string'=>'Tên phải là dạng ký tự',
            'name.regex'=>'Tên không được chứa ký tự số',
            'user_catalogue_id'=>'Bạn chưa chọn nhóm thành viên',
            'password.required'=>'Bạn chưa nhập vào mật khẩu',
            'password.min'=>'Độ dài mật khẩu tối thiểu 6 ký tự',
            'password.string'=>'Mật khẩu phải là dạng ký tự',
            'repassword.required'=> 'Bạn chưa nhập lại mật khẩu',
            'repassword.same'=>'Mật khẩu nhập lại không khớp',
            'phone.required'=>'Bạn chưa nhập số điện thoại',
            'phone.regex'=>'Số điện không hợp lệ vui lòng nhập theo định dạng: 0xxxxxxxxx'
        ];
    }
}
