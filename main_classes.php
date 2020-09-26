<?php
	/*
		BRIGHTER SETTING LIBRARY 
		bs-php-mysql
	*/

	//DB CONNECTOR
	require_once("db_connect.php");

	//error vars
	$incorrect_args_no="Incorrect number of arguments provided";
	/**
	 * Insert data to table
	 */
	class BS_INSERT
	{
		function bs_inserter()
		{
			//VARS
			$table="";
			$fields="";
			$data="";
			
			//SET DB CONNECTION
			$db="";

			//overloading
			if (func_num_args()==3) {
				$args=func_get_args();
				$table=$args[0];
				$fields=$args[1];
				$data=$args[2];
				//REFs
				$db=new BS_DB;
				$db=$db->bs_database_connect();
			}else if(func_num_args()==4){
				$args=func_get_args();
				$table=$args[0];
				$fields=$args[1];
				$data=$args[2];
				//REFs
				$db=new BS_DB($args[3]);
				$db=$db->bs_database_connect();
			}else{
				//die($incorrect_args_no);
			}

			//variables
			$fields_data="";
			$data_holder="";
			$exec_variable=array();

			for ($i=0; $i < count($fields); $i++) { 
				//set fields data
				if(empty($fields_data)){
					$fields_data=$fields[$i];
				}else{
					$fields_data=$fields_data.",".$fields[$i];
				}
				//set data holder
				if(empty($data_holder)){
					$data_holder=":".$fields[$i];
				}else{
					$data_holder=$data_holder.",:".$fields[$i];
				}
				//set execution data
				$exec_variable[$fields[$i]]=$data[$i];
			}
			//insert data
			$db->query("SET FOREIGN_KEY_CHECKS=0");
			$query1="INSERT INTO $table($fields_data)VALUES($data_holder)";

			$stmt=$db->prepare($query1);

			$ret="";
			try{
				if($stmt->execute($exec_variable)){
					$ret=1;
				}
			}catch(PDOException $e){
				if ($e->getCode() == 23000) {
			        $ret="entry_exists";
			    } else {
			        $ret=0;
			    }
			}
			$db->query("SET FOREIGN_KEY_CHECKS=1");
			return $ret;
		}
	}

	/**
	 * check numrows
	 */
	class BS_ROWCOUNT{
		function bs_rowcounter(){
			//SET DB CONNECTION
			$db="";

			//overloading
			if (func_num_args()==4) {
				$args=func_get_args();
				$table=$args[0];
				$data=$args[1];
				$where=$args[2];
				$whereValues=$args[3];
				//REFs
				$db=new BS_DB;
				$db=$db->bs_database_connect();
			}else if(func_num_args()==5){
				$args=func_get_args();
				$table=$args[0];
				$data=$args[1];
				$where=$args[2];
				$whereValues=$args[3];
				//REFs
				$db=new BS_DB($args[4]);
				$db=$db->bs_database_connect();
			}else{
				//die($incorrect_args_no);
			}
			
			//check numrows
			$query="SELECT $data FROM $table WHERE $where='$whereValues'";
			
			try{
				if($query=$db->query($query)){
					$ret=$query->rowCount();
				}else{
					$ret=0;
				}
			}catch(PDOException $e){
			    $ret=0;
			}
			return $ret;
		}

		function bs_allrowcounter($table,$data){
			//SET DB CONNECTION
			$db="";

			//overloading
			if (func_num_args()==2) {
				$args=func_get_args();
				$table=$args[0];
				$data=$args[1];
				//REFs
				$db=new BS_DB;
				$db=$db->bs_database_connect();
			}else if(func_num_args()==3){
				$args=func_get_args();
				$table=$args[0];
				$data=$args[1];
				//REFs
				$db=new BS_DB($args[2]);
				$db=$db->bs_database_connect();
			}else{
				//die($incorrect_args_no);
			}
			
			//insert data
			$query="SELECT $data FROM $table";

			try{
				if($query=$db->query($query)){
					$ret=$query->rowCount();
				}else{
					$ret=0;
				}
			}catch(PDOException $e){
			    $ret=0;
			}
			return $ret;
		}
		
	}
	/**
	 * Retrieve Data
	 */
	class BS_RETRIEVE
	{
		function bs_retriever()
		{
			$args="";
			$query="";
			if(func_get_args()){
				$args=func_get_args();
			}else{
				die("retriever() expects arguments");
			}

			//var
			$fetch_type="";

			//SET DB CONNECTION
			$db="";

			//overloading
			if (func_num_args()==3) { //($table,$data,$fetch_type) 
		        $table=$args[0];
		        $data=$args[1];
		        $fetch_type=$args[2];

		        $query="SELECT $data FROM $table";
		        //REFs
				$db=new BS_DB;
				$db=$db->bs_database_connect();
		    }else if (func_num_args()==4 and is_array($args[3])) { //($table,$data,$fetch_type,$db_var) 
		    	$table=$args[0];
		        $data=$args[1];
		        $fetch_type=$args[2];

		        $query="SELECT $data FROM $table";
		        //REFs
				$db=new BS_DB($args[3]);
				$db=$db->bs_database_connect();
		    }else if(func_num_args()==5){ //($table,$data,$where,$whereValues,$fetch_type)    
		    	$table=$args[0];
		        $data=$args[1];
		        $where=$args[2];
		        $whereValues=$args[3];
		        $fetch_type=$args[4];

		        $query="SELECT $data FROM $table WHERE $where = '$whereValues'";
		        //REFs
				$db=new BS_DB;
				$db=$db->bs_database_connect();
		    }else if(func_num_args()==6 and is_array($args[5])){ //($table,$data,$where,$whereValues,$fetch_type)  
		    	$table=$args[0];
		        $data=$args[1];
		        $where=$args[2];
		        $whereValues=$args[3];
		        $fetch_type=$args[4];

		        $query="SELECT $data FROM $table WHERE $where = '$whereValues'";

		    	//REFs
				$db=new BS_DB($args[5]);
				$db=$db->bs_database_connect();
		    }
		   
		    //echo $query;
		    $row="";
		    if($fetch_type=="fetch"){
		    	$sql=$db->prepare($query);
		    	$sql->execute();
				$rows=$sql->fetch();
		    }else if($fetch_type=="fetchAll"){
		    	$sql=$db->prepare($query);
		    	$sql->execute();
				$rows=$sql->fetchAll();
		    }else{
		    	echo "<p class='alert alert-danger'>Fetch type error </p>";
		    }
			

			if($rows){
				return $rows;
			}else if($sql->fetchColumn()==0){
				return $ret=1020;
			}else{
				return $ret=0;
			}
		}
	}

	/**
	 * update Data
	 */
	class BS_UPDATE
	{
		function bs_updater(){
			//SET DB CONNECTION
			$db="";

			//overloading
			if (func_num_args()==4) {
				$args=func_get_args();
				$table=$args[0];
				$data=$args[1];
				$where=$args[2];
				$whereValues=$args[3];
				//REFs
				$db=new BS_DB;
				$db=$db->bs_database_connect();
			}else if(func_num_args()==5){
				$args=func_get_args();
				$table=$args[0];
				$data=$args[1];
				$where=$args[2];
				$whereValues=$args[3];
				//REFs
				$db=new BS_DB($args[4]);
				$db=$db->bs_database_connect();
			}else{
				//die($incorrect_args_no);
			}

			//update
			$query="UPDATE $table SET $data WHERE $where='$whereValues'";
			$sql=$db->prepare($query);
			
			$error=$sql->errorInfo();

			$ret="";
			try{
				if($sql->execute()){
					$ret=1;
				}
			}catch(PDOException $e){
				if ($e->getCode() == 23000) {
			        $ret="entry_exists";
			    } else {
			        $ret=0;
			    }
			}
			return $ret;
		}
	}

	class BS_DELETE
	{
		function bs_deleter(){ //DELETE SPECIFIC COLUMN   //$table,$where,$whereValue

			//SET DB CONNECTION
			$db="";

			//overloading
			if (func_num_args()==3) {
				$args=func_get_args();
				$table=$args[0];
				$where=$args[1];
				$whereValue=$args[2];
				//REFs
				$db=new BS_DB;
				$db=$db->bs_database_connect();
			}else if(func_num_args()==4){
				$args=func_get_args();
				$table=$args[0];
				$where=$args[1];
				$whereValue=$args[2];
				//REFs
				$db=new BS_DB($args[3]);
				$db=$db->bs_database_connect();
			}else{
				//die($incorrect_args_no);
			}

			//delete
			$query="DELETE FROM $table WHERE $where = '$whereValue'";
			$sql=$db->prepare($query);

			$ret="";
			try{
				if($sql->execute()){
					$ret=1;
				}else{
					$ret=0;
				}
			}catch(PDOException $e){
			    $ret=0;
			    //echo $e;  Remove comments to see errors
			}
			return $ret;
		}
	}
?>
