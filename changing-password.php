<?php
// Recojo pagina FROM para devolver al lugar desde el que se ha logado.	--> http://localhost/lvti-premium/modules/vl/login.php?from=%2Flvti-premium%2Fdemo5premium2.php

//session_start();
require_once('includes/defines.php'); 
include_once (INC . 'configuration.php');

include_once(SCRIPT_DIR."check_user.php"); 
require_once (MODELS_DIR .  'commenters.php');  // necesito Commenters para updateMemberPassword

$pwd_reset_authorized = false;
$new_pwd_posted = false;
$error_passwords = "";

if(isset($_GET['from'])) {
    $pagina_from = htmlspecialchars($_GET['from']);
}
else {
	if(isset($_POST['from_back'])) {
		//$pagina_from = htmlspecialchars($_POST['from_back']);
		$pagina_from = $_POST['from_back'];
	}
	// para páginas donde no hemos añadido el GET_from.
	else {
		$pagina_from = "index.php";	// (en este caso)
	}
}

// quiero saber si estoy cambiando mi propio usuario (via reset_password option, por ejemplo, o tambien pagina Profile) o el de otro usuario (via pagina profile) .

// get user id
if ( !empty ( $_GET['user'] ) ) {
  // queremos (agente o admin) cambiar el password de un usuario concreto.
  //$current_url .= "?".$_SERVER['QUERY_STRING'];
  $user_id = $_GET['user'];      
  if ( $log_user_id == $user_id ) {
    $mine = true;   // y si no, sigue siendo false (inicializado como false al principio de la página)
  }
  $changing_user = Commenters::getMember($user_id);
} 
else {
  // no pasamos parámetro, queremos "mi" perfil.
  $user = Commenters::getMember($log_user_id);
  $user_id = $log_user_id;
  $mine = true;
}


// Si llega desde el link del email enviado:
if(isset($_GET['user']) && $_GET['user'] != "" && isset($_GET['token']) && $_GET['token'] != ""){
	include_once("scripts/connect.php");
	$user = preg_replace('#[^0-9]#', '', $_GET['user']);
	$token = preg_replace('#[^a-z0-9]#i', '', $_GET['token']);
  //$token = "'" . preg_replace('#[^a-z0-9]#i', '', $_GET['token']) . "'";
	$stmt = $db->prepare("SELECT user, token FROM activate WHERE user=:user AND token=:token");
	$stmt->bindValue(':user',$user,PDO::PARAM_INT);
	$stmt->bindValue(':token',$token,PDO::PARAM_STR);
	try{
		$stmt->execute();
		$count = $stmt->rowCount();

		if($count > 0){
			try{
				$db->beginTransaction();
				$updateSQL = $db->prepare("UPDATE members SET activated='1' WHERE id=:user");
				$updateSQL->bindValue(':user',$user,PDO::PARAM_INT);
				$updateSQL->execute();
				//$deleteSQL = $db->prepare("DELETE FROM activate WHERE user=:user AND token=:token");
        $deleteSQL = $db->prepare("DELETE FROM activate WHERE user=:user");
				$deleteSQL->bindValue(':user',$user,PDO::PARAM_INT);
				//$deleteSQL->bindValue(':token',$token,PDO::PARAM_STR);
				$deleteSQL->execute();
				$db->commit();
				
				//echo 'Your account has been activated! Click the link to log in: <a href="login.php?from='.$pagina_from.'">Log In</a>';
				$pwd_reset_authorized = true;
				$new_pwd_posted = false;

				$db = null;
				//exit();
			
			}
			catch(PDOException $e){
				$db->rollBack();
        $result_notok_msg = $e->getMessage();
			}
		}
		else{
      $result_notok_msg = "Sorry, there has been an error with your username. Please try again later.";
			$db = null;
			//exit();
		}
	}
	catch(PDOException $e){
    $result_notok_msg = $e->getMessage();
		$db = null;
		//exit();
	}
}
// si postea el nuevo password
elseif (isset($_POST['new_pass1']) && $_POST['new_pass1'] != "" && isset($_POST['new_pass2']) && $_POST['new_pass2'] != "") {
  $user = preg_replace('#[^0-9]#', '', $_GET['user']);
	$pwd_reset_authorized = true;
	$new_pwd_posted = true;
	// check passwords match
	if ( $_POST['new_pass1'] != $_POST['new_pass2'] ) {
    // passwords don't match. Please try again.
        
        $pwd_reset_authorized = true;
        $new_pwd_posted = false;
        $error_passwords = "Passwords don't match. Please try again.";
  }
  else {
	// update members with new password

      $password_new = $_POST['new_pass1'];
      //$hmac_new = hash_hmac('sha512', $password_new, file_get_contents('../secret/key.txt'));


      //// create the hmac /////
      $hmac_new = hash_hmac('sha512', $password_new, file_get_contents('secret/key.txt'));
      //// create random bytes for salt ////
      $bytes = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
      //// Create salt and replace + with . ////
      $salt = strtr(base64_encode($bytes), '+', '.');
      //// make sure our bcrypt hash is 22 characters which is the required length ////
      $salt = substr($salt, 0, 22);
      //// This is the hashed password to store in the db ////
      $bcrypt = crypt($hmac_new, '$2y$12$' . $salt);
      $token = md5($bcrypt);  // aunque ahora no lo utilizamos.
      
      $user_updated = Commenters::updateMemberPassword( $user, $bcrypt ); // mantenemos $user en GET.
      //
      // report success and provide link to $login (with $page???)

      //echo "user: ".$user;
      //echo "<br>";
      //echo "<br>";

      $changed_user = Commenters::getMember($user);
      $changeduser_username = $changed_user->username;  
      $changeduser_id = $changed_user->id;  
      $changeduser_role = $changed_user->role;  
      $changeduser_email = $changed_user->email;  
      $changeduser_activated = $changed_user->activated;  
      $changeduser_full_name = $changed_user->full_name;  
      $changeduser_lastlog = $changed_user->lastlog;  
      $changeduser_signup_date = $changed_user->signup_date;  
      //var_dump($changed_user);
      //die;

      // if "remember me" was checked, and user is mine, login & setcookie.

      if (isset($_POST['rememberme']) && ($_POST['rememberme'] == "checked") ) {
        if ($mine) {

              $_SESSION['uid'] = $user;
              $_SESSION['username'] = $changeduser_username;
              $_SESSION['password'] = $user_updated->password;   // ¿$bcrypt?;  //$hash;
              $_SESSION['role_name'] = $log_user_role;
              setcookie("id", $uid, strtotime( '+30 days' ), "/", "", "", TRUE);
              setcookie("username", $username, strtotime( '+30 days' ), "/", "", "", TRUE);
              setcookie("password", $hash, strtotime( '+30 days' ), "/", "", "", TRUE); 
              /* echo 'Valid password<br />'.$_SESSION['uid'].'<br />'.$_SESSION['username'].'<br />'.$_SESSION['password'].'
              <br />'.$_COOKIE['id']; */
              setcookie("role_name", $log_user_role, strtotime( '+30 days' ), "/", "", "", TRUE);
              // fin update actual
              $result_password = "Your new password has been saved successfully!";
              $result_password_ok = true;
        }
        else {
          // nothing
              $result_password = "New password for ".$changeduser_role." ".$changeduser_username." has been saved successfully!";
              $result_password_ok = true;
        }
      }
      else {
        // nothing
            $result_password = "New password for ".$changeduser_role." ".$changeduser_username." has been saved successfully!";
              $result_password_ok = true;
      }
  }
}
// something went wrong?
else {
	$result_notok_msg = "Oops, something went wrong with your request.";
	$pwd_reset_authorized = false;
	$new_pwd_posted = false;
  $result_password_ok = false;
}
?>

<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Changing Password - VideoLayers</title>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Google Font: Open Sans -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">

  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="social/css/font-awesome.min.css">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="social/css/bootstrap.min.css">

  <!-- Plugin CSS -->
  <link rel="stylesheet" href="social/css/jasny-bootstrap.css">
  <link rel="stylesheet" href="social/css/magnific-popup.css">
    <!-- App CSS -->
  <link rel="stylesheet" href="social/css/social-admin.css">
  <!-- <link rel="stylesheet" href="./css/custom.css"> -->


  <!-- Favicon -->
  <link rel="shortcut icon" href="/favicon.ico">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class="account-bg">

  <header class="navbar" role="banner">

    <div class="container">

      <div class="navbar-header">
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <i class="fa fa-cog"></i>
        </button>

        <a href="<?php echo $ruta_raiz; ?>" class="navbar-brand navbar-brand-img">
          <img src="imatges/logo-peque.png" width="110" height="55">
        </a>
      </div> <!-- /.navbar-header -->

      <nav class="collapse navbar-collapse" role="navigation">   
        <ul class="nav navbar-nav navbar-right">     
          <li>
            <a href="<?php echo $pagina_from; ?>"><i class="fa fa-angle-double-left"></i> &nbsp;Back to Home</a>
          </li>   
        </ul>
      </nav>

    </div> <!-- /.container -->

  </header>

  <br class="xs-80">

  <div class="account-wrapper">



  	<?php 
  			// PASO 2.1 de reset password. 
  			//	Viene del email con token de reset password. Ha hecho click en link hacia aquí.
  			//		Le presento form con 2 campos para que escoja new_password deseado. -> Lo posteo a esta misma página (hacia paso 2.2)

  			if ( ($pwd_reset_authorized) && (!$new_pwd_posted) ) {

  			
  	?>

    <div class="account-body">

      <h3>Welcome back to VideoLayers.</h3>

      <?php 
        if ($error_passwords!="") {
          echo "<h5>".$error_passwords."</h5>";  
        }
      ?>
      <h5>Please choose your new password.</h5>

      <br>

      <span class="account-or-social text-muted">OR, SIGN IN BELOW</span>

      <form class="form account-form" method="POST" action="changing-password.php?user=<?php echo ($user.'&from='.$pagina_from); ?>">

        <div class="form-group">
          <label for="password1" class="placeholder-hidden">New password</label>
          <input type="password" class="form-control" id="password1" name="new_pass1" placeholder="New password" tabindex="1">
        </div> <!-- /.form-group -->

        <div class="form-group">
          <label for="password2" class="placeholder-hidden">Confirm new password</label>
          <input type="password" class="form-control" id="password2" name="new_pass2" placeholder="Confirm new password" tabindex="2">
        </div> <!-- /.form-group -->

        <div class="form-group clearfix">
          <div class="pull-left">					
            <label class="checkbox-inline">
            <input type="checkbox" class="" value="" tabindex="3"> <small>Remember me</small>
            </label>
          </div>
        </div> <!-- /.form-group -->

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block btn-lg" tabindex="4">Set new password &nbsp; <i class="fa fa-play-circle"></i></button>
        </div> <!-- /.form-group -->

        <?php
          // <input type="hidden" name="from_back" value="http://localhost/lvti-premium/demo5premium2.php">
          echo '<input type="hidden" name="from_back" value="';
          if(isset($_GET['from'])) {
              echo htmlspecialchars($_GET['from']);
          }
          elseif(isset($_POST['from_back'])) {
            echo htmlspecialchars($_POST['from_back']);
          }
          echo '" />';
        ?>
      </form>

    </div> <!-- /.account-body -->



    <div class="account-footer">
      <p>
      Don't have an account? &nbsp;
      <a href="register.php" class="">Create an Account!</a>
      </p>
    </div> <!-- /.account-footer -->








  	<?php 
  				
  			}
  			// PASO 2 de reset password. Ya se ha enviado el nuevo password (posted) y lo hemos guardado

  			elseif ( ($pwd_reset_authorized) && ($new_pwd_posted) ) {
  				
  			// presentar 2 campos de new_pass, y submit para post.
  	?>



    <div class="account-body">

      <h3>
        <?php 
        if ($result_password_ok) {
          echo "Password changed";
        }
        else {
          echo "Password NOT changed";
        }
        ?>
      </h3>

      <br>

      <h5><?php echo $result_password; ?></h5>
      <br>
      <br>
      <?php 
        if ($result_password_ok) {
          $result_class = "fa-thumbs-o-up";  // "fa-smile-o" "fa-check-circle"
          $result_style = "font-size: 12em; color: #398439;";
          $button_class = "btn-success";

        }
        else {
          $result_class = "fa-thumbs-o-down"; // "fa-frown-o"
          $result_style = "font-size: 12em; color: #ec971f;";
          $button_class = "btn-warning";
        }
      ?>
      <p><i class="fa fa-5x <?php echo $result_class; ?>" style="<?php echo $result_style; ?>"></i>&nbsp;</p>

      <br>

      <span class="account-or-social text-muted">Please click here to log in</span>
      <p><a href="<?php echo $pagina_from; ?>" class="btn <?php echo $button_class; ?> btn-lg">Go back to your video page!</a></p>


    </div> <!-- /.account-body -->













  	<?php 
  				
  			}

  			else {
  				// Ha aterrizado aquí desde cualquier otra parte. Something went wrong (claro). Informamos y mostramos login normal
  				// ¿y si está logado? ¿Presentar logout?

  	?>



    <div class="account-body">

      <h3>Changing password...</h3>

      <h5><?php echo $result_notok_msg; ?></h5>

      <br>
      <br>

      <p><i class="fa fa-5x fa-question-circle" style="font-size: 12em; color: #ec971f;"></i>&nbsp;</p>

      <br>

      <span class="account-or-social text-muted">Please, try again later, or sign-in below</span>

      <form class="form account-form" method="POST" action="">

        <div class="form-group">
          <label for="email" class="placeholder-hidden">Email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Your email" tabindex="1">
        </div> <!-- /.form-group -->

        <div class="form-group">
          <label for="password" class="placeholder-hidden">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" tabindex="2">
        </div> <!-- /.form-group -->

        <div class="form-group clearfix">
          <div class="pull-left">					
            <label class="checkbox-inline">
            <input type="checkbox" class="" value="" tabindex="3"> <small>Remember me</small>
            </label>
          </div>

          <div class="pull-right">
            <small><a href="account-forgot.php">Forgot Password?</a></small>
          </div>
        </div> <!-- /.form-group -->

        <div class="form-group">
          <button type="submit" class="btn btn-info btn-block btn-lg" tabindex="4">Signin &nbsp; <i class="fa fa-play-circle"></i></button>
        </div> <!-- /.form-group -->

        <?php
          // <input type="hidden" name="from_back" value="http://localhost/lvti-premium/demo5premium2.php">
          echo '<input type="hidden" name="from_back" value="';
          if(isset($_GET['from'])) {
              echo htmlspecialchars($_GET['from']);
          }
          elseif(isset($_POST['from_back'])) {
            echo htmlspecialchars($_POST['from_back']);
          }
          echo '" />';
        ?>
      </form>

    </div> <!-- /.account-body -->



    <div class="account-footer">
      <p>
      Don't have an account? &nbsp;
      <a href="register.php" class="">Create an Account!</a>
      </p>
    </div> <!-- /.account-footer -->



  	<?php 
  				
  			}
  	?>





    <!-- users -->

  <br class="xs-80">

  <br class="xs-80">


  </div> <!-- /.account-wrapper -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Core JS -->
<script src="social/js/jquery.js"></script>
<script src="social/js/bootstrap.min.js"></script>

<script src="social/js/jquery.slimscroll.min.js"></script>


<!-- App JS -->
<script src="social/js/social-core.js"></script>
<script src="social/js/social-helpers.js"></script>
<script src="social/js/social-admin.js"></script>


<!-- Demo JS -->
<script src="social/js/social-account.js"></script>




<a id="back-to-top" href="#top" style="display: none;"><i class="fa fa-chevron-up"></i></a></body></html>