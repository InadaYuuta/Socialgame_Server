<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    protected $table = 'devices';
    public $incrementing = false;
    protected $primarykey = 'device_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
    ];
    
    protected $guarded = [
        'device_id',
    ];
}