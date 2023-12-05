<?php

ini_set('max_execution_time', 0);
 

$fulDirpath  =  realpath( __DIR__);
$projPath    =  substr($fulDirpath, 0, strrpos( $fulDirpath, '/'));

require_once ($projPath.'/src/config/config.php');
require_once ($projPath.'/src/config/connect.php');
require_once ($projPath.'/src/utils/ConnectionManager.php');
require_once ($projPath.'/src/utils/GeneralMethod.php');


$db = new ConnectionManager();
	

function sensor_dashboard(){

	$db = new ConnectionManager();

    	$sQuery = 	"SELECT user_id"
		." FROM users";	

    	$db->query($sQuery);
    	$res = $db->resultSet();

    	if(is_array($res) && !empty($res)){
        	foreach ($res as $key => $value) {
        		$user_id = $value[user_id];
        		$date = date('Y-m-d H:i:s');
        
			$query = "INSERT INTO general_settings (user_id, accelerometer, gyroscope, temperature, humidity, stream, accelerometerstream) "
				. "VALUES (:user_id, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y')";
			$db->query($query);                	
			$db->bind(':user_id',$user_id);
                	$db->execute();			
        		                                                                                                          
            	}           
        }
		
				
	
}


sensor_dashboard();

	
?>