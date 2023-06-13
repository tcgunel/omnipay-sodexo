<?php

namespace Omnipay\Sodexo\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Sodexo\Constants\TxnStatuses;
use Omnipay\Sodexo\Traits\HasParameters;
use phpseclib\Crypt\RSA;
use phpseclib\Math\BigInteger;
use SoapClient;

class PurchaseRequest extends RemoteAbstractRequest
{
    use HasParameters;

    public string $endpoint = '/PaymentWS.svc?wsdl';

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate(
            'merchant_no',
            'terminal_no',
            'amount',
            'external_rrn',
            'external_info',
        );

        if ($this->getTxnStatus() === TxnStatuses::SALE) {

            $this->validate(
                'token',
                'txn_code',
                'gsm',
                'modulus_key',
                'exponent_key',
            );

            if ($this->getGsm() !== null) {

                $gsm = (int)$this->getGsm();

                if (strlen($gsm) !== 10) {

                    throw new InvalidRequestException('Gsm numarası 10 haneli olmalıdır.');

                }

                if (((string)$gsm)[0] !== '5') {

                    throw new InvalidRequestException('Gsm numarası 5 ile başlamalıdır.');

                }

            }

        }

        if ($this->getTxnStatus() === TxnStatuses::VOID) {

            $this->validate('rrn');

        }

        return [
            'paymentInfo' => [
                'WsToken'      => $this->getToken(),
                'TxnType'      => 'OtpPayment',
                'TxnStatus'    => $this->getTxnStatus(),
                'MerchantNo'   => $this->getMerchantNo(),
                'TerminalNo'   => $this->getTerminalNo(),
                'TxnCode'      => $this->encrypt($this->getModulusKey(), $this->getExponentKey(), $this->getTxnCode()),
                'ServiceId'    => $this->getServiceId(),
                'Amount'       => (float)$this->getAmount(),
                'ExternalRrn'  => $this->getExternalRrn(),
                'GSM'          => $this->getGsm(),
                'ExternalInfo' => $this->getExternalInfo(),
                'Rrn'          => $this->getRrn(),
            ],
        ];
    }

    public function encrypt(string $modulus_key, string $exponent_key, $data): ?string
    {
        try {
            $expBytes = base64_decode(trim($exponent_key));
            $modBytes = base64_decode(trim($modulus_key));
            $modulus  = new BigInteger($modBytes, 256);
            $exponent = new BigInteger($expBytes, 256);

            $rsa = new RSA();
            $rsa->loadKey(['n' => $modulus, 'e' => $exponent]);
            $rsa->setEncryptionMode(RSA::ENCRYPTION_OAEP);

            $data = iconv('UTF-8', 'UTF-16LE', $data);

            $encrypted = $rsa->encrypt($data);

            return base64_encode($encrypted);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function sendData($data): PurchaseResponse
    {
        $client = new SoapClient($this->getEndpoint(), [
            'trace'      => true, // Enable trace to view SOAP requests and responses (optional)
            'exceptions' => true, // Enable exceptions for SOAP errors (optional)
            'cache_wsdl' => WSDL_CACHE_NONE,
        ]);

        $response = $client->__soapCall('MakePayment', [$this->getData()]);

        return $this->createResponse($response);
    }

    protected function createResponse($data): PurchaseResponse
    {
        $this->response = new PurchaseResponse($this, $data);

        return $this->response;
    }
}
