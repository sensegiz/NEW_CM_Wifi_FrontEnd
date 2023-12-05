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
require_once ($projPath.'/src/utils/CurlRequest.php');

//Twilio
 require ($projPath.'/library/twilio-php-master/Twilio/autoload.php');
 use Twilio\Rest\Client;
//Sendgrid
 require ($projPath.'/library/sendgrid-php/vendor/autoload.php');
 
 //Connect to MQTT server
$mqtt = new phpMQTT(SERVER_IP, 1883, "phpMQTT Sub Example", $username, $password); 

if(!$mqtt->connect()){
	exit(1);
}
else{
    echo "Connected to 1883";
}
//end of connection
// //save gateway and devices
$topics['gatewaydata'] = array("qos"=>0, "function"=>"save_gateway_data");


//
$topics['pingalert'] = array("qos"=>0, "function"=>"set_gateway_status");

// Current gateway firmware version
$topics['gatewayversion'] = array("qos"=>0, "function"=>"set_gateway_version");


//Get Threshold From Gateway
$topics['updategetcurrentvalue'] = array("qos"=>0, "function"=>"get_current_value");

$topics['registeredcoin'] = array("qos"=>0, "function"=>"validate_registered_coin");//Pending

$topics['registereddeviceid'] = array("qos"=>0, "function"=>"registered_device_id");

  

$mqtt->subscribe($topics,0);

while($mqtt->proc()){
		
}

$mqtt->close();



//------** 1 **-------
// save_gateway_data2();
function save_gateway_data($topic,$data){

	$db = new ConnectionManager();
	$curl     = new CurlRequest();

    	if(!empty($data) && (strlen($data)==24 || strlen($data)==26)){		

		$type = substr($data, 14, 2);

		if(strlen($data)==24){

			$zQuery = "INSERT INTO data_dump"
				." (data)"
				." VALUES (:data)";
			$db->query($zQuery);
			$db->bind(':data', $data);
			$db->execute();

		}elseif($type == '12' || $type == '14' || $type == '15'){

			$sQuery41 = " INSERT INTO accstream_dump "
				. " (accstream_data, action)"
				. " VALUES (:data, 0)";
		
			$db->query($sQuery41);
			$db->bind(':data',$data);	
			$db->execute();


		}elseif($type == '09' || $type == '10'){

			$sQuery41 = " INSERT INTO stream_dump "
				. " (stream_data, action)"
				. " VALUES (:data, 0)";
		
			$db->query($sQuery41);
			$db->bind(':data',$data);	
			$db->execute();

		}else{	

		
			$zQuery = "INSERT INTO data_dump"
				." (data)"
				." VALUES (:data)";
			$db->query($zQuery);
			$db->bind(':data', $data);
			$db->execute();

		}
	}

	
	
}



//------** 1 **-------
// set_gateway_status();
function set_gateway_status($topic,$data){

    	if(!empty($data) && strlen($data)==24){		

		$db = new ConnectionManager();

		$sQuery41 = " INSERT INTO ping_alert "
				. " (alert_data, action)"
				. " VALUES (:data, 0)";
		
		$db->query($sQuery41);
		$db->bind(':data',$data);	
		$db->execute();	

	}

}


function set_gateway_version($topic,$data){

    	if(!empty($data)){		

		$db = new ConnectionManager();

		$arr = split_on($data, 12);
		$gateway_id = $arr[0];

		$ver = split_on($data, 13);
		$vers = $ver[1];
	
		$uQuery = " UPDATE user_gateways "
			. " SET gateway_version = :version, updated_on = DATE_TRUNC('second', NOW())"
			. " WHERE gateway_id=:gateway_id AND is_deleted =0 ";

		$db->query($uQuery);                    
		$db->bind(':gateway_id',$gateway_id);   
		$db->bind(':version',$vers);               
		$db->execute();

	}

}



//--------------------

//------** 2 **-------
//get_current_value
function get_current_value($topic,$data){
        if(!empty($data)){
                echo "-Get TH Recieved: ".date('d-m-Y h:i:s')."\nData:$data\n";
               
                $db             = new ConnectionManager();
    
                //check string/packet length is 18 chars=> 
                //new format=> 12 digits gateway_id, 2 digits device/sensor_id, 2 digits device_type, 2 digits threshold value     
                if(strlen($data)==18){

                     $dataArr = extractData($data);

                    if(sizeof($dataArr)==4){

                            $gateway_id        = $dataArr[0];
                            $device_id         = $dataArr[1];
                            $device_type       = $dataArr[2];
                            $get_current_value = $dataArr[3];
                            
			//update threshold value
                        $sQuery3 = " UPDATE devices "
                               . " SET get_current_value=:get_current_value,updated_on=DATE_TRUNC('second', NOW())"
                               . " WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type=:device_type AND is_deleted =0 ";
                        $db->query($sQuery3);                    
                        $db->bind(':gateway_id',$gateway_id);
                        $db->bind(':device_id',$device_id);
                        $db->bind(':device_type',$device_type);
                        $db->bind(':get_current_value',$get_current_value);
                        if($db->execute()){                   

                            print_r('-Get Curr Val Updated-'); 
                        }

			$sQuery31 = " UPDATE devices_stream "
                                    . " SET get_current_value=:get_current_value,updated_on=DATE_TRUNC('second', NOW())"
                                    . " WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type=:device_type AND is_deleted =0 ";
                        $db->query($sQuery31);                    
                        $db->bind(':gateway_id',$gateway_id);
                        $db->bind(':device_id',$device_id);
                        $db->bind(':device_type',$device_type);
                        $db->bind(':get_current_value',$get_current_value);
                        $db->execute();

                    }
                }
                
                
    }
}
//--------------------

//-------** 3 * ----
//validate_registered_coin
function validate_registered_coin($topic,$data){
    if(!empty($data)){
        $deviceMac      =   $data;
                
        $db             = new ConnectionManager();
        
        $isDeviceExists = isDeviceExists($db,$deviceMac);

//$fulDirpath  =  realpath( __DIR__);//  /var/www/devsensegiz/public_html/sensegiz-dev/sensegiz-mqtt
//$projPath    =  substr($fulDirpath, 0, strrpos( $fulDirpath, '/'));        
        $url      = 'https://'.SERVER_IP.'/sensegiz-dev/sensegiz-mqtt/publish.php';
//        print_r($url);
        $curl     = new CurlRequest();
        
        $dataArray = [];
        if(!empty($isDeviceExists)){
            
            $dataArray['device_mac_address']     =  $deviceMac;
            $dataArray['topic']                  =  'validcoin';

//            $response   =  $curl->postRequestData($url, $data_arr);

            
            print_r('-valid coin-');
        } 
        else{
            $dataArray['device_mac_address']     =  $deviceMac;
            $dataArray['topic']                  =  'unregisteredcoin';

            $response   =  $curl->postRequestData($url, $data_arr);          
            
            print_r('-unregistered coin-');
        }
        
        //
        if(!empty($dataArray)){
           $postData = http_build_query($dataArray, '', '&'); 
           $ch = curl_init();  
           curl_setopt($ch,CURLOPT_URL,$url);
           curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
           
           curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3600);
           
           curl_setopt($ch,CURLOPT_HEADER, false); 
           curl_setopt($ch, CURLOPT_POST, count($postData));               
           curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    

           $output=curl_exec($ch);

           curl_close($ch);
        }
        //
        
        
    }
}
//

//------** 4 **---------
function registered_device_id($topic,$data){
    
    if(!empty($data)  && (strlen($data)==14)){
        print_r($data);
        $arr        = split_on($data, 12);
        $deviceMac  =   $arr[0];
        $deviceId   =   $arr[1];
        
        
        if($deviceMac!='' && $deviceId!=''){
            $db             = new ConnectionManager();

            $isDeviceExists    = isDeviceExists($db,$deviceMac);

            if(!empty($isDeviceExists)){

			$gatewayId = $isDeviceExists['gateway_id'];
			$deviceSensorExist = isDeviceSensorExists($db, $gatewayId, $deviceId);
                       //update device id based on device mac address                                                     
                        $sQuery1 = " UPDATE gateway_devices "
                                . " SET device_id=:device_id, updated_on=DATE_TRUNC('second', NOW())"
                                . " WHERE device_mac_address=:device_mac_address AND is_deleted =0 ";
                        $db->query($sQuery1);                    
                        $db->bind(':device_id',$deviceId);
                        $db->bind(':device_mac_address',$deviceMac);
			if($db->execute()){

				if(empty($deviceSensorExist)){
					print_r('-entering-sensor-adder');
					//insert all sensors for coin in devices
					$prefix = 0;
					$i = 1;
					for($i=1; $i<32; $i++){
						if($i>=9){				
							if($i==9){
								$deviceType = '0'.$i;
							}else{				
								$deviceType = $i;
							}

							
							print_r($deviceType);
							$cQuery = "INSERT INTO devices"
								 ." (device_id, gateway_id, device_type, active)"
								 ." VALUES(:device_id, :gateway_id, :device_type, 'N')";
							$db->query($cQuery);
							$db->bind(':device_id', $deviceId);
							$db->bind(':gateway_id', $gatewayId);
							$db->bind(':device_type', $deviceType);
							$db->execute();
							print_r('-device_type-'.$deviceType.'-inserted-');
						} else {
							$deviceType = '0'.$i;
							print_r($deviceType);

							if($deviceType == '01'){
								$threshold_value = '09';
							}

							if($deviceType == '02'){
								$threshold_value = '1C';
							}

							if($deviceType == '03'){
								$threshold_value = '01';
							}
							if($deviceType == '04'){
								$threshold_value = '1E';
							}

							if($deviceType == '05'){
								$threshold_value = '05';
							}

							if($deviceType == '06'){
								$threshold_value = '46';
							}

							if($deviceType == '07'){
								$threshold_value = '01';
							}

							if($deviceType == '08'){
								$threshold_value = '5A';
							}
							$cQuery = "INSERT INTO devices"
								 ." (device_id, gateway_id, device_type, threshold_value, active)"
								 ." VALUES(:device_id, :gateway_id, :device_type, :threshold_value, 'N')";
							$db->query($cQuery);
							$db->bind(':device_id', $deviceId);
							$db->bind(':gateway_id', $gatewayId);
							$db->bind(':device_type', $deviceType);
							$db->bind(':threshold_value', $threshold_value);
							$db->execute();
														

							print_r('-device_type-'.$deviceType.'-inserted-');
						  }
					}

					$device_sensors = array('Accelerometer', 'Gyroscope', 'Temperature', 'Humidity', 'Temperature Stream', 'Humidity Stream', 'Accelerometer Stream');

					foreach ($device_sensors as $value) {
	
					
						$dsQuery = "INSERT INTO device_settings"
								 ." (gateway_id, device_id, device_sensor)"
								 ." VALUES(:gateway_id, :device_id, :device_sen)";
							$db->query($dsQuery);
							$db->bind(':gateway_id', $gatewayId);
							$db->bind(':device_id', $deviceId);
							$db->bind(':device_sen', $value);							
							
							$db->execute();
					}
	
					$Velocity_types = array('20','21','22');
					
          				foreach ($Velocity_types as $types) { 
						$sQuery43 = " INSERT INTO acceleration_types"
							. " (device_id,gateway_id,device_type, acceleration_value, acceleration_type)"
							. " VALUES (:device_id,:gateway_id,:device_type,'0','velocity')";
							$db->query($sQuery43);
							$db->bind(':gateway_id', $gatewayId);
							$db->bind(':device_id', $deviceId);
              						$db->bind(':device_type', $types);
              						$db->execute();
         				}
	
					$displacement_types = array('23','24','25');

          				foreach ($displacement_types as $types) { 
						$sQuery44 = " INSERT INTO acceleration_types"
							. " (device_id,gateway_id,device_type, acceleration_value, acceleration_type)"
							. " VALUES (:device_id,:gateway_id,:device_type,'0','Displacement')";
							$db->query($sQuery44);
							$db->bind(':gateway_id',$gatewayId);
							$db->bind(':device_id',$deviceId);
              						$db->bind(':device_type', $types);
              						$db->execute();
         				}
	

				} else {print_r('coin added');}
			}

                        print_r('-device id UPDATED-');
            } 
        
        }
    }            
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

//check device sensors exist or not
function isDeviceSensorExists($db, $gatewayId, $deviceId){
	print_r('-'.$deviceId.'-');
	print_r('-'.$gatewayId.'-');
	$aQuery = "SELECT *"
		 ." FROM devices"
                 ." WHERE device_id=:device_id AND gateway_id=:gateway_id  AND is_deleted =0";

	$db->query($aQuery);
	$db->bind(':device_id', $deviceId);
	$db->bind(':gateway_id', $gatewayId);
	return $row = $db->resultSet();
}

//publish to gateway topic
function publishGateway($pTopic,$tMessage){
    //Connect to MQTT server
    $clientId  = 'pubgateway_'.time(). rand(11111, 99999);
    $mqttPub   = new phpMQTT(SERVER_IP, 1883, $clientId); 
    if ($mqttPub->connect()) {
        $mqttPub->publish($pTopic,$tMessage,0);
        print_r('--published--'.$tMessage.'--');
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