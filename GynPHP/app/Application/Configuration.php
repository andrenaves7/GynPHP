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

namespace Application;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class Configuration
{
	/**
	 *
	 * @var string
	 */
	const LANGUAGE = 'data/language/pt_br.lang';
	
	/**
	 * DB configuration MySQL
	 *
	 * @var array
	 */
	public $db = array(
			'adapter' => 'MySQL',
			'host'    => '',
			'port'    => '',
			'schema'  => '',
			'user'    => '',
			'pass'    => ''
	);
	
	/**
	 * 
	 * @var string
	 */
	public $root = '/GynPHP/';

	/**
	 * 
	 * @var string
	 */
	public $logDir = 'data/log/';
	
	/**
	 * Environment
	 * 
	 * @var string
	 */
	public $environment = 'development';
	
	/**
	 * 
	 * @var string
	 */
	protected $layoutFile = '[module]/views/layouts/layout.phtml';
	
	/**
	 * 
	 * @var string
	 */
	public $catastrophicErrorFile = 'Def/views/layouts/_catastrophic.phtml';
}