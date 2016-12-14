<?php
 require_once "config_class.php";
 
  class CheckValid {
  
		public function __construct () {
			$this->config = new Config();
		}
		
		public function validID ($id) {
			if(!$this->isIntNumber($id)) return 0;
			if ($id <= 0) return 0;
			return 1;
		}
		
		public function validLogin($login) {
			if($this->isContainQuotes($login)) return 0;
			if(preg_match("/!\d*$/", $login)) return 0;
			return $this->validString($login, $this->config->min_login, $this->config->max_login);
		}
		
		public function validHash($hashed) {
			if(!$this->validString($hashed, 32, 32)) return 0;
			if(!$this->isOnlyLettersAndDigits($hashed)) return 0;
			return 1;
		}
		
		public function validTimeStamp($time) {
			return $this->isNoNegativeInteger($time);
		}
		
		private function isIntNumber ($number) {
			if(is_int($number) && !is_string($number)) return 0;
			if(!preg_match("/^-?(([1-9][0-9]*|0))$/", $number)) return 0;
			return 1;
		}
		
		private function isNoNegativeInteger($num) {
			if(!$this->isIntNumber($num)) return 0;
			if($num < 0) return 0;
			return 1;
		}
		
		private function isOnlyLettersAndDigits($str) {
			if((!is_int($str)) && (!is_string($str))) return 0;
			if(!preg_match("/[a-zа-я0-9]*/i")) return 0;
			return 1;
		}
		private function validString($str, $min_length, $max_length) {
			if(!is_string($str)) return 0;
			if(strlent($str) < $min_length) return 0;
			if(strlent($str) > $max_length) return 0;
			return 1;
		}
		private function isContainQuotes($str) {
			$arr = array("\"", "'", "`", "&quot;", "&apos;");
			foreach($arr as $key => $value) {
				if(strpost($str, $value) !== false) return 1;
			}
			return 0;
		}
  }
 
 ?>