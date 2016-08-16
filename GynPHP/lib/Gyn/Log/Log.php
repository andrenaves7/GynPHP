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

use Gyn\Log\Interfaces\LogInterface;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class Log implements LogInterface
{
	/**
	 * 
	 * @var \Gyn\Config\Config
	 */
	protected $config;
	
	/**
	 * 
	 * @var integer
	 */
	protected $code;
	
	/**
	 * 
	 * @var string
	 */
	protected $msg;
	
	/**
	 * 
	 * @var string
	 */
	protected $dir;
	
	/**
	 * 
	 * @var string
	 */
	protected $file;
	
	/**
	 *
	 * @var string
	 */
	protected $br = "\r\n";
	
	/**
	 *
	 * @var string
	 */
	protected $userIP;
	
	/**
	 *
	 * @var string
	 */
	protected $reportMsg;
	
	public function write()
	{
		// Verigy if the log dir exists
		if (!is_dir($this->dir)) {
			// Case there's no a log directory, we create one
			mkdir($this->dir);
		}
			
		// Open the file
		$fp = fopen($this->file, 'a');
			
		// Write the file
		$write = fwrite($fp, $this->reportMsg);
			
		// Close the file
		fclose($fp);
	}
}