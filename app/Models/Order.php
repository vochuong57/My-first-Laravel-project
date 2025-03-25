<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

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
}
