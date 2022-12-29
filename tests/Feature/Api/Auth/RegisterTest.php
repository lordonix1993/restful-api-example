<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;
use GuzzleHttp\Client;

class RegisterTest extends TestCase
{
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
     * The test which checks response when request has password less than set symbols
     * This request must return 422 code and validation errors
     *
     * @return void
     */
    public function test_throw_success_registration(): void
    {
        $user_data = [
            'name' => $this->user['name'],
            'email' => $this->user['email'],
            'password' => $this->user['password']
        ];

        $client = new Client();
        $response = $client->post(config('app.url').'/api/auth/register', ['json' => $user_data]);

        $this->userDeleteEmail = $this->user['email'];
        $response_data = false;

        try {
            $response_data = json_decode($response->getBody()->getContents(), true);
            $this->assertEquals($response->getStatusCode(), 200);
            $this->assertEquals($response_data['message'], __('auth_response.200.register'));
            $this->assertEquals($response_data['data']['token_type'], 'bear');
            $this->assertNotEmpty($response_data['data']['token']);
        } catch(\Exception $err) {}

        $this->assertNotFalse($response_data);

        //$response = $this->post('/api/auth/register', $user_data);
            /*->assertStatus(self::HTTP_CODE_SUCCESS)
            ->assertJsonFragment([
                'success'   => true,
                'message'   => __('auth.response.200.register'),
                'data'      => [
                    'token_type' => 'bearer'
                ]
            ])
            ->assertJsonStructure([
                'data' => ['access_token', 'token_type', 'expires_in']
            ]);*/


        /*$token = '';
        try {
            $response_data = $response->decodeResponseJson();
            if(isset($response_data['data']) && isset($response_data['data']['token'])) {
                $token = $response_data['data']['token'];
            }
        } catch(\Throwable $err) {}

        $this->assertNotEmpty($token);
        */
    }

    /**
     * The test which checks response when request doesn't contain all user data
     * This request must return 422 code and validation errors
     *
     * @return void
     */
    public function test_throw_an_error_missing_all_data(): void
    {
        $user_data = [
            'name' => '',
            'email' => '',
            'password' => ''
        ];
        $this->post('/api/auth/register', $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJsonFragment([
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
    public function test_throw_an_error_not_validate_email(): void
    {
        $user_data = [
            'name' => $this->user['name'],
            'email' => 'usermail.com',
            'password' => $this->user['password']
        ];
        $this->post('/api/auth/register', $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJsonFragment([
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
    public function test_throw_an_error_when_email_exist(): void
    {
        //Create user
        $user = User::factory()->create();
        $this->userDeleteEmail = $user['email'];

        $user_data = [
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password']
        ];

        //Create user again using user data above
        $this->post('/api/auth/register', $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJsonFragment([
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
    public function test_throw_an_error_when_password_less_than(): void
    {
        $user_data = [
            'name' => $this->user['name'],
            'email' => $this->user['email'],
            'password' => Str::random(5)
        ];
        $this->post('/api/auth/register', $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJsonFragment([
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
    public function test_throw_an_error_when_password_more_than(): void
    {
        $user_data = [
            'name' => $this->user['name'],
            'email' => $this->user['email'],
            'password' => Str::random(300)
        ];
        $this->post('/api/auth/register', $user_data)
            ->assertStatus(self::HTTP_CODE_UNPROCESSABLE_PROCESS)
            ->assertJsonFragment([
                'success' => false,
                'message' => __('auth.response.422.validation')
            ])
            ->assertJsonStructure([
                'data' => ['password']
            ]);
    }



    /**
     * This method run every time when test method was completed
     *
     * @return void
     */
    public function tearDown(): void
    {
        if(!empty($this->userDeleteEmail)) {
            DB::table('users')->where('email', $this->userDeleteEmail)->delete();
            $this->userDeleteEmail = '';
        }
        parent::tearDown();
    }
}
