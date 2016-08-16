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

namespace Gyn\Dispatch;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
use Gyn\Config\Config;
use Gyn\Log\Error;
use Gyn\Mvc\Controller\DataController;

class Dispatcher
{
	/**
	 * 
	 * @var \Gyn\Config\Config
	 */
	private $config;
	
	/**
	 * 
	 * @var array
	 */
	private $delimiters = array('-', '.', '_');
	
	public function __construct()
	{
		$this->config = new Config();
	}
	
	public function dispatch()
	{
		$url = explode(DS, $this->prepareURL());
		
		if (isset($url[0]) && $url[0] && $this->isModule($url[0])) {
			$module     = isset($url[0]) && $url[0]? $url[0]: 'def';
			$controller = isset($url[1]) && $url[1]? $url[1]: 'index';
			$action     = isset($url[2]) && $url[2]? $url[2]: 'index';
			
			unset($url[0], $url[1], $url[2]);
		} else {
			$module     = 'def';
			$controller = isset($url[0]) && $url[0]? $url[0]: 'index';
			$action     = isset($url[1]) && $url[1]? $url[1]: 'index';
			
			unset($url[0], $url[1]);
		}
		
		$params = $url;
		
		$module          = $this->prepareModule($module);
		$controllerClass = $this->prepareController($module, $controller);
		$actionMethod    = $this->prepareAction($action);
		$params          = $this->prepareParams($params);
		
		$data = new DataController();
		$data->setModule($module);
		$data->setController($controller);
		$data->setAction($action);
		$data->setControllerClass($controllerClass);
		$data->setActionMethod($actionMethod);
		$data->setParams($params);
		
		try {
			$app = new App($data, $this->config);
			$app->run();
		} catch (\Exception $e) {
			$this->dispatchOnException($e->getCode(), $e->getMessage());
		}
	}
	
	/**
	 * 
	 * @param integer $code
	 * @param string $msg
	 */
	private function dispatchOnException($code, $msg)
	{
		$noRenderViewCode = array(1007, 1012);
		
		$module     = 'def';
		$controller = 'error';
		$action     = 'index';
		
		$module          = $this->prepareModule($module);
		$controllerClass = $this->prepareController($module, $controller);
		$actionMethod    = $this->prepareAction($action);
		
		$params[] = $code;
		$params[] = $msg;
		
		$data = new DataController();
		$data->setModule($module);
		$data->setController($controller);
		$data->setAction($action);
		$data->setControllerClass($controllerClass);
		$data->setActionMethod($actionMethod);
		$data->setParams($params);
		
		$renderLayout = !in_array($code, $noRenderViewCode);
		
		try {
			$app = new App($data, $this->config);
			$app->run($renderLayout);
		} catch (\Exception $e) {
			App::callOnException($this->config, $e->getCode(), $e->getMessage());
		}
		
		$log = new Error($this->config, $code, $msg);
		$log->write();
	}
	
	/**
	 * 
	 * @return string
	 */
	private function prepareURL()
	{
		$uri  = explode(DS, $_SERVER['REQUEST_URI']);
		$root = explode(DS, $this->config->root);
	
		for ($i = 0; $i < count($root); $i++) {
			if ($root[$i] == $uri[$i]) {
				unset($uri[$i]);
			}
		}
	
		return implode(DS, $uri);
	}
	
	/**
	 * 
	 * @param string $moduleName
	 * @return boolean
	 */
	private function isModule($moduleName)
	{
		$moduleName = $this->prepareModule($moduleName);
		$dirName    = MODULES . DS . $moduleName;
		
		if (is_dir($dirName)) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 
	 * @param string $module
	 * @return string
	 */
	private function prepareModule($module)
	{
		$module = explode($this->delimiters[0], str_replace($this->delimiters, $this->delimiters[0], $module));
	
		foreach ($module as $key => $value) {
			$module[$key] = ucfirst($value);
		}
	
		return implode('', $module);
	}
	
	/**
	 * 
	 * @param string $module
	 * @param string $controller
	 * @return string
	 */
	private function prepareController($module, $controller)
	{
		$controller = explode($this->delimiters[0], str_replace($this->delimiters, $this->delimiters[0], $controller));
		
		foreach ($controller as $key => $value) {
			$controller[$key] = ucfirst($value);
		}
		
		$controllerClass  = ucfirst(strtolower($module)) . CB;
		$controllerClass .= 'Controllers' . CB;
		$controllerClass .= implode('', $controller) . 'Controller';
		
		return $controllerClass;
	}
	
	/**
	 * 
	 * @param string $action
	 * @return string
	 */
	private function prepareAction($action)
	{
		$action = explode($this->delimiters[0], str_replace($this->delimiters, $this->delimiters[0], $action));
	
		foreach ($action as $key => $value) {
			$action[$key] = ucfirst($value);
		}
	
		return lcfirst(implode('', $action)) . 'Action';
	}
	
	/**
	 * 
	 * @param array $params
	 * @return multitype:NULL unknown
	 */
	private function prepareParams(array $params)
	{
		$return = array();
		foreach ($params as $value) {
			if (!empty($value)) {
				if ($value != '_null_') {
					$return[] = $value;
				} else {
					$return[] = null;
				}
			}
		}
	
		return $return;
	}
}