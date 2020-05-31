<?php

class MngDb{
	private $_mysqli;
	
	//constructor
	public function __construct() {
		$this->_mysqli= mysqli_connect(MYSQL_IP, MYSQL_USER, MYSQL_PASSWORD, MYSQL_TABLE);
		if (mysqli_connect_errno($this->_mysqli)) {
			echo "Error Mysqli : " . mysqli_connect_error();
		}
	}
	
	//getter
	public function mysqli() {
		$this->_mysqli;
	}
	
	//method
	public function execRequest($request, $formError){
		$result=mysqli_query($this->_mysqli, $request);
		if(!$result && ERROR_USER > 0)
		{
			die("Mysql Error :".mysqli_error($this->_mysqli));
		}
		else if(!$result && ERROR_USER == 0)
		{
			die('<div class="formstatuserror">Error : '.$formError.'</div>');
		}
		return $result;
	}
	
	public function close(){
		mysqli_close($this->_mysqli);
	}
}
?>