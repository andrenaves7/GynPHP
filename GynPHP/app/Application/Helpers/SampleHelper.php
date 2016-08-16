<?php

namespace Application\Helpers;

use Gyn\Helper\Helper;
use Gyn\Helper\Interfaces\HelperInterface;

class SampleHelper extends Helper implements HelperInterface
{
	public function sample($value)
	{
		return 'Sample helper for: "' . $value . '"<br />';
	}
}