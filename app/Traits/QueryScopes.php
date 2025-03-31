<?php

namespace App\Traits;

trait QueryScopes{

    public function scopeKeyword($query, $keyword, $fieldsSearch = [], $whereHas = []){
        if(!empty($keyword)){
            if(isset($fieldsSearch) && count($fieldsSearch)){
                foreach($fieldsSearch as $field){
                    $query->orWhere($field, 'LIKE', '%' . $keyword . '%');
                }
            }else{
                $query->where('name', 'LIKE', '%' . $keyword . '%');
                // Kiểm tra nếu bảng có cột 'canonical'
                if (\Schema::hasColumn($this->getTable(), 'canonical')) {
                    $query->orWhere('canonical', 'LIKE', '%' . $keyword . '%');
                }
            }
        }
        if(isset($whereHas) && count($whereHas)){
            $field = $whereHas['field'];
            $query->orWhereHas($whereHas['relation'], function($q) use ($field, $keyword){
                $q->where($field, 'LIKE', '%' . $keyword . '%');
            });
        }
        return $query;
    }

    public function scopePublish($query, $publish){
        if(!empty($publish)){
            $query->where('publish', '=', $publish);
        }
        return $query;
    }

    public function scopeCustomDropdownFilter($query, $dropdownCondition = []){
        if(!empty($dropdownCondition)){
            foreach($dropdownCondition as $key => $val){
                if($val != 0){
                    $query->where($key, '=', $val);
                }
            }
        }
        return $query;
    }

    public function scopeCustomCreatedAt($query, $createdAt = []){
        if(!is_null($createdAt)){
            $createdAt = explode(' - ', $createdAt);
            // dd($createdAt);
            if(count($createdAt) == 1){
                $createdAt[1] = $createdAt[0];
            }
            $startDate = convertDateTime($createdAt[0], 'Y-m-d H:i', 'd/m/Y H:i');
            $endDate = convertDateTime($createdAt[1], 'Y-m-d H:i', 'd/m/Y H:i');
            // dd($startDate, $endDate);
            $query->where('created_at', '>=', $startDate);
            $query->where('created_at', '<=', $endDate);
        }
        return $query;
    }

    public function scopeCustomWhere($query, $where = []){
        if(count($where)){
            foreach($where as $key => $val){
                $query->where($val[0], $val[1], $val[2]);
            }
        }
        return $query;
    }

    public function scopeCustomWhereRaw($query, $rawQuery = []){
        if(!empty($rawQuery)){
            foreach($rawQuery as $key => $val){
                $query->whereRaw($val[0], $val[1]);
            }
        }
        return $query;
    }

    public function scopeRelationCount($query, $relation){
        if(!empty($relation)){
            foreach($relation as $item){
                $query->withCount($item);
            }
        }
        return $query;
    }

    public function scopeRelation($query, $relation){
        if(!empty($relation)){
            foreach($relation as $item){
                $query->with($item);
            }
        }
        return $query;
    }

    public function scopeCustomJoin($query, $join){
        if(!empty($join)){
            foreach($join as $key =>$val){
                $query->join($val[0],$val[1],$val[2],$val[3]);
            }
        }
        return $query;
    }

    public function scopeCustomGroupBy($query, $groupBy){
        if(!empty($groupBy)){
            $query->groupBy($groupBy);
        }
        return $query;
    }

    public function scopeCustomOrderBy($query, $orderBy){
        if(isset($orderBy)&&!empty($orderBy)){
            $query->orderBy($orderBy[0], $orderBy[1]);
        }
        return $query;
    }
}