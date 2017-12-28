<?php
session_start();
require_once('../includes/defines.php'); 
include_once (INC . 'configuration.php');

include_once(SCRIPT_DIR."check_user.php"); 
require_once (MODELS_DIR .  'commenters.php');
require_once (MODELS_DIR .  'social.php');

// log_user_role
// log_user_id
// user_is_logged
// log_uname
// log_pass

$mine=false;

    // get user id
    if ( !empty ( $_GET['uid'] ) ) {
      $user_id = $_GET['uid'];
      if ( $log_user_id == $user_id ) {
        $mine = true;   // y si no, sigue siendo false (inicializado como false al principio de la página)
      }
      $user = Commenters::getMember($user_id);
    } 
    else {
      // no pasamos parámetro, queremos "mi" perfil.
      $user = Commenters::getMember($log_user_id);
      $user_id = $log_user_id;
      $mine = true;
    }





?>
<!DOCTYPE html>
<html lang="en" class="no-js"><!--<![endif]--><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Notifications</title>

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
          <img src="img/logo.png" alt="MVP Ready">
        </a>
      </div> <!-- /.navbar-header -->

      <nav class="collapse navbar-collapse" role="navigation">

        <ul class="nav navbar-nav navbar-left">

          <li class="dropdown navbar-notification">

            <a href="./page-notifications_files/page-notifications.html" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell navbar-notification-icon"></i>
              <span class="visible-xs-inline">&nbsp;Notifications</span>
              <b class="badge badge-primary">3</b>
            </a>

            <div class="dropdown-menu">

              <div class="dropdown-header">&nbsp;Notifications</div>

              <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 225px;"><div class="notification-list" style="overflow: hidden; width: auto; height: 225px;">

                <a href="./page-notifications_files/page-notifications.html" class="notification">
                  <span class="notification-icon"><i class="fa fa-cloud-upload text-primary"></i></span>
                  <span class="notification-title">Notification Title</span>
                  <span class="notification-description">Praesent dictum nisl non est sagittis luctus.</span>
                  <span class="notification-time">20 minutes ago</span>
                </a> <!-- / .notification -->

                <a href="./page-notifications_files/page-notifications.html" class="notification">
                  <span class="notification-icon"><i class="fa fa-ban text-secondary"></i></span>
                  <span class="notification-title">Notification Title</span>
                  <span class="notification-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</span>
                  <span class="notification-time">20 minutes ago</span>
                </a> <!-- / .notification -->

                <a href="./page-notifications_files/page-notifications.html" class="notification">
                  <span class="notification-icon"><i class="fa fa-warning text-tertiary"></i></span>
                  <span class="notification-title">Storage Space Almost Full!</span>
                  <span class="notification-description">Praesent dictum nisl non est sagittis luctus.</span>
                  <span class="notification-time">20 minutes ago</span>
                </a> <!-- / .notification -->

                <a href="./page-notifications_files/page-notifications.html" class="notification">
                  <span class="notification-icon"><i class="fa fa-ban text-danger"></i></span>
                  <span class="notification-title">Sync Error</span>
                  <span class="notification-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</span>
                  <span class="notification-time">20 minutes ago</span>
                </a> <!-- / .notification -->

              </div><div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; background: rgb(0, 0, 0);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div></div> <!-- / .notification-list -->

              <a href="./page-notifications_files/page-notifications.html" class="notification-link">View All Notifications</a>

            </div> <!-- / .dropdown-menu -->

          </li>



          <li class="dropdown navbar-notification">

            <a href="./page-notifications_files/page-notifications.html" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope navbar-notification-icon"></i>
              <span class="visible-xs-inline">&nbsp;Messages</span>
            </a>

            <div class="dropdown-menu">

              <div class="dropdown-header">Messages</div>

              <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 225px;"><div class="notification-list" style="overflow: hidden; width: auto; height: 225px;">

                <a href="./page-notifications_files/page-notifications.html" class="notification">
                  <div class="notification-icon"><img src="./page-notifications_files/avatar-3-md.jpg" alt=""></div>
                  <div class="notification-title">New Message</div>
                  <div class="notification-description">Praesent dictum nisl non est sagittis luctus.</div>
                  <div class="notification-time">20 minutes ago</div>
                </a> <!-- / .notification -->

                <a href="./page-notifications_files/page-notifications.html" class="notification">
                  <div class="notification-icon"><img src="./page-notifications_files/avatar-3-md.jpg" alt=""></div>
                  <div class="notification-title">New Message</div>
                  <div class="notification-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</div>
                  <div class="notification-time">20 minutes ago</div>
                </a> <!-- / .notification -->

                <a href="./page-notifications_files/page-notifications.html" class="notification">
                  <div class="notification-icon"><img src="./page-notifications_files/avatar-4-md.jpg" alt=""></div>
                  <div class="notification-title">New Message</div>
                  <div class="notification-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</div>
                  <div class="notification-time">20 minutes ago</div>
                </a> <!-- / .notification -->

                <a href="./page-notifications_files/page-notifications.html" class="notification">
                  <div class="notification-icon"><img src="./page-notifications_files/avatar-5-md.jpg" alt=""></div>
                  <div class="notification-title">New Message</div>
                  <div class="notification-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</div>
                  <div class="notification-time">20 minutes ago</div>
                </a> <!-- / .notification -->

              </div><div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; background: rgb(0, 0, 0);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div></div> <!-- / .notification-list -->

              <a href="./page-notifications_files/page-notifications.html" class="notification-link">View All Messages</a>

            </div> <!-- / .dropdown-menu -->

          </li>


          <li class="dropdown navbar-notification empty">

            <a href="./page-notifications_files/page-notifications.html" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-warning navbar-notification-icon"></i>
              <span class="visible-xs-inline">&nbsp;&nbsp;Alerts</span>
            </a>

            <div class="dropdown-menu">

              <div class="dropdown-header">Alerts</div>

              <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 225px;"><div class="notification-list" style="overflow: hidden; width: auto; height: 225px;">
                
                <h4 class="notification-empty-title">No alerts here.</h4>
                <p class="notification-empty-text">Check out what other makers are doing on Explore!</p>     

              </div><div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; background: rgb(0, 0, 0);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div></div> <!-- / .notification-list -->

              <a href="./page-notifications_files/page-notifications.html" class="notification-link">View All Alerts</a>

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
              <img src="./page-notifications_files/avatar-4-sm.jpg" class="navbar-profile-avatar" alt="">
              <span class="visible-xs-inline">@peterlandt &nbsp;</span>
              <i class="fa fa-caret-down"></i>
            </a>

            <ul class="dropdown-menu" role="menu">

              <li>
                <a href="https://jumpstartthemes.com/demo/v/2.1.0/templates/admin/page-profile.html">
                  <i class="fa fa-user"></i> 
                  &nbsp;&nbsp;My Profile
                </a>
              </li>

              <li>
                <a href="https://jumpstartthemes.com/demo/v/2.1.0/templates/admin/page-pricing-plans.html">
                  <i class="fa fa-dollar"></i> 
                  &nbsp;&nbsp;Plans &amp; Billing
                </a>
              </li>

              <li>
                <a href="https://jumpstartthemes.com/demo/v/2.1.0/templates/admin/page-settings.html">
                  <i class="fa fa-cogs"></i> 
                  &nbsp;&nbsp;Settings
                </a>
              </li>

              <li class="divider"></li>

              <li>
                <a href="https://jumpstartthemes.com/demo/v/2.1.0/templates/admin/account-login.html">
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

        <div class="col-md-8">

          <h2 class="">Your Notifications</h2>

          <br><br>


          <div class="heading-block">
            <h4>
              Database
            </h4>
          </div> <!-- /.heading-block -->

          <ul class="icons-list notifications-list">
            <?php
              $notifs = Social::getNotifications($user_id);
              foreach ($notifs as $notif) {
                 echo('<li>
                      <i class="icon-li fa fa-reply text-warning"></i><span class="notification_title">'.$notif->subject.'</span>
                      <div class="notification_content">'.$notif->content.'</div>
                      </li>');
              }
            ?>

          </ul>








          <div class="heading-block">
            <h4>
              Sent Today
            </h4>
          </div> <!-- /.heading-block -->

          <ul class="icons-list notifications-list">
            <li>
              <i class="icon-li fa fa-envelope text-warning"></i>
              <span class="notification-icon"><img src="img/avatar-3-md.jpg" width="50" height="50" alt=""></span>
              Agent <a href="javascript:;">Nikita Williams</a> sent you a <a href="javascript:;">message</a>.
            </li>

            <li>
              <i class="icon-li fa fa-reply text-warning"></i>
              Agent <a href="javascript:;">Nikita Williams</a> replied to this <a href="javascript:;">comment</a> from you
            </li>

            <li>
              <i class="icon-li fa fa-thumbs-up text-secondary"></i>
              User <a href="javascript:;">John Doe</a> liked your <a href="javascript:;">comment</a> on the page <a href="javascript:;">VoC Tours Can Hawaii</a>
            </li>

            <li>
              <i class="icon-li fa fa-star text-secondary"></i>
              <a href="javascript:;">You</a> favorited the page <a href="javascript:;">VoC Tours Can Hawaii</a>
            </li>
          </ul>

          <br><br>

          <div class="heading-block">
            <h4>
              Sent Yesterday
            </h4>
          </div> <!-- /.heading-block -->

          <ul class="icons-list notifications-list">

            <li>
              <i class="icon-li fa fa-comments-o text-success"></i>
              The user <a href="javascript:;">usuario</a> replied this <a href="javascript:;">comment</a> on your 
              <span><button type="button" class="btn btn-default demo-element ui-popover" data-toggle="tooltip" data-placement="top" data-trigger="hover" 
                data-content="Your comment content" 
                title="" data-original-title="Your comment title">
              comment
              </button></span>on the page <a href="javascript:;">VoC Tours Can Hawaii</a>
            </li>

            <li>
              <i class="icon-li fa fa-comment text-success"></i>
              <a href="javascript:;">You</a> posted  <a href="javascript:;">this comment</a> on the page <a href="javascript:;">VoC Tours Can Hawaii</a>
            </li>

            <li>
              <i class="icon-li fa fa-eye text-secondary"></i>
              <a href="javascript:;">You</a> watched the page <a href="javascript:;">VoC Tours Can Hawaii</a>
            </li>
          </ul>

        </div> <!-- /.col -->

      </div> <!-- /.row -->

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