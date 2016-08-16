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

namespace Gyn\Log;

use Gyn\Config\Config;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class Error extends Log
{
	/**
	 * 
	 * @param Config $config
	 * @param integer $code
	 * @param string $msg
	 */
	public function __construct(Config $config, $code, $msg)
	{
		$this->config = $config;
		$this->code   = $code;
		$this->msg    = str_replace("\n", '', $msg);
		$this->dir    = $this->config->logDir;
		$this->userIP = $_SERVER['REMOTE_ADDR'];
		$this->file   = $this->dir . 'error' . DS . date('Y-m-d') . '.csv';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Gyn\Log\Log::write()
	 */
	public function write()
	{
		if (isset($this->config->errorLog) && $this->config->errorLog === true) {
			$msg  = date('Y-m-d H:i:s') . ';' . $this->userIP . ';';
			$msg .= $this->code . ';' . $this->msg . ';' . $this->br;
				
			$this->reportMsg = $msg;

			parent::write();
		}
	}
}