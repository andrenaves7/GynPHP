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

namespace Gyn\Helper;

use Gyn\Config\Config;
use Gyn\Data\Data;
use Gyn\Language\Language;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class Helper
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
	 * @param Data $data
	 * @param Config $config
	 */
	public function __construct(Data $data, Config $config)
	{
		$this->data   = $data;
		$this->config = $config;
	}
	
	/**
	 * Primeiro método a ser executado
	 */
	public function init()
	{
		
	}
	
	/**
	 * 
	 * @param string $id
	 * @return string/NULL
	 */
	public function getValuesById($id)
	{
		if (count($this->data->helper->getValues()) > 0) {
			foreach ($this->data->helper->getValues() as $key => $val) {
				if ($key == $id) {
					return $val;
				}
			}
		}
		return null;
	}
	
	/**
	 * 
	 * @param string $id
	 * @return string
	 */
	public function getErrorsListById($id)
	{
		$html = '';
		if (count($this->data->helper->getErrors()) > 0) {
			foreach ($this->data->helper->getErrors() as $key => $errors) {
				if($key == $id) {
					$html .= '<ul class="error">';
					foreach ($errors as $error) {
						$html .= "<li>{$error}</li>";
					}
					$html .= '</ul>';
				}
			}
		}
		return $html;
	}
	
	/**
	 * 
	 * @param array $options
	 * @return string
	 */
	protected function getAttr(array $options = array())
	{
		$attr = '';
		if (count($options) > 0){
			foreach($options as $url => $opt){
				$attr .= " {$url}=\"{$opt}\"";
			}
		}
		
		return $attr;
	}
	
	/**
	 *
	 * @param string $filename
	 * @throws \Exception
	 * @return string
	 */
	protected function import($filename)
	{
		$translate = Language::getInstance();
		if (file_exists($filename)) {
			return $this->requireOnce($filename);
		} else {
			throw new \Exception($translate->translate('VIEW_FILE_NOT_FOUND', array($filename)), 1007);
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
}