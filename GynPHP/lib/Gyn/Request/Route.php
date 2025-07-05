<?php

namespace Gyn\Request;

use Gyn\Config\Config;
use Gyn\Mvc\Controller\DataController;

class Route
{
	/**
	 *
	 * @var array
	 */
	private $delimiters = array('-', '.', '_');

	private $config = null;

	private $data = null;

	/**
	 *
	 * @param DataController $data
	 */
	public function __construct(Config $config, DataController $data)
	{
		$this->config = $config;
		$this->data   = $data;
	}

	/**
	 *
	 * @param string $route
	 * @param string $module
	 * @param string $controller
	 */
	public function setRoute($route, $module, $controller)
	{
		$uri = str_replace($this->config->root, '', $_SERVER['REQUEST_URI']);
		$uri = explode('/', $uri);

		if (isset($uri[0]) && $uri[0] == $route) {
			unset($uri[0]);
			$this->data->setModule($this->prepareModule($module));
			$this->data->setController($controller);
			$this->data->setControllerClass($this->prepareController($module, $controller));
			$this->data->setParams($this->prepareParams($uri));
		}
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