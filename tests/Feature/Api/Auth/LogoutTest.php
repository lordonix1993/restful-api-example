<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The test checks response where don't set token
     * This request must return 401 code
     *
     * @return void
     */
    public function test_logout_without_token(): void
    {
        $response = $this->post('/api/auth/logout');
        $response->assertStatus(self::HTTP_CODE_UNAUTHORIZED)
            ->assertJson([
                'success' => false,
                'message' => __('auth.response.401.middleware')
            ])
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    /**
     * The test which checks response where get authorized user process is fail when token is wrong
     * This request must return 401 code
     * @return void
     */
    public function test_logout_not_correctly_token(): void
    {
        $response = $this->withHeaders([
            "Authorization" => "Bearer ".Str::random(900)
        ])->post('/api/auth/me');

        $response->assertStatus(self::HTTP_CODE_UNAUTHORIZED)
            ->assertJson([
                'success' => false,
                'message' => __('auth.response.401.middleware')
            ])
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    /**
     * The test checks response when logout process is successful
     * This request must return 200 code
     * @return void
     */
    public function test_logout_throw_success(): void
    {
        $response_login_arr = [];
        $password = Str::random(10);
        $user = User::factory()
            ->set('password', bcrypt($password))
            ->create();

        $response_login = $this->post('/api/auth/login', [
            'email'     => $user['email'],
            'password'  => $password
        ]);

        try {
            $response_login_arr = $response_login->decodeResponseJson()->json();
        } catch(\Throwable $err) {}

        $token = '';

        if(
            isset($response_login_arr['data']) &&
            isset($response_login_arr['data']['access_token'])
        ) {
            $token = $response_login_arr['data']['access_token'];

            $response = $this->withHeaders([
                "Authorization" => "Bearer ".$token
            ])->post('/api/auth/logout');

            $response->assertStatus(self::HTTP_CODE_SUCCESS)
                ->assertJson([
                    'success'   => true,
                    'message'   => __('auth.response.200.logout'),
                    'error'     => ''
                ]);
        }

        $this->assertNotEmpty($token);
    }
}
