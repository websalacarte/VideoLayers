<?php 
//session_start();
include_once('scripts/check_user.php');
include_once('includes/defines.php');				// añadido porque no veía $ruta_raiz.

$log_link = "";
if($user_is_logged == true){
	$query = $db->query("SELECT avatar, full_name FROM members WHERE id='$log_user_id'");
	if($query->rowCount() > 0){
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$avatar = $row['avatar'];
			$full_name = $row['full_name'];
		}
		if($full_name != ""){
			$member = $full_name;
		}else{
			$member = $log_uname;
		}
		
		/*
		SI LOGADO
			<div id="login" class="login-container">	
				<section class="main">
					<div class="wrapper-demo">
						<div id="dd" class="wrapper-dropdown-5" tabindex="1">(NAME + PICTURE)
							<ul class="dropdown">
								<li><a href="#"><i class="icon-user"></i>Profile</a></li>
								<!-- <li><a href="#"><i class="icon-cog"></i>Settings</a></li> -->
								<li><a href="#"><i class="icon-remove"></i>Log out</a></li>
							</ul>
						</div>
					</div>
				</section>
			</div>	
			$log_link2 = '<div id="login" class="login-container"><section class="main"><div class="wrapper-demo">';
			$log_link2 .= '<div id="dd" class="wrapper-dropdown-5" tabindex="1">' . $member;
			$log_link2 .= '&nbsp;&nbsp;<img src="members/' . $log_user_id . '/' . $avatar . '" alt="' . $log_uname . '" width="200" height="200" />';
			$log_link2 .= '<ul class="dropdown">';
			$log_link2 .= '<li><a href="#"><i class="icon-user"></i>Profile</a></li>':
			$log_link2 .= '<li><a href="#"><i class="icon-remove"></i>Log out</a></li>';
			$log_link2 .= '</ul></div></div></section></div>';
		*/
		/*
		SI NO LOGADO
			<div id="login" class="login-container">	
				<section class="main">
					<div class="wrapper-demo">
						<div id="dd" class="wrapper-dropdown-5" tabindex="1">("LOGIN")
						</div>
						<div id="dd" class="wrapper-dropdown-5" tabindex="1">("SIGN UP")
							<ul class="dropdown">
								<li><a href="#"><i class="icon-user"></i>REGISTER WITH US</a></li>
								<li><a href="#"><i class="icon-cog"></i>REGISTER WITH FACEBOOK</a></li>
								<li><a href="#"><i class="icon-remove"></i>REGISTER WITH G+</a></li>
							</ul>
						</div>
					</div>
				</section>
			</div>	
		*/
		
		if($avatar != '' && file_exists("members/$log_user_id/$avatar")){
			$log_link = '<span id="user_top"><a href="#" onclick="return false;" onmousedown="showToggle(\'drop_box\')">'.$member.'&nbsp;&nbsp;<img src="members/'.$log_user_id.'/'.$avatar.'" alt="'.$log_uname.'" width="200" height="200" /></a></span>';
			
			$log_link2 = '<div id="logo" class="logo"><a href="'.$ruta_raiz.'"><img src="imatges/logo-peque.png" width="110" height="55" /></a></div>';
			$log_link2 .= '<div id="login" class="login-container"><section class="main"><div class="wrapper-demo">';
			$log_link2 .= '<div id="dd" class="wrapper-dropdown-5" tabindex="1">' . $member;
			$log_link2 .= '&nbsp;&nbsp;<img src="members/' . $log_user_id . '/' . $avatar . '" alt="' . $log_uname . '" width="50" height="50" />';
			$log_link2 .= '<ul class="dropdown">';
			//$log_link2 .= '<li><a href="profile.php?user=' . $log_uname . '"><i class="icon-user"></i>Profile</a></li>';
			$log_link2 .= '<li><a href="#"><i class="icon-user"></i>Profile</a></li>';
			$log_link2 .= '<li><a href="logout.php"><i class="icon-remove"></i>Log out</a></li>';
			$log_link2 .= '</ul></div></div></section></div><div class="clr"></div>';
		}else{
			$log_link = '<span id="user_top"><a href="#" onclick="return false;" onmousedown="showToggle(\'drop_box\')">'.$member.'&nbsp;&nbsp;<img src="images/default_avatar.png" alt="'.$log_uname.'" width="50" height="50" /></a></span>';
			
			$log_link2 = '<div id="logo" class="logo"><a href="'.$ruta_raiz.'"><img src="imatges/logo-peque.png" width="110" height="55" /></a></div>';
			$log_link2 .= '<div id="login" class="login-container"><section class="main"><div class="wrapper-demo">';
			$log_link2 .= '<div id="dd" class="wrapper-dropdown-5" tabindex="1">' . $member;
			$log_link2 .= '&nbsp;&nbsp;<img src="images/default_avatar.png" alt="' . $log_uname . '" width="50" height="50" />';
			$log_link2 .= '<ul class="dropdown">';
			//$log_link2 .= '<li><a href="profile.php?user=' . $log_uname . '"><i class="icon-user"></i>Profile</a></li>';
			$log_link2 .= '<li><a href="#"><i class="icon-user"></i>Profile</a></li>';
			$log_link2 .= '<li><a href="logout.php"><i class="icon-remove"></i>Log out</a></li>';
			$log_link2 .= '</ul></div></div></section></div><div class="clr"></div>';
		}
	}
}
else{
	$log_link = '<span class="submit" style="color:#F8F8F8;"><button onclick="window.location=\'login.php\'">Log In</button>
	&nbsp;&nbsp;&nbsp;<button onclick="window.location=\'signup_method.php\'">Sign Up</button></span>';
			
			$log_link2 = '<div id="logo" class="logo"><a href="'.$ruta_raiz.'"><img src="imatges/logo-peque.png" width="110" height="55" /></a></div>';
			$log_link2 .= '<div id="login" class="login-container"><section class="main"><div class="wrapper-demo">';
			$log_link2 .= '<div id="dd2" class="wrapper-dropdown-5 nodrop" tabindex="1"><a href="login.php">LOGIN</a></div>';
			$log_link2 .= '<div id="dd" class="wrapper-dropdown-5" tabindex="1">Sign Up';
			$log_link2 .= '<ul class="dropdown">';
			$log_link2 .= '<li><a href="register.php"><i class="icon-user"></i>Register with us</a></li>';
			$log_link2 .= '<li><a href="fb_register.php"><i class="fa fa-facebook"></i>Register with Facebook</a></li>';
			$log_link2 .= '<li><a href="gplus_register.php"><i class="fa fa-google-plus"></i>Register with Google+</a></li>';
			$log_link2 .= '</ul></div></div></section></div><div class="clr"></div>';
}
?>
<div class="header">
	<?php echo $log_link2; ?>
	<br class="clearfloat" />
</div>


<script>
function showToggle(e){
	var target = document.getElementById(e);
	if(target.style.display == 'none'){
		target.style.display = 'block';
	}else{
		target.style.display = 'none';
	}
}
</script>