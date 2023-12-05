<?php

ini_set('max_execution_time', 0);

$fulDirpath  =  realpath( __DIR__);
$projPath    =  substr($fulDirpath, 0, strrpos( $fulDirpath, '/'));


require_once ($projPath.'/src/config/config.php');
require_once ($projPath.'/src/config/connect.php');
require_once ($projPath.'/src/utils/ConnectionManager.php');
require_once ($projPath.'/src/utils/GeneralMethod.php');


$db = new ConnectionManager();

while(true){

    	$aQuery = "SELECT *"
        	." FROM ping_alert"
        	." WHERE action=0 ORDER BY pa_id ASC LIMIT 1";
    	$db->query($aQuery);
    	$row=$db->single();

    	$data = $row[alert_data];
    	$dump_id = $row[pa_id];
    	$updated_on = $row[received_on];

	if(!empty($data) && (strlen($data)==24)){

    		$gw = split_on($data, 12);
		$gatewayId = $gw[0];
			

		$uQuery = " UPDATE user_gateways "
			. " SET status = 'Online', active = 'Y', updated_on = DATE_TRUNC('second', NOW()), last_ping_alert = DATE_TRUNC('second', NOW()), gateway_status_mail=0"
			. " WHERE gateway_id=:gateway_id AND is_deleted =0 ";

		$db->query($uQuery);                    
		$db->bind(':gateway_id',$gatewayId);               
				
		if($db->execute()){ 
			$eQuery = "UPDATE ping_alert"
				." SET action = 1"
				." WHERE pa_id=:dump_id";
			$db->query($eQuery);
			$db->bind(':dump_id', $dump_id);
			$db->execute();

		}
	}
						
}

//split into 2 strings, first one will be based on length and second one will be remaining string
function split_on($string, $num) {
    $length = strlen($string);
    $output[0] = substr($string, 0, $num);
    $output[1] = substr($string, $num, $length);
    return $output;
}

?>