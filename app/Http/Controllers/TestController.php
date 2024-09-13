<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    public function __invoke(){
        $url = "https://yuu-game.com/api/buyCurrency?uid&=01J68TDP1C5GCN40347FGZCWEEpid&=10001"; // ちゃんと接続して通貨が購入されるかチェック
        $result = file_get_contents($url);
        return $result;
    }
}