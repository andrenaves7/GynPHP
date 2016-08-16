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

use Gyn\Db\Interfaces\ActionInterface;
use Gyn\Config\Config;
use Gyn\Db\DbStorage;
use Gyn\Language\Language;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class PostgreSQL implements ActionInterface
{
	/**
	 * 
	 * @var \Gyn\Db\Adapter\PostgreSQL\Connection
	 */
	private $connection;
	
	/**
	 * 
	 * @var \Gyn\Config\Config
	 */
	private $config;
	
	/**
	 * 
	 * @var string
	 */
	private $returning;
	
	/**
	 *
	 * @var \Gyn\Language\Language
	 */
	private $translate;
	
	/**
	 * 
	 * @param \Gyn\Config\Config $config
	 */
	public function __construct(Config $config)
	{
		$this->translate  = Language::getInstance();
		$this->config     = $config;
		$this->connection = Connection::getInstance($config)->getConnection();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\ActionInterface::setReturning()
	 */
	public function setReturning($returning)
	{
		$this->returning = $returning;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\ActionInterface::getConnection()
	 */
	public function getConnection()
	{
		return $this->connection;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\ActionInterface::fetchAll()
	 */
	public function fetchAll($table, $where = null, $group = null, $order = null, $limit = null, $offset = null)
	{
		if ($where instanceof Select) {
			$sql = $where->getQuery();
		} else {
			$where = $this->mountWhere($where);
			$group = $this->mountGroupBy($group);
			$order = $this->mountOrderBy($order);
			
			if ($limit != null) {
				$limit = (int)$limit;
				$limit = 'LIMIT ' . $limit . ' ';
			}
			if ($offset != null) {
				$offset = (int)$offset;
				$offset = 'OFFSET ' . $offset . ' ';
			}
			
			$sql = trim('SELECT * FROM ' . $table . $where . $group . $order . $limit . $offset);
		}
		$res = $this->connection->query($sql, \PDO::FETCH_ASSOC);
		
		$this->setStorage($sql);
		
		return $res->fetchAll();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\ActionInterface::fetchRow()
	 */
	public function fetchRow($table, $where = null, $order = null)
	{
		if ($where instanceof Select) {
			$sql = $where->getQuery();
		} else {
			$where = $this->mountWhere($where);
			$order = $this->mountOrderBy($order);
				
			$sql = trim('SELECT * FROM ' . $table . $where . $order);
		}
		$res = $this->connection->query($sql, \PDO::FETCH_ASSOC);
		
		$this->setStorage($sql);
		
		return $res->fetch();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\ActionInterface::insert()
	 */
	public function insert($table, array $data)
	{
		if ($this->returning) {
			$returning = "RETURNING {$this->returning}";
		}
		
		$columns = implode(', ', array_keys($data));
		$values  = ':' . implode(', :', array_keys($data));
		$sql     = trim('INSERT INTO ' . $table . ' (' . $columns . ') VALUES (' . $values . ') ' . $returning);
		$res     = $this->connection->prepare($sql);
	
		foreach ($data as $key => $value) {
			$res->bindValue(':' . $key, $value);
		}
		
		$this->setStorage($this->prepareToStorage($sql, $data));
		
		if ($res->execute()) {
			$result = $res->fetch(\PDO::FETCH_ASSOC);
			return $result[$this->returning];
		} else {
			return false;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\ActionInterface::update()
	 */
	public function update($table, array $data, $where = null)
	{
		$cond = array();
		foreach ($data as $key => $value) {
			array_push($cond, $key  . ' = :' . $key);
		}
		$where = $this->mountWhere($where);
		$newValues = implode(', ', $cond);
		$sql       = 'UPDATE ' . $table . ' SET ' . $newValues . $where;
		$res       = $this->connection->prepare($sql);
		foreach ($data as $key => $value) {
			$res->bindValue(':' . $key, $value);
		}
		
		$this->setStorage($this->prepareToStorage($sql, $data));
		
		if ($res->execute()) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\ActionInterface::delete()
	 */
	public function delete($table, $where = null)
	{
		$where = $this->mountWhere($where);
		
		$sql = 'DELETE FROM ' . $table . $where;
		$res = $this->connection->prepare($sql);
		
		$this->setStorage($sql);
		
		if ($res->execute()) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\ActionInterface::quote()
	 */
	public function quote($string)
	{
		if (!is_numeric($string)) {
			$string = addslashes(trim($string));
			$string = get_magic_quotes_gpc()? stripcslashes($string): $string;
		}
		
		return $string;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\ActionInterface::querySQL()
	 */
	public function querySQL($sql, $all = true)
	{
		if ($this->connection instanceof \PDO) {
			$res = $this->connection->query($sql, \PDO::FETCH_ASSOC);
			
			$this->setStorage($sql);
			
			if ($all == true) {
				return $res->fetchAll();
			} else {
				return $res->fetch();
			}
		} else {
			throw new \Exception($this->translate->translate('DB_CONNECTION_NOT_ESTABLISHED'), 1015);
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\ActionInterface::executeSQL()
	 */
	public function executeSQL($sql)
	{
		$res = $this->connection->prepare($sql);
		
		$this->setStorage($sql);
		
		if ($res->execute()) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\ActionInterface::beginTransaction()
	 */
	public function beginTransaction()
	{
		$this->setStorage('begin transaction');
		
		$this->connection->beginTransaction();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\ActionInterface::commit()
	 */
	public function commit()
	{
		$this->setStorage('commit');
		
		$this->connection->commit();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\ActionInterface::rollBack()
	 */
	public function rollBack()
	{
		$this->setStorage('rollback');
		
		$this->connection->rollBack();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\ActionInterface::select()
	 */
	public function select()
	{
		return new Select($this);
	}
	
	/**
	 * 
	 * @param string/array $where
	 * @return string
	 */
	private function mountWhere($where)
	{
		$sql = '';
		if (is_array($where)) {
			foreach ($where as $key => $value) {
				$sql .= $sql? ' AND ': '';
				$sql .= '(' . $key . ' = ' . $value . ')';
			}
		} else {
			$sql = $where;
		}
		
		$sql = $sql? 'WHERE ' . $sql: $sql;
		
		return ' ' . $sql . ' ';
	}
	
	/**
	 * 
	 * @param string/array $group
	 * @return string
	 */
	private function mountGroupBy($group)
	{
		$sql = '';
		if (is_array($group)) {
			$sql .= implode(', ', $group);
		} else {
			$sql = $group;
		}
		
		$sql = $sql? 'GROUP BY ' . $sql: $sql;
		
		return ' ' . $sql . ' ';
	}
	
	/**
	 * 
	 * @param string/array $order
	 * @return string
	 */
	private function mountOrderBy($order)
	{
		$sql = '';
		if (is_array($order)) {
			$sql .= implode(', ', $order);
		} else {
			$sql = $order;
		}
		
		$sql = $sql? 'ORDER BY ' . $sql: $sql;
		
		return ' ' . $sql . ' ';
	}
	
	/**
	 * 
	 * @param string $sql
	 */
	private function setStorage($sql)
	{
		if (!$this->config->inProduction()) {
			DbStorage::getInstance()->add(time(), $sql);
		}
	}
	
	/**
	 * 
	 * @param string $sql
	 * @param array $data
	 * @return string
	 */
	private function prepareToStorage($sql, array $data = array()) {
		$str = '';
		foreach($data as $key => $value) {
			$str .= ($str)? '; ': '';
			$str .= ':' . $key . ' => \'' . $value . '\'';
		}
		return '\'' . $sql . '\'' . ($str? ' ' . $str: $str);
	}
}