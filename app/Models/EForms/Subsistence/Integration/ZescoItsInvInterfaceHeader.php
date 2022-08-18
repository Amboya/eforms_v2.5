<?php

namespace App\Models\EForms\Subsistence\Integration;

use App\Models\EForms\Subsistence\SubsistenceModel;
use App\Models\Main\StatusModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZescoItsInvInterfaceHeader extends Model
{
    use HasFactory;
    use HasFactory;
    public $timestamps = false;
    //test
    //production
    protected $connection = 'oracle_isd_prod';
    protected $table = 'fms_invoice_interface_header';

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
        return $this->belongsTo(SubsistenceModel::class, 'invoice_id', 'code');
    }

}
