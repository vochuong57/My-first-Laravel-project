<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\QueryScopes;

class Promotion extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $table = 'promotions';

    protected $fillable =[
        'id',
        'name',
        'code',
        'description',
        'method',
        'discountInformation',
        'neverEndDate',
        'startDate',
        'endDate',
        'publish',
        'order',
        'maxDiscountValue',
        'discountValue',
        'discountType',
    ];

    protected $casts = [
        'discountInformation' => 'json',
    ];
}
