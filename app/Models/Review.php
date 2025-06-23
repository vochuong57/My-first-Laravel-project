<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class Review extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'reviewable_type',
        'reviewable_id',
        'parent_id',
        'lft',
        'rgt',
        'level',
        'fullname',
        'phone',
        'email',
        'gender',
        'description',
        'score',
        'customer_id',
        'publish',
    ];

    protected $table='reviews';

    public function products(){
        return $this->belongsTo(Product::class,'reviewable_id', 'id');
    }

    public function reviewable()
    {
        return $this->morphTo();
    }
}
