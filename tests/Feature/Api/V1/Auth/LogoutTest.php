<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class LogoutTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * The test checks response where don't set token
     * This request must return 401 code
     *
     * @return void
     */
    public function test_logout_without_token(): void
    {
        $response = $this->post(route('auth_logout_v1'));
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
     * The test which checks response where get authorized user process is fail where token is wrong
     * This request must return 401 code
     * @return void
     */
    public function test_logout_not_correctly_token(): void
    {
        $response = $this->withHeaders([
            "Authorization" => "Bearer ".$this->faker->sha256()
        ])->post(route('auth_logout_v1'));

        $response->assertStatus(self::HTTP_CODE_UNAUTHORIZED)
            ->assertJson([
                'success' => false,
                'message' => __('auth.response.401.middleware'),
                'version' => 'v1'
            ])
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    /**
     * The test checks response where logout process is successful
     * This request must return 200 code
     * @return void
     */
    public function test_logout_throw_success(): void
    {
        $response_login_arr = [];
        $password = $this->faker->password(8);
        $user = User::factory()
            ->set('password', bcrypt($password))
            ->create();

        $response_login = $this->post(route('auth_login_v1'), [
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
            ])->post(route('auth_logout_v1'));

            $response->assertStatus(self::HTTP_CODE_SUCCESS)
                ->assertJson([
                    'success'   => true,
                    'message'   => __('auth.response.200.logout'),
                    'version' => 'v1'
                ]);
        }

        $this->assertNotEmpty($token);
    }
}
