<?php
// Además de este fichero, tenemos funciones para votos en getVotes.php


include_once("scripts/connect.php");
$num1 = "0";
$num2 = "0";
$num3 = "0";

function get_video_comments( $video_id ) {
	
	// Usamos la clase Comments.
	require_once (MODELS_DIR . 'comments.php');
	if( class_exists( 'Comments' ) ) {
		$numCommentsVideo = Comments::getTotalNumCommentsVideo($video_id);
		$debug = ' | debug: existe clase';
		return $numCommentsVideo ;
	}
	else {
		$error_getNumCommentsVideo = "0" . "<script>console.log('no encontrados comentarios para video $video_id ')";
		return $error_getNumCommentsVideo;
	}
}
function get_video_comments_box( $video_id, $box_id ) {
	
	// Usamos la clase Comments.
	require_once (MODELS_DIR . 'comments.php');
	if( class_exists( 'Comments' ) ) {
		$numCommentsVideoCaja = Comments::getNumCommentsVideoBox($video_id, $box_id);
		$debug = ' | debug: existe clase';
		return $numCommentsVideoCaja;
	}
	else {
		$error_getNumCommentsVideoCaja = "0" . "<script>console.log('no encontrados comentarios para video $video_id y caja $box_id')";
		return $error_getNumCommentsVideoCaja;
	}
}

?>