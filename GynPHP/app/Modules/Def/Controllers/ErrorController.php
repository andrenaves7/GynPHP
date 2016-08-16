<?php

namespace Def\Controllers;

use Gyn\Mvc\Controller;

class ErrorController extends Controller
{
	public function indexAction($code = null, $msg = null)
	{
		$showError = true;
		
		if ($this->config->inProduction()) {
			$showError = false;
		}
		
		$this->view->showError = $showError;
		$this->view->code      = $code;
		$this->view->msg       = $msg;
	}
}