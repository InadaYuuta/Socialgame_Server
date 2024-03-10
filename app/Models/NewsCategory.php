<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    use HasFactory;

    protected $table = 'news_categories';
    protected $primarykey = 'news_category';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
    ];
}
