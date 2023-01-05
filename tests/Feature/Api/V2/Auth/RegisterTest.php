<?php

namespace Tests\Feature\Api\V2\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class RegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private array $user = [];

    /**
     * This method run every time before run every test method
     *
     * @return void
     */
    public function setUp(): void {
        parent::setUp();
        $this->user = User::factory()->definition();
        $this->user['password'] = $this->faker->password(8);
    }

    /**
     * The test checks response where request doesn't contain all user data
     * This request must return 422 code and validation errors
     *
     * @return void
     */
    public function test_register_throw_an_error_missing_all_data(): void
    {
        $user_data = [
            'name'                  => '',
            'email'                 => '',
            'password'              => '',
            'password_confirmation' => '',
        ];

        $this->post(route('auth_registration_v2'), $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJson([
                'success' => false,
                'message' => __('auth.response.422.validation'),
                'version' => 'v2'
            ])
            ->assertJsonStructure([
                'data' => ['password', 'name', 'email', 'password_confirmation']
            ]);
    }

    /**
     * The test checks response where request has not valid email
     * This request must return 422 code and validation errors
     *
     * @return void
     */
    public function test_register_throw_an_error_different_passwords(): void
    {
        $user_data = [
            'name'                  => $this->user['name'],
            'email'                 => $this->user['email'],
            'password'              => $this->user['password'],
            'password_confirmation' => $this->faker->password(8),
        ];

        $this->post(route('auth_registration_v2'), $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJson([
                'success' => false,
                'message' => __('auth.response.422.validation'),
                'version' => 'v2'
            ])
            ->assertJsonStructure([
                'data' => ['password']
            ]);
    }

    /**
     * The test checks response where request has not valid email
     * This request must return 422 code and validation errors
     *
     * @return void
     */
    public function test_register_throw_an_error_not_validate_email(): void
    {
        $user_data = [
            'name'                  => $this->user['name'],
            'email'                 => 'usermail.com',
            'password'              => $this->user['password'],
            'password_confirmation' => $this->user['password'],
        ];

        $this->post(route('auth_registration_v2'), $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJson([
                'success' => false,
                'message' => __('auth.response.422.validation'),
                'version' => 'v2'
            ])
            ->assertJsonStructure([
                'data' => ['email']
            ]);
    }

    /**
     * The test checks response where request has not valid email
     * This request must return 422 code and validation errors
     *
     * @return void
     */
    public function test_register_throw_an_error_where_email_exist(): void
    {
        //Create user
        $user = User::factory()->create();

        $user_data = [
            'name'                  => $user['name'],
            'email'                 => $user['email'],
            'password'              => $user['password'],
            'password_confirmation' => $user['password'],
        ];

        //Create user again using user data above
        $this->post(route('auth_registration_v2'), $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJson([
                'success' => false,
                'message' => __('auth.response.422.validation'),
                'version' => 'v2'
            ])
            ->assertJsonStructure([
                'data' => ['email']
            ]);
    }

    /**
     * The test checks response where request has password less than set symbols
     * This request must return 422 code and validation errors
     *
     * @return void
     */
    public function test_register_throw_an_error_where_password_less_than(): void
    {
        $user_data = [
            'name'      => $this->user['name'],
            'email'     => $this->user['email'],
            'password'  => $this->faker->password(1,5)
        ];

        $this->post(route('auth_registration_v2'), $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJson([
                'success' => false,
                'message' => __('auth.response.422.validation'),
                'version' => 'v2'
            ])
            ->assertJsonStructure([
                'data' => ['password']
            ]);
    }

    /**
     * The test checks response where request has password less than set symbols
     * This request must return 422 code and validation errors
     *
     * @return void
     */
    public function test_register_throw_an_error_where_password_more_than(): void
    {
        $user_data = [
            'name'      => $this->user['name'],
            'email'     => $this->user['email'],
            'password'  => $this->faker->password(300, 300)
        ];

        $this->post(route('auth_registration_v2'), $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJson([
                'success' => false,
                'message' => __('auth.response.422.validation'),
                'version' => 'v2'
            ])
            ->assertJsonStructure([
                'data' => ['password']
            ]);
    }

    /**
     * The test checks response where registration is successful
     *
     * @return void
     */
    public function test_register_throw_success(): void
    {
        $user_data = [
            'name'                  => $this->user['name'],
            'email'                 => $this->user['email'],
            'password'              => $this->user['password'],
            'password_confirmation' => $this->user['password'],
        ];

        $response = $this->post(route('auth_registration_v2'), $user_data);
        $response->assertStatus(self::HTTP_CODE_SUCCESS)
            ->assertJson([
                'success'   => true,
                'message'   => __('auth.response.200.register'),
                'version'   => 'v2',
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

    /**
     * This method run every time after run every test method
     *
     * @return void
     */
    public function tearDown(): void
    {
        $this->user = [];
        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}
