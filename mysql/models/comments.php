<?php
/*
* getComments(): select tots els comentaris a taula comentarios
* getCommentsVideoBox_vtt($video, $box): igual que getCommentsVideoBox_vtt, pero ordenados por timein de primero a último, para descarga fichero subtitulos (save_subt.php)
* getCommentsVideoBox($video, $box): select comentaris de video_id i box_id de taula comentarios_video_box
* insert_con_vid_y_box( $comment_txt, $userId, $vidId, $boxId , $timein , $timeout): inserta comentari a taula comentarios_video_box 
* insert( $comment_txt, $userId ): inserta commentari a taula comentarios
* update( $data ): pendent. Actualitzaria comentari. (Permetria editar a l'usuari).
* delete( $commentId ): esborra comentari commentId de la taula comentarios_video_box
*/

require_once (MODELS_DIR .  'commenters.php');
$debug_dentro_de_comments = 'dentro de comments';

//$debug_commentsphp = 'estoy en commentsphp ' . $debug4;
class Comments {


	public static function getTotalNumCommentsVideo($video) {
	
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getTotalNumCommentsVideo)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$sql_pdo = "select count(*) from comentarios_video_box where video_id=:video";
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getTotalNumCommentsVideo)';
			/*
			$result = $stmt->execute( array(
					':video' => $video
					));
			//$result = $stmt->fetchAll();
			$num_rows = (int) $result->fetchColumn();
			*/
			$num_rows_query = $db->query('select count(*) from comentarios_video_box where video_id='.$video); 
			$debug_conexio .= ', num_rows_query ';
			$num_rows = $num_rows_query->fetchColumn(); 
			
			$debug_conexio .= ', num_rows es: ' . $num_rows;
			if ( $num_rows ) { 
				$output = $num_rows;
			 }
			 else {
				$output = 0;		// cuando no hay comentarios, fetchColumn retorna null (select count = 0 -> no hay columnas.
				$debug_conexio .= 'getTotalNumCommentsVideo en ELSE, result: ' . $output . ' , num_rows: 0, '. $num_rows;
			 }
		}
		catch(PDOException $e){
			echo 'catch en getTotalNumCommentsVideo!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
		
	}


	public static function getNumCommentsVideoBox($video, $box) {

		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getTotalNumCommentsVideo)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$sql_pdo = "select count(*) from comentarios_video_box where video_id=:video and box_id=:box";
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getTotalNumCommentsVideo)';
			
			/*
			$result = $stmt->execute( array(
					':video' => $video,
					':box' => $box
					));
			*/
			//$result = $stmt->fetchAll();
			//$num_rows = $result->fetchColumn();
			
			
			$num_rows = $db->query('select count(*) from comentarios_video_box where video_id='.$video.' and box_id='.$box)->fetchColumn(); 
			
			$debug_conexio .= ', num_rows es: ' . $num_rows;
			if ( $num_rows ) { 
				$output = $num_rows;
			 }
			 else {
				$output = 0;		// cuando no hay comentarios, fetchColumn retorna null (select count = 0 -> no hay columnas.
				$debug_conexio .= 'getNumCommentsVideoBox en ELSE, output: ' . $output . ' , num_rows: 0, '. $num_rows;
			 }
		}
		catch(PDOException $e){
			echo 'catch en getNumCommentsVideoBox!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
		
	}

	public static function getCommentsVideoBox_vtt($video, $box) {
	
		$output = array();
		//$sql = "select * from comentarios_video_box where video_id=$video and box_id=$box order by comment_id desc";	
		// INSERT INTO `comentarios_video_box`(`comment_id`, `comment`, `userId`, `video_id`, `box_id`, `time_in`, `time_out`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7])
		//$query = mysql_query( $sql );
				
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getCommentsVideoBox_vtt)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		//$query = mysql_query( $sql );
		$sql_pdo = "select * from comentarios_video_box where video_id=:video and box_id=:box order by time_in asc";
		//insert into paginas values( :page_id , :page_title , :page_header1 , :video_id , :num_boxes , :creator_id , :owner_id , :creation_date )";
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getCommentsVideoBox_vtt)';
			//$stmt->execute();
			$stmt->execute( array(
					':video' => $video,
					':box' => $box
					));
			$result = $stmt->fetchAll();
			if ( count($result) ) { 
				foreach($result as $row) {
					//print_r($row);
					
					$std = new stdClass();
					//$insert_id = mysql_insert_id();
					$std->comment_id = $row['comment_id'];
					$std->comment = $row['comment'];
					$std->userId = $row['userId'];
					$std->video_id = $row['video_id'];
					$std->box_id = $row['box_id'];
					$std->time_in = $row['time_in'];
					$std->time_out = $row['time_out'];
					
					$std->debug = 'getCommentsVideoBox_vtt if query ok';
					$debug_pagesphp = 'estoy en getCommentsVideoBox_vtt if query';
					$debug_conexio .= 'rowCount > 0, y ' . $std->debug;
					
					$output[] = $std;
				}
			 }
			 else {
				$debug_conexio .= 'getCommentsVideoBox_vtt en ELSE, count(result): 0, '. count($result);
			 }
		}
		catch(PDOException $e){
			echo 'catch en getCommentsVideoBox_vtt!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
		
		/*	
		if( $query ) {
			if( mysql_num_rows( $query ) > 0 ) {
				while( $row = mysql_fetch_object( $query ) ) {
					$output[] = $row;
				}	
			}
		}
		return $output;
		*/
	}

	public static function getCommentsVideoBox($video, $box) {
	
		$output = array();
		//$sql = "select * from comentarios_video_box where video_id=$video and box_id=$box order by comment_id desc";	
		// INSERT INTO `comentarios_video_box`(`comment_id`, `comment`, `userId`, `video_id`, `box_id`, `time_in`, `time_out`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7])
		//$query = mysql_query( $sql );
				
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getCommentsVideoBox)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		//$query = mysql_query( $sql );
		$sql_pdo = "select * from comentarios_video_box where video_id=:video and box_id=:box order by comment_id desc";
		//insert into paginas values( :page_id , :page_title , :page_header1 , :video_id , :num_boxes , :creator_id , :owner_id , :creation_date )";
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getCommentsVideoBox)';
			//$stmt->execute();
			$stmt->execute( array(
					':video' => $video,
					':box' => $box
					));
			$result = $stmt->fetchAll();
			if ( count($result) ) { 
				foreach($result as $row) {
					//print_r($row);
					
					$std = new stdClass();
					//$insert_id = mysql_insert_id();
					$std->comment_id 	= $row['comment_id'];
					$std->comment 		= $row['comment'];
					$std->userId 		= $row['userId'];
					$std->video_id 		= $row['video_id'];
					$std->box_id 		= $row['box_id'];
					$std->time_in 		= $row['time_in'];
					$std->time_out 		= $row['time_out'];
					// added 17-12-2017 ("x,y,w,h")
					$std->pos_x 		= $row['pos_x'];
					$std->pos_y 		= $row['pos_y'];
					$std->width 		= $row['width'];
					$std->height 		= $row['height'];
					
					$std->debug = 'getCommentsVideoBox if query ok';
					$debug_pagesphp = 'estoy en getCommentsVideoBox if query';
					$debug_conexio .= 'rowCount > 0, y ' . $std->debug;
					
					$output[] = $std;
				}
			 }
			 else {
				$debug_conexio .= 'getCommentsVideoBox en ELSE, count(result): 0, '. count($result);
			 }
		}
		catch(PDOException $e){
			echo 'catch en getCommentsVideoBox!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
		
		/*	
		if( $query ) {
			if( mysql_num_rows( $query ) > 0 ) {
				while( $row = mysql_fetch_object( $query ) ) {
					$output[] = $row;
				}	
			}
		}
		return $output;
		*/
	}

	/* getComments sólo sirve para versiones anteriores a v0.7 */
	public static function getComments() {
	
		$output = array();
		$sql = "select * from comentarios order by comment_id desc";
		$query = mysql_query( $sql );
		
		if( $query ) {
			if( mysql_num_rows( $query ) > 0 ) {
				while( $row = mysql_fetch_object( $query ) ) {
					$output[] = $row;
				}	
			}
		}
		return $output;
	
	}

	
	// update_con_vid_y_box
	public static function update_con_vid_y_box( $comment_id, $comment_txt, $userId, $vidId, $boxId , $timein , $timeout, $pos_x = "", $pos_y = "", $width = "", $height = "") {
		// Insert data into DB
		$comment_txt = addslashes( $comment_txt );
		
		/*
		$sql = "UPDATE comentarios_video_box SET comment='$comment_txt', userId=$userId, video_id=$vidId, box_id=$boxId, time_in=$timein, time_out=$timeout WHERE comment_id=$comment_id";
		// UPDATE `comentarios_video_box` SET `comment_id`=[value-1],`comment`=[value-2],`userId`=[value-3],`video_id`=[value-4],`box_id`=[value-5],`time_in`=[value-6],`time_out`=[value-7] WHERE 1
		
		$query = mysql_query( $sql );
		
		if( $query ) {
			//$insert_id = mysql_insert_id();
			
			$std = new stdClass();
			$std->comment_id = $comment_id;
			$std->comment = $comment_txt;
			$std->userId = (int)$userId;
			$std->vidId = (int)$vidId;
			$std->boxId = (int)$boxId;
			$std->timein = (int)$timein;
			$std->timeout = (int)$timeout;
			$std->debug = 'commentsphp if query ok';
			$debug_commentsphp = 'estoy en commentsphp if query';
			return $std;
		}
		return null;
		*/
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (comments update)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$sql_pdo = "UPDATE comentarios_video_box SET comment=:comment_txt, userId=:userId, video_id=:vidId, box_id=:boxId, time_in=:timein, time_out=:timeout, pos_x=:posx, pos_y=:posy, width=:width, height=:height WHERE comment_id=:comment_id";
		// UPDATE `comentarios_video_box` SET `comment_id`=[value-1],`comment`=[value-2],`userId`=[value-3],`video_id`=[value-4],`box_id`=[value-5],`time_in`=[value-6],`time_out`=[value-7] WHERE 1
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (comments update_con_vid_y_box)';
			//$stmt->execute();
			$result = $stmt->execute( array(
					':comment_id' => $comment_id,
					':comment_txt' => $comment_txt,
					':userId' => $userId,
					':vidId' => $vidId,
					':boxId' => $boxId,
					':timein' => $timein,
					':timeout' => $timeout,
					':posx' => $pos_x,
					':posy' => $pos_y,
					':width' => $width,
					':height' => $height
					));
			//$result = $stmt->fetch();					// update, insert y delete a veces dan error Error: SQLSTATE[HY000] y es debido al fetch. No se usa fetch. Ponemos $result en el execute.
			if ( count($result) ) { 
				$std = new stdClass();
				$std->comment_id 	= $comment_id;
				$std->comment 		= $comment_txt;
				$std->userId 		= (int)$userId;
				$std->vidId 		= (int)$vidId;
				$std->boxId 		= (int)$boxId;
				$std->timein 		= (int)$timein;
				$std->timeout 		= (int)$timeout;
				// added 17-12-2017 ("x,y,w,h")
				$std->posx 			= (int)$pos_x;
				$std->posy 			= (int)$pos_y;
				$std->width 		= (int)$width;
				$std->height 		= (int)$height;
				$std->debug = 'comments update_con_vid_y_box if query ok';
				$debug_commentsphp = 'estoy en comments update_con_vid_y_box if query';
				return $std;
			 }
			 else {
				$debug_conexio .= ' comments update_con_vid_y_box en ELSE, count(result): 0, '. count($result);
			 }
		}
		catch(PDOException $e){
			echo 'catch en comments update_con_vid_y_box!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
	}
	// insert_con_vid_y_box
	public static function insert_con_vid_y_box( $comment_txt, $userId, $vidId, $boxId , $timein , $timeout, $pos_x = "", $pos_y = "", $width = "", $height = "") {
		// Insert data into DB
		$comment_txt = addslashes( $comment_txt );
		/*
		$sql = "insert into comentarios_video_box values('' , '$comment_txt' , $userId , $vidId , $boxId , $timein , $timeout )";
		// INSERT INTO `comentarios_video_box`(`comment_id`, `comment`, `userId`, `video_id`, `box_id`, `time_in`, `time_out`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7])
		
		$query = mysql_query( $sql );
		
		if( $query ) {
			$insert_id = mysql_insert_id();
			
			$std = new stdClass();
			$std->comment_id = $insert_id;
			$std->comment = $comment_txt;
			$std->userId = (int)$userId;
			$std->vidId = (int)$vidId;
			$std->boxId = (int)$boxId;
			$std->timein = (int)$timein;
			$std->timeout = (int)$timeout;
			$std->debug = 'commentsphp if query ok';
			$debug_commentsphp = 'estoy en commentsphp insert_con_vid_y_boxif query';
			return $std;
		}
		return null;
		*/
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (comments insert_con_vid_y_box)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$sql_pdo = "insert into comentarios_video_box (`comment`, `userId`, `video_id`, `box_id`, `time_in`, `time_out`, `pos_x`, `pos_y`, `width`, `height`) values (:comment_txt , :userId , :vidId , :boxId , :timein , :timeout , :posx , :posy , :width , :height )";
		// INSERT INTO `comentarios_video_box`(`comment_id`, `comment`, `userId`, `video_id`, `box_id`, `time_in`, `time_out`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7])
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (comments insert_con_vid_y_box)';
			//$stmt->execute();
			$result = $stmt->execute( array(
					//':comment_id' => $comment_id,
					':comment_txt' => $comment_txt,
					':userId' => $userId,
					':vidId' => $vidId,
					':boxId' => $boxId,
					':timein' => $timein,
					':timeout' => $timeout,
					':posx' => $pos_x,
					':posy' => $pos_y,
					':width' => $width,
					':height' => $height
					));
			//$result = $stmt->fetch();
			
			$nuevo_comm_id = $db->lastInsertId();
			if ( count($result) > 0 ) { 		// 
				$std = new stdClass();
				
				$std->comment_id = $nuevo_comm_id;
				$std->comment = $comment_txt;
				$std->userId = (int)$userId;
				$std->vidId = (int)$vidId;
				$std->boxId = (int)$boxId;
				$std->timein = (int)$timein;
				$std->timeout = (int)$timeout;
				// added 17-12-2017 ("x,y,w,h")
				$std->posx 		= (int)$pos_x;
				$std->posy 		= (int)$pos_y;
				$std->width 		= (int)$width;
				$std->height 		= (int)$height;

				$std->debug = 'comments insert_con_vid_y_box if query ok';
				$debug_commentsphp = 'estoy en comments insert_con_vid_y_box if query';
				return $std;
			 }
			 else {
				$debug_conexio .= ' comments insert_con_vid_y_box en ELSE, count(result): 0, '. count($result);
			 }
		}
		catch(PDOException $e){
			echo 'catch en comments insert_con_vid_y_box!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		return null;
	}
	
	// return a stdClass Object from the database
	/* la función insert sólo sirve para versiones antiguas a v0.7 */
	public static function insert( $comment_txt, $userId ) {
		// Insert data into DB
		$comment_txt = addslashes( $comment_txt );
		$sql = "insert into comentarios values('' , '$comment_txt' , $userId )";
		
		$query = mysql_query( $sql );
		
		if( $query ) {
			$insert_id = mysql_insert_id();
			
			$std = new stdClass();
			$std->comment_id = $insert_id;
			$std->comment = $comment_txt;
			$std->userId = (int)$userId;
			$std->debug = 'commentsphp if query ok';
			$debug_commentsphp = 'estoy en commentsphp if query';
			return $std;
		}
		return null;
	}
	
	
	public static function update( $data ) {
	}
	
	
	public static function delete( $commentId ) {
		//delete the comments from the database using the id of comment_id
		//mysql_query('delete from comments where comment_id=$comment_id');
		
		/*		
		$sql = "delete from comentarios_video_box where comment_id=$commentId";
		$query = mysql_query( $sql );
		
		if ( $query ) {
			return true;
		}
		return null;
		*/
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (comments delete)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$sql_pdo = "delete from comentarios_video_box where comment_id=:commentId";
		// delete from comments where comment_id=$comment_id
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (comments delete)';
			//$stmt->execute();
			$result = $stmt->execute( array(
					':commentId' => $commentId
					));
			//$result = $stmt->fetch();
			if ( count($result) > 0 ) { 		// DELETE (PDO) returns the number of rows deleted.
				return true;
			}
			return false;
		}
		catch(PDOException $e){
			echo 'catch en comments delete!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		return false;
		
	}
	
}

?>