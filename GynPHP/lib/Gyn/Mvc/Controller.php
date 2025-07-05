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

use Gyn\Mvc\Interfaces\ControllerInterface;
use Gyn\Config\Config;
use Gyn\Data\Data;
use Gyn\Helper\DataHelper;
use Gyn\Mvc\Controller\DataController;
use Gyn\Validator\ActionValidator;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class Controller implements ControllerInterface
{
	/**
	 * 
	 * @var \Gyn\Data\Data
	 */
	protected $data;
	
	/**
	 * 
	 * @var \Gyn\Config\Config
	 */
	protected $config;
	
	/**
	 * 
	 * @var \Gyn\Mvc\View
	 */
	protected $view;
	
	/**
	 * 
	 * @var array
	 */
	protected $params;
	
	/**
	 * 
	 * @param DataController $data
	 * @param Config $config
	 */
	public function __construct(DataController $data, Config $config)
	{
		$this->data = new Data();
		
		$this->data->helper     = new DataHelper();
		$this->data->controller = $data;
		$this->config           = $config;

		if ($_POST) {
			$this->params = $_POST;
			$this->data->helper->setValues($this->params);
		}
		
		$this->view = new View($this->data, $this->config);
		
		$this->init();
	}
	
	protected function init()
	{
		
	}
	
	/**
	 * 
	 * @param string $param
	 * @param string $default
	 * @return multitype:|string
	 */
	public function getParam($param, $default = '')
	{
		if (isset($this->params[$param])) {
			return $this->params[$param];
		} else {
			return $default;
		}
	}
	
	/**
	 * 
	 * @param array $data
	 */
	protected function jsonEncode(array $data)
	{
		$this->view->setNoRenderLayout();
		$this->view->setNoRenderView();
	
		echo json_encode($data);
	}
	
	/**
	 * 
	 * @param array $url
	 */
	protected function redirect(array $url = array())
	{
		if (count($url) > 0) {
			$url = $this->config->root . implode(DS, $url) . DS;
		} else {
			$url = $this->config->root;
		}
	   
		header('Location: ' . $url);
	}
	
	/**
	 * 
	 * @return \Gyn\Mvc\View
	 */
	public function getView()
	{
		return $this->view;
	}
	
	/**
	 * 
	 * @return \Gyn\Validator\ActionValidator
	 */
	protected function validate()
	{
		return new ActionValidator($this->view->form);
	}
}