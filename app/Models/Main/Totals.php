<?php

namespace App\Models\Main;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Totals extends Model
{
    use HasFactory;

    //table name
    protected $table  = 'totals_tbl';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'eform_id',
        'eform_code',

        'column_one',
        'column_one_value',

        'column_two',
        'column_two_value',

        'total_one',
        'total_one_value',

        'total_two',
        'total_two_value',

        'created_at',
        'updated_at',
    ] ;


    protected $with = [
        'myDirectorate',
    ];


    public function myDirectorate()
    {
        return $this->belongsTo(DirectoratesModel::class, 'column_one_value', 'id',);
    }


}
