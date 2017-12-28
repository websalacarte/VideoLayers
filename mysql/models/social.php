<?php
#	function get_date_format_ago($std_date)

#	function getUserTotalLikesSent($user_id)
#	function getNumLikesVoce($voce_id)
#	function getUserTotalLikesReceived($user_id)
#	function getUserComments($user_id)
#	function getUserTotalComments($user_id)

#	function getUserVoceMaxLikes($user_id)
# 	function getUserSocialActivities($userId)
# 	function insert_action($sender_id, $sender_role_id, $action_type_id , $recipient_type_id, $recipient_name, $recipient_link , $status)

#	function getNotifications($user_id)
# 	function getNotificationsUnread_MostRecentPerType($user_id)
#	function setNotificationsRead($user_id)
#	function getVoceSubscribers($voce_id)
#	function getPageSubscribers($page_id)

#	function getUserPageFavorites($user_id)
# 	function getUserFriends($user_id)

#	function getUserPages($user_id)

#	function getFriendSubscribers($user_id)

#	function getUserPreferences($userId)
#	function setNewUserPreferences($userId)
#	function updateUserPreferences($userId, $send_email, $page_activity, $voce_activity, $private_voce_activity, $friend_activity)

#	function insert_notification_voce_replied_____________old($voce_id, $user_sender_id, $user_sender_name, $user_sender_role, $voce_name)
#	function insert_notification_voce_replied($voce_id, $user_sender_id, $user_sender_name, $user_sender_role, $voce_name)
#	function insert_notification_page_updated($page_id, $voce_id, $user_sender_id, $user_sender_name, $user_sender_role)
#	function insert_notification_friend_replied($voce_id, $user_sender_id, $user_sender_name, $user_sender_role, $voce_name)

#	function get_is_page_favorite($user_id, $page_id)
# 	function insert_fav_this_page($user_id, $page_id)
#	function delete_fav_this_page($user_id, $page_id)

#	function get_is_user_favorite($user_id, $friend_id)
#	function insert_fav_this_friend($user_id, $friend_id)
#	function delete_fav_this_friend($user_id, $friend_id)

#	function get_is_voce_favorite($user_id, $voce_id)
#	function insert_fav_this_voce($user_id, $voce_id, $original_voce_id)
#	function delete_fav_this_voce($user_id, $voce_id, $original_voce_id)
#
#



require_once (MODELS_DIR .  'comments.php');

class Social {


	public static function get_date_format_ago($std_date) {
		// funcion que transforma una fecha en formato "Y-m-d H:i:s" en "4 hours ago"
		$sql_pdo = "select sa.activity_id, sa.date_created, sa.sender_id, sa.sender_role_id, sa.action_type_id, a.action_type_name, sa.recipient_type_id, r.recipient_type_name, sa.recipient_name, sa.recipient_link
					from social_activity as sa, social_activity_action_types as a, social_activity_recipient_types as r 
					where sender_id=:user_id and sa.action_type_id=a.action_type_id and sa.recipient_type_id=r.recipient_type_id and sa.status=0
					order by date_created desc";	// el propietario del comentario/reply es user_id. El id de la voz original está o bien en 'id' o bien en 'is_reply_to'
				
		$ago_date = "";
		
		       
                 $time_std = new DateTime($std_date);
                 $time_now = new DateTime(Date('Y-m-d H:i:s') );
                 $interval = $time_std->diff($time_now);
                 $interval_days = $interval->format('%d');  // con %a no funciona, no se porque.
                 $interval_hours = $interval->format('%h');  // con %a no funciona, no se porque.
                 $interval_mins = $interval->format('%i');  // con %a no funciona, no se porque.
                 $interval_secs = $interval->format('%s');  // con %a no funciona, no se porque.
                 
                 if($interval_days === '0') {
                    $ago_date = 'Today';
                    if($interval_hours === '0') {
                      if($interval_mins === '0') {
                        // some seconds ago, <1 min
                        $secs_ago = (int) $interval_secs;
                        $ago_date .= ', '.$interval_secs.' seconds ago';
                      }
                      else {
                        // some minutes ago, <1 h
                        $mins_ago = (int) $interval_mins;
                        $ago_date .= ', '.$interval_mins.' minutes ago';
                      }
                    }
                    elseif ($interval_hours === '1') {
                      $hours_ago = (int) $interval_hours;
                      $ago_date .= ', about '.$interval_hours.' hour ago';
                    }
                    else {
                      $hours_ago = (int) $interval_hours;
                      $ago_date .= ', about '.$interval_hours.' hours ago';
                    }
                 }
                 elseif($interval_days ==='1') {
                  $ago_date = 'Yesterday';
                 }
                 else {
                  $ago_date = $interval->format('%d days ago');
                 }

		
		return $ago_date;
	}	

	public static function getUserTotalLikesSent($user_id) {
		// total number of likes registered by user_id. Likes currently existing!! (deleted don't count)
		// returns: number
		// 		
		$sql_pdo = "select count(id) from social_voce_favorites where user_id = :user_id";
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getUserTotalLikesSent)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getUserTotalLikesSent)';
			//$stmt->execute();
			$stmt->execute( array(
					':user_id' => $user_id
					));
			$result = $stmt->fetch();
			if ( $result[0] >= 0 ) { 
					$output = $result[0];
			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - getUserTotalLikesSent!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}

	public static function getNumLikesVoce($voce_id) {
		// total number of likes for voce_id. Likes currently existing!! (deleted don't count)
		// returns: number
		// 		
		$sql_pdo = "select count(id) from social_voce_favorites where voce_id = :voce_id";
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getNumLikesVoce)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getNumLikesVoce)';
			//$stmt->execute();
			$stmt->execute( array(
					':voce_id' => $voce_id
					));
			$result = $stmt->fetch();
			if ( $result[0] >= 0 ) { 
					$output = $result[0];
			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - getNumLikesVoce!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}

	public static function getUserTotalLikesReceived($user_id) {
		// returns number of likes for all comments by this user.
		// Strat:
			// 1. get all his/her voces
			$user_comments = self::getUserComments($user_id);
			// 2. foreach voce, count likes in social_voce_favorites
			$total_num_likes = 0;
			foreach ($user_comments as $user_comment) {
				//
				$num_likes_comment = Social::getNumLikesVoce($user_comment->comment_id);
				$total_num_likes += $num_likes_comment;
			}
			return $total_num_likes;
	}

	public static function getUserComments($user_id) {
		// total number of comments by user. Comments currently existing!! (deleted don't count)
		// returns: number
		// 		
		$sql_pdo = "select * from voces where user_id = :user_id";
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getUserComments)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getUserComments)';
			//$stmt->execute();
			$stmt->execute( array(
					':user_id' => $user_id
					));
			$result = $stmt->fetchAll();
			if ( count($result) ) { 
				foreach($result as $row) {
					//print_r($row);
					$debug_pagesphp = 'estoy en getUserComments if query';

					$std = new stdClass();
					
					$std->comment_id = $row['comment_id'];
					$std->comment = $row['comment'];
					$std->user_id = $row['user_id'];
					$std->video_id = $row['video_id'];
					$std->position_vert = $row['position_vert'];
					$std->speed = $row['speed'];
					$std->position_hor = $row['position_hor'];
					$std->filter_id = $row['filter_id'];
					$std->language_id = $row['language_id'];
					$std->name = $row['name'];
					$std->title = $row['title'];
					$std->style_id = $row['style_id'];
					$std->created_by = $row['created_by'];
					$std->creation_date = $row['creation_date'];
					$std->modified_by = $row['modified_by'];
					$std->last_modified = $row['last_modified'];
					$std->is_private = $row['is_private'];
					$std->private_with = $row['private_with'];
					$std->is_reply = $row['is_reply'];
					$std->is_reply_to = $row['is_reply_to'];

					$std->debug = 'getUserComments if query ok';
					$debug_conexio .= 'rowCount > 0, y ' . $std->debug;

					$output[] = $std;
				}
			}
		}
		catch(PDOException $e){
			echo 'catch en socialphp - getUserComments!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}	

	public static function getUserTotalComments($user_id) {
		// total number of comments by user. Comments currently existing!! (deleted don't count)
		// returns: number
		// 		
		$sql_pdo = "select count(comment_id) from voces where user_id = :user_id";
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getUserTotalComments)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getUserTotalComments)';
			//$stmt->execute();
			$stmt->execute( array(
					':user_id' => $user_id
					));
			$result = $stmt->fetch();
			if ( $result[0] >= 0 ) { 
					$output = $result[0];
			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - getUserTotalComments!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}	


	public static function getUserVoceMaxLikes($user_id) {
		// top 3 voces de un usuario, as in "number of likes"
		// 	from: social_voce_favorites as svf, social_activity as sa, voces as v, members as m 
					//social_activity_action_types as a, social_activity_recipient_types as r 	PENDIENTE roles y ampliar a otros tipos de actividad. Fijado a action_type=2
		//	where 	
		//			//social_voce_favorites.user_id = $user_id 			Estos user_id's son los usuarios que han hecho el like, no el owner de la voce.
		//			social_activity.sender_id = $user_id && 			Usuario que ha posted or replied la Voce.
		//			(social_voce_favorites.action_type_id = 1 or social_voce_favorites.action_type_id = 2) 	
		//			social_activity.recipient_name = social_voce_favorites.voce_id 	!!!! 	type=1 "name_456", type=2 "456" !!!!!
		// 			voces.comment_id = social_activity.recipient_name 	Para obtener resto de datos de la voce.
					// voces & user data
		//			members.id = voces.user_id
		// returns: 
		//		datos de la voce (incluido comment y date_created)
		//		num_likes 	<-- count(social_voce_favorites.id) as num_likes
		//		page_uri 	<-- social_activity.recipient_link  as page_uri
		//		voce_image	<-- social_activity.recipient_image as voce_image
		//		date_created<-- social_activity.date_created as date_created
		//		username 	<-- members.username
		//		voce_id 	<-- voces.comment_id
		//		activity_type = 2 	(replied, sólo, de momento)
		// 		
		$sql_pdo = "select 
							v.comment_id,
							v.comment, 
							m.username, 
							m.full_name, 
							count(svf.id) as num_likes, 
							sa.recipient_link  as page_uri, 
							sa.recipient_image as voce_image, 
							sa.date_created

					from social_voce_favorites as svf, social_activity as sa, voces as v, members as m  
					where 	sa.sender_id = :user_id
							and sa.action_type_id = 2 
							and sa.recipient_name = svf.voce_id 
							and v.comment_id = sa.recipient_name
							and m.id = v.user_id
							and sa.status=0
					group by sa.date_created
					order by num_likes desc limit 3";	// el propietario del comentario/reply es user_id. El id de la voz original está o bien en 'id' o bien en 'is_reply_to'
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getUserVoceMaxLikes)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getUserVoceMaxLikes)';
			//$stmt->execute();
			$stmt->execute( array(
					':user_id' => $user_id
					));
			$result = $stmt->fetchAll();
			if ( count($result) ) { 
				foreach($result as $row) {
					//print_r($row);
					$debug_pagesphp = 'estoy en getUserVoceMaxLikes if query';

					$std = new stdClass();
					
					$std->comment_id = $row['comment_id'];
					$std->comment = $row['comment'];
					$std->username = $row['username'];
					$std->full_name = $row['full_name'];
					$std->num_likes = $row['num_likes'];
					$std->page_uri = $row['page_uri'];
					$std->voce_image = $row['voce_image'];
					$std->date_created = $row['date_created'];
					//$std->action_type_id = $row['action_type_id'];	// 2
					//$std->action_type = $row['action_type'];			// 'replied'
					//$std->read_status = $row['read_status'];

					$std->debug = 'getUserVoceMaxLikes if query ok';
					$debug_conexio .= 'rowCount > 0, y ' . $std->debug;

					$output[] = $std;
				}
			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - getUserVoceMaxLikes!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}	

	public static function getUserSocialActivities($user_id) {
		// 
		$sql_pdo = "select sa.activity_id, sa.date_created, sa.sender_id, sa.sender_role_id, sa.action_type_id, a.action_type_name, sa.recipient_type_id, r.recipient_type_name, sa.recipient_name, sa.recipient_link, sa.recipient_image
					from social_activity as sa, social_activity_action_types as a, social_activity_recipient_types as r 
					where sender_id=:user_id and sa.action_type_id=a.action_type_id and sa.recipient_type_id=r.recipient_type_id and sa.status=0
					order by date_created desc";	// el propietario del comentario/reply es user_id. El id de la voz original está o bien en 'id' o bien en 'is_reply_to'
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getUserSocialActivities)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getUserSocialActivities)';
			//$stmt->execute();
			$stmt->execute( array(
					':user_id' => $user_id
					));
			$result = $stmt->fetchAll();
			if ( count($result) ) { 
				foreach($result as $row) {
					//print_r($row);
					$debug_pagesphp = 'estoy en getUserSocialActivities if query';

					$std = new stdClass();
					
					$std->activity_id = $row['activity_id'];
					$std->date_created = $row['date_created'];
					$std->sender_id = $row['sender_id'];
					$std->sender_role_id = $row['sender_role_id'];
					$std->action_type_id = $row['action_type_id'];		// recojo los dos: id y name.
					$std->action_type_name = $row['action_type_name'];
					$std->recipient_type_id = $row['recipient_type_id'];
					$std->recipient_type_name = $row['recipient_type_name'];
					$std->recipient_name = $row['recipient_name'];
					$std->recipient_link = $row['recipient_link'];
					$std->recipient_image = $row['recipient_image'];

					//$std->read_status = $row['read_status'];

					$std->debug = 'getUserSocialActivities if query ok';
					$debug_conexio .= 'rowCount > 0, y ' . $std->debug;

					$output[] = $std;
				}
			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - getUserSocialActivities!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}	

	public static function insert_action($sender_id, $sender_role_id, $action_type_id , $recipient_type_id, $recipient_name, $recipient_link , $status, $voce_img) {
		// este insert permite incluir el id $social_activity_id en social_notification, lo cual permite que destaquemos en el menú-badge las notificaciones sin repetición, pertenecientes a "activities" distintas.

		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (insert_action)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$output = array();
		$date_created = Date( "Y-m-d H:i:s" );

		$sql_pdo = "insert into social_activity 
					(date_created , sender_id, sender_role_id, action_type_id , recipient_type_id, recipient_name, recipient_link , status, recipient_image  ) 
					values (:date_created , :sender_id, :sender_role_id, :action_type_id , :recipient_type_id, :recipient_name, :recipient_link , :status, :img )";
		$stmt = $db->prepare($sql_pdo);


		try{
			$debug_conexio .= 'dins de try (comments insert_action)';
			//$stmt->execute();
			$result = $stmt->execute( array(
					':date_created' => $date_created,
					':sender_id' => $sender_id,
					':sender_role_id' => $sender_role_id, 
					':action_type_id' => $action_type_id,
					':recipient_type_id' => $recipient_type_id,
					':recipient_name' => $recipient_name,
					':recipient_link' => $recipient_link,
					':status' => $status,
					':img' => $voce_img
					));
			//$result = $stmt->fetch();
			
			$nuevo_page_fav_id = $db->lastInsertId();
			if ( count($result) > 0 ) {
				
				//$std = new stdClass();
				//$std->comment_id = $nuevo_page_fav_id;

				$output = $nuevo_page_fav_id;
				//return 1;
			 }
			 else {
				$debug_conexio .= ' comments insert_action en ELSE, count(result): 0, '. count($result);
				$output = 0;
			 }
		}
		catch(PDOException $e){
			echo 'catch en comments insert_action!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		return $output;
	}

	public static function getNotifications($user_id) {
		// 
		$sql_pdo = "select * from social_notifications where recipient_id=:user_id order by date_created desc";	// el propietario del comentario/reply es user_id. El id de la voz original está o bien en 'id' o bien en 'is_reply_to'
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getNotifications)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getNotifications)';
			//$stmt->execute();
			$stmt->execute( array(
					':user_id' => $user_id
					));
			$result = $stmt->fetchAll();
			if ( count($result) ) { 
				foreach($result as $row) {
					//print_r($row);
					$debug_pagesphp = 'estoy en getNotifications if query';

					$std = new stdClass();
					
					$std->notification_id = $row['notification_id'];
					$std->date_created = $row['date_created'];
					$std->sender_id = $row['sender_id'];
					$std->recipient_id = $row['recipient_id'];
					$std->subject = $row['subject'];
					$std->content = $row['content'];
					$std->read_status = $row['read_status'];

					$std->debug = 'getNotifications if query ok';
					$debug_conexio .= 'rowCount > 0, y ' . $std->debug;

					$output[] = $std;
				}
			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - getNotifications!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}	


	public static function getNotificationsUnread_MostRecentPerType($user_id) {
		// http://www.xaprb.com/blog/2006/12/07/how-to-select-the-firstleastmax-row-per-group-in-sql/

		/*		$sql_pdo = "select notification_id, max(date_created) as max_date_created, sender_id, recipient_id, subject, content, read_status
								from social_notifications 
								where recipient_id=:user_id and read_status=0 
								group by subject, sender_id 
								order by date_created desc";

								// la más basica	: todas, ordenadas por fecha.
		$sql_pdo = "select notification_id, date_created, sender_id, recipient_id, subject, content, read_status
								from social_notifications 
								where recipient_id=:user_id and read_status=0 
								order by date_created desc";


								// algo menos basica	: sólo la última de cada sender_id, ordenadas por fecha.
		$sql_pdo = "select notification_id, max(date_created) as max_date_created, sender_id, recipient_id, subject, content, read_status
								from social_notifications 
								where recipient_id=:user_id and read_status=0 
								group by sender_id 
								order by date_created desc";
		*/
		//$sql_pdo = "(select * from fruits where type = 'apple' order by price limit 2)
		//			union all
		//			(select * from fruits where type = 'orange' order by price limit 2)
		//			union all
		//			(select * from fruits where type = 'pear' order by price limit 2)
		//			union all
		//			(select * from fruits where type = 'cherry' order by price limit 2)";
		//
		/*sobre user_id=32:
			 #	notif_id
		(1): 1 	1
		(2): 1 	2
		(3): 1 	20
		(4): 1 	64
		*/
		/*
		$sql_pdo = "(select notification_id, max(date_created) as max_date_created, sender_id, recipient_id, subject, content, read_status
						 from social_notifications 
						 where subject = 'Reply to your comment' 
						 	and recipient_id=:user_id and read_status=0 
						 order by max_date_created limit 4)
					union all
					(select notification_id, max(date_created) as max_date_created, sender_id, recipient_id, subject, content, read_status
						 from social_notifications 
						 where subject = 'Reply to your comment (test)' 
						 	and recipient_id=:user_id and read_status=0 
						 order by max_date_created limit 4)
					union all
					(select notification_id, max(date_created) as max_date_created, sender_id, recipient_id, subject, content, read_status
						 from social_notifications 
						 where subject = 'A friend published new content' 
						 	and recipient_id=:user_id and read_status=0 
						 order by max_date_created limit 4)
					union all
					(select notification_id, max(date_created) as max_date_created, sender_id, recipient_id, subject, content, read_status
						 from social_notifications 
						 where subject = 'Your favorite page has been updated' 
						 	and recipient_id=:user_id and read_status=0 
						 order by max_date_created limit 4)";
		*/
		/*sobre user_id=32:
			 #	notif_id
		(1): 4 	1, 6, 7, 8
			notification_id	max_date_created	sender_id	recipient_id	subject	content	read_status read (1) or not yet read (0) by the recipient
			Editar	Borrar	1	2015-11-18 07:33:12	33	32	Reply to your comment	Agent <a href="agent_id">Agent_name</a> has replie...	0
			Editar	Borrar	6	2015-11-19 12:22:19	33	32	Reply to your comment	agent <a href="/lvti-premium/modules/vl/social/pro...	0
			Editar	Borrar	7	2015-11-19 12:24:42	33	32	Reply to your comment	agent <a href="/lvti-premium/modules/vl/social/pro...	0
			Editar	Borrar	8	2015-11-19 12:26:40	33	32	Reply to your comment	agent <a href="/lvti-premium/modules/vl/social/pro...	0

		(2): 1 	2
			Editar	Borrar	2	2015-11-18 07:33:12	33	32	Reply to your comment (test)	Content TEST	0

		(3): 4 	20, 24, 27, 28
			Editar	Borrar	20	2015-11-19 12:59:01	33	32	A friend published new content	agent <a href="/lvti-premium/modules/vl/social/pro...	0
			Editar	Borrar	24	2015-11-19 13:16:16	33	32	A friend published new content	agent <a href="/lvti-premium/modules/vl/social/pro...	0
			Editar	Borrar	27	2015-11-19 13:31:17	33	32	A friend published new content	agent <a href="/lvti-premium/modules/vl/social/pro...	0
			Editar	Borrar	28	2015-11-19 13:31:17	33	32	A friend published new content	agent <a href="/lvti-premium/modules/vl/social/pro...	0

		(4): 1 	64, 70, 78, 84
			Editar	Borrar	64	2015-11-19 13:48:30	33	32	Your favorite page has been updated	agent TEST PAGE13 has updated your favorite page <...	0
			Editar	Borrar	70	2015-11-19 13:53:46	33	32	Your favorite page has been updated	agent <a href="/lvti-premium/modules/vl/social/pro...	0
			Editar	Borrar	78	2015-11-19 14:07:12	33	32	Your favorite page has been updated	agent <a href="/lvti-premium/modules/vl/social/pro...	0
			Editar	Borrar	84	2015-11-19 14:11:44	33	32	Your favorite page has been updated	agent <a href="/lvti-premium/modules/vl/social/pro...	0
		*/
		$sql_pdo = "(select notification_id, date_created as max_date_created, sender_id, recipient_id, subject, content, read_status, social_activity_id
						 from social_notifications 
						 where subject = 'Reply to your comment' 
						 	and recipient_id=:user_id and read_status=0 
						 group by social_activity_id
						 order by max_date_created DESC limit 4)
					union all
					(select notification_id, date_created as max_date_created, sender_id, recipient_id, subject, content, read_status, social_activity_id
						 from social_notifications 
						 where subject = 'Reply to your comment (test)' 
						 	and recipient_id=:user_id and read_status=0 
						 group by social_activity_id
						 order by max_date_created DESC limit 4)
					union all
					(select notification_id, date_created as max_date_created, sender_id, recipient_id, subject, content, read_status, social_activity_id
						 from social_notifications 
						 where subject = 'A friend published new content' 
						 	and recipient_id=:user_id and read_status=0 
						 group by social_activity_id
						 order by max_date_created DESC limit 4)
					union all
					(select notification_id, date_created as max_date_created, sender_id, recipient_id, subject, content, read_status, social_activity_id
						 from social_notifications 
						 where subject = 'Your favorite page has been updated' 
						 	and recipient_id=:user_id and read_status=0 
						 group by social_activity_id
						 order by max_date_created DESC limit 4)";


						// el propietario del comentario/reply es user_id. El id de la voz original está o bien en 'id' o bien en 'is_reply_to'
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getNotificationsUnread_MostRecentPerType)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getNotificationsUnread_MostRecentPerType)';
			//$stmt->execute();
			$stmt->execute( array(
					':user_id' => $user_id
					));
			$result = $stmt->fetchAll();
			if ( count($result) ) { 
				foreach($result as $row) {
					//print_r($row);
					$debug_pagesphp = 'estoy en getNotificationsUnread_MostRecentPerType if query';

					$std = new stdClass();
					
					$std->notification_id = $row['notification_id'];
					$std->date_created = $row['max_date_created'];
					$std->sender_id = $row['sender_id'];
					$std->recipient_id = $row['recipient_id'];
					$std->subject = $row['subject'];
					$std->content = $row['content'];
					$std->read_status = $row['read_status'];

					$std->debug = 'getNotificationsUnread_MostRecentPerType if query ok';
					$debug_conexio .= 'rowCount > 0, y ' . $std->debug;

					$output[] = $std;
				}
			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - getNotificationsUnread_MostRecentPerType!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}	

	public static function getNotificationsUnread($user_id) {
		// 
		//$sql_pdo = "select * from social_notifications where recipient_id=:user_id and read_status=0 group by subject, sender_id order by date_created desc";	// el propietario del comentario/reply es user_id. El id de la voz original está o bien en 'id' o bien en 'is_reply_to'
		$sql_pdo = "select * from social_notifications where recipient_id=:user_id and read_status=0 group by social_activity_id order by date_created desc";
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getNotifications)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getNotifications)';
			//$stmt->execute();
			$stmt->execute( array(
					':user_id' => $user_id
					));
			$result = $stmt->fetchAll();
			if ( count($result) ) { 
				foreach($result as $row) {
					//print_r($row);
					$debug_pagesphp = 'estoy en getNotifications if query';

					$std = new stdClass();
					
					$std->notification_id = $row['notification_id'];
					$std->date_created = $row['date_created'];
					$std->sender_id = $row['sender_id'];
					$std->recipient_id = $row['recipient_id'];
					$std->subject = $row['subject'];
					$std->content = $row['content'];
					$std->read_status = $row['read_status'];
					$std->social_activity_id = $row['social_activity_id'];

					$std->debug = 'getNotifications if query ok';
					$debug_conexio .= 'rowCount > 0, y ' . $std->debug;

					$output[] = $std;
				}
			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - getNotifications!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}	

	public static function setNotificationsRead($user_id) {
		// 
		$sql_pdo = "update social_notifications set read_status=1 where recipient_id=:user_id";	// el propietario del comentario/reply es user_id. El id de la voz original está o bien en 'id' o bien en 'is_reply_to'
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (setNotificationsRead)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (setNotificationsRead)';
			//$stmt->execute();
			$result = $stmt->execute( array(
					':user_id' => $user_id
					));
			//$result = $stmt->fetchAll();
			if ( count($result) ) { 

				$output = 1;

			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - setNotificationsRead!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}

	public static function getVoceSubscribers($voce_id) {
		// && 'social_notifications_user_preferences'.voce_activity=1
		$sql_pdo = "select distinct v.user_id from voces as v, social_notifications_user_preferences as up where up.user_id=v.user_id and up.voce_activity=1 and (v.comment_id=:voce_id or v.is_reply_to=:voce_id)";
			// el propietario del comentario/reply es user_id. El id de la voz original está o bien en 'id' o bien en 'is_reply_to'
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getVoceSubscribers)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getVoceSubscribers)';
			//$stmt->execute();
			$stmt->execute( array(
					':voce_id' => $voce_id
					));
			$result = $stmt->fetchAll();
			if ( count($result) ) { 
				foreach($result as $row) {
					//print_r($row);
					$debug_pagesphp = 'estoy en getVoceSubscribers if query';
					/*
					$std = new stdClass();
					
					$std->user_id = $row['user_id'];
					$std->debug = 'getVoceSubscribers if query ok';
					$debug_conexio .= 'rowCount > 0, y ' . $std->debug;

					$output[] = $std;
					*/
					$output[] = $row['user_id'];
				}
			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - getVoceSubscribers!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}

	public static function getPageSubscribers($page_id) {
		//$sql = "select * from voces where userId=$userId";
		$sql_pdo = "select distinct p.user_id from social_page_favorites as p, social_notifications_user_preferences as up where up.user_id=p.user_id and up.page_activity=1 and p.page_id=:page_id";
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getPageSubscribers)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getPageSubscribers)';
			//$stmt->execute();
			$stmt->execute( array(
					':page_id' => $page_id
					));
			$result = $stmt->fetchAll();
			if ( count($result) ) { 
				foreach($result as $row) {
					//print_r($row);
					$debug_pagesphp = 'estoy en getPageSubscribers if query';

					//$std = new stdClass();
					//$std->user_id = $row['user_id'];
					//$std->debug = 'getPageSubscribers if query ok. debug_pagesphp: '.$debug_pagesphp. '. count(result)='.count($result);

					//$output[] = $std;
					$output[] = $row['user_id'];
				}
			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - getPageSubscribers!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}

	public static function getUserPageFavorites($user_id) {
		// Solo para el count. Sin restricciones de settings notifications
		$sql_pdo = "select distinct page_id from social_page_favorites where user_id=:user_id";
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getUserPageFavorites)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getUserPageFavorites)';
			//$stmt->execute();
			$stmt->execute( array(
					':user_id' => $user_id
					));
			$result = $stmt->fetchAll();
			if ( count($result) ) { 
				foreach($result as $row) {
					//print_r($row);
					$debug_pagesphp = 'estoy en getUserPageFavorites if query';

					//$std = new stdClass();
					//$std->user_id = $row['user_id'];
					//$std->debug = 'getUserPageFavorites if query ok. debug_pagesphp: '.$debug_pagesphp. '. count(result)='.count($result);

					//$output[] = $std;
					$output[] = $row['page_id'];
				}
			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - getUserPageFavorites!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}

	public static function getUserFriends($user_id) {
		//$sql = "select * from voces where userId=$userId"; sin limite de estar subscrito a notificaciones o no. Lo usa page-settings.php tab: friends
		$sql_pdo = "select distinct friend_id from social_friends where user_id=:user_id";
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getUserFriends)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getUserFriends)';
			//$stmt->execute();
			$stmt->execute( array(
					':user_id' => $user_id
					));
			$result = $stmt->fetchAll();
			if ( count($result) ) { 
				foreach($result as $row) {
					//print_r($row);
					$debug_pagesphp = 'estoy en getUserFriends if query';

					//$std = new stdClass();
					//$std->user_id = $row['user_id'];
					//$std->debug = 'getFriendSubscribers if query ok. debug_pagesphp: '.$debug_pagesphp. '. count(result)='.count($result);

					//$output[] = $std;
					$output[] = $row['friend_id'];
				}
			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - getUserFriends!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}

	public static function getUserPages($user_id) {
		
		//$sql_pdo = "select distinct p.page_id, p.page_uri, p.page_title, p.page_header, p.page_avatar, count(v.comment_id) as num_voces, p.num_filtros, p.creation_date from voces as v, voc_pages as p where p.video_id=v.video_id and v.user_id=:user_id";
		$sql_pdo = "select p.page_id, p.page_uri, p.page_title, p.page_header, p.page_avatar, count(v.comment_id) as num_voces, p.num_filtros, p.creation_date, count(v.is_reply) as num_replies, count(vf.id) as num_likes 
					from voc_pages as p, voces as v LEFT JOIN social_voce_favorites as vf ON (v.user_id=vf.user_id and v.comment_id=vf.voce_id)
					where p.video_id=v.video_id and v.user_id=:user_id 
					group by p.page_id";

		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getUserPages)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getUserPages)';
			//$stmt->execute();
			$stmt->execute( array(
					':user_id' => $user_id
					));
			$result = $stmt->fetchAll();
			if ( count($result)>0 ) { 
				foreach($result as $row) {
					//print_r($row);
					$debug_pagesphp = 'estoy en getUserPages if query';

					$std = new stdClass();
					$std->page_id = $row['page_id'];
					$std->page_uri = $row['page_uri'];			// page_uri contiene dominio! y empieza con //
					$std->page_title = $row['page_title'];
					$std->page_header = $row['page_header'];
					$std->page_avatar = $row['page_avatar'];
					$std->num_voces = $row['num_voces'];
					$std->num_filters = $row['num_filtros'];
					//$std->num_languages = $result['num_languages'];
					$std->date_created = $row['creation_date'];
					$std->num_replies = $row['num_replies'];
					$std->num_likes = $row['num_likes'];


					$std->debug = 'getUserPages if query ok. debug_pagesphp: '.$debug_pagesphp. '. count(result)='.count($result);
					$std->resultado_sql = 'resultado_sql: '.json_encode($result);

					$output[] = $std;
					//$output[] = $row['friend_id'];
				}
			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - getUserPages!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}



	public static function getFriendSubscribers($user_id) {
		//$sql = "select * from voces where userId=$userId";
		$sql_pdo = "select distinct f.user_id from social_friends as f, social_notifications_user_preferences as up where up.user_id=f.user_id and up.friend_activity=1 and f.friend_id=:user_id";
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getFriendSubscribers)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getFriendSubscribers)';
			//$stmt->execute();
			$stmt->execute( array(
					':user_id' => $user_id
					));
			$result = $stmt->fetchAll();
			if ( count($result) ) { 
				foreach($result as $row) {
					//print_r($row);
					$debug_pagesphp = 'estoy en getFriendSubscribers if query';

					//$std = new stdClass();
					//$std->user_id = $row['user_id'];
					//$std->debug = 'getFriendSubscribers if query ok. debug_pagesphp: '.$debug_pagesphp. '. count(result)='.count($result);

					//$output[] = $std;
					$output[] = $row['user_id'];
				}
			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - getFriendSubscribers!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}

	public static function getUserPreferences($userId) {
		//$sql = "select * from commenters where userId=$userId";
		$sql_pdo = "select * from social_notifications_user_preferences where user_id=:userId";
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (getUserPreferences)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (getUserPreferences)';
			//$stmt->execute();
			$stmt->execute( array(
					':userId' => $userId
					));
			$result = $stmt->fetchAll();
			$std = new stdClass();
			if ( count($result) ) { 
				foreach($result as $row) {
					//print_r($row);
					$std->user_id = $row['user_id'];
					$std->send_email = $row['send_email'];
					$std->page_activity = $row['page_activity'];
					$std->voce_activity = $row['voce_activity'];
					$std->private_voce_activity = $row['private_voce_activity'];
					$std->friend_activity = $row['friend_activity'];
				$row_user = $row['user_id'];
				$row_send_email = $row['send_email'];
				$row_page_activity = $row['page_activity'];
				$row_voce_activity = $row['voce_activity'];
				$row_private = $row['private_voce_activity'];
				$row_friend_activity = $row['friend_activity'];
					$std->debug = 'getUserPreferences if query ok, con userId: '.$row_user.', send_email: '.$row_send_email.', page_activity: '.$row_page_activity.', voce_activity: '.$row_voce_activity.', private_voce_activity: '.$row_private.', friend_activity: '.$row_friend_activity;
					$debug_pagesphp = 'estoy en getUserPreferences if query';
					$debug_conexio .= 'rowCount > 0, y ' . $std->debug;

					$output[] = $std;
				}
			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - getUserPreferences!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output[0];
	}

	public static function setNewUserPreferences($userId) {
		//$sql = "select * from commenters where userId=$userId";
		$sql_pdo = "insert into social_notifications_user_preferences (user_id) values (:userId)";
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (setNewUserPreferences)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (setNewUserPreferences)';
			//$stmt->execute();
			$result = $stmt->execute( array(
					':userId' => $userId
					));
			//$result = $stmt->fetchAll();
			$std = new stdClass();
			if ( count($result) ) { 
					//print_r($row);
					$std->result = 1;
					$std->debug = 'setNewUserPreferences if query ok';
					$debug_pagesphp = 'estoy en setNewUserPreferences if query';
					$debug_conexio .= 'rowCount > 0, y ' . $std->debug;
			 }
			 $output[] = $std;
		}
		catch(PDOException $e){
			echo 'catch en socialphp - setNewUserPreferences!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}

	public static function updateUserPreferences($userId, $send_email, $page_activity, $voce_activity, $private_voce_activity, $friend_activity) {
		//$sql = "select * from commenters where userId=$userId";
		$sql_pdo = "update social_notifications_user_preferences set send_email=:send_email, page_activity=:page_activity, voce_activity=:voce_activity, private_voce_activity=:private_voce_activity, friend_activity=:friend_activity where user_id=:userId";
				
		$output = array();
		
		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (updateUserPreferences)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$stmt = $db->prepare($sql_pdo);
		try{
			$debug_conexio .= 'dins de try (updateUserPreferences)';
			//$stmt->execute();
			$result = $stmt->execute( array(
					':userId' => (int) $userId,
					':send_email' => (int) $send_email,
					':page_activity' => (int) $page_activity,
					':voce_activity' => (int) $voce_activity,
					':private_voce_activity' => (int) $private_voce_activity,
					':friend_activity' => (int) $friend_activity
					));
			//$result = $stmt->fetchAll();
			$std = new stdClass();
			if ( count($result) ) { 
					//print_r($row);

					$get_preferences = self::getUserPreferences($userId);

					$std->user_id = $get_preferences->user_id;
					$std->send_email = $get_preferences->send_email;
					$std->page_activity = $get_preferences->page_activity;
					$std->voce_activity = $get_preferences->voce_activity;
					$std->private_voce_activity = $get_preferences->private_voce_activity;
					$std->friend_activity = $get_preferences->friend_activity;
					$std->debug_get = $get_preferences->debug;


					$std->debug = 'updateUserPreferences if query ok, con debug_get: '.$get_preferences->debug;
					$debug_pagesphp = 'estoy en updateUserPreferences if query';
					$debug_conexio .= 'rowCount > 0, y ' . $std->debug;
					$output[] = $std;
			 }
		}
		catch(PDOException $e){
			echo 'catch en socialphp - updateUserPreferences!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		
		return $output;
	}

	public static function insert_notification_voce_replied_____________old($voce_id, $user_sender_id, $user_sender_name, $user_sender_role, $voce_name) {
		//for each user_id en "select user_id where comment_id = $lastId", && 'social_notifications_user_preferences'.voce_activity=1

		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (insert_notification_voce_replied)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$output = array();
		$date_created = Date( "Y-m-d H:i:s" );
		// get $voce_URL
		$page = Comments::getPage($voce_id);		// "/lvti-premium/demo5premium2.php"; 	 
		$page_URL = $page->uri;
		$page_name = $page->title;


		// get recipients. -> Do foreach
		// && 'social_notifications_user_preferences'.voce_activity=1
		$recipientIds = self::getVoceSubscribers($voce_id);	// pending to be read.
		/*
		echo('<script>console.log("recipientIds="+JSON.stringify('.print_r($recipientIds).'));</script>');
		echo('<script>console.log("recipientIds='.$recipientIds[0].', length: '.count($recipientIds).'");</script>');
		$aux_recipients = "";
		foreach ($recipientIds as $recipientId) {
			$aux_recipients .= ', '.$recipientId;
		}
		echo ('aux_recipients='.$aux_recipients);
		exit;
		*/

		$subject = 'Reply to your comment';	// 'Reply to your comment'
		if ($user_sender_role == 'guest') {
			$content = 'A guest user';
		}
		else {
			$content = $user_sender_role . ' <a href="/lvti-premium/modules/vl/social/profile.php?uid=' .$user_sender_id.'">'.$user_sender_name. '</a>';
		}
		
		$content .= ' has replied to your <a href="'.$voce_URL.'">comment</a>';	
					// 'User/Agent/Admin/Guest <a href="(user_profile_URL)">(user_name)</a> has replied to your <a href="(voce_URL)">comment</a>'.
		$read_status = '1';	// pending to be read.

		$output = array();
		// PENDIENTE MEJORAR para optimizar rendimiento a gran volumen de usuarios... Ver: http://stackoverflow.com/q/1176352/1414176
		foreach ($recipientIds as $recipientId) {	//$key => $value
			if ($recipientId != $user_sender_id) {
				//$debug_recipients .= $recipientId.', ';
				//$sql_pdo = "insert into social_notifications ( date_created, sender_id, recipient_id, subject, content, read_status ) values( :date , :senderId , :recipientId , :subject , :content, :read_status ) ";
				$sql_pdo = "insert into social_notifications values( :date , :senderId , :recipientId , :subject , :content, :read_status ) ";
				$stmt = $db->prepare($sql_pdo);

				try{
					$debug_conexio .= 'dins de try (insert_notification_voce_replied), amb sql_pdo: '.$sql_pdo.', voce_id: '.$voce_id.', user_sender_id: '.$user_sender_id.', user_sender_name: '.$user_sender_name.', user_sender_role:'.$user_sender_role.', i voce_name: '.$voce_name.'.';
					//$stmt->execute();
					/*
					$stmt->execute( array(
							':date' => $date_created,
							':senderId' => $user_sender_id,
							':recipientId' => $recipientId,	
							':subject' => $subject,
							':content' => $content,	
							':read_status' => $read_status
							));
					*/
					//$subject=$subject.' (s:'.$user_sender_id.', r:'.$recipientId.')';

					//echo('<script>console.log("sql_pdo='.$sql_pdo.'");</script>');
					//echo('<script>console.log("debug_conexio='.$debug_conexio.'");</script>');
					//echo 'debug_conexio = '.$debug_conexio;
					//return $sql_pdo;
					//exit;
					/*
					$stmt->execute( array(
							':date' => '2015-11-19 07:33:12',	//Date($date_created, "Y-m-d H:i:s"),
							':senderId' => '33',	//int($user_sender_id),
							':recipientId' => '35',	//int($recipientId),
							':subject' => 'Reply to your comment (test)',	//strval($subject),
							':content' => 'Content TEST',	//strval($content),	
							':read_status' => '0'	//int($read_status)
							));
					*/
					$output_sql = $stmt->execute( array(
							':date' => '2015-11-19 07:33:12',
							':senderId' => '33',
							':recipientId' => '35',
							':subject' => 'Reply to your comment (test)',
							':content' => 'Content TEST',
							':read_status' => '0'
							));
					/*
					$stmt->execute( array(
							':date' => '2015-11-18 07:33:12',
							':senderId' => '33',
							':recipientId' => '35',								//Object of class stdClass could not be converted to string 
							':subject' => 'Reply to your comment (test)',
							':content' => 'Content TEST',	
							':read_status' => '0'
							));
					*/

					$result_dbg = $stmt->fetch();
					$output[] = "result_dbg: ".$result_dbg;
					$output_sql_id = $db->lastInsertId();
					$output[] = "output_sql: ".$output_sql.', output_sql_id: '.$output_sql_id;
					//exit;

					 if($stmt->rowCount() > 0){
					 	$output[]=1;
						return $output;
					}
				}
				catch(PDOException $e){
					echo 'catch en insert_notification_voce_replied!!, y debug_conexio: ' . $debug_conexio;
					echo 'retorno de recipients: '.$recipients.', || debug_recipients: '.$debug_recipients;
					echo 'Error: ' . $e->getMessage();
					return false;
				}
			} // fin if recipient != sender
		}

		return $output.", fin insert_notification_voce_replied";
	}


	public static function insert_notification_voce_replied($voce_id, $user_sender_id, $user_sender_name, $user_sender_role, $voce_name, $social_activity_id) {
		//for each user_id en "select user_id where comment_id = $lastId", && 'social_notifications_user_preferences'.voce_activity=1

		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (insert_notification_voce_replied)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$output = array();
		$date_created = Date( "Y-m-d H:i:s" );
		// get $voce_URL
		$page = Comments::getPage($voce_id);		// "/lvti-premium/demo5premium2.php"; 	 
		$page_URL = $page->uri;				
		$page_title = $page->title;
		$voce_URL = $page_URL.'#'.$voce_name;

		// get recipients. -> Do foreach
		// && 'social_notifications_user_preferences'.voce_activity=1
		$recipientIds = self::getVoceSubscribers($voce_id);	// pending to be read.

		$subject = 'Reply to your comment';	// 'Reply to your comment'
		if ($user_sender_role == 'guest') {
			$content = 'A guest user';
		}
		else {
			$content = $user_sender_role . ' <a href="/lvti-premium/modules/vl/social/profile.php?uid=' .$user_sender_id.'">'.$user_sender_name. '</a>';
		}
		
		$content .= ' has replied to your <a href="'.$voce_URL.'">comment</a>';	
					// 'User/Agent/Admin/Guest <a href="(user_profile_URL)">(user_name)</a> has replied to your <a href="(voce_URL)">comment</a>'.
		$read_status = 1;	// pending to be read.

		$output = array();
		// PENDIENTE MEJORAR para optimizar rendimiento a gran volumen de usuarios... Ver: http://stackoverflow.com/q/1176352/1414176 y http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers

		$recipientId = '';
		$sql_pdo = "insert into social_notifications ( date_created, sender_id, recipient_id, subject, content, read_status, social_activity_id ) values( :date , :senderId , :recipientId , :subject , :content, :read_status, :social_activity_id ) ";
		$stmt = $db->prepare($sql_pdo);
		$stmt->bindParam(':date', $date_created, PDO::PARAM_STR);
		$stmt->bindParam(':senderId', $user_sender_id, PDO::PARAM_INT);
		$stmt->bindParam(':recipientId', $recipientId, PDO::PARAM_INT);
		$stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
		$stmt->bindParam(':content', $content, PDO::PARAM_STR);
		$stmt->bindParam(':read_status', $read_status, PDO::PARAM_BOOL);
		$stmt->bindParam(':social_activity_id', $social_activity_id, PDO::PARAM_INT);

		try{
			$debug_conexio .= 'dins de try (insert_notification_voce_replied), amb sql_pdo: '.$sql_pdo.', voce_id: '.$voce_id.', user_sender_id: '.$user_sender_id.', user_sender_name: '.$user_sender_name.', user_sender_role:'.$user_sender_role.', i voce_name: '.$voce_name.'.';

			foreach ($recipientIds as $recipientId) {	//$key => $value
				if ($recipientId != $user_sender_id) {

					$output_sql = $stmt->execute();	

					if($stmt->rowCount() > 0){
					 	$output[]=1;
						//return $output;
					}
				}
			}
		}
		catch(PDOException $e){
			echo 'catch en insert_notification_voce_replied!!, y debug_conexio: ' . $debug_conexio;
			//echo 'retorno de recipients: '.$recipients.', || all_recipients: '.$all_recipients;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		return $output;
	}

	public static function insert_notification_page_updated($page_id, $voce_id, $user_sender_id, $user_sender_name, $user_sender_role, $social_activity_id) {

		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (insert_notification_page_updated)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$output = array();
		$date_created = Date( "Y-m-d H:i:s" );
		// get $voce_URL
		$page = Comments::getPage($voce_id);		// "/lvti-premium/demo5premium2.php"; 	
		$page_URL = $page->uri;
		$voce_name = $page->name;
		$page_title = $page->title;
		// get recipients. -> Do foreach

		//		if (!$page_id) {
		//			$page_id=$page->video_id;
		//		}
		//$output_page = print_r($page, true);					// DEBUG
		//$output[]="page: ".$output_page;						// DEBUG

		$recipientIds = self::getPageSubscribers($page_id);	// pending to be read.

		$subject_page = 'Your favorite page has been updated';	// 'Reply to your comment'
		if ($user_sender_role == 'guest') {
			$content_page = 'A guest user';
		}
		else {
			$content_page = $user_sender_role . ' <a href="/lvti-premium/modules/vl/social/profile.php?uid=' .$user_sender_id.'">'.$user_sender_name. '</a>';
		}
		
		$content_page .= ' has updated your favorite page <a href="'.$page_URL.'">'.$page_title.'</a>';	
					// 'User/Agent/Admin/Guest <a href="(user_profile_URL)">(user_name)</a> has replied to your <a href="(voce_URL)">comment</a>'.
		$read_status = 1;	// pending to be read.

		//$output = array();

		$recipientId = '';
		$sql_pdo = "insert into social_notifications ( date_created, sender_id, recipient_id, subject, content, read_status, social_activity_id ) values( :date , :senderId , :recipientId , :subject , :content, :read_status, :social_activity_id ) ";
		$stmt = $db->prepare($sql_pdo);
		$stmt->bindParam(':date', $date_created, PDO::PARAM_STR);
		$stmt->bindParam(':senderId', $user_sender_id, PDO::PARAM_INT);
		$stmt->bindParam(':recipientId', $recipientId, PDO::PARAM_INT);
		$stmt->bindParam(':subject', $subject_page, PDO::PARAM_STR);
		$stmt->bindParam(':content', $content_page, PDO::PARAM_STR);
		$stmt->bindParam(':read_status', $read_status, PDO::PARAM_BOOL);
		$stmt->bindParam(':social_activity_id', $social_activity_id, PDO::PARAM_INT);

		$output[]="entrando en try, con pageId: ".$page_id.", con recipientIds length: ".count($recipientIds);
		$output[]=print_r($recipientIds, true);
		
		try{
			$debug_conexio .= 'dins de try (insert_notification_page_updated), amb sql_pdo: '.$sql_pdo.', voce_id: '.$voce_id.', user_sender_id: '.$user_sender_id.', user_sender_name: '.$user_sender_name.', user_sender_role:'.$user_sender_role.', i voce_name: '.$voce_name.'.';

			foreach ($recipientIds as $recipientId) {	//$key => $value
				$output[]="entro en foreach, recipient_id: ".$recipientId;
				if ($recipientId != $user_sender_id) {

					$output_sql = $stmt->execute();	

					if($stmt->rowCount() > 0){
					 	//$output[]=1;
					 	$output[]=$recipientId;
						//return $output;
					}
				}
			}
		}
		catch(PDOException $e){
			echo 'catch en insert_notification_page_updated!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		return $output;
	}

	public static function insert_notification_friend_replied($voce_id, $user_sender_id, $user_sender_name, $user_sender_role, $voce_name, $social_activity_id) {

		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (insert_notification_friend_replied)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		$output = array();
		$date_created = Date( "Y-m-d H:i:s" );
		// get $voce_URL
		$page = Comments::getPage($voce_id);		// "/lvti-premium/demo5premium2.php"; 	
		$page_URL = $page->uri;
		$page_title = $page->title;
		$voce_URL = $page_URL.'#'.$voce_name;

		//		$voce_URL = $page_URL."#".$voce_name;
		// get recipients. -> Do foreach



		$recipientIds = self::getFriendSubscribers($user_sender_id);	// pending to be read.

		$subject = 'A friend published new content';	// 'Reply to your comment'
		if ($user_sender_role == 'guest') {
			$content = 'A guest user';
		}
		else {
			$content = $user_sender_role . ' <a href="/lvti-premium/modules/vl/social/profile.php?uid=' .$user_sender_id.'">'.$user_sender_name. '</a>';
		}
		
		$content .= ' has published a comment in the page <a href="'.$voce_URL.'">'.$page_title.'</a>';	
					// 'User/Agent/Admin/Guest <a href="(user_profile_URL)">(user_name)</a> has replied to your <a href="(voce_URL)">comment</a>'.
		$read_status = 1;	// pending to be read.

		$output = array();

		$recipientId = '';
		$sql_pdo = "insert into social_notifications ( date_created, sender_id, recipient_id, subject, content, read_status, social_activity_id ) values( :date , :senderId , :recipientId , :subject , :content, :read_status, :social_activity_id ) ";
		$stmt = $db->prepare($sql_pdo);
		$stmt->bindParam(':date', $date_created, PDO::PARAM_STR);
		$stmt->bindParam(':senderId', $user_sender_id, PDO::PARAM_INT);
		$stmt->bindParam(':recipientId', $recipientId, PDO::PARAM_INT);
		$stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
		$stmt->bindParam(':content', $content, PDO::PARAM_STR);
		$stmt->bindParam(':read_status', $read_status, PDO::PARAM_BOOL);
		$stmt->bindParam(':social_activity_id', $social_activity_id, PDO::PARAM_INT);

		try{
			$debug_conexio .= 'dins de try (insert_notification_friend_replied), amb sql_pdo: '.$sql_pdo.', voce_id: '.$voce_id.', user_sender_id: '.$user_sender_id.', user_sender_name: '.$user_sender_name.', user_sender_role:'.$user_sender_role.', i voce_name: '.$voce_name.'.';

			foreach ($recipientIds as $recipientId) {	//$key => $value
				if ($recipientId != $user_sender_id) {

					$output_sql = $stmt->execute();	

					if($stmt->rowCount() > 0){
					 	$output[]=1;
						//return $output;
					}
				}
			}
		}
		catch(PDOException $e){
			echo 'catch en insert_notification_friend_replied!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}

		return $output;
	}



	public static function get_is_page_favorite($user_id, $page_id) {

		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (get_is_page_favorited)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		$sql_pdo = "select id from social_page_favorites where user_id=:user_id and page_id=:page_id";
		$stmt = $db->prepare($sql_pdo);
		//$output = array();

		try{
			$debug_conexio .= 'dins de try (get_is_page_favorited), amb user_id: '.$user_id.', page_id: '.$page_id;
			$result = $stmt->execute( array(
					':user_id' => $user_id,
					':page_id' => $page_id
					));
			//$result = $stmt->fetchAll();
			$rows = $stmt->fetchAll();
						
			$debug_conexio .= ', num_rows es: ' . count($rows);
			if ( count($rows) > 0 ) { 

				$std = new stdClass();
				$std->debug = $debug_conexio;
				$output = 1;	//$std;
			 }
			 else {
				$output = 0;		// cuando no hay comentarios, fetchColumn retorna null (select count = 0 -> no hay columnas.
				$debug_conexio .= 'get_is_page_favorited en ELSE, result: ' . $result . ' , count(rows): 0, '. count($rows);

			 }
		}
		catch(PDOException $e){
			echo 'catch en get_is_page_favorited!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			$output = 0;
			return false;
		}
		
		return $output;
	}



	public static function insert_fav_this_page($user_id, $page_id) {

		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (insert_fav_this_page)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		//$output = array();

		$sql_pdo = "insert into social_page_favorites (user_id , page_id ) values (:user_id , :page_id )";
		$stmt = $db->prepare($sql_pdo);


		try{
			$debug_conexio .= 'dins de try (comments insert_fav_this_page)';
			//$stmt->execute();
			$result = $stmt->execute( array(
					':user_id' => $user_id,
					':page_id' => $page_id
					));
			//$result = $stmt->fetch();
			
			$nuevo_page_fav_id = $db->lastInsertId();
			if ( count($result) > 0 ) {
				
				//$std = new stdClass();
				//$std->comment_id = $nuevo_page_fav_id;

				$output = 1;
				//return 1;
			 }
			 else {
				$debug_conexio .= ' comments insert_fav_this_page en ELSE, count(result): 0, '. count($result);
				$output = 0;
			 }
		}
		catch(PDOException $e){
			echo 'catch en comments insert_fav_this_page!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		return $output;
	}



	public static function delete_fav_this_page($user_id, $page_id) {

		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (delete_fav_this_page)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		//$output = array();

		$sql_pdo = "delete from social_page_favorites where user_id=:user_id and page_id=:page_id ";
		$stmt = $db->prepare($sql_pdo);


		try{
			$debug_conexio .= 'dins de try (comments delete_fav_this_page)';
			//$stmt->execute();
			$result = $stmt->execute( array(
					':user_id' => $user_id,
					':page_id' => $page_id
					));
			//$result = $stmt->fetch();
			
			if ( count($result) > 0 ) {
				
				//$std = new stdClass();
				//$std->comment_id = $nuevo_page_fav_id;

				$output = 1;
				//return 1;
			 }
			 else {
				$debug_conexio .= ' comments delete_fav_this_page en ELSE, count(result): 0, '. count($result);
				$output = 0;
			 }
		}
		catch(PDOException $e){
			echo 'catch en comments delete_fav_this_page!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		return $output;
	}




	public static function get_is_user_favorite($user_id, $friend_id) {

		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (get_is_user_favorite)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		$sql_pdo = "select id from social_friends where user_id=:user_id and friend_id=:friend_id";
		$stmt = $db->prepare($sql_pdo);
		//$output = array();

		try{
			$debug_conexio .= 'dins de try (get_is_user_favorite), amb user_id: '.$user_id.', friend_id: '.$friend_id;
			$result = $stmt->execute( array(
					':user_id' => $user_id,
					':friend_id' => $friend_id
					));
			//$result = $stmt->fetchAll();
			$rows = $stmt->fetchAll();
						
			$debug_conexio .= ', num_rows es: ' . count($rows);
			if ( count($rows) > 0 ) { 

				$std = new stdClass();
				$std->debug = $debug_conexio;
				$output = 1;	//$std;
			 }
			 else {
				$output = 0;		// cuando no hay comentarios, fetchColumn retorna null (select count = 0 -> no hay columnas.
				$debug_conexio .= 'get_is_user_favorite en ELSE, result: ' . $result . ' , count(rows): 0, '. count($rows);

			 }
		}
		catch(PDOException $e){
			echo 'catch en get_is_user_favorite!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			$output = 0;
			return false;
		}
		
		return $output;
	}

	public static function insert_fav_this_friend($user_id, $friend_id) {

		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (insert_fav_this_friend)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		//$output = array();

		$sql_pdo = "insert into social_friends (user_id , friend_id ) values (:user_id , :friend_id )";
		$stmt = $db->prepare($sql_pdo);


		try{
			$debug_conexio .= 'dins de try (comments insert_fav_this_friend)';
			//$stmt->execute();
			$result = $stmt->execute( array(
					':user_id' => $user_id,
					':friend_id' => $friend_id
					));
			//$result = $stmt->fetch();
			
			$nuevo_page_fav_id = $db->lastInsertId();
			if ( count($result) > 0 ) {
				
				//$std = new stdClass();
				//$std->comment_id = $nuevo_page_fav_id;

				$output = 1;
				//return 1;
			 }
			 else {
				$debug_conexio .= ' comments insert_fav_this_friend en ELSE, count(result): 0, '. count($result);
				$output = 0;
			 }
		}
		catch(PDOException $e){
			echo 'catch en comments insert_fav_this_friend!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		return $output;
	}



	public static function delete_fav_this_friend($user_id, $friend_id) {

		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (delete_fav_this_friend)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		//$output = array();

		$sql_pdo = "delete from social_friends where user_id=:user_id and friend_id=:friend_id ";
		$stmt = $db->prepare($sql_pdo);


		try{
			$debug_conexio .= 'dins de try (comments delete_fav_this_friend)';
			//$stmt->execute();
			$result = $stmt->execute( array(
					':user_id' => $user_id,
					':friend_id' => $friend_id
					));
			//$result = $stmt->fetch();
			
			if ( count($result) > 0 ) {
				
				//$std = new stdClass();
				//$std->comment_id = $nuevo_page_fav_id;

				$output = 1;
				//return 1;
			 }
			 else {
				$debug_conexio .= ' comments delete_fav_this_friend en ELSE, count(result): 0, '. count($result);
				$output = 0;
			 }
		}
		catch(PDOException $e){
			echo 'catch en comments delete_fav_this_friend!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		return $output;
	}


	public static function get_is_voce_favorite($user_id, $voce_id) {

		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (get_is_voce_favorite)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		$sql_pdo = "select id from social_voce_favorites where user_id=:user_id and voce_id=:voce_id";
		$stmt = $db->prepare($sql_pdo);
		//$output = array();

		try{
			$debug_conexio .= 'dins de try (get_is_voce_favorite), amb user_id: '.$user_id.', voce_id: '.$voce_id;
			$result = $stmt->execute( array(
					':user_id' => $user_id,
					':voce_id' => $voce_id
					));
			//$result = $stmt->fetchAll();
			$rows = $stmt->fetchAll();
						
			$debug_conexio .= ', num_rows es: ' . count($rows);
			if ( count($rows) > 0 ) { 

				$std = new stdClass();
				$std->debug = $debug_conexio;
				$output = 1;	//$std;
			 }
			 else {
				$output = 0;		// cuando no hay comentarios, fetchColumn retorna null (select count = 0 -> no hay columnas.
				$debug_conexio .= 'get_is_voce_favorite en ELSE, result: ' . $result . ' , count(rows): 0, '. count($rows);

			 }
		}
		catch(PDOException $e){
			echo 'catch en get_is_voce_favorite!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			$output = 0;
			return false;
		}
		
		return $output;
	}

	public static function insert_fav_this_voce($user_id, $voce_id, $original_voce_id) {

		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (insert_fav_this_voce)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		//$output = array();

		$sql_pdo = "insert into social_voce_favorites (user_id , voce_id, original_voce_id ) values (:user_id , :voce_id, :original_voce_id )";
		$stmt = $db->prepare($sql_pdo);


		try{
			$debug_conexio .= 'dins de try (comments insert_fav_this_voce)';
			//$stmt->execute();
			$result = $stmt->execute( array(
					':user_id' => $user_id,
					':voce_id' => $voce_id,
					':original_voce_id' => $original_voce_id
					));
			//$result = $stmt->fetch();
			
			$nuevo_page_fav_id = $db->lastInsertId();
			if ( count($result) > 0 ) {
				
				//$std = new stdClass();
				//$std->comment_id = $nuevo_page_fav_id;

				$output = 1;
				//return 1;
			 }
			 else {
				$debug_conexio .= ' comments insert_fav_this_voce en ELSE, count(result): 0, '. count($result);
				$output = 0;
			 }
		}
		catch(PDOException $e){
			echo 'catch en comments insert_fav_this_voce!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		return $output;
	}



	public static function delete_fav_this_voce($user_id, $voce_id, $original_voce_id) {

		if (!isset($db)) {
			$debug_conexio = 'no estava definida db (delete_fav_this_voce)';
			require(SCRIPT_DIR . "connect.php");					// con require_ONCE no funciona (porque db_connect lo 'machaca').
		}
		
		//$output = array();

		$sql_pdo = "delete from social_voce_favorites where user_id=:user_id and voce_id=:voce_id and original_voce_id=:original_voce_id ";
		$stmt = $db->prepare($sql_pdo);


		try{
			$debug_conexio .= 'dins de try (comments delete_fav_this_voce)';
			//$stmt->execute();
			$result = $stmt->execute( array(
					':user_id' => $user_id,
					':voce_id' => $voce_id,
					':original_voce_id' => $original_voce_id
					));
			//$result = $stmt->fetch();
			
			if ( count($result) > 0 ) {
				
				//$std = new stdClass();
				//$std->comment_id = $nuevo_page_fav_id;

				$output = 1;
				//return 1;
			 }
			 else {
				$debug_conexio .= ' comments delete_fav_this_voce en ELSE, count(result): 0, '. count($result);
				$output = 0;
			 }
		}
		catch(PDOException $e){
			echo 'catch en comments delete_fav_this_voce!!, y debug_conexio: ' . $debug_conexio;
			echo 'Error: ' . $e->getMessage();
			return false;
		}
		return $output;
	}

}

?>