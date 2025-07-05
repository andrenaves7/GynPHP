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

namespace Gyn\Language;

use Gyn\Config\Config;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class Language
{
	/**
	 * 
	 * @var \Gyn\Language\Language
	 */
	private static $instance;
	
	/**
	 * 
	 * @var array
	 */
	private $translate = null;
	
	/**
	 * 
	 * @throws \Exception
	 */
	private function __construct()
	{	
		if (is_file(Config::LANGUAGE)) {
			$data = file_get_contents(Config::LANGUAGE);
			$this->setTranslate($data);
		} else {
			throw new \Exception('The view file \'' . Config::LANGUAGE . '\' was not found', 2000);
		}
	}
	
	/**
	 * 
	 * @return \Gyn\Language\Language
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}
	
	/**
	 * 
	 * @throws \Exception
	 */
	private function __clone()
	{
		throw new \Exception('This is not a cloneable class', 1000);
	}
	
	/**
	 * 
	 * @param string $string
	 */
	private function setTranslate($string)
	{
		$res = array();
		
		$lines = explode("\n", $string);
		foreach ($lines as $value) {
			$column = explode('::', $value);
			
			if (isset($column[0]) && isset($column[1])) {
				$res[$column[0]] = $column[1];
			}
		}
		
		$this->translate = $res;
	}
	
	/**
	 * 
	 * @param string $key
	 * @param array $replace
	 * @return mixed|NULL
	 */
	public function translate($key, array $replace = null)
	{
		if (isset($this->translate[$key])) {
			$text = $this->translate[$key];
			if ($replace != null && count($replace) > 0) {
				for ($i = 0; $i < count($replace); $i++) {
					$text = str_replace('$' . $i, $replace[$i], $text);
				}
			}
			
			return $text;
		} else {
			return null;
		}
	}
}