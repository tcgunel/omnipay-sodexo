<?php

namespace Omnipay\Sodexo\Models;

class CreateActionTokenResponseModel extends BaseModel
{
    public int $ResultCode;

    public string $ResultMessage;

    public ?string $Token;
}
