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

use Gyn\Mvc\Controller\DataController;
use Gyn\Config\Config;
use Application\Bootstrap;
use Gyn\Loader\File;
use Gyn\Language\Language;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class App
{
	/**
	 * 
	 * @var \Gyn\Mvc\Controller\DataController
	 */
	private $data;
	
	/**
	 * 
	 * @var \Gyn\Config\Config
	 */
	private $config;
	
	/**
	 * 
	 * @var \Gyn\Language\Language
	 */
	private $translate;
	
	/**
	 * 
	 * @param DataController $data
	 * @param Config $config
	 */
	public function __construct(DataController $data, Config $config)
	{
		$this->translate = Language::getInstance();
		$this->config    = $config;
		$this->data      = $data;
	}
	
	/**
	 * 
	 * @param string $renderLayout
	 * @throws \Exception
	 */
	public function run($renderLayout = true)
	{
		new Bootstrap($this->data, $this->config);
		
		$controllerClass = $this->data->getControllerClass();
		$actionMethod    = $this->data->getActionMethod();
		$params          = $this->data->getParams();
		
		$file = new File();
		
		if (!$file->classExist(MODULES, $controllerClass)) {
			throw new \Exception($this->translate->translate('CONTROLLER_NOT_FOUND', array(str_replace(CB, DS, $controllerClass))), 1004);
		}
		
		if (!class_exists($controllerClass)) {
			throw new \Exception($this->translate->translate('CONTROLLER_CLASS_NOT_FOUND', array($controllerClass)), 1005);
		}
		
		$controller = new $controllerClass($this->data, $this->config);
		
		if (!method_exists($controller, $actionMethod)) {
			throw new \Exception($this->translate->translate('ACTION_NOT_FOUND', array($actionMethod)), 1006);
		}
		
		call_user_func_array(array($controller, $actionMethod), $params);
		if ($renderLayout) {
			$controller->getView()->renderLayout();
		} else {
			$controller->getView()->renderView();
		}
	}
	
	/**
	 * 
	 * @param Config $config
	 * @param integer $code
	 * @param string $msg
	 * @throws \Exception
	 */
	public static function callOnException(Config $config, $code, $msg)
	{
		if (is_file(MODULES . DS . $config->catastrophicErrorFile)) {
			require_once MODULES . DS . $config->catastrophicErrorFile;
		} else {
			$translate = Language::getInstance();
			throw new \Exception($translate->translate('ERROR_FILE_NOT_FOUND', array(MODULES . DS . $config->catastrophicErrorFile)), 2001);
		}
	}
}