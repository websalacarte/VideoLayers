<?php
/*
* getComments(): select tots els comentaris a taula comentarios
* getCommentsVideoBox($video, $box): select comentaris de video_id i box_id de taula comentarios_video_box
* insert_con_vid_y_box( $comment_txt, $userId, $vidId, $boxId , $timein , $timeout): inserta comentari a taula comentarios_video_box 
* insert( $comment_txt, $userId ): inserta commentari a taula comentarios
* update( $data ): pendent. Actualitzaria comentari. (Permetria editar a l'usuari).
* delete( $commentId ): esborra comentari commentId de la taula comentarios_video_box
*/
require_once('../../includes/defines.php');	// /home5/websalac/public_html/videolayers/v0.8/includes/defines.php
//require_once (MODELS_DIR .  'commenters.php');
//require_once (MODELS_DIR .  'comments.php');
$debug_dentro_de_videos = 'dentro de clase videos';
//require_once(SCRIPT_DIR . "connect.php");
global $db;
$debug_conexio = '';
//$debug_votesphp = 'estoy en videosphp ' . $debug4;
class Videos {

	public static function insert_video($platform_id, $platform_video_id, $nombre_video_en_origen) {
		// Insert data into DB
		//$sql = "insert into datos_video values('' , $platform_id , $platform_video_id , $nombre_video_en_origen )";
		$sql = "insert into datos_video values($platform_video_id , $platform_id , $platform_video_id , $nombre_video_en_origen )";										/* TEMPORALMENTE: mientras no abrimos a otras plataformas, el video_id puede ser el platform_video_id.*/
		// INSERT INTO `datos_video`(`video_id`, `platform_id`, `platform_video_id`, `nombre_video_en_origen`) VALUES ([value-1],[value-2],[value-3],[value-4])
		
		//$query = mysql_query( $sql );
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		$sql_pdo = "insert into datos_video values( :video_id , :platform_id , :platform_video_id , :nombre_video_en_origen )";	
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try';
			//$stmt->execute();
			$stmt->execute( array(
					':video_id' => '',			// era: $platform_video_id,		pero ya tenemos YT y video_id =/= platform_video_id.
					':platform_id' => $platform_id,
					':platform_video_id' => $platform_video_id,
					':nombre_video_en_origen' => $nombre_video_en_origen
					));
			 if($stmt->rowCount() > 0){
				//$insert_id = mysql_insert_id();
				//$insert_id = $platform_video_id;					// $db->lastInsertId();	(cuando tengamos listas las distintas plataformas). Antes, video_id = platform_video_id para el objeto video de html.
				$insert_id = $db->lastInsertId();
				
				$std = new stdClass();
				$std->video_id = $insert_id;
	/*			$std->comment = $comment;
				$std->userId = (int)$user;
				$std->vidId = (int)$video;
				$std->boxId = (int)$box;
				$std->pageId = (int)$page;*/
				$std->debug = 'videosphp if query ok';
				$debug_pagesphp = 'estoy en videosphp if query';
				$debug_conexio .= 'rowCount > 0, y ' . $std->debug;
				return $std;
			 }
		}
		catch(PDOException $e){
			echo 'catch!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
	/*
		$query = $db->query($sql);
		
		if( $query ) {
			$insert_id = mysql_insert_id();
			
			$std = new stdClass();
			$std->video_id = $insert_id;
			$std->debug = 'videosphp if query ok';
			$debug_pagesphp = 'estoy en videosphp if query';
			return $std;
		}
	*/	
		return null;
	}
	
	
	
	
}

?>