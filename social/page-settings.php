<?php
session_start();
require_once('../includes/defines.php'); 
include_once (INC . 'configuration.php');

include_once(SCRIPT_DIR."check_user.php"); 
require_once (MODELS_DIR .  'commenters.php');

// log_user_role
// log_user_id
// user_is_logged
// log_uname
// log_pass

  require_once('includes/class-query.php');
  require_once('includes/class-insert.php');
  
$mine=false;
$current_url = $_SERVER['PHP_SELF'];

    if ( !empty ( $_GET['uid'] ) ) {
      // queremos (agente o admin) cambiar el password de un usuario concreto.
      $current_url .= "?".$_SERVER['QUERY_STRING'];
    }
$break_out = false;

$base_social_url = $path_social;        // <-- defined in configuration file. (included)
//$base_vl_url = "C:\\xampp\\xampp\\lvti-premium\\"; //$path_vl;                // <-- defined in configuration file. (included)

$debug_post = ( !empty ( $_POST ) ) ? "not empty" : "empty";
$debug_pwd = "";
$result_password = "";

if ( !empty ( $_POST ) ) {
  // hay envio formulario
  if ( $_POST['settings_change'] == "change_profile" ) {
    // formulario es 'profile_settings'

      // Logica de update profile
        // validación campos tab profile

      $id_usuario = $_POST['id_usuario'];
      $fb_userId = $_POST['fb_userId'];
      $username = $_POST['username'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $avatar = $_POST['avatar'];
      $banner = $_POST['banner'];
      $full_name = $_POST['full_name'];
      $country = $_POST['country'];
      $state = $_POST['state'];
      $city = $_POST['city'];
      $gender = $_POST['gender'];
      $birthday = $_POST['birthday'];
      $user_role_name = $_POST['user_role_name'];

    // ################# upload file
    //if ( !empty ( $_POST['newavatarfile'] ) ) { //&& !empty($_FILES['newavatarfile'] ) ) {

    if ( !empty($_FILES['fileToUpload'] ) ) {

          $target_dir = "../members/33/"; //"/lvti-premium/modules/vl/members/33/";   //$target_dir = "/lvti-premium/members/".$id_usuario."/";
          $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
          $avatar_filename = basename($_FILES["fileToUpload"]["name"]); // nombre del fichero en el directorio 'modules/vl/members/{id_usuario}'. --> en DB, sólo nombre fichero.
          $uploadOk = 1;
          $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
          // Check if image file is a actual image or fake image
          if(isset($_POST["submit"])) {
              $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
              if($check !== false) {
                  echo "File is an image - " . $check["mime"] . ".";
                  $uploadOk = 1;
              } else {
                  echo "File is not an image.";
                  $uploadOk = 0;
              }
          }
          // Check if file already exists
          if (file_exists($target_file)) {
              echo "Sorry, file already exists.";
              $uploadOk = 0;
          }
          // Check file size
          if ($_FILES["fileToUpload"]["size"] > 500000) {
              echo "Sorry, your file is too large.";
              $uploadOk = 0;
          }
          // Allow certain file formats
          if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
          && $imageFileType != "gif" ) {
              echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
              $uploadOk = 0;
          }
          // Check if $uploadOk is set to 0 by an error
          if ($uploadOk == 0) {
              echo "Sorry, your file was not uploaded.";
          // if everything is ok, try to upload file
          } else {
              if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                  //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                  $avatar = $avatar_filename;
              } else {
                  echo "Sorry, there was an error uploading your file (target_file: ".$target_file.", basename: ".basename( $_FILES["fileToUpload"]["name"]).").";
              }
          }
    }
    // ################# fin upload file


    // llamada a clase update DB members.
    $user = Commenters::updateMember( $id_usuario, $fb_userId, $username, $email, $password, $avatar, $banner, $full_name, $country, $state, $city, $gender, $birthday, $user_role_name );
    //$debug = "id_usuario=$id_usuario, fb_userId=$fb_userId, username=$username, email=$email, password=$password, avatar=$avatar, banner=$banner, full_name=$full_name, 
        //country=$country, state=$state, city=$city, gender=$gender, birthday=$birthday, user_role_name=$user_role_name";
      

  }




    // Logica de update password
        // validación campos password
        // update DB
      // Logica de update notifications preferences
        // no hay validación. Directamente update DB.
      // Friends
      // Emails

  elseif ( $_POST['settings_change'] == "change_password" ) {
    //  $remove_friend = $insert->remove_friend($_POST['user_id'], $_POST['friend_id']);
    $debug_pwd = "En change_password. <br>";

    // get user id
    if ( !empty ( $_GET['uid'] ) ) {
      // queremos (agente o admin) cambiar el password de un usuario concreto.
      //$current_url .= "?".$_SERVER['QUERY_STRING'];
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

    


    if( isset($_POST['old-password']) && trim($_POST['old-password']) != "" && isset($_POST['new-password-1']) && trim($_POST['new-password-1']) != "" && isset($_POST['new-password-2']) && trim($_POST['new-password-2']) != "" && trim($_POST['new-password-1']) == trim($_POST['new-password-2']) ){
      $debug_pwd .= "if match <br>";
      // verificamos corrección old-password
      $password_old = $_POST['old-password'];
      $hmac_old = hash_hmac('sha512', $password_old, file_get_contents('../secret/key.txt'));
      $password_new = $_POST['new-password-1'];
      //$hmac_new = hash_hmac('sha512', $password_new, file_get_contents('../secret/key.txt'));


      //// create the hmac /////
      $hmac_new = hash_hmac('sha512', $password_new, file_get_contents('../secret/key.txt'));
      //// create random bytes for salt ////
      $bytes = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
      //// Create salt and replace + with . ////
      $salt = strtr(base64_encode($bytes), '+', '.');
      //// make sure our bcrypt hash is 22 characters which is the required length ////
      $salt = substr($salt, 0, 22);
      //// This is the hashed password to store in the db ////
      $bcrypt = crypt($hmac_new, '$2y$12$' . $salt);
      $token = md5($bcrypt);  // aunque ahora no lo utilizamos.



      // necesito asegurar que la conexión con la DB está establecida, sigue en vigor.
      //include(SCRIPT_DIR."connect.php"); 

      $uid = $user->id;
      $username = $user->username;
      $hash = $user->password;
      if ($mine) {
        $log_user_role = $user->role; //isset($user->role_name) ? $user->role_name : $user->role;                // getMember devuelve role.  
      }
      else {
        // no modificar $log_user_role, porque corresponde al usuario logado, no al que estamos visionando y modificando.
      }

      if (crypt($hmac_old, $hash) === $hash) {    
        // coincide! --> podemos cambiar el password a nuevo.
        $user_updated = Commenters::updateMemberPassword( $user_id, $bcrypt );
      }
      else {
        // password antiguo no coincide. 
            $result_password = "Old password incorrect (user name is: '".$username."').";      
            $break_out = true;
            exit();  
      }
      // if "mine" (user), update session & cookie.
      if (!$break_out) {
        if ($mine) {

              $_SESSION['uid'] = $uid;
              $_SESSION['username'] = $username;
              $_SESSION['password'] = $hash;
              $_SESSION['role_name'] = $log_user_role;
              setcookie("id", $uid, strtotime( '+30 days' ), "/", "", "", TRUE);
              setcookie("username", $username, strtotime( '+30 days' ), "/", "", "", TRUE);
              setcookie("password", $hash, strtotime( '+30 days' ), "/", "", "", TRUE); 
              /* echo 'Valid password<br />'.$_SESSION['uid'].'<br />'.$_SESSION['username'].'<br />'.$_SESSION['password'].'
              <br />'.$_COOKIE['id']; */
              setcookie("role_name", $log_user_role, strtotime( '+30 days' ), "/", "", "", TRUE);
              // fin update actual
              $result_password = "Password changed successfully.";
        }
        else {
          // nothing
              $result_password = "Password changed successfully.";
        }
      }

    }
    else {

      if (!$break_out) {
        // volver. O no son iguales, o alguno no se ha informado...
        $result_password = "Password not changed. Check all fields are filled in properly";
        $debug_pwd .= "if DON'T match <br>";
        //header('Location: '.$current_url);
      }
    }
  }
    
  elseif ( $_POST['settings_change'] == "change_notifications" ) {
    //  
  }
    
  elseif ( $_POST['settings_change'] == "change_friends" ) {
    //  $remove_friend = $insert->remove_friend($_POST['user_id'], $_POST['friend_id']);

      //$add_friend = $insert->add_friend($_POST['user_id'], $_POST['friend_id']);
  }

    // PREPARAMOS PAGINA:    
    if ( !empty ( $_GET['uid'] ) ) {
      // queremos (agente o admin) el perfil de un usuario concreto.
      //$current_url .= "?".$_SERVER['QUERY_STRING'];               // lo comento porque ya lo hemos recogido anteriormente.
      $user_id = $_GET['uid'];
      //$user = $query->load_user_object($user_id);
      $user = Commenters::getMember($user_id);
      
      if ( $log_user_id == $user_id ) {
        $mine = true;
      }
    } 
    else {
      // no pasamos parámetro, queremos "mi" perfil.
      //$user = $query->load_user_object($log_user_id);
      $user = Commenters::getMember($log_user_id);
      $mine = true;
    }

    $id_usuario = $user->id;
    $fb_userId = $user->ext_id;
    $username = $user->username;
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
else {

    //$log_user_id = 34;
    
    if ( !empty ( $_GET['uid'] ) ) {
      // queremos (agente o admin) el perfil de un usuario concreto.
      //$current_url .= "?".$_SERVER['QUERY_STRING'];               // lo comento porque ya lo hemos recogido anteriormente.
      $user_id = $_GET['uid'];
      //$user = $query->load_user_object($user_id);
      $user = Commenters::getMember($user_id);
      
      if ( $log_user_id == $user_id ) {
        $mine = true;
      }
    } 
    else {
      // no pasamos parámetro, queremos "mi" perfil.
      //$user = $query->load_user_object($log_user_id);
      $user = Commenters::getMember($log_user_id);
      $mine = true;
    }

    $id_usuario = $user->id;
    $fb_userId = $user->ext_id;
    $username = $user->username;
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
<html lang="en" class="no-js">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>User Settings</title>

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

  <!-- Plugin CSS -->
  <link rel="stylesheet" href="css/jasny-bootstrap.css">
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
                  <div class="notification-icon"><img src="./page-settings_files/avatar-3-md.jpg" alt=""></div>
                  <div class="notification-title">New Message</div>
                  <div class="notification-description">Praesent dictum nisl non est sagittis luctus.</div>
                  <div class="notification-time">20 minutes ago</div>
                </a> <!-- / .notification -->

                <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="notification">
                  <div class="notification-icon"><img src="./page-settings_files/avatar-3-md.jpg" alt=""></div>
                  <div class="notification-title">New Message</div>
                  <div class="notification-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</div>
                  <div class="notification-time">20 minutes ago</div>
                </a> <!-- / .notification -->

                <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="notification">
                  <div class="notification-icon"><img src="./page-settings_files/avatar-4-md.jpg" alt=""></div>
                  <div class="notification-title">New Message</div>
                  <div class="notification-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</div>
                  <div class="notification-time">20 minutes ago</div>
                </a> <!-- / .notification -->

                <a href="/lvti-premium/modules/vl/social/page-notifications.html" class="notification">
                  <div class="notification-icon"><img src="./page-settings_files/avatar-5-md.jpg" alt=""></div>
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
              <img src="./page-settings_files/avatar-4-sm.jpg" class="navbar-profile-avatar" alt="">
              <span class="visible-xs-inline">@peterlandt &nbsp;</span>
              <i class="fa fa-caret-down"></i>
            </a>

            <ul class="dropdown-menu" role="menu">

              <li>
                <a href="/lvti-premium/modules/vl/social/page-profile.html">
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
                <a href="./page-settings_files/page-settings.html">
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

      <div class="layout layout-main-right layout-stack-sm">

        <div class="col-md-3 col-sm-4 layout-sidebar">

          <div class="nav-layout-sidebar-skip">
            <strong>Tab Navigation</strong> / <a href="/lvti-premium/modules/vl/social/page-settings.php#settings-content">Skip to Content</a>	
          </div>

          <ul id="myTab" class="nav nav-layout-sidebar nav-stacked">
              <li class="active">
              <a href="/lvti-premium/modules/vl/social/page-settings.php#profile-tab" data-toggle="tab">
              <i class="fa fa-user"></i> 
              &nbsp;&nbsp;Profile Settings
              </a>
            </li>

            <li>
              <a href="/lvti-premium/modules/vl/social/page-settings.php#avatar-tab" data-toggle="tab">
              <i class="fa fa-camera"></i> 
              &nbsp;&nbsp;Change Avatar
              </a>
            </li>

            <li>
              <a href="/lvti-premium/modules/vl/social/page-settings.php#password-tab" data-toggle="tab">
              <i class="fa fa-lock"></i> 
              &nbsp;&nbsp;Change Password
              </a>
            </li>

            <li>
              <a href="/lvti-premium/modules/vl/social/page-settings.php#messaging" data-toggle="tab">
              <i class="fa fa-bullhorn"></i> 
              &nbsp;&nbsp;Notifications
              </a>
            </li>

            <li>
              <a href="/lvti-premium/modules/vl/social/page-settings.php#friends" data-toggle="tab">
              <i class="fa fa-users"></i> 
              &nbsp;&nbsp;Friends
              </a>
            </li>

            <li>
              <a href="/lvti-premium/modules/vl/social/page-settings.php#reports" data-toggle="tab">
              <i class="fa fa-envelope"></i> 
              &nbsp;&nbsp;Emails
              </a>
            </li>
          </ul>

        </div> <!-- /.col -->



        <div class="col-md-9 col-sm-8 layout-main">

          <div id="settings-content" class="tab-content stacked-content">

            <div class="tab-pane fade in active" id="profile-tab">

            <div class="heading-block">
              <h3>
                Edit Profile
              </h3>
            </div> <!-- /.heading-block -->



<p class="debug">
  <?php 
    if (!empty($result_profile) ) {
      echo( '<div class="alert alert-info"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>'.$result_profile.'</div>' );
    }
    if (!empty($result_password) ) {
      echo( '<div class="alert alert-info"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>'.$result_password.'</div>' );
    }
   ?>
  <?php //echo("\n <br ><br >username = ".$username.", user-username: ".$user->username); ?>
  <?php //echo("\n <br ><br >debug = ".$debug); ?>
  <?php //echo("\n <br ><br >id_usuario = ".$id_usuario); ?>
  <?php //echo("\n <br ><br >user_id = ".$user_id); ?>
  <?php //echo("\n <br ><br >mine = ".$mine); ?>

</p>




              <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes. Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</p>

              <br><br>

              <form action="<?php echo $current_url; ?>" class="form-horizontal" method="post" enctype="multipart/form-data">

                <div class="form-group">

                  <label class="col-md-3 control-label">Avatar</label>

                  <div class="col-md-7">

                    <div class="fileinput fileinput-new" data-provides="fileinput">
                      <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="min-width: 125px; min-height: 125px;">
                        <img src="<?php echo "../../../modules/vl/members/" . $id_usuario . '/' . $avatar; ?>" width="125" height="125" alt="Avatar">
                      </div>
                      <div>
                        <span class="btn btn-default btn-file">
                          <span class="fileinput-new">Select image</span>
                          <span class="fileinput-exists">Change</span>
                          <!--<input type="file" name="newavatarfile">-->
                          <input type="file" name="fileToUpload">
                        </span>
                        <a href="/lvti-premium/modules/vl/social/page-settings.php" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                      </div>
                    </div> <!-- /.fileupload -->

                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->
                <input type="hidden"  name="id_usuario" id="id_usuario" value="<?php echo $id_usuario; ?>" />
                <input type="hidden" name="avatar" id="avatar" value="<?php echo $avatar; ?>" />
                <input type="hidden" name="username" id="username" value="<?php echo $username; ?>" />
                <input type="hidden" name="password" id="password" value="<?php echo $password; ?>" />
                <input type="hidden" name="fb_userId" id="fb_userId" value="<?php echo $fb_userId; ?>" />
                <!--<input type="hidden"  name="type" value="profile_settings" />-->

                <div class="form-group">

                  <label class="col-md-3 control-label">Username</label>

                  <div class="col-md-7">
                    <input type="text" name="username_disabled" value="<?php echo $username; ?>" class="form-control" disabled="">
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->

                <div class="form-group">

                  <label class="col-md-3 control-label">Social Id</label>

                  <div class="col-md-7">
                    <input type="text" name="fb_userId_disabled" value="<?php echo $fb_userId; ?>" class="form-control" disabled="">
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->

                <div class="form-group">

                  <label class="col-md-3 control-label">Full Name</label>

                  <div class="col-md-7">
                    <input type="text" name="full_name" value="<?php echo $full_name; ?>" class="form-control">
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->



                <div class="form-group">

                  <label class="col-md-3 control-label">Email Address</label>

                  <div class="col-md-7">
                    <input type="text" name="email" value="<?php echo $email; ?>" class="form-control">
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->



                <div class="form-group">

                  <label class="col-md-3 control-label">City</label>

                  <div class="col-md-7">
                    <input type="text" name="city" value="<?php echo $city; ?>" class="form-control">
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->

                <div class="form-group">

                  <label class="col-md-3 control-label">State</label>

                  <div class="col-md-7">
                    <input type="text" name="state" value="<?php echo $state; ?>" class="form-control">
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->



                <div class="form-group">

                  <label class="col-md-3 control-label">Country</label>

                  <div class="col-md-7">
                    <input type="text" name="country" value="<?php echo $country; ?>" class="form-control">
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->



                <div class="form-group">

                  <label class="col-md-3 control-label">User Permissions (admin only)</label>

                  <div class="col-md-7">
                    <select name="user_role_name" class="form-control">
                      <option value="admin" <?php echo($user_role_name == "admin" ? "selected" : ""); ?>>admin</option>
                      <option value="agent" <?php echo($user_role_name == "agent" ? "selected" : ""); ?>>agent</option>
                      <option value="user"  <?php echo($user_role_name == "user" ? "selected" : ""); ?>>user</option>
                    </select>
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->



                <div class="form-group">

                  <label class="col-md-3 control-label">About You</label>

                  <div class="col-md-7">
                    <textarea id="about-textarea" name="about-you" rows="6" class="form-control">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.</textarea>
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->



                <div class="form-group">
                  <div class="col-md-7 col-md-push-3">
                    <button type="submit" name="settings_change" value="change_profile" class="btn btn-primary">Save Changes</button>
                    &nbsp;
                    <button type="reset" class="btn btn-default">Cancel</button>
                  </div> <!-- /.col -->
                </div> <!-- /.form-group -->

              </form>


            </div> <!-- /.tab-pane -->



            <div class="tab-pane fade" id="avatar-tab">

              <div class="heading-block">
                <h3>
                  Change Avatar
                </h3>
              </div> <!-- /.heading-block -->

              <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.</p>

              <br><br>


              <!--<form action="/lvti-premium/modules/vl/social/upload.php" class="form-horizontal" method="post" enctype="multipart/form-data">-->
              <form action="upload.php" class="form-horizontal" method="post" enctype="multipart/form-data">

                <div class="form-group">

                  <label class="col-md-3 control-label">Avatar</label>

                  <div class="col-md-7">


                    <div class="fileinput fileinput-new" data-provides="fileinput">
                      <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="min-width: 125px; min-height: 125px;">
                        <img src="<?php echo "../../../modules/vl/members/" . $id_usuario . '/' . $avatar; ?>" width="125" height="125" alt="Avatar">
                      </div>
                      <div>
                        <span class="btn btn-default btn-file">
                          <span class="fileinput-new">Select image</span>
                          <span class="fileinput-exists">Change</span>
                          <!--<input type="file" name="newavatarfile">-->
                          <input type="file" name="fileToUpload">
                        </span>
                        <a href="/lvti-premium/modules/vl/social/page-settings.php" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                      </div>
                    </div> <!-- /.fileupload -->
<!--                    
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
-->
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->





                <div class="form-group">

                  <div class="col-md-7 col-md-push-3">
                    <button type="submit" name="settings_change" value="change_avatar" class="btn btn-primary">Save Changes</button>
                    &nbsp;
                    <button type="reset" class="btn btn-default">Cancel</button>
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->

              </form>

            </div> <!-- /.tab-pane -->



            <div class="tab-pane fade" id="password-tab">

              <div class="heading-block">
                <h3>
                  Change Password
                </h3>
              </div> <!-- /.heading-block -->

              <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.</p>

              <br><br>

              <form action="<?php echo $current_url; ?>" class="form-horizontal" method="post">

                <div class="form-group">

                  <label class="col-md-3 control-label">Old Password</label>

                  <div class="col-md-7">
                    <input type="password" name="old-password" class="form-control">
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->


                <hr>


                <div class="form-group">

                  <label class="col-md-3 control-label">New Password</label>

                  <div class="col-md-7">
                    <input type="password" name="new-password-1" class="form-control">
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->


                <div class="form-group">

                  <label class="col-md-3 control-label">New Password Confirm</label>

                  <div class="col-md-7">
                    <input type="password" name="new-password-2" class="form-control">
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->

                <input type="hidden"  name="id_usuario" id="id_usuario" value="<?php echo $id_usuario; ?>" />
                <!--<input type="hidden"  name="type" value="change_password" />-->

                <div class="form-group">

                  <div class="col-md-7 col-md-push-3">
                    <button type="submit" name="settings_change" value="change_password" class="btn btn-primary">Save Changes</button>
                    &nbsp;
                    <button type="reset" class="btn btn-default">Cancel</button>
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->

              </form>

            </div> <!-- /.tab-pane -->



            <div class="tab-pane fade" id="messaging">

              <div class="heading-block">
                <h3>
                  Notification Settings
                </h3>
              </div> <!-- /.heading-block -->

              <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.</p>

              <br><br>

              <form action="<?php echo $current_url; ?>" class="form form-horizontal">

                <div class="form-group">

                  <label class="col-md-3 control-label">Toggle Notifications</label>

                  <div class="col-md-7">

                    <div class="checkbox">
                      <label>
                      <input value="" type="checkbox" checked="">
                      Send me a notification when someone replies to my public comments on a page.
                      </label>
                    </div> <!-- /.checkbox -->

                    <div class="checkbox">
                      <label>
                      <input value="" type="checkbox" checked="">
                      Send me a notification when an agent replies to my private comments on a page.
                      </label>
                    </div> <!-- /.checkbox -->

                    <div class="checkbox">
                      <label>
                      <input value="" type="checkbox">
                      Send me a notification on changes in my favorite pages (people comments, agents adding contents, ...).
                      </label>
                    </div> <!-- /.checkbox -->

                    <div class="checkbox">
                      <label>
                      <input value="" type="checkbox">
                      Send me a notification when someone likes my comments on a page.
                      </label>
                    </div> <!-- /.checkbox -->

                    <div class="checkbox">
                      <label>
                      <input value="" type="checkbox">
                      Send me a notification on my friends activities.
                      </label>
                    </div> <!-- /.checkbox -->

                    <div class="checkbox">
                      <label>
                      <input value="" type="checkbox" checked="">
                      Send me all notifications by email too, to the address selected below.
                      </label>
                    </div> <!-- /.checkbox -->

                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->


                <div class="form-group">

                  <label class="col-md-3 control-label">Email for Notifications</label>

                  <div class="col-md-7">
                    <select name="email_notifications" class="form-control">
                      <option value="1"><?php echo $user->email; ?></option>
                      <option value="2"><?php echo $user->email; ?></option>
                      <option value="3"><?php echo $user->email; ?></option>
                    </select>
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->

                <!--<input type="hidden"  name="type" value="notifications_settings" />-->


                <div class="form-group">

                  <div class="col-md-7 col-md-push-3">
                    <button type="submit" name="settings_change" value="change_notifications" class="btn btn-primary">Save Changes</button>
                    &nbsp;
                    <button type="reset" class="btn btn-default">Cancel</button>
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->

              </form>

            </div> <!-- /.tab-pane -->


            <div class="tab-pane fade" id="friends">

              <div class="heading-block">
                <h3>
                  Payments Settings
                </h3>
              </div> <!-- /.heading-block -->

              <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.</p>

              <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>

            </div> <!-- /.tab-pane -->

                <!--<input type="hidden"  name="type" value="friends_settings" />-->


            <div class="tab-pane fade" id="reports">

              <div class="heading-block">
                <h3>
                  Reports Settings
                </h3>
              </div> <!-- /.heading-block -->

              <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.</p>

              <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>
            </div> <!-- /.tab-pane -->

          </div> <!-- /.tab-content -->

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


<!-- Plugin JS -->
<script src="js/social-fileinput.js"></script>
<!-- App JS -->
<script src="js/social-core.js"></script>
<script src="js/social-helpers.js"></script>
<script src="js/social-admin.js"></script>
<!--
<script>
$( document ).ready(function() {
  $('.fileinput').fileinput();
});
</script>
-->

<a id="back-to-top" href="#top" style="display: none;"><i class="fa fa-chevron-up"></i></a></body></html>