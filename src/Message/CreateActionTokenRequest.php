<?php

namespace Omnipay\Sodexo\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use SoapClient;

class CreateActionTokenRequest extends RemoteAbstractRequest
{
    protected string $endpoint = '/LoginWS.svc?wsdl';

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('token');

        return [
            'actionTokenRequest' => [
                'ActionType' => 'SodexoPayment',
                'WsToken'    => $this->getToken(),
            ],
        ];
    }

    public function sendData($data): CreateActionTokenResponse
    {
        $client = new SoapClient($this->getEndpoint(), [
            'trace'      => true, // Enable trace to view SOAP requests and responses (optional)
            'exceptions' => true, // Enable exceptions for SOAP errors (optional)
            'cache_wsdl' => WSDL_CACHE_NONE,
        ]);

        $response = $client->__soapCall('CreateActionToken', [$this->getData()]);

        return $this->createResponse($response);
    }

    protected function createResponse($data): CreateActionTokenResponse
    {
        $this->response = new CreateActionTokenResponse($this, $data);

        return $this->response;
    }
}
