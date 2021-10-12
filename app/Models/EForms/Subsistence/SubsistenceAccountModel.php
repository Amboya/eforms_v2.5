<?php

namespace App\Models\EForms\Subsistence;

use App\Http\Controllers\Main\HomeController;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\StatusModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SubsistenceAccountModel extends Model
{
    use HasFactory;
    use SoftDeletes ;

    //table name
    protected $table = 'eform_subsistence_account';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'creditted_account_id',
        'creditted_amount',
        'debitted_account_id',
        'debitted_amount',
        'eform_subsistence_id',
        'subsistence_code',

        'cost_center' ,
        'business_unit_code' ,
        'user_unit_code' ,

        'status_id',
        'account',
        'company',
        'intra_company',
        'project',
        'pems_project',
        'spare',
        'description',

        'claimant_staff_no',
        'claimant_name',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $with = [
        'status',
    ];

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'status_id', 'id');
    }

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

}
