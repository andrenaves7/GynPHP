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

namespace Gyn\Db\Interfaces;

use Gyn\Config\Config;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
interface ActionInterface
{
	/**
	 * 
	 * @param Config $config
	 */
	public function __construct(Config $config);
	
	/**
	 * 
	 * @param string $returning
	 */
	public function setReturning($returning);
	
	public function getConnection();
	
	/**
	 * 
	 * @param string/array $table
	 * @param string/array $where
	 * @param string/array $order
	 * @param integer $limit
	 * @param integer $offset
	 */
	public function fetchAll($table, $where, $order, $limit, $offset);
	
	/**
	 * 
	 * @param string/array $table
	 * @param string/array $where
	 * @param string/array $order
	 */
	public function fetchRow($table, $where, $order);
	
	/**
	 * 
	 * @param string/array $table
	 * @param array $data
	 */
	public function insert($table, array $data);
	
	/**
	 * 
	 * @param string/array $table
	 * @param array $data
	 * @param string $where
	 */
	public function update($table, array $data, $where = '');
	
	/**
	 * 
	 * @param string/array $table
	 * @param string/array $where
	 */
	public function delete($table, $where);
	
	/**
	 * 
	 * @param string $string
	 */
	public function quote($string);
	
	/**
	 * 
	 * @param string $sql
	 * @param boolean $all
	 */
	public function querySQL($sql, $all = true);
	
	/**
	 * 
	 * @param string $sql
	 */
	public function executeSQL($sql);
	
	public function beginTransaction();
	
	public function commit();
	
	public function rollBack();
	
	public function select();
}