<?php

namespace Omnipay\Sodexo\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use SoapClient;

class LoginRequest extends RemoteAbstractRequest
{
	public string $endpoint = '/LoginWS.svc?wsdl';

	/**
     * @throws InvalidRequestException
     */
	public function getData(): array
	{
        $this->validate('password', 'username');

        return [
            'wsUserInfo' => [
                'Password' => $this->getPassword(),
                'UserName' => $this->getUsername(),
            ],
        ];
	}

    public function sendData($data): LoginResponse
    {
        $client = new SoapClient($this->getEndpoint(), [
            'trace' => true, // Enable trace to view SOAP requests and responses (optional)
            'exceptions' => true, // Enable exceptions for SOAP errors (optional)
            'cache_wsdl' => WSDL_CACHE_NONE,
        ]);

        $response = $client->__soapCall('LoginWsUser', [$this->getData()]);

        return $this->createResponse($response);
    }

    protected function createResponse($data): LoginResponse
    {
        $this->response = new LoginResponse($this, $data);

        return $this->response;
    }
}
