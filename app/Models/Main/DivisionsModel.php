<?php

namespace App\Models\Main;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DivisionsModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table  = 'config_divisions';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'name',
        'code',
        'directorate_id',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ] ;

    protected $with = [
        'user',
        'directorate',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function directorate(){
        return $this->belongsTo(DirectoratesModel::class, 'directorate_id', 'id');
    }

}
