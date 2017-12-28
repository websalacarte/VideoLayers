<?php include_once("../includes/defines.php"); ?>
<?php include_once("../scripts/check_user.php"); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Demo 06 ( con login ) - Video Layers</title>
<?php include_once("head_common.php"); ?>

<title>Página no encontrada</title>
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
					<h1 style="text-align:center; color:#F00;">404 Page Not Found!</h1>
					<p style="text-align:center;">
						You have found a page that no longer exists... Or never existed at all. It could be that you clicked on a broken link... Or it could be that you were just typing random nonsense into your browsers address bar. 
						Either way, you should <a href="<?php echo $ruta_raiz; ?>">move along now.</a> 
					</p>
				</div>
				<!---------------------->
				<div style="width:100%;">
					<div style="float:left; width:20%; height:200px;"></div>
					<div style="float:left; width:58%;"><img src="../images/404.png" alt="not found" style="width:100%;"/></div>
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