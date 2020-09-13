<?php
//BRIGHTER SETTING LIBRARY DB CONNECT
/**
 * Database connection
 */


//DEFAULT DB INFO
$db_host="localhost";	//put your database host here
$db_name="crud_test";	//put your database name here
$db_user="root";	//put your database user here
$db_password="";	//put your database user password here

class BS_DB
{
	public $db;

	function __construct()
	{
		//GLOBALS
		global $db_host;
		global $db_name;
		global $db_user;
		global $db_password;
		
		$args="";
		if(func_get_args()){
			$args=func_get_args();
		}
		if (func_num_args()==1) {
			$db_connect_var=$args[0];

			$db_host=$db_connect_var[0];
			$db_user=$db_connect_var[1];
			$db_password=$db_connect_var[2];
			$db_name=$db_connect_var[3];
		}

		try{
			$this->db=new PDO("mysql:host=$db_host;dbname=$db_name",$db_user,$db_password);
			$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $this->db;
		}catch(Exception $e){
			echo $e;   //remove the comment to see the actual error
			die("Sorry, Something went wrong. Try again later");
		}
	}

	function bs_database_connect(){
	    return $this->db;
	}
}

?>