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
     * @OA\Post(
     * path="/api/auth/registration",
     *   tags={"Auth"},
     *   summary="Registration",
     *   description="Registration users. If registration will be successfully you get Bearer Token",
     *   operationId="registration",
     *
     *   @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *  ),
     * )
     * @param ApiRegistrationRequest $request
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
     * @OA\Post(
     * path="/api/auth/login",
     *   tags={"Auth"},
     *   summary="Authorization",
     *   description="Authorization users to get Bearer Token",
     *   operationId="login",
     *
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *  ),
     * )
     * @param ApiLoginRequest $request
     * @return JsonResponse
     */
    public function login(ApiLoginRequest $request): JsonResponse
    {
        $credentials = $request->all();
        return $this->loginProcess($credentials);
    }

    /**
     * @OA\Post(
     * path="/api/auth/me",
     *   tags={"Auth"},
     *   summary="Get authorized user details",
     *   description="Get current authorized user details",
     *   operationId="me",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *  ),
     * )
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
     * @OA\Post(
     * path="/api/auth/logout",
     *   tags={"Auth"},
     *   summary="Logout",
     *   description="Logout user",
     *   operationId="logout",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *  ),
     * )
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json([
            'success'   => true,
            'message'   => __('auth.logout_success')
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
