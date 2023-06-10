<?php

namespace Omnipay\Sodexo\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Sodexo\Message\PurchaseRequest;
use Omnipay\Sodexo\Message\PurchaseResponse;
use Omnipay\Sodexo\Models\PurchaseResponseModel;
use Omnipay\Sodexo\Tests\TestCase;

class PurchaseTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_purchase_sale_request()
    {
        $params = [
            'token'         => 'token',
            'txn_status'    => \Omnipay\Sodexo\Constants\TxnStatuses::SALE,
            'merchant_no'   => 'a',
            'terminal_no'   => 'b',
            'txn_code'      => 'c',
            'amount'        => '9.99',
            'external_rrn'  => 'cart_id_1',
            'gsm'           => 5554443322,
            'external_info' => 'additional data for sale',
            'modulus_key'   => '---',
            'exponent_key'  => '---',
            'service_id'    => '',
        ];

        $params_to_be_expected_back = [
            'paymentInfo' => [
                'WsToken'      => 'token',
                'TxnType'      => 'OtpPayment',
                'TxnStatus'    => \Omnipay\Sodexo\Constants\TxnStatuses::SALE,
                'MerchantNo'   => 'a',
                'TerminalNo'   => 'b',
                'ServiceId'    => '',
                'Amount'       => 9.99,
                'ExternalRrn'  => 'cart_id_1',
                'GSM'          => 5554443322,
                'ExternalInfo' => 'additional data for sale',
                'TxnCode'      => '',
                'Rrn'          => null,
            ],
        ];

        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $params_to_be_expected_back['paymentInfo']['TxnCode'] = $request->encrypt('---', '---', 'c');

        $request->initialize($params);

        $data = $request->getData();

        self::assertEquals($data, $params_to_be_expected_back);
    }

    public function test_purchase_void_request()
    {
        $params = [
            'token'         => 'token',
            'txn_status'    => \Omnipay\Sodexo\Constants\TxnStatuses::VOID,
            'merchant_no'   => 'a',
            'terminal_no'   => 'b',
            'amount'        => '9.99',
            'external_rrn'  => 'cart_id_1',
            'external_info' => 'additional data for sale',
            'service_id'    => '',
            'rrn'           => '000011111',
        ];

        $params_to_be_expected_back = [
            'paymentInfo' => [
                'WsToken'      => 'token',
                'TxnType'      => 'OtpPayment',
                'TxnStatus'    => \Omnipay\Sodexo\Constants\TxnStatuses::VOID,
                'MerchantNo'   => 'a',
                'TerminalNo'   => 'b',
                'ServiceId'    => '',
                'Amount'       => 9.99,
                'ExternalRrn'  => 'cart_id_1',
                'GSM'          => null,
                'ExternalInfo' => 'additional data for sale',
                'TxnCode'      => '',
                'Rrn'          => '000011111',
            ],
        ];

        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $data = $request->getData();

        self::assertEquals($data, $params_to_be_expected_back);
    }

    public function test_purchase_request_validation_error()
    {
        $params = [
            'token' => 'token',
        ];

        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_purchase_void_request_validation_error()
    {
        $params = [
            'token'         => 'token',
            'txn_status'    => \Omnipay\Sodexo\Constants\TxnStatuses::VOID,
            'merchant_no'   => 'a',
            'terminal_no'   => 'b',
            'amount'        => '9.99',
            'external_rrn'  => 'cart_id_1',
            'external_info' => 'additional data for sale',
            'service_id'    => '',
        ];

        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($params);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_purchase_request_gsm_bad_start_validation_error()
    {
        $params = [
            'token'         => 'token',
            'txn_status'    => \Omnipay\Sodexo\Constants\TxnStatuses::SALE,
            'merchant_no'   => 'a',
            'terminal_no'   => 'b',
            'txn_code'      => 'c',
            'amount'        => '9.99',
            'external_rrn'  => 'cart_id_1',
            'gsm'           => 9055544433,
            'external_info' => 'additional data for sale',
            'modulus_key'   => '---',
            'exponent_key'  => '---',
            'service_id'    => '',
        ];

        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->expectException(InvalidRequestException::class);

        $this->expectExceptionMessage('Gsm numarası 5 ile başlamalıdır.');

        $request
            ->initialize($params)
            ->getData();
    }

    public function test_purchase_request_gsm_short_validation_error()
    {
        $params = [
            'token'         => 'token',
            'txn_status'    => \Omnipay\Sodexo\Constants\TxnStatuses::SALE,
            'merchant_no'   => 'a',
            'terminal_no'   => 'b',
            'txn_code'      => 'c',
            'amount'        => '9.99',
            'external_rrn'  => 'cart_id_1',
            'gsm'           => '0555444333',
            'external_info' => 'additional data for sale',
            'modulus_key'   => '---',
            'exponent_key'  => '---',
            'service_id'    => '',
        ];

        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->expectException(InvalidRequestException::class);

        $this->expectExceptionMessage('Gsm numarası 10 haneli olmalıdır.');

        $request
            ->initialize($params)
            ->getData();
    }

    public function test_purchase_response()
    {
        $response_class = new \stdClass();

        $response_class->MakePaymentResult = new \stdClass();

        $response_class->MakePaymentResult->ResultCode       = 0;
        $response_class->MakePaymentResult->ResultMessage    = 'a';
        $response_class->MakePaymentResult->AvailablePoint   = null;
        $response_class->MakePaymentResult->BatchNo          = 111;
        $response_class->MakePaymentResult->HostBalance      = null;
        $response_class->MakePaymentResult->LylBankPoint     = null;
        $response_class->MakePaymentResult->LylMrcPoint      = null;
        $response_class->MakePaymentResult->Otc              = 11;
        $response_class->MakePaymentResult->Rrn              = '000000111111';
        $response_class->MakePaymentResult->ServiceId        = 1111;
        $response_class->MakePaymentResult->TransactionEncId = '11AA111A11AAAA11';
        $response_class->MakePaymentResult->UserInvoice      = false;

        $response = new PurchaseResponse($this->getMockRequest(), $response_class);

        $data = $response->getData();

        $this->assertTrue($response->isSuccessful());

        $expected = new PurchaseResponseModel([
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

    public function test_purchase_response_api_error()
    {
        $response_class = new \stdClass();

        $response_class->MakePaymentResult = new \stdClass();

        $response_class->MakePaymentResult->ResultCode    = 1;
        $response_class->MakePaymentResult->ResultMessage = 'error';

        $response = new PurchaseResponse($this->getMockRequest(), $response_class);

        $data = $response->getData();

        $this->assertFalse($response->isSuccessful());

        $expected = new PurchaseResponseModel([
            'ResultCode'    => 1,
            'ResultMessage' => 'error',
        ]);

        $this->assertEquals($expected, $data);
    }
}
