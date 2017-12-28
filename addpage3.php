<?php
include_once("scripts/check_user.php"); 
	include('includes/template-config.php');
/* INVALIDO TEMPORALMENTE LA NECESIDAD DE ESTAR LOGADO. PENDIENTE ASIGNAR PERMISOS A USUARIOS. */
/* 
if($user_is_logged == false){
	header("location: index.php");
	exit();
}
*/

	if( isset($_POST['new_video_pg']) ) {

//require_once ($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'videolayers' . DIRECTORY_SEPARATOR . 'v0.8' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'defines.php');
require_once ( 'includes/defines.php');
require_once (MODELS_DIR . 'pages.php');
require_once (MODELS_DIR . 'videos.php');
//require_once ($_SERVER['DOCUMENT_ROOT'] . '/videolayers/v0.7/mysql/models/' . 'pages.php');
//require_once ($_SERVER['DOCUMENT_ROOT'] . '/videolayers/v0.7/mysql/models/' . 'videos.php');
		// video
		$new_platform_id = $_POST['new_platform_id'];
		$new_video_id = $_POST['new_video_id'];		// éste es [datos_videos].platform_video_id (es decir, el id del video en YT o en VImeo. NO es el video_id de nuestra BD.
		$video_title = $_POST['video_title'];
		// layers
		$cat_1 = $_POST['cat_1'];
		$cat_2 = $_POST['cat_2'];
		$cat_3 = $_POST['cat_3'];
		$cat_4 = $_POST['cat_4'];
		// page
		$new_video_pg = $_POST['new_video_pg'];			// url página nueva
		$page_title = $_POST['page_title'];
		$page_header = $_POST['page_header'];

		if ($user_is_logged) {
			$client_id = $log_user_id;
			$client_name = $log_uname;
		}
		else {
			$client_id = 2;		//Websalacarte;
			$client_name = 'Video Layers';
		}
		

/* Tras validación de datos de entrada, insertamos en BD y obtenemos id's para página y video. */
		
		/* Empezamos con insert video */
		$std_video = new stdClass();
		$std_video->error = false;
		if( !$new_platform_id ) {
			$platform_id = 1;	// de momento sólo Vimeo
		} else {
			$platform_id = $new_platform_id;
		}
		$nombre_video_en_origen = '';	// de momento aún no lo obtenemos de la API. Pendiente.
		//echo('platform_id: ' . $platform_id . ', new_video_id (platform_video_id: ' . $new_video_id . ', nombre_origen: ' . $nombre_video_en_origen);
		if( class_exists( 'Videos' ) ) {
			$VideoInserted = Videos::insert_video( $platform_id, $new_video_id, $nombre_video_en_origen );
									//insert_video($platform_id, $platform_video_id, $nombre_video_en_origen) {
			
			if ( $VideoInserted == null ) {
				//No ha podido insertar video
				$std_video->error = true;
				$std_video->debug = 'VideoInserted es nul';
				$std_video->debug2 = 'Error en insert video';
				$std_video->videoid = null;
			}
			else {
				$std_video->error = false;
				$std_video->debug = 'VideoInserted es ' . $VideoInserted->video_id;
				$std_video->debug2 = 'Ok en insert video';
				$std_video->videoid = $VideoInserted->video_id;										// Éste SÍ es nuestro id de video en BD.
			}
			
			$std_video->debug3 = $std_video->debug2 . '\n y video_id: ' . $std_video->videoid . '.';
			
		}
		else {
			$std_video->error = true;
			$std_video->videoid = 0;
			$std_video->debug = 'else, no existen las clases';
			
		}
//		echo json_encode( $std_video );																								// no queremos echo esto, sino la página.
		/*
		//echo (' <script>console.log("std_video: ' . json_encode( $std_video ) . ' ");</script>');
		$out = json_encode( $std_video );
		echo (' <script>console.log("out: ' . $out . '");</script> ');
		*/
		
		/* Seguimos con insert page */
		$std_page = new stdClass();
		$std_page->error = false;
		
		$num_boxes = 4;	// de momento sólo 4 cajas
		// ATENCION. $userId sólo estará informado cuando usuario esté logado. Si no lo está (y le dejamos crear página), debería hacer owner = Websalacarte (userId=2).
		if ( isset($userId) ) {
			$ownerId = $userId;		// Pendiente ROLES. Si $userId no puede ser owner, modificar.
		}
		else {
			$ownerId = 2; 	// Websalacarte
		}
		
		if( class_exists( 'Pages' ) ) {
			$PageInserted = Pages::insert_page( $new_video_pg, $page_title, $page_header, $std_video->videoid, $new_platform_id, $num_boxes, $client_id, $ownerId );		// usamos el id del video de BD (no el id de YT-Vimeo).
								  //insert_page((page uri), $page_title, $page_header1, $video_id, $platform_id, $num_boxes, $creator_id, $owner_id) {
			
			if ( $PageInserted == null ) {
				//No ha podido insertar video
				$std_page->error = true;
				$std_page->debug = 'PageInserted es nul';
				$std_page->debug2 = 'Error en insert video';
				$std_page->pageid = null;
			}
			else {
				$std_page->error = false;
				$std_page->debug = 'PageInserted es ' . $PageInserted->page_id;
				$std_page->debug2 = 'Ok en insert video';
				$std_page->pageid = $PageInserted->page_id;
			}
			
			$std_page->debug3 = $std_page->debug2 . '\n y page_id: ' . $std_page->pageid . '.';
			
		}
		else {
			$std_page->error = true;
			$std_page->pageid = 0;
			$std_page->debug = 'else, no existen las clases';
			
		}

		$new_page_id = $std_page->pageid;
		//$new_video_id = $std_video->videoid;				
		
/* Tras insertar en BD, creamos página desde template y entregamos página a usuario */
		
$html_file_name = $new_video_pg . ".php";
		
$new_video_title = $page_title;
$new_h3 = $page_header;

$new_video_name = $page_header;
$video_id = $new_video_id;

//$link_main_page = '../' . $members_path . $members_video_dir;
$link_main_page = $members_path . $members_video_dir;
//$debug = 'video_id: ' . $video_id . ', $_POST["video_id"]: ' . $_POST['video_id'];
$debug = 'video_id: ' . $video_id . ', $_POST["new_video_id"]: ' . $_POST['new_video_id'];

		//Elegir template según plataforma
		if( $new_platform_id == 2 ) {
			$tpl = file_get_contents($tpl_path.$tpl_file_yt);		// tpl : template, llama al fichero "/videolayers/v0.8/index-template-yt.php"
		} else {
			$tpl = file_get_contents($tpl_path.$tpl_file);		// tpl : template, llama al fichero "/videolayers/v0.8/index-template.php"
		}
		
		// video_id = new_video_id = id en YT-Vimeo
		// $std_video->videoid es el id en nuestra BD.
		
		// Ahora debo reemplazar contenido:
			//$data['last_name'] = "Doe"; 
			//$placeholders = array("{username}", "{first_name}", "{last_name}", "{city}", "{street_address}", "{email_address}");		// the elements possition from the both arrays should match!
			//$new_member_file = str_replace($placeholders, $data, $tpl);
			$data['video_id'] = $std_video->videoid;
			$data['platform_video_id'] = $video_id;
			$data['page_title'] = $page_title;
			$data['new_h3'] = $new_h3;
			$data['client_name'] = $client_name;
			$data['cat_1'] = $cat_1;
			$data['cat_2'] = $cat_2;
			$data['cat_3'] = $cat_3;
			$data['cat_4'] = $cat_4;
			$data['new_page_id'] = $new_page_id;
			$placeholders = array("{video_id}", "{platform_video_id}", "{page_title}", "{new_h3}", "{client_name}", "{cat_1}", "{cat_2}", "{cat_3}", "{cat_4}", "{new_page_id}");
			$new_member_file = str_replace($placeholders, $data, $tpl);
		
		$fp = fopen($root_dir_sys.$members_path.$members_video_dir.$html_file_name, "w"); 
		fwrite($fp, $new_member_file); 
		fclose($fp);
		
		
		echo ('
		<!DOCTYPE html>
			<html> 
				<head> 
					<title>Add a new video page</title> ');
include_once("head_common.php"); 
echo('
				</head> 
				<body> 
					<div class="main_container">');
include_once("header_template.php"); 
echo('
						<!-- CONTAINER -->
						<h2>Add a new video page <span>Video Layers</span></h2>
		
						<div class="container">
							<div id="form">
								<h3>Your new video page has been created</h3> 
								<p>Click <a href="' . $root_dir.$members_path.$members_video_dir.$html_file_name . '">here</a> to access your new video page.</p>
								<p><br /><br /><br /><br /><br /></p>
								<p>Click <a href="' . $link_main_page . '">here</a> to go back to your Main page.</p>

							</div>
						</div>
					</div>
		');
		include_once("foot_common.php");
		echo('
				</body>
			</html>
		');


	}
	else{
	
	
		echo ('
		
			<!DOCTYPE html>
			<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<title>Add new video page - Video Layers</title>');
include_once("head_common.php"); 
echo('
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css/bootstrapValidator.css"/>

<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script> 
<script src="js/form-validacions2.js"></script>
<script type="text/javascript" src="js/bootstrapValidator.min.js"></script>
				</head>
				<body>
					<div class="main_container">');
include_once("header_template.php"); 
echo('
						<!-- CONTAINER -->
						<h2>Add a new video page <span>Video Layers</span></h2>
		
						<div class="container">
							<div id="form">
<style>
form .addpage_contenido {
	text-align: left;
}
form .addpage_contenido.tab-pane {
	min-height: 400px;
}
form .addpage_contenido h3 {
	text-align: center;
	color: #7CBCD6;		/*	#F0A108; 	*/
	margin-top: 40px;
}
form .addpage_contenido h3 span.glyphicon {
	margin-right: 10px;
	color: #7CBCD6;
}
form .addpage_contenido button#envia_formulario_boton {
	text-transform: uppercase;
	font-size: 24px;
	text-shadow: 0px 2px #333;
}
form .addpage_contenido #envia_formulario_boton span.glyphicon {
	margin-left: 20px;
}
form .addpage_contenido i.form-control-feedback.glyphicon-remove {
	color: #A94442;
}
form .addpage_contenido i.form-control-feedback.glyphicon-ok {
	color: #326233;
}
form .addpage_contenido button#envia_formulario_container {
	margin: 50px auto;
}
form .addpage_contenido button#envia_formulario_boton {
	padding: 30px;
}
form .addpage_contenido .addpage_contenido_video_imagen {
	margin-top: 50px;
}
</style>		
								<ul class="nav nav-tabs">
									<li class="active"><a href="#addpage_contenido_video" data-toggle="tab"><span class="glyphicon glyphicon-facetime-video"></span>Video</a></li>
									<li><a href="#addpage_contenido_categorias" data-toggle="tab"><span class="glyphicon glyphicon-flag"></span>Categories</a></li>
									<li><a href="#addpage_contenido_pagina" data-toggle="tab"><span class="glyphicon glyphicon-file"></span>Page</a></li>
								</ul>
								<form action="" method="post" class="form formulari_validat" id="formulari_nova_pagina">
									<div class="addpage_form_container tab-content">
										<div class="addpage_contenido tab-pane active" id="addpage_contenido_video">
											<div class="row">
												<div class="col-xs-6">
													<h3>Choose the video</h3>
												
													
													<div class="form-group">
														<label for="new_platform_id" class="control-label">Video platform</label>
														<div>
															<label class="radio-inline">
																<input type="radio" id="new_platform_id" name="new_platform_id" value="1" checked />Vimeo
															</label>
															<label class="radio-inline">
																<input type="radio" id="new_platform_id" name="new_platform_id" value="2" />Youtube
															</label>
														</div>
													</div>
													<div class="clearfix"></div>
													<div class="form-group" id="input_video_id">
														<label for="new_video_id">Video id</label>
														<input type="text" id="new_video_id" name="new_video_id" class="form-control" placeholder="Enter your video unique Vimeo id" />
													</div>
													<div class="form-group">
														<label for="video_title">Video title</label>
														<input type="text" id="video_title" name="video_title" class="form-control" placeholder="Enter your video title"/>
													</div>
												</div>
												<div class="col-xs-6 addpage_contenido_video_imagen" id="addpage_contenido_video_imagen_1">
													<img id="imagen_muestra_video_id" src="img/form_video_vimeo.jpg" width="488" height="300" />
												</div>
												<div id="imagen_video_real" style="display: none;"></div>
											</div>
										</div>
										
										<div class="addpage_contenido tab-pane" id="addpage_contenido_categorias">
											<div class="row">
												<div class="col-xs-6">
													<h3>Set your categories</h3>
													
													<div class="form-group">
														<label for="cat_1">Category 1</label>
														<input type="text" id="cat_1" name="cat_1" class="form-control" placeholder="Name for the 1st tag, subject, topic" />
													</div>
													<div class="form-group">
														<label for="cat_2">Category 2</label>
														<input type="text" id="cat_2" name="cat_2" class="form-control" placeholder="Name for the 2nd tag, subject, topic" />
														<!--<input class="form-control" type="text" name="categories[]" />-->
													</div>
													<div class="form-group">
														<label for="cat_3">Category 3</label>
														<input type="text" id="cat_3" name="cat_3" class="form-control" placeholder="Name for the 3rd tag, subject, topic" />
													</div>
													<div class="form-group">
														<label for="cat_4">Category 4</label>
														<input type="text" id="cat_4" name="cat_4" class="form-control" placeholder="Name for the 4th tag, subject, topic" />
													</div>
												</div>
												<div class="col-xs-6 addpage_contenido_video_imagen" id="addpage_contenido_video_imagen_2">
													<img src="img/form_video_categories.jpg" width="488" height="300" />
												</div>
											</div>
										</div>
										
										<div class="addpage_contenido tab-pane" id="addpage_contenido_pagina">
											<div class="row">
												<div class="col-xs-6">
													<h3>Page details</h3>
													
													<div class="form-group">
														<label for="new_video_pg">Video page filename</label>
														<input type="text" id="new_video_pg" name="new_video_pg" class="form-control" placeholder="Enter my_page, and it will become my_page.php" />
													</div>
													<div class="form-group">
														<label for="page_title">Page title</label>
														<input type="text" id="page_title" name="page_title" class="form-control" placeholder="This title goes to the browser\'s main tag" />
													</div>
													<div class="form-group">
														<label for="page_header">Page header</label>
														<input type="text" id="page_header" name="page_header" class="form-control" placeholder="This page header goes to the beginning of the page" />
													</div>
												</div>
												<div class="col-xs-6 addpage_contenido_video_imagen" id="addpage_contenido_video_imagen_2">
													<img src="img/form_video_page.jpg" width="488" height="300" />
												</div>
											</div>
										</div>
										<!--
										<div class="addpage_contenido col-xs-6" id="addpage_contenido_enviar">
										
											<div class="form-group input-lg" style="margin: 50px auto;">
												<button type="submit" id="envia_formulario" class="btn btn-default btn-primary btn-block" style="padding: 60px;">Add video page<span class="glyphicon glyphicon-circle-arrow-right"></span></button>
											</div>
											
										</div>
										-->
									</div>
									<div class="addpage_contenido row" id="addpage_contenido_enviar">
										<div id="envia_formulario_container" class="form-group input-lg">
											<button type="submit" id="envia_formulario_boton" class="btn btn-default btn-primary btn-block">Add video page<span class="glyphicon glyphicon-circle-arrow-right"></span></button>
										</div>
									</div>
								</form>
				
								<div class="clr"></div>
							</div>
							<!-- END FORM -->
						</div>
						<p class="descripcio">&copy; EMM &amp; co | more info: josep@websalacarte.com</p>
						<!-- END CONTAINER -->
					</div>

');
include_once("foot_common.php"); 
echo('

				</body>
			</html>
		');


	}


?>

