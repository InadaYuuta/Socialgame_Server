<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class WeaponInstance extends Model
{
    use HasFactory;

    protected $table = 'weapon_instances';
    protected $primarykey = 'manage_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
    ];
}
