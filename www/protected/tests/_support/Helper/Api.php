<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Api extends \Codeception\Module
{
	public function dumpHeaders() {
		$module = $this->getModule('REST');
		return (isset($module->headers) ? $module->headers : NULL);
	}

	public function dumpParams() {
		$module = $this->getModule('REST');
		return (isset($module->params) ? $module->params : NULL);
	}

	public function dumpResponse() {
		$module = $this->getModule('REST');
		return (isset($module->response) ? $module->response : NULL);
	}

	public function getRESTConfig($key) {
		$module = $this->getModule('REST');
		return $module->_getConfig($key);
	}
}
