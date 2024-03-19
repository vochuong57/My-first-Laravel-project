<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'name',
        'canonical'
    ];

    protected $table='permissions';

    public function user_catalogues(){
        return $this->belongsToMany(UserCatalogue::class, 'user_catalogue_permission', 'permission_id', 'user_catalogue_id');
    }
}
