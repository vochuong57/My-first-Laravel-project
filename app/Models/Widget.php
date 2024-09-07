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
        'model_id',
        'model',
        'album',
        'description',
        'publish',
        'user_id',
        'short_code'
    ];

    // Accessor để giải mã JSON khi truy cập thuộc tính `model_id`
    public function getModel_idAttribute($value)
    {
        return json_decode($value, true);
    }
    public function getAlbumAttribute($value)
    {
        return json_decode($value, true);
    }
}
