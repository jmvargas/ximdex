<?php

use Ximdex\Authenticator;


define('XIMDEX_XIMNOTA_PATH', dirname(__FILE__) . '/..');

require_once(XIMDEX_ROOT_PATH . '/inc/patterns/Factory.class.php');

class ximNOTAServer {

	// Class constants
	var $_USER = 'ximdex';
	var $_PASS = 'ximetrix';
	var $_CODE = '-1000';

	// Private class attributes
	var $method = null;
	var $action = null;
	var $pathToFile = null;
	var $pathToXml = null;
	var $pathToXimdex = null;
	var $nameFileMaster = null;
	var $pathInXimdex = null;
	
	function ximNOTAServer($command, $params) {
	
		//MigratePair(ruta_fich_pdf, ruta_fich_xml, destino)
		//PublicatePair(nombre_nodo_pdf, destino_pdf)
	
		$this->method = $command;
		$this->action = 'Action' . ucfirst($this->method);
		// MigratePair API
		foreach($params as $key => $value) {
			switch ($key) {
				//migration
				case 'ruta_fich_pdf':
					$this->pathToFile = $value;
				break;
				case 'ruta_fich_xml':
					$this->pathToXml = $value;
				break;
                                case 'destino':
					$this->pathToXimdex = $value;
                                break;
				//publication
                                case 'nombre_nodo_pdf':
					$this->nameFileMaster = $value;
                                break;
                                case 'destino_pdf':
					$this->pathInXimdex = $value;
                                break;

			}
		}
	}
	
	// private
	function val($name) {
		$value = isset($_GET[$name]) ? $_GET[$name] : null;
		if ($value === null) {
			$value = isset($_POST[$name]) ? $_POST[$name] : null;
		}
		return $value;
	}

	// private
	function output($resp, $message='') {
		$output = array(
			"<command>{$this->method}</command>",
			"<parameters>",
			"<ruta_fich_pdf>{$this->pathToFile}</ruta_fich_pdf>",
			"<ruta_fich_xml>{$this->pathToXml}</ruta_fich_xml>",
			"<destino>{$this->pathToXimdex}</destino>",
			"<nombre_nodo_pdf>{$this->pathToFile}</nombre_nodo_pdf>",
			"<destino_pdf>{$this->pathToXml}</destino_pdf>",
			"</parameters>",
			"<response>$resp</response>",
			"<message>$message</message>",
		);
		$output = sprintf('<ximNOTA>%s</ximNOTA>', implode('', $output));
		return $output;
	}

	// public
	function callAction() {
	
		$authenticator = new Authenticator();
		if ($authenticator->login($this->_USER, $this->_PASS)) {
			XSession::set('context', 'ximdex');
		} else {
			return $this->output($this->_CODE, 'Login failed');
		}
		
		if (!method_exists($this, $this->method)) {
	
			return $this->output($this->_CODE, "Method {$this->method} not found");
		}
	
		$factory = new Factory(sprintf('%s/actions/%s', XIMDEX_XIMNOTA_PATH, strtolower($this->method)), 'Action');
		$instance = $factory->instantiate(ucfirst($this->method));
	
		if ($instance === null) {
			return $this->output($this->_CODE, "Class {$this->action} not found");
		}
	
		if (method_exists($instance, $this->method)) {
			
			$method = $this->method;
			return $this->$method($instance);
		} else {
	
			return $this->output($this->_CODE, "Method {$this->method} not found");
		}
	
	}
	
	// private
	function migratePair(&$instance) {

			$ret = $instance->migratePair($this->pathToFile, $this->pathToXml, $this->pathToXimdex);
			$output = array(
				"<command>migratePair</command>",
				"<parameters>",
				"<ruta_fich_pdf>{$this->pathToFile}</ruta_fich_pdf>",
				"<ruta_fich_xml>{$this->pathToXml}</ruta_fich_xml>",
				"<destino>{$this->pathToXimdex}</destino>",
				"</parameters>",
				"<response>$ret</response>",
				"<message></message>",
			);
			$output = sprintf('<ximNOTA>%s</ximNOTA>', implode('', $output));
			return $output;
	}
	
	// private
	function publicatePair(&$instance) {

			$ret = $instance->publicatePair($this->nameFileMaster, $this->pathInXimdex);
			$output = array(
				"<command>publicatePair</command>",
				"<parameters>",
				"<nombre_nodo_pdf>{$this->nameFileMaster}</nombre_nodo_pdf>",
				"<destino_pdf>{$this->pathInXimdex}</destino_pdf>",
				"</parameters>",
				"<response>$ret</response>",
				"<message></message>",
			);
			$output = sprintf('<ximNOTA>%s</ximNOTA>', implode('', $output));
			return $output;
	}

}

?>
