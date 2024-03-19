<?php
// file này có sẵn lúc mới tạo project laravel
// Laravel giả định rằng tên bảng là biểu diễn số ít và chữ thường của tên model với thêm vào đó là "s" ở cuối. Trong trường hợp của User.php, Laravel sẽ tự động liên kết nó với bảng "users".
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [//c=những trường nào cho phép người dùng cập nhật thông tin
        'name',
        'email',
        'password',
        'phone',
        'province_id',
        'district_id',
        'ward_id',
        'address',
        'birthday',
        'image',
        'description',
        'user_agent',
        'ip',
        'user_catalogue_id',
        'publish'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function user_catalogues(){
        return $this->belongsTo(UserCatalogue::class,'user_catalogue_id', 'id');
    }
    // //phân quyền
    // public function hasPermission($permissionCanonical){
    //     return $this->user_catalogues->permissions->contains('canonical',$permissionCanonical);
    // }
}
