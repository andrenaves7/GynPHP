<?php

namespace Application\Filters;

use Gyn\Filter\Filter;
use Gyn\Filter\Interfaces\FilterInterface;

class SampleFilter extends Filter implements FilterInterface
{
	public function sample($value)
	{
		return 'Sample filter for: "' . $value . '"<br />';
	}
}