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

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
interface SelectInterface
{
	/**
	 * 
	 * @param ActionInterface $action
	 */
	public function __construct(ActionInterface $action);
	
	public function __toString();
	
	/**
	 * 
	 * @param string/array $table
	 * @param array $columns
	 */
	public function from($table, array $columns);
	
	/**
	 * 
	 * @param string $condition
	 * @param number/string $value
	 * @param boolean $string
	 */
	public function where($condition, $value, $string = true);
	
	/**
	 * 
	 * @param string $condition
	 * @param number/string $value
	 */
	public function orWhere($condition, $value, $string = true);
	
	/**
	 * 
	 * @param integer $order
	 */
	public function order($order);
	
	/**
	 * 
	 * @param string/array $group
	 */
	public function group($group);
	
	/**
	 * 
	 * @param number $limit
	 * @param number $offset
	 */
	public function limit($limit, $offset = 0);
	
	/**
	 * 
	 * @param number $offset
	 */
	public function offset($offset);
	
	public function getQuery();
	
	/**
	 * 
	 * @param string/array $table
	 * @param string $on
	 * @param array $columns
	 */
	public function join($table, $on, array $columns = array());
	
	/**
	 * 
	 * @param string/array $table
	 * @param string $on
	 * @param array $columns
	 */
	public function joinLeft($table, $on, array $columns = array());
	
	/**
	 * 
	 * @param string/array $table
	 * @param string $on
	 * @param array $columns
	 */
	public function joinRight($table, $on, array $columns = array());
	
	public function fetch();
	
	public function fetchAll();
}