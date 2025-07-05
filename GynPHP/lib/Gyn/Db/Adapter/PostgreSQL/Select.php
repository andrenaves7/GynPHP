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

use Gyn\Db\Interfaces\SelectInterface;
use Gyn\Db\Interfaces\ActionInterface;
use Gyn\Language\Language;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class Select implements SelectInterface
{
	const LEFT  = 'LEFT';
	const RIGHT = 'RIGHT';
	const INNER = 'INNER';
	
	private $action;
	
	/**
	 * 
	 * @var array
	 */
	private $table = array();
	
	/**
	 * 
	 * @var array
	 */
	private $columns = array();
	
	/**
	 * 
	 * @var array
	 */
	private $where = array();
	
	/**
	 * 
	 * @var array
	 */
	private $orWhere = array();
	
	/**
	 * 
	 * @var array
	 */
	private $order = array();
	
	/**
	 * 
	 * @var array
	 */
	private $group = array();
	
	/**
	 * 
	 * @var array
	 */
	private $join = array();
	
	/**
	 * 
	 * @var integer
	 */
	private $limit = null;
	
	/**
	 * 
	 * @var integer
	 */
	private $offset = null;
	
	/**
	 * 
	 * @var string
	 */
	private $sql = '';
	
	/**
	 *
	 * @var \Gyn\Language\Language
	 */
	private $translate;
	
	/**
	 * 
	 * @param ActionInterface $action
	 */
	public function __construct(ActionInterface $action)
	{
		$this->translate = Language::getInstance();
		$this->action    = $action;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\SelectInterface::__toString()
	 */
	public function __toString()
	{
		return $this->sql;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\SelectInterface::from()
	 */
	public function from($table, array $columns = array())
	{
		$table = $this->prepareTableName($table);
		
		if (count($columns) > 0) {
			foreach($columns as $key => $val) {
				if ($this->pgsqlFunctions($val)) {
					$columns[$key] = $val;
				} else {
					$columns[$key] = $table[1] . '.' . $val;
				}
			}
		}
		array_push($this->table, $table[0]);
		$this->columns = array_merge($this->columns, $columns);
		$this->setQuery();
		
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\SelectInterface::where()
	 */
	public function where($condition, $value = null, $string = true)
	{
		$this->where[] = '(' . $this->prepareCondition($condition, $value, $string) . ')';
		$this->setQuery();
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\SelectInterface::orWhere()
	 */
	public function orWhere($condition, $value = null, $string = true)
	{
		$this->orWhere[] = '(' . $this->prepareCondition($condition, $value, $string) . ')';
		$this->setQuery();
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\SelectInterface::order()
	 */
	public function order($order)
	{
		if (is_array($order) && count($order) > 0) {
			$order = implode(', ', $order);
		}
		array_push($this->order, $order);
		$this->setQuery();
		
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\SelectInterface::group()
	 */
	public function group($group)
	{
		if (is_array($group) && count($group) > 0) {
			$group = implode(', ', $group);
		}
		array_push($this->group, $group);
		$this->setQuery();
		
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\SelectInterface::limit()
	 */
	public function limit($limit, $offset = null)
	{
		$limit  = (int)$limit;
		$offset = (int)$offset;
		
		if ($limit > 0) {
			$this->limit = $limit;
		}
		
		$this->offset($offset);
		$this->setQuery();
		
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\SelectInterface::offset()
	 */
	public function offset($offset)
	{
		$offset = (int)$offset;
		
		if ($offset > 0) {
			$this->offset = $offset;
		}
		$this->setQuery();
		
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\SelectInterface::join()
	 */
	public function join($table, $on, array $columns = array())
	{
		$table = $this->prepareTableName($table);
		$this->joinRelationship(self::INNER, $table, $on, $columns);
		$this->setQuery();
		
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\SelectInterface::joinLeft()
	 */
	public function joinLeft($table, $on, array $columns = array())
	{
		$table = $this->prepareTableName($table);
		$this->joinRelationship(self::LEFT, $table, $on, $columns);
		$this->setQuery();
		
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\SelectInterface::joinRight()
	 */
	public function joinRight($table, $on, array $columns = array())
	{
		$table = $this->prepareTableName($table);
		$this->joinRelationship(self::RIGHT, $table, $on, $columns);
		$this->setQuery();
		
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\SelectInterface::getQuery()
	 */
	public function getQuery()
	{
		return $this->sql;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\SelectInterface::fetch()
	 */
	public function fetch()
	{
		return $this->action->querySQL($this->sql, false);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Db\Interfaces\SelectInterface::fetchAll()
	 */
	public function fetchAll()
	{
		return $this->action->querySQL($this->sql, true);
	}
	
	/**
	 * 
	 * @param string $type
	 * @param array $table
	 * @param string $on
	 * @param array $columns
	 */
	private function joinRelationship($type, array $table, $on, array $columns = array())
	{
		if (count($columns) > 0) {
			foreach ($columns as $key => $val) {
				if ($this->pgsqlFunctions($val)) {
					$columns[$key] = $val;
				} else {
					$columns[$key] = $table[1] . '.' . $val;
				}
			}
		}
		$this->columns = array_merge($this->columns, $columns);
		$string        = $type . ' JOIN ' . $table[0] . ' ON ' . $on;
		array_push($this->join, $string);
		return;
	}
	
	/**
	 * 
	 * @throws \Exception
	 */
	private function setQuery()
	{
		if(count($this->table) > 0) {
			$table = implode(', ', $this->table);
		} else {
			throw new \Exception($this->translate->translate('NONE_TABLE_DEFINED'), 1014);
		}
		
		if (count($this->columns) > 0) {
			$columns = implode(', ', $this->columns);
		} else {
			$columns = '*';
		}
		
		if (count($this->where) > 0) {
			$where = 'WHERE ' . implode(' AND ', $this->where);
		} else {
			$where = '';
		}
		
		if (count($this->orWhere) > 0) {
			$orWhere  = 'OR ' . implode(' OR ', $this->orWhere);
		} else {
			$orWhere = '';
		}
		
		if (count($this->order) > 0) {
			$order = 'ORDER BY ' . implode(', ', $this->order);
		} else {
			$order = '';
		}
		
		if (count($this->group) > 0) {
			$group = 'GROUP BY ' . implode(', ', $this->group);
		} else {
			$group = '';
		}
		
		if ($this->limit != null) {
			$limit = 'LIMIT ' . $this->limit;
		} else {
			$limit = '';
		}
		
		if ($this->offset != null) {
			$offset = 'OFFSET ' . $this->offset;
		} else {
			$offset = '';
		}
		
		if (count($this->join) > 0) {
			$join = implode(' ', $this->join);
		} else {
			$join = '';
		}
		
		$sql  = 'SELECT ';
		$sql .= $columns . ' ';
		$sql .= 'FROM ';
		$sql .= $table . ' ';
		$sql .= $join . ' ';
		$sql .= $where . ' ';
		$sql .= $orWhere . ' ';
		$sql .= $group . ' ';
		$sql .= $order . ' ';
		$sql .= $limit . ' ';
		$sql .= $offset;
		
		$this->sql = trim($sql);
	}
	
	/**
	 * 
	 * @param string/array $table
	 * @throws \Exception
	 * @return multitype:string Ambigous <string, mixed> |multitype:unknown
	 */
	private function prepareTableName($table)
	{
		$alias     = '';
		$tableName = '';
		
		if (is_array($table)) {
			$alias = key($table);
			if (is_array($alias)) {
				throw new \Exception($this->translate->translate('INCORRECT_VALUE_FOR_ALIAS', array($alias)), 1013);
			} else {
				$string = $table[$alias] . ' ' . $alias;
				return array($string, $alias);
			}
		} else {
			return array($table, $table);
		}
	}

	/**
	 * Prepara a condição substituindo os placeholders (?) por valores escapados com segurança.
	 *
	 * @param string $condition
	 * @param mixed $value Pode ser string, int, array
	 * @param bool $string Se os valores devem ser tratados como string (com aspas)
	 * @return string Condição segura com valores interpolados
	 */
	private function prepareCondition($condition, $value, $string)
	{
		if (!is_array($value)) {
			$value = [$value];
		}

		$placeholders = substr_count($condition, '?');

		if ($placeholders !== count($value)) {
			throw new \InvalidArgumentException(sprintf(
				"Esperado %d valor(es) para %d placeholder(s) na condição '%s', mas %d valor(es) foram fornecidos.",
				$placeholders,
				$placeholders,
				$condition,
				count($value)
			));
		}

		$segments = explode('?', $condition);
		$result = '';

		foreach ($segments as $i => $segment) {
			$result .= $segment;
			if (isset($value[$i])) {
				$val = $value[$i];
				if (is_int($val)) {
					$quoted = (string)$val;
				} else {
					$quoted = $this->action->quote($val);
					$quoted = trim($quoted, "'");
					$quoted = $string ? "'" . $quoted . "'" : $quoted;
				}
				$result .= $quoted;
			}
		}

		return $result;
	}

	
	/**
	 * 
	 * @param string $field
	 * @return boolean
	 */
	private function pgsqlFunctions($field)
	{
		static $functions = [
			'AVG', 'COUNT', 'MIN', 'MAX', 'SDT', 'SDTDEV', 'SUM', 'CONCAT',
			'COALESCE', 'IFNULL', 'LTRIM', 'RTRIM', 'TRIM', 'CASE', 'ARRAY',
			'DISTINCT', 'TO_CHAR', 'REPLACE', 'CAST', 'FORMAT', 'EXTRACT'
		];

		// Usando expressão regular para detectar a função como uma palavra inteira
		$pattern = '/\b(' . implode('|', $functions) . ')\b/i';

		return preg_match($pattern, $field) === 1;
	}

}