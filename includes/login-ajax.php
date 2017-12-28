 <?php 
 
	function connect() {
		$connect = mysql_connect( 'localhost', 'websalac_vladmin', '8464vdEmm' );
		$db = mysql_select_db( 'websalac_videolayers' );
		return $connect;
	}
	
	function get_user( $id ) {
		connect();
		$sql = "select * from commenters where userId=$id";			// commenters --> `userId`, `userName`, `profile_img`
		$query = mysql_query($sql);
		
		
		if ($query) {
			if ( mysql_num_rows( $query ) == 1 ) {
				$row = mysql_fetch_object( $query );
				
				//+login como ese userId
					$userId = $id;
					$userName = $row->userName;
					//echo 'nou usuari: ' . $userName;
				return $row;
			}
		}
		return null;
	}
	
	function get_user_list() {
		connect();
		$sql = "select * from commenters order by userId asc";
		$query = mysql_query( $sql );
		
		if( $query ) {
			if( mysql_num_rows( $query ) > 0 ) {
				$people = array();
				while( $row = mysql_fetch_object( $query ) ) {
					$people[] = $row;
				}
				return $people;
			}
		}
		return null;
	}
	
	$method = $_SERVER['REQUEST_METHOD'];
	if( strtolower( $method ) == 'post' ) {
		if( isset( $_POST['task'] ) && $_POST['task'] == "get_user_list" ) {
			$people = get_user_list();
			echo json_encode( $people );
		}
		else if( isset( $_POST['task'] ) && $_POST['task'] == "get_user" ) {
			$user = get_user( $_POST['id'] );
			echo json_encode( $user );
		}
		else if(isset( $_POST['task'] ) && $_POST['task'] == "save_user" ) {
			$nombre_persona 	= addslashes( $_POST['nombre_persona'] );
			$foto 	= addslashes( $_POST['foto'] );
			$id 	= addslashes( $_POST['id'] );
			
			$connect = connect();
$debug_saveuser = '1 connect: ' . $connect;
			
			if( $connect ) {
				if( $id == 0 ) {
					$sql = "insert into commenters (userName, profile_img) values('$nombre_persona', '$foto')";
					$query = mysql_query( $sql );
$debug_saveuser .= ', connect id==0, query: ' . $query;
					if( $query ) {
						$id = mysql_insert_id();
						$user = get_user($id);
						echo json_encode( $user );
$debug_saveuser .= ', if yes';
					}
				}
				else {
					$sql = "update commenters set userName='$nombre_persona', profile_img='$foto' where userId=$id";		
					$query = mysql_query( $sql );
$debug_saveuser .= ', connect id =/= 0, query: ' . $query;
					if( $query ) {
$debug_saveuser .= ', if yes';
						//echo $debug_saveuser;
						//echo 1;
						$user = get_user($id);
						echo json_encode( $user );
					}
					else {
$debug_saveuser .= ', else';
						//echo $debug_saveuser;
						//echo 0;
						$user = get_user($id);
						echo json_encode( $user );
					}
				}
				
			}
		}
	}
 
 
 ?>