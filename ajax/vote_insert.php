<?php
	// inserta un nuevo voto en BD.
	//		
	
	if( isset( $_POST['task']) && ($_POST['task'] == 'insert_vote')) {
		require_once ('../includes/defines.php');
		require_once (MODELS_DIR . 'votes.php');
		
		
		$userId = (int)$_POST['userId'];
		//$comment = addslashes( str_replace( "\n", "<br>", $_POST['comment'] ) );
		$commentId =  $_POST['comment_id'];
		$vidId = (int)$_POST['vidId'];
		$boxId = (int)$_POST['boxId'];
		$pageId = (int)$_POST['pageId'];
		
		$std = new stdClass();
		$std->error = false;
		
		if( class_exists( 'Votes' ) ) {
			$VoteInserted = Votes::insert_vote( $vidId, $commentId, $boxId, $pageId, $userId );
			
			if ( $VoteInserted == null ) {
				//dará problemas porque no ha podido insertar voto
				$std->error = true;
				$std->debug = 'VoteInserted es nul';
				$std->debug2 = 'Error en insert vote';
				$std->voteid = null;
			}
			else {
				$std->error = false;
				$std->debug2 = 'Ok en insert vote';
				$std->voteid = $VoteInserted->vote_id;
			}
			
			$std->vote = $VoteInserted;
			$std->debug3 = $std->debug2 . '\n userId vale: '.$userId.'\n y vote_id: ' . $std->voteid ;
			
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
		header('location: /videolayers/v0.7/');
		//header('location: /');
	}
?>