<?php

require_once('../includes/defines.php'); 
include_once(SCRIPT_DIR."check_user.php"); 
require_once (MODELS_DIR .  'commenters.php');

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
  else {

    //$log_user_id = 34;
    
    if ( !empty ( $_GET['uid'] ) ) {
      // queremos (agente o admin) el perfil de un usuario concreto.
      $user_id = $_GET['uid'];
      //$user = $query->load_user_object($user_id);
      $user = Commenters::getMember($user_id);
      
      if ( $log_user_id == $user_id ) {
        $mine = true;
      }
    } else {
      // no pasamos parámetro, queremos "mi" perfil.
      //$user = $query->load_user_object($log_user_id);
      $user = Commenters::getMember($log_user_id);
      $mine = true;
    }

      $id_usuario = $user->id;
      $fb_userId = $user->ext_id;
      $username = $std->username;
      $email = $user->email;
      $password = $user->password;
      $avatar = $user->avatar;
      $banner = $user->banner;
      $full_name = $user->full_name;
      $country = $user->country;
      $state = $user->state;
      $city = $user->city;
      $gender = $user->gender;
      $birthday = $user->birthday;
      $user_role_name = $user->role;
  }

  //$friends = $query->get_friends($log_user_id);
?>
<!DOCTYPE html>
<!-- saved from url=(0074)/lvti-premium/modules/vl/social/page-profile.html -->
<html lang="en" class="no-js"><!--<![endif]--><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Profile</title>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Google Font: Open Sans -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">

  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="css/font-awesome.min.css">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">


    <!-- App CSS -->
  <link rel="stylesheet" href="css/social-admin.css">
  <!-- <link rel="stylesheet" href="./css/custom.css"> -->


  <!-- Favicon -->
  <link rel="shortcut icon" href="/favicon.ico">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class=" ">

<div id="wrapper">

  <header class="navbar" role="banner">

    <div class="container">

      <div class="navbar-header">
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <i class="fa fa-cog"></i>
        </button>

        <a href="/lvti-premium/modules/vl/social/" class="navbar-brand navbar-brand-img">
          <img src="img/logo.png" alt="">
        </a>
      </div> <!-- /.navbar-header -->

      <nav class="collapse navbar-collapse" role="navigation">

        <ul class="nav navbar-nav navbar-left">

          <li class="dropdown navbar-notification">

            <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell navbar-notification-icon"></i>
              <span class="visible-xs-inline">&nbsp;Notifications</span>
              <b class="badge badge-primary">3</b>
            </a>

            <div class="dropdown-menu">

              <div class="dropdown-header">&nbsp;Notifications</div>

              <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 225px;"><div class="notification-list" style="overflow: hidden; width: auto; height: 225px;">

                <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="notification">
                  <span class="notification-icon"><i class="fa fa-cloud-upload text-primary"></i></span>
                  <span class="notification-title">Notification Title</span>
                  <span class="notification-description">Praesent dictum nisl non est sagittis luctus.</span>
                  <span class="notification-time">20 minutes ago</span>
                </a> <!-- / .notification -->

                <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="notification">
                  <span class="notification-icon"><i class="fa fa-ban text-secondary"></i></span>
                  <span class="notification-title">Notification Title</span>
                  <span class="notification-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</span>
                  <span class="notification-time">20 minutes ago</span>
                </a> <!-- / .notification -->

                <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="notification">
                  <span class="notification-icon"><i class="fa fa-warning text-tertiary"></i></span>
                  <span class="notification-title">Storage Space Almost Full!</span>
                  <span class="notification-description">Praesent dictum nisl non est sagittis luctus.</span>
                  <span class="notification-time">20 minutes ago</span>
                </a> <!-- / .notification -->

                <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="notification">
                  <span class="notification-icon"><i class="fa fa-ban text-danger"></i></span>
                  <span class="notification-title">Sync Error</span>
                  <span class="notification-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</span>
                  <span class="notification-time">20 minutes ago</span>
                </a> <!-- / .notification -->

              </div><div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; background: rgb(0, 0, 0);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div></div> <!-- / .notification-list -->

              <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="notification-link">View All Notifications</a>

            </div> <!-- / .dropdown-menu -->

          </li>



          <li class="dropdown navbar-notification">

            <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope navbar-notification-icon"></i>
              <span class="visible-xs-inline">&nbsp;Messages</span>
            </a>

            <div class="dropdown-menu">

              <div class="dropdown-header">Messages</div>

              <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 225px;"><div class="notification-list" style="overflow: hidden; width: auto; height: 225px;">

                <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="notification">
                  <div class="notification-icon"><img src="./Profile_files/avatar-3-md.jpg" alt=""></div>
                  <div class="notification-title">New Message</div>
                  <div class="notification-description">Praesent dictum nisl non est sagittis luctus.</div>
                  <div class="notification-time">20 minutes ago</div>
                </a> <!-- / .notification -->

                <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="notification">
                  <div class="notification-icon"><img src="./Profile_files/avatar-3-md.jpg" alt=""></div>
                  <div class="notification-title">New Message</div>
                  <div class="notification-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</div>
                  <div class="notification-time">20 minutes ago</div>
                </a> <!-- / .notification -->

                <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="notification">
                  <div class="notification-icon"><img src="./Profile_files/avatar-4-md.jpg" alt=""></div>
                  <div class="notification-title">New Message</div>
                  <div class="notification-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</div>
                  <div class="notification-time">20 minutes ago</div>
                </a> <!-- / .notification -->

                <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="notification">
                  <div class="notification-icon"><img src="./Profile_files/avatar-5-md.jpg" alt=""></div>
                  <div class="notification-title">New Message</div>
                  <div class="notification-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</div>
                  <div class="notification-time">20 minutes ago</div>
                </a> <!-- / .notification -->

              </div><div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; background: rgb(0, 0, 0);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div></div> <!-- / .notification-list -->

              <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="notification-link">View All Messages</a>

            </div> <!-- / .dropdown-menu -->

          </li>


          <li class="dropdown navbar-notification empty">

            <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-warning navbar-notification-icon"></i>
              <span class="visible-xs-inline">&nbsp;&nbsp;Alerts</span>
            </a>

            <div class="dropdown-menu">

              <div class="dropdown-header">Alerts</div>

              <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 225px;"><div class="notification-list" style="overflow: hidden; width: auto; height: 225px;">
                
                <h4 class="notification-empty-title">No alerts here.</h4>
                <p class="notification-empty-text">Check out what other makers are doing on Explore!</p>     

              </div><div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; background: rgb(0, 0, 0);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div></div> <!-- / .notification-list -->

              <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="notification-link">View All Alerts</a>

            </div> <!-- / .dropdown-menu -->

          </li>          

        </ul>



        <ul class="nav navbar-nav navbar-right">    

          <li>
            <a href="javsacript:;">Projects</a>
          </li>    

          <li>
            <a href="javascript:;">Support</a>
          </li>    

          <li class="dropdown navbar-profile">
            <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
              <img src="./Profile_files/avatar-4-sm.jpg" class="navbar-profile-avatar" alt="">
              <span class="visible-xs-inline">@peterlandt &nbsp;</span>
              <i class="fa fa-caret-down"></i>
            </a>

            <ul class="dropdown-menu" role="menu">

              <li>
                <a href="./Profile_files/Profile.html">
                  <i class="fa fa-user"></i> 
                  &nbsp;&nbsp;My Profile
                </a>
              </li>

              <li>
                <a href="/lvti-premium/modules/vl/social/page-pricing-plans.html">
                  <i class="fa fa-dollar"></i> 
                  &nbsp;&nbsp;Plans &amp; Billing
                </a>
              </li>

              <li>
                <a href="/lvti-premium/modules/vl/social/page-settings.html">
                  <i class="fa fa-cogs"></i> 
                  &nbsp;&nbsp;Settings
                </a>
              </li>

              <li class="divider"></li>

              <li>
                <a href="/lvti-premium/modules/vl/social/account-login.html">
                  <i class="fa fa-sign-out"></i> 
                  &nbsp;&nbsp;Logout
                </a>
              </li>

            </ul>

          </li>

        </ul>

      </nav>

    </div> <!-- /.container -->

  </header>


  <div class="content">

    <div class="container">

      <div class="row">

        <div class="col-md-3 col-sm-5">

          <div class="profile-avatar">
            <img src="<?php echo "../../../modules/vl/members/" . $user->id . '/' . $user->avatar; ?>" class="profile-avatar-img img-responsive thumbnail" width="260" height="260" alt="Profile Image">
          </div> <!-- /.profile-avatar -->

          <div class="list-group">  

            <a href="javascript:;" class="list-group-item">
              <i class="fa fa-asterisk text-primary"></i> &nbsp;&nbsp;Activity Feed

              <i class="fa fa-chevron-right list-group-chevron"></i>
            </a> 

            <a href="javascript:;" class="list-group-item">
              <i class="fa fa-book text-primary"></i> &nbsp;&nbsp;Projects

              <i class="fa fa-chevron-right list-group-chevron"></i>
              <span class="badge">3</span>
            </a> 

            <a href="javascript:;" class="list-group-item">
              <i class="fa fa-envelope text-primary"></i> &nbsp;&nbsp;Messages

              <i class="fa fa-chevron-right list-group-chevron"></i>
            </a> 

            <a href="javascript:;" class="list-group-item">
              <i class="fa fa-group text-primary"></i> &nbsp;&nbsp;Friends

              <i class="fa fa-chevron-right list-group-chevron"></i>
            </a> 

            <a href="javascript:;" class="list-group-item">
              <i class="fa fa-cog text-primary"></i> &nbsp;&nbsp;Settings

              <i class="fa fa-chevron-right list-group-chevron"></i>
            </a> 
          </div> <!-- /.list-group -->



        </div> <!-- /.col -->



        <div class="col-md-6 col-sm-7">

          <br class="visible-xs">

          <h3><?php echo $user->full_name; ?></h3>

          <h5 class="text-muted"><?php echo $user->username; ?></h5>

          <hr>

          <p>
            <a href="javascript:;" class="btn btn-primary">Follow <?php echo $user->username; ?></a>
            &nbsp;&nbsp;
            <a href="javascript:;" class="btn btn-secondary">Send Message</a>
          </p>

          <hr>
          
          <ul class="icons-list">
            <li><i class="icon-li fa fa-envelope"></i> <?php echo $user->email; ?></li>
            <li><i class="icon-li fa fa-key"></i> <?php echo $user->role; ?></li>
            <li><i class="icon-li fa fa-globe"></i> <?php echo $user->full_name; ?></li>
            <li><i class="icon-li fa fa-map-marker"></i> <?php echo $user->city; ?>, <?php echo $user->state; ?>. <?php echo $user->country; ?></li>
          </ul>    

          <br>

          <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec.</p>

          <hr>


          <br><br>

          <div class="heading-block">
            <h4>
              Activity Feed
            </h4>
          </div> <!-- /.heading-block -->

            <div class="share-widget clearfix">

              <textarea class="form-control share-widget-textarea" rows="3" placeholder="Share what you&#39;ve been up to..." tabindex="1"></textarea>

              <div class="share-widget-actions">
                <div class="share-widget-types pull-left">
                  <a href="javascript:;" class="fa fa-picture-o ui-tooltip" title="" data-original-title="Post an Image"><i></i></a>
                  <a href="javascript:;" class="fa fa-video-camera ui-tooltip" title="" data-original-title="Upload a Video"><i></i></a>
                  <a href="javascript:;" class="fa fa-lightbulb-o ui-tooltip" title="" data-original-title="Post an Idea"><i></i></a>
                  <a href="javascript:;" class="fa fa-question-circle ui-tooltip" title="" data-original-title="Ask a Question"><i></i></a>
                </div>	

              <div class="pull-right">
                <a class="btn btn-primary btn-sm" tabindex="2">Post</a>
              </div>

              </div> <!-- /.share-widget-actions -->

            </div> <!-- /.share-widget -->

            <br><br>

            <div class="feed-item feed-item-idea">

              <div class="feed-icon bg-primary">
                <i class="fa fa-lightbulb-o"></i>
              </div> <!-- /.feed-icon -->

              <div class="feed-subject">
                <p><a href="javascript:;"><?php echo $user->username; ?></a> shared an idea: <a href="javascript:;">Create an Awesome Idea</a></p>
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

              <div class="feed-icon bg-secondary">
                <i class="fa fa-picture-o"></i>
              </div> <!-- /.feed-icon -->

              <div class="feed-subject">
                <p><a href="javascript:;"><?php echo $user->username; ?></a> posted the <strong>4 files</strong>: <a href="javascript:;">Annual Reports</a></p>
              </div> <!-- /.feed-subject -->

              <div class="feed-content">
                <div class="thumbnail" style="width: 375px">
                  <img src="./Profile_files/mockup.png" style="width: 100%;" alt="Gallery Image">
                </div> <!-- /.thumbnail -->
              </div> <!-- /.feed-content -->

              <div class="feed-actions">
                <a href="javascript:;" class="pull-left"><i class="fa fa-thumbs-up"></i> 123</a> 
                <a href="javascript:;" class="pull-left"><i class="fa fa-comment-o"></i> 29</a>

                <a href="javascript:;" class="pull-right"><i class="fa fa-clock-o"></i> 2 days ago</a>
              </div> <!-- /.feed-actions -->

            </div> <!-- /.feed-item -->



            <div class="feed-item feed-item-file">

              <div class="feed-icon bg-success">
                <i class="fa fa-cloud-upload"></i>
              </div> <!-- /.feed-icon -->

              <div class="feed-subject">
                <p><a href="javascript:;"><?php echo $user->username; ?></a> posted the <strong>4 files</strong>: <a href="javascript:;">Annual Reports</a></p>
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

              <div class="feed-icon bg-tertiary">
                <i class="fa fa-bookmark"></i>
              </div> <!-- /.feed-icon -->

              <div class="feed-subject">
                <p><a href="javascript:;"><?php echo $user->username; ?></a> bookmarked a page on Delicious: <a href="javascript:;">Jumpstart Themes</a></p>
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

              <div class="feed-icon bg-secondary">
                <i class="fa fa-question"></i>
              </div> <!-- /.feed-icon -->

              <div class="feed-subject">
                <p><a href="javascript:;"><?php echo $user->username; ?></a> posted the question: <a href="javascript:;">Who can I call to get a new parking pass for the Rowan Building?</a></p>
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

            <br class="visible-xs">            
            <br class="visible-xs">
            
          </div> <!-- /.col -->


          <div class="col-md-3">

            <div class="heading-block">
              <h5>
                Social Stats
              </h5>
            </div> <!-- /.heading-block -->

            <div class="list-group">  

              <a href="javascript:;" class="list-group-item">
                  <h3 class="pull-right"><i class="fa fa-eye text-primary"></i></h3>
                  <h4 class="list-group-item-heading">38,847</h4>
                  <p class="list-group-item-text">Profile Views</p>                  
                </a>

              <a href="javascript:;" class="list-group-item">
                <h3 class="pull-right"><i class="fa fa-facebook-square  text-primary"></i></h3>
                <h4 class="list-group-item-heading">3,482</h4>
                <p class="list-group-item-text">Facebook Likes</p>
              </a>

              <a href="javascript:;" class="list-group-item">
                <h3 class="pull-right"><i class="fa fa-twitter-square  text-primary"></i></h3>
                <h4 class="list-group-item-heading">5,845</h4>
                <p class="list-group-item-text">Twitter Followers</p>
              </a>
            </div> <!-- /.list-group -->

            <br>

            <div class="heading-block">
              <h5>
                Project Activity
              </h5>
            </div> <!-- /.heading-block -->

            <div class="well">


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

            </div> <!-- /.well -->

          </div> <!-- /.col -->

        </div> <!-- /.row -->

        <br><br>

    </div> <!-- /.container -->

  </div> <!-- .content -->

</div> <!-- /#wrapper -->

<footer class="footer">
  <div class="container">
    <p class="pull-left">Websalacarte.</p>
  </div>
</footer>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Core JS -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<script src="js/jquery.slimscroll.min.js"></script>


<!-- App JS -->
<script src="js/social-core.js"></script>
<script src="js/social-helpers.js"></script>
<script src="js/social-admin.js"></script>



<a id="back-to-top" href="#top" style="display: none;"><i class="fa fa-chevron-up"></i></a></body></html>