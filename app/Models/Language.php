<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class Language extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'name',
        'canonical',
        'publish',
        'user_id',
        'image',
        'description',
        'current'
    ];

    protected $table='languages';

    //comment vì chung ta chỉ sử dụng phương thức languages() này ở trong models/PostCatalogue thôi
    // public function languages(){//tạo phương thức languages này để tiến hành thêm dữ liệu của 2 bảng 'language_id', 'post_catalogue_id' vào trong bảng post_catalogue_language
    //     return $this->belongsToMany(PostCatalogue::class, 'post_catalogue_language', 'language_id', 'post_catalogue_id')
    //     ->withPivot(
    //     'name', 
    //     'canonical', 
    //     'meta_title', 
    //     'meta_keyword', 
    //     'meta_description', 
    //     'description', 
    //     'content'
    //     )->withTimestamps();
    // }
}
