<?php
	/*
		The file download tool
		
		Return a file to the client.
		
		@name      : Resource_File_Download.class.php
		@author    : Michaël Rouges <michael.rouges@gmail.com>
		@version   : 0.9
		@package   : ML-ICMD::Resource_File_Download
		@link      : Not yet
        @copyright : 2012 Rouges Michaël [LGPL 3] (http://www.gnu.org/licenses/lgpl-3.0.txt)
	*/
	class Resource_File_Download{
		private $_file;
		private $_filename;
		private $_speed;
		private $_length;
		private $_contentDisposition;
		private $_resumable;
		private $_delay;
		private $_startRange;
		private $_endRange;
		public function __construct($file,$contentDisposition='inline',$filename=null){
			$this->_file=$file;
			$this->_contentDisposition=$contentDisposition;
			$this->_filename=$filename;
			$this->_speed=RESOURCE_FILE_DOWNLOAD_SPEED;
			$this->_length=RESOURCE_FILE_DOWNLOAD_LENGTH;
			$this->_resumable=$contentDisposition=='attachment'&&(isset($_SERVER['HTTP_RANGE'])||isset($_ENV['HTTP_RANGE']));
			$this->_delay=round(1000000*$this->_length/$this->_speed);
			$this->_startRange=0;
			$this->_endRange=$this->_file->size-1;
			$this->_start();
		}
		private function _start(){
			session_write_close();
			set_time_limit(0);
			if(ini_get('zlib.output_compression')){
				ini_set('zlib.output_compression','Off');
			}
			$this->_sendHeaders();
			$position=$this->_startRange;
			while(false!==($data=$this->_file->read($position,$this->_length))){
				echo $data;
				flush();
				$position+=$this->_length;
				usleep($this->_delay);
			}
			$this->_file->close();
			exit;
		}
		private function _sendHeaders(){
			header('Content-Disposition: '.$this->_contentDisposition.'; filename="'.$this->_file->basename.'"');
			if($this->_contentDisposition=='attachment'){
				header('Accept-Ranges: bytes');
				header('Cache-Control: no-cache, must-revalidate');
				header('Cache-Control: post-check=0,pre-check=0');
				header('Cache-Control: max-age=0');
				header('Content-Description: File Transfer');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Pragma: no-cache');
				if($this->_resumable){
					$httpRange='';
					$results=array();
					if(isset($_SERVER['HTTP_RANGE'])){
						$httpRange=$_SERVER['HTTP_RANGE'];
					}
					elseif(isset($_ENV['HTTP_RANGE'])){
						$httpRange=$_SERVER['HTTP_RANGE'];
					}
					if(preg_match('#bytes=([0-9]+)?-([0-9]+)?(/[0-9]+)?#i',$httpRange,$results)){
						$this->_startRange=!empty($results[1])?(int)$results[1]:null;
						$this->_endRange=!empty($results[2])?(int)$results[2]:$this->_endRange;
						if($this->_startRange===null){
							$this->_startRange=$this->_file->size-$this->_endRange;
							$this->_endRange-=1;
						}
						header('HTTP/1.1 206 Partial Content');
						header('Content-Range: '.$this->_startRange.'-'.$this->_endRange.'/'.$this->_file->size);
					}
					else{
						header('HTTP/1.1 416 Requested Range Not Satisfiable');
						exit;
					}
				}
			}
			header('Content-Type: '.$this->_file->mimeType);
			header('Content-Length: '.($this->_endRange-$this->_startRange+1));
		}
	}
?>