<?php

	session_start();
	if(isset($_SESSION['authString']) && $_SESSION['authString'] != '' && isset($_SESSION['loginType']) && $_SESSION['loginType'] != '' && isset($_SESSION['userName']) && $_SESSION['userName'] != '') {
		
            if($_SESSION['loginType']=='admin'){
                header('Location: admin/users.php');	
            }
            elseif($_SESSION['loginType']=='user'){
                header('Location: user/gateways.php');	
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

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

	<script src="https://use.fontawesome.com/webfontloader/1.6.24/webfontloader.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/b9bdbd120a.css" media="all">
	<!-- <script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-30d18ea41045577cdb11c797602d08e0b9c2fa407c8b81058b1c422053ad8041.js"></script> -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<style>
.error-msg{
	/*float: left;*/
        padding-bottom: 10px;
}
.fa-fw {

    width: 1.28571429em;
    text-align: center;
    margin-left: -22px;

}
</style>
</head>
<body class="login-sn">
<div class="login-detail">
    <div class="login-logo"><img width="224" height="76" src="img/logo.png" alt="logo"/></div>
<form name="login" id="login" class="login" >
<div class="login-frm">
<h6>Login</h6>
<span class="error-msg"></span>
<input type="text" id="email" name="email" class="validation" placeholder="Email*" />
<p id="show_hide_password">
<input type="password" id="password" name="password" class="validation" placeholder="Password*" style="margin-left:-14px" />
 <span style="margin-left:-32px;font-size:15px"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
</p>
<label style="display: inline-block;  width: 100px;">
    <input type="radio" name="login_type" id="user" value="user" checked/>USER
</label>

<label style="display: inline-block; width: 150px;">
    <input type="radio" name="login_type" id="admin" value="admin" />ADMIN
</label>

  

<span class="log-last">

    <input type="submit" class="save" value="Sign In"/></a>
</span>
</div>
<br/>
 <div class="row">
  <div class="col-sm-6" style="text-align:left; font-size:10px;">Version 4.3</div>
  <div class="col-sm-6" style="text-align:right; text-decoration: underline; text-decoration-color: #2a6496;"><a href="forgot_password.php" class="forgotpw">Forgot Password</a></div>
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

<script>
	$(document).ready(function() {
    $("#show_hide_password span").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_password i').addClass( "fa-eye" );
        }
    });
});
</script>

</body>
</html>