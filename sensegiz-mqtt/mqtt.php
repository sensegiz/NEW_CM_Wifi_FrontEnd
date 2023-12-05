<?php

$fulDirpath  =  realpath( __DIR__);
$projPath    =  substr($fulDirpath, 0, strrpos( $fulDirpath, '/'));

require_once ($projPath.'/src/config/config.php');
require_once ($projPath.'/src/config/connect.php');
require_once ($projPath.'/src/utils/ConnectionManager.php');

$db = new ConnectionManager();

$aQuery = "SELECT username, password FROM mqtt_user WHERE id =1";
$db->query($aQuery);
$row=$db->single();

$username = $row[username];
$password = $row[password];


?>