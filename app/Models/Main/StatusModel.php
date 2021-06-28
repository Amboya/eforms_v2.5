<?php

namespace App\Models\Main;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table = 'config_status';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'name',
        'other',
        'html',
        'percentage',
        'description',

        'status',
        'status_next',
        'status_failed',

        'eform_id',
        'eform_code',

        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',

        'previous_status_id',
        'next_status_id',
        'failed_status_id',
    ];

    protected $with = [
        // 'user',
       // 'eform',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id',);
    }

    public function eform()
    {
        return $this->belongsTo(EFormModel::class, 'eform_id', 'id',);
    }
}
