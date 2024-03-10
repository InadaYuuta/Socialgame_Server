<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrezentBoxInstance extends Model
{
    use HasFactory;

    protected $table = 'prezent_box_instances';
    protected $primarykey = ['manage_id','prezent_id'];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
    ];
}
