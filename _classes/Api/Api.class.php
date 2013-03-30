<?php
	/*
		The API
		
		Loads the controller & the model
		
		@name      : Api.class.php
		@author    : Michaël Rouges <michael.rouges@gmail.com>
		@version   : 0.9
		@package   : ML-ICMD::Api
		@link      : Not yet
        @copyright : 2012 Rouges Michaël [LGPL 3] (http://www.gnu.org/licenses/lgpl-3.0.txt)
	*/
	class Api{
		private static $_initialized=false;
		public static function init($instanceInfos,$defaultTemplate){
			if(!self::$_initialized){
				self::$_initialized=true;
				$baseTemplate=Resource::file($defaultTemplate);
				$ucControllerName=ucfirst($instanceInfos['controller']);
				$controllerName='Controllers_'.$ucControllerName;
				$modelName='Models_'.$ucControllerName;
				$instance=new $controllerName(new $modelName(),$baseTemplate);
				$instance->$instanceInfos['method']($instanceInfos['params']);
			}
			else{
				throw new Exception('Class Api already initialized');
			}
			exit;
		}
	}
?>