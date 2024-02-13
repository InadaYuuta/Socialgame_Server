<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemInstance extends Model
{
    use HasFactory;

    protected $table = 'items_instances';
    protected $primarykey = 'manage_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
        '',
    ];
}
