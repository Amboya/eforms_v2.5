<?php

namespace App\Models\EForms\Trip;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\EformApprovalsModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\StatusModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;


class TripMembers extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $appends = ['numdays', 'total'];

    protected $dates = [
        'date_arrived', 'date_left'
    ];

    protected $casts = [
        'date_arrived' => 'date:Y-m-d',
        'date_left' => 'date:Y-m-d',
    ];

    public function getNumdaysAttribute()
    {
        return $this->date_arrived->diffInDays($this->date_left);
    }

    //table name
    protected $table = 'eform_trip_members';
    //primary key
    protected $primaryKey = 'id';

    //fields fillable
    protected $fillable = [
            'user_id',
            'man_no',
            'name',
            'user_unit_code',
            'trip_code',
            'trip_id',
            'subsistence_code',
            'subsistence_id',
            'destination',

            'days_claimed',
            'm_v_number',
            'date_arrived',
            'date_left',
            'config_status_id',

            'approved_by_name',
            'approved_by_staff_no',
            'approved_by_date',

            'hrm_name',
            'hrm_staff_no',
            'hrm_date',

            'authorised_by_name',
            'authorised_by_staff_no',
            'authorised_by_date',

            'created_by',
            'deleted_at',
    ];


    //RELATIONSHIPS

    protected $with = [
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'config_status_id', 'id');
    }


    protected static function booted()
    {

    }


}

