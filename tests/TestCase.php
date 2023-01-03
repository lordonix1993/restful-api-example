<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // Http codes constants
    const HTTP_CODE_SUCCESS                 = 200;
    const HTTP_CODE_UNPROCESSABLE_PROCESS   = 422;
    const HTTP_CODE_UNAUTHORIZED            = 401;
}
