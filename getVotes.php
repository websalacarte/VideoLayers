<?php
include_once("scripts/connect.php");
$num1 = "0";
$num2 = "0";
$num3 = "0";


function get_pagina_votes( $pagina_id ) {
	
	// Usamos la clase Votos.
	require_once (MODELS_DIR . 'votes.php');
	if( class_exists( 'Votes' ) ) {
		$numVotosPagina = Votes::getNumVotesDePagina($pagina_id);
		$debug = ' | debug: existe clase';
		return $numVotosPagina ;
	}
	else {
		$error_getNumVotesPage = "0" . "<script>console.log('no encontrados votos para pagina $page ')";
		return $error_getNumVotesPage;
	}
}

function get_video_votes( $vid_id ) {
	
	/*
	$sql1 = "SELECT count(*) FROM votos_comentarios Where video_id=$vid_id"; 
	$query1 = mysql_query( $sql1 );
	//$count1 = $query1->rowCount();
	if(!$query1) {
		$num1 = 0;
	} else {
		$num1 = mysql_fetch_row($query1);
	}
	return $num1;
	*/
	// Usamos la clase Votos.
	require_once (MODELS_DIR . 'votes.php');
	if( class_exists( 'Votes' ) ) {
		$numVotosPagina = Votes::getNumVotesDeVideo($vid_id);
		$debug = ' | debug: existe clase';
		return $numVotosPagina ;
	}
	else {
		$error_getNumVotesPage = "0" . "<script>console.log('no encontrados votos para pagina $page ')";
		return $error_getNumVotesPage;
	}
}

function get_total_user_votes( $usr_id ) {
	$query2 = "SELECT voto_id FROM votos_comentarios Where user_id=$usr_id"; 
	$execute2 = $db->query($query2);
	$count2 = $query2->rowCount();
	if($execute2->rowCount() > 0){
		$num2 = $execute2->rowCount();
	}
	return $num2;
}
function get_user_votes_on_video( $usr_id, $vid_id ) {
	$sql3 = "SELECT count(voto_id) FROM votos_comentarios Where user_id=$usr_id and video_id=$vid_id"; 
	$query3 = mysql_query( $sql3 );
	if(!$query3) {
		$num3 = 0;
	} else {
		$num3 = mysql_fetch_row($query3);
	}
	return $num3;
}

function get_comm_votes( $comm_id, $vid_id ) {
	$sql4 = "SELECT count(voto_id) FROM votos_comentarios Where comment_id=$comm_id and video_id=$vid_id"; 
	$query4 = mysql_query( $sql4 );
	if(!$query4) {
		$num4 = 0;
	} else {
		$num4 = mysql_fetch_row($query4);
	}
	return $num4;
}
//insert_vote
function insert_vote( $comm_id, $vid_id, $usr_id ) {
	$num_votes = get_comm_votes( $comm_id, $vid_id );
	$sql5 = "INSERT INTO votos_comentarios VALUES ( '', $comm_id, $vid_id, $usr_id ) "; 	//INSERT INTO `votos_comentarios`(`voto_id`, `comment_id`, `video_id`, `user_id`) VALUES ([value-1],[value-2],[value-3],[value-4])
	$query5 = mysql_query( $sql5 );
	if( $execute5 ) {
		return $num_votes + 1;
	} else {
		return $num_votes;
	}	
}

//$totalNums = "$num1, $num2, $num3";
//echo "$totalNums";
?>
