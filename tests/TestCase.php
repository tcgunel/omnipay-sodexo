<?php

namespace Omnipay\Sodexo\Tests;

use Faker\Factory;
use Omnipay\Sodexo\Gateway;
use Omnipay\Tests\GatewayTestCase;

class TestCase extends GatewayTestCase
{
	public $faker;

	public $username = "XXXXXXXXXXXX";

	public $password = "YYYYYYYYYYYY";

	public $merchant_no = "999999";

	public $terminal_no = "888888";

	public function setUp(): void
	{
		parent::setUp();

		$this->faker = Factory::create("tr_TR");

		$this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
	}
}
