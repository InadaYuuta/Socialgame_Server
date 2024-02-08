<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeItemShop extends Model
{
    use HasFactory;

    protected $table = 'exchange_item_shops';
    protected $primarykey = 'exchange_product_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
        '',
    ];
}
