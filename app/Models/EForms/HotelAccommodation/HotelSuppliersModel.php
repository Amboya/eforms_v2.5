<?php

namespace App\Models\EForms\HotelAccommodation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelSuppliersModel extends Model
{
    use HasFactory;

    //table name
    protected $table = 'suppliers';
    //primary key
    protected $primaryKey = 'code_supplier';
    //fields fillable
    protected $fillable = [
        'user_act',
        'date_act',
        'code_supplier',
        'name_supplier',
        'type_supplier',
        'comments',
        'status',
        'status_user',
        'status_date',
        'payment_method',
        'payment_period',
        'code_currency',
        'minimum_order_value',
        'purchase_specifications',
        'fiscal_address',
        'pin_no',
        'vat_reg_no',
        'tax_code',
        'bank_account',
        'bank_name',
        'bank_branch',
        'bank_country',
        'street',
        'street_no',
        'building',
        'floor',
        'city_town',
        'province',
        'state',
        'region',
        'country',
        'po_box',
        'postal_code',
        'city_town_postal',
        'country_postal',
        'telephone',
        'fax',
        'e_mail',
        'contact_person',
        'telephone_contact',
        'cell_contact',
        'fax_contact',
        'e_mail_contact',
        'code_supplier_finance',
        'payment_period_unit',
        'min_order_value_currency',
        'area',
        'suppliers_account',
        'creditors_account',
        'finance_supplier_code',
        'status_finance',
        'bank_swift_co',
        'bank_sort_co',
        'pacra',
    ];
}
