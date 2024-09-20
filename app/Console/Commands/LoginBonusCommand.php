<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\TestController;

class LoginBonusCommand extends Command
{
    protected $signature = 'command:login_bonus'; // コマンド名

    protected $description = 'login bonus send'; // コマンド説明

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        TestController::class; // 実行する処理
    }

}
