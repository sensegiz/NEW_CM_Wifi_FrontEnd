<?php

ini_set('max_execution_time', 0);
require("phpMQTT.php");

require("mqtt.php");
	

$fulDirpath  =  realpath( __DIR__);//  /var/www/devsensegiz/public_html/sensegiz-dev/sensegiz-mqtt
$projPath    =  substr($fulDirpath, 0, strrpos( $fulDirpath, '/'));//  /var/www/devsensegiz/public_html/sensegiz-dev
require_once ($projPath.'/src/config/config.php');
require_once ($projPath.'/src/config/connect.php');
require_once ($projPath.'/src/utils/ConnectionManager.php');
require_once ($projPath.'/src/utils/GeneralMethod.php');


//Connect to MQTT server
$mqtt = new phpMQTT(SERVER_IP, 8883, "PubSenseDev", $username, $password); 
print_r($mqtt);
//if you get some post data then only publish
    if ($mqtt->connect()) {
        echo "Connected to publisher";
        
        
        if(isset($_POST['topic'])){

            $db             = new ConnectionManager();
                       

            $topic   = (string) $_POST['topic'];
            $message = '';

            echo '-'.$topic.'-';



//Publish setthreshold topic
            if($topic == 'setthreshold' && isset($_POST['device_id']) && isset($_POST['gateway_id']) && isset($_POST['gateway_mac_id']) && isset($_POST['device_type']) && isset($_POST['threshold_value']) && isset($_POST['dynamic_id'])){
                $device_id          = $_POST['device_id'];
                $gateway_id         = $_POST['gateway_id'];
		$gateway_mac_id         = $_POST['gateway_mac_id'];
                $device_type        = $_POST['device_type'];
                $device_value       = '00';                
                $threshold_value    = $_POST['threshold_value'];
		$dynamic_id = $_POST['dynamic_id'];

		               
                //New format. 20bits length=> 12bits=> gw_id, 2bits=>sensor id, 2bits=>sensor type, 2bits=>value, 2bits=> threshold
                $message = (string)($gateway_mac_id.''.$device_id.''.$device_type.''.$device_value.''.$threshold_value.''.$dynamic_id);
                                                         
                        
            }
         



//publish setstream topic - publish.php
if($topic == 'setstream' && isset($_POST['device_id']) && isset($_POST['gateway_id']) && isset($_POST['gateway_mac_id']) && isset($_POST['device_type']) && isset($_POST['rate_value']) && isset($_POST['dynamic_id'])){

                $device_id          = $_POST['device_id'];
                $gateway_id         = $_POST['gateway_id'];
                $device_type        = $_POST['device_type'];
                $format             = $_POST['format'];
                $rate_value         = $_POST['rate_value'];
		$gateway_mac_id         = $_POST['gateway_mac_id'];
		$dynamic_id = $_POST['dynamic_id'];


	if($device_type == '45' || $device_type == '41' || $device_type == '47' || $device_type == '54' || $device_type == '48' || $device_type == '68' || $device_type == '74' || $device_type == '61' || $device_type == '18'  || $device_type == '76'){
		$message = (string)($gateway_mac_id.''.$device_id.''.$device_type.''.$rate_value.''.$format.''.$dynamic_id);
	}
	else {                              
                
		$message = (string)($gateway_mac_id.''.$device_id.''.$device_type.''.$format.''.$rate_value.''.$dynamic_id);
		
	}	              
}

//Publish getcurrentvalue topic            
            if($topic == 'getcurrentvalue' && isset($_POST['device_id']) && isset($_POST['gateway_id']) && isset($_POST['gateway_mac_id']) && isset($_POST['device_type']) && isset($_POST['dynamic_id'])){
                $device_id          = $_POST['device_id'];
                $gateway_id         = $_POST['gateway_id'];
                $device_type        = $_POST['device_type'];
		$gateway_mac_id         = $_POST['gateway_mac_id'];
		$dynamic_id = $_POST['dynamic_id'];
                
                //last 4 bits should be 0's because we are not going to send sensor value and th value.
                //New format. 20bits length=> 12bits=> gw_id, 2bits=>sensor id, 2bits=>sensor type, 2bits=>value, 2bits=> threshold
                $message = (string)($gateway_mac_id.''.$device_id.''.$device_type.'0000'.''.$dynamic_id);                
            }

//Publish settime topic            
            if($topic == 'settime' && isset($_POST['device_id']) && isset($_POST['gateway_id']) && isset($_POST['gateway_mac_id']) && isset($_POST['device_value'])  && isset($_POST['dynamic_id'])){
           
	    $device_id          = $_POST['device_id'];
                $gateway_id         = $_POST['gateway_id'];
                $device_value        = $_POST['device_value'];
		$gateway_mac_id         = $_POST['gateway_mac_id'];
		$dynamic_id = $_POST['dynamic_id'];
                
                $message = (string)($gateway_mac_id.''.$device_id.''.$device_value.''.$dynamic_id);                
            }


  
//Publish valid coin topic            
            if($topic == 'validcoin' && isset($_POST['device_mac_address'])){
                
                $deviceMac   =      $_POST['device_mac_address'];
                
                $message     =      (string)($deviceMac);  
                
                print_r('pub valid coin');
            } 
            
//Publish Unregistered topic            
            if($topic == 'unregisteredcoin' && isset($_POST['device_mac_address'])){
                
                $deviceMac   =      $_POST['device_mac_address'];
                
                $message     =      (string)($deviceMac);  
                
                print_r('pub unreg coin');
            }            
            
            
        if($topic!='' && $message!=''){
            
            $mqtt->publish($topic,$message,0);    //qos=2       
            echo '-topic-'.$topic;
            echo '-pub data-'.$message;
            
//            $mqtt->close();
        }

    }
    }
    else{
        echo 'Could not connect to broker';
    }
    
//while($mqtt->proc()){
//		
//}

$mqtt->close();    

?>
