<?php
$success_msg =  '';
include_once("scripts/check_user.php");
if($user_is_logged == true){
	header("location: index.php");
	exit();
}
function ArrayBinder(&$pdoStatement, &$array){
	foreach($array as $k=>$v){
		$pdoStatement->bindValue(':'.$k,$v);
	}
}
	$msg = "";
//<<<<<<< HEAD
	$app_id = "283275678516776";
	$app_secret = "93dbbb2c2d33cdcda89065dc23d41a60";
	$my_url = "http://www.websalacarte.com/videolayers/v0.7/fb_register.php";
//>>>>>>> d7b5d743b014f1907be088907051fd23c0c121c4
	///////////////////////////////////////////////
	$code = $_REQUEST["code"];

   if(empty($code)) {
     $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
     $dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
       . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
       . $_SESSION['state'] . "&scope=email,user_about_me&fields=id";

     echo("<script> top.location.href='" . $dialog_url . "'</script>");
   }
    if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
     $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       . "&client_secret=" . $app_secret . "&code=" . $code;

     $response = file_get_contents($token_url);
     $params = null;
     parse_str($response, $params);

     $_SESSION['access_token'] = $params['access_token'];

     $graph_url = "https://graph.facebook.com/me?access_token=" 
       . $params['access_token'];

     $user = json_decode(file_get_contents($graph_url));
	 $picture="https://graph.facebook.com/me/picture?access_token=".$_SESSION['access_token']."&type=normal";
	 $ext_id = $user->id;
	 $name = $user->name;
	 $email = $user->email;
	 if(empty($ext_id) || empty($name) || empty($email)){
		 echo "Sorry, there was an error proceccing your Facebook data. Please try signing up in again later";
		 exit();
	 }
	 $ip = getenv('REMOTE_ADDR');
     $ip = preg_replace('#[^0-9.]#i', '', $ip);
   	/////////////////////////////////////////////////
	$has_password = false;
	$pass_stmt = $db->prepare("SELECT password from members WHERE ext_id=:ext_id LIMIT 1");
	$pass_stmt->bindValue(':ext_id',$ext_id,PDO::PARAM_INT);
	try{
		$pass_stmt->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		$db = null;
		exit();
	}
	 if($pass_stmt->rowCount() > 0){
		 echo "That Facebook user is already in our system. Someone has either stolen your Facebook password and email, or you have already linked this account to a profile you own here.";
		 $db = null;
		 exit();
	 }
	$stmt = $db->prepare("SELECT email FROM members WHERE email=:email LIMIT 1");
	$stmt->bindValue(':email',$email,PDO::PARAM_STR);
	try{
	$stmt->execute();
	$count = $stmt->rowCount();
	}
	catch(PDOException $e){
		echo $e->getMessage();
			$db = null;
			exit();
	}
	if($count > 0){
		echo "Sorry, that email is already in use in the system";
		$db = null;
		exit();
	}
	$userForm = '<div id="form" class="form">
	<label for="displayName">
	<form id="signup_form"><strong>How your name will appear</strong>
<br />
<input type="text" id="displayName" name="displayName" value="'.$user->name.'">
</label>
<br />
<label for="email"><strong>Your email</strong>
<br />
<input type="text" id="email" name="email" value="'.$user->email.'">
</label>
<input type="hidden" id="ext_id" name="ext_id" value="'.$user->id.'">
<br />
<br />
<p class="submit">
<button id="signUpBtn" type="submit" onclick="return false;">Next</button>
</form></div>';
}else{
	echo 'Error retrieving your Facebook data. Please try later, or try signing up normally without your Facebook account.';
	$db = null;
	exit();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Register Your VideoLayers Account</title>
	<?php include_once("head_common.php"); ?>
	
<!--<link rel="stylesheet" href="style/style.css"/>-->
<script type="text/javascript" src="js/serialize.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>
</head>

<div class="main_container">
<?php include_once("header_template.php"); ?>
	 
	<!-- CONTAINER -->
	<h2>Register through <span>Facebook</span></h2>
	<div class="container">
		<div id="form">
		
			  <div class="contentBottom">
				<h2 style="text-align:center;">Facebook login.</h2>
				<p id="message_span">To register your VideoLayers account using your Facebook profile information click the "Next" button below. The data we have gathered from Facebook, your email address, public id, and basic details like first and last name are used solely 
				to identify you here on the site.</p>
				<br />
				<br />
				<?php echo $userForm ?>
			  </div>
			  
		</div>
		<div class="clearfloat"></div>
	<!-- end .container -->
	</div>
</div>
<script>
document.getElementById('signUpBtn').onmousedown = function(){
	ajax('signup_form',
		 'POST', 
		 'ext_signup.php', 
		 'form'
	)};
</script>
<?php include_once("foot_common.php"); ?>

</body>
</html>
