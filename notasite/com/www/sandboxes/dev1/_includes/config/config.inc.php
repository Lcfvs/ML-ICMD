<?php
	/*
		A personalized config sample
		
		Loads the default config & sets some parameters for only one developper
		
		@name      : config.inc.php
		@author    : Michaël Rouges <michael.rouges@gmail.com>
		@version   : 0.9
		@package   : ML-ICMD::sandboxes/config
		@link      : Not yet
        @copyright : 2012 Rouges Michaël [LGPL 3] (http://www.gnu.org/licenses/lgpl-3.0.txt)
	*/
	Resource::file('root/config.inc.php')->load();
	error_reporting(E_ALL ^ E_NOTICE);
?>