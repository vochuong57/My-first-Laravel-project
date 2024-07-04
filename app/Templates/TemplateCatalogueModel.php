<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\QueryScopes;


class {ModuleTemplate} extends Model
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

    protected $table='{tableNames}';

    //Chức năng thêm, Sửa cho bảng {pivotTable} khi cho 2 khóa ngoại thay khóa chính
    public function languages(){//function này sẽ đc sử dụng ở lơp base để tiến tiến hành attach dữ liệu từ bảng thứ 2 sẽ khởi tạo cụ thể nó là createLanguagePivot
        return $this->belongsToMany(Language::class, '{pivotTable}', '{moduleKey}', 'language_id')
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

    //thêm mối quan hệ cho bảng {pivotTable} để mà lấy ra được tên của {tableNames} dựa vào id truyền tới khi cần vd sử hiện tên nhóm bài viết cho từng bài viết trong post/post/table
    public function {pivotTable}(){
        return $this->hasMany({pivotModel}::class, '{moduleKey}', 'id');
    }

    //thêm mối quan hệ cho bảng {relation}s
    public function {relation}s(){
        return $this->belongsToMany({relationModel}::class, '{relationTable1}', '{moduleKey}', '{relation}_id');
    }

    //CHỨC NĂNG XÓA
    public static function isNodeCheck($id = 0){
        //echo $id; die();
        ${moduleTemplate}={ModuleTemplate}::find($id);
        //dd(${moduleTemplate});
        if(${moduleTemplate}->rgt - ${moduleTemplate}->lft != 1){
            return false;
        }
        return true;
    }

}
