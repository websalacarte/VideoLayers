<?php 
$connect = mysql_connect('{yourDBservername}', '{yourDBusername}', '{yourDBpssword}') or die("Problems!" . mysql_error());

//if (!isset ($db)) {
	$db = mysql_select_db('websalac_videolayersv09');
	//$db = mysql_select_db('websalac_voctours');					// local portatil
//}

if( !$connect ) {
}

?>
