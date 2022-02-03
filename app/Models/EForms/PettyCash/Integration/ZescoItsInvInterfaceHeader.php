<?php

namespace App\Models\EForms\PettyCash\Integration;

use App\Models\EForms\PettyCash\PettyCashModel;
use App\Models\Main\StatusModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZescoItsInvInterfaceHeader extends Model
{
    use HasFactory;

    //table name
    protected $table = 'fms_invoice_interface_header';
//    protected $table = 'zesco_its_inv_interface_header';
    //fields fillable
    protected $fillable = [
        'invoice_id',
        'transaction_type',
        'invoice_num',
        'invoice_date',
        'invoice_description',
        'invoice_type',
        'supplier_num',
        'invoice_amount',
        'invoice_currency_code',
        'exchange_rate',
        'gl_date',
        'org_id',
        'company_code',
        'creation_date',
        'process_yn',
        'process_date',
        'error_msg'
    ];


    protected $with = [
        'status',
    ];

    //RELATIONSHIP
    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'process_yn', 'other');
    }

    //RELATIONSHIP
    public function code()
    {
        return $this->belongsTo(PettyCashModel::class, 'invoice_id', 'code');
    }

}
