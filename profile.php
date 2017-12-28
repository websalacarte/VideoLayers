<?php
$msg = "";
if(isset($_GET['msg'])){
	$msg = preg_replace('#[^a-z0-9%]#i', '', $_GET['user']);
	$msg = str_replace('%20', ' ', $msg);
}
$po_id =
$avatar = "";
$fullName = "";
$location = "";
$birthday = "";
$friends_count = "";
$enemies_count = ""; 
$username = "";
$banner = "";
include_once("scripts/check_user.php");
if(!isset($_GET['user']) || $_GET['user'] == ""){
	header("location: oops.php?msgcode=p1");
	$db = null;
	exit();
}
if($user_is_logged != true){
	header("Location: oops.php?msgcode=p2");
	$db = null;
	exit();
}
$pageowner = preg_replace('#[^a-z0-9_.-]#i', '', $_GET['user']);
$stmt = $db->prepare("SELECT id, ext_id, username, full_name, avatar, banner FROM members WHERE username=:pageowner AND activated='1' LIMIT 1");
$stmt->bindValue(':pageowner',$pageowner,PDO::PARAM_STR);
	try{
		$stmt->execute();
	}
	catch(PDOException $e){
		//echo $e->getMessage();
		print_r($e->getTrace());
		$db = null;
		exit();
	}
$user_exists = $stmt->rowCount();
if($user_exists == 0){
	header("location: oops.php?msgcodde=p3");
}
function isPageOwner($user, $pagename){
	if($user == $pagename){
		return true;
	}else{
		return false;
	}
}
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$po_id = $row['id'];
	$ext_id = $row['ext_id'];
	$username = $row['username'];
	$avatar = $row['avatar'];
	$fullName = $row['full_name'];
	$banner = $row['banner'];
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<title>Profile at VideoLayers</title>
<?php include_once("head_common.php"); ?>


<!--<link rel="stylesheet" href="style/profile.css"/>-->
<script type="text/javascript" src="js/serialize.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript">
window.onload = function(){
	var start_date = 2013;
		for(var i=start_date; i>1900; i--){
			document.getElementById('birthyear').innerHTML += '<option value='+i+'>'+i+'</option>';
	}
}
function details_init(e){
var details = document.getElementById('details').id;
var settings = document.getElementById('settings').id;
var privacy = document.getElementById('privacy').id;
var detailsMenu = [details,settings,privacy];
console.log(detailsMenu);
console.log(e);
var target = document.getElementById(e).id;
navmenus(target,detailsMenu);
}
function uploads_init(e){
var avatar_div = document.getElementById('avatar_upload').id;
var banner_div = document.getElementById('banner_upload').id;
var detailsMenu = [avatar_div,banner_div];
console.log(detailsMenu);
console.log(e);
var target = document.getElementById(e).id;
navmenus(target,detailsMenu);
}
function navmenus(x, ma){
	for (var m in ma) {
		if(ma[m] != x){
			document.getElementById(ma[m]).style.display = "none";
		}
	}
	if(document.getElementById(x).style.display == 'block'){
		document.getElementById(x).style.display = "none";
	}
	else{
		document.getElementById(x).style.display = "block";
	}
}
</script>
<script>
function show_lightbox(){
	document.getElementById('light').style.display = 'block';
	document.getElementById('fade').style.display = 'block';
}
function hide_lightbox(){
	document.getElementById('light').style.display = 'none';
	document.getElementById('fade').style.display = 'none';
}
</script>
</head>
<body>
<div class="main_container">
<?php include_once("header_template.php"); ?>
	 
	<!-- CONTAINER -->
	<h2>Register for a <span>Video Layers</span> account</h2>
	<div class="container">
		<div id="form">
			<div class="user-details">
				<div style="width:600px; text-align:left; padding:16px; float:left;">
					<?php if ($fullName != ''): ?>
					<h2><strong><?php echo $fullName; ?></strong></h2>
					<?php else: ?>
					<h2><strong><?php echo $username; ?></strong></h2>
					<?php endif; ?>  
				</div>
			</div>
			<!--<div class="clearfloat" style="height:0px;"></div> -->
			<div class="sidebar1" style="clear:both;">
			<div class="avatar">
				<?php if ($avatar != '' && file_exists("members/$po_id/$avatar")): ?>
				<img src="members/<?php echo $po_id.'/'.$avatar ?>" alt="<?php echo $username ?>" />
				<?php else: ?>
				<img src="imatges/default_avatar.png" alt="<?php echo $username ?>" />
				<?php endif; ?>
			</div>
			<?php if(isPageOwner($log_uname,$pageowner)): ?>
			<ul class="nav" style="margin-top:26px;">
				<li>
					<a href="#" onclick="show_lightbox();">Edit Avatar/Banner <img src="imatges/tickdown.png" alt="toggle"/></a>
				</li>
			</ul>
			<?php else: ?>
			<ul class="nav" style="margin-top:26px;">
				<li>
					<a href="#" onclick="return false;" onmousedown="showToggle('interactnav');">Interact <img src="imatges/tickdown.png" alt="toggle"/></a>
				</li>
			</ul>
		   <?php endif; ?>
	   
			<?php if(isPageOwner($log_uname,$pageowner)): ?>
			<div id="light" class="white_content">
				<div id="graphics-uploads">
					<strong style="color:#F00; float:right;"><a href="#" onclick="return false;" onmousedown="hide_lightbox()">Cancel</a></strong>
					<p class="submit" style="float:left;"><button type="button" onclick="uploads_init('avatar_upload')">Edit Avatar</button></p>
					<p class="submit" style="float:left;"><button type="button" onclick="uploads_init('banner_upload')">Edit Banner</button></p>
					<div class="page-uploads" id="avatar_upload" style="display:none; clear:both;">
						<form action="profWrite.php" method="post" enctype="multipart/form-data" name="myform">
							<strong>Upload an avatar</strong>
							<input style="none;" name="avatar" type="file">
							<br class="clearfloat" />
							Our system will automatically re size your image, but for best results your image should be between 180 to 200 pixels wide and 180 to 200 pixels in height.
							<p class="submit"><button type="submit">Upload</button></p>
						</form>
					</div>
					<div class="page-uploads" id="banner_upload" style="display:none; clear:both;">
						<form action="profWrite.php" method="post" enctype="multipart/form-data" name="myform">
							<strong>Upload a Banner</strong>
							<input style="none;" name="banner" type="file">
							<br />
							Our system will automatically re size your image, but for best results your image should be between 400 to 600 pixels wide and 180 to 272 pixels in height.
							<p class="submit"><button type="submit">Upload</button></p>
						</form>
					</div>
				</div>
			</div>
			<div id="fade" class="black_overlay"></div>
			  <?php endif; ?>
			  <?php if(isPageOwner($log_uname,$pageowner)): ?>
				<ul class="nav" style="margin-top:68px;">
				  <li><a class="edit-details" href="#" onclick="return false;" onmousedown="details_init('details')">Your Details</a></li>
				  <li><a class="edit-details" href="#" onclick="return false;" onmousedown="details_init('settings')">Settings</a></li>
				  <li><a class="edit-details" href="#"onclick="return false;" onmousedown="details_init('privacy')">Privacy</a></li>
				  <li><a href="#">Inbox</a></li>
				</ul>
				<div id="details" style="display:none">
					<form id="edit_details" class="form" onSubmit="return false;">
						 <em>Full Name:</em>
						 <br />
						   <input type="text" name="fullname" id="fullname">
						 <em>Country</em>
						   <input type="text" name="country" id="country">
						 <em>State/Province</em>
						   <input type="text" name="state" id="state">
						 <em>City/Town</em>
						   <input type="text" name="city" id="city">
						   <br />
						<em>Birth Date</em>
						 <br />
						 <div style="float:left;">
							<input type="text" style="width:28px; height:9px; margin-bottom:8px;" id="birthmonth" placeholder="m/m"> 
							<input type="text" style="width:22px; height:9px; margin-right:8px;" id="birthday" placeholder="d/d">
						 </div>
						 <div class="styled-select"><label><select id="birthyear"></select></label></div>
						 <div class="clearfloat"></div>
						 <div style="float:left;">
							<em>Gender:</em>
							<div class="styled-select">
								<label>
									<select id="gender">
										<option value=""></option>
										<option value="female">Female</option>
										<option value="male">Male</option>
									</select>
								</label>
							</div>
						 </div>
						 <div style="float:left; padding-left:24px; padding-bottom:16px;">
							<br />
							<span class="submit"><button id="detailsForm" type="button">Update</button></span>
							<div id="details_status"></div>
						 </div>
						 <br class="clearfloat" />
					</form>
				</div>
	<script>
	document.getElementById('detailsForm').onclick = function(){
	ajax('edit_details',
		 'POST', 
		 'profWrite.php', 
		 'details_status'
	)};
	</script>
				<div id="settings" style="display:none;">
					This is for editing settings
				</div>
				<div id="privacy" style="display:none;">
					This is for editing privacy
				</div>
				<?php else: ?>
				<div id="interactnav" style="display:none">
					<ul class="nav" style="margin-bottom:68px;">
						<li><a href="#">Add Friend</a></li>
						<li><a href="#">Make Enemy</a></li>
						<li><a href="#">Follow</a></li>
						<li><a href="#">Block <?php echo $pageowner ?></a></li>
					</ul>
				</div>
				<?php endif; ?>
			</div>
			
			 <div class="content">
				<div class="banner">
					<?php if ($banner != '' && file_exists("banners/$po_id/$banner")): ?>
						<img src="banners/<?php echo $po_id.'/'.$banner ?>" alt="no banner" />
					<?php else: ?>
						<img src="imatges/banner_default.png" alt="no banner" />
					<?php endif; ?>
				</div>
				<div class="banner_bottom">
					<ul class="nav2">
						<li><a href="#"><img src="imatges/combubSmall.png" alt="posts"/>Posts</a></li>
						<li><a href="#"><img src="imatges/aboutsmall.png" alt="about"/>About</a></li>
					</ul>
				</div>
				<div class="clearfloat"></div>
			</div>
			
		<div class="clearfloat"></div>
		</div>
  <!-- end .container -->
  </div>
</div>

<?php include_once("foot_common.php"); ?>

</body>
</html>