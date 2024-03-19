<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'user_id',
        'follow',
        'post_catalogue_id'
    ];

    protected $table='posts';

    //Chức năng thêm, Sửa cho bảng post_catalogue_language khi cho 2 khóa ngoại thay khóa chính
    public function languages(){//function này sẽ đc sử dụng ở lơp base để tiến tiến hành attach dữ liệu từ bảng thứ 2 sẽ khởi tạo cụ thể nó là createLanguagePivot
        return $this->belongsToMany(Language::class, 'post_language', 'post_id', 'language_id')
        ->withPivot(
        'name', 
        'canonical', 
        'meta_title', 
        'meta_keyword', 
        'meta_description', 
        'description', 
        'content'
        )->withTimestamps();
    }

    //CHỨC NĂNG HIỂN THỊ
    //Thêm khai báo cho mối qua hệ với bảng post_catalogues n-n
    public function post_catalogues(){
        return $this->belongsToMany(PostCatalogue::class, 'post_catalogue_post', 'post_id', 'post_catalogue_id');
    }

}
