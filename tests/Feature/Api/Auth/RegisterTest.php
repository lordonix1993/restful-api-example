<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private array $user = [];
    private string $userDeleteEmail = '';

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
     * The test which checks response when request doesn't contain all user data
     * This request must return 422 code and validation errors
     *
     * @return void
     */
    public function test_register_throw_an_error_missing_all_data(): void
    {
        $user_data = [
            'name'      => '',
            'email'     => '',
            'password'  => ''
        ];
        $this->post('/api/auth/register', $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJson([
                'success' => false,
                'message' => __('auth.response.422.validation')
            ])
            ->assertJsonStructure([
                'data' => ['password', 'name', 'email']
            ]);
    }

    /**
     * The test which checks response when request has not valid email
     * This request must return 422 code and validation errors
     *
     * @return void
     */
    public function test_register_throw_an_error_not_validate_email(): void
    {
        $user_data = [
            'name'      => $this->user['name'],
            'email'     => 'usermail.com',
            'password'  => $this->user['password']
        ];
        $this->post('/api/auth/register', $user_data)
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
     * The test which checks response when request has not valid email
     * This request must return 422 code and validation errors
     *
     * @return void
     */
    public function test_register_throw_an_error_when_email_exist(): void
    {
        //Create user
        $user = User::factory()->create();

        $user_data = [
            'name'      => $user['name'],
            'email'     => $user['email'],
            'password'  => $user['password']
        ];

        //Create user again using user data above
        $this->post('/api/auth/register', $user_data)
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
     * The test which checks response when request has password less than set symbols
     * This request must return 422 code and validation errors
     *
     * @return void
     */
    public function test_register_throw_an_error_when_password_less_than(): void
    {
        $user_data = [
            'name'      => $this->user['name'],
            'email'     => $this->user['email'],
            'password'  => Str::random(5)
        ];
        $this->post('/api/auth/register', $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJson([
                'success' => false,
                'message' => __('auth.response.422.validation')
            ])
            ->assertJsonStructure([
                'data' => ['password']
            ]);
    }

    /**
     * The test which checks response when request has password less than set symbols
     * This request must return 422 code and validation errors
     *
     * @return void
     */
    public function test_register_throw_an_error_when_password_more_than(): void
    {
        $user_data = [
            'name'      => $this->user['name'],
            'email'     => $this->user['email'],
            'password'  => Str::random(300)
        ];
        $this->post('/api/auth/register', $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJson([
                'success' => false,
                'message' => __('auth.response.422.validation')
            ])
            ->assertJsonStructure([
                'data' => ['password']
            ]);
    }

    /**
     * The test which checks response when registration is successful
     *
     * @return void
     */
    public function test_register_throw_success(): void
    {
        $user_data = [
            'name'      => $this->user['name'],
            'email'     => $this->user['email'],
            'password'  => $this->user['password']
        ];

        $response = $this->post('/api/auth/register', $user_data);
        $response->assertStatus(self::HTTP_CODE_SUCCESS)
            ->assertJson([
                'success'   => true,
                'message'   => __('auth.response.200.register'),
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
