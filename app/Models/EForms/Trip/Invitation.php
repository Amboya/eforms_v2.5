<?php

namespace App\Models\EForms\Trip;

use App\Models\Main\EformApprovalsModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class Invitation extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table = 'eform_trip_invitation';
    //primary key
    protected $primaryKey = 'id';

    //fields fillable
    protected $fillable = [
        'man_no',
        'user_unit',
        'trip_id',
        'trip_code',
        'subsistence_code',
        'subsistence_id',
        'date_from',
        'date_to',
        'status_id',
        'deleted_at',
    ];

//    protected $with = [
//        'trips',
//    ];

    public function trips()
    {
        return $this->belongsTo(Trip::class, 'trip_code', 'code');
    }
    public function members()
    {
        return $this->hasOne(User::class, 'staff_no', 'man_no');
    }


}

