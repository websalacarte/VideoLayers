<?php require_once('includes/defines.php'); ?>
<?php require_once ('mysql/models/comments.php'); ?>
<?php require_once('includes/ficheros.php'); ?>

<?php include_once("scripts/check_user.php"); ?>
<?php include_once("getVotes.php"); ?>
<?php include_once("getStats.php"); ?>
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
				<div class="item" style="background-image:url('img/projects/railway.jpg'); background-position: 50% 50%;">
					<h3>Railway Station</h3>
					<a href="railway5.php" class="call">View this project >></a>
					<p>Project created by: Websalacarte</p>
					<p class="stats_video"><span class="dades"><i class="fa fa-tags"></i>: 4</span><span class="dades"><i class="fa fa-comments-o"></i>: <?php $num_comms = get_video_comments(43853702); echo(  $num_comms ); ?></span><span class="dades"><i class="fa fa-thumbs-up"></i>:  <?php $num_votes = get_pagina_votes( 7 ); echo(  $num_votes ); ?></span></p>
				</div>
				<div class="item" style="background-image:url('img/projects/ploren-les-campanes-bg1.jpg'); background-position: 50% 50%;">
					<h3>Ploren les campanes</h3>
					<a href="plorencampanes.php" class="call">View this project >></a>
					<p>Project created by: Websalacarte</p>
					<p class="stats_video"><span class="dades"><i class="fa fa-tags"></i>: 4</span><span class="dades"><i class="fa fa-comments-o"></i>: <?php $num_comms = get_video_comments(94333292); echo(  $num_comms ); ?></span><span class="dades"><i class="fa fa-thumbs-up"></i>:  <?php $num_votes = get_pagina_votes( 5 ); echo(  $num_votes ); ?></span></p>
				</div>
				<div class="item" style="background-image:url('img/projects/russianvipparty.jpg'); background-position: 50% 50%;">
					<h3>Russian VIP Party</h3>
					<a href="russianvipparty.php" class="call">View this project >></a>
					<p>Project created by: Websalacarte</p>
					<p class="stats_video"><span class="dades"><i class="fa fa-tags"></i>: 4</span><span class="dades"><i class="fa fa-comments-o"></i>: <?php $num_comms = get_video_comments(89292050); echo(  $num_comms ); ?></span><span class="dades"><i class="fa fa-thumbs-up"></i>:  <?php $num_votes = get_pagina_votes( 4 ); echo(  $num_votes ); ?></span></p>
				</div>
				<div class="item" style="background-image:url('img/projects/447722010_640x360.jpg'); background-position: 50% 50%;">
					<h3>Kevin Spacey speech</h3>
					<a href="spacey.php" class="call">View this project >></a>
					<p>Project created by: Websalacarte</p>
					<p class="stats_video"><span class="dades"><i class="fa fa-tags"></i>: 4</span><span class="dades"><i class="fa fa-comments-o"></i>: <?php $num_comms = get_video_comments(73589408); echo(  $num_comms ); ?></span><span class="dades"><i class="fa fa-thumbs-up"></i>:  <?php $num_votes = get_pagina_votes( 3 ); echo(  $num_votes ); ?></span></p>
				</div>
				<div class="item" style="background-image:url('img/projects/451743905_640x480.jpg')">
					<h3>Aisha</h3>
					<a href="aisha.php" class="call">View this project >></a>
					<p>Project created by: Websalacarte</p>
					<p class="stats_video"><span class="dades"><i class="fa fa-tags"></i>: 4</span><span class="dades"><i class="fa fa-comments-o"></i>: <?php $num_comms = get_video_comments(76792382); echo(  $num_comms ); ?></span><span class="dades"><i class="fa fa-thumbs-up"></i>:  <?php $num_votes = get_pagina_votes( 2 ); echo(  $num_votes ); ?></span></p>
				</div>
				<div class="item" style="background-image:url('img/projects/alqvimia-bg.jpg'); background-position: 50% 50%;">
					<h3>Demo for Alqvimia</h3>
					<a href="alqvimia.php" class="call">View this project >></a>
					<p>Project created by: John Taylor</p>
					<p class="stats_video"><span class="dades"><i class="fa fa-tags"></i>: 4</span><span class="dades"><i class="fa fa-comments-o"></i>: <?php $num_comms = get_video_comments(24819045); echo(  $num_comms ); ?></span><span class="dades"><i class="fa fa-thumbs-up"></i>: <?php $num_votes = get_pagina_votes( 1 ); echo(  $num_votes ); ?></span></p>
				</div>
				
			</div>
			
			<h3 class="izq">Only Vimeo videos</h3>
			<p class="izq">This new technology is only available to Vimeo videos.</p>
			<p class="izq">To make use of the synched comments and tagging, you need to have your video on Vimeo.</p>
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