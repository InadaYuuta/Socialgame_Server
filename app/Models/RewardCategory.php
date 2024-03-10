<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardCategory extends Model
{
    use HasFactory;
    protected $table = 'reward_categories';
    protected $primarykey = 'reward_category';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
    ];
}
