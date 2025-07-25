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

namespace Gyn\Config;

use Gyn\Config\Interfaces\BootstrapInterface;
use Gyn\Mvc\Controller\DataController;
use Gyn\Language\Language;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class DefaultBootstrap implements BootstrapInterface
{
	/**
	 * 
	 * @var \Gyn\Mvc\Controller\DataController
	 */
	protected $data;
	
	/**
	 * 
	 * @var \Gyn\Config\Config
	 */
	protected $config;
	
	/**
	 *
	 * @var \Gyn\Language\Language
	 */
	private $translate;
	
	/**
	 * 
	 * @param DataController $data
	 * @param Config $config
	 */
	public function __construct(DataController $data, Config $config)
	{
		$this->translate = Language::getInstance();
		$this->data      = $data;
		$this->config    = $config;
		
		$this->init();
	}
	
	/**
	 * 
	 * @throws \Exception
	 */
	protected function init()
	{
		throw new \Exception($this->translate->translate('MUST_OVERRIDE_METHOD', array('Gyn\Config\Bootstrap::init()')), 1009);
	}
	
	/**
	 * 
	 * @param array $url
	 */
	protected function redirect(array $url = array())
	{
		if (count($url) > 0) {
			$url = $this->config->root . implode(DS, $url) . DS;
		} else {
			$url = $this->config->root;
		}
		
		header('Location: ' . $url);
	}
}