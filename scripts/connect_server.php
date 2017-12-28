<?php
//session_start();

// Usualy "localhost" but could be different on different servers
$db_host = "localhost";
// Place the username for the MySQL database here
$db_username = "websalac_vladmin";	//"lvti_admin"; // "websalac_vladmin";		//"USERNAME"; 
// Place the password for the MySQL database here
$db_pass = "8464vdEmm";						//"PASSWORD"; 
// Place the name for the MySQL database here
$db_name = "websalac_videolayersv09";	//"websalac_voctours";	//"websalac_videolayers";			//"DB_NAME";
try{
	$db = new PDO('mysql:host='.$db_host.';dbname='.$db_name,$db_username,$db_pass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
}
catch(PDOException $e){
	echo "No ha pogut establir la connexio a la db\n";
	echo $e->getMessage();
	exit();
}
