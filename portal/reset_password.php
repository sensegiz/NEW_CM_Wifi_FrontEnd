<?php
	$fulDirpath  =  realpath( __DIR__);
	$projPath    =  substr($fulDirpath, 0, strrpos( $fulDirpath, '/')); //  /var/www/html/sensegiz-dev

	require ($projPath.'/library/sendgrid-php/vendor/autoload.php');
	require_once '../src/config/config.php';


	pg_connect('host=localhost port=5432 dbname=iot_stream user=postgres password='.DB_PASS) or die("Couldn't Connect ".pg_last_error()); // Connect to the Database



	if (isset($_POST['btn-rpw'])) {

		$otp = trim($_POST['otp']);
		$user_pw = trim($_POST['user_password']);
		$user_cpw = trim($_POST['cpass']);

		$res4=pg_query("SELECT * FROM users WHERE otp='$otp'");

		if (empty($otp)) {
			$error = true;
			$otpError= "Please enter your OTP!";
		}
		else if (strlen($otp) < 5 || strlen($otp) > 5) {
			$error = true;
			$nameError = "Invalid OTP!";
		}
		else if(pg_fetch_array($res4) == 0){
		    	$error = true;
			$nameError = "Invalid OTP!";
		}
		else if (empty($user_pw)) {
			$error = true;
			$passError= "Please enter yours new password.";
		}
		else if(strlen($user_pw) < 6) {
			$error = true;
			$passError = "Password must have atleast 6 characters.";
		}
		else if (empty($user_cpw)) {
			$error = true;
			$cpassError= "Please re-confirm your new password.";
		}
		else if($user_pw != $user_cpw){
			$error = true;
                        $cpassError = "Password doesn't match.";
		}
		else{
			$user_password = md5($user_pw);

			$query = ("UPDATE users SET user_password = '$user_password', otp = 0 WHERE otp = '$otp'");
			$res = pg_query($query);
			
			if ($res) {
				
				echo "<script type ='text/javascript'>alert('Your password has been reset successfully! You can login using the new password.'); window.location= 'index.php';</script>";
			} 
		}
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
<h6>Reset Password</h6>
<span class="error-msg"></span>

<input type="text" name="otp" placeholder="Enter OTP" value="<?php echo $name ?>" />
	  <br/>
<span class="text-danger"><?php echo $otpError; ?></span>

<input type="password" name="user_password" placeholder="Enter Password"/>
	  <br/>
<span class="text-danger"><?php echo $passError; ?></span>

<input type="password" name="cpass" placeholder="Confirm Password" />
	  <br/>
<span class="text-danger"><?php echo $cpassError; ?></span>

<span class="log-last">	

<input type="submit" class="save" name="btn-rpw" value="Reset"/></a>
</span>
</div>
<br/>
 <div class="row">
  <div class="col-sm-6" style="text-align:left; text-decoration: underline; text-decoration-color: #2a6496;"><a href="forgot_password.php" >Resend OTP..</a></div>
  <div class="col-sm-6" style="text-align:right; text-decoration: underline; text-decoration-color: #2a6496;"><a href="index.php" >Sign In here</a></div>
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