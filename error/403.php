<?php include_once("../includes/defines.php"); ?>
<?php include_once("../scripts/check_user.php"); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Demo 06 ( con login ) - Video Layers</title>
<?php include_once("head_common.php"); ?>

<title>You shall not pass!</title>
</head>
<body>
<div class="main_container">
<?php include_once("header_template.php"); ?>
	<!-- CONTAINER -->
	<h2>Demo 06 de <span>Video Layers</span></h2>
	<div class="container">
		<div id="form">
		   
		   
			<div class="content">
				<div class="banner">
					<h1 style="text-align:center; color:#F00;">This page is forbidden!</h1>
					<p style="text-align:center;">
						You have navigated to a page that you are not authorized to view. Whatever the reason, you should <a href="<?php echo $ruta_raiz; ?>index.php">move along now.</a>  
					</p>
				</div>
				<!---------------------->
				<div style="width:100%;">
					<div style="float:left; width:20%; height:200px;"></div>
					<div style="float:left; width:58%; padding-left:24px;"><img src="../images/403.png" alt="nopass" style="width:90%; height:448px;"/></div>
				</div>
				<div class="clearfloat"></div>
			<!-- end .content --></div>
			<div class="clearfloat"></div>
		</div>
		<!-- END FORM -->
	</div>
	<!-- END CONTAINER -->
	
	
</div>
<?php include_once("foot_common.php"); ?>

</body>
</html>