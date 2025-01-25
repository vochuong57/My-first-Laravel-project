<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\QueryScopes;

class PromotionObjectVariant extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $table = 'promotion_object_variant';

    protected $fillable =[
        'id',
        'promotion_id',
        'object_id',
        'object_variant_uuid',
        'model',
    ];
}
