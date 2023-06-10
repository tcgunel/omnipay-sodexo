<?php

namespace Omnipay\Sodexo\Traits;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Sodexo\Constants\TxnStatuses;

trait HasParameters
{
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getEndpoint()
    {
        return
            $this->getTestMode() ?
                'https://uatps.sodexopayment.com/' . $this->endpoint :
                'https://www.sodexopayment.com/' . $this->endpoint;
    }

    public function getToken()
    {
        return $this->getParameter('token');
    }

    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }

    public function getTxnStatus()
    {
        return $this->getParameter('txn_status');
    }

    public function setTxnStatus($value)
    {
        if (!in_array($value, TxnStatuses::list())) {

            throw new \Exception(sprintf('TxnStatus değeri şunlardan biri olmalıdır: %s', implode(', ', TxnStatuses::list())));

        }

        return $this->setParameter('txn_status', $value);
    }

    public function getMerchantNo()
    {
        return $this->getParameter('merchant_no');
    }

    public function setMerchantNo($value)
    {
        return $this->setParameter('merchant_no', (string)$value);
    }

    public function getTerminalNo()
    {
        return $this->getParameter('terminal_no');
    }

    public function setTerminalNo($value)
    {
        return $this->setParameter('terminal_no', (string)$value);
    }

    public function getTxnCode()
    {
        return (string)$this->getParameter('txn_code');
    }

    public function setTxnCode($value)
    {
        return $this->setParameter('txn_code', (string)$value);
    }

    public function getServiceId()
    {
        return $this->getParameter('service_id');
    }

    public function setServiceId($value)
    {
        return $this->setParameter('service_id', $value);
    }

    public function getExternalRrn()
    {
        return $this->getParameter('external_rrn');
    }

    public function setExternalRrn($value)
    {
        return $this->setParameter('external_rrn', $value);
    }

    public function getOriginalRrn()
    {
        return $this->getParameter('original_rrn');
    }

    public function setOriginalRrn($value)
    {
        return $this->setParameter('original_rrn', $value);
    }

    public function getRrn()
    {
        return $this->getParameter('rrn');
    }

    public function setRrn($value)
    {
        return $this->setParameter('rrn', $value);
    }

    public function getGsm()
    {
        return $this->getParameter('gsm');
    }

    public function setGsm($value)
    {
        return $this->setParameter('gsm', $value);
    }

    public function getExternalInfo()
    {
        return $this->getParameter('external_info');
    }

    public function setExternalInfo($value)
    {
        return $this->setParameter('external_info', $value);
    }

    public function getModulusKey()
    {
        return (string)$this->getParameter('modulus_key');
    }

    public function setModulusKey($value)
    {
        return $this->setParameter('modulus_key', $value);
    }

    public function getExponentKey()
    {
        return (string)$this->getParameter('exponent_key');
    }

    public function setExponentKey($value)
    {
        return $this->setParameter('exponent_key', $value);
    }
}
