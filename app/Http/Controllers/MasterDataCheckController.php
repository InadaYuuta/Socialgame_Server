<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\MasterDataService;

class MasterDataCheckController extends Controller
{
    public function __invoke(Request $request)
    {
        // マスターデータチェック
        $client_master_version = $request->mv;
        if(!MasterDataService::CheckMasterDataVersion($client_master_version)) {
            return config('error.ERROR_MASTER_DATA_UPDATE');
        }
        $response = ['message' => '正常終了'];
        
        return json_encode($response);
    }
}
