<?php

namespace App\Models\Main;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttachedFileModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    //table name
    protected $table  = 'ATTACHED_FILES';
    //primary key
    protected $primaryKey = 'id';
    //fields fillable
    protected $fillable = [
        'name',
        'extension',
        'file_type',
        'file_size',
        'location',
        'form_type',
        'form_id',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ] ;

}
