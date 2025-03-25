<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'order_id',
        'method_name',
        'payment_id',
        'payment_detail',
    ];

    protected $table='order_paymentable';

    protected $casts = [
        'payment_detail' => 'json',
    ];

    public function orders(){
        return $this->hasMany(Order::class, 'order_id', 'id');
    }
}
