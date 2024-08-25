<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\QueryScopes;

class Slide extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $table = 'slides';

    protected $fillable =[
        'id',
        'name',
        'keyword',
        'album',
        'setting',
        'publish',
        'user_id',
        'short_code'
    ];
}
