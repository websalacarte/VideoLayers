<?php 
include_once("scripts/check_user.php");
if($user_is_logged == true){
	header("location: index.php");
	exit();
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<title>Signup Method</title>
<?php include_once("head_common.php"); ?>

<script type="text/javascript">
function relocate(url){
	window.location = url;
}
</script>

</head>
<body>
<div class="main_container">
<?php include_once("header_template.php"); ?>
	 
	<!-- CONTAINER -->
	<h2>Signup page</h2>
	<div class="container">
		<div id="form">
			<div class="banner">
				<h3 style="text-align:center; color:#333;">Please select your registration method below</h3>
			</div>
		  <!---------------------->
			<div style="width:100%; text-align:center;">
				<p class="submit"><button id="here" onclick="relocate('register.php')">Register using Websalacarte</button></p>
				<p class="submit"><button id="fb" onclick="relocate('fb_register.php')">Register using Facebook</button></p>
				<p class="submit"><button id="gp" onclick="relocate('gplus_register.php')">Register using Google+</button></p>
			</div>
			<div class="clearfloat"></div>
		</div>
  <!-- end .container -->
  </div>
</div>


<?php include_once("foot_common.php"); ?>

</body>
</html>