<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\QueryScopes;


class ProductCatalogue extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'parent_id',
        'lft',
        'rgt',
        'level',
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'user_id',
        'follow'
    ];

    protected $table='product_catalogues';

    //Chức năng thêm, Sửa cho bảng product_catalogue_language khi cho 2 khóa ngoại thay khóa chính
    public function languages(){//function này sẽ đc sử dụng ở lơp base để tiến tiến hành attach dữ liệu từ bảng thứ 2 sẽ khởi tạo cụ thể nó là createLanguagePivot
        return $this->belongsToMany(Language::class, 'product_catalogue_language', 'product_catalogue_id', 'language_id')
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

    //thêm mối quan hệ cho bảng product_catalogue_language để mà lấy ra được tên của product_catalogues dựa vào id truyền tới khi cần vd sử hiện tên nhóm bài viết cho từng bài viết trong post/post/table
    public function product_catalogue_language(){
        return $this->hasMany(ProductCatalogueLanguage::class, 'product_catalogue_id', 'id');
    }

    //thêm mối quan hệ cho bảng products
    public function products(){
        return $this->belongsToMany(Product::class, 'product_catalogue_product', 'product_catalogue_id', 'product_id');
    }

    //CHỨC NĂNG XÓA
    public static function isNodeCheck($id = 0){
        //echo $id; die();
        $productCatalogue=ProductCatalogue::find($id);
        //dd($productCatalogue);
        if($productCatalogue->rgt - $productCatalogue->lft != 1){
            return false;
        }
        return true;
    }

}
