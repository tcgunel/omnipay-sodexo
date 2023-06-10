<?php

namespace Omnipay\Sodexo\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Sodexo\Message\LoginRequest;
use Omnipay\Sodexo\Message\LoginResponse;
use Omnipay\Sodexo\Models\LoginResponseModel;
use Omnipay\Sodexo\Tests\TestCase;

class LoginTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_login_request()
    {
        $params = [
            'Password' => 'a',
            'UserName' => 'b',
        ];

        $params_to_be_expected_back = [
            'wsUserInfo' => [
                'Password' => 'a',
                'UserName' => 'b',
            ],
        ];

        $request = new LoginRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $data = $request->getData();

        self::assertEquals($data, $params_to_be_expected_back);
    }

    public function test_login_request_validation_error()
    {
        $params = [
            'Password' => 'a',
        ];

        $request = new LoginRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_login_response()
    {
        $response_class = new \stdClass();

        $response_class->LoginWsUserResult = new \stdClass();

        $response_class->LoginWsUserResult->ResultCode    = 0;
        $response_class->LoginWsUserResult->ResultMessage = 'a';
        $response_class->LoginWsUserResult->RefreshToken  = 'b';
        $response_class->LoginWsUserResult->Token         = 'c';

        $response = new LoginResponse($this->getMockRequest(), $response_class);

        $data = $response->getData();

        $this->assertTrue($response->isSuccessful());

        $expected = new LoginResponseModel([
            'ResultCode'    => 0,
            'ResultMessage' => 'a',
            'RefreshToken'  => 'b',
            'Token'         => 'c',
        ]);

        $this->assertEquals($expected, $data);
    }

    public function test_login_response_api_error()
    {
        $response_class = new \stdClass();

        $response_class->LoginWsUserResult = new \stdClass();

        $response_class->LoginWsUserResult->ResultCode    = 1;
        $response_class->LoginWsUserResult->ResultMessage = 'error';

        $response = new LoginResponse($this->getMockRequest(), $response_class);

        $data = $response->getData();

        $this->assertFalse($response->isSuccessful());

        $expected = new LoginResponseModel([
            'ResultCode'    => 1,
            'ResultMessage' => 'error',
        ]);

        $this->assertEquals($expected, $data);
    }
}
