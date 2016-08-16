<?php

namespace Application\Validators;

use Gyn\Validator\Validator;
use Gyn\Validator\Interfaces\ValidatorInterface;

class SampleValidator extends Validator implements ValidatorInterface
{
	public function sample($value)
	{
		$this->setMessage('Erro de Validação');
		
		if ($value) {
			$this->setStatus(true);
		} else {
			$this->setStatus(false);
		}
	}
}