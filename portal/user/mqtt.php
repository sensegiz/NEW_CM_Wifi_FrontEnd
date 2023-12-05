<?php

require_once('../../src/config/config.php');
require_once('../../src/config/connect.php');
require_once('../../src/utils/ConnectionManager.php');

$db = new ConnectionManager();

$aQuery = "SELECT username, password FROM mqtt_user WHERE id = 1";
$db->query($aQuery);
$row=$db->single();

$username = $row[username];
$password = $row[password];


?>