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

namespace Gyn\Session;

use Gyn\Session\Interfaces\SessionInterface;
use Gyn\Language\Language;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class SessionManager implements SessionInterface
{
	/**
	 * 
	 * @var \Gyn\Session\SessionManager
	 */
	private static $instance;
	
	private function __construct()
	{
		session_start();
	}
	
	/**
	 * 
	 * @return \Gyn\Session\SessionManager
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
		$translate = Language::getInstance();
		throw new \Exception($translate->translate('NOT_CLONEABLE_CLASS', array('Gyn\Session\SessionManager')), 1000);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Session\Interfaces\SessionInterface::set()
	 */
	public function set($key, $value)
	{
		$_SESSION['Gyn\Session\SessionManager'][$key] = $value;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Session\Interfaces\SessionInterface::get()
	 */
	public function get($key)
	{
		if (isset($_SESSION['Gyn\Session\SessionManager'][$key])) {
			return $_SESSION['Gyn\Session\SessionManager'][$key];
		} else {
			return null;
		}
	}
	
	/**
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function check($key)
	{
		if (isset($_SESSION['Gyn\Session\SessionManager'][$key])) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Session\Interfaces\SessionInterface::destroy()
	 */
	public function destroy()
	{
		if (isset($_SESSION['Gyn\Session\SessionManager'])) {
			unset($_SESSION['Gyn\Session\SessionManager']);
		}
		session_destroy();
	}
}