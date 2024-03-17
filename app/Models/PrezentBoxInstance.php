<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrezentBoxInstance extends Model
{
    use HasFactory;

    protected $table = 'prezent_box_instances';
    protected $primarykey = ['manage_id','prezent_id'];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

   // 変更を許可しないカラムのリスト
    protected $guarded = [
        'created',
    ];

     // マスタデータ取得
     public static function GetPrezentBoxInstance()
     {
         $prezent_box_data_list = MasterDataService::GetMasterData('prezentBoxInstance');
         return $prezent_box_data_list;
     }
}
