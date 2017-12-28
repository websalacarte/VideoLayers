<?php require_once('includes/defines.php'); ?>
<?php require_once ('mysql/models/comments.php'); ?>
<?php require_once('includes/ficheros.php'); ?>
<?php include_once("scripts/check_user.php"); ?>
<?php include_once("getVotes.php"); ?>
<?php include_once("getStats.php"); ?>
	<?php require_once (MODELS_DIR . 'pages.php'); // incluye Commenters ?>
<?php 

/**** check_user devuelve:

$user_is_logged = false;
$log_user_id = "";
$log_uname = "";
$log_pass = "";
*/
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Video Layers - Welcome</title>
<?php include_once("head_common.php"); ?>
</head>
<body>
<div class="main_container">
<?php include_once("header_template.php"); ?>
	 
	<!-- CONTAINER -->
	<h2><span>Video Layers</span> - Welcome</h2>
	<div class="container">
		<div id="form">
			<h3 class="izq">What is <span>Video Layers</span></h3>
			<p class="izq">Video Layers is a new software solution that allows video owners to include comments synchronized with their videos. With Video Layers, you comment on the scene you're watching, and you read comments regarding that such scene.</p>
			<p class="izq">Also, Video Layers allows multiple comment categories in their videos. This way, video owners and video viewers can organize their opinions to make the best of their communication.</p>
			
			<h3 class="izq">Projects</h3>
			<p class="izq">Here are the video layers projects currently available:</p>
		   
			<div class="scenarios">
				<div class="item" style="background-image:url('img/projects/new-vlideo.jpg')">
					<h3>Your project</h3>
					<a href="addpage.php" class="call">Add new page >></a>
					<p>Project created by: (you)</p>
					<p>&nbsp;</p>
				</div>
				
				
				<?php 
				
				$pages = Pages::getPublicPages(1); 
				require (INC . 'pages_box.php'); 
				
				//$num_paginas;		// count de page_id en tabla [datos_video]
				//$i;					//indice para recorrer la tabla de páginas.
				//$pag[$i];
				
				
				//for ($i = 1; $i <= $num_paginas; $i++) {
					// recorremos [datos_video], 
					// averiguamos qué páginas son públicas, y cuales son visibles para este usuario [ROLES], y quizás también temas (sectores). (De momento, sectores = all).
					// averiguamos plataforma, por si queremos distinguir con algún icono, y para controlar la interpretación de los datos.
					//obtenemos imagen_video, título_proyecto (página_header), uri, autor, (owner), (fecha creación), 
					// obtenemos también stats_video: nº categorías, nº comments, nº likes
					// echo.
				?>
				
				
				<?php //} ?>
				
				
				
				
				
			</div>
			
			<h3 class="izq">Only Youtube and Vimeo videos</h3>
			<p class="izq">This new technology is only available to Youtube and Vimeo videos.</p>
			<p class="izq">To make use of the synched comments and tagging, you need to have your video on one of these platforms: Vimeo or Youtube.</p>
			<h3 class="izq">Adding your project</h3>
			<p class="izq">Do you want your audience to try commenting your each and every scene in an organized manner?</p>
			<p class="izq">Make your own project and invite them to comment!</p>
			
			<div class="clr"></div>
			<p class="descripcio">&copy; websalacarte.com</p>
		
			
		</div>
		<!-- END FORM -->
	</div>
	<!-- END CONTAINER -->
	<input type="hidden" id="userId" value="<?php 
												//echo $userId; 
												echo $log_user_id; 
												?>" />
	<input type="hidden" id="userName" value="<?php 
												//echo $userName; 
												echo $log_uname; 
												?>" />
	
	
</div>
<?php include_once("foot_common.php"); ?>

</body>
</html>