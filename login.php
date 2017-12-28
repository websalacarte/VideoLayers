<?php
include_once("scripts/check_user.php"); 
if($user_is_logged == true){
	header("location: index.php");
	exit();
}
if(isset($_POST['email']) && trim($_POST['email']) != ""){
	$email = strip_tags($_POST['email']);
	$password = $_POST['password'];
	$hmac = hash_hmac('sha512', $password, file_get_contents('secret/key.txt'));
	$stmt1 = $db->prepare("SELECT id, username, password FROM members WHERE email=:email AND activated='1' LIMIT 1");
	$stmt1->bindValue(':email',$email,PDO::PARAM_STR);
	try{
		$stmt1->execute();
		$count = $stmt1->rowCount();
		if($count > 0){
			while($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
				$uid = $row['id'];
				$username = $row['username'];
				$hash = $row['password'];
			}
			if (crypt($hmac, $hash) === $hash) {
				$db->query("UPDATE members SET lastlog=now() WHERE id='$uid' LIMIT 1");
				$_SESSION['uid'] = $uid;
				$_SESSION['username'] = $username;
				$_SESSION['password'] = $hash;
				setcookie("id", $uid, strtotime( '+30 days' ), "/", "", "", TRUE);
				setcookie("username", $username, strtotime( '+30 days' ), "/", "", "", TRUE);
    			setcookie("password", $hash, strtotime( '+30 days' ), "/", "", "", TRUE); 
				/* echo 'Valid password<br />'.$_SESSION['uid'].'<br />'.$_SESSION['username'].'<br />'.$_SESSION['password'].'
				<br />'.$_COOKIE['id']; */
				header("location: index.php");
				exit();
			} else {
				echo 'Invalid password. Press back and try again<br />';
				exit();
			}
		}
		else{
			echo "A user with that email address does not exist here";
			$db = null;
			exit();
		}
	}
	catch(PDOException $e){
		echo $e->getMessage();
		$db = null;
		exit();
	}
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Login - Video Layers</title>
<?php include_once("head_common.php"); ?>

		

</head>
<body>
<div class="main_container">
<?php include_once("header_template.php"); ?>
	 
	<!-- CONTAINER -->
	<h2>Log in <span>Video Layers</span></h2>
	
	<div class="container">
		<div id="form">
			
			<form action="" method="post" class="form">
				<h3>Log In</h3>
				<strong>Email</strong><br />
				<input type="text" name="email">
					<br />
					<strong>Password</strong><br />
				<input type="password" name="password">
					<br />
					<br />
				<p class="submit">
					<button type="submit">Log In</button>
				</p>
			</form>
			
			
			<div id="usuaris_test">
				<p>For demo purposes, we have set these test users for you to try:</p>
				<table class="taula_usuaris">
					<tr>
						<th>Name</td>
						<th>email</td>
						<th>password</td>
					</tr>
					<tr>
						<td>Jane Doe</td>
						<td>test-3@websalacarte.com</td>
						<td>test</td>
					</tr>
					<tr>
						<td>John Smith</td>
						<td>test-4@websalacarte.com</td>
						<td>test</td>
					</tr>
					<tr>
						<td>James Taylor</td>
						<td>test-5@websalacarte.com</td>
						<td>test</td>
					</tr>
					<tr>
						<td>Mary Smith</td>
						<td>test-9@websalacarte.com</td>
						<td>test</td>
					</tr>
				</table>
			</div>
			<div class="clr"></div>
		</div>
		<!-- END FORM -->
	</div>
	<p class="descripcio">&copy; EMM &amp; co | more info: josep@websalacarte.com</p>
	<!-- END CONTAINER -->
</div>


<?php include_once("foot_common.php"); ?>

</body>
</html>
