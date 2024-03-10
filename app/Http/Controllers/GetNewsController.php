<?php

namespace App\Http\Controllers;

use App\Models\News;

use Illuminate\Support\Facades\DB;

class GetNewsController extends Controller
{
    public function __invoke()
    {
        $response = [
            'news'=>News::get(),
        ];

        return json_encode($response);
    }
}
