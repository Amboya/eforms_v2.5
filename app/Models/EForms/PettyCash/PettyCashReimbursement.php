<?php

namespace App\Models\EForms\PettyCash;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PettyCashReimbursement extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table  = 'eform_pt_reimbursement';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
            'user_unit_id',
            'user_unit_code',
            'from',
            'to',
            'amount',
            'reason',
            'name',
            'title',
            'profile',
            'created_by',
            'cash_percentage',
    ] ;



}
