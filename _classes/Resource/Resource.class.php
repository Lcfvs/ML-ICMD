<?php
	/*
		The Ressource tool
		
		A super model involving access to all types of resources (files, databases, RSS, cURL, ...).
		
		@name      : Resource.class.php
		@author    : Michaël Rouges <michael.rouges@gmail.com>
		@version   : 0.9
		@package   : ML-ICMD::Resource
		@link      : Not yet
        @copyright : 2012 Rouges Michaël [LGPL 3] (http://www.gnu.org/licenses/lgpl-3.0.txt)
	*/
	class Resource{
		private static $_ready=false;
		private static $_paths;
		private static $_publicDir;
		private static $_privateDir;
		private static $_ressourceTypePaths;
		private static $_reflection;
		private static function init(){
			self::$_reflection=new ReflectionClass(__CLASS__);
			function __autoload($classname){
				try{
					Resource::file($classname.'.class.php')->load();
				}
				catch(Exception $e){
					throw new Exception('Class not found : '.$className);
				}
			}
		}
		private static function setPaths(array $sitePaths,array $extPaths,$publicDir,$privateDir=null,$sandboxId=null){
			// If a sandbox ID is specified, adding the sandbox path
			if($sandboxId!==null){
				$sitePaths=array_reverse($sitePaths,true);
				$sitePaths['sandbox']=$sitePaths['subdomain'].'sandboxes/'.$sandboxId.'/';
				$sitePaths=array_reverse($sitePaths,true);
			}
			// dressing the list of all paths, by full file extensions
			foreach($extPaths as $extension=>$directory){
				foreach($sitePaths as $pathKey=>$sitePath){
					self::$_paths[$extension][$pathKey]=$sitePath.$directory;
				}
			}
			self::$_publicDir=$publicDir;
			self::$_privateDir=$privateDir;
		}
		private static function startSession(){
			// Starting & securing the session
			session_start();
			session_regenerate_id(true);
		}
		private static function config($configFilename){
			self::file($configFilename)->load();
			self::$_ready=true;
		}
		public static function file($path){
			$className=__CLASS__.'_File';
			require_once(__DIR__.'/File/'.$className.'.class.php');
			return new $className($path,self::$_paths,self::$_publicDir,self::$_privateDir);
		}
		public static function __callStatic($name,$args){
			if((self::$_ready==false&&($name=='init'||self::$_reflection->getMethod($name)->isPrivate()))||self::$_reflection->getMethod($name)->isPrivate()){
				call_user_func_array(__CLASS__.'::'.$name,$args);
			}          
		}
	} 
?>
