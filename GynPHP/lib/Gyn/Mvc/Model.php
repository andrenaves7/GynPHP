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

namespace Gyn\Mvc;

use Gyn\Mvc\Interfaces\ModelInterface;
use Gyn\Config\Config;
use Gyn\Language\Language;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class Model implements ModelInterface
{
	/**
	 * 
	 * @var \Gyn\Db\Interfaces\ActionInterface
	 */
	protected $action;
	
	/**
	 * 
	 * @var \Gyn\Config\Config
	 */
	protected $config;
	
	/**
	 * 
	 * @var string
	 */
	protected $name;
	
	/**
	 * 
	 * @var string
	 */
	protected $primary;
	
	/**
	 * 
	 * @throws \Exception
	 */
	public function __construct()
	{
		$translate = Language::getInstance();
		
		$this->config = new Config();
		if (isset($this->config->db['adapter'])) {
			$adapter = 'Gyn' . CB . 'Db' . CB . 'Adapter' . CB . $this->config->db['adapter'] . CB . $this->config->db['adapter'];
			
			if (!file_exists(LIB . DS . str_replace(CB, DS, $adapter) . '.php')) {
				throw new \Exception($translate->translate('ADAPTER_NOT_FOUND', array($adapter)), 1016);
			}
			
			$this->action = new $adapter($this->config);
			if (method_exists($this->action, 'setReturning')) {
				$this->action->setReturning($this->primary);
			}
		} else {
			throw new \Exception($translate->translate('MUST_DEFINE_PROPRETY', array('Environment\Config::$db[adapter]')), 1016);
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Mvc\Interfaces\ModelInterface::select()
	 */
	public function select()
	{
		return $this->action->select();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Mvc\Interfaces\ModelInterface::fetchAll()
	 */
	public function fetchAll($where = null, $group = null, $order = null, $limit = null, $offset = null)
	{
		return $this->action->fetchAll($this->name, $where, $group, $order, $limit, $offset);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Mvc\Interfaces\ModelInterface::fetchRow()
	 */
	public function fetchRow($where = null, $order = null)
	{
		return $this->action->fetchRow($this->name, $where, $order);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Mvc\Interfaces\ModelInterface::insert()
	 */
	public function insert(array $data)
	{
		return $this->action->insert($this->name, $data);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Mvc\Interfaces\ModelInterface::update()
	 */
	public function update(array $data, $where = null)
	{
		return $this->action->update($this->name, $data, $where);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Mvc\Interfaces\ModelInterface::delete()
	 */
	public function delete($where = null)
	{
		return $this->action->delete($this->name, $where);
	}
	
	/**
	 * 
	 * @return \Gyn\Db\Interfaces\Action
	 */
	public function getAction()
	{
		return $this->action;
	}
}