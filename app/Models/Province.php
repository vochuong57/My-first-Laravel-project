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
    protected $table ='provinces';//khai báo tên bảng,
    protected $primaryKey='code';//khai báo khóa chính của province
    public $incrementing=false;

    //xử lí từ thành phố chọn huyện
    public function districts(){//tên của function trùng với tên trong DB
        return $this->hasMany(District::class, 'province_code', 'code');//nếu khóa ngoại tên khác thì thông báo khóa ngoại ở đây
    }
}
