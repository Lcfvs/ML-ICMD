<?php
	/*
		The File tool
		
		Find your files based on basename, but a specific site directory may be used.
		
		@name      : Resource_File.class.php
		@author    : Michaël Rouges <michael.rouges@gmail.com>
		@version   : 0.9
		@package   : ML-ICMD::Resource_File
		@link      : Not yet
        @copyright : 2012 Rouges Michaël [LGPL 3] (http://www.gnu.org/licenses/lgpl-3.0.txt)
	*/
	class Resource_File{
		// (array) All paths, full file extensions as keys
		private static $_paths;
		// (string) Public dir path
		private static $_publicDir;
		// (string) Private dir path
		private static $_privateDir;
		// (string) File path
		private $_path;
		// (string) Generic file path
		private $_target;
		// (string) Specific dir path (eg: "dir/")
		private $_targetDir;
		// (string) File basename
		private $_basename;
		// (string) File extension (eg: "php")
		private $_extension;
		// (string) File extension (eg: "inc.php", "class.php")
		private $_fullExtension;
		// (string) File mime-type
		private $_mimeType;
		// (string) File size
		private $_size;
		private $_isBinary;
		private $_handle;
		/*
			@name   : __construct
			@param  : (string) path (must matches on /^([\w\d]+\/)?[\w\d\.-_]+$/)
			@return : (void)
		*/
		public function __construct($path,array $paths,$publicDir,$privateDir=null){
			if(!isset(self::$_paths)){
				self::$_paths=$paths;
				self::$_publicDir=$publicDir;
				self::$_privateDir=$privateDir;
			}
			// Setting the required file infos
			$this->_basename=basename($path);
			$this->targetDir=implode('/',explode('/',str_replace('../','',$path),-1));
			$extensionParts=explode('.',$this->_basename);
			$this->_extension=$extensionParts[count($extensionParts)-1];
			$this->_fullExtension=implode('.',array_slice($extensionParts,1));
			$this->_target=str_replace('_','/',$extensionParts[0]).'/'.$this->_basename;
		}
		/*
			@name   : __get
			@param  : (string) property name
			@return : (string) property value
		*/
		public function __get($name){
			// Adding prefix "_" to the property name, to get the private property
			$property='_'.$name;
			if(!property_exists($this,$property)){
				throw new Exception('Unknown property : '.$name.' in '.__CLASS__);
			}
			// Calling the associated method, if the property isn't defined
			if(!isset($this->$property)){
				$methodName='_set'.ucfirst($name);
				$this->$methodName();
			}
			return $this->$property;
		}
		/*
			@name   : _setPath
			@return : (void)
		*/
		private function _setPath(){
			if(isset(self::$_paths[$this->fullExtension])){
				// Listing all paths matches on full file extension
				if($this->targetDir!=''&&array_key_exists($this->targetDir,self::$_paths[$this->fullExtension])){
					$paths=array(self::$_paths[$this->fullExtension][$this->targetDir]);
				}
				else{
					$paths=self::$_paths[$this->fullExtension];
				}
				// Finding the first file matches on file basename
				foreach($paths as $key=>$tempPath){
					$this->_path=strstr($tempPath,self::$_publicDir)||strstr($tempPath,self::$_privateDir)?$tempPath.$this->basename:$tempPath.$this->target;
					if(is_file($this->_path)){
						break;
					}
					$this->_path=null;
				}
			}
			if($this->_path==null){
				throw new Exception('Unable to find : '.$this->basename);
			}
		}
		/*
			@name   : _setMimeType
			@return : (void)
		*/
		private function _setMimeType(){
			$mimes=json_decode(RESOURCE_FILE_JSON_MIMES);
			$extension=$this->_extension;
			$this->_mimeType=isset($mimes->$extension)?$mimes->$extension:'application/octet-stream';
		}
		/*
			@name   : _setSize
			@return : (void)
		*/
		private function _setSize(){
			$this->_size=filesize($this->path);
		}
		private function _setIsBinary(){
			$finfo=new finfo(FILEINFO_MIME_TYPE);
			$this->_isBinary=strpos($finfo->file($this->path),'charset=binary');
		}
		public function load(){
			require_once($this->path);
		}
		public function getContents(){
			return file_get_contents($this->path);
		}
		public function read($startRange=0,$length){
			if(is_readable($this->path)){
				$data=false;
				$startRange=(int)abs($startRange);
				$length=abs($length);
				$remainingSize=$this->size-$startRange;
				$length=$remainingSize-$length<0?$remainingSize:$length;
				if($length>0){
					if(!isset($this->_handle)){
						$this->_handle=fopen($this->path,$this->_isBinary?'rb':'r');
					}
					fseek($this->handle,$startRange);
					$data=fread($this->_handle,$length);
				}
			}
			else{
				throw new Exception('Unable to read : '.$this->_basename);
			}
			return $data;
		}
		public function download($filename=null){
			new Resource_File_Download($this,'inline',$filename);
		}
		public function forcedDownload($filename=null){
			new Resource_File_Download($this,'attachment',$filename);
		}
		public function close(){
			if(isset($this->_handle)){
				fclose($this->_handle);
			}
		}
	}
?>
