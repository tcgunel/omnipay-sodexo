<?php

namespace Omnipay\Sodexo\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Sodexo\Models\LoginResponseModel;
use Omnipay\Sodexo\Models\PurchaseResponseModel;

class PurchaseResponse extends AbstractResponse
{
	protected $response;

	protected $request;

	public function __construct($request, $data)
	{
		parent::__construct($request, $data);

		$this->request = $request;

		$this->response = new PurchaseResponseModel(json_decode(json_encode($data->MakePaymentResult), true));
	}

	public function isSuccessful(): bool
	{
        return $this->response->ResultCode === 0;
	}

	public function getMessage(): ?string
	{
		return $this->response->ResultMessage;
	}

	public function getData(): PurchaseResponseModel
	{
		return $this->response;
	}

	public function getRedirectData()
	{
		return null;
	}

	public function getRedirectUrl()
	{
		return '';
	}
}
