<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLoginRequest;
use App\Http\Requests\ApiRegistrationRequest;
use \Illuminate\Http\JsonResponse;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login', 'refresh', 'registration']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     *  @param  ApiLoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(ApiLoginRequest $request): JsonResponse
    {
        $credentials = $request->all();
        return $this->loginProcess($credentials);
    }

    /**
     * Registration user and Get a JWT via given credentials.
     *
     *  @param  ApiRegistrationRequest $request
     *
     * @return JsonResponse
     */
    public function registration(ApiRegistrationRequest $request): JsonResponse
    {
        $credentials = $request->all();

        try {
            User::create([
                'name' => $credentials['name'],
                'email' => $credentials['email'],
                'password' => bcrypt($credentials['password'])
            ]);
        } catch(\Exception $error) {
            return $this->respondUnauthorized(__('auth.registration_error'));
        }

        return $this->loginProcess($credentials);
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
    private function respondWithToken(string $token): JsonResponse
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

    /**
     * Response with 401 Unauthorized
     *
     * @param  string $message
     *
     * @return JsonResponse
     */
    private function respondUnauthorized($message): JsonResponse
    {
        return response()->json([
            'success'   => false,
            'message'   => $message
        ], 401);
    }

    /**
     * Method login user
     *
     * @param  array $credentials
     *
     * @return JsonResponse
     */
    private function loginProcess(array $credentials): JsonResponse {
        if (! $token = auth()->attempt($credentials)) {
            return $this->respondUnauthorized(__('auth.login_error'));
        }

        return $this->respondWithToken($token);
    }
}
