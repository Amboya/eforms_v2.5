<?php

namespace App\Models\EForms\Subsistence\Integration;

use App\Models\EForms\Subsistence\SubsistenceModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZescoItsInvInterfaceDetail extends Model
{
    use HasFactory;

    //table name
    protected $table = 'fms_invoice_interface_detail';
  //  protected $table = 'zesco_its_inv_interface_detail';

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
        'line_type',
        'creation_date'
    ];



    //RELATIONSHIP
    public function code()
    {
        return $this->belongsTo(SubsistenceModel::class, 'invoice_id', 'code');
    }

}
