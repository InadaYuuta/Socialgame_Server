<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class PresentBoxInstance extends Model
{
    use HasFactory;

    protected $table = 'present_box_instances';
    protected $primarykey = ['manage_id','present_id'];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

   // 変更を許可しないカラムのリスト
    protected $guarded = [
        'created',
    ];

     // マスタデータ取得
     public static function GetPresentBoxInstance()
     {
         $present_box_data_list = MasterDataService::GetMasterData('presentBoxInstance');
         return $present_box_data_list;
     }
}
