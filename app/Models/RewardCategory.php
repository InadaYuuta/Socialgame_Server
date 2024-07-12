<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class RewardCategory extends Model
{
    use HasFactory;
    protected $table = 'reward_categories';
    protected $primarykey = 'reward_category';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    // 変更を許可しないカラムのリスト
    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetRewardCategory()
    {
        $reward_category_data_list = MasterDataService::GetMasterData('reward_category');
        return $reward_category_data_list;
    }
}
