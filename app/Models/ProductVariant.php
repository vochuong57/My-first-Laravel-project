<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\QueryScopes;


class ProductVariant extends Model
{
    use HasFactory, QueryScopes;

    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'product_id',
        'code',
        'quantity',
        'sku',
        'price',
        'barcode',
        'file_name',
        'file_path',
        'album',
        'publish',
        'user_id'
    ];

    protected $table='product_variants';

    // khai báo mối quan hệ này dùng để thêm dữ liệu nhiều mảng cùng một lúc dùng createBatch (insert) vào bảng product_variants
    public function products(){
        return $this->belongsTo(Product::class,'product_id', 'id');
    }

    //Chức năng thêm, Sửa cho bảng post_catalogue_language khi cho 2 khóa ngoại thay khóa chính
    public function languages(){//function này sẽ đc sử dụng ở lơp base để tiến tiến hành attach dữ liệu từ bảng thứ 2 sẽ khởi tạo cụ thể nó là createLanguagePivot
        return $this->belongsToMany(Language::class, 'product_variant_language', 'product_variant_id', 'language_id')
        ->withPivot(
        'name', 
        )->withTimestamps();
    }

    // ProductRepository/getProductById() c1
    public function attributes(){
        return $this->belongsToMany(Attribute::class, 'product_variant_attribute', 'product_variant_id', 'attribute_id');
    }
}
