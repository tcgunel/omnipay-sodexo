<?php

namespace Omnipay\Sodexo\Constants;

class TxnStatuses
{
    public const SALE = 'N'; // Normal satış.

    public const VOID = 'V'; // Void-Normal Satışın iptali.

    public const REVERSE = 'R'; // Reverse-Normal satışın teknik iptali.

    public const REVERSE_OF_VOID = 'RV'; // Reverse of Void-İptalin teknik iptali.

    public static function list(): array
    {
        return [
            self::SALE,
            self::VOID,
            self::REVERSE,
            self::REVERSE_OF_VOID,
        ];
    }
}
