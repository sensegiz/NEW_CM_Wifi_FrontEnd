<?php

//require '/var/www/html/sensegiz-dev/src/utils/CurlRequest.php';


$fulDirpath  =  realpath( __DIR__);
$projPath    =  substr($fulDirpath, 0, strrpos( $fulDirpath, '/'));

require_once ($projPath.'/src/config/config.php');
require_once ($projPath.'/src/config/connect.php');
require_once ($projPath.'/src/config/jsontags.php');
require_once ($projPath.'/src/utils/ConnectionManager.php');
require_once ($projPath.'/src/utils/GeneralMethod.php');
require_once ($projPath.'/src/utils/CurlRequest.php');
	
$db = new ConnectionManager();
$curl     = new CurlRequest();

while(true)
{
	
	$aQuery = "SELECT *"
        	." FROM request_list"
        	." WHERE request_type IN ('GET','SET','SETS', 'EnD', 'DETP', 'FREQ', 'GETSENSOR') AND action=0 and resent = 'NO' ORDER BY rl_id ASC LIMIT 1";
    	$db->query($aQuery);
    	$row=$db->single();

	$currentTime = date("Y-m-d H:i:s");
	$requestSentTime = $row['published_on'];
	$seconds = strtotime($currentTime) - strtotime($requestSentTime);

	$rl_id = $row['rl_id'];
	$gatewayId = $row['gateway_id'];
	$gateway_mac_id = $row['gateway_mac_id'];
	$deviceId = $row['device_id'];
	$device_type = $row['device_type'];
	$device_value = $row['device_value'];
	$request_type = $row['request_type'];
	$threshold_value = $row['threshold'];
	$format = $row['format'];
	$rate_value = $row['rate_value'];
	$dynamic_id = $row['dynamic_id'];

	
	if($request_type == 'GET'){
		if($seconds >= 60 && $seconds < 180)
		{
			$data_arr  =  array();

			$data_arr['gateway_mac_id']    =  $gateway_mac_id;
                	$data_arr['gateway_id']    =  $gatewayId;
                	$data_arr['device_id']     =  $deviceId;
                	$data_arr['device_type']   =  $device_type;        
                    	$data_arr['dynamic_id']     =  $dynamic_id;
                                
               		$data_arr['topic']         =  TOPIC_GET_CURRENTVALUE;

               		$url        =   URL_END_POINT.'sensegiz-mqtt/publish.php';

               		$response   =  $curl->postRequestData($url, $data_arr);

		
			$uQuery = " UPDATE request_list SET resent='YES', updated_on=now() WHERE rl_id=$rl_id";
			$db->query($uQuery);	
			$db->execute(); 	
		
		}
		if($seconds >= 180){
				$uQuery = " UPDATE request_list SET action=2, updated_on=now() WHERE rl_id=$rl_id";
				$db->query($uQuery);	
				$db->execute(); 
		}
	}
	if($request_type == 'SET'){
		if($seconds >= 60 && $seconds < 180)
		{
			$data_arr  =  array();
                    	$data_arr['gateway_mac_id']    =  $gateway_mac_id;
                    	$data_arr['gateway_id']    =  $gatewayId;
                    	$data_arr['device_id']     =  $deviceId;
                    	$data_arr['device_type']   =  $device_type;
                    
                    	$data_arr['device_value']   =  $device_value;
                    
                    	$data_arr['threshold_value']     =  $threshold_value;

                    	$data_arr['dynamic_id']     =  $dynamic_id;
                    
                    	$data_arr['topic']         =  TOPIC_SET_THRESHOLD;
            
                	$url        =  URL_END_POINT.'sensegiz-mqtt/publish.php';
                
                	$response   =  $curl->postRequestData($url, $data_arr);

			$uQuery = " UPDATE request_list SET resent='YES', updated_on=now() WHERE rl_id=$rl_id";
			$db->query($uQuery);	
			$db->execute(); 

		}
		if($seconds >= 180){
				$uQuery = " UPDATE request_list SET action=2, updated_on=now() WHERE rl_id=$rl_id";
				$db->query($uQuery);	
				$db->execute(); 
		}
	}
	if($request_type == 'SETS'){
		if($seconds >= 60 && $seconds < 180)
		{
			$data_arr  =  array();
                    	$data_arr['gateway_mac_id']    =  $gateway_mac_id;
                    	$data_arr['gateway_id']    =  $gatewayId;
                    	$data_arr['device_id']     =  $deviceId;
                    	$data_arr['device_type']   =  $device_type;
                    
                    	$data_arr['format']        =  $format;
                    
                    	$data_arr['rate_value']     =  $rate_value;
                    	$data_arr['dynamic_id']     =  $dynamic_id;
                    
                    	$data_arr['topic']         =  TOPIC_SET_STREAM;
            
                	$url        =  URL_END_POINT.'sensegiz-mqtt/publish.php';
                
                	$response   =  $curl->postRequestData($url, $data_arr);

			$uQuery = " UPDATE request_list SET resent='YES', updated_on=now() WHERE rl_id=$rl_id";
			$db->query($uQuery);	
			$db->execute(); 

		}
		if($seconds >= 180){
				$uQuery = " UPDATE request_list SET action=2, updated_on=now() WHERE rl_id=$rl_id";
				$db->query($uQuery);	
				$db->execute(); 
		}
	}
	if($request_type == 'EnD'){
		if($seconds >= 60 && $seconds < 180)
		{
			$data_arr  =  array();
                    
                    	$data_arr['gateway_id']    =  $gatewayId;
                    	$data_arr['device_id']     =  $deviceId;
                    	$data_arr['device_type']   =  $device_type;
                    	$data_arr['gateway_mac_id']    =  $gateway_mac_id;
                    	$data_arr['format']        =  $format;
                    	$data_arr['dynamic_id']     =  $dynamic_id;  
                    
                    	$data_arr['rate_value']     =  $rate_value;
                    
                    	$data_arr['topic']         =  TOPIC_SET_STREAM;
            
                	$url        =  URL_END_POINT.'sensegiz-mqtt/publish.php';
                
                	$response   =  $curl->postRequestData($url, $data_arr);


			$uQuery = " UPDATE request_list SET resent='YES', updated_on=now() WHERE rl_id=$rl_id";
			$db->query($uQuery);	
			$db->execute(); 

		}
		if($seconds >= 180){
				$uQuery = " UPDATE request_list SET action=2, updated_on=now() WHERE rl_id=$rl_id";
				$db->query($uQuery);	
				$db->execute(); 
		}
	}			
	if($request_type == 'DETP'){
		if($seconds >= 60 && $seconds < 180)
		{
			$data_arr  =  array();
                    	$data_arr['gateway_mac_id']    =  $gateway_mac_id;
                    	$data_arr['gateway_id']    =  $gatewayId;
                    	$data_arr['device_id']     =  $deviceId;
                    	$data_arr['device_type']   =  $device_type;
                    
                    	$data_arr['format']        =  $format;
                    
                    	$data_arr['rate_value']     =  $rate_value;
                    	$data_arr['dynamic_id']     =  $dynamic_id;
                    
                    	$data_arr['topic']         =  TOPIC_SET_STREAM;
            
                	$url        =  URL_END_POINT.'sensegiz-mqtt/publish.php';
                
                	$response   =  $curl->postRequestData($url, $data_arr);

			$uQuery = " UPDATE request_list SET resent='YES', updated_on=now() WHERE rl_id=$rl_id";
			$db->query($uQuery);	
			$db->execute(); 

		}
		if($seconds >= 180){
				$uQuery = " UPDATE request_list SET action=2, updated_on=now() WHERE rl_id=$rl_id";
				$db->query($uQuery);	
				$db->execute(); 
		}
	}

	if($request_type == 'FREQ'){
		if($seconds >= 60 && $seconds < 180)
		{
			$data_arr  =  array();
                    	$data_arr['gateway_mac_id']    =  $gateway_mac_id;
                    	$data_arr['gateway_id']    =  $gatewayId;
                    	$data_arr['device_id']     =  $deviceId;
                    	$data_arr['device_type']   =  $device_type;
                    
                    	$data_arr['format']        =  $format;
                    
                    	$data_arr['rate_value']     =  $rate_value;
                    	$data_arr['dynamic_id']     =  $dynamic_id;
                    
                    	$data_arr['topic']         =  TOPIC_SET_STREAM;
            
                	$url        =  URL_END_POINT.'sensegiz-mqtt/publish.php';
                
                	$response   =  $curl->postRequestData($url, $data_arr);

			$uQuery = " UPDATE request_list SET resent='YES', updated_on=now() WHERE rl_id=$rl_id";
			$db->query($uQuery);	
			$db->execute(); 

		}
		if($seconds >= 180){
				$uQuery = " UPDATE request_list SET action=2, updated_on=now() WHERE rl_id=$rl_id";
				$db->query($uQuery);	
				$db->execute(); 
		}
	}

	if($request_type == 'GETSENSOR'){
        if($seconds >= 30 && $seconds < 180)
        {
            $data_arr  =  array();
            $data_arr['gateway_mac_id']    =  $gateway_mac_id;
            $data_arr['gateway_id']    =  $gatewayId;
            $data_arr['device_type']   =  $device_type;
            $data_arr['rate_value']     =  $rate_value;
            $data_arr['device_id']     =  $deviceId;
        
            $data_arr['format']        =  $format;
        
            $data_arr['dynamic_id']     =  $dynamic_id;
        

            $data_arr['topic']         =  TOPIC_SET_STREAM;
            print_r($data_arr);

            $url        =  URL_END_POINT.'cm-processing/publish.php';
            print_r($url);

            $response   =  $curl->postRequestData($url, $data_arr);
            
            $uQuery = " UPDATE request_list SET action=1, updated_on=now() WHERE rl_id=$rl_id";
            $db->query($uQuery);    
            $db->execute();

        }
        if($seconds >= 180){
            $uQuery = " UPDATE request_list SET action=2, updated_on=now() WHERE rl_id=$rl_id";
            $db->query($uQuery);    
            $db->execute(); 
        }
    }


}

?>
