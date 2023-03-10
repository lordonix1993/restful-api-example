<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * The test checks response where request doesn't contain all credentials
     * This request must return 422 code and validation errors
     *
     * @return void
     */
    public function test_login_throw_error_missing_all_data(): void
    {
        $user_data = [
            'name'      => '',
            'password'  => ''
        ];

        $response = $this->post(route('auth_login_v1'), $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJson([
                'success' => false,
                'message' => __('auth.response.422.validation'),
                'version' => 'v1'
            ])
            ->assertJsonStructure([
                'data' => ['password', 'email']
            ]);
    }

    /**
     * The test checks response where credentials are wrong
     * This request must return 401 code and authorization is wrong
     *
     * @return void
     */
    public function test_login_throw_error_where_credentials_are_wrong(): void
    {
        $password = $this->faker->password(8);
        User::factory()
            ->set('password', bcrypt($password))
            ->create();

        $response = $this->post(route('auth_login_v1'), [
            'email'     => $this->faker->unique()->freeEmail(),
            'password'  => $this->faker->password(8)
        ]);

        $response->assertStatus(self::HTTP_CODE_UNAUTHORIZED)
            ->assertJson([
                'success'   => false,
                'message'   => __('auth.response.401.login'),
                'version' => 'v1',
                'data'      => []
            ]);
    }

    /**
     * The test checks response where authorization is successful
     * This request must return 200 code
     * @return void
     */
    public function test_login_throw_success(): void
    {
        $password = $this->faker->password(8);
        $user = User::factory()
            ->set('password', bcrypt($password))
            ->create();

        $response = $this->post(route('auth_login_v1'), [
            'email'     => $user['email'],
            'password'  => $password
        ]);

        $response->assertStatus(self::HTTP_CODE_SUCCESS)
            ->assertJson([
                'success'   => true,
                'message'   => __('auth.response.200.login'),
                'version' => 'v1',
                'data'      => [
                    'token_type' => 'bearer'
                ]
            ])
            ->assertJsonStructure([
                'data' => ['access_token', 'token_type', 'expires_in']
            ]);

        $token = '';
        try {
            $response_data = $response->decodeResponseJson()->json('data');
            if(isset($response_data['access_token'])) {
                $token = $response_data['access_token'];
            }
        } catch(\Throwable $err) {}

        $this->assertNotEmpty($token);
    }
}
