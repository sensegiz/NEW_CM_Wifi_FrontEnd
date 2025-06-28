<?php
//To supress the errors
error_reporting(1);
ini_set('display_errors', 1);
/*
  Project                     : Sensegiz
  Module                      : NA
  File name                   : config.php
  Description                 : Contains configuration settings used in the project codes.
 */
/*********Credentials*********/
define("DBTYPE","pgsql");
//define("SERVER", "DEVELOPMENT");
define("SERVER", "STAGING");
//define("SERVER", "PRODUCTION");

define("WEB_CPANEL_FOLDER","admin/");
define("TIMEZONE", "UTC");

define("URL_END_POINT", "https://cm2.sensegiz.com/sensegiz-dev/");
//define("URL_END_POINT", "http://40.83.125.24/sensegiz/");

define("SERVER_IP", "cm2.sensegiz.com");

define("DB_PASS", "CoinLive@AWS");

//Sendgrid API KEY /vinayct accnt
//define("SENDGRID_APIKEY","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");
define("SENDGRID_APIKEY","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");


//define('VERIFICATION_FILE_PATH', 'http://cumulations.com/api');

//define('ADMIN_PATH', 'http://localhost/sensegiz/portal');//development
define('ADMIN_PATH', 'https://cm2.sensegiz.com/sensegiz-dev/portal');//staging
//define('ADMIN_PATH', 'http://40.83.125.24/sensegiz/portal');//master

//define('PROJECT_PATH', __DIR__);

ini_set('max_execution_time', 0); 
date_default_timezone_set(TIMEZONE);
?>
