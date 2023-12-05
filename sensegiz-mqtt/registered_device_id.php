<?php
ini_set('max_execution_time', 0);
require("phpMQTT.php");


if(!$mqtt->connect()){
	exit(1);
}

$fulDirpath  =  realpath( __DIR__);//  /var/www/devsensegiz/public_html/sensegiz-dev/sensegiz-mqtt
$projPath    =  substr($fulDirpath, 0, strrpos( $fulDirpath, '/'));//  /var/www/devsensegiz/public_html/sensegiz-dev
require_once ($projPath.'/src/config/config.php');
require_once ($projPath.'/src/config/connect.php');
require_once ($projPath.'/src/utils/ConnectionManager.php');

//Connect to MQTT server
$mqtt = new phpMQTT(SERVER_IP, 1883, "sensegiz_subscriber_01"); 

// //save gateway and devices
$topics['gatewaydata'] = array("qos"=>0, "function"=>"save_gateway_data");

//Get Threshold From Gateway
$topics['updategetcurrentvalue'] = array("qos"=>0, "function"=>"get_current_value");

$topics['registeredcoin'] = array("qos"=>0, "function"=>"validate_registered_coin");//Pending

$topics['registereddeviceid'] = array("qos"=>0, "function"=>"registered_device_id");

$mqtt->subscribe($topics,0);

while($mqtt->proc()){

}

$mqtt->close();
//------** 4 **---------
function registered_device_id($topic,$data){
    
    if(!empty($data)){
        print_r($data);
        $arr        = split_on($data, 12);
        $deviceMac  =   $arr[0];
        $deviceId   =   $arr[1];
        
//        console.log($data);
//        console.log($deviceMac);
//        console.log($deviceId);
        
        if($deviceMac!='' && $deviceId!=''){
            $db             = new ConnectionManager();

            $isDeviceExists    = isDeviceExists($db,$deviceMac);

            if(!empty($isDeviceExists)){

			print_r($isDeviceExists);
			$gatewayId = $isDeviceExists['gateway_id'];
			$deviceSensorExist = isDeviceSensorExists($db, $gatewayId, $deviceId);
                       //update device id based on device mac address                                                     
                        $sQuery1 = " UPDATE gateway_devices "
                                . " SET device_id=:device_id, updated_on=now()"
                                . " WHERE device_mac_address=:device_mac_address AND is_deleted =0 ";
                        $db->query($sQuery1);                    
                        $db->bind(':device_id',$deviceId);
                        $db->bind(':device_mac_address',$deviceMac);
			if($db->execute()){
				print_r($deviceSensorExist);
				if(empty($deviceSensorExist)){
					print_r('-entering-sensor-adder');
					//insert all sensors for coin in devices
					$prefix = 0;
					$i = 1;
					for($i=1; $i<16; $i++){
						if($i>=9){
							if($i==9){
								$deviceType = '0'.$i;
							}else{				
								$deviceType = $i;
							}
							print_r($deviceType);
							$cQuery = "INSERT INTO devices"
								 ." (device_id, gateway_id, device_type, added_on, updated_on, active)"
								 ." VALUES(:device_id, :gateway_id, :device_type, NOW(), NOW(), 'N')";
							$db->query($cQuery);
							$db->bind(':device_id', $deviceId);
							$db->bind(':gateway_id', $gatewayId);
							$db->bind(':device_type', $deviceType);
							$db->execute();
							print_r('-device_type-'.$deviceType.'-inserted-');
						} else {
							$deviceType = '0'.$i;
							print_r($deviceType);

							if($deviceType == 01){
								$threshold_value = '09';
							}

							if($deviceType == 02){
								$threshold_value = '1C';
							}

							if($deviceType == 03){
								$threshold_value = '0A';
							}
							if($deviceType == 04){
								$threshold_value = '12C';
							}

							if($deviceType == 05){
								$threshold_value = '05';
							}

							if($deviceType == 06){
								$threshold_value = '46';
							}

							if($deviceType == 07){
								$threshold_value = '01';
							}

							if($deviceType == 08){
								$threshold_value = '5A';
							}
							$cQuery = "INSERT INTO devices"
								 ." (device_id, gateway_id, device_type, threshold_value, added_on, updated_on, active)"
								 ." VALUES(:device_id, :gateway_id, :device_type, :threshold_value, NOW(), NOW(), 'N')";
							$db->query($cQuery);
							$db->bind(':device_id', $deviceId);
							$db->bind(':gateway_id', $gatewayId);
							$db->bind(':device_type', $deviceType);
							$db->bind(':threshold_value', $threshold_value);
							$db->execute();
							print_r('-device_type-'.$deviceType.'-inserted-');
						  }
					}
					$device_sensors = array("Accelerometer", "Gyroscope", "Temperature", "Humidity", "Temperature Stream", "Humidity Stream");

					foreach ($device_sensors as $value) {
						$dsQuery = "INSERT INTO device_settings"
								 ." (gateway_id, device_id, device_sensor, sensor_active, added_on, updated_on)"
								 ." VALUES(:gateway_id, :device_id, $value, 'N', NOW(), NOW())";
							$db->query($dsQuery);
							$db->bind(':gateway_id', $gatewayId);
							$db->bind(':device_id', $deviceId);							
							
							$db->execute();
					}

				} else {print_r('coin added');}
			}

                        print_r('-device id UPDATED-');
            } 
        
        }
    }            
}

//Utility functions

//check device sensors exist or not
function isDeviceSensorExists($db, $gatewayId, $deviceId){
    print_r('-'.$deviceId.'-');
    print_r('-'.$gatewayId.'-');
    $aQuery = "SELECT *"
         ." FROM devices"
                 ." WHERE device_id=:device_id AND gateway_id=:gateway_id";

    $db->query($aQuery);
    $db->bind(':device_id', $deviceId);
    $db->bind(':gateway_id', $gatewayId);
    print_r($row);
    return $row = $db->resultSet();
}

//It will parse the 18 digits into 12,2,2,2 in a array
function extractData($data){
    $finalArr = [];
    //Ex: abcdefghijkl123456
    //First it will get 12 digits in first element and remaining 6 in second element. i.e arr1[0]=>abcdefghijkl & arr1[1]=> 123456
    $arr1        = split_on($data, 12);
    
    $finalArr[0] = $arr1[0];
    //It will split 2 digits each from 6 digits i.e $arr2[0]=> 12, $arr2[1]=> 34, $arr2[3]=> 56,
    $arr2        = str_split($arr1[1], 2);
    $finalArr[1] = $arr2[0];
    $finalArr[2] = $arr2[1];
    $finalArr[3] = $arr2[2];
                
    return $finalArr;
}

//split into 2 strings, first one will be based on length and second one will be remaining string
function split_on($string, $num) {
    $length = strlen($string);
    $output[0] = substr($string, 0, $num);
    $output[1] = substr($string, $num, $length);
    return $output;
}


//check Device & Gateway already exists or not in devices table. if exists returns device-gateway details else empty array
function isDeviceGatewayExists($db,$gateway_id,$device_id,$device_type){
        $sQuery = " SELECT *"
                . " FROM devices"                    
                . " WHERE device_id=:device_id AND device_type=:device_type AND gateway_id=:gateway_id AND is_deleted =0 ORDER BY d_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':device_id',$device_id);
        $db->bind(':device_type',$device_type);
        $db->bind(':gateway_id',$gateway_id);
        
        return  $row = $db->single();    
}

//Check gateway is added by User or not
function checkAuthenticatedGateway($db,$gateway_id){
        $sQuery = " SELECT *"
                . " FROM user_gateways"                    
                . " WHERE gateway_id=:gateway_id AND is_blacklisted='N' AND is_deleted =0 ORDER BY ug_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':gateway_id',$gateway_id);
        $row = $db->single();
        
        return  $row = $db->single();     
}

//Check device is added by user or not
function checkAuthenticatedDevice($db,$gateway_id,$device_id){
        $sQuery = " SELECT *"
                . " FROM gateway_devices"                    
                . " WHERE device_id=:device_id AND gateway_id=:gateway_id AND is_blacklisted='N' AND is_deleted =0 ORDER BY gd_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':device_id',$device_id);
        $db->bind(':gateway_id',$gateway_id);
        
        return  $row = $db->single();     
    print_r($row);
}

//Check device is exists or not
function isDeviceExists($db,$deviceMac){
        $sQuery = " SELECT *"
                . " FROM gateway_devices"
                . " WHERE device_mac_address=:device_mac_address AND is_blacklisted='N' AND is_deleted =0 ORDER BY gd_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':device_mac_address',$deviceMac);
        $row = $db->single();
        
        return  $row = $db->single();     
}

//publish to gateway topic
function publishGateway($pTopic,$tMessage){
    //Connect to MQTT server
    $clientId  = 'pubgateway_'.time(). rand(11111, 99999);
    $mqttPub   = new phpMQTT(SERVER_IP, 1883, $clientId); 
    if ($mqttPub->connect()) {
        $mqttPub->publish($pTopic,$tMessage,0);
    }
    
    $mqttPub->close();   
}

//check Gateway Settings
function checkGatewaySettings($db,$gateway_id,$device_type){
    $sensor_type = '';
    if($device_type=='01' || $device_type=='02'){
        $sensor_type = 'Accelerometer';
    }
    elseif($device_type=='03' || $device_type=='04'){
        $sensor_type = 'Gyrosensor';
    } 
    elseif($device_type=='05' || $device_type=='06'){
        $sensor_type = 'Temperature';
    } 
    elseif($device_type=='07' || $device_type=='08'){
        $sensor_type = 'Humidity';
    } 
    else{
        $sensor_type = 'Others';
    }
    
    
    //var arrSenTypes = ['Accelerometer', 'Gyrosensor', 'Temperature', 'Humidity', 'Others'];
        $sQuery = " SELECT *"
                . " FROM gateway_settings"                    
                . " WHERE gateway_id=:gateway_id AND sensor_type=:sensor_type AND is_deleted =0 ORDER BY s_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':sensor_type',$sensor_type);
        $db->bind(':gateway_id',$gateway_id);
        
        return  $row = $db->single();    
}
    /*
      Function            : sendMails($emails,$subject,$message)
      Brief               : Function used to display the result in json format.
      Details             : Function used to display the result in json format.
      Input param         : $emails,$subject,$message
      Input/output param  : Nil
      Return              : bool
     */
    function sendMails($emailsArray,$subject,$message){
       
//                        require 'library/sendgrid-php/vendor/autoload.php';

                        $sendgrid = new SendGrid(SENDGRID_APIKEY);
                        $email    = new SendGrid\Email();

                        //To send to multiple emails can use ->setTos(array of emails)
                         //        ->addTo("test@sendgrid.com")
                        $email->setSmtpapiTos($emailsArray)
                              ->setFrom("info@sensegiz.com")
                              ->setFromName('SenseGiz')
                              ->setSubject($subject)
                              ->setHtml($message);

                        $sendgrid->send($email);                
                //end of sendgrid
                print_r('-email sent-'); 
//              print_r('---email sent--msg--'.$message.'--');  
    }
    
    function sendSMS($userPhoneArr,$messageSms){        

    //24x7 sms gateway details
        $apiKey         = 'bglCSP2rzgR';
    $senderId    = 'SENSGZ';
    $serviceName = 'TEMPLATE_BASED';

    //twilio sms gateway details
    $sid    = 'ACafeb140bfa630fa5dbcbe99b2ef1f0eb';
        $token  = '9c04ba3e3952db12775eed8d76ae14f7';
        $client = new Client($sid, $token);
        
        foreach ($userPhoneArr as $key => $userPhone) {

        //strip + from country code as per requirement of 24x7 sms gateway 
        print_r('-original number-'.$userPhone);

        $phone = ltrim($userPhone, '+');
        print_r('-number wtihout plus-'.$phone);
    
        $country = str_split($phone, '2');
        print_r('-country code'.$country);
        if($country[0] == '+91'){
                $ch=curl_init();
            $message = urlencode($messageSms);
    
            $url = 'https://smsapi.24x7sms.com/api_2.0/SendSMS.aspx?APIKEY='.$apiKey.'&MobileNo='.$userPhone.'&SenderID='.$senderId.'&Message='.$message.'&ServiceName='.$serviceName.'';
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            if($output =curl_exec($ch)) {
                print_r('-24x7-sms-sent-');
            }
            curl_close($ch); 
            print_r('--log_start---');
            print_r($output);
            print_r('---log_end--');
            } else {
            print_r($userPhone);                
            $phone = '+'.'$phone';
            print_r($userPhone);
            $client->messages->create(
                    $userPhone,    
                    array(
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => '+12158393224',
                    // the body of the text message you'd like to send
                    'body' => $messageSms
                    )
                );
            print_r('-twilio-sms sent-');
//            print_r('-sms sent to - '+$userPhone+' --');
        }
    }
    }    
 

    //Get User Details
    function getUserDetails($db,$userId){
                
        $sQuery = " SELECT *"
                . " FROM users"                    
                . " WHERE user_id=:user_id AND is_deleted =0";       
         
        $db->query($sQuery);
        $db->bind(':user_id',$userId);
        
        return  $row = $db->single();            
    }
    
    //Get User Email Ids to send Notifications
    function getUserEmailIds($db,$userId){
                
        $sQuery = " SELECT notification_email"
                . " FROM notification_emails"                    
                . " WHERE user_id=:user_id AND is_deleted =0";       
         
        $db->query($sQuery);
        $db->bind(':user_id',$userId);
        
       $row = $db->resultSet(); 
//       print_r($row);
       $resArray = [];
       if(is_array($row) && !empty($row)){
           foreach ($row as $key => $value) {
               $resArray[] = $value['notification_email'];
           }
       }
        return $resArray;
    } 
    
    //Get User Mobile numbers to send Notifications
    function getUserPhoneNumbers($db,$userId){
                
        $sQuery = " SELECT notification_phone"
                . " FROM notification_phone"                    
                . " WHERE user_id=:user_id AND is_deleted =0";       
         
        $db->query($sQuery);
        $db->bind(':user_id',$userId);
        
       $row = $db->resultSet(); 
//       print_r($row);
       
       $resArray = [];
       if(is_array($row) && !empty($row)){
           foreach ($row as $key => $value) {
               $resArray[] = $value['notification_phone'];
           }
       }
        return $resArray;
    }

?>