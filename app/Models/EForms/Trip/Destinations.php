<?php

namespace App\Models\EForms\Trip;

use App\Models\Main\EformApprovalsModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class Destinations extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table = 'eform_trip_destinations';
    //primary key
    protected $primaryKey = 'id';

    //fields fillable
    protected $fillable = [
        'user_unit_code',
        'trip_code',
        'date_from',
        'date_to',
        'deleted_at',
    ];

    protected $with = [
        'trips',
    ];

    public function trips()
    {
        return $this->belongsTo(Trip::class, 'trip_code', 'code');
    }


}

