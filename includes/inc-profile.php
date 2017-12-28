<?php
$msg = "";
if(isset($_GET['msg'])){
	$msg = preg_replace('#[^a-z0-9%]#i', '', $_GET['user']);
	$msg = str_replace('%20', ' ', $msg);
}
$po_id =
$avatar = "";
$fullName = "";
$location = "";
$birthday = "";
$friends_count = "";
$enemies_count = ""; 
$username = "";
$banner = "";
include_once("scripts/check_user.php");
if(!isset($_GET['user']) || $_GET['user'] == ""){
	header("location: oops.php?msgcode=p1");
	$db = null;
	exit();
}
if($user_is_logged != true){
	header("Location: oops.php?msgcode=p2");
	$db = null;
	exit();
}
$pageowner = preg_replace('#[^a-z0-9]#i', '', $_GET['user']);
$stmt = $db->prepare("SELECT id, ext_id, username, full_name, avatar, banner FROM members WHERE username=:pageowner AND activated='1' LIMIT 1");
$stmt->bindValue(':pageowner',$pageowner,PDO::PARAM_STR);
	try{
		$stmt->execute();
	}
	catch(PDOException $e){
		//echo $e->getMessage();
		print_r($e->getTrace());
		$db = null;
		exit();
	}
$user_exists = $stmt->rowCount();
if($user_exists == 0){
	header("location: oops.php?msgcodde=p1");
}
function isPageOwner($user, $pagename){
	if($user == $pagename){
		return true;
	}else{
		return false;
	}
}
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$po_id = $row['id'];
	$ext_id = $row['ext_id'];
	$username = $row['username'];
	$avatar = $row['avatar'];
	$fullName = $row['full_name'];
	$banner = $row['banner'];
}

?>