<?php

namespace App\Models\EForms\Trip;

use App\Models\Main\ConfigWorkFlow;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class DestinationsApprovals extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table = 'eform_trip_dest_approvals';
    protected $appends = ['days'];
    //primary key
    protected $primaryKey = 'id';

    //fields fillable
    protected $fillable = [
        'trip_id',
        'trip_code',
        'subsistence_id',
        'subsistence_code',
        'user_unit_code',
        'created_by',
        'dest_comment',
        'date_from',
        'date_to'
        ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function user_unit()
    {
        return $this->belongsTo(ConfigWorkFlow::class, 'user_unit_code', 'user_unit_code');
    }

    public function getDaysAttribute(){
        if($this->date_to == null){
            return 0 ;
        }
        return ($this->date_to )->diffInDays($this->date_from  );
    }



}

