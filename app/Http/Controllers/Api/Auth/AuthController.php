<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private bool $loginProcessMethod = false;
    private string $token = '';

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
    /**
     * @OA\Post(
     *   path="/api/auth/registration",
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
     * @param RegistrationRequest $request
     * @return JsonResponse
     */
    public function registration(RegistrationRequest $request): JsonResponse
    {
        $credentials = $request->all();

        try {
            User::create([
                'name' => $credentials['name'],
                'email' => $credentials['email'],
                'password' => bcrypt($credentials['password'])
            ]);
        } catch(\Exception $error) {
            return $this->responseJSON(__('auth.response.422.register', 422));
        }

        return $this->loginProcess($credentials);
    }


    /**
     * @OA\Post(
     *   path="/api/auth/login",
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
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $this->loginProcessMethod = true;
        $credentials = $request->all();
        return $this->loginProcess($credentials);
    }

    /**
     * @OA\Post(
     *   path="/api/auth/me",
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
        $user_data = auth()->user();
        return $this->responseJSON(
            __('auth.response.200.me'),
            200,
            $user_data != null ? $user_data->toArray() : []
        );
    }

    /**
     * @OA\Post(
     *   path="/api/auth/logout",
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
        return $this->responseJSON(__('auth.response.200.logout'));
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $this->token = auth()->refresh();
        if(!empty($this->token)) {
            return $this->responseJSON(
                __('response.200.refresh_token'),
                200,
                $this->getTokenData()
            );
        }
        return $this->responseJSON(__('response.422.token'), 422);
    }

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
    private function responseJSON(string $message, int $code = 200, array $data = [], string | array $error = ''): JsonResponse
    {
        $json = [
            'success'   => $code == 200 ? true : false,
            'message'   => $message,
            'error'     => $error,
            'data'      => []
        ];
        if(!empty($data)) $json['data'] = $data;
        return response()->json($json, $code);
    }

    /**
     * Method login user
     *
     * @param  array $credentials
     *
     * @return JsonResponse
     */
    private function loginProcess(array $credentials): JsonResponse {
        if (! $this->token = auth()->attempt($credentials)) {
            return $this->responseJSON(__('auth.response.401', 401));
        }
        return $this->responseJSON(
            $this->loginProcessMethod ? __('auth.response.200.login') : __('auth.response.200.register'),
            200,
            $this->getTokenData()
        );
    }

    private function getTokenData() {
        return [
            'access_token'  => $this->token,
            'token_type'    => 'bearer',
            'expires_in'    => auth()->factory()->getTTL() * 60
        ];
    }
}
