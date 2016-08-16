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

namespace Gyn\Mvc\Controller;

use Gyn\Mvc\Interfaces\DataControllerInterface;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class DataController implements DataControllerInterface
{
	/**
	 * 
	 * @var string
	 */
	private $module = null;
	
	/**
	 * 
	 * @var string
	 */
	private $controller = null;
	
	/**
	 * 
	 * @var string
	 */
	private $action = null;
	
	/**
	 * 
	 * @var string
	 */
	private $controllerClass = null;
	
	/**
	 * 
	 * @var string
	 */
	private $actionMethod = null;
	
	/**
	 * 
	 * @var array
	 */
	private $params = array();
	
	/**
	 * 
	 * @param string $module
	 */
	public function setModule($module)
	{
		$this->module = $module;
	}
	
	/**
	 * 
	 * @param string $controller
	 */
	public function setController($controller)
	{
		$this->controller = $controller;
	}
	
	/**
	 * 
	 * @param string $action
	 */
	public function setAction($action)
	{
		$this->action = $action;
	}
	
	/**
	 * 
	 * @param string $controllerClass
	 */
	public function setControllerClass($controllerClass)
	{
		$this->controllerClass = $controllerClass;
	}
	
	/**
	 * 
	 * @param string $actionMethod
	 */
	public function setActionMethod($actionMethod)
	{
		$this->actionMethod = $actionMethod;
	}
	
	/**
	 *
	 * @param array $params
	 */
	public function setParams(array $params)
	{
		$this->params = $params;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getModule()
	{
		return $this->module;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getController()
	{
		return $this->controller;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getAction()
	{
		return $this->action;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getControllerClass()
	{
		return $this->controllerClass;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getActionMethod()
	{
		return $this->actionMethod;
	}
	
	/**
	 * 
	 * @return multitype:
	 */
	public function getParams()
	{
		return $this->params;
	}
}