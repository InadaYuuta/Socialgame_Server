<?php

namespace App\Libs;

use Illuminate\Support\Facades\Auth;

class ErrorUtilService
{
    // TODO: 現状ここでreturn json_encodeするとただjson変換されたコードが返るだけで、呼び出し元のコードから出れないから余裕があるときに出れるようにするコードを書く
    /**
     * $errcodeの中に入ったエラーコードをクライアントに返す
     */
    public static function returnErrorCode($errcode)
    {
        $response = [
            'errcode' => $errcode,
        ];
        return json_encode($response);
    }

    /**
     * ユーザーのログインチェック
     */
    public static function checkLoginUser($manage_id)
    {
        // ユーザーがログインしていなかったらリダイレクト
        if (!Auth::hasUser()) {
            ErrorUtilService::returnErrorCode('constants.ERRCODE_LOGIN_USER_NOT_FOUND');
        }

        $authUserData = Auth::user();
        // ログインしているユーザーが自分と違ったらリダイレクト
        if ($manage_id != $authUserData->manage_id) {
            ErrorUtilService::returnErrorCode('constants.ERRCODE_LOGIN_SESSION');
        }
    }
}
