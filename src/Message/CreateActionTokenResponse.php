<?php

namespace Omnipay\Sodexo\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Sodexo\Models\CreateActionTokenResponseModel;

class CreateActionTokenResponse extends AbstractResponse
{
	protected $response;

	protected $request;

	public function __construct($request, $data)
	{
		parent::__construct($request, $data);

		$this->request = $request;

		$this->response = new CreateActionTokenResponseModel(json_decode(json_encode($data->CreateActionTokenResult ), true));
	}

	public function isSuccessful(): bool
	{
        return $this->response->ResultCode === 0;
	}

	public function getMessage(): ?string
	{
		return $this->response->ResultMessage;
	}

    public function getToken(): ?string
    {
        return $this->response->Token ?? null;
    }

	public function getData(): CreateActionTokenResponseModel
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
