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
    public function all(array $relation = []){
        return $this->model->with($relation)->get();
    }
    public function pagination(
        array $column=['*'],
        array $condition=[],
        int $perpage=0, 
        array $extend=[],
        array $orderBy=['id', 'DESC'],
        array $join=[],
        array $relations=[],
        array $rawQuery = []
        ){
        $query=$this->model->select($column)->where(function($query) use($condition){
            if(isset($condition['keyword']) && !empty($condition['keyword'])){
                $query->where('name', 'LIKE', '%'.$condition['keyword'].'%');
            }
        });
        if(isset($rawQuery['whereRaw']) && count($rawQuery['whereRaw'])){
            foreach($rawQuery['whereRaw'] as $key => $val){
                $query->whereRaw($val[0], $val[1]);
            }
        }
        if(isset($relations)&&!empty($relations)){
            foreach($relations as $relation){
                $query->withCount($relation);
            }
        }
        if(isset($join)&&is_array($join)&&count($join)){
            foreach($join as $key =>$val){
                $query->join($val[0],$val[1],$val[2],$val[3]);
            }
        }
        if(isset($extend['groupBy']) && !empty($extend['groupBy'])){
            $query->groupBy($extend['groupBy']);
        }
        if(isset($orderBy)&&!empty($orderBy)){
            $query->orderBy($orderBy[0], $orderBy[1]);
        }
        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL').$extend['path']);
    }
    public function findById(int $id, array $column=['*'], array $relation =[]){
        return $this->model->select($column)->with($relation)->findOrFail($id);
    }
    public function findWhereIn(string $column='', array $ids = []){//dùng khi có một mảng id
        return $this->model->whereIn($column, $ids)->get();
    }
    public function findByCondition(array $condition = []){
        $query = $this->model->newQuery();
        foreach($condition as $key => $val){
            $query->where($val[0], $val[1], $val[2]);
        }
        return $query->first();
    }
    public function findByConditions(array $condition = []){
        $query = $this->model->newQuery();
        foreach($condition as $key => $val){
            $query->where($val[0], $val[1], $val[2]);
        }
        return $query->get();
    }
    //phương thức thêm (CREATE)
    public function create(array $payload =[]){
        $model= $this->model->create($payload);
        return $model->fresh();
    }
    public function createBatch(array $payload = []){
        return $this->model->insert($payload);
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
    // Phương thức cập nhật WHERE (UPDATE) dùng trong cập nhật ngôn ngữ giao diện trnag web
    public function updateByWhere(array $condition=[], array $payload=[]){
        $query = $this->model->newQuery();
        foreach($condition as $key => $val){
            $query->where($val[0], $val[1], $val[2]);
        }
        //echo $query->toSql(); die();
        return $query->update($payload);
    }
    //Phương thức xóa mềm (DELETE) 
    public function delete(int $id=0){
        return $this->findById($id)->delete();
    }
    //Phương thức xóa cứng (FORCEDELETE)
    public function forceDelete(int $id=0){
        return $this->findById($id)->forceDelete();
    }
    //Phương thức xóa WhereIn (DELETE/FORCEDELTE)
    public function deleteByWhereIn(string $whereInField = '', array $whereIn = [], int $languageId = null) {
        $query = $this->model->whereIn($whereInField, $whereIn);
    
        if ($languageId !== null) {
            $query->where('language_id', $languageId);
        }
    
        return $query->forceDelete();
    }
    public function deleteByWhere(array $condition=[]){
        $query = $this->model->newQuery();
        foreach($condition as $key => $val){
            $query->where($val[0], $val[1], $val[2]);
        }
        //echo $query->toSql(); die();
        return $query->forceDelete();
    }
    
    public function createPivot($model, array $payload=[], string $relation=''){
        return $model->{$relation}()->attach($model->id, $payload);
    }

}
