<?php

namespace App\Models\Main;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatingUnits extends Model
{
    use HasFactory;

    //table name
    protected $table  = 'operating_units';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'org_id',
        'ou_desc',
        'bu_code',
        'bu_desc',
        'created_by',
        'created_at',
        'updated_at',
    ] ;

    public function user_unit(){
        return $this->belongsTo(ConfigWorkFlow::class, 'bu_code', 'user_unit_bc_code');
    }

}
