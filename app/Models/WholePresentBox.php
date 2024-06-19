<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class WholePresentBox extends Model
{
    use HasFactory;
    protected $table = 'whole_present_boxes';
    protected $primarykey = 'whole_present_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $casts = [
        'distribution_start'=>'datetime',
        'distribution_end'=>'datetime',
    ];

   // 変更を許可しないカラムのリスト
    protected $guarded = [
        'whole_present_id',
        'created',
    ];

     // マスタデータ取得
     public static function GetPresentBoxMaster()
     {
         $present_box_master_data_list = MasterDataService::GetMasterData('presentBoxMaster');
         return $present_box_master_data_list;
     }
}
