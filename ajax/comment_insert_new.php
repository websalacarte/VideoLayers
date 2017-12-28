<?php
	// inserta un nuevo comentario en BD.
	
	if( isset( $_POST['task']) && ($_POST['task'] == 'comment_insert')) {
		//require_once ($_SERVER['DOCUMENT_ROOT'] . 'videolayers/v0.5/includes/defines.php');
		require_once ('../includes/defines.php');
		
		require_once (MODELS_DIR . 'comments.php');
		
		$userId = (int)$_POST['userId'];
		//$comment = addslashes( str_replace( "\n", "<br>", $_POST['comment'] ) );
		//$comment =  str_replace( "\n", "<br>", $_POST['comment'] );
		// LIMPIO BIEN EL COMENTARIO ANTES
		$comment = nl2br($_POST['comment']); // con esto conservo los retornos		//str_replace( "\n", "<br>", $_POST['comment'] );
		//$comment = str_replace("&amp;","&",$comment);
		$comment = htmlspecialchars ($comment, ENT_QUOTES);
		$comment = addslashes($comment);
		$comment = htmlentities($comment);	// '<' --> '&lt;'


		$vid = (int)$_POST['vidId'];
		$box = (int)$_POST['boxId'];
		//$timein = (int)$_POST['timein'];
		//$timeout = (int)$_POST['timeout'];
		$timein = $_POST['timein'];
		$timeout = $_POST['timeout'];
		// 25-12-2017 Caso xy: anyado posx, posy, width y height
		$posx 	= (!isset($_POST['posx']) ) ? '' : $_POST['posx'];
		$posy 	= (!isset($_POST['posy']) ) ? '' : $_POST['posy'];
		$width 	= (!isset($_POST['width']) ) ? '' : $_POST['width'];
		$height = (!isset($_POST['height']) ) ? '' : $_POST['height'];
		$std = new stdClass();
		$std->error = false;
		$std->debug = ' ';
		
		if( class_exists( 'Comments' ) && class_exists( 'Commenters' )) {
			//$userInfo = Commenters::getCommenter( $userId );
			$userInfo = Commenters::getMember( $userId );
			
			if ( $userId == null ) {
				//dará problemas porque no ha podido seleccionar
				$std->error = true;
				$std->debug = 'userid es nul';
			}
			if ( $userInfo == null ) {
				//dará problemas porque no ha podido obtener commenter
				$std->error = true;
				$std->debug = 'userinfo es nul';
			}
			
			//$commentInfo = Comments::insert( $comment , $userId );	// retornará null si problemas
			$commentInfo = Comments::insert_con_vid_y_box( $comment , $userId , $vid , $box , $timein , $timeout , $posx , $posy , $width , $height);
			if( $commentInfo == null ) {
				//dará problemas porque no ha podido insertar
				$std->error = true;
				$std->debug .= 'commentInfo es nul';
			}
			
			$std->user = $userInfo;
			$std->comment = $commentInfo;	// tendré timein en $std->comment->timein (es decir, $commentInfo->timein), etc.
			//$std->debug3 = 'ok en insert. \n userid vale: '.$userId.'\n y data_user: ' . $userInfo->profile_img . '     comment vale: ' . $comment . '\n y data_comment: ' . $commentInfo->comment_id . '    debug2 vale: '.$debug_commentsphp.'.';
			$std->debug3 = 'ok en insert. \n userid vale: '.$userId.'\n y data_user: ' . $userInfo->avatar . '     comment vale: ' . $comment . '\n y data_comment: ' . $commentInfo->comment_id . '    debug vale: '. $std->debug . '.';
			
		}
		else {
			$std->error = true;
			$std->user = 1;
			$std->comment = 'estoy en _else_';
			$std->debug = 'else, no existen las clases';
			
		}
		
		echo json_encode( $std );
		
	}
	else  {
		//echo "GET request";
		header('location: '.$protocolo_no_ssl.$ruta_dominio.$path_vl);	//header('location: /videolayers/v0.8/');				// debería ser página de origen de la llamada -> pagina_id
		//header('location: /');
	}


?>