<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuLanguage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'name',
        'canonical',
        'menu_id',
        'language_id'
    ];

    protected $table='menu_language';
    protected $primaryKey = 'menu_id';//khai báo khóa chính giả để nếu cần chạy create()
}
