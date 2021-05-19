<?php

namespace App\Models\LoginAPI;

use App\Models\Main\StatusModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientSystem extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table  = 'api_client_system';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'name',
        'url',
        'ip_address',
        'access_key',
        'status_id',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ] ;


    protected $with = [
        'status',
    ];

    public function status()
    {
        return $this->belongsTo(StatusModel::class, 'status_id', 'id');
    }

}
