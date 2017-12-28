<?php
//include_once("scripts/connect.php");
include_once("scripts/check_user.php");
if($user_is_logged == true){
	header("location: index.php");
	exit();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Register through G+</title>
	<?php include_once("head_common.php"); ?>
	
<!--<link rel="stylesheet" href="style/style.css"/>-->
<script type="text/javascript" src="js/serialize.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>
</head>

<div class="main_container">
<?php include_once("header_template.php"); ?>
	 
	<!-- CONTAINER -->
	<h2>Register through <span>Google+</span></h2>
	<div class="container">
		<div id="form">
		
			<div class="contentBottom">
				<div id="whole_page">
					<h2 style="text-align:center;">Registration through Google+ </h2>
					<p id="message_span">To register your VideoLayers account using your Google+ profile information click the "Authorize" button below to allow us to gather information. We will only gather your email address, public id, and basic details like first and last name. 
						This data is used solely to identify you here on the site.
						<br /><p class="submit"><button id="authorize-button">Get your data from Google</button></p></p>
					<br />
<!-- *** -->					<div id="form" class="form">
						<strong style="text-align:center">Please verify the information below and click next.</strong>
						<br />
						<br />
						<div id="signup_form">
							<form id="gp_signup">
								<label for="displayName"><strong>How your name will appear</strong>
									<br />
									<input type="text" id="displayName" name="displayName">
								</label>
								<br />
								<label for="email"><strong>Your email</strong>
									<br />
									<input type="text" id="email" name="email">
								</label>
								
								<input type="hidden" id="verified_email" name="verified_email">
								<input type="hidden" id="ext_id" name="ext_id">
								<br />
								<br />
								<p class="submit">
									<button id="signUpBtn" onclick="return false;" type="submit">Next</button>
								</p>
							</form>
						</div>
					</div>
				</div>
				<br />
			</div>
		</div>
		
		<div class="clearfloat"></div>
	<!-- end .container -->
	</div>
</div>

<script type="text/javascript">
      var clientId = '414125108458-3ilvoocqj06j7q19tmdrrb12gr04n2rv.apps.googleusercontent.com'; //'113990362337680373105';
      var apiKey = 'AIzaSyD6wQt5Nz26dacxDUATm3tArVy1O9q4Ub8';	//'AIzaSyDSZFPN52FoNeR7ePLDAKTMCpRIgQ9kEuQ';
      var scopes = 'https://www.googleapis.com/auth/plus.me https://www.googleapis.com/auth/userinfo.email';

      function handleClientLoad() {
        gapi.client.setApiKey(apiKey);
        window.setTimeout(checkAuth,1);
      }

      function checkAuth() {
        gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: true}, handleAuthResult);
      }


      function handleAuthResult(authResult) {
        var authorizeButton = document.getElementById('authorize-button');
        if (authResult) {
          authorizeButton.style.visibility = 'hidden';
          makeApiCall();
        } else {
          authorizeButton.style.visibility = '';
          authorizeButton.onclick = handleAuthClick;
        }
      }

      function handleAuthClick(event) {
        gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: false}, handleAuthResult);
        return false;
      }

      function logResponse(resp) {
        console.log(resp);
      }

      function makeApiCall() {

        gapi.client.load('oauth2', 'v2', function() {
          var request = gapi.client.oauth2.userinfo.get();

          request.execute(function(logResponse){
			  document.getElementById('email').value = logResponse.email;
			  document.getElementById('verified_email').value = logResponse.verified_email;
			  document.getElementById('ext_id').value = logResponse.id;
			  console.log(logResponse.email);
			  console.log(logResponse.id);
		  });
        });
		
        gapi.client.load('plus', 'v1', function() { 
          var request = gapi.client.plus.people.get({ 
            'userId': 'me' 
          });

          request.execute(function(logResponse){
			 document.getElementById('displayName').value = logResponse.displayName;
			 document.getElementById('message_span').style.display = 'none';
			  //console.log(logResponse.displayName+'|'+logResponse.email+'|'+logResponse.image.url+'|'+email);
			  console.log(logResponse.image.url);
			  console.log(logResponse.email);
			  console.log(logResponse);
		  });
        });
      }
    </script>
    <script src="https://apis.google.com/js/client.js?onload=handleClientLoad">
    </script>
    <script>
document.getElementById('signUpBtn').onmousedown = function(){
ajax('gp_signup',
	 'POST', 
	 'ext_signup.php', 
	 'signup_form'
)};
</script>
<?php include_once("foot_common.php"); ?>

</body>
</html>
