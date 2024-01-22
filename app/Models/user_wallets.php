<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_wallets extends Model
{
    use HasFactory;

    protected $table = 'user_wallets';
    protected $primarykey = 'manage_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
        'manage_id',
    ];
}
