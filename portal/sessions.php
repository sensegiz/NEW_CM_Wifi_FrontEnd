	
<?php
	session_start();        
        $now = time();
        
        
	if((isset($_SESSION['expire']) && $_SESSION['expire'] > $now) && isset($_SESSION['authString']) && $_SESSION['authString'] != '' && isset($_SESSION['userName']) && $_SESSION['userName'] != '' ) {

		$userName   = $_SESSION['userName'];
		$authString = $_SESSION['authString'];
		$headerString = '$.ajaxSetup({ headers: {Authorization: "'. $authString . '"}}); userName = "' . $userName . '"' ;	
		
		//echo '$("document").ready(function(){';
		echo $headerString;
		//echo '});';
	} else {
                session_destroy();
		echo 'window.location = "../index.php"';
	}
	
?>


