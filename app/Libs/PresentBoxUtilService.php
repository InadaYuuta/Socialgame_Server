<?php

namespace App\Libs;

use App\Models\PresentBoxInstance;

use Carbon\Carbon;

class PresentBoxUtilService
{
    /*現在受取可能かつ表示期間内のプレゼントボックスインスタンスのデータを返す */
    public static function GetCanReceiptPresentBoxData($manage_id)
    {
        $can_receipt_present_data = [];
        $check_present_data = PresentBoxInstance::where('manage_id',$manage_id)->get();
        $current_date = Carbon::now()->format('Y-m-d H:i:s'); // 現在の日時
        foreach($check_present_data as $data)
        {
           if($data->receipt != 1 && $data->display > $current_date)
            {
                array_push($can_receipt_present_data,$data); 
            }
        }
        return $can_receipt_present_data;
    }
}