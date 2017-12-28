<?php
	//include_once('../../scripts/connect.php');



	function get_db_vars(&$db_host, &$db_username, &$db_pass, &$db_name) {
		// Usualy "localhost" but could be different on different servers
		$db_host = "localhost";
		// Place the username for the MySQL database here
		$db_username = "websalac_vladmin"; // "lvti_admin";		//websalac_vladmin";		//"USERNAME"; 			// lvti_admin
		// Place the password for the MySQL database here
		$db_pass = "8464vdEmm";						//"PASSWORD"; 
		// Place the name for the MySQL database here
		//$db_name = "voctours";			//"DB_NAME";		// websalac_videolayers
		$db_name = "websalac_voctours";			//"DB_NAME";		// websalac_videolayers
	}
	get_db_vars($db_host, $db_username, $db_pass, $db_name);

	if ( !class_exists ('DB') ) {
		class DB {
			public function __construct() {
				//$mysqli = new mysqli('localhost', 'root', '8464vd', 'voctours');
				get_db_vars($db_host, $db_username, $db_pass, $db_name);
				$mysqli = new PDO('mysql:host='.$db_host.';dbname='.$db_name,$db_username,$db_pass, 
						    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));					// cyrillic support !!!! 
							$mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$mysqli->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8; SET CHARACTER SET utf8; SET SESSION collation_connection = utf8_general_ci;');
				
				if (isset($mysqli->connect_errno)) {
					printf("Connect failed %s\n", $mysqli->connect_error);
					exit();
				}
				
				$this->connection =  $mysqli;
				
				
			}
			
			public function insert($query) {				
				$result = $this->connection->query($query);
				
				return $result;
			}
			
			public function update($query) {
				$result = $this->connection->query($query);
				
				return $result;
			}
			
			public function select($query) {							
				$result = $this->connection->query($query);
				
				if ( !$result ) {
					return false;
				}
				
				while ( $obj = $result->fetch_object() ) {
					$results[] = $obj;
				}
				
				return $results;
			}
		}
	}
	
	$db = new DB;
?>