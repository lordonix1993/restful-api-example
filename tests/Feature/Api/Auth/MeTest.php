<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class MeTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private mixed $response_login_arr = '';

    /**
     * This method run every time before run every test method
     *
     * @return void
     */
    public function setUp(): void {
        parent::setUp();

        $password = Str::random(10);
        $this->user = User::factory()
            ->set('password', bcrypt($password))
            ->create();

        $response_login = $this->post('/api/auth/login', [
            'email'     => $this->user['email'],
            'password'  => $password
        ]);

        try {
            $this->response_login_arr = $response_login->decodeResponseJson()->json();
        } catch(\Throwable $err) {}
    }

    /**
     * The test checks response where don't set token
     * This request must return 401 code
     *
     * @return void
     */
    public function test_me_get_response_without_token(): void
    {
        $response = $this->post('/api/auth/me');
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
        $this->assertNotEmpty($this->response_login_arr);
        $token = '';

        if(
            isset($this->response_login_arr['data']) &&
            isset($this->response_login_arr['data']['access_token'])
        ) {
            $token = $this->response_login_arr['data']['access_token'];

            $response = $this->withHeaders([
                "Authorization" => "Bearer ".$token
            ])->post('/api/auth/me');

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

    /**
     * The test which checks response where get authorized user process is fail when token is wrong
     * This request must return 401 code
     * @return void
     */
    public function test_me_get_response_with_not_correctly_token(): void
    {
        $this->assertNotEmpty($this->response_login_arr);
        $token = '';

        if(
            isset($this->response_login_arr['data']) &&
            isset($this->response_login_arr['data']['access_token'])
        ) {
            $token = $this->response_login_arr['data']['access_token'];

            $response = $this->withHeaders([
                "Authorization" => "Bearer ".$token.'1'
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

        $this->assertNotEmpty($token);
    }

    public function tearDown(): void
    {
        $this->response_login_arr = '';
        parent::tearDown();
    }
}
