<?php

namespace App\Models\EForms\PettyCash;

use App\Models\Main\ConfigWorkFlow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PettyCashFloat extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table = 'eform_petty_cash_float';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'user_unit_id',
        'user_unit_code',
        'float',
        'utilised',
        'cash',
        'percentage',
        'created_by',
        'created_by_name',
    ];


    protected $with = [
        'user_unit',
    ];

    //RELATIONSHIP
    public function user_unit(){
        return $this->belongsTo(ConfigWorkFlow::class, 'user_unit_id', 'id');
    }

}
