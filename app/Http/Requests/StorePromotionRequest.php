<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StorePromotionRequest extends FormRequest
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
        $date = $this->only('startDate', 'endDate');
        // dd($date);
       
        $rules = [
            'name' => 'required',
            'code' => 'required|unique:promotions',
            'startDate' => 'required|custom_date_format',
        ];

        if(!$this->input('neverDate')){
            $rules['endDate'] = 'required|custom_date_format|custom_date_after:startDate';
            // $rules['endDate'] = 'required|custom_date_format|after:startDate';
        }
        // Y-m-d H:i đỉnh dạng của check after dạng time của hệ thống
        // d/m/Y H:i
        
        if($this->input('source') == 'choose'){
            $rules['sourceValue'] = 'required|array|min:1'; // Ensure at least one option is selected
            $rules['sourceValue.*'] = 'required|string'; // Optional: Validate each selected value
        }


        return $rules;
    }
    public function messages(): array
    {
        $messages = [
            'name.required' => 'Bạn chưa nhập tên khuyến mại',
            'code.required' => 'Bạn chưa nhập mã khuyến mại',
            'code.unique'=>'Mã khuyến mại đã tồn tại, hãy nhập mã khuyến mại khác',
            'startDate.required' => 'Bạn chưa nhập vào ngày bắt đầu khuyến mại',
            'startDate.custom_date_format' => 'Ngày bắt đầu khuyến mại không đúng đinh dạng d/m/Y H:i',
            'endDate.required' => 'Bạn chưa nhập vào ngày kết thúc khuyến mại',
            'endDate.custom_date_format' => 'Ngày kết thúc khuyến mại không đúng đinh dạng d/m/Y H:i',
        ];

        if(!$this->input('neverDate')){
            $messages['endDate.required'] = 'Bạn chưa chọn ngày kết thúc của khuyến mãi';
            $messages['endDate.custom_date_after'] = 'Ngày kết thúc khuyến mại phải lớn hơn ngày bắt đầu khuyến mại';
        }

        if($this->input('source') == 'choose'){
            $messages['sourceValue.required'] = 'Bạn chưa chọn ít nhất một nguồn khách áp dụng';
            $messages['sourceValue.array'] = 'Giá trị nguồn nguồn khách áp dụng không hợp lệ';
            $messages['sourceValue.min'] = 'Bạn phải chọn ít nhất một nguồn khách áp dụng';
            $messages['sourceValue.*.required'] = 'Giá trị nguồn khách áp dụng không hợp lệ';
        }

        return $messages;
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
    //             $validator->errors()->add('type', 'Bạn chưa chọn kiểu promotion');
    //         }
    //     });
    // }
}
