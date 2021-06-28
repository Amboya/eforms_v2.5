<?php

namespace App\Models\main;

use App\Models\User;
use App\Models\Main\EFormModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TotalsModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table  = 'config_totals';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'name',
        'value',
        'eform_id',
        'color',
        'icon',
        'url',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ] ;


    protected $with = [
//        'eform',
    ];


    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function eform(){
        return $this->belongsTo(EFormModel::class, 'eform_id', 'id');
    }

}
