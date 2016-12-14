<?php
 require_once "global_class.php";
 
 class User extends GlobalClass{
	public function __construct($db) {
		parent::__construct("users", $db);
	}
	
	public function addUser($login, $password, $regdate, $email) {
		if(!$this->checkValid($login, $pass, $regdate, $email)) return false;
		return $this->add(array("login" => $login, "password" => $password, "regdate" => $regdate, "email" => $email));
	}
	
	public function editUser($id, $login, $pass, $regdate, $email) {
		if(!$this->checkValid($login, $pass, $regdate, $email)) return 0;
		return $this->edit($id, array("login" => $login, "password" => $pass, "regdate" => $regdate, "email" => $email));
	}
	
	public function isExist($login) {
		return $this->isExists("login", $login);
	}
	
	public function getUserByLogin($login) {
		$id = $this->getField("id", "login", $login);
		return $this->get($id);
	}
	private function checkValid($login, $password, $regdate, $email) {
		if(!$this->valid->validLogin($login)) return 0;
		if(!$this->valid->validHash($password)) return 0;
		if(!$this->valid->validTimeStamp($regdate)) return 0;
		return true;
	}
 }

?>