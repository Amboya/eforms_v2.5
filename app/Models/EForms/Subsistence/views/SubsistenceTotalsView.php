<?php

namespace App\Models\EForms\PettyCash\Views;

use App\Http\Controllers\Main\HomeController;
use App\Models\EForms\PettyCash\PettyCashItemModel;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\DirectoratesModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\ProjectsModel;
use App\Models\Main\StatusModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SubsistenceTotalsView extends Model
{
    use HasFactory;

    //table name
    protected $table = 'eform_petty_cash_dashboard_all_totals_view';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'cost_center',
        'business_unit_code',
        'user_unit_code',
        'directorate_id',
        'total',
        'amount',
        'claimant_staff_no',
        'config_status_id',

    ];
    protected $with = [
        'user',
        'status',
    ];

    protected static function booted()
    {
        //check if authenticated user
        if (auth()->check()) {
            $user = Auth::user();
            if ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
                //if you are just a requester, then only see your forms
                static::addGlobalScope('staff_number', function (Builder $builder) {
                    $builder->where('claimant_staff_no', Auth::user()->staff_no);
                });
            } else {
                static::addGlobalScope('hod', function (Builder $builder) {
                    $fdsf = HomeController::getMyProfile(config('constants.eforms_id.subsistence'));
                    $mine = $fdsf->pluck('user_unit_code')->toArray();
                    $builder->WhereIn('user_unit_code', $mine);
                });

            }
        }
    }


    //RELATIONSHIP

    public function user_unit()
    {
        return $this->belongsTo(ConfigWorkFlow::class, 'user_unit_code', 'user_unit_code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function directorate()
    {
        return $this->belongsTo(DirectoratesModel::class, 'directorate_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'config_status_id', 'id');
    }


}
