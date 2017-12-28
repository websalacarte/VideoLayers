<?php
include_once("scripts/check_user.php");

// Necesito Socials para set los settings del nuevo usuario.
include_once("includes/defines.php");


    function randomPassword() {
      // http://stackoverflow.com/q/6101956/1414176
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }


// Recojo pagina FROM para devolver al lugar desde el que se ha logado. --> http://localhost/lvti-premium/modules/vl/login.php?from=%2Flvti-premium%2Fdemo5premium2.php
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
    $pagina_from = "index.php";
  }
}

if($user_is_logged == true){
  header("location: ".$pagina_from );
  exit();
}





// funcionalidad retrieve password
// ********************************

if(isset($_POST['forgot_email'])){
  $email1 = strip_tags($_POST['forgot_email']);
  $dbg_email_forgotten = $email1;
  echo("<script>console.log('dbg_email_forgotten=".$email1."');</script>");   

  // make sure no fields are blank /////
  if(trim($email1) == ""){
      echo "Error: Email field is required. Please press back in your browser and try again.";
      $db = null;
      exit();
  }
  if(!filter_var($email1, FILTER_VALIDATE_EMAIL)){
    echo "You have entered an invalid email. Press back and try again";
    $db = null;
        exit();
  }

  // Asegurar email existe en members, crear password ALEATORIO, insertar en db, enviar token. -> enviar a form tq cambien password -> update db (todo sin desactivar)


  //// query to check if email is in the db already ////
  //$stmt = $db->prepare("SELECT id, email, username FROM members WHERE email=:email1 LIMIT 1");
  //$stmt = $db->prepare("SELECT * FROM members WHERE email=:email1 LIMIT 1");
  //$stmt = $db->prepare("SELECT id, email, username FROM members WHERE email='josep_portell@hotmail.com' LIMIT 1");
  $stmt = $db->prepare("SELECT * FROM members WHERE email=? LIMIT 1");
  //$stmt->bindValue(':email1',$email1,PDO::PARAM_STR);
  //$email1_withquotes = "'".$email1."'";
  //$stmt->bindValue(':email1',$email1_withquotes,PDO::PARAM_STR);
  $stmt->bindValue(1,$email1,PDO::PARAM_STR);
  try{
    $stmt->execute();
    $count = $stmt->rowCount();
  }
  catch(PDOException $e){
    echo $e->getMessage();
      $db = null;
      exit();
  }

  ///Check if email is in the db . If it is not, don't procede and inform. ////
  if($count == 0){
    echo "Sorry, that email is not in use in the system";
    $db = null;
    exit();
  }
  else {
    $user_found = $stmt->fetch();
    $username = $user_found['username']; 
    $user_found_id = $user_found['id'];   // irá más rápida la query de update.
    
    $dbg_user_found = 'email: "'.$email1.'", id: '.$user_found_id.', username: '.$username;

    echo("<script>console.log('dbg_user_found=".$dbg_user_found."');</script>");  

    /*
    var_dump($user_found);
    echo "<br>";
    echo "<br>";
    print_r($user_found);
    echo "<br>";
    echo "<br>";

    echo (json_encode($user_found) ) ;
    die;
    */

  }

  // definida funcion randomPassword.
  $pass1 = randomPassword();


  //// create the hmac /////
  $hmac = hash_hmac('sha512', $pass1, file_get_contents('secret/key.txt'));
  //// create random bytes for salt ////
  $bytes = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
  //// Create salt and replace + with . ////
  $salt = strtr(base64_encode($bytes), '+', '.');
  //// make sure our bcrypt hash is 22 characters which is the required length ////
  $salt = substr($salt, 0, 22);
  //// This is the hashed password to store in the db ////
  $bcrypt = crypt($hmac, '$2y$12$' . $salt);
  $token = md5($bcrypt);


  // Updateo password temporal en db, desactivo usuario e inserto en tabla activate (pendiente activacion, click link email)
  try{
    $db->beginTransaction();
    $ipaddress = getenv('REMOTE_ADDR');
    echo("<script>console.log('account_forgot, ip=$ipaddress');</script>");   

    $stmt2 = $db->prepare("UPDATE members SET password=:bcrypt, signup_date=now(), ipaddress=:ipaddress, activated=:activated, lastlog=now() WHERE id=:user_found_id ");
    //$stmt2 = $db->prepare("UPDATE members SET password=:bcrypt, signup_date=now(), ipaddress=:ipaddress, lastlog=now() WHERE id=:user_found_id ");

    $stmt2->bindParam(':user_found_id',$user_found_id,PDO::PARAM_INT);
    //$stmt2->bindParam(':email1',$email1,PDO::PARAM_STR);
    //$stmt2->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt2->bindParam(':bcrypt',$bcrypt,PDO::PARAM_STR);
    $stmt2->bindParam(':ipaddress',$ipaddress,PDO::PARAM_STR);
    $activated = '0';
    $stmt2->bindParam(':activated',$activated,PDO::PARAM_INT);
    $stmt2->execute();

    /// Get the last id inserted to the db which is now this users id for activation and member folder creation ////
    $stmt3 = $db->prepare("INSERT INTO activate (user, token) VALUES ('$user_found_id', :token)");
    $stmt3->bindValue(':token',$token,PDO::PARAM_STR);
    $stmt3->execute();



    //// Send email activation to the new user ////
    $from = "From: JPG <josep@websalacarte.com>";
    $subject = "IMPORTANT: Reseting your password for your VoC Tours account";
    //$link = 'http://www.websalacarte.com/videolayers/v0.7/activate.php?user='.$lastId.'&token='.$token.'';
    //$link = $ruta_raiz.'activate.php?user='.$lastId.'&token='.$token.'';
    //var_dump($_SERVER);
    $domain = $_SERVER['SERVER_NAME'];
    $host = $_SERVER['HTTP_HOST'];
    $http_referer = $_SERVER['HTTP_REFERER'];
    $req_uri = $_SERVER['REQUEST_URI'];
    $uri_params = explode('/', $req_uri, -1);
    $path_uri = implode('/', $uri_params);

    //$path =  $http_referer['dirname']; //basename($_SERVER['HTTP_REFERRER']);
    //$link = $ruta_raiz.'changing-password.php?user='.$user_found_id.'&token='.$token.'&from='.$pagina_from;
    $link = 'http://'.$domain.$path_uri.'/changing-password.php?user='.$user_found_id.'&token='.$token.'&from='.$pagina_from;
    
    /*
    echo "domain: ".$domain;
    echo "<br>";
    echo "host: ".$host;
    echo "<br>";
    echo "path: ".$path;
    echo "<br>";
    echo "http_referer: ".$http_referer;
    echo "<br>";
    echo "req_uri: ".$req_uri;
    echo "<br>";
    echo "uri_params: 0: ".$uri_params[0].', 1: '.$uri_params[1].', 2: '.$uri_params[2].', 3: '.$uri_params[3].', 4: '.$uri_params[4].', 5: '.$uri_params[5].', ';
    echo "<br>";
    echo "*** path_uri: ".$path_uri;
    echo "<br>";
    echo "link: ".$link;
    echo "<br>";
    echo "<br>";
    var_dump($referer);
    echo "<br>";
    */
    //// Start Email Body ////
    $message = "
      You have indicated you want to reset your password for your $username account at VoC Tours! 
      Please click the link below to confirm your identity and choose the new password you want.
      If the link below is not active please copy and paste it into your browser address bar. See you on the site!

      <a href='".$link."' style='text-decoration: underline;'>Click here to choose your new password at VoC Tours</a>
      ";
    //// Set headers ////
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
    $headers .= "From: $from" . "\r\n";
    /// Send the email now ////
    mail($email1, $subject, $message, $headers, '-f info@websalacarte.com');
    //mail($email1, $subject, $message, $headers, '-f noreply@your-email.com');
    $db->commit();
    echo "Reseting your password! Please, check your email in a few moments to finish reseting the password for your account (or check your spam folder if you do not find it). 
    Then you'll be able to choose the password you want!";
    echo "(or click here: <a href='$link'>Local activation</a>)";
    $db = null;
    exit();
  }
  catch(PDOException $e){
    $db->rollBack();
    echo $e->getMessage();
    $db = null;
    exit();
  }
}



?>
<!DOCTYPE html>
<html lang="en" class="no-js"><!--<![endif]-->
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Forgot Password - VoC Tours</title>

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

        <a href="/lvti-premium/" class="navbar-brand navbar-brand-img">
          <img src="social/img/logo.png" alt="">
        </a>
      </div> <!-- /.navbar-header -->

      <nav class="collapse navbar-collapse" role="navigation">   
        <ul class="nav navbar-nav navbar-right">     
          <li>
            <a href="#"><i class="fa fa-angle-double-left"></i> &nbsp;Back to Home</a>
          </li>   
        </ul>
      </nav>

    </div> <!-- /.container -->

  </header>

  <br class="xs-80">

  <div class="account-wrapper">

    <div class="account-body">

      <h2>Password Reset</h2>

      <h5>We'll email you instructions on how to reset your password.</h5>

      <form class="form account-form" method="POST" action="">

        <div class="form-group">
          <label for="forgot-email" class="placeholder-hidden">Your Email</label>
          <input type="text" class="form-control" id="forgot-email" name="forgot_email" placeholder="Your Email" tabindex="1">
        </div> <!-- /.form-group -->

        <div class="form-group">
          <button type="submit" class="btn btn-secondary btn-block btn-lg" tabindex="2">
            Reset Password &nbsp; <i class="fa fa-refresh"></i>
          </button>
        </div> <!-- /.form-group -->

        <div class="form-group">
          <a href="login.php"><i class="fa fa-angle-double-left"></i> &nbsp;Back to Login</a>
        </div> <!-- /.form-group -->
      </form>

    </div> <!-- /.account-body -->

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