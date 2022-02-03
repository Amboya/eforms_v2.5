<?php

namespace App\Models\Main;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxModel extends Model
{
    use HasFactory;

    //table name
    protected $table  = 'config_tax';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'name',
        'tax',
        'business_unit',
        'cost_center',
        'account_code',
        'created_at',
        'updated_at',
        'deleted_at',
    ] ;

}
