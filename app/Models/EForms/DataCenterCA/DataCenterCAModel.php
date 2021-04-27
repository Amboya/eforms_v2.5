<?php

namespace App\Models\EForms\DataCenterCA;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\main\StatusModel;
use Illuminate\Support\Facades\Auth;
use App\Models\main\ProfileAssigmentModel;

class DataCenterCAModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table = 'eform_datacenterca';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [

        'asset_name',
        'code',
        'asset_category',
        'rack_location',
        'criticality',
        'physical_location',
        'status_id',
        'profile',

        'staff_name',
        'staff_no',
        'submitted_date',

        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $with = [
        'user',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'status_id', 'id');
    }


    protected static function booted()
    {
        //check if authenticated user
        if (auth()->check()) {
            //get the profile for this user
            $user = Auth::user();
            $profile_assignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.datacenter_ca'))
                ->where('user_id', $user->id)->first();

            $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
            $user->profile_id = $default_profile;
            $user->save();

            //[1] REQUESTER
            //if you are just a requester, then only see your forms
            if ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
                static::addGlobalScope('staff_no', function (Builder $builder) {
                    $builder->where('staff_no', Auth::user()->staff_no);
                });
            } else {
                //[2A] HOD
                //see forms for the same work area and user unit
                if ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {

                    //  dd(Auth::user()->user_unit->code) ;
                    static::addGlobalScope('user_unit_id', function (Builder $builder) {
                        $builder->where('user_unit', Auth::user()->user_unit->code);
                    });
                }

            }

        }

    }


}
