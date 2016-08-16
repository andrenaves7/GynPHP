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

namespace Gyn\Mvc\Interfaces;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
interface ModelInterface
{
	public function __construct();
	
	public function select();
	
	/**
	 * 
	 * @param string/array $where
	 * @param string/array $order
	 * @param integer $limit
	 * @param integer $offset
	 * @return array
	 */
	public function fetchAll($where = null, $order = null, $limit = null, $offset = null);
	
	/**
	 * 
	 * @param string/array $where
	 * @param string/array $order
	 * @return array
	 */
	public function fetchRow($where = null, $order = null);
	
	/**
	 * 
	 * @param array $data
	 * @return boolean/integer
	 */
	public function insert(array $data);
	
	/**
	 * 
	 * @param array $data
	 * @param string $where
	 * @return boolean
	 */
	public function update(array $data, $where = null);
	
	/**
	 * 
	 * @param string $where
	 * @return boolean
	 */
	public function delete($where = null);
}