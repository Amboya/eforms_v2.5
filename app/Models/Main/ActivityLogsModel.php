<?php

namespace App\Models\Main;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLogsModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // table name
    protected $table = 'config_activity_logs';
    // primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [

        'user_id',
        'staff_no',
        'staff_profile',
        'username',
        'user_email',

        'eform_code',
        'eform_id',

        'ip_address',
        'route_url',
        'previous_url',
        'request_method',
        'request_params',

        'action_name',
        'action_type',
        'comment',
        'meta_data',

        'device',
        'device_type',
        'os',
        'os_version',
        'browser',
        'browser_version',

        'iso_code',
        'country',
        'city',
        'state',
        'state_name',
        'postal_code',
        'latitude',
        'longitude',
        'timezone',
        'continent',
        'currency',
        'value',
        'created_at',
        'updated_at',
        'deleted_at'
    ];



}
