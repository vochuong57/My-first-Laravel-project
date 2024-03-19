<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;
    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'name',
        
    ];

    //Khai báo một cái bảng
    protected $table ='wards';
    protected $primaryKey='code';//khai báo khóa chính của province
    public $incrementing=false;

    public function districts(){
        return $this->belongsTo(District::class, 'district_code','code');
    }
}
