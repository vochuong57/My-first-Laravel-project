<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\QueryScopes;


class ProductVariantLanguage extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'product_variant_id',
        'language_id',
        'name',
    ];

    protected $table='product_variant_language';

    public $timestamps = true;

}
