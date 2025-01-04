<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class CustomerCatalogue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'name',
        'description',
        'publish'
    ];

    protected $table='customer_catalogues';

    public function customers(){
        return $this->hasMany(Customer::class, 'customer_catalogue_id', 'id');
    }

}
