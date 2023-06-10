<?php

namespace Omnipay\Sodexo\Models;

class LoginResponseModel extends BaseModel
{
    public int $ResultCode;

    public string $ResultMessage;

    public ?string $RefreshToken;

    public ?string $Token;
}
