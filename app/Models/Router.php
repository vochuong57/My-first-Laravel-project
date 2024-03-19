<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Router extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'routers';

    protected $fillable =[
        'canonical',
        'module_id',
        'controller'
    ];
}
