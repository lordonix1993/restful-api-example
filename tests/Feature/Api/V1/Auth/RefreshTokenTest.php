<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class RefreshTokenTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * The test checks response where don't set token
     * This request must return 401 code
     *
     * @return void
     */
    public function test_refresh_token_without_token(): void
    {
        $response = $this->post(route('auth_refresh_token_v1'));
        $response->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJson([
                'success' => false,
                'version' => 'v1',
            ])
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    /**
     * The test checks response where don't set token
     * This request must return 401 code
     *
     * @return void
     */
    public function test_refresh_with_not_correctly_token(): void
    {
        $response = $this->withHeaders([
            "Authorization" => "Bearer ".$this->faker->sha256()
        ])->post(route('auth_refresh_token_v1'));

        $response->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJson([
                'success' => false,
                'version' => 'v1'
            ])
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    /**
     * The test which checks response where the refresh token process is successful
     * This request must return 200 code
     * @return void
     */
    public function test_refresh_get_response_with_successful_token(): void
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

        $response_login->assertJson([
                'success'   => true,
                'message'   => __('auth.response.200.login'),
                'version'   => 'v1',
                'data'      => [
                    'token_type' => 'bearer'
                ]
            ])->assertJsonStructure([
                'data' => ['access_token', 'token_type', 'expires_in']
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
            ])->post(route('auth_refresh_token_v1'));

            $response->assertStatus(self::HTTP_CODE_SUCCESS)
                ->assertJson([
                    'success' => true,
                    'message' => __('auth.response.200.refresh_token'),
                    'version' => 'v1'
                ])
                ->assertJsonStructure([
                    'data' => ['access_token', 'token_type', 'expires_in']
                ]);
        }

        $this->assertNotEmpty($token);
    }

    /**
     * The test which checks response where the refresh token process twice
     * This request must return 422 code
     * @return void
     */
    public function test_refresh_get_response_refresh_token_twice(): void
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

        $response_login->assertJson([
                'success'   => true,
                'message'   => __('auth.response.200.login'),
                'version' => 'v1',
                'data'      => [
                    'token_type' => 'bearer'
                ]
            ])->assertJsonStructure([
                'data' => ['access_token', 'token_type', 'expires_in']
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
            ])->post(route('auth_refresh_token_v1'));

            $response->assertStatus(self::HTTP_CODE_SUCCESS)
                ->assertJson([
                    'success' => true,
                    'version' => 'v1',
                    'message' => __('auth.response.200.refresh_token')
                ])
                ->assertJsonStructure([
                    'data' => ['access_token', 'token_type', 'expires_in']
                ]);

            $response_again = $this->withHeaders([
                "Authorization" => "Bearer ".$token
            ])->post(route('auth_refresh_token_v1'));

            $response_again->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
                ->assertJson([
                    'success' => false
                ]);
        }

        $this->assertNotEmpty($token);
    }
}
