<?php

namespace Omnipay\Sodexo\Models;

class PurchaseResponseModel extends BaseModel
{
    /**
     * 0 başarılı.
     * 33 ve 34  geldiğinde tekrar Payment Token alınmalı.
     *
     * @var int
     */
    public int $ResultCode;

    public string $ResultMessage;

    public ?float $Amount;

    public ?string $AvailablePoint;

    /**
     * Sodexo Günsonu No.
     *
     * @var int
     */
    public int $BatchNo;

    public ?string $HostBalance;

    public ?string $LylBankPoint;

    public ?string $LylMrcPoint;

    public ?string $Otc;

    /**
     * Sodexo İşlem No.
     */
    public ?string $Rrn;

    public ?string $ServiceId;

    public ?string $TransactionEncId;

    public ?bool $UserInvoice;
}
