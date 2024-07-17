<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\QueryScopes;


class Product extends Model
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
        'product_catalogue_id'
    ];

    protected $table='products';

    //Chức năng thêm, Sửa cho bảng product_language khi cho 2 khóa ngoại thay khóa chính
    public function languages(){//function này sẽ đc sử dụng ở lơp base để tiến tiến hành attach dữ liệu từ bảng thứ 2 sẽ khởi tạo cụ thể nó là createLanguagePivot
        return $this->belongsToMany(Language::class, 'product_language', 'product_id', 'language_id')
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
    //Thêm khai báo cho mối qua hệ với bảng product_catalogues n-n
    public function product_catalogues(){// dùng để add, update dữ liệu của product_catalogue_id và catalogue vào bảng product_catalogue_product khi đang ở form thêm của bang Product và dùng để show tên product_catalogue của từng Product trong table/product trước tiến lấy được các id của Product đó
        return $this->belongsToMany(ProductCatalogue::class, 'product_catalogue_product', 'product_id', 'product_catalogue_id');//sau đó ở đây nó sẽ lấy được tên nhờ việc nó truy cập được vào trong các function ProductCatalogue::class cụ thể ở đây là product_catalogue_language() nơi lưu tên product_catalogue
    }

}
