<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * This method run every time before run every test method
     *
     * @return void
     */
    public function setUp(): void {
        parent::setUp();
        $this->user = User::factory()->definition();
        $this->user['password'] = Str::random(10);
    }

    /**
     * The test which checks response when request doesn't contain all credentials
     * This request must return 422 code and validation errors
     *
     * @return void
     */
    public function test_login_throw_an_error_missing_all_data(): void
    {
        $user_data = [
            'name' => '',
            'password' => ''
        ];

        $this->post('/api/auth/login', $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJson([
                'success' => false,
                'message' => __('auth.response.422.validation')
            ])
            ->assertJsonStructure([
                'data' => ['password', 'email']
            ]);
    }

    /**
     * The test which checks response when request has not valid email
     * This request must return 422 code and validation errors
     *
     * @return void
     */
    public function test_login_throw_an_error_not_validate_email(): void
    {
        $user_data = [
            'name'      => $this->user['name'],
            'email'     => 'usermail.com',
            'password'  => $this->user['password']
        ];
        $response = $this->post('/api/auth/login', $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJson([
                'success' => false,
                'message' => __('auth.response.422.validation')
            ])
            ->assertJsonStructure([
                'data' => ['email']
            ]);
    }

    /**
     * The test which checks response where credentials are wrong
     *
     * @return void
     */
    public function test_login_throw_error_when_credentials_are_wrong(): void
    {
        $password = Str::random(10);
        $user = User::factory()
            ->set('password', bcrypt($password))
            ->create();

        $response = $this->post('/api/auth/login', [
            'email'     => 'test_'.$user['email'],
            'password'  => 'test123_'.$password
        ]);

        $response->assertStatus(self::HTTP_CODE_UNAUTHORIZED)
            ->assertJson([
                'success'   => false,
                'message'   => __('auth.response.401.login'),
                'data'      => []
            ]);
    }

    /**
     * The test which checks response when authorization is successful
     *
     * @return void
     */
    public function test_login_throw_success(): void
    {
        $password = Str::random(10);
        $user = User::factory()
            ->set('password', bcrypt($password))
            ->create();

        $response = $this->post('/api/auth/login', [
            'email'     => $user['email'],
            'password'  => $password
        ]);

        $response->assertStatus(self::HTTP_CODE_SUCCESS)
            ->assertJson([
                'success'   => true,
                'message'   => __('auth.response.200.login'),
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
