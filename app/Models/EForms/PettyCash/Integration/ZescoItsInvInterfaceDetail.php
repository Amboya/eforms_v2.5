<?php

namespace App\Models\EForms\PettyCash\Integration;

use App\Models\EForms\PettyCash\PettyCashModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZescoItsInvInterfaceDetail extends Model
{
    use HasFactory;

    //table name
    protected $table = 'fms_invoice_interface_detail';
    //fields fillable
    protected $fillable = [
        'invoice_id',
        'line_id',
        'line_number',
        'amount',
        'item_description',
        'org_id',
        'company_code',
        'business_unit',
        'cost_centre',
        'gl_account',
        'vat_rate',
        'creation_date'
    ];



    //RELATIONSHIP
    public function code()
    {
        return $this->belongsTo(PettyCashModel::class, 'invoice_id', 'code');
    }

}
