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
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'user_id',
        'follow',
        '{foreignKey}'
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

    //CHỨC NĂNG HIỂN THỊ
    //Thêm khai báo cho mối qua hệ với bảng {relationCatalogue}s n-n
    public function {relationCatalogue}s(){// dùng để add, update dữ liệu của {foreignKey} và catalogue vào bảng {relationTable2} khi đang ở form thêm của bang {ModuleTemplate} và dùng để show tên {relationCatalogue} của từng {ModuleTemplate} trong table/{moduleTemplate} trước tiến lấy được các id của {ModuleTemplate} đó
        return $this->belongsToMany({relationModelCatalogue}::class, '{relationTable2}', '{moduleKey}', '{foreignKey}');//sau đó ở đây nó sẽ lấy được tên nhờ việc nó truy cập được vào trong các function {relationModelCatalogue}::class cụ thể ở đây là {relationCatalogue}_language() nơi lưu tên {relationCatalogue}
    }

}
