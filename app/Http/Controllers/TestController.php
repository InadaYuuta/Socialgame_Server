<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    public function __invoke(){
        $file = "/home/inada/socialgame_server/app/Cron/test.txt";
        $text = "こんにちは"."\n";
        return file_put_contents($file,$text,FILE_APPEND); // 一旦テストで書き込み
    }
}
