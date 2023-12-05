<?php

ini_set('max_execution_time', 0);

require('phpMQTT.php');
// require('mqtt.php');

$fulDirpath  =  realpath( __DIR__);
$projPath    =  substr($fulDirpath, 0, strrpos( $fulDirpath, '/'));


require_once ($projPath.'/src/config/config.php');
require_once ($projPath.'/src/config/connect.php');
require_once ($projPath.'/src/utils/ConnectionManager.php');
require_once ($projPath.'/src/utils/GeneralMethod.php');


$db = new ConnectionManager();

while(true){

    	$aQuery = "SELECT *"
        	." FROM stream_dump"
        	." WHERE (action=50) AND (stream_data NOT LIKE '%CM') ORDER BY sd_id ASC LIMIT 1";
    	$db->query($aQuery);
    	$row=$db->single();

    	$data = $row[stream_data];
    	$dump_id = $row[sd_id];
    	$updated_on = $row[received_on];

	
	$dataArr = extractData($data);
		
	if(sizeof($dataArr)==8){
		// print_r("inside If"); exit();
		$gateway_id   = $dataArr[0];
            	$device_id    = $dataArr[1];
            	$device_type  = $dataArr[2];
            	$device_value = $dataArr[3];
		$dec_device_value = $dataArr[4];
				
		$hh = hexdec($dataArr[5]);
		$mm = hexdec($dataArr[6]);
		$ss = hexdec($dataArr[7]);


		if($hh<=9){
                 	$hh = sprintf("%02s", $hh);
                  	$hh = strtoupper($hh);
                } 

		if($mm<=9){
                   	$mm = sprintf("%02s", $mm);
                    	$mm = strtoupper($mm);
                } 

		if($ss<=9){
                   	$ss = sprintf("%02s", $ss);
                    	$ss = strtoupper($ss);
                } 
							
		$recv_time = $hh . ':' . $mm . ':' . $ss;

		if($hh > 23 || $mm > 59 || $ss > 59){	

			//send reset command		

			//Publish on mQTT directly -- topic 'settime'

			$GatewayMacID = getGatewayMacID($db, $gateway_id);
			$gateway_mac_id = $GatewayMacID[0]['gateway_mac_id'];

			
			$data_arr  =  array();
                    	$data_arr['gateway_mac_id']    =  $gateway_mac_id;
                    	$data_arr['gateway_id']    =  $gateway_id;
                    	$data_arr['device_id']     =  'FF';
                    	$data_arr['device_value']   =  '00FFFF';
                    	$data_arr['dynamic_id']     =  'FF';
                    	$data_arr['topic']         =  TOPIC_SET_TIME;
            
			$url        =  URL_END_POINT.'sensegiz-mqtt/publish.php';
                
			$response   =  $curl->postRequestData($url, $data_arr);		
			
			$eQuery = "UPDATE stream_dump"
				." SET action = 10"
				." WHERE sd_id=:dump_id";
			$db->query($eQuery);
			$db->bind(':dump_id', $dump_id);
			$db->execute();
			print_r($eQuery);
			continue;
		}
	
		$received_on = substr_replace($updated_on, $recv_time, 11, 8);
		//$c_hh = substr($updated_on, 11, 2);

		$diff = strtotime($received_on) - strtotime($updated_on);

		if($received_on > $updated_on && $diff > 60){
			$tquery = "SELECT ((received_on) - INTERVAL '1 DAY') AS c_date from stream_dump where sd_id = :dump_id";
			$db->query($tquery);
			$db->bind(':dump_id', $dump_id);
			$res=$db->resultset();
			
			$cdate = $res[0][c_date];
			$updated_on = substr_replace($cdate, $recv_time, 11, 8);

		}else{
			$updated_on = substr_replace($updated_on, $recv_time, 11, 8);
		}


		$isGwAuthenticated = checkAuthenticatedGateway($db,$gateway_id);

		if(!empty($isGwAuthenticated)){
			
                	$sQuery1 = " UPDATE user_gateways "
                        	. " SET data_received_on=:updated_on, updated_on=:updated_on, active='Y', status = 'Online'"
                        	. " WHERE gateway_id=:gateway_id AND is_deleted =0 ";
                	$db->query($sQuery1);                    
                	$db->bind(':gateway_id',$gateway_id);               
                	$db->bind(':updated_on', $updated_on);
                	$db->execute();	

    			$isAuthenticatedDevice   =   checkAuthenticatedDevice($db,$gateway_id,$device_id);
				
			if(!empty($isAuthenticatedDevice)){

				$isDevGwExists = isDeviceGatewayExists($db,$gateway_id,$device_id,$device_type);
				if(!empty($isDevGwExists)){
					$decVal = hexdec($device_value);

					$dec_device_value = hexdec($dec_device_value);

					if($device_type == '09')
  					{
 						if($decVal > 126)
 						{
    							$decVal = $decVal - 126;
   							$decVal = -$decVal;
 						}
 						if($decVal == 126)
 						{
    							$decVal = 0;
 						}
						$decVal = $decVal . '.' . $dec_device_value;
						//  update If we got positive 0.6
						$latest_temp_Data = getlatestTemperature($db,$gateway_id,$device_id); 
						$latest_temp_val = $latest_temp_Data['device_value'];

						if($decVal == "0.6" ){
							$decVal = $latest_temp_val;
						}
					}

					/* Humidity Value reduce for Rockman Customer */
					if(($device_type == '10') && ($isGwAuthenticated['user_id'] == '13')){
						$decVal = $decVal - 20;
					}

					$coin_type = $isAuthenticatedDevice['coin_type'];

					/* Reduce Humidity Value with Adjustments */
					if(($device_type == '10') && ($isGwAuthenticated['user_id'] != '13')){
						$latest_temp = getlatestTemperature($db,$gateway_id,$device_id); // ,$device_type
						$latest_temp2 = $latest_temp['device_value'];
						$latest_temp1 = intval($latest_temp2);

						
						if($coin_type == '1'){  /* If COIN is Pro */

							/* Humidity 60-70 */
							if((($latest_temp1 >= '20') && ($latest_temp1 <= '30')) && (($decVal >= '60') && ($decVal <= '70'))){  
									$decVal = $decVal - 25;
							}

							if((($latest_temp1 >= '31') && ($latest_temp1 <= '40')) && (($decVal >= '60') && ($decVal <= '70'))){  
									$decVal = $decVal - 20;
							}

							if((($latest_temp1 >= '41') && ($latest_temp1 <= '50')) && (($decVal >= '60') && ($decVal <= '70'))){  
									$decVal = $decVal - 10;
							}
							/* Humidity 60-70 */

							/* Humidity 70-80 */
							if((($latest_temp1 >= '20') && ($latest_temp1 <= '30')) && (($decVal >= '71') && ($decVal <= '80'))){  
									$decVal = $decVal - 30;
							}

							if((($latest_temp1 >= '31') && ($latest_temp1 <= '40')) && (($decVal >= '71') && ($decVal <= '80'))){  
									$decVal = $decVal - 25;
							}

							if((($latest_temp1 >= '41') && ($latest_temp1 <= '50')) && (($decVal >= '71') && ($decVal <= '80'))){  
									$decVal = $decVal - 20;
							}

							if((($latest_temp1 >= '51') && ($latest_temp1 <= '60')) && (($decVal >= '71') && ($decVal <= '80'))){  
									$decVal = $decVal - 10;
							}
							/* Humidity 70-80 */
						} 
						else if($coin_type == '2')  /* If COIN is Coin-Cell */
						{  
							/* Reduce Humidity Value */
							if((($latest_temp1 >= '25') && ($latest_temp1 <= '40')) && (($decVal >= '30') && ($decVal <= '49'))){  
									$decVal = $decVal - 10;
							}
						}
					}
					
					
					if(($device_type == '10') && ($decVal > 99 || $decVal < 1)){										

						$eQuery = "UPDATE stream_dump"
							." SET action = 11"
							." WHERE sd_id=:dump_id";
						$db->query($eQuery);
						$db->bind(':dump_id', $dump_id);
						$db->execute();

						continue;
					}

					$rowSettings  =  checkDeviceSettings($db,$gateway_id,$device_id,$device_type);

					if(!empty($rowSettings) && ($rowSettings['device_sensor']=='Temperature Stream' || $rowSettings['device_sensor']=='Humidity Stream')){						
						$sensorType = $rowSettings['device_sensor'];
						$low_threshold = $rowSettings['low_threshold'];
						$high_threshold = $rowSettings['high_threshold'];
														
						if($low_threshold != '' && $high_threshold != ''){
							$userTempUnit    =     @$userDetails['temp_unit'];

							if($userTempUnit == 'Fahrenheit' && $device_type == '09'){
								$decValF = ($decVal * 1.8) + 32;
							}else{
								$decValF = $decVal;
							}
							if($decValF < $low_threshold || $decValF > $high_threshold){

								$nickName = $isAuthenticatedDevice['nick_name'];
								$userId   = $isGwAuthenticated['user_id'] ;
								$userDetails   =     getUserDetails($db,$userId);
								$userMailArr   =       getUserEmailIds($db,$userId);
								$userEmail     =     @$userDetails['user_email'];


								if(!empty($userMailArr) && $rowSettings['email_alert']=='Y'){
									$emailsArray = $userMailArr;									

										$subject     =  'Alert! '.$sensorType.' value crossed threshold for: '.$nickName;
										$messageEmail = '<html>
										<head>
										<title>Alert</title>
										</head>
										<body>
											<h2>!! Alert !!</h2>
											<h3>Gateway - '.$gateway_id.'</h3>
											<h4>'.$nickName.' '.$sensorType.' has crossed the threshold and current value is '.$decValF.'</h4>
										</body>
										</html>';
									

									if($rowSettings['device_sensor']!='Others'){
										
										// sendMails($emailsArray,$subject,$messageEmail);
										$mail_limit_value = $userDetails['mail_restriction_limit'];
										$setMailInterval = $userDetails['mail_restriction_interval'];

										if($userDetails['mail_alert_restriction'] == 'Y')
										{
											$res_mailInterval = getTodayMailTriggerData($db, $userId, $gateway_id, $device_id, $sensorType);
											print_r($res_mailInterval);

											$mail_trigger_time = $res_mailInterval['mail_trigger_time'];
											$mail_trigger_time1 = date('H:i:s', $mail_trigger_time);
											
											$updatedTime = $res_mailInterval['updated_on'];
											
											$getTime = strtotime($updatedTime);
											// $getTime1 = date('H:i', $getTime);

											$getCurrentHM = strtotime($currentTime);
											// $getCurrentHM1 = date('H:i',$getCurrentHM);

											/*$minutediff = round((($getCurrentHM - $getTime))/60);
											echo "3.<pre>";
											print_r($minutediff);
											echo "</pre><br />";*/

											$date = date('Y-m-d H:i:s');
        
        									$minutediff = round((strtotime($date) - strtotime($updatedTime))/60);

											if($minutediff > $setMailInterval){

												$mail_alert_result = mail_trigger_alert($db, $userId, $gateway_id, $device_id, $sensorType, $mail_limit_value);

												if($mail_alert_result == '111'){  
													sendMails($emailsArray,$subject,$messageEmail);
												}
											}
										}
										else
										{
											sendMails($emailsArray,$subject,$messageEmail);
										}

									}
								}
							}																	                                      																	                                                        
						}			                       								
							
					}

				
					//Dew Point Temperature
					if($device_type=='09'){
						$dptQuery = "SELECT device_value"
							." FROM devices"
							." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type='10' AND is_deleted =0 ORDER BY d_id DESC LIMIT 1";
						$db->query($dptQuery);
						$db->bind(':gateway_id', $gateway_id);
						$db->bind(':device_id', $device_id);
						$dptres = $db->single();

						$rh_value = $dptres[device_value];

						if(!empty($rh_value)){

							$dptemp = $decVal - ((100 - $rh_value)/5);
		
							$dptuQuery = " UPDATE devices "
                                				. " SET device_value=:device_value, updated_on=:updated_on"
                                				. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type='26' AND is_deleted =0 ";

                        				$db->query($dptuQuery);                    
                        				$db->bind(':gateway_id',$gateway_id);
                        				$db->bind(':device_id',$device_id);
                        				$db->bind(':device_value',$dptemp);
                        				$db->bind(':updated_on', $updated_on);

							if($db->execute()){

								$dptiQuery = " INSERT INTO dewpointtemperature"
									. " (device_id, gateway_id, device_type, device_value, added_on)"
									. " VALUES (:device_id, :gateway_id, '26', :device_value, :updated_on)";
								$db->query($dptiQuery);
								$db->bind(':gateway_id',$gateway_id);	
								$db->bind(':device_id',$device_id); 									
                        					$db->bind(':device_value',$dptemp);
								$db->bind(':updated_on', $updated_on);

								$db->execute();	
							}
							
							$temp_value = $decVal;

							$C = 2.16679;

							if($temp_value < 50){
								$A = 6.116441;
								$m = 7.591386;
								$Tn = 240.7263;
							}else{
								$A = 6.004918;
								$m = 7.337936;
								$Tn = 229.3975;
							}

							$e = ($m * $temp_value)/($temp_value + $Tn);

							$Pws = $A * pow(10, $e);

							$Pw = ($decVal * $Pws);

							$ahumid = $C * $Pw/(273.15 + $temp_value);
		
							$ahuQuery = " UPDATE devices "
                               					. " SET device_value=:device_value, updated_on=:updated_on"
                               					. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type='27' AND is_deleted =0 ";

                       					$db->query($ahuQuery);                    
                       					$db->bind(':gateway_id',$gateway_id);
                       					$db->bind(':device_id',$device_id);
                       					$db->bind(':device_value',$ahumid);
                       					$db->bind(':updated_on', $updated_on);
							if($db->execute()){

								$ahiQuery = " INSERT INTO absolutehumidity"
									. " (device_id, gateway_id, device_type, device_value, added_on, humidity)"
									. " VALUES (:device_id, :gateway_id, '27', :device_value, :updated_on, :humidity)";
								$db->query($ahiQuery);
								$db->bind(':gateway_id',$gateway_id);	
								$db->bind(':device_id',$device_id); 									
                       						$db->bind(':device_value',$ahumid);
								$db->bind(':updated_on', $updated_on);
								$db->bind(':humidity', $rh_value);
								$db->execute();	
							}	
						}					
					}

					$sQuery3 = " UPDATE devices "
                                		. " SET device_value=:device_value, updated_on=:updated_on"
                                		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type=:device_type AND is_deleted =0 ";
                        		$db->query($sQuery3);                    
                        		$db->bind(':gateway_id',$gateway_id);
                        		$db->bind(':device_id',$device_id);
                        		$db->bind(':device_type',$device_type);
                        		$db->bind(':device_value',$decVal);
                        		$db->bind(':updated_on', $updated_on);
						
					if($db->execute()){
                                		$gQuery = "UPDATE gateway_devices"
                                        		." SET active='Y', status='Y', updated_on=:updated_on"
                                        		." WHERE gateway_id=:gateway_id AND device_id=:device_id";
                                		$db->query($gQuery);
                                		$db->bind(':gateway_id', $gateway_id);
                                		$db->bind(':device_id', $device_id);
                                		$db->bind(':updated_on', $updated_on);
								
                                		if($db->execute()){

							$sQuery41 = " INSERT INTO devices_stream "
								. " (device_id,gateway_id,device_type,device_value,updated_on)"
								. " VALUES (:device_id,:gateway_id,:device_type,:device_value,:updated_on)";
							$db->query($sQuery41);
							$db->bind(':gateway_id',$gateway_id);
							$db->bind(':device_id',$device_id);
							$db->bind(':device_type',$device_type);
							$db->bind(':device_value',$decVal);
							$db->bind(':updated_on', $updated_on);
							$db->execute();

							$eQuery = "UPDATE stream_dump"
								." SET action = 1"
								." WHERE sd_id=:dump_id";
							$db->query($eQuery);
							$db->bind(':dump_id', $dump_id);
							$db->execute();
						}
					}

					
					$pubTopic    =  "$gateway_id".'';
					$pubMessage  =  $gateway_id.','.$device_id.','.$device_type.','.$decVal.','.$isAuthenticatedDevice['nick_name'].','.$updated_on;
						
					if($pubTopic!='' && strlen($pubMessage)>12){
						publishGateway($pubTopic,$pubMessage);
					}												
				}					
				else
				{

					$eQuery = "UPDATE stream_dump"
					." SET action = 5"
					." WHERE sd_id=:dump_id";
					$db->query($eQuery);
					$db->bind(':dump_id', $dump_id);
					$db->execute();
				}
			}else
			{

				$eQuery = "UPDATE stream_dump"
					." SET action = 4"
					." WHERE sd_id=:dump_id";
				$db->query($eQuery);
				$db->bind(':dump_id', $dump_id);
				$db->execute();
			}
		}else
		{

			$eQuery = "UPDATE stream_dump"
				." SET action = 3"
				." WHERE sd_id=:dump_id";
			$db->query($eQuery);
			$db->bind(':dump_id', $dump_id);
			$db->execute();
		}
	}			
}

//publish to gateway topic
function publishGateway($pTopic,$tMessage){
    //Connect to MQTT server
    $clientId  = 'pubgateway_'.time(). rand(11111, 99999);

	
    $mqttPub   = new phpMQTT(SERVER_IP, 8883, $clientId, "sensegiz123", "sg12345"); 
    if ($mqttPub->connect()) {
        $mqttPub->publish($pTopic,$tMessage,0);
    }
    
    $mqttPub->close();   
}


function extractData($data){
    	$finalArr = [];

    	$arr1        = split_on($data, 12);
    
    	$finalArr[0] = $arr1[0];

    	//It will split 2 digits each from 6 digits i.e $arr2[0]=> 12, $arr2[1]=> 34, $arr2[3]=> 56,
    	$arr2        = str_split($arr1[1], 2);
    	$finalArr[1] = $arr2[0];
    	$finalArr[2] = $arr2[1];
    	$finalArr[3] = $arr2[2];
	$finalArr[4] = $arr2[3];
	$finalArr[5] = $arr2[4];
	$finalArr[6] = $arr2[5];
	$finalArr[7] = $arr2[6];
                
    	return $finalArr;
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

//split into 2 strings, first one will be based on length and second one will be remaining string
function split_on($string, $num) {
    $length = strlen($string);
    $output[0] = substr($string, 0, $num);
    $output[1] = substr($string, $num, $length);
    return $output;
}


function getGatewayMacID($db, $gatewayId){
            $sQuery = " SELECT gateway_mac_id"
                    . " FROM user_gateways"                    
                    . " WHERE gateway_id=:gateway_id AND is_deleted =0 AND is_blacklisted = 'N'";       

            $db->query($sQuery);  
            $db->bind(':gateway_id',$gatewayId);

            $row = $db->resultSet();
            
            return $row;
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
    elseif($device_type=='09'){
        $sensor_type = 'Temperature Stream';
    }
    elseif($device_type=='10'){
        $sensor_type = 'Humidity Stream';
    }
    else{
        $sensor_type = 'Others';
    }
    
        
        $sQuery = " SELECT *"
                . " FROM gateway_settings"                    
                . " WHERE gateway_id=:gateway_id AND sensor_type=:sensor_type AND is_deleted =0 ORDER BY s_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':sensor_type',$sensor_type);
        $db->bind(':gateway_id',$gateway_id);
        
        return  $row = $db->single();    
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
       $resArray = [];
       if(is_array($row) && !empty($row)){
           foreach ($row as $key => $value) {
               $resArray[] = $value['notification_email'];
           }
       }
        return $resArray;
}

//check Device Settings
function checkDeviceSettings($db, $gateway_id, $device_id, $device_type){
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
    elseif($device_type=='12' || $device_type=='14' || $device_type=='15'){
        $sensor_type = 'Accelerometer Stream';
    }     
    elseif($device_type=='09'){
        $sensor_type = 'Temperature Stream';
    } 
    elseif($device_type=='10'){
        $sensor_type = 'Humidity Stream';
    } 
    else{
        $sensor_type = 'Others';
    }
    
    
        $sQuery = " SELECT *"
                . " FROM device_settings"                    
                . " WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_sensor=:sensor_type AND is_deleted =0 ORDER BY ds_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':sensor_type',$sensor_type);
        $db->bind(':gateway_id',$gateway_id);
        $db->bind(':device_id',$device_id);
        
        return  $row = $db->single();    
}


function mail_trigger_alert($db, $userId, $gatewayId, $deviceId, $deviceType, $mail_limit_value){

	$todaysDate = date("Y-m-d");
	$currentTime = date("H:i:s");

	$sQuery = " SELECT COUNT(*)"
				. " FROM mail_alert_trigger "
				. " WHERE user_id=:user_id AND gateway_id=:gateway_id AND device_id=:device_id AND device_type=:device_type AND mail_trigger_date=:mail_trigger_date";

		
		$db->query($sQuery);

        $db->bind(':user_id',$userId);
        $db->bind(':gateway_id',$gatewayId);
        $db->bind(':device_id',$deviceId);
        $db->bind(':device_type',$deviceType);
        $db->bind(':mail_trigger_date',$todaysDate);

		$db->execute();
		
	$row = $db->resultSet(); 
	$mail_count = $row[0]['count'];

	if($mail_count >= $mail_limit_value)
	{
		// Don't send mail alert
		$result_status = '000';
		return $result_status;
	}
	else
	{
		// Insert into and trigger the mail function
		$query = "INSERT INTO mail_alert_trigger(user_id, gateway_id, device_id, device_type, mail_trigger_date, mail_trigger_time) "
                       			. "VALUES (:user_id, :gateway_id, :device_id, :device_type, :mail_trigger_date, :mail_trigger_time)";
            
			$db->query($query);
    		$db->bind(':user_id',$userId);
	        $db->bind(':gateway_id',$gatewayId);
	        $db->bind(':device_id',$deviceId);
	        $db->bind(':device_type',$deviceType);
	        $db->bind(':mail_trigger_date',$todaysDate);
	        $db->bind(':mail_trigger_time',$currentTime);
			$db->execute();

			$return_status = '111';
			return $return_status;
	}

	// return;
}

// Function to get all Mail Trigger Table data
function getTodayMailTriggerData($db, $userId, $gatewayId, $deviceId, $deviceType){

	$todaysDate = date("Y-m-d");

	$sQuery = " SELECT *"
				. " FROM mail_alert_trigger "
				. " WHERE user_id=:user_id AND gateway_id=:gateway_id AND device_id=:device_id AND device_type=:device_type AND mail_trigger_date=:mail_trigger_date ORDER BY mat_id DESC LIMIT 1";
		
		$db->query($sQuery);

        $db->bind(':user_id',$userId);
        $db->bind(':gateway_id',$gatewayId);
        $db->bind(':device_id',$deviceId);
        $db->bind(':device_type',$deviceType);
        $db->bind(':mail_trigger_date',$todaysDate);

        // $db->execute();
		
	$row = $db->single(); 
	return $row;
}

function sendMails($emailsArray,$subject,$message){
		require("/var/www/html/sensegiz-dev/library/sendgrid-php/sendgrid-php.php");

                            $mailidslength=count($emailsArray);

                            	$message = urlencode($message);
								$subject = urlencode($subject);

							for($x=0;$x<$mailidslength;$x++){
								
								$ch=curl_init();

								curl_setopt($ch,CURLOPT_URL,"http://".SERVER_IP.":3000/ses/?message=".$message."&to=".$emailsArray[$x]."&subject=".$subject."");
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
								$output =curl_exec($ch);
								curl_close($ch);
                            }
       
  
}


function getlatestTemperature($db,$gateway_id,$device_id){ //,$device_type

	$tempQuery = " SELECT *"
				. " FROM devices_stream "
				. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type='09' ORDER BY d_id DESC LIMIT 1";
		
		$db->query($tempQuery);

        $db->bind(':gateway_id',$gateway_id);
        $db->bind(':device_id',$device_id);
        // $db->bind(':device_type',$deviceType);
        // $db->execute();
		
	$row = $db->single(); 
	return $row;
}


?>