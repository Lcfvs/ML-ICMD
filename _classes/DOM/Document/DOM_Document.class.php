<?php
	/*
		The DOM Document
		
		Generate a DOM Document.
		
		@name      : DOM_Document.class.php
		@author    : Michaël Rouges <michael.rouges@gmail.com>
		@version   : 0.9
		@package   : ML-ICMD::DOM_Document
		@link      : Not yet
        @copyright : 2012 Rouges Michaël [LGPL 3] (http://www.gnu.org/licenses/lgpl-3.0.txt)
	*/
	class DOM_Document extends DOMImplementation{
		private $_document;
		private $unbreakables=array('head','title','script','style','pre','span');
		public function __construct($baseTemplate,$lang='en',$charset='utf-8',$standalone=true){
			$_document=$this->_document=$this->createDocument('','html',$this->createDocumentType('html','',''));
			$_document->load($baseTemplate->path);
			$_document->encoding=$charset;
			$_document->standalone=$standalone;
			$_document->documentElement->setAttribute('lang',$lang);
		}
		public function setNode($nodeName,$parentNode,$nextNode=null,$attributes=null,$text=null){
			$document=$this->_document;
			$node=$document->createElement($nodeName);
			if(is_array($attributes)){
				foreach($attributes as $attribute=>$value){
					$nodeAttribute=$this->_document->createAttribute($attribute);			
					$nodeAttribute->value=strip_tags($value);
					$node->appendChild($nodeAttribute);
				}
			}
			if($text!==null){
				if(!in_array($nodeName,$this->unbreakables)){
					$text=preg_split('/\n\r?/',$text);
				}
				if(is_array($text)){
					$i=-1;
					$l=count($text);
					while(++$i<$l){
						if($i>0){
							$node->appendChild($this->_document->createElement('br'));
						}
						$node->appendChild($this->_document->createTextNode($text[$i]));
					}
				}
				else{
					$node->appendChild($this->_document->createTextNode($text));
				}
			}
			if($nextNode!==null){
				$parentNode->appendChild($node,$nextNode);
			}
			else{
				$parentNode->appendChild($node);
			}
		}
		public function setUnbreakable($nodeName){
			if(!in_array($nodeName,$this->unbreakables)){
				$this->unbreakables[]=$nodeName;
			}
		}
		public function createDocumentFragmentFrom($path){
			$fragment=$this->_document->createDocumentFragment();
			$fragment->appendXML(Resource::file($path)->getContents());
			return $fragment;
		}
		public function __call($method,$arguments){
			try{
				return call_user_func_array(array($this->_document,$method),$arguments);
			}
			catch(Exception $e){
				throw new Exception('Unknown method : '.$method.' in '.__CLASS__);
			}
		}
		public function __destruct(){
			$this->_document->formatOutput=true;
			$this->_document->preserveWhiteSpace=false;
			echo $this->_document->saveHTML();
		}
	}
?>