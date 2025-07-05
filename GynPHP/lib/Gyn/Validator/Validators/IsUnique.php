<?php
/**
 * Copyright (c) 2013-2016, The GynPHP Framework Project
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE REGENTS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 */

namespace Gyn\Validator\Validators;

use Gyn\Validator\Interfaces\ValidatorInterface;
use Gyn\Validator\Validator;
use Gyn\Mvc\Model;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class IsUnique extends Validator implements ValidatorInterface
{
	/**
	 * 
	 * @param string $value
	 * @param string $table
	 * @param string $field
	 * @param array $except
	 */
	public function isUnique($value, $table = null, $field = null, array $except = array(), $msg = null)
	{
	    if($msg == null){
		  $this->setMessage($this->language->translate('VALIDATOR_ISUNIQUE'));
	    }else{
	        $this->setMessage($msg);
	    }
		
		if ($table === null) {
			throw new \Exception($this->language->translate('PARAMETER_NOT_DEFINED', array('$table')), 1026);
		}
		
		if ($field === null) {
			throw new \Exception($this->language->translate('PARAMETER_NOT_DEFINED', array('$field')), 1026);
		}
	
		try {
			if (strlen($value) > 0) {
				$model = new Model();
				$action = $model->getAction();
				$sql = $action->select()->from($table, array('COUNT(' . $field . ') as qtd'))
				->where("{$field} = ?", $value);
				
				if (!empty($except)) {
					foreach ($except as $key => $e) {
						$sql->where("{$key} <> ?", $e);
					}
				}
				$res = $sql->fetch();
			} else {
				$res['qtd'] = 0;
			}
		} catch (\PDOException $e) {
			$res['qtd'] = 0;
		}
		
		if (isset($res["qtd"]) && $res["qtd"] > 0) {
			$this->setStatus(false);
		} else {
			$this->setStatus(true);
		}
	}
}