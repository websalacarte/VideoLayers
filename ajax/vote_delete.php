<?php
	if( isset( $_POST['task']) && ($_POST['task'] == 'delete_vote')) {
		//require_once ($_SERVER['DOCUMENT_ROOT'] . 'videolayers/v0.7/includes/defines.php');
		require_once ('../includes/defines.php');
		require_once (MODELS_DIR . 'votes.php');
		
		$userId = (int)$_POST['userId'];
		$commentId =  $_POST['comment_id'];
		$vidId = (int)$_POST['vidId'];
		$pageId = (int)$_POST['pageId'];
		$debug_votos = '';
		
		$std = new stdClass();
		$std->error = false;
		
		if( class_exists( 'Votes' ) ) {
		//deleteVote($video, $comment, $page, $user)
			$VoteDeleted = Votes::delete_Vote( $vidId, $commentId, $pageId, $userId, '' );	// añado debug_votos para error en delete_voto (el error está en getVoteId y me falta visibilidad)
			
			if ( $VoteDeleted == null ) {
				//no ha podido borrar voto
				$std->error = true;
				$std->debug = 'VoteDeleted es nul';
				$std->debug2 = 'Error en delete vote. debug_votos: ' . $debug_votos . 'userId: ' . $userId . ', comment: ' . $commentId . ', vidId: ' . $vidId . ', pageId: ' . $pageId;
				$std->voteid = null;
			}
			else {
				$std->error = false;
				$std->debug2 = 'Ok en delete vote';
				//$std->voteid = $VoteDeleted->vote_id;
				$std->voteid = $VoteDeleted;
			}
			
			$std->vote = $VoteDeleted;
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