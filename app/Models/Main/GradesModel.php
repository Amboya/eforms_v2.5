<?php

namespace App\Models\Main;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradesModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table  = 'config_grades';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'name',
        'category_id',
        'sub_rate',
        'kilometer_allowance_rate',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ] ;
    protected $with = [
        'user',
        'category'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function category(){
        return $this->belongsTo(GradesCategoryModel::class, 'category_id', 'id');
    }

}
