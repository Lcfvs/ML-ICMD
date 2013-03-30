<?php
	/*
		The home controller (sample)
		
		The default controller
		
		@name      : Controllers_Home.class.php (sample)
		@author    : Michaël Rouges <michael.rouges@gmail.com>
		@version   : 0.9
		@package   : ML-ICMD::Controllers_Home (sample)
		@link      : Not yet
        @copyright : 2012 Rouges Michaël [LGPL 3] (http://www.gnu.org/licenses/lgpl-3.0.txt)
	*/
	class Controllers_Home{
		private $_model;
		private $_baseTemplate;
		private $_document;
		public function __construct($model,$baseTemplate){
			$this->_model=$model;
			$this->_baseTemplate=$baseTemplate;
		}
		public function init(){
			$this->_document=new DOM_Document($this->_baseTemplate);
			$fragment=$this->_document->createDocumentFragmentFrom('headers_test.tpl');
			$this->_document->getElementsByTagName('header')->item(0)->appendChild($fragment);
		}
	}
?>