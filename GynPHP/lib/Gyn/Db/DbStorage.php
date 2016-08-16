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

namespace Gyn\Db;

use Gyn\Db\Interfaces\DbStorageInterface;
use Gyn\Language\Language;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class DbStorage implements DbStorageInterface
{
	/**
	 * 
	 * @var \Gyn\Db\DbStorage
	 */
	private static $instance;
	
	/**
	 * 
	 * @var array
	 */
	private $sql = array();
	
	/**
	 *
	 * @var \Gyn\Language\Language
	 */
	private $translate;
	
	private function __construct()
	{
		$this->translate = Language::getInstance();
	}
	
	/**
	 * 
	 * @return Ambigous <\Gyn\Db\DbStorage, \Gyn\Db\Storage>
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
		throw new \Exception($this->translate->translate('NOT_CLONEABLE_CLASS', array('\Gyn\Db\DbStorage')), 1000);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\DbStorageInterface::add()
	 */
	public function add($time, $sql)
	{
		$this->sql[] = array('time' => $time, 'sql' => $sql);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\DbStorageInterface::getLog()
	 */
	public function getLog()
	{
		return $this->sql;
	}
}