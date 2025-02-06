<?php

namespace App\Rules\Promotion;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductAndQuantityRule implements ValidationRule
{
    protected $data;

    public function __construct($data){
        $this->data=$data;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // dd($this->data);

        // Chuyển đổi giá trị từ định dạng có dấu chấm phân cách thành số nguyên
        $discountValue = intval(str_replace('.', '', $this->data['product_and_quantity']['discountValue']));
        // dd($discountValue);

        if($this->data['product_and_quantity']['quantity'] == 0){
            $fail('Bạn phải nhập số lượng mua tối thiểu để hưởng chiết khấu');
        }

        if($this->data['product_and_quantity']['discountType'] == 'percent' && $this->data['product_and_quantity']['maxDiscountValue'] == 0 && $discountValue < 100){
            $fail('Bạn phải nhập giá trị chiết khấu tối đa cho phép vì loại khuyến mại bạn đang chọn là %');
        }

        if($this->data['product_and_quantity']['discountValue'] == 0){
            $fail('Bạn phải nhập vào giá trị của chiết khấu');
        }

        if($this->data['product_and_quantity']['discountType'] == 'percent' && $discountValue >= 101){
            $fail('Giá trị chiết khấu nếu chọn loại chiết khâu % không được quá 100');
        }

        if($this->data['product_and_quantity']['discountType'] == 'percent' && $discountValue == 100 && $this->data['product_and_quantity']['maxDiscountValue'] > 0){
            $fail('Nếu giá trị chiết khấu là 100 và loại chiết khẩu là % thì không nhập dữ liệu vào giới hạn khuyễn mại');
        }

        if($this->data['product_and_quantity']['discountType'] == 'cash' && $this->data['product_and_quantity']['maxDiscountValue'] > 0){
            $fail('Nếu loại chiết khẩu là đ thì không nhập dữ liệu vào giới hạn khuyễn mại');
        }
        
        if(!isset($this->data['object'])){
            $fail('Bạn chưa chọn đối tượng áp dụng chiết khấu');
        }

    }
}
