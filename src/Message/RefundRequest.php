<?php

namespace Omnipay\Sodexo\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Sodexo\Traits\HasParameters;
use SoapClient;

class RefundRequest extends RemoteAbstractRequest
{
    use HasParameters;

    public string $endpoint = '/PaymentWS.svc?wsdl';

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate(
            'token',
            'merchant_no',
            'terminal_no',
            'amount',
            'original_rrn',
        );

        return [
            'refundInfo' => [
                'WsToken'      => $this->getToken(),
                'TxnStatus'    => $this->getTxnStatus(),
                'MerchantNo'   => $this->getMerchantNo(),
                'TerminalNo'   => $this->getTerminalNo(),
                'Amount'       => (float)$this->getAmount(),
                'OriginalRrn'  => $this->getOriginalRrn(),
                'ExternalRrn'  => $this->getExternalRrn(),
            ],
        ];
    }

    public function sendData($data): RefundResponse
    {
        $client = new SoapClient($this->getEndpoint(), [
            'trace'      => true, // Enable trace to view SOAP requests and responses (optional)
            'exceptions' => true, // Enable exceptions for SOAP errors (optional)
            'cache_wsdl' => WSDL_CACHE_NONE,
        ]);

        $response = $client->__soapCall('MakeRefund', [$this->getData()]);

        return $this->createResponse($response);
    }

    protected function createResponse($data): RefundResponse
    {
        $this->response = new RefundResponse($this, $data);

        return $this->response;
    }
}
