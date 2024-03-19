<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLanguage extends Model
{
    use HasFactory;

    protected $table='post_language';

    public function posts(){
        return $this->belongsTo(Post::class,'post_id', 'id');//chưa dùng. chỉ đơn giản tạo file để khi xóa có thể cập nhật cột canonical về null
    }
}
