<?php

namespace App\Models\Main;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegionsModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table  = 'config_regions';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'name',
        'code',
        'division_id',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ] ;

    protected $with = [
        'user',
        'division',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function division(){
        return $this->belongsTo(DivisionsModel::class, 'division_id', 'id');
    }
}
