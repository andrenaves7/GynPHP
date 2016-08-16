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

namespace Gyn\Loader;

use Gyn\Language\Language;
/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class Autoloader
{
	/**
	 * 
	 * @var array
	 */
	private $loaders = array();
	
	public function __construct()
	{
		$this->loaders[LIB]         = LIB;
		$this->loaders[MODULES]     = MODULES;
		$this->loaders[APPLICATION] = APPLICATION;
	
		spl_autoload_register(array($this, 'loader'));
	}
	
	/**
	 * 
	 * @param string $key
	 * @param string $path
	 * @throws \Exception
	 */
	public function set($key, $path)
	{
		if (!$this->has($key)) {
			$this->loaders[$key] = $path;
		} else {
			$translate = Language::getInstance();
			throw new \Exception($translate->translate('LOADER_ALREADY_DEFINED', array($key)), 1001);
		}
	}
	
	/**
	 * 
	 * @param string $key
	 * @throws \Exception
	 * @return array
	 */
	public function get($key)
	{
		if ($this->has($key)) {
			return $this->loaders[$key];
		} else {
			$translate = Language::getInstance();
			throw new \Exception($translate->translate('LOADER_NOT_DEFINED', array($key)), 1002);
		}
	}
	
	/**
	 * 
	 * @param string $key
	 * @throws \Exception
	 */
	public function remove($key)
	{
		if ($this->has($key)) {
			unset($this->loaders[$key]);
		} else {
			$translate = Language::getInstance();
			throw new \Exception($translate->translate('LOADER_NOT_DEFINED', array($key)), 1002);
		}
	}
	
	/**
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function has($key) {
		if (isset($this->loaders[$key])) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 
	 * @param string $className
	 * @return boolean
	 */
	private function loader($className)
	{
		foreach ($this->loaders as $value) {
			$fileName  = $value . DS . str_replace(CB, DS, $className) . '.php';
			if (is_file($fileName)) {
				require_once $fileName;
				
				LoaderStorage::getInstance()->add(time(), $fileName);
				return true;
			}
		}
	}
}