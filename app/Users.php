<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Users extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $primarykey = 'manage_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    // protected $fillable = [
    //     'user_id',
    //     'user_name',
    //     'handover_passhash',
    //     'has_weapon_exp_point',
    //     'user_rank',
    //     'login_days',
    //     'max_stamina',
    //     'last_stamina',
    // ];
    
    protected $guarded = [
        'manage_id',
    ];
}