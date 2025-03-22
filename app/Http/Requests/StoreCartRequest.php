<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartRequest extends FormRequest
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
            'fullname' => 'required',
            'phone' => ['required', 'regex:/^\d+$/', 'max:20'],
            'email' => 'required|email',
            'address' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required'=>'* Bạn chưa nhập Họ Tên.',
            'phone.required'=>'* Bạn chưa nhập Số điện thoại.',
            'phone.regex' => '* Số điện thoại chỉ được chứa các ký tự số.',
            'phone.max' => '* Số điện thoại không được vượt quá 20 ký tự.',
            'email.required'=>'* Bạn chưa nhập email.',
            'email.email'=>'* Email chưa đúng định dạng. VD: abc@gmail.com',
            'address.required'=>'* Bạn chưa nhập Địa chỉ.',
        ];
    }
}
