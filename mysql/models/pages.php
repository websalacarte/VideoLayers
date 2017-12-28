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
$debug_dentro_de_pages = 'dentro de clase pages';

//$debug_votesphp = 'estoy en votesphp ' . $debug4;
class Pages {

	public static function insert_page($page_uri, $page_title, $page_header1, $video_id, $platform_id, $num_boxes, $creator_id, $owner_id) {
		// Insert data into DB
		$creation_date = Date( "Y-m-d" );
		$sql = "insert into paginas values('' , $page_uri, $page_title , $page_header1 , $video_id , $platform_id, $num_boxes , $creator_id , $owner_id , $creation_date )";
		// INSERT INTO `paginas`(`page_id`, `page_uri`, `page_title`, `page_header1`, `video_id`, `platform_id`, `num_boxes`, `creator_id`, `owner_id`, `creation_date`, `is_public`, `page_avatar`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12])
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (pages)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		//$query = mysql_query( $sql );
		$sql_pdo = "insert into paginas values( :page_id , :page_uri, :page_title , :page_header1 , :video_id , :platform_id, :num_boxes , :creator_id , :owner_id , :creation_date, :is_public, :page_avatar )";
		//INSERT INTO `paginas`(`page_id`, `page_uri`, `page_title`, `page_header1`, `video_id`, `platform_id`, `num_boxes`, `creator_id`, `owner_id`, `creation_date`, `is_public`, `page_avatar`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12])
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (pages)';
			//$stmt->execute();
			$stmt->execute( array(
					':page_id' => '',
					':page_uri' => $page_uri,
					':page_title' => $page_title,
					':page_header1' => $page_header1,
					':video_id' => $video_id,
					':platform_id' => $platform_id,
					':num_boxes' => $num_boxes,
					':creator_id' => $creator_id,
					':owner_id' => $owner_id,
					':creation_date' => $creation_date,
					':is_public' => 1,								// por defecto, mientras no amplíe opciones, es página pública.
					':page_avatar' => 'new-video.jpg'				// PENDIENTE coger imagen de YT/Vimeo e insertarla aquí
					));
			 if($stmt->rowCount() > 0){
				//$insert_id = mysql_insert_id();
				$insert_id = $db->lastInsertId();
					
				$std = new stdClass();
				$std->page_id = $insert_id;
				$std->debug = 'pagesphp if query ok';
				$debug_pagesphp = 'estoy en pagesphp if query';
				$debug_conexio .= 'rowCount > 0, y ' . $std->debug;
				return $std;
			 }
		}
		catch(PDOException $e){
			echo 'catch en pages!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return null;
	}
	public static function getPublicPages($pagina_publica) {
		// usado en página index para mostrar todas las páginas (públicas) actuales		
		// $pagina_publica = 1 -> página es pública. $pagina_publica = 0 sólo administradores.		// pendiente ROLES.
		// retorna las páginas 
		// (`page_id`, `page_uri`, `page_title`, `page_header1`, `video_id`, `platform_id`, `num_boxes`, `creator_id`, `owner_id`, `creation_date`, `is_public`)
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getPublicPages) ';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$sql_pdo = "select * from paginas where `is_public`=:publica ORDER BY page_id DESC ";	// select * from paginas where `is_public`=1 
		$stmt = $db->prepare($sql_pdo);
		
		try{
			$debug_conexio .= 'dins de try (getPublicPages)';
			$stmt->execute( array(
					':publica' => $pagina_publica
					));
			$result = $stmt->fetchAll();
			if ( count($result) > 0 ) { 
				return $result;
			 }
		}
		catch(PDOException $e){
			echo 'catch en getPublicPages!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return 0;
	
	}
	
	
	
	
}

?>