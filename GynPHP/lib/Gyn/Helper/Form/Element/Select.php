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

namespace Gyn\Helper\Form\Element;

use Gyn\Helper\Interfaces\HelperInterface;
use Gyn\Helper\Helper;

/**
 *
 * @license new BSD
 * @author Andre Naves
 */
class Select extends Helper implements HelperInterface
{
	/**
	 * 
	 * @param string $id
	 * @param array $values
	 * @param array $options
	 * @param string $selected
	 * @return string
	 */
	public function select($id, array $values = array(), array $options = array(), $selected = null)
	{
		$attr  = $this->getAttr($options);
		$erros = '';
	
		$erros    = $this->getErrorsListById($id);
		$select   = "<select id=\"{$id}\" name=\"{$id}\" {$attr}>";
		$selected = $selected != null? $selected: $this->getValuesById($id);
		if (count($values) > 0) {
			foreach ($values as $url => $val) {
				$isSelected = '';
				if ($selected == $url) {
					$isSelected = ' selected="selected"';
				}
				$select .=  "<option value=\"{$url}\"{$isSelected}>{$val}</option>";
			}
		}
		$select .= "</select>{$erros}";
	
		return $select;
	}
}