<?php
	require_once "text/globalmessages_class.php";
	
	class Message extends globalMessages {
		
		private $data;
		
		public function __construct() {
			parent::__construct("messages");
		}
		
	}
	
 ?>