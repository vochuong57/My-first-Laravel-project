<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\QueryScopes;


class Attribute extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'user_id',
        'follow',
        'attribute_catalogue_id'
    ];

    protected $table='attributes';

    //Chức năng thêm, Sửa cho bảng attribute_language khi cho 2 khóa ngoại thay khóa chính
    public function languages(){//function này sẽ đc sử dụng ở lơp base để tiến tiến hành attach dữ liệu từ bảng thứ 2 sẽ khởi tạo cụ thể nó là createLanguagePivot
        return $this->belongsToMany(Language::class, 'attribute_language', 'attribute_id', 'language_id')
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
    //Thêm khai báo cho mối qua hệ với bảng attribute_catalogues n-n
    public function attribute_catalogues(){// dùng để add, update dữ liệu của attribute_catalogue_id và catalogue vào bảng attribute_catalogue_attribute khi đang ở form thêm của bang Attribute và dùng để show tên attribute_catalogue của từng Attribute trong table/attribute trước tiến lấy được các id của Attribute đó
        return $this->belongsToMany(AttributeCatalogue::class, 'attribute_catalogue_attribute', 'attribute_id', 'attribute_catalogue_id');//sau đó ở đây nó sẽ lấy được tên nhờ việc nó truy cập được vào trong các function AttributeCatalogue::class cụ thể ở đây là attribute_catalogue_language() nơi lưu tên attribute_catalogue
    }

    public function attribute_language()
    {
        return $this->hasMany(AttributeLanguage::class, 'attribute_id');
    }
}
