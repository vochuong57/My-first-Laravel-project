<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class UserCatalogue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'name',
        'description',
        'publish'
    ];

    protected $table='user_catalogues';

    public function users(){
        return $this->hasMany(User::class, 'user_catalogue_id', 'id');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'user_catalogue_permission', 'user_catalogue_id', 'permission_id');
    }
}
