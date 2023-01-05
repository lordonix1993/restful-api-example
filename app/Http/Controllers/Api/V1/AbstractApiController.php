<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Google Ads App",
 *      description="Google Ads App project",
 *      @OA\Contact(
 *          email="admin@admin.com"
 *      )
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   in="header",
 *   name="Authorization",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT",
 * )
 *
 * @OA\Server(
 *      url="http://test-laravel.loc/api/V1",
 *      description="Google Ads API Server"
 * )
 */


abstract class AbstractApiController extends Controller
{
    /**
     * Response JSON format
     *
     * @param  string $message
     * @param  int $code
     * @param  array $data
     * @param  string | array $error
     *
     * @return JsonResponse
     */
    protected function responseJSON(string $message, int $code = 200, array $data = []): JsonResponse
    {
        $json = [
            'success'   => $code == 200 ? true : false,
            'message'   => $message,
            'data'      => [],
            'version'   => 1
        ];
        if(!empty($data)) $json['data'] = $data;
        return response()->json($json, $code);
    }
}
