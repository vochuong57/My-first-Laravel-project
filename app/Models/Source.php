<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\QueryScopes;

class Source extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $table = 'sources';

    protected $fillable =[
        'id',
        'name',
        'keyword',
        'description',
        'publish',
        'user_id',
    ];

    public function customers(){
        return $this->hasMany(Customer::class, 'source_id', 'id');
    }
}
