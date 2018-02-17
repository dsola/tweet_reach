<?php

namespace Test\Unit\App\Validators;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class TweetUrlValidator extends TestCase
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var TweetUrlValidator
     */
    private $validator;

    protected final function setUp()
    {
        parent::setUp();
        $this->request = new Request();
        $this->validator = new TweetUrlValidator();
    }

    public final function test_when_url_not_exists_in_request() {
        $this->expectException(ValidationException::class);
        $this->validator->validate($this->request);
    }

    protected final function tearDown()
    {
        unset($this->request, $this->validator);
        parent::tearDown();
    }
}
