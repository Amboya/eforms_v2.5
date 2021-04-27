<?php

namespace App\Models\EForms\PettyCash;

use App\Models\User;
use App\Models\EForms\PettyCash\PettyCashModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PettyCashItemModel extends Model
{
    use HasFactory;
      use SoftDeletes;

    //table name
    protected $table  = 'eform_petty_cash_item';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'name',
        'amount',
        'eform_petty_cash_id',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ] ;




}
