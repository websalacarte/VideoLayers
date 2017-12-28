<?php

require_once('../includes/defines.php'); 
include_once(SCRIPT_DIR."check_user.php"); 

// log_user_role
// log_user_id
// user_is_logged
// log_uname
// log_pass

	require_once('includes/class-query.php');
	require_once('includes/class-insert.php');
	
	if ( !empty ( $_POST ) ) {
		if ( $_POST['type'] == 'add' ) {
			$add_friend = $insert->add_friend($_POST['user_id'], $_POST['friend_id']);
		}
		
		if ( $_POST['type'] == 'remove' ) {
			$remove_friend = $insert->remove_friend($_POST['user_id'], $_POST['friend_id']);
		}
	}
	
	//$log_user_id = 34;
	
	if ( !empty ( $_GET['uid'] ) ) {
		$user_id = $_GET['uid'];
		$user = $query->load_user_object($user_id);
		
		if ( $log_user_id == $user_id ) {
			$mine = true;
		}
	} else {
		$user = $query->load_user_object($log_user_id);
		$mine = true;
	}

	//$friends = $query->get_friends($log_user_id);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>User profile</title>
		<link rel="stylesheet" href="css/style.css" />
	</head>
	<body>
		<div id="navigation">
			<ul>
				<li><a href="/social">Home</a></li>
				<li><a href="profile-view.php">View Profile</a></li>
				<li><a href="profile-edit.php">Edit Profile</a></li>
				<li><a href="friends-directory.php">Member Directory</a></li>
				<li><a href="friends-list.php">Friends List</a></li>
				<li><a href="feed-view.php">View Feed</a></li>
				<li><a href="feed-post.php">Post Status</a></li>
				<li><a href="messages-inbox.php">Inbox</a></li>
				<li><a href="messages-compose.php">Compose</a></li>
			</ul>
		</div>
		<h1>View Profile</h1>
		<div class="content">
			<p>User name: <?php echo $user->username;//$user->user_nicename; ?></p>
			<p>Name: <?php echo $user->full_name;//$user->user_nicename; ?></p>
			<p>Email Address: <?php echo $user->email;	//$user->user_email; ?></p>
			<p>Gender: <?php echo $user->gender;	//$user->user_email; ?></p>
			<p>Birthday: <?php echo $user->birthday;	//$user->user_email; ?></p>
			<p>City: <?php echo $user->city;	//$user->user_email; ?></p>
			<p>State: <?php echo $user->state;	//$user->user_email; ?></p>
			<p>Country: <?php echo $user->country;	//$user->user_email; ?></p>
			<p>Avatar: <?php echo $user->avatar;	//$user->user_email; ?></p>
			<p>Banner: <?php echo $user->banner;	//$user->user_email; ?></p>
			<p>Email Address: <?php echo $user->email;	//$user->user_email; ?></p>
			<?php if ( !$mine ) : ?>
				<?php if ( !in_array($user_id, $friends) ) : ?>
					<p>
						<form method="post">
							<input name="user_id" type="hidden" value="<?php echo $log_user_id; ?>" />
							<input name="friend_id" type="hidden" value="<?php echo $user_id; ?>" />
							<input name="type" type="hidden" value="add" />
							<input type="submit" value="Add as Friend" />
						</form>

					</p>
				<?php else : ?>
					<p>
						<form method="post">
							<input name="user_id" type="hidden" value="<?php echo $log_user_id; ?>" />
							<input name="friend_id" type="hidden" value="<?php echo $user_id; ?>" />
							<input name="type" type="hidden" value="remove" />
							<input type="submit" value="Remove Friend" />
						</form>
					</p>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</body>
</html>