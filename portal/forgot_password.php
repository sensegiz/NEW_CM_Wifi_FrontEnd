<?php

	$fulDirpath  =  realpath( __DIR__);
	$projPath    =  substr($fulDirpath, 0, strrpos( $fulDirpath, '/')); //  /var/www/html/sensegiz-dev
	$SERVER_IP  = $_SERVER['HTTP_HOST'];
	require ($projPath.'/library/sendgrid-php/vendor/autoload.php');
	require_once '../src/config/config.php';

	pg_connect('host=localhost port=5432 dbname=iot_stream user=postgres password='.DB_PASS) or die("Couldn't Connect ".pg_last_error()); // Connect to the Database



	if( isset($_POST['btn-fpw']) ) {	
		$user_email = trim($_POST['email']);
		if(empty($user_email)){
			$error = true;
			$emailError = "Please enter your Email ID!";
		}else{
			$res=pg_query("SELECT user_email FROM users WHERE user_email='$user_email' AND is_deleted = 0");
			$row=pg_fetch_array($res);
			$count = pg_num_rows($res);

			if( $count == 1 ) {
				$otp = mt_rand(10000, 99999);

				$subject     =  'OTP for your SenseGiz Portal';
								
				sendMails($user_email,$subject,$otp);

				$query = ("UPDATE users SET otp = '$otp' WHERE user_email='$user_email'");
				$res = pg_query($query);

				header("Location: reset_password.php");

			}else{
				$error = true;
				$emailError = "This Email ID is not registered!";

			}
		}
	}

    

function sendMails($emailsArray,$subject,$otp){

	/*require("/var/www/html/sensegiz-dev/library/sendgrid-php/sendgrid-php.php");


	$from = new SendGrid\Email(null, "info@sensegiz.com");

	$to = new SendGrid\Email(null, $emailsArray);

	$content = new SendGrid\Content("text/html", "<html><head><title>Reset Password</title></head><body>
                                <div style=\"text-align: center\"><h3>You have requested for a password change on your SenseGiz account!</h3> <h4>The OTP for the password change is $otp.</h4> </div>                                   
                                            </body></html>");

	$mail = new SendGrid\Mail($from, $subject, $to, $content);

	$apiKey = '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';
	$sg = new \SendGrid($apiKey);

	$response = $sg->client->mail()->send()->post($mail);
	echo $response->statusCode();
	echo $response->headers();
	echo $response->body();*/

	$message = "<html><head><title>Reset Password</title></head><body>
                                <div style=\"text-align: center\"><h3>You have requested for a password change on your SenseGiz account!</h3> <h4>The OTP for the password change is $otp.</h4> </div>                                   
                                            </body></html>";

	$message = urlencode($message);
	$subject = urlencode($subject);
	$ch=curl_init();

	curl_setopt($ch,CURLOPT_URL,"http://".$SERVER_IP.":3000/ses/?message=".$message."&to=".$emailsArray."&subject=".$subject."");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$output =curl_exec($ch);
	curl_close($ch);
          
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>SenseGiz</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/master.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/developer.css" rel="stylesheet"> 

<style>
.error-msg{
	/*float: left;*/
        padding-bottom: 10px;
}

</style>
</head>
<body>
<div class="login-detail">
    <div class="login-logo"><img width="224" height="76" src="img/logo.png" alt="logo"/></div>
<form method="POST" class="login" >
<div class="login-frm">
<h6>Forgot Password</h6>
<span class="error-msg"></span>
<input type="text" id="email" name="email" class="validation" placeholder="Email*" />
	  <br/>
<span class="text-danger"><?php echo $emailError; ?></span>
<span class="log-last">	

    <input type="submit" class="save" name="btn-fpw" value="Get OTP"/></a>
</span>
</div>
<br/>
 <div class="row">
  <div class="col-sm-6" style="text-align:left; text-decoration: underline; text-decoration-color: #2a6496;"><a href="index.php" >Sign In here</a></div>
</div> 
</form>
</div>
<!--scripts-->
<script src="js/jquery-1.11.0.min.js"></script> 
<script src="js/jquery-migrate-1.2.1.min.js"></script> 
<script src="js/bootstrap.min.js"></script>
<script src="js/validate.js"></script>
<script src="js/url_path.js"></script>
<script src="js/jquery.serialize-object.min.js"></script>

<script src="js/custom.js"></script>

<script>
$(document).keypress(function(e) {
    if(e.which == 13) {
       $('.save').click();
    }
});


</script>
</body>
</html>