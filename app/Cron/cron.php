<?php

$file = "/home/inada/socialgame_server/app/Cron/test.txt";
$text = "こんにちは"."\n";

file_put_contents($file,$text,FILE_APPEND);