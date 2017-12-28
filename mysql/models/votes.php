<?php
/*
* getComments(): select tots els comentaris a taula comentarios
* getCommentsVideoBox($video, $box): select comentaris de video_id i box_id de taula comentarios_video_box
* insert_con_vid_y_box( $comment_txt, $userId, $vidId, $boxId , $timein , $timeout): inserta comentari a taula comentarios_video_box 
* insert( $comment_txt, $userId ): inserta commentari a taula comentarios
* update( $data ): pendent. Actualitzaria comentari. (Permetria editar a l'usuari).
* delete( $commentId ): esborra comentari commentId de la taula comentarios_video_box
*/

require_once (MODELS_DIR .  'commenters.php');
require_once (MODELS_DIR .  'comments.php');
$debug_dentro_de_votes = 'dentro de votes';

//$debug_votesphp = 'estoy en votesphp ' . $debug4;
class Votes {

	public static function insert_vote($video, $comment, $box, $page, $user) {
		// Insert data into DB
		
		/*
		$sql = "insert into votos_comentarios values('' , $comment , $video , $user , $box , $page )";
		// INSERT INTO `votos_comentarios`(`voto_id`, `comment_id`, `video_id`, `user_id`, `box_id`, `page_id`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6])
		
		$query = mysql_query( $sql );
		
		if( $query ) {
			$insert_id = mysql_insert_id();
			
			$std = new stdClass();
			$std->vote_id = $insert_id;
			$std->comment = $comment;
			$std->userId = (int)$user;
			$std->vidId = (int)$video;
			$std->boxId = (int)$box;
			$std->pageId = (int)$page;
			$std->debug = 'votesphp if query ok';
			$debug_commentsphp = 'estoy en votesphp if query';
			return $std;
		}
		
		return null;
		*/
		
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (insert_vote) ';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$output = array();
		$sql_pdo = "insert into votos_comentarios values( :voto_id , :comment_id , :video_id , :user_id , :box_id , :page_id )";
		// INSERT INTO `votos_comentarios`(`voto_id`, `comment_id`, `video_id`, `user_id`, `box_id`, `page_id`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6])
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (insert_vote)';
			//$stmt->execute();
			$result = $stmt->execute( array(
					':voto_id' => '',
					':comment_id' => $comment,
					':video_id' => $video,
					':user_id' => $user,
					':box_id' => $box,
					':page_id' => $page
					));
			//$result = $stmt->fetchAll();
			//if ( count($result) > 0 ) { 
			if ( $stmt->rowCount() > 0 ) { 
				$debug_conexio .= 'count positiu (insert_vote)';
				$std = new stdClass();
				$nuevo_voto_id = $db->lastInsertId();
				$debug_conexio .= 'count positiu (insert_vote), nuevo_voto_id: ' . $nuevo_voto_id ;
				
				$std->voto_id = $nuevo_voto_id;
				$std->comment_id = $result['comment_id'];				// es un id, no un texto.
				$std->video_id = (int)$result['video_id'];
				$std->user_id = (int)$result['user_id'];
				$std->box_id = (int)$result['box_id'];
				$std->page_id = (int)$result['page_id'];
				
				$std->debug = $debug_conexio;
				//print_r($result);
				$output[] = $std;

			 }
		}
		catch(PDOException $e){
			echo 'catch en insert_vote!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
		
		
		
	}
	
	public static function getVoteId($video, $comment, $page, $user, $debug_votos) {
	//public static function getNumVotesDeComment($video, $comment, $box, $page, $user) {
	/*
		$votos = array();
		//$sql = "select * from votos_comentarios where video_id=$video and comment_id=$comment and page_id=$page";	// en realidad, con comment_id basta. Video_id y página_id contarán cuando sea para todos los comentarios (de este video en todas las páginas, o de este video y página)
		$sql_get = "select vote_id from votos_comentarios where video_id=$video and comment_id=$comment and page_id=$page and user_id=$user";	
		$debug_votos = $sql_get;
		$query_get = mysql_query( $sql_get );
		if ( $query_get ) {
			$debug_votos .= ', $query_get not null';
			$votos = mysql_fetch_array($query_get);
			$debug_votos .= ', votos[0]: ' . $votos[0];
			return $votos[0];
		}
		$debug_votos .= ', $query_get null';
		return null;
	*/
				
		$votos = array();	// aunque select devuelve sólo el vote_id, nada más.
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getVoteId) ';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$sql_pdo = "select vote_id from votos_comentarios where video_id=:video and comment_id=:comment and page_id=:page and user_id=:user";
		// INSERT INTO `votos_comentarios`(`voto_id`, `comment_id`, `video_id`, `user_id`, `box_id`, `page_id`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6])
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getVoteId)';
			//$stmt->execute();
			$stmt->execute( array(
					//':voto_id' => '',
					':comment' => $comment,
					':video' => $video,
					':user' => $user,
					//':box_id' => $box,
					':page' => $page
					));
			$votos = $stmt->fetch();
			if ( count($votos) ) { 
				return $votos[0];
			 }
		}
		catch(PDOException $e){
			echo 'catch en getVoteId!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		return null;
	}
	
	public static function delete_Vote($video, $comment, $page, $user, $debug_votos) {
		//$voto_a_borrar = select vote_id from votos_comentarios where todo coincida.
		
		$voto_a_borrar = Votes::getVoteId($video, $comment, $page, $user, $debug_votos);
		
/*		
		if ( $voto_a_borrar ) {
			$sql = "delete from votos_comentarios where vote_id=$voto_a_borrar";
			$query = mysql_query( $sql );
			if ( $query ) {
				return true;
			}
		}
		return $debug_votos;
*/		
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (delete_Vote) ';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		$debug_conexio .= ', voto_a_borrar: ' . $voto_a_borrar;
		if ( $voto_a_borrar ) {
			$sql_pdo = "delete from votos_comentarios where vote_id=:voto_a_borrar";
			// INSERT INTO `votos_comentarios`(`voto_id`, `comment_id`, `video_id`, `user_id`, `box_id`, `page_id`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6])
			$stmt = $db->prepare($sql_pdo);
			try{
				$debug_conexio .= ', dins de try (delete_Vote)';
				//$stmt->execute();
				$res_delete = $stmt->execute( array(
						':voto_a_borrar' => $voto_a_borrar
						));
				//$res_delete = $stmt->fetch();
				if ( $res_delete > 0	) { 	// debe ser 1
					return 1;
				 }
			}
			catch(PDOException $e){
				echo 'catch en delete_Vote!!, y debug_conexio: ' . $debug_conexio;
				echo 'Error: ' . $e->getMessage();
				return 0;
			}
			return 0;
		}
	}
	
	public static function getNumVotesDeComment($comment) {
	//public static function getNumVotesDeComment($video, $comment, $box, $page, $user) {
/*	
		//$sql = "select * from votos_comentarios where video_id=$video and comment_id=$comment and page_id=$page";	// en realidad, con comment_id basta. Video_id y página_id contarán cuando sea para todos los comentarios (de este video en todas las páginas, o de este video y página)
		$sql = "select * from votos_comentarios where comment_id=$comment";	
			// INSERT INTO `votos_comentarios`(`voto_id`, `comment_id`, `video_id`, `user_id`, `box_id`, `page_id`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6])
		$query = mysql_query( $sql );
		
		if( $query ) {
			$output = mysql_num_rows( $query );
			return $output;
		}
		return null;
*/		
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getNumVotesDeComment) ';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		
		$sql_pdo = "select * from votos_comentarios where comment_id=:comment";
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getNumVotesDeComment)';
			//$stmt->execute();
			$stmt->execute( array(
					':comment' => $comment
					));
			$result = $stmt->fetchAll();
			if ( count($result) > 0 ) { 
				return count($result);
			 }
		}
		catch(PDOException $e){
			echo 'catch en getNumVotesDeComment!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return null;
	
	}
	
	public static function getNumVotesDeCommentDeUser($comment, $user) {
	//para marcar Like cuando escribimos página, si usuario ya ha votado este comentario previamente.
	
/*		//$sql = "select * from votos_comentarios where video_id=$video and comment_id=$comment and page_id=$page";	// en realidad, con comment_id basta. Video_id y página_id contarán cuando sea para todos los comentarios (de este video en todas las páginas, o de este video y página)
		$sql = "select * from votos_comentarios where comment_id=$comment and user_id=$user";	
			// INSERT INTO `votos_comentarios`(`voto_id`, `comment_id`, `video_id`, `user_id`, `box_id`, `page_id`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6])
		$query = mysql_query( $sql );
		
		if( $query ) {
			$output = mysql_num_rows( $query );
			return $output;
		}
		return null;
*/		
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getNumVotesDeCommentDeUser) ';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		
		$sql_pdo = "select * from votos_comentarios where comment_id=:comment and user_id=:user";
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getNumVotesDeCommentDeUser)';
			//$stmt->execute();
			$stmt->execute( array(
					':comment' => $comment,
					':user' => $user
					));
			$result = $stmt->fetchAll();
			if ( count($result) > 0 ) { 
				return count($result);
			 }
		}
		catch(PDOException $e){
			echo 'catch en getNumVotesDeCommentDeUser!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return null;
	
	}

	public static function getNumVotesDeVideo($video) {
		// De momento, coincidirá con getNumVotesDePagina. 
		// Cuando repitamos un mismo video en varias páginas, podremos hacer Stats por video también.
/*		
		$sql = "select * from votos_comentarios where video_id=$video";	
			// INSERT INTO `votos_comentarios`(`voto_id`, `comment_id`, `video_id`, `user_id`, `box_id`, `page_id`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6])
		$query = mysql_query( $sql );
		
		if( $query ) {
			$output = mysql_num_rows( $query );
			return $output;
		}
		return null;
*/		
		
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getNumVotesDeVideo) ';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$sql_pdo = "select * from votos_comentarios where video_id=:video";
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getNumVotesDeVideo)';
			$stmt->execute( array(
					':video' => $video
					));
			$result = $stmt->fetchAll();
			if ( count($result) > 0 ) { 
				return count($result);
			 }
		}
		catch(PDOException $e){
			echo 'catch en getNumVotesDeVideo!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return null;
	
	
	}

	public static function getNumVotesDePagina($page) {
		// usado en página index para contabilizar todos los votos de una página (video ... pero si el video se usa en más páginas, sólo de esta página).
/*		
		$sql = "select * from votos_comentarios where page_id=$page";	
			// INSERT INTO `votos_comentarios`(`voto_id`, `comment_id`, `video_id`, `user_id`, `box_id`, `page_id`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6])
		$query = mysql_query( $sql );
		
		if( $query ) {
			$output = mysql_num_rows( $query );
			return $output;
		}
		return null;
*/		
		
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getNumVotesDePagina) ';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$sql_pdo = "select * from votos_comentarios where page_id=:page";
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getNumVotesDePagina)';
			$stmt->execute( array(
					':page' => $page
					));
			$result = $stmt->fetchAll();
			if ( count($result) > 0 ) { 
				return count($result);
			 }
		}
		catch(PDOException $e){
			echo 'catch en getNumVotesDePagina!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return 0;
	
	}

	public static function getNumVotesDeUsuario($user) {
		// Para Stats, o quizás Perfil de usuario.
/*		
		$sql = "select * from votos_comentarios where user_id=$user";	
			// INSERT INTO `votos_comentarios`(`voto_id`, `comment_id`, `video_id`, `user_id`, `box_id`, `page_id`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6])
		$query = mysql_query( $sql );
		
		if( $query ) {
			$output = mysql_num_rows( $query );
			return $output;
		}
		return null;
*/		
		
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getNumVotesDeUsuario) ';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$sql_pdo = "select * from votos_comentarios where user_id=:user";
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getNumVotesDeUsuario)';
			$stmt->execute( array(
					':user' => $user
					));
			$result = $stmt->fetchAll();
			if ( count($result) > 0 ) { 
				return count($result);
			 }
		}
		catch(PDOException $e){
			echo 'catch en getNumVotesDeUsuario!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return null;
	
	}
	
	
	
}

?>