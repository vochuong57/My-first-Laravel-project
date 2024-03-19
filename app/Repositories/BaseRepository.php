<?php
// Đối với các class, interface và services, repositories, quan trọng nhất là cấu trúc namespace, autoloading, và cách bạn đăng ký chúng trong service provider.
namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
//chèn thêm thư viện để lấy thông tin Base từ DB
use App\Models\Base;
//chèn thêm thư viện có sẵn
use illuminate\Database\Eloquent\Model;

/**
 * Class BaseService
 * @package App\Services
 */
class BaseRepository implements BaseRepositoryInterface
{
    protected $model;
    public function __construct(Model $model){
        $this->model=$model;
    }
    public function all(){
        return $this->model->all();
    }
    public function pagination(array $column=['*'],array $condition=[],array $join=[],array $extend=[],int $perpage=0){
        $query=$this->model->select($column)->where(function($query) use($condition){
            if(isset($condition['keyword']) && !empty($condition['keyword'])){
                $query->where('name', 'LIKE', '%'.$condition['keyword'].'%');
            }
        });
        if(!empty($join)){
            $query->join(...$join);
        }
        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL').$extend['path']);
    }
    public function findById(int $id, array $column=['*'], array $relation =[]){
        return $this->model->select($column)->with($relation)->findOrFail($id);
    }
    public function findTableById(int $id = 0){
        return $this->model->where('id','=',$id)->get();
    }
    //phương thức thêm (CREATE)
    public function create(array $payload =[]){
        $model= $this->model->create($payload);
        return $model->fresh();
    }
    //Phương thức cập nhật (UPDATE)
    public function update(int $id=0, array $payload=[]){
        $model=$this->findById($id);
        return $model->update($payload);
    }
    //Phương thức cập nhật WHERE IN (UPDATE)
    public function updateByWhereIn(string $whereInField='', array $whereIn=[], array $payload=[]){
        return $this->model->whereIn($whereInField, $whereIn)->update($payload);
    }
    //Phương thức xóa mềm (DELETE) 
    public function delete(int $id=0){
        return $this->findById($id)->delete();
    }
    //Phương thức xóa cứng (DELETE)
    public function forceDelete(int $id=0){
        return $this->findById($id)->forceDelete();
    } 
}
