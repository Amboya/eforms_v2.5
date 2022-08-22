<?php

namespace App\Models\Main\Sync;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganogramSync extends Model
{

    use HasFactory;

    //table name
    protected $table  = 'organogram_mapping_view';

    //fields fillable
    protected $fillable = [
        'bu_description',
        'bu_code',
        'cc_description',
        'cc_code',
        'level_1',
        'level_2',
        'level_3',
        'level_4',
    ] ;


}
