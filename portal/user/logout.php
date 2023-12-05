<?php
	$SERVER_IP  = $_SERVER['HTTP_HOST'];
	session_start();
	session_destroy(); 
	header("location:https://".$SERVER_IP."/sensegiz-dev/portal/index.php");
	exit();
?>

