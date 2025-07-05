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

namespace Gyn\Loader;

use Gyn\Loader\Interfaces\LoaderInterface;
use Gyn\Mvc\Controller\DataController;
use Gyn\Mvc\View;
use Gyn\Language\Language;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class Loader implements LoaderInterface
{
	/**
	 *
	 * @var \Gyn\Mvc\Controller\DataController
	 */
	protected $data;

	public $view;
	
	/**
	 * 
	 * @param DataController $data
	 */
	public function __construct(DataController $data)
	{
		$this->data = $data;
	}
	
	/**
	 * 
	 * @param View $view
	 * @param string $logFile
	 * @throws \Exception
	 */
	public function loadLogFile(View $view, $logFile)
	{
		$this->view = $view;
		
		if (preg_match('/\[module\]/', $logFile)) {
			$modules = dir(MODULES);
			while($module = $modules->read()){
				if ($module === $this->data->getModule()) {
					$logFile = MODULES . DS . str_replace('[module]', $module, $logFile);
				}
			}
		} else {
			$logFile = MODULES . DS . $logFile;
		}
		
		if(file_exists($logFile)) {
			return require_once $logFile;
		} else {
			$translate = Language::getInstance();
			throw new \Exception($translate->translate('LOG_FILE_NOT_FOUND', array($logFile)), 1022);
		}
	}
}