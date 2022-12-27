<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    'response' => [
        '401' => 'Unauthorized.',
        '422' => [
            'validation'    => 'Validation errors.',
            'register'  => 'Registration failed',
            'token'         => 'The Token was not generated'
        ],
        '200' => [
            'register'      => 'You have successfully registered.',
            'login'         => 'You have successfully logged in.',
            'logout'        => 'You have successfully logged out.',
            'me'            => 'User data.',
            'refresh_token' => 'You have successfully refreshed token.'
        ]
    ],
    'registration_error' => 'Registration failed',

];
