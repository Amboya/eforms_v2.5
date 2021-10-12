<?php

namespace App\Models;

use App\Models\Main\GradesCategoryModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhrisUserDetailsModel extends Model
{
    use HasFactory;

    //table name
    protected $table  = 'ipa_phris_view';
    //primary key
    protected $primaryKey = 'con_per_no';
    //fields fillable
    protected $fillable = [
        'contract_type',
        'con_st_code',
        'con_wef_date',
        'con_wet_date',
        'name',
        'nrc',
        'sex',
        'mobile_no',
        'group_type',
        'jb_title',
        'grade',
        'functional_section',
        'directorate',
        'location',
        'pay_point',
        'bu_code',
        'cc_code',
        'staff_email',
        'job_code',
        'station',
        'affiliated_union',
    ] ;

//    protected $with = [
//        'grade_category',
//    ];
//    public function grade_category(){
//        return $this->belongsTo(GradesCategoryModel::class, 'grade', 'id');
//    }

}
