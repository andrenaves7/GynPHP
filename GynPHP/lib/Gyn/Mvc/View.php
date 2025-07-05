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

use Gyn\Mvc\Interfaces\ViewInterface;
use Gyn\Config\Config;
use Gyn\Loader\Loader;
use Gyn\Db\DbStorage;
use Gyn\Helper\Form\Form;
use Gyn\Helper\Tag\Tag;
use Gyn\Data\Data;
use Gyn\Helper\Helper\Helper;
use Gyn\Loader\LoaderStorage;
use Gyn\Filter\ActionFilter;
use Gyn\Language\Language;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class View implements ViewInterface
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
	 * @var \Gyn\Loader\Loader
	 */
	private $loader = null;
	
	/**
	 * 
	 * @var boolean
	 */
	private $renderView = true;
	
	/**
	 *
	 * @var boolean
	 */
	private $renderLayout = true;
	
	/**
	 * 
	 * @var \Gyn\Helper\Form\Form
	 */
	public $form = null;
	
	/**
	 * 
	 * @var \Gyn\Helper\Tag\Tag
	 */
	public $tag = null;
	
	/**
	 * 
	 * @var \Gyn\Helper\Helper\Helper
	 */
	public $helper = null;
	
	/**
	 * 
	 * @var \Gyn\Filter\Filter
	 */
	public $filter = null;
	
	/**
	 *
	 * @var \Gyn\Language\Language
	 */
	private $translate;

	/**
	 * Params to view
	 * @var arary 
	 */
	private $params = array();

	public $logSQL;

	public $logLoader;
	
	/**
	 * 
	 * @param Data $data
	 * @param Config $config
	 */
	public function __construct(Data $data, Config $config)
	{
		$this->translate = Language::getInstance();
		
		$this->data   = $data;
		$this->config = $config;
		$this->loader = new Loader($this->data->controller);
		
		$this->form   = new Form($this->data, $this->config);
		$this->tag    = new Tag($this->data, $this->config);
		$this->helper = new Helper($this->data, $this->config);
		
		$this->filter = new ActionFilter($this->data, $this->config);
	}

	public function setParam($key, $val)
	{
		$this->params[$key] = $val;
	}

	public function getParam($key)
	{
		if (isset($this->params[$key])) {
			return $this->params[$key];
		}
		return '';
	}
	
	public function setRenderView()
	{
		$this->renderView = true;
	}
	
	public function setNoRenderView()
	{
		$this->renderView = false;
	}
	
	public function setRenderLayout()
	{
		$this->renderLayout = true;
	}
	
	public function setNoRenderLayout()
	{
		$this->renderLayout = false;
	}
	
	/**
	 * 
	 * @param string $return
	 * @throws \Exception
	 * @return string
	 */
	public function renderView($return = false, $url = null)
	{
	    $headers = $this->get_headers_custom();
		//if(isset($headers['ACCESS-TOKEN']) && $headers['ACCESS-TOKEN'] == ACCESS_TOKEN && isset($headers['APP-TOKEN']) && $headers['APP-TOKEN'] == APP_TOKEN) {
		if(isset($headers['ACCESS-TOKEN']) && isset($headers['APP-TOKEN'])) {
	        //header('Content-type:application/json;charset=utf-8');
	    } else {
    		if ($this->renderView || $return) {
    			$moduleName     = $this->data->controller->getModule();
    			$controllerName = $this->data->controller->getController();
    			$actionName     = $this->data->controller->getAction();
    			
    			if (!$url) {
    				$fileName  = MODULES . DS . $moduleName . DS . 'views' . DS . 'scripts' . DS;
    				$fileName .= $controllerName . DS . $actionName . '.phtml';
    			} else {
    				$fileName = ROOT_DIR . DS . $url;
    			}
    			
    			if (file_exists($fileName)) {
    				$res = $this->requireOnce($fileName);
    				if ($return) {
    					return $res;
    				} else {
    					echo $res;
    				}
    			} else {
    				throw new \Exception($this->translate->translate('VIEW_FILE_NOT_FOUND', array($fileName)), 1007);
    			}
    		}
	    }
	}
	
	/**
	 * 
	 * @throws \Exception
	 */
	public function renderLayout()
	{
		if($this->renderLayout) {
			$layoutFile = $this->config->getLayoutFile();
			
			if (preg_match('/\[module\]/', $layoutFile)) {
				$layoutFile = '';
				
				$modules = dir(MODULES);
				while($module = $modules->read()){
					if ($module === $this->data->controller->getModule()) {
						$layoutFile = MODULES . DS . str_replace('[module]', $module, $this->config->getLayoutFile());
					}
				}
			} else {
				$layoutFile = MODULES . DS . $layoutFile;
			}
			
			if(file_exists($layoutFile)) {
				echo $this->requireOnce($layoutFile);
			} else {
				throw new \Exception($this->translate->translate('LAYOUT_FILE_NOT_FOUND', array($layoutFile)), 1011);
			}
		} else {
			$this->renderView();
		}
	}
	
	/**
	 * 
	 * @param string $file
	 * @throws \Exception
	 */
	private function render($file)
	{
		if (file_exists($file)) {
			echo $this->requireOnce($file);
			return;
		}
		
		throw new \Exception($this->translate->translate('FILE_NOT_FOUND', array($file)), 1012);
		
		return;
	}
	
	/**
	 * 
	 * @param string $file
	 */
	public function renderLayoutFile($file)
	{
		$layoutFile = $this->config->getLayoutFile();
		if (preg_match('/\[module\]/', $layoutFile)) {
			$fileToLoad = MODULES . DS . $this->data->controller->getModule() . DS . 'views' . DS;
		} else {
			$fileToLoad = MODULES . DS . $this->config->getLayoutDir() . DS . 'views' . DS;
		}
		
		$load = $fileToLoad . 'layouts' . DS . $file;
		
		return $this->render($load);
	}
	
	/**
	 * 
	 * @param string $file
	 */
	public function renderScriptFile($file)
	{
		$layoutFile = $this->config->getLayoutFile();
		
		$load  = MODULES . DS . $this->data->controller->getModule() . DS . 'views' . DS;
		$load .= 'scripts' . DS . $file;
		
		return $this->render($load);
	}
	
	protected function log()
	{
		if (!$this->config->inProduction()) {
			$this->logSQL    = DbStorage::getInstance()->getLog();
			$this->logLoader = LoaderStorage::getInstance()->getLog();
			$this->loader->loadLogFile($this, $this->config->getLogFile());
		}
	}
	
	/**
	 * 
	 * @param string $file
	 * @return string
	 */
	private function requireOnce($file)
	{
		ob_start();
		require_once $file;
		
		return ob_get_clean();
	}
	
    private function get_headers_custom() {
         $arh = array();
		  $rx_http = '/\AHTTP_/';
		  foreach($_SERVER as $key => $val) {
		    if( preg_match($rx_http, $key) ) {
		      $arh_key = preg_replace($rx_http, '', $key);
		      $rx_matches = array();
		      // do some nasty string manipulations to restore the original letter case
		      // this should work in most cases
		      $rx_matches = explode('_', $arh_key);
		      if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
		        foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
		        $arh_key = implode('-', $rx_matches);
		      }
		      $arh[$arh_key] = $val;
		    }
		  }
		  return( $arh );
    }
}