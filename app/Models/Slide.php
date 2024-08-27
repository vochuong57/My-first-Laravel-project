<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\QueryScopes;

class Slide extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $table = 'slides';

    protected $fillable =[
        'id',
        'name',
        'keyword',
        'album',
        'setting',
        'publish',
        'user_id',
        'short_code'
    ];

    // Đừng dùng tới đoạn code này nếu như chúng ta đã tự xữ lí thủ công dữ liệu cột setting và album ép về kiểu json ở service
    // protected $casts = [
    //     'album' => 'json',
    //     'setting' => 'json'
    // ];

    // Accessor để giải mã JSON khi truy cập thuộc tính `setting`
    public function getSettingAttribute($value)
    {
        return json_decode($value, true);
    }

    // Accessor để giải mã JSON khi truy cập thuộc tính `album`
    public function getAlbumAttribute($value)
    {
        return json_decode($value, true);
    }
}
