<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'name',
        
    ];

    //Khai báo một cái bảng
    protected $table ='provinces';
}
