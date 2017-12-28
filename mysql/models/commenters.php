<?php
/*
Version 	Fecha 		Descrpcion
1.1 		16-12-2017 	Anyadido updateMemberPassword (para reset-password, se llama desde Changing-password.php) y updateMember (aun no listo, pendiente creacion tabla voc_user_roles (equivalente) en VL)
*/
/*
	* getCommenter($userId): consulta taula Commenters i obté dades usuari. La cridem a "includes/comment_box.php". I també a "ajax/comment_insert.php" --> pasa via Ajax $userInfo (toda la consulta) a "js/comment_insert.js" (funcion comment_video_box_post_btn_click).
		INSERT INTO `commenters`(`userId`, `userName`, `profile_img`, `fbId`, `genero`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5])
	* checkFacebookCommenter(fb_userId): consulta d'un usuari a taula Commenters via usuari de facebook. Ja no necessària.
	* newCommenter( $first_name, $last_name, $gender, $fb_userId ): alta d'un nou usuari. Ja no es fa servir.
	* updateFacebookCommenter( $id_usuario, $nombre_persona, $fb_foto, $fb_userId ): actualitza dades del usuari de facebook a la taula Commenters. Ja no es fa servir.
	
	* getMember($userId) --> retorna dades de userId de la taula Members.	--> cal ajustar noms camps
		INSERT INTO `members`(`id`, `ext_id`, `username`, `email`, `password`, `lastlog`, `signup_date`, `activated`, `avatar`, `banner`, `full_name`, `country`, `state`, `city`, `gender`, `birthday`, `ipaddress`) VALUES ([value-1],[value-2],....,[value-16],[value-17])
	
	
*/
class Commenters {

	public static function getCommenter($userId) {
		// v1.1 	22-11-2017 	admito solo un resultado en output. No array, no foreach.  --> para includes/comment_box.php.

		//$sql = "select * from commenters where userId=$userId";
		$sql_pdo = "select * from commenters where userId=:userId";
				
		// v1.1 $output = array();	//v1.1 
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (commenters)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (commenters)';
			//$stmt->execute();
			$stmt->execute( array(
					':userId' => $userId
					));
			$result = $stmt->fetchAll();
			$std = new stdClass();
			if ( count($result) ) { 
				/*
				foreach($result as $row) {
					//print_r($row);
					$output[] = $row;
					$std->debug = 'commentersphp if query ok';
					$debug_pagesphp = 'estoy en commentersphp if query';
					$debug_conexio .= 'rowCount > 0, y ' . $std->debug;
				}
				*/

				$output = $result;
				$std->debug = 'commentersphp if query ok';
				$debug_pagesphp = 'estoy en commentersphp if query';
				$debug_conexio .= 'rowCount > 0, y ' . $std->debug;
			 }
		}
		catch(PDOException $e){
			echo 'catch en commentersphp - getCommenter!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}
	
	public static function getMember($userId) {
		// v1.1 	22-11-2017 	admito solo un resultado en output. No array, no foreach.  --> para includes/comment_box.php.

		//$sql = "select * from members where id=$userId";
		$sql_pdo = "select * from members where id=:userId";
		//$output = array();		// peta si es un array.
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (commenters - getMember)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (commenters - getMember)';
			//$stmt->execute();
			$stmt->execute( array(
					':userId' => $userId
					));
			$result = $stmt->fetch();
			$std = new stdClass();
			if ( count($result) ) { 
				//foreach($result as $row) {
					//print_r($row);
					$std->id = $result['id'];
					$std->ext_id = $result['ext_id'];
					$std->username = $result['username'];
					$std->email = $result['email'];
					$std->password = $result['password'];
					$std->lastlog = $result['lastlog'];
					$std->signup_date = $result['signup_date'];
					$std->activated = $result['activated'];
					$std->avatar = $result['avatar'];
					$std->banner = $result['banner'];
					$std->full_name = $result['full_name'];
					$std->country = $result['country'];
					$std->state = $result['state'];
					$std->city = $result['city'];
					$std->gender = $result['gender'];
					$std->birthday = $result['birthday'];
					$std->ipaddress = $result['ipaddress'];
					
					$std->debug = 'commentersphp  - getMember if query ok';
					$debug_pagesphp = 'estoy en commentersphp - getMember if query';
					$debug_conexio .= 'rowCount > 0, y ' . $std->debug;
					$output = $std;
				//}
			 }
		}
		catch(PDOException $e){
			echo 'catch en commentersphp - getMember!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
		/*		
			//$query = mysql_query( $sql );
			if( $query ) {
				if( mysql_num_rows( $query ) == 1 ) {
					return mysql_fetch_object( $query );
				}
			}
			return null;
		*/		
	}

	public static function checkFacebookCommenter($fb_userId) {
		/*	
			$sql = "select * from commenters where fbId=$fb_userId";
			$query = mysql_query( $sql );
			if( $query ) {
				if( mysql_num_rows( $query ) == 1 ) {
					return mysql_fetch_object( $query );
				}
			}
			return null;
		*/		
		
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (checkFacebookCommenter) ';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$output = array();
		$sql_pdo = "select * from commenters where fbId=:fb_userId";
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (checkFacebookCommenter)';
			//$stmt->execute();
			$stmt->execute( array(
					':fb_userId' => $fb_userId
					));
			$result = $stmt->fetchAll();
			$std = new stdClass();
			if ( count($result) ) { 
				foreach($result as $row) {
					//print_r($row);
					$std->id = $row['id'];
					$std->ext_id = $row['ext_id'];
					$std->username = $row['username'];
					$std->email = $row['email'];
					$std->password = $row['password'];
					$std->lastlog = $row['lastlog'];
					$std->signup_date = $row['signup_date'];
					$std->activated = $row['activated'];
					$std->avatar = $row['avatar'];
					$std->banner = $row['banner'];
					$std->full_name = $row['full_name'];
					$std->country = $row['country'];
					$std->state = $row['state'];
					$std->city = $row['city'];
					$std->gender = $row['gender'];
					$std->birthday = $row['birthday'];
					$std->ipaddress = $row['ipaddress'];
					$output[] = $std;
				}
			 }
		}
		catch(PDOException $e){
			echo 'catch en checkFacebookCommenter!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}

	public static function newCommenter( $first_name, $last_name, $gender, $fb_userId ) {
		/*
			//INSERT INTO `commenters`(`userId`, `userName`, `profile_img`, `fbId`, `genero`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5])
			$sql = "insert into commenters values(  '' , '$first_name . $last_name' , '' , '$fb_userId' , '$gender' ) ";
			//$sql = "select * from commenters where userId=".$userId;
			$query = mysql_query( $sql );
					
			if( $query ) {
				$insert_id = mysql_insert_id();
				
				$std = new stdClass();
				$std->user_id = $insert_id;
				$std->user_name = $first_name . ' ' . $last_name;
				$std->profile_img = $profile_img;
				$std->fb_userId = (int)$fb_userId;
				$std->gender = (int)$gender;
				
				return $std;
			}
			return null;
		*/		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (newCommenter) ';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$output = array();
		$sql_pdo = "insert into commenters values(  :userId , :username , :profile_img , :fb_userId , :gender ) ";
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (newCommenter)';
			//$stmt->execute();
			$stmt->execute( array(
					':userId' => $fb_userId,										// ¿ ??
					':username' => $first_name . ' ' .$last_name,						// en DB es userName
					':profile_img' => '',
					':fb_userId' => $fb_userId,										// en DB es fbId
					':gender' => $gender											// en DB es 'genero'
					));
			
			 if($stmt->rowCount() > 0){
				//$insert_id = mysql_insert_id();
				$insert_id = $db->lastInsertId();
					
				$std = new stdClass();
				$std->userId = $insert_id;
				$std->userName = $first_name . ' ' . $last_name ;
				$std->profile_img = '';
				$std->fbId = $fb_userId;
				$std->genero = $gender;
				
				$std->debug = 'newCommenter if query ok';
				$debug_commentersphp = 'estoy en newCommenter if query';
				$debug_conexio .= 'rowCount > 0, y ' . $std->debug;
				return $std;
			}
		}
		catch(PDOException $e){
			echo 'catch en newCommenter!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return null;
	}

	public static function updateFacebookCommenter( $id_usuario, $nombre_persona, $fb_foto, $fb_userId ) {
		/*	//UPDATE `commenters` SET `userId`=[value-1],`userName`=[value-2],`profile_img`=[value-3],`fbId`=[value-4],`genero`=[value-5] WHERE 1
			$sql = "update commenters set userName='$nombre_persona', profile_img='$fb_foto', fbId=$fb_userId where userId=$id_usuario";
			$query = mysql_query( $sql );
			if( $query ) {
				echo 1;
			}
			else {
				echo 0;
			}
		*/		
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (newCommenter) ';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$sql_pdo = "update commenters set userName='$nombre_persona', profile_img='$fb_foto', fbId=$fb_userId where userId=$id_usuario";
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (updateFacebookCommenter)';
			//$stmt->execute();
			$stmt->execute( array(
					':userId' => $id_usuario,
					':username' => $nombre_persona,			// se mantienen las comillas??
					':profile_img' => $fb_foto,				// se mantienen las comillas??
					':fb_userId' => $fb_userId
					));
			
			 if($stmt->rowCount() > 0){
				//$insert_id = mysql_insert_id();
				$insert_id = $db->lastInsertId();
					
				$std = new stdClass();
				$std->page_id = $insert_id;
				$std->userName = $id_usuario;
				$std->profile_img = $profile_img;
				$std->fbId = $fb_userId;
				//$std->genero = $genre;
				$std->debug = 'updateFacebookCommenter if query ok';
				$debug_pagesphp = 'estoy en updateFacebookCommenter if query';
				$debug_conexio .= 'rowCount > 0, y ' . $std->debug;
				return $std;
			}
		}
		catch(PDOException $e){
			echo 'catch en updateFacebookCommenter!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
	}
	
	public static function updateMemberPassword( $id_usuario, $new_password ) {
		//return "hola desde updateMemberPassword(id_usuario=".$id_usuario.", new_password=".$new_password.")";

		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (updateMemberPassword) ';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$sql_pdo = "update members set password=:password where id=:userId";
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (updateMemberPassword)';
			//$stmt->execute();
			$stmt->execute( array(
					':userId' => $id_usuario,
					':password' => $new_password
					));
			
			if($stmt->rowCount() > 0){
				$std = new stdClass();

				$std->id = $id_usuario;
				$std->password = $new_password;
				
				$std->debug = 'commentersphp  - updateMemberPassword if query ok';
				$std->debug_pagesphp = 'estoy en commentersphp - updateMemberPassword if query';
				$std->debug_conexio .= 'rowCount > 0, y ' . $std->debug;
				$output = $std;
			}
			else {

				$std = new stdClass();
				$std->debug = 'commentersphp  - updateMemberPassword if ELSE (No rows have been updated: rowcount = '.$stmt->rowCount().' )';
				$std->debug_conexio .= 'rowCount > 0, y ' . $std->debug;
				$output = $std;

			}
			return $output;
		}
		catch(PDOException $e){
			echo 'catch en updateMemberPassword!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
	}

	// updateMember usa voc_user_roles, pendiente crear tabla equivalente en VL.
	public static function updateMember( $id_usuario, $fb_userId, $username, $email, $password, $fb_foto, $banner, $full_name, $country, $state, $city, $gender, $birthday, $user_role_name ) {
		/*		//UPDATE `commenters` SET `userId`=[value-1],`userName`=[value-2],`profile_img`=[value-3],`fbId`=[value-4],`genero`=[value-5] WHERE 1
			$sql = "update commenters set userName='$nombre_persona', profile_img='$fb_foto', fbId=$fb_userId where userId=$id_usuario";
			$query = mysql_query( $sql );
			if( $query ) {
				echo 1;
			}
			else {
				echo 0;
			}
		*/		
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (newCommenter) ';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$sql_pdo = "update members as m, voc_user_roles as r set 
										m.username=:username, 
										m.ext_id=:ext_id, 
										m.email=:email, 
										m.password=:password, 
										m.avatar=:avatar, 
										m.banner=:banner, 
										m.full_name=:full_name, 
										m.country=:country, 
										m.state=:state, 
										m.city=:city, 
										m.gender=:gender,
										m.birthday=:birthday,
										m.role_id=r.role_id 
					where m.id=:userId and r.role_name=:user_role_name";
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (updateMember)';
			//$stmt->execute();
			$stmt->execute( array(
					':userId' => $id_usuario,
					':ext_id' => $fb_userId,
					':username' => $username,
					':email' => $email,						// se mantienen las comillas??
					':password' => $password,
					':avatar' => $fb_foto,				// se mantienen las comillas??
					':banner' => $banner,
					':full_name' => $full_name,
					':country' => $country,
					':state' => $state,
					':city' => $city,
					':gender' => $gender,
					':birthday' => $birthday,
					':user_role_name' => $user_role_name
					));
			
			if($stmt->rowCount() > 0){
				$std = new stdClass();
				$std->id = $id_usuario;
				$std->ext_id = $fb_userId;
				$std->username = $nombre_persona;
				$std->email = $email;
				$std->password = $password;
				//$std->role = $row['role_name'];						// incorporado 19.10.15
				$std->avatar = $fb_foto;
				$std->banner = $banner;
				$std->full_name = $full_name;
				$std->country = $country;
				$std->state = $state;
				$std->city = $city;
				$std->gender = $gender;
				$std->birthday = $birthday;
				//$std->ipaddress = $row['ipaddress'];
				$std->user_role_name = $user_role_name;
				
				$std->debug = 'commentersphp  - updateMember if query ok';
				$debug_pagesphp = 'estoy en commentersphp - updateMember if query';
				$debug_conexio .= 'rowCount > 0, y ' . $std->debug;
				$output = $std;
			}
		}
		catch(PDOException $e){
			echo 'catch en updateMember!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
	}
	
}

?>