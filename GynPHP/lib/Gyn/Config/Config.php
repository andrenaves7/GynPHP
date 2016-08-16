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

use Gyn\Config\Interfaces\ConfigInterface;
use Application\Configuration;
use Gyn\Language\Language;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class Config extends Configuration implements ConfigInterface
{
	/**
	 * 
	 * @var \Gyn\Language\Language
	 */
	private $translate;
	
	public function __construct()
	{
		$this->translate = Language::getInstance();
	}
	
	/**
	 * 
	 * @throws \Exception
	 * @return string
	 */
	public function getLayoutDir()
	{
		if (isset($this->layoutDir)) {
			return $this->layoutDir;
		} else {
			throw new \Exception($this->translate->translate('PROPERTY_NOT_DEFINED', array('Config::layoutDir')), 1021);
		}
	}
	
	/**
	 * 
	 * @throws \Exception
	 * @return string
	 */
	public function getLayoutFile()
	{
		if (isset($this->layoutFile)) {
			return $this->layoutFile;
		} else {
			throw new \Exception($this->translate->translate('PROPERTY_NOT_DEFINED', array('Config::layoutFile')), 1021);
		}
	}
	
	/**
	 * 
	 * @throws \Exception
	 * @return string
	 */
	public function getLogFile()
	{
		if (isset($this->logFile)) {
			return $this->logFile;
		} else {
			throw new \Exception($this->translate->translate('PROPERTY_NOT_DEFINED', array('Config::logFile')), 1021);
		}
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function inProduction()
	{
		if ($this->environment == 'production') {
			return true;
		} else {
			return false;
		}
	}
}