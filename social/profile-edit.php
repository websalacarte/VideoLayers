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
	
	//$log_user_id = 35;
	
	if ( !empty ( $_POST ) ) {
		$update = $insert->update_user($log_user_id, $_POST);
	}
	
	$user = $query->load_user_object($log_user_id);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Edit Profile</title>
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
		<h1>Edit Profile</h1>
		<div class="content">
			<form method="post">
				<p>
					<label class="labels" for="name">Username:</label>
					<input name="username" type="text" value="<?php echo $user->username;//$user->user_nicename; ?>" />
				</p>
				<p>
					<label class="labels" for="name">Full Name:</label>
					<input name="full_name" type="text" value="<?php echo $user->full_name;//$user->user_nicename; ?>" />
				</p>
				<p>
					<label class="labels" for="email">Email Address:</label>
					<input name="email" type="text" value="<?php echo $user->email;//$user->user_email; ?>" />
				</p>
				<p>
					<label class="labels" for="password">Password:</label>
					<input name="user_pass" type="password" value="<?php echo $user->password;//$user->user_pass; ?>" />
				</p>
				<p>
					<label class="labels" for="name">Gender:</label>
					<input name="gender" type="text" value="<?php echo $user->gender;//$user->user_nicename; ?>" />
				</p>
				<p>
					<label class="labels" for="name">Birthday:</label>
					<input name="birthday" type="text" value="<?php echo $user->birthday;//$user->user_nicename; ?>" />
				</p>
				<p>
					<label class="labels" for="name">City:</label>
					<input name="city" type="text" value="<?php echo $user->city;//$user->user_nicename; ?>" />
				</p>
				<p>
					<label class="labels" for="name">State:</label>
					<input name="state" type="text" value="<?php echo $user->state;//$user->user_nicename; ?>" />
				</p>
				<p>
					<label class="labels" for="name">Country:</label>
					<input name="country" type="text" value="<?php echo $user->country;//$user->user_nicename; ?>" />
				</p>
				<p>
					<label class="labels" for="name">Avatar:</label>
					<input name="avatar" type="text" value="<?php echo $user->avatar;//$user->user_nicename; ?>" />
				</p>
				<p>
					<input type="submit" value="Submit" />
				</p>
			</form>
		</div>
	</body>
</html>