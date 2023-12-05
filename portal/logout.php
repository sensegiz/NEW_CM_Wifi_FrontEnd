<?php
	session_start();
	unset($_SESSION['userName']);
	unset($_SESSION['authString']);
	session_destroy(); 
?>
