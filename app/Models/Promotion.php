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
        
    ];

    
}
