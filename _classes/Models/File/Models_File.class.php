<?php
	/*
		The File model
		
		The model for file downloads (forced or not).
		
		@name      : Models_File.class.php
		@author    : Michaël Rouges <michael.rouges@gmail.com>
		@version   : 0.9
		@package   : ML-ICMD::Models_File
		@link      : Not yet
        @copyright : 2012 Rouges Michaël [LGPL 3] (http://www.gnu.org/licenses/lgpl-3.0.txt)
	*/
	class Models_File extends Resource{
		public function getFile($path){
			return self::file($path);
		}
		public function getFileName($basename){
			return $basename;
		}
	}
?>