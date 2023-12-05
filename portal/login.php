<?php
	session_start();
	if(isset($_SESSION['authString']))
		unset($_SESSION['authString']);
		
	if(isset($_SESSION['userName']))
		unset($_SESSION['userName']);

	if(isset($_SESSION['loginType']))
		unset($_SESSION['loginType']);

	if(isset($_SESSION['adminId']))
		unset($_SESSION['adminId']);

	if(isset($_SESSION['userId']))
		unset($_SESSION['userId']);
        
        if(isset($_SESSION['apikey']))
		unset($_SESSION['apikey']);
        
        if(isset($_SESSION['start']))
            unset ($_SESSION['start']);
        
        if(isset($_SESSION['expire']))
            unset ($_SESSION['expire']);    

	if(isset($_SESSION['date_format']))
		unset($_SESSION['date_format']); 

	if(isset($_SESSION['rms_values']))
		unset($_SESSION['rms_values']);

	if(isset($_SESSION['logo']))
		unset($_SESSION['logo']);

	if(isset($_SESSION['temp_unit']))
		unset($_SESSION['temp_unit']); 
   
        //print_r($_REQUEST);
            
	$_SESSION['userName']   = $_REQUEST['userName'];
//        $_SESSION['email']      = $_REQUEST['email_id'];
	$_SESSION['authString'] = $_REQUEST['authString'];
        
        $_SESSION['loginType']  = $_REQUEST['loginType'];
        
        if(isset($_REQUEST['loginType']) && $_REQUEST['loginType']=='admin'){
            $_SESSION['adminId']   =  $_REQUEST['loginId'];
        }
        if(isset($_REQUEST['loginType']) && $_REQUEST['loginType']=='user'){
            $_SESSION['userId']    =  $_REQUEST['loginId'];
		$_SESSION['date_format']    =  $_REQUEST['date_format'];
		$_SESSION['rms_values']    =  $_REQUEST['rms_values'];
		$_SESSION['logo']    =  $_REQUEST['logo'];
		$_SESSION['temp_unit']    =  $_REQUEST['temp_unit'];
        }
        if(isset($_REQUEST['apikey'])){
            $_SESSION['apikey']    =  $_REQUEST['apikey'];
        }         
        
        $_SESSION['start']  = time(); // Taking now logged in time.
        // Ending a session in 7 days from the starting time.
        $_SESSION['expire'] = $_SESSION['start'] + (7*24*60*60); //604800 seconds
//        $_SESSION['expire'] = $_SESSION['start'] + (3*60);    //testing 3 min    
//        print_r($_SESSION);
//        exit();
?>


