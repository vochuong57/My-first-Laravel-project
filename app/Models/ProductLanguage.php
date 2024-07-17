<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLanguage extends Model
{
    use HasFactory;

    protected $table='product_language';

    public function products(){
        return $this->belongsTo(Product::class,'product_id', 'id');
    }
}
