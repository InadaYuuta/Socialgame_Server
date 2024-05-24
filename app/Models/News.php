<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';
    protected $primarykey = 'news_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    // 変更を許可しないカラムのリスト
    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetNews()
    {
        $news_data_list = MasterDataService::GetMasterData('news');
        return $news_data_list;
    }
}
