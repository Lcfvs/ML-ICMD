<?php
	/*
		Instructor file
		
		Context initialization
		
		@name      : index.php
		@author    : Michaël Rouges <michael.rouges@gmail.com>
		@version   : 0.9
		@package   : ML-ICMD::index.php
		@link      : Not yet
        @copyright : 2012 Rouges Michaël [LGPL 3] (http://www.gnu.org/licenses/lgpl-3.0.txt)
	*/
	// $_SERVER['PHP_AUTH_USER']='dev1';
	require_once('./_classes/Resource/Resource.class.php');
	Resource::init();
	Resource::setPaths($_SERVER['sitePaths'],$_SERVER['extPaths'],$_SERVER['sharedPaths']['publicDir'],$_SERVER['sharedPaths']['privateDir'],isset($_SERVER['PHP_AUTH_USER'])?$_SERVER['PHP_AUTH_USER']:null);
	Resource::startSession();
	Resource::config($_SERVER['configFilename']);
	Api::init($_SERVER['instanceInfos'],'base.tpl');
	header('HTTP/1.0 404 Not Found');
	exit;
?>
