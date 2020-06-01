<?php

class MngUser
{
	private $_id;
    private $_login; 
	private $_pass;
	private $_md5Pass;
	private $_email;
	private $_gm; 
	private $_dateToUse;
	
	public function __construct($id, $login, $pass, $email) {
		$this->_id=$id;
		$this->_login=$login;
		$this->_pass=$pass;
		$this->_email=$email;
		$this->_md5pass=md5($pass);
		$this->_dateToUse = date('Y-m-d H:i:s');
	}
	
	//getter
	public function id (){
		return $this->_id;
	}
	public function login (){
		return $this->_login;
	}
	
	public function pass (){
		return $this->_pass;
	}
	public function md5Pass (){
		return $this->_md5Pass;
	}
	public function email (){
		return $this->_email;
	}
	public function gm (){
		return $this->_gm;
	}
	public function dateToUse(){
		return $this->_dateToUse;
	}
	
	//setter
	public function setGm ($gm){
		if(is_bool($gm)){
			$this->_gm=$gm;
		}
		else{
			trigger_error('gm must be a boolean', E_USER_WARNING);
		}
	}
	public function setId ($id){
		if(is_int($id)){
			  $this->_id=$id;
		}
		else{
			trigger_error('id must be an integer', E_USER_WARNING);
		}
	}
}

?>