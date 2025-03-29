<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class Order extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'code',
        'fullname',
        'phone',
        'email',
        'province_id',
        'district_id',
        'ward_id',
        'address',
        'description',
        'promotion',
        'cart',
        'customer_id',
        'guest_cookie',
        'method',
        'confirm',
        'payment',
        'delivery',
        'shipping',
    ];

    protected $table='orders';

    protected $casts = [
        'cart' => 'json',
        'promotion' => 'json',
    ];

    public function products(){
        return $this->belongsToMany(Product::class, 'order_product', 'order_id', 'product_id')
        ->withPivot(
        'variant_uuid', 
        'name', 
        'qty', 
        'price', 
        'priceOriginal', 
        'promotion', 
        'option'
        )->withTimestamps();
    }

    public function order_paymentable(){
        return $this->belongsTo(OrderPayment::class, 'order_id', 'id');
    }

    public function provinces(){
        return $this->belongsTo(Province::class, 'province_id', 'code');
    }
    public function districts(){
        return $this->belongsTo(District::class, 'district_id', 'code');
    }
    public function wards(){
        return $this->belongsTo(Ward::class, 'ward_id', 'code');
    }
}
