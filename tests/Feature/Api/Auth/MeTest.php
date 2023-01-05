<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class MeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * The test checks response where don't set token
     * This request must return 401 code
     *
     * @return void
     */
    public function test_me_get_response_without_token(): void
    {
        $response = $this->post(route('auth_me'));
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
    public function test_me_get_response_with_not_correctly_token(): void
    {
        $response = $this->withHeaders([
            "Authorization" => "Bearer ".$this->faker->sha256()
        ])->post(route('auth_me'));

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
     * The test which checks response where get authorized user process is successful
     * This request must return 200 code
     * @return void
     */
    public function test_me_get_response_with_successful_token(): void
    {
        $response_login_arr = [];
        $password = $this->faker->password(8);
        $user = User::factory()
            ->set('password', bcrypt($password))
            ->create();

        $response_login = $this->post(route('auth_login'), [
            'email'     => $user['email'],
            'password'  => $password
        ]);

        $response_login->assertJson([
                'success'   => true,
                'message'   => __('auth.response.200.login'),
                'data'      => [
                    'token_type' => 'bearer'
                ]
            ])
            ->assertJsonStructure([
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
            ])->post(route('auth_me'));

            $response->assertStatus(self::HTTP_CODE_SUCCESS)
                ->assertJson([
                    'success' => true,
                    'message' => __('auth.response.200.me')
                ])
                ->assertJsonStructure([
                    "data" => ['id', 'name', 'email']
                ]);
        }

        $this->assertNotEmpty($token);
    }
}
