<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\QueryScopes;

class Widget extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $table = 'widgets';

    protected $fillable =[
        'id',
        'name',
        'keyword',
        'modle_id',
        'model',
        'album',
        'description',
        'publish',
        'user_id',
    ];

}
