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

namespace Gyn\Filter;

use Gyn\Loader\File;
use Gyn\Config\Config;
use Gyn\Language\Language;
use Gyn\Data\Data;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class ActionFilter
{
	/**
	 * 
	 * @var \Gyn\Mvc\Controller\DataController
	 */
	protected $data = null;
	
	/**
	 * 
	 * @var \Gyn\Config\Config
	 */
	protected $config = null;
	
	public $filter = null;
	
	/**
	 *
	 * @var \Gyn\Language\Language
	 */
	private $translate;
	
	/**
	 * 
	 * @param Data $data
	 * @param Config $config
	 */
	public function __construct(Data $data, Config $config)
	{
		$this->translate = Language::getInstance();
		$this->data      = $data;
		$this->config    = $config;
	}
	
	/**
	 * 
	 * @param string $method
	 * @param array $args
	 * @throws \Exception
	 * @return mixed
	 */
	public function __call($method, $args)
	{
		$class['gyn'] = 'Gyn' . CB . 'Filter' . CB . 'Filters' . CB . ucfirst($method);
		$class['app'] = 'Application' . CB . 'Filters' . CB . ucfirst($method) . 'Filter';
		
		$file = new File();
		
		if (!$file->classExist(LIB, $class['gyn']) && !$file->classExist(APPLICATION, $class['app'])) {
			throw new \Exception($this->translate->translate('FILTER_NOT_FOUND', array($method)), 1019);
		}
		
		if ($this->filter != null) {
			$c = $class['gyn'];
		} else {
			$c = $class['app'];
		}
		
		try {
			$component = new $c($this->data, $this->config);
			$component->init();
			if (method_exists($component, $method)) {
				return call_user_func_array(array($component, $method), $args);
			} else {
				throw new \Exception($this->translate->translate('FILTER_METHOD_NOT_FOUND', array($method,  $c)), 1017);
			}
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage(), $e->getCode());
		}
	}
}