<?php
/*
  Project                     : Sensegiz
  Module                      : Connect
  File name                   : connect.php
  Description                 : Connecting to Production or Development Server 
 */

if(SERVER =='DEVELOPMENT'){
    define("HOST", "localhost");
    define("USER", "root");
    define("PASSWORD", "CoinLive@AWS");
    define("DATABASE", "postgres");
    define("BASE_URL_STRING", getBaseUrl()); 
    define("WEB_LOGIN_URL", BASE_URL_STRING.WEB_CPANEL_FOLDER); 
}

if(SERVER =='STAGING'){
    define("HOST", "localhost");
    define("PORT", "5432");
    define("USER", "postgres"); 
    define("PASSWORD", "CoinLive@AWS");
    define("DATABASE", "postgres");
    define("BASE_URL_STRING", getBaseUrl());
    define("WEB_LOGIN_URL", BASE_URL_STRING.WEB_CPANEL_FOLDER); 
}

if(SERVER =='PRODUCTION'){
    define("HOST", "localhost");
    define("USER", "root");
    define("PASSWORD", "CoinLive@AWS");
    define("DATABASE", "postgres");
    define("BASE_URL_STRING", getBaseUrl());
    define("WEB_LOGIN_URL", BASE_URL_STRING.WEB_CPANEL_FOLDER); 
}

/*
 Function            : getBaseUrl
 Brief               : Function to get Base URL
 Details             : Function to get Base URL
 Input param         : Nil
 Input/output param  : String
 Return              : Returns string.
*/ 

function getBaseUrl()
{
    $currentPath = $_SERVER['PHP_SELF'];$pathInfo = pathinfo($currentPath);
    $hostName = $_SERVER['SERVER_NAME'];
    $protocol =   isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
    
                $url  = $protocol."://".$hostName.$pathInfo['dirname']."/";
            if (substr($url, -1) == '/')
                    $url = substr($url, 0, -1);
return $url;
  //  return $protocol."://".$hostName.$pathInfo['dirname']."/";
}
