<?php
	if( isset( $_POST['task']) && ($_POST['task'] == 'comment_delete')) {
		//echo "This page was loaded only by a POST request";
		//require_once ($_SERVER['DOCUMENT_ROOT'] . 'videolayers/v0.8/includes/defines.php');
		require_once ('../includes/defines.php');

		require_once (MODELS_DIR . 'comments.php');
		
		//echo "I am on the server side and heard your request";
		//echo $debug_dentro_de_comments;
		
		if( class_exists( 'Comments' ) ) {
			if( Comments::delete( $_POST['comment_id']) ) {
				echo "true";
			}
		}
		echo "false";
	}	
?>