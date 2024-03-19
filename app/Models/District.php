<?php
//Những file như ward, district, province sẽ tự tạo và phải bó S để laravel hiểu tự động kết nối với DB để truy xuất dữ liệu
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'name',
        
    ];

    //Khai báo một cái bảng
    protected $table ='districts';
    protected $primaryKey='code';
    public $incrementing=false;


    public function provinces(){
        return $this->belongsTo(Province::class, 'code');
    }

    //xử lí từ huyện chọn xã
    public function wards(){//tên của function trùng với tên trong DB
        return $this->hasMany(Ward::class, 'district_code', 'code');//nếu khóa ngoại tên khác thì thông báo khóa ngoại ở đây
    }
}
