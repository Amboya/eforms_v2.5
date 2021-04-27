<?php

namespace App\Models\main;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table  = 'config_location';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'name',
        'code',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ] ;

    protected $with = [
        'user',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

}
