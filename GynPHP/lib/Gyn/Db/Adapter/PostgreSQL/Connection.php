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

namespace Gyn\Db\Adapter\PostgreSQL;

use Gyn\Db\Interfaces\ConnectionInterface;
use Gyn\Config\Config;
use Gyn\Language\Language;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class Connection implements ConnectionInterface
{
	/**
	 * 
	 * @var \Gyn\Db\Adapter\PostgreSQL\Connection
	 */
	private static $instance;
	
	/**
	 * 
	 * @var \PDO
	 */
	private $connection;
	
	/**
	 * 
	 * @var \Gyn\Config\Config
	 */
	protected $config;
	
	/**
	 * 
	 * @var string
	 */
	private $host;
	
	/**
	 *
	 * @var string
	 */
	private $port;
	
	/**
	 *
	 * @var string
	 */
	private $schema;
	
	/**
	 *
	 * @var string
	 */
	private $user;
	
	/**
	 *
	 * @var string
	 */
	private $pass;
	
	/**
	 *
	 * @var \Gyn\Language\Language
	 */
	private $translate;
	
	/**
	 * 
	 * @param Config $config
	 */
	private function __construct(Config $config)
	{
		$this->translate = Language::getInstance();
		$this->host      = $config->db['host'];
		$this->port      = $config->db['port'];
		$this->schema    = $config->db['schema'];
		$this->user      = $config->db['user'];
		$this->pass      = $config->db['pass'];
		$this->charset   = ($config->db['charset']? $config->db['charset']: 'UTF8');
		
		$this->config = $config;
		
		$this->connection();
	}
	
	/**
	 * 
	 * @param Config $config
	 * @return \Gyn\Db\Adapter\PostgreSQL\Connection
	 */
	public static function getInstance(Config $config)
	{
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c($config);
		}
		return self::$instance;
	}
	
	/**
	 * 
	 * @throws \Exception
	 */
	private function __clone()
	{
		throw new \Exception($this->translate->translate('NOT_CLONEABLE_CLASS', array('Gyn\Db\Adapter\PostgreSQL\Connection')), 1000);
	}
	
	/**
	 * 
	 * @throws \Exception
	 */
	private function connection()
	{
		try {
			$port = $this->port != ''? " port=$this->port;": '';
			$dsn  = 'pgsql:host=' . $this->host . ';' .$port . 'dbname=' . $this->schema . '';
			$this->connection = new \PDO($dsn, $this->user, $this->pass);
			$this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch (\PDOException $e) {
			throw new \Exception($e->getMessage(), $e->getCode());
		}
	}
	
	/**
	 * 
	 * @return PDO
	 */
	public function getConnection()
	{
		return $this->connection;
	}
}