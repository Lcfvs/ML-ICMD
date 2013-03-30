<?php
	/*
		The File controller
		
		The controller for file downloads (forced or not).
		
		@name      : Controllers_File.class.php
		@author    : Michaël Rouges <michael.rouges@gmail.com>
		@version   : 0.9
		@package   : ML-ICMD::Controllers_File
		@link      : Not yet
        @copyright : 2012 Rouges Michaël [LGPL 3] (http://www.gnu.org/licenses/lgpl-3.0.txt)
	*/
	class Controllers_File{
		private $_model;
		private $_baseTemplate;
		private $_document;
		public function __construct($model,$baseTemplate){
			$this->_model=$model;
			$this->_baseTemplate=$baseTemplate;
		}
		public function download($path){
			try{
				$file=$this->_model->getFile(str_replace('-','/',$path));
				$file->download($file->basename);
			}
			catch(Exception $e){}
		}
		public function forcedDownload($path){
			try{
				$file=$this->_model->getFile(str_replace('-','/',$path));
				$file->download($file->basename);
			}
			catch(Exception $e){}
		}
	}
?>