<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotAUrl implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Kiểm tra nếu giá trị là một URL
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            // Nếu giá trị là URL, gọi phương thức $fail để thông báo lỗi
            $fail("Trường {$attribute} không được chứa URL.");
        }
    }
}
