<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LangController extends Controller
{
    public function index($lang = 'en') {
        $json = [
            "test" => 111,
            "lang" => App::getLocale()
        ];
        $code = 200;

        return response()->json($json, $code);
    }

    public function lang() {
        $json = [
            "test" => 555
        ];
        $code = 200;

        return response()->json($json, $code);
    }
}
