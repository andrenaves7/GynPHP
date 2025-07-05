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

namespace Gyn\Validator;

use Gyn\Loader\File;
use Gyn\Helper\Form\Form;
use Gyn\Language\Language;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class ActionValidator
{
	/**
	 * 
	 * @var array
	 */
	private $message = array();
	
	/**
	 * 
	 * @var boolean
	 */
	private $status = true;
	
	/**
	 * 
	 * @var \Gyn\Helper\Form\Form
	 */
	private $form = null;
	
	/**
	 * 
	 * @param Form $form
	 */
	public function __construct(Form $form)
	{
		$this->form = $form;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function getMessage()
	{
		return $this->message;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function getStatus()
	{
		return $this->status;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function isValid()
	{
		if ($this->getStatus() == true) {
			return true;
		} else {
			$this->form->setErrors($this->getMessage());
			return false;
		}
	}

	public function __call($method, $args)
	{
		$translate = Language::getInstance();
		
		if (count($args)) {
			$class['gyn'] = 'Gyn' . CB . 'Validator' . CB . 'Validators' . CB . ucfirst($method);
			$class['app'] = 'Application' . CB . 'Validators' . CB . ucfirst($method) . 'Validator';
			
			$file = new File();
			
			if ($file->classExist(LIB, $class['gyn'])) {
				$c = new $class['gyn'];
			} else if ($file->classExist(APPLICATION, $class['app'])) {
				$c = new $class['app'];
			} else {
				throw new \Exception($translate->translate('VALIDATOR_NOT_FOUND', array($method)), 1024);
			}
			
			$idComponent = $args[0];
			unset($args[0]);
			
			if (!isset($args[1])) {
				throw new \Exception($translate->translate('MUST_DEFINE_VALIDATOR', array($method)), 1027);
			}
			
			call_user_func_array(array($c, $method), $args);
			
			if ($this->status == true) {
				$this->status = $c->getStatus();
			}
			
			if ($c->getStatus() == false) {
				$this->message[$idComponent][] = $c->getMessage();
			}
			
			return $this;
			
		} else {
			throw new \Exception($translate->translate('ILLEGAL_NUMBER_OF_ARGUMENTS'), 1020);
		}
	}
}