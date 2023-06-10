<?php

namespace Omnipay\Sodexo\Models;

class BaseModel
{
	public function __construct(?array $abstract)
	{
		foreach ($abstract as $key => $arg) {

			if (property_exists($this, $key)) {

				$this->$key = $arg;

			}

		}

        $this->original_response = json_encode($abstract);
	}

    public string $original_response;
}
