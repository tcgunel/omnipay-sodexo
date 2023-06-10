<?php

namespace Omnipay\Sodexo\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Sodexo\Message\RefundRequest;
use Omnipay\Sodexo\Message\RefundResponse;
use Omnipay\Sodexo\Models\RefundResponseModel;
use Omnipay\Sodexo\Tests\TestCase;

class RefundTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_refund_request()
    {
        $params = [
            'token'         => 'token',
            'txn_status'    => \Omnipay\Sodexo\Constants\TxnStatuses::SALE,
            'merchant_no'   => 'a',
            'terminal_no'   => 'b',
            'amount'        => '9.99',
            'original_rrn'  => 'original_rrn_1',
            'external_rrn'  => 'refund_id_1',
            'external_info' => 'additional data for refund',
        ];

        $params_to_be_expected_back = [
            'refundInfo' => [
                'WsToken'      => 'token',
                'TxnStatus'    => \Omnipay\Sodexo\Constants\TxnStatuses::SALE,
                'MerchantNo'   => 'a',
                'TerminalNo'   => 'b',
                'Amount'       => 9.99,
                'OriginalRrn'  => 'original_rrn_1',
                'ExternalRrn'  => 'refund_id_1',
            ],
        ];

        $request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $data = $request->getData();

        self::assertEquals($data, $params_to_be_expected_back);
    }

    public function test_refund_request_validation_error()
    {
        $params = [
            'token' => 'token',
        ];

        $request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_refund_response()
    {
        $response_class = new \stdClass();

        $response_class->MakeRefundResult = new \stdClass();

        $response_class->MakeRefundResult->ResultCode       = 0;
        $response_class->MakeRefundResult->ResultMessage    = 'a';
        $response_class->MakeRefundResult->AvailablePoint   = null;
        $response_class->MakeRefundResult->BatchNo          = 111;
        $response_class->MakeRefundResult->HostBalance      = null;
        $response_class->MakeRefundResult->LylBankPoint     = null;
        $response_class->MakeRefundResult->LylMrcPoint      = null;
        $response_class->MakeRefundResult->Otc              = 11;
        $response_class->MakeRefundResult->Rrn              = '000000111111';
        $response_class->MakeRefundResult->ServiceId        = 1111;
        $response_class->MakeRefundResult->TransactionEncId = '11AA111A11AAAA11';
        $response_class->MakeRefundResult->UserInvoice      = false;

        $response = new RefundResponse($this->getMockRequest(), $response_class);

        $data = $response->getData();

        $this->assertTrue($response->isSuccessful());

        $expected = new RefundResponseModel([
            'ResultCode'       => 0,
            'ResultMessage'    => 'a',
            'AvailablePoint'   => null,
            'BatchNo'          => 111,
            'HostBalance'      => null,
            'LylBankPoint'     => null,
            'LylMrcPoint'      => null,
            'Otc'              => 11,
            'Rrn'              => '000000111111',
            'ServiceId'        => 1111,
            'TransactionEncId' => '11AA111A11AAAA11',
            'UserInvoice'      => false,
        ]);

        $this->assertEquals($expected, $data);
    }

    public function test_refund_response_api_error()
    {
        $response_class = new \stdClass();

        $response_class->MakeRefundResult = new \stdClass();

        $response_class->MakeRefundResult->ResultCode    = 1;
        $response_class->MakeRefundResult->ResultMessage = 'error';

        $response = new RefundResponse($this->getMockRequest(), $response_class);

        $data = $response->getData();

        $this->assertFalse($response->isSuccessful());

        $expected = new RefundResponseModel([
            'ResultCode'    => 1,
            'ResultMessage' => 'error',
        ]);

        $this->assertEquals($expected, $data);
    }
}
