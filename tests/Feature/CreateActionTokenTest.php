<?php

namespace Omnipay\Sodexo\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Sodexo\Message\CreateActionTokenRequest;
use Omnipay\Sodexo\Message\CreateActionTokenResponse;
use Omnipay\Sodexo\Models\CreateActionTokenResponseModel;
use Omnipay\Sodexo\Tests\TestCase;

class CreateActionTokenTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_create_action_token_request()
    {
        $params = [
            'token' => 'b',
        ];

        $params_to_be_expected_back = [
            'actionTokenRequest' => [
                'ActionType' => 'SodexoPayment',
                'WsToken'    => 'b',
            ],
        ];

        $request = new CreateActionTokenRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $data = $request->getData();

        self::assertEquals($data, $params_to_be_expected_back);
    }

    public function test_create_action_token_request_validation_error()
    {
        $params = [];

        $request = new CreateActionTokenRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_create_action_token_response()
    {
        $response_class = new \stdClass();

        $response_class->CreateActionTokenResult = new \stdClass();

        $response_class->CreateActionTokenResult->ResultCode    = 0;
        $response_class->CreateActionTokenResult->ResultMessage = 'a';
        $response_class->CreateActionTokenResult->Token         = 'c';

        $response = new CreateActionTokenResponse($this->getMockRequest(), $response_class);

        $data = $response->getData();

        $this->assertTrue($response->isSuccessful());

        $expected = new CreateActionTokenResponseModel([
            'ResultCode'    => 0,
            'ResultMessage' => 'a',
            'Token'         => 'c',
        ]);

        $this->assertEquals($expected, $data);
    }

    public function test_create_action_token_response_api_error()
    {
        $response_class = new \stdClass();

        $response_class->CreateActionTokenResult = new \stdClass();

        $response_class->CreateActionTokenResult->ResultCode    = 1;
        $response_class->CreateActionTokenResult->ResultMessage = 'error';

        $response = new CreateActionTokenResponse($this->getMockRequest(), $response_class);

        $data = $response->getData();

        $this->assertFalse($response->isSuccessful());

        $expected = new CreateActionTokenResponseModel([
            'ResultCode'    => 1,
            'ResultMessage' => 'error',
        ]);

        $this->assertEquals($expected, $data);
    }
}
