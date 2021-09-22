<?php

namespace App\Models\EForms\Trip;

use App\Http\Controllers\EForms\Trip\HomeController;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\StatusModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;


class Trip extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'eform_trip';

    //table name
    protected $primaryKey = 'id';
    //primary key
    protected $fillable = [
        'date_from',
        'date_to',
        'hod_code',
        'hod_unit',

        'code',
        'name',
        'description',
        'destination',
        'config_status_id',
        'invited',

        'initiator_name',
        'initiator_staff_no',
        'initiator_date',

        'closed_by_name',
        'closed_by_staff_no',
        'closed_by_date',

        'created_by',
        'deleted_at',
    ];

    //fields fillable
    protected $with = [
        'status',
    ];


    //RELATIONSHIPS

    protected static function booted()
    {
      $best = HomeController::getMyProfile();
    }

    public function getNumdaysAttribute()
    {
        return $this->date_arrived->diffInDays($this->date_left);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'config_status_id', 'id');
    }

    public function user_unit()
    {
        return $this->belongsTo(ConfigWorkFlow::class, 'user_unit_code', 'user_unit_code');
    }

    public function members()
    {
        return $this->hasMany(TripMembers::class, 'trip_id', 'id');
    }


}


