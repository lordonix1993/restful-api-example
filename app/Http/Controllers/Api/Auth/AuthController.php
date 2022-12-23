<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLoginRequest;
use \Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login', 'refresh']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(ApiLoginRequest $request): JsonResponse
    {
        $credentials = $request->all();

        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'success'   => false,
                'message'   => __('auth.login_error')
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json([
            'success'   => true,
            'message'   => __('auth.user_data'),
            'data'      => auth()->user()
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json([
            'success'   => true,
            'message'   => __('auth.logout')
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    private function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'success'   => true,
            'message'   => __('auth.generated_token'),
            'data'      => [
                'access_token'  => $token,
                'token_type'    => 'bearer',
                'expires_in'    => auth()->factory()->getTTL() * 60
            ]
        ]);
    }
}
