<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeItemCategory extends Model
{
    use HasFactory;

    protected $table = 'exchange_item_categories';
    protected $primarykey = 'exchange_item_category';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
        '',
    ];
}
