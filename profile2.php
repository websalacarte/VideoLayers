<?php
/* P�gina per al User Profile */

// includes para Profile
include_once('includes/inc-profile.php');

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>User Profile - Video Layers</title>
<?php include_once("head_common.php"); ?>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">   
    
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
    <link href="css/font-awesome.css" rel="stylesheet">
    
    <link href="css/layout-profile-apps.css" rel="stylesheet"> 
    <link href="css/layout-profile2.css" rel="stylesheet"> 
    <link href="css/layout-profile-responsive.css" rel="stylesheet"> 
    
    
    <!--<link href="css/pages/plans.css" rel="stylesheet"> -->

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	
  </head>

<body>


<div class="main_container">
<?php include_once("header_template.php"); ?>
	 
	<!-- CONTAINER -->
	<h2>Register for a <span>Video Layers</span> account</h2>
	<div class="container">
		<div id="form">

<!--	
<div class="navbar navbar-fixed-top">
	
	<div class="navbar-inner">
		
		<div class="container">
			
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> 
				<span class="icon-bar"></span> 
				<span class="icon-bar"></span> 
				<span class="icon-bar"></span> 				
			</a>
			
			<a class="brand" href="/index.php">Video Layers</a>
			
			<div class="nav-collapse">
			
				<ul class="nav pull-right">
					<li>
						<a href="#"><span class="badge badge-warning">7</span></a>
					</li>
					
					<li class="divider-vertical"></li>
					
					<li class="dropdown">
						
						<a data-toggle="dropdown" class="dropdown-toggle " href="#">
							<?php echo $username; ?>
							<b class="caret"></b>							
						</a>
						
						<ul class="dropdown-menu">
							<li>
								<a href="/profile2.php"><i class="icon-user"></i> Account Setting  </a>
							</li>
							
							<li>
								<a href="/change_password.php"><i class="icon-lock"></i> Change Password</a>
							</li>
							
							<li class="divider"></li>
							
							<li>
								<a href="/logout.php"><i class="icon-off"></i> Logout</a>
							</li>
						</ul>
					</li>
				</ul>
				
			</div> 
			
		</div> 
		
	</div> 
	
</div> 
-->




<div id="content">
	<div class="content-container">
		<div class="row">
			<div class="span2">
				<div class="account-container">
					<div class="account-avatar">
						<?php if ($avatar != '' && file_exists("members/$po_id/$avatar")): ?>
						<img src="members/<?php echo $po_id.'/'.$avatar ?>" alt="<?php echo $username ?>" class="thumbnail" />
						<?php else: ?>
						<img src="imatges/default_avatar.png" alt="<?php echo $username ?>" class="thumbnail" />
						<?php endif; ?>
					</div> <!-- /account-avatar -->
				
					<div class="account-details">
						<span class="account-name"><?php echo $username ?></span>
						<span class="account-role">Administrator</span>
						<span class="account-actions">
							<a href="javascript:;">Profile</a> |
							<a href="javascript:;">Edit Settings</a>
						</span>
					</div> <!-- /account-details -->
				</div> <!-- /account-container -->
				<hr />
				<ul id="main-nav" class="nav nav-tabs nav-stacked">
					<li>
						<a href="./index.php">
							<i class="icon-home"></i>
							Home<!-- Dashboard -->			
						</a>
					</li>
					
					<li>
						<a href="./faq.html" class="edit-avatar" id="li_avatar">
							<i class="icon-pushpin"></i>
							Edit your avatar	
						</a>
					</li>
					
					<li class="active">
						<a href="#" class="edit-details" id="li_account">
							<i class="icon-th-list"></i>
							Your Account		
						</a>
					</li>
					
					<li>
						<a href="./charts.html" class="edit-details" id="li_projects">
							<i class="icon-signal"></i>
							Your projects
							<span class="label label-warning pull-right">5</span>
						</a>
					</li>
					
					<li>
						<a href="./profile2.php" class="edit-details" id="li_comments">
							<i class="icon-user"></i>
							Your comments							
						</a>
					</li>
					
					<li>
						<a href="./login.php" class="edit-details" id="li_inbox">
							<i class="icon-lock"></i>
							Your inbox	
						</a>
					</li>
				</ul>	
				<hr />
				<div class="sidebar-extra">
					<p>(Short description about you)</p>
				</div> <!-- .sidebar-extra -->
				<br />
			</div> <!-- /span3 -->
			
			
			
			<div class="span8">
				<h1 class="page-title">
					<i class="icon-th-large"></i>
					User Account					
				</h1>
				<div class="row menu_info info_usuario info_invisible" id="info_user">
					<div class="span8" id="basic_information">
						<div class="widget">
							<div class="widget-header">
								<h3>Basic Information</h3>
							</div> <!-- /widget-header -->
							<div class="widget-content">
								<div class="tabbable">
									<ul class="nav nav-tabs">
									  <li class="active">
										<a href="#tab1" data-toggle="tab">Profile</a>
									  </li>
									  <li><a href="#tab2" data-toggle="tab">Settings</a></li>
									</ul>
									
									<br>
									<div class="tab-content">
										<div class="tab-pane active" id="tab1">
											<form id="edit-profile" class="form-horizontal">
											<fieldset>
												
												<div class="control-group">											
													<label class="control-label" for="username">Username</label>
													<div class="controls">
														<input type="text" class="input-medium disabled" id="username" value="<?php
																																if($user_exists > 0) echo($username);
																																else echo("John Smith");												
																															?>" disabled>
														<p class="help-block">Your username is for logging in and cannot be changed.</p>
													</div> <!-- /controls -->				
												</div> <!-- /control-group -->
												
												
												<div class="control-group">											
													<label class="control-label" for="firstname">Full Name</label>
													<div class="controls">
														<input type="text" class="input-medium" id="fullname" value="<?php	
																															if($user_exists > 0) echo($fullName);
																															else echo("John Smith");
																													?>">
													</div> <!-- /controls -->				
												</div> <!-- /control-group -->
												
												
												<div class="control-group">
													<label class="control-label" for="gender">Gender</label>
													<div class="controls">
														<label class="radio">
															<input type="radio" name="gender" value="option1" checked="checked" id="accounttype">
															Male
														</label>
														<label class="radio">
															<input type="radio" name="gender" value="option2">
															Female
														</label>
													</div>
												</div>
												
												<div class="control-group">											
													<label class="control-label" for="email">Email Address</label>
													<div class="controls">
														<input type="text" class="input-large" id="email" value="test@websalacarte.com">
													</div> <!-- /controls -->				
												</div> <!-- /control-group -->
												
												
												<br /><br />
												
												<div class="control-group">											
													<label class="control-label" for="password1">Password</label>
													<div class="controls">
														<input type="password" class="input-medium" id="password1" value="password">
													</div> <!-- /controls -->				
												</div> <!-- /control-group -->
												
												
												<div class="control-group">											
													<label class="control-label" for="password2">Confirm</label>
													<div class="controls">
														<input type="password" class="input-medium" id="password2" value="password">
													</div> <!-- /controls -->				
												</div> <!-- /control-group -->
												
												
													
													<br />
												
													
												<div class="form-actions">
													<button type="submit" class="btn btn-primary">Save</button> 
													<button class="btn">Cancel</button>
												</div> <!-- /form-actions -->
											</fieldset>
										</form>
										</div>
										
										<div class="tab-pane" id="tab2">
											<form id="edit-profile2" class="form-horizontal">
												<fieldset>
													
													
													<div class="control-group">
														<label class="control-label" for="accounttype">Account Type</label>
														<div class="controls">
															<label class="radio">
																<input type="radio" name="accounttype" value="option1" checked="checked" id="accounttype">
																Administrator
															</label>
															<label class="radio">
																<input type="radio" name="accounttype" value="option2">
																Participant
															</label>
														</div>
													</div>
													
												
													<div class="control-group">											
														<label class="control-label" for="country">Country</label>
														<div class="controls">
															<input type="text" class="input-medium" id="country" value="Spain">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
													
													<div class="control-group">											
														<label class="control-label" for="state">State</label>
														<div class="controls">
															<input type="text" class="input-medium" id="state" value="Catalunya">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
													
													<div class="control-group">											
														<label class="control-label" for="city">City</label>
														<div class="controls">
															<input type="text" class="input-medium" id="city" value="Barcelona">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
												
																					
													<div class="control-group">
														<label class="control-label" for="preferredLanguage">Preferred Language</label>
														<div class="controls">
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option1" checked="checked" id="preferredLanguage">
																Català
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option2">
																Español
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option3">
																English
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option4">
																Русский
															</label>
														</div>
													</div>			
													
													
													<div class="control-group">
														<label class="control-label" for="accountadvanced">Advanced Settings</label>
														<div class="controls">
															<label class="checkbox">
																<input type="checkbox" name="accountadvanced" value="option1" checked="checked" id="accountadvanced">
																Send me an email when someone replies to my comments.
															</label>
															<label class="checkbox">
																<input type="checkbox" name="accounttype" value="option2">
																Send me an email when someone likes my comments.
															</label>
														</div>
													</div>

													
													<br />
													
													<div class="form-actions">
														<button type="submit" class="btn btn-primary">Save</button> <button class="btn">Cancel</button>
													</div>
												</fieldset>
											</form>
										</div>
										
									</div>
								</div>
							</div> <!-- /widget-content -->
							
						</div> <!-- /widget -->
						
					</div> <!-- /span9 -->
					
				</div> <!-- /row -->
				
				<div class="row menu_info info_avatar info_invisible" id="info_avatar">
					<div class="span8" id="basic_information">
						<div class="widget">
							<div class="widget-header">
								<h3>User's Avatar</h3>
							</div> <!-- /widget-header -->
							
							<div class="widget-content">
								<p>User's avatar and other "public info".</p>
								
								
											<form id="edit-profile2" class="form-horizontal">
												<fieldset>
													
													
													<div class="control-group">
														<label class="control-label" for="accounttype">Account Type</label>
														<div class="controls">
															<label class="radio">
																<input type="radio" name="accounttype" value="option1" checked="checked" id="accounttype">
																Administrator
															</label>
															<label class="radio">
																<input type="radio" name="accounttype" value="option2">
																Participant
															</label>
														</div>
													</div>
													
												
													<div class="control-group">											
														<label class="control-label" for="country">Country</label>
														<div class="controls">
															<input type="text" class="input-medium" id="country" value="Spain">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
													
													<div class="control-group">											
														<label class="control-label" for="state">State</label>
														<div class="controls">
															<input type="text" class="input-medium" id="state" value="Catalunya">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
													
													<div class="control-group">											
														<label class="control-label" for="city">City</label>
														<div class="controls">
															<input type="text" class="input-medium" id="city" value="Barcelona">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
												
																					
													<div class="control-group">
														<label class="control-label" for="preferredLanguage">Preferred Language</label>
														<div class="controls">
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option1" checked="checked" id="preferredLanguage">
																Català
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option2">
																Español
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option3">
																English
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option4">
																Русский
															</label>
														</div>
													</div>			
													
													
													<div class="control-group">
														<label class="control-label" for="accountadvanced">Advanced Settings</label>
														<div class="controls">
															<label class="checkbox">
																<input type="checkbox" name="accountadvanced" value="option1" checked="checked" id="accountadvanced">
																Send me an email when someone replies to my comments.
															</label>
															<label class="checkbox">
																<input type="checkbox" name="accounttype" value="option2">
																Send me an email when someone likes my comments.
															</label>
														</div>
													</div>

													
													<br />
													
													<div class="form-actions">
														<button type="submit" class="btn btn-primary">Save</button> <button class="btn">Cancel</button>
													</div>
												</fieldset>
											</form>
								
							</div> <!-- /widget-content -->
						</div> <!-- /widget -->
					</div> <!-- /span9 -->
				</div> <!-- /row -->
				
				<div class="row menu_info info_preferences info_invisible" id="info_preferences">
					<div class="span8" id="basic_information">
						<div class="widget">
							<div class="widget-header">
								<h3>User's Preferences</h3>
							</div> <!-- /widget-header -->
							
							<div class="widget-content">
								<p>Choose your preferred utilities.</p>
								
								
											<form id="edit-profile2" class="form-horizontal">
												<fieldset>
													
													
													<div class="control-group">
														<label class="control-label" for="accounttype">Account Type</label>
														<div class="controls">
															<label class="radio">
																<input type="radio" name="accounttype" value="option1" checked="checked" id="accounttype">
																Administrator
															</label>
															<label class="radio">
																<input type="radio" name="accounttype" value="option2">
																Participant
															</label>
														</div>
													</div>
													
												
													<div class="control-group">											
														<label class="control-label" for="country">Country</label>
														<div class="controls">
															<input type="text" class="input-medium" id="country" value="Spain">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
													
													<div class="control-group">											
														<label class="control-label" for="state">State</label>
														<div class="controls">
															<input type="text" class="input-medium" id="state" value="Catalunya">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
													
													<div class="control-group">											
														<label class="control-label" for="city">City</label>
														<div class="controls">
															<input type="text" class="input-medium" id="city" value="Barcelona">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
												
																					
													<div class="control-group">
														<label class="control-label" for="preferredLanguage">Preferred Language</label>
														<div class="controls">
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option1" checked="checked" id="preferredLanguage">
																Català
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option2">
																Español
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option3">
																English
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option4">
																Русский
															</label>
														</div>
													</div>			
													
													
													<div class="control-group">
														<label class="control-label" for="accountadvanced">Advanced Settings</label>
														<div class="controls">
															<label class="checkbox">
																<input type="checkbox" name="accountadvanced" value="option1" checked="checked" id="accountadvanced">
																Send me an email when someone replies to my comments.
															</label>
															<label class="checkbox">
																<input type="checkbox" name="accounttype" value="option2">
																Send me an email when someone likes my comments.
															</label>
														</div>
													</div>

													
													<br />
													
													<div class="form-actions">
														<button type="submit" class="btn btn-primary">Save</button> <button class="btn">Cancel</button>
													</div>
												</fieldset>
											</form>
								
							</div> <!-- /widget-content -->
						</div> <!-- /widget -->
					</div> <!-- /span9 -->
				</div> <!-- /row -->
				<div class="row menu_info info_projects info_invisible" id="info_projects">
					<div class="span8" id="basic_information">
						<div class="widget">
							<div class="widget-header">
								<h3>Your projects</h3>
							</div> <!-- /widget-header -->
							
							<div class="widget-content">
								<p>Projects you have started or participated in.</p>
								
								
											<form id="edit-profile2" class="form-horizontal">
												<fieldset>
													
													
													<div class="control-group">
														<label class="control-label" for="accounttype">Account Type</label>
														<div class="controls">
															<label class="radio">
																<input type="radio" name="accounttype" value="option1" checked="checked" id="accounttype">
																Administrator
															</label>
															<label class="radio">
																<input type="radio" name="accounttype" value="option2">
																Participant
															</label>
														</div>
													</div>
													
												
													<div class="control-group">											
														<label class="control-label" for="country">Country</label>
														<div class="controls">
															<input type="text" class="input-medium" id="country" value="Spain">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
													
													<div class="control-group">											
														<label class="control-label" for="state">State</label>
														<div class="controls">
															<input type="text" class="input-medium" id="state" value="Catalunya">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
													
													<div class="control-group">											
														<label class="control-label" for="city">City</label>
														<div class="controls">
															<input type="text" class="input-medium" id="city" value="Barcelona">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
												
																					
													<div class="control-group">
														<label class="control-label" for="preferredLanguage">Preferred Language</label>
														<div class="controls">
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option1" checked="checked" id="preferredLanguage">
																Català
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option2">
																Español
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option3">
																English
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option4">
																Русский
															</label>
														</div>
													</div>			
													
													
													<div class="control-group">
														<label class="control-label" for="accountadvanced">Advanced Settings</label>
														<div class="controls">
															<label class="checkbox">
																<input type="checkbox" name="accountadvanced" value="option1" checked="checked" id="accountadvanced">
																Send me an email when someone replies to my comments.
															</label>
															<label class="checkbox">
																<input type="checkbox" name="accounttype" value="option2">
																Send me an email when someone likes my comments.
															</label>
														</div>
													</div>

													
													<br />
													
													<div class="form-actions">
														<button type="submit" class="btn btn-primary">Save</button> <button class="btn">Cancel</button>
													</div>
												</fieldset>
											</form>
								
							</div> <!-- /widget-content -->
						</div> <!-- /widget -->
					</div> <!-- /span9 -->
				</div> <!-- /row -->
				<div class="row menu_info info_comments info_invisible" id="info_comments">
					<div class="span8" id="basic_information">
						<div class="widget">
							<div class="widget-header">
								<h3>Your latest comments posted.</h3>
							</div> <!-- /widget-header -->
							
							<div class="widget-content">
								<p>User's avatar and other "public info".</p>
								
								
											<form id="edit-profile2" class="form-horizontal">
												<fieldset>
													
													
													<div class="control-group">
														<label class="control-label" for="accounttype">Account Type</label>
														<div class="controls">
															<label class="radio">
																<input type="radio" name="accounttype" value="option1" checked="checked" id="accounttype">
																Administrator
															</label>
															<label class="radio">
																<input type="radio" name="accounttype" value="option2">
																Participant
															</label>
														</div>
													</div>
													
												
													<div class="control-group">											
														<label class="control-label" for="country">Country</label>
														<div class="controls">
															<input type="text" class="input-medium" id="country" value="Spain">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
													
													<div class="control-group">											
														<label class="control-label" for="state">State</label>
														<div class="controls">
															<input type="text" class="input-medium" id="state" value="Catalunya">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
													
													<div class="control-group">											
														<label class="control-label" for="city">City</label>
														<div class="controls">
															<input type="text" class="input-medium" id="city" value="Barcelona">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
												
																					
													<div class="control-group">
														<label class="control-label" for="preferredLanguage">Preferred Language</label>
														<div class="controls">
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option1" checked="checked" id="preferredLanguage">
																Català
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option2">
																Español
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option3">
																English
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option4">
																Русский
															</label>
														</div>
													</div>			
													
													
													<div class="control-group">
														<label class="control-label" for="accountadvanced">Advanced Settings</label>
														<div class="controls">
															<label class="checkbox">
																<input type="checkbox" name="accountadvanced" value="option1" checked="checked" id="accountadvanced">
																Send me an email when someone replies to my comments.
															</label>
															<label class="checkbox">
																<input type="checkbox" name="accounttype" value="option2">
																Send me an email when someone likes my comments.
															</label>
														</div>
													</div>

													
													<br />
													
													<div class="form-actions">
														<button type="submit" class="btn btn-primary">Save</button> <button class="btn">Cancel</button>
													</div>
												</fieldset>
											</form>
								
							</div> <!-- /widget-content -->
						</div> <!-- /widget -->
					</div> <!-- /span9 -->
				</div> <!-- /row -->
				<div class="row menu_info info_messages info_invisible" id="info_messages">
					<div class="span8" id="basic_information">
						<div class="widget">
							<div class="widget-header">
								<h3>Messages to you</h3>
							</div> <!-- /widget-header -->
							
							<div class="widget-content">
								<p>Messages to you from other users, if you have configured so.</p>
								
								
											<form id="edit-profile2" class="form-horizontal">
												<fieldset>
													
													
													<div class="control-group">
														<label class="control-label" for="accounttype">Account Type</label>
														<div class="controls">
															<label class="radio">
																<input type="radio" name="accounttype" value="option1" checked="checked" id="accounttype">
																Administrator
															</label>
															<label class="radio">
																<input type="radio" name="accounttype" value="option2">
																Participant
															</label>
														</div>
													</div>
													
												
													<div class="control-group">											
														<label class="control-label" for="country">Country</label>
														<div class="controls">
															<input type="text" class="input-medium" id="country" value="Spain">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
													
													<div class="control-group">											
														<label class="control-label" for="state">State</label>
														<div class="controls">
															<input type="text" class="input-medium" id="state" value="Catalunya">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
													
													<div class="control-group">											
														<label class="control-label" for="city">City</label>
														<div class="controls">
															<input type="text" class="input-medium" id="city" value="Barcelona">
														</div> <!-- /controls -->				
													</div> <!-- /control-group -->
												
																					
													<div class="control-group">
														<label class="control-label" for="preferredLanguage">Preferred Language</label>
														<div class="controls">
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option1" checked="checked" id="preferredLanguage">
																Català
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option2">
																Español
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option3">
																English
															</label>
															<label class="radio">
																<input type="radio" name="preferredLanguage" value="option4">
																Русский
															</label>
														</div>
													</div>			
													
													
													<div class="control-group">
														<label class="control-label" for="accountadvanced">Advanced Settings</label>
														<div class="controls">
															<label class="checkbox">
																<input type="checkbox" name="accountadvanced" value="option1" checked="checked" id="accountadvanced">
																Send me an email when someone replies to my comments.
															</label>
															<label class="checkbox">
																<input type="checkbox" name="accounttype" value="option2">
																Send me an email when someone likes my comments.
															</label>
														</div>
													</div>

													
													<br />
													
													<div class="form-actions">
														<button type="submit" class="btn btn-primary">Save</button> <button class="btn">Cancel</button>
													</div>
												</fieldset>
											</form>
								
							</div> <!-- /widget-content -->
						</div> <!-- /widget -->
					</div> <!-- /span9 -->
				</div> <!-- /row -->
				<div class="row menu_info info_home info_visible" id="info_home">

						<div class="col-md-9">

							<div class="row">

								<div class="col-md-4 col-sm-5">

									<div class="thumbnail">
										<img src="./img/avatars/avatar-large-1.jpg" alt="Profile Picture">
									</div> <!-- /.thumbnail -->

									<br>

									<div class="list-group">  

										<a href="javascript:;" class="list-group-item">
											<i class="fa fa-asterisk"></i> &nbsp;&nbsp;Activity Feed

											<i class="fa fa-chevron-right list-group-chevron"></i>
										</a> 

										<a href="javascript:;" class="list-group-item">
											<i class="fa fa-book"></i> &nbsp;&nbsp;Projects

											<i class="fa fa-chevron-right list-group-chevron"></i>
											<span class="badge">3</span>
										</a> 

										<a href="javascript:;" class="list-group-item">
											<i class="fa fa-envelope"></i> &nbsp;&nbsp;Messages

											<i class="fa fa-chevron-right list-group-chevron"></i>
										</a> 

										<a href="javascript:;" class="list-group-item">
											<i class="fa fa-group"></i> &nbsp;&nbsp;Friends

											<i class="fa fa-chevron-right list-group-chevron"></i>
											<span class="badge">7</span>
										</a> 

										<a href="javascript:;" class="list-group-item">
											<i class="fa fa-cog"></i> &nbsp;&nbsp;Settings

											<i class="fa fa-chevron-right list-group-chevron"></i>
										</a> 
									</div> <!-- /.list-group -->

								</div> <!-- /.col -->


								<div class="col-md-8 col-sm-7">

									<h2>Rod Howard</h2>

									<h4>Visual, UI, UX Designer</h4>

									<hr>

									<p>
										<a href="javascript:;" class="btn btn-primary">Follow Rod</a>
										&nbsp;&nbsp;
										<a href="javascript:;" class="btn btn-secondary">Send Message</a>
									</p>

									<hr>


									<ul class="icons-list">
										<li><i class="icon-li fa fa-envelope"></i> rod@jumpstartui.com</li>
										<li><i class="icon-li fa fa-globe"></i> jumstartthemes.com</li>
										<li><i class="icon-li fa fa-map-marker"></i> Las Vegas, NV</li>
									</ul>

									<br>

									<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec.</p>

									<hr>

									<br>

									<h3 class="heading">Social Feed</h3>


									<div class="feed-item feed-item-idea">

										<div class="feed-icon">
											<i class="fa fa-lightbulb-o"></i>
										</div> <!-- /.feed-icon -->

										<div class="feed-subject">
											<p><a href="javascript:;">Rod Howard</a> shared an idea: <a href="javascript:;">Create an Awesome Idea</a></p>
										</div> <!-- /.feed-subject -->

										<div class="feed-content">
											<ul class="icons-list">
												<li>
													<i class="icon-li fa fa-quote-left"></i>
													Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.
												</li>
											</ul>
										</div> <!-- /.feed-content -->

										<div class="feed-actions">
											<a href="javascript:;" class="pull-left"><i class="fa fa-thumbs-up"></i> 123</a> 
											<a href="javascript:;" class="pull-left"><i class="fa fa-comment-o"></i> 29</a>
											
											<a href="javascript:;" class="pull-right"><i class="fa fa-clock-o"></i> 2 days ago</a>
										</div> <!-- /.feed-actions -->

									</div> <!-- /.feed-item -->

									<div class="feed-item feed-item-image">

										<div class="feed-icon">
											<i class="fa fa-picture-o"></i>
										</div> <!-- /.feed-icon -->

										<div class="feed-subject">
											<p><a href="javascript:;">John Doe</a> posted the <strong>4 files</strong>: <a href="javascript:;">Annual Reports</a></p>
										</div> <!-- /.feed-subject -->

										<div class="feed-content">
											<div class="thumbnail" style="width: 375px">
												<div class="thumbnail-view">
													<a class="thumbnail-view-hover ui-lightbox" href="./img/mockup.png">
													</a>

													<img src="./img/mockup.png" style="width: 100%;" alt="Gallery Image">
												</div>

											</div> <!-- /.thumbnail -->
										</div> <!-- /.feed-content -->

										<div class="feed-actions">
											<a href="javascript:;" class="pull-left"><i class="fa fa-thumbs-up"></i> 123</a> 
											<a href="javascript:;" class="pull-left"><i class="fa fa-comment-o"></i> 29</a>
											
											<a href="javascript:;" class="pull-right"><i class="fa fa-clock-o"></i> 2 days ago</a>
										</div> <!-- /.feed-actions -->

									</div> <!-- /.feed-item -->

									<div class="feed-item feed-item-file">

										<div class="feed-icon">
											<i class="fa fa-cloud-upload"></i>
										</div> <!-- /.feed-icon -->

										<div class="feed-subject">
											<p><a href="javascript:;">John Doe</a> posted the <strong>4 files</strong>: <a href="javascript:;">Annual Reports</a></p>
										</div> <!-- /.feed-subject -->

										<div class="feed-content">
											<ul class="icons-list">
												<li>
													<i class="icon-li fa fa-file-text-o"></i>
													<a href="javascript:;">Annual Report 2007</a> - annual_report_2007.pdf
												</li>
												<li>
													<i class="icon-li fa fa-file-text-o"></i>
													<a href="javascript:;">Annual Report 2008</a> - annual_report_2007.pdf
												</li>
												<li>
													<i class="icon-li fa fa-file-text-o"></i>
													<a href="javascript:;">Annual Report 2009</a> - annual_report_2007.pdf
												</li>
												<li>
													<i class="icon-li fa fa-file-text-o"></i>
													<a href="javascript:;">Annual Report 2010</a> - annual_report_2007.pdf
												</li>
											</ul>
										</div> <!-- /.feed-content -->

										<div class="feed-actions">
											<a href="javascript:;" class="pull-left"><i class="fa fa-thumbs-up"></i> 123</a> 
											<a href="javascript:;" class="pull-left"><i class="fa fa-comment-o"></i> 29</a>
											
											<a href="javascript:;" class="pull-right"><i class="fa fa-clock-o"></i> 2 days ago</a>
										</div> <!-- /.feed-actions -->

									</div> <!-- /.feed-item -->



									<div class="feed-item feed-item-bookmark">

										<div class="feed-icon">
											<i class="fa fa-bookmark"></i>
										</div> <!-- /.feed-icon -->

										<div class="feed-subject">
											<p><a href="javascript:;">Rod Howard</a> bookmarked a page on Delicious: <a href="javascript:;">Jumpstart Themes</a></p>
										</div> <!-- /.feed-subject -->

										<div class="feed-content">
									
										</div> <!-- /.feed-content -->

										<div class="feed-actions">
											<a href="javascript:;" class="pull-left"><i class="fa fa-thumbs-up"></i> 123</a> 
											<a href="javascript:;" class="pull-left"><i class="fa fa-comment-o"></i> 29</a>
											
											<a href="javascript:;" class="pull-right"><i class="fa fa-clock-o"></i> 2 days ago</a>
										</div> <!-- /.feed-actions -->

									</div> <!-- /.feed-item -->

									<div class="feed-item feed-item-question">

										<div class="feed-icon">
											<i class="fa fa-question"></i>
										</div> <!-- /.feed-icon -->

										<div class="feed-subject">
											<p><a href="javascript:;">Rod Howard</a> posted the question: <a href="javascript:;">Who can I call to get a new parking pass for the Rowan Building?</a></p>
										</div> <!-- /.feed-subject -->

										<div class="feed-content">
											<ul class="icons-list">
												<li>
													<i class="icon-li fa fa-quote-left"></i>
													Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.
												</li>
											</ul>
										</div> <!-- /.feed-content -->

										<div class="feed-actions">
											<a href="javascript:;" class="pull-left"><i class="fa fa-thumbs-up"></i> 123</a> 
											<a href="javascript:;" class="pull-left"><i class="fa fa-comment-o"></i> 29</a>
											
											<a href="javascript:;" class="pull-right"><i class="fa fa-clock-o"></i> 2 days ago</a>
										</div> <!-- /.feed-actions -->

									</div> <!-- /.feed-item -->

								</div>

							</div>

						</div>


						<div class="col-md-3 col-sm-6 col-sidebar-right">
							<h4>Easy Statistics</h4>
							<div class="list-group">  

								<a href="javascript:;" class="list-group-item"><h3 class="pull-right"><i class="fa fa-eye"></i></h3>
								  <h4 class="list-group-item-heading">38,847</h4>
								  <p class="list-group-item-text">Profile Views</p>
								  
								</a>

								<a href="javascript:;" class="list-group-item"><h3 class="pull-right"><i class="fa fa-facebook-square"></i></h3>
								  <h4 class="list-group-item-heading">3,482</h4>
								  <p class="list-group-item-text">Facebook Likes</p>

								</a>

								<a href="javascript:;" class="list-group-item"><h3 class="pull-right"><i class="fa fa-twitter-square"></i></h3>
								  <h4 class="list-group-item-heading">5,845</h4>
								  <p class="list-group-item-text">Twitter Followers</p>

								</a>
							</div> <!-- /.list-group -->

							<br>
							<div class="well">
								<h4>Recent Activity</h4>
								<ul class="icons-list text-md">
									<li>
										<i class="icon-li fa fa-location-arrow"></i>

										<strong>Rod</strong> uploaded 6 files. 
										<br>
										<small>about 4 hours ago</small>
									</li>

									<li>
										<i class="icon-li fa fa-location-arrow"></i>

										<strong>Rod</strong> followed the research interest: <a href="javascript:;">Open Access Books in Linguistics</a>. 
										<br>
										<small>about 23 hours ago</small>
									</li>

									<li>
										<i class="icon-li fa fa-location-arrow"></i>

										<strong>Rod</strong> added 51 papers. 
										<br>
										<small>2 days ago</small>
									</li>
								</ul>
							</div>
						</div>

				</div>
				
				
				
				
			</div> <!-- /span9 -->
			
			
		</div> <!-- /row -->
		
	</div> <!-- /container -->
	
</div> <!-- /content -->
					
	

<div id="footer">
	
	<div class="container">				
		<div class="row">
			
				<div id="rights" class="span6">
				&copy; 2013-14 Websalacarte. All Rights Reserved.
				</div> <!-- /grid-6 -->
				
				<div id="attribution" class="span6">
					Contact:  <a href="#" target="_blank">josep@websalacarte.com</a>
				</div> <!-- /grid-6 -->
				
			</div> <!-- /.row -->
	</div> <!-- /content-container -->
	
</div> <!-- /footer -->


		</div>
	</div>
</div>
<?php include_once("foot_common.php"); ?>




<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery-1.11.1.min.js"></script>


<script src="js/bootstrap.min.js"></script>

	
<script type="text/javascript" src="js/serialize.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript">
window.onload = function(){
	var start_date = 2013;
		for(var i=start_date; i>1900; i--){
			document.getElementById('birthyear').innerHTML += '<option value='+i+'>'+i+'</option>';
	}
}
</script>
<script src="js/profile.js"></script>

  </body>
</html>
