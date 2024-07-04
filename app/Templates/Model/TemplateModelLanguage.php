<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {ModuleTemplate}Language extends Model
{
    use HasFactory;

    protected $table='{pivotTable}';

    public function {tableNames}(){
        return $this->belongsTo({ModuleTemplate}::class,'{moduleKey}', 'id');
    }
}
