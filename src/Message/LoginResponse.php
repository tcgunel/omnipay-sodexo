<?php

namespace Omnipay\Sodexo\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Sodexo\Models\LoginResponseModel;

class LoginResponse extends AbstractResponse
{
	protected $response;

	protected $request;

	public function __construct($request, $data)
	{
		parent::__construct($request, $data);

		$this->request = $request;

		$this->response = new LoginResponseModel(json_decode(json_encode($data->LoginWsUserResult), true));
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

    public function getRefreshToken(): ?string
    {
        return $this->response->RefreshToken ?? null;
    }

	public function getData(): LoginResponseModel
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
