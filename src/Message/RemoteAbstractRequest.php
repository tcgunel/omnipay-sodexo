<?php

namespace Omnipay\Sodexo\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Sodexo\Traits\HasParameters;

abstract class RemoteAbstractRequest extends AbstractRequest
{
    use HasParameters;

    abstract protected function createResponse($data);
}
