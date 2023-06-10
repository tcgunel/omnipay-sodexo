<?php

namespace Omnipay\Sodexo;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Sodexo\Constants\TxnStatuses;
use Omnipay\Sodexo\Traits\HasParameters;

/**
 * Sodexo Gateway
 * (c) Tolga Can Günel
 * 2015, mobius.studio
 * http://www.github.com/tcgunel/omnipay-sodexo
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = [])
 */
class Gateway extends AbstractGateway
{
    use HasParameters;

    public function getName(): string
    {
        return 'Sodexo';
    }

    public function getDefaultParameters()
    {
        return [
            'username'     => null,
            'password'     => null,
            'token'        => null,
            'service_id'   => null,
            'modulus_key'  => null,
            'exponent_key' => null,
            'txn_code'     => null,
            'gsm'          => null,
            'rrn'          => null,
            /*"clientIp" => "127.0.0.1",

            "installment"   => "1",
            "nationalId"    => "11111111111",
            "taxNumber"     => "",
            "taxOffice"     => "",
            "userReference" => "",
            "secure"        => false,
            "publicKey"     => "",
            "privateKey"    => "",
            "language"      => ["tr-TR", "en-US"],
            "echo"          => "",
            "version"       => '1.0',

            "pageSize"  => '10',
            "pageIndex" => '1',*/

        ];
    }

    public function login(array $parameters = array()): AbstractRequest
    {
        return $this->createRequest('\Omnipay\Sodexo\Message\LoginRequest', $parameters);
    }

    public function createActionToken(array $parameters = array()): AbstractRequest
    {
        return $this->createRequest('\Omnipay\Sodexo\Message\CreateActionTokenRequest', $parameters);
    }

    public function purchase(array $parameters = array()): AbstractRequest
    {
        $this->setTxnStatus(TxnStatuses::SALE);

        return $this->createRequest('\Omnipay\Sodexo\Message\PurchaseRequest', $parameters);
    }

    public function voidPayment(array $parameters = array()): AbstractRequest
    {
        $this->setTxnStatus(TxnStatuses::VOID);

        return $this->createRequest('\Omnipay\Sodexo\Message\PurchaseRequest', $parameters);
    }

    public function reversePayment(array $parameters = array()): AbstractRequest
    {
        $this->setTxnStatus(TxnStatuses::REVERSE);

        return $this->createRequest('\Omnipay\Sodexo\Message\PurchaseRequest', $parameters);
    }

    public function reverseOfVoidPayment(array $parameters = array()): AbstractRequest
    {
        $this->setTxnStatus(TxnStatuses::REVERSE_OF_VOID);

        return $this->createRequest('\Omnipay\Sodexo\Message\PurchaseRequest', $parameters);
    }

    public function refund(array $parameters = array()): AbstractRequest
    {
        $this->setTxnStatus(TxnStatuses::SALE);

        return $this->createRequest('\Omnipay\Sodexo\Message\RefundRequest', $parameters);
    }
}
