<?php

namespace App\Models\Main;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EFormModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table  = 'config_eform';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'name',
        'code',
        'icon',
        'description',
        'test_url',
        'production_url',
        'category_id',
        'status_id',
        'total_new',
        'total_pending',
        'total_closed',
        'total_rejected',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ] ;

    protected $with = [
        'category',
      //  'user',
        'status',
    ];


    public function category(){
        return $this->belongsTo(EFormCategoryModel::class, 'category_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function status(){
        return $this->belongsTo(StatusModel::class, 'status_id', 'id');
    }
}
