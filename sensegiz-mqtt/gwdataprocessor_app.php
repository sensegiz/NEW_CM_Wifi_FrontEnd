<?php
/* 
Flag Codes:== 
	1) 5=>check Device & Gateway already exists or not in devices table; 
	2) 4=Check Gateway and Device_ID exists or not;
*/
//process gatewaydata stored in the data_dump

//fetch unprocessed data from the data_dump and process it.
//upon processing, set the action flag in data_dump to true

require('phpMQTT.php');
// require('mqtt.php');


$fulDirpath  =  realpath( __DIR__);//  /var/www/devsensegiz/public_html/sensegiz-dev/sensegiz-mqtt
$projPath    =  substr($fulDirpath, 0, strrpos( $fulDirpath, '/'));//  /var/www/devsensegiz/public_html/sensegiz-dev

require_once ($projPath.'/src/config/config.php');
require_once ($projPath.'/src/config/connect.php');
require_once ($projPath.'/src/config/jsontags.php');
require_once ($projPath.'/src/utils/ConnectionManager.php');
require_once ($projPath.'/src/utils/GeneralMethod.php');
require_once ($projPath.'/src/utils/CurlRequest.php');

//Twilio
 require ($projPath.'/library/twilio-php-master/Twilio/autoload.php');
 use Twilio\Rest\Client;
//Sendgrid
//require ($projPath.'/library/sendgrid-php/vendor/autoload.php');


$db = new ConnectionManager();
$curl = new CurlRequest();

while(true){
	$aQuery = "SELECT *"
        ." FROM data_dump"
        ." WHERE (action=0) AND (data LIKE '%CM') ORDER BY id ASC LIMIT 1";
    $db->query($aQuery);
    $row=$db->single();

    $data = $row[data];
    $dump_id = $row[id];
    $updated_on = $row[received_on];

	
	if(!empty($data) && (strlen($data)==36)){

		$getTime = substr($data, 12, 14);
		$ack = substr($data, 14, 2);
		$datavalue = substr($data, 14, 2);

		if($ack == '27'){
			//Logic
			$ackarr = extractData($data);
				
			$gateway_id   = $ackarr[0];
			$device_id    = $ackarr[1];
			$device_type  = $ackarr[3]; //Sensor_type
			if($device_type == '09' || $device_type == '10'){
				$set_format = $ackarr[4]; // Sensor_typ - 09 & 10: HH/MM/SS; 
				$device_value = $ackarr[5]; // Sensor_typ - 09-10: Value
			}else{
				$device_value = $ackarr[4]; // 09 & 10: HH/MM/SS; 01-08: Value
			}
			
			
			$user_id = ltrim($gateway_id, "0");


				if($device_type == '01' || $device_type == '02' || $device_type == '03' || $device_type == '04' || $device_type == '05' || $device_type == '06' || $device_type == '07' || $device_type == '08'){					
							
					$uQuery1 = " UPDATE devices SET threshold_value=:threshold"
							." WHERE gateway_id=:gateway_id AND device_id =:device_id AND device_type = :device_type AND is_deleted =0 ";
					$db->query($uQuery1);	
					$db->bind(':gateway_id',$gateway_id);
					$db->bind(':device_id',$device_id);
					$db->bind(':device_type',$device_type);	
					$db->bind(':threshold', $device_value);
					$db->execute();
					
				}


				if($device_type == '09' || $device_type == '10'){

											
					$uQuery1 = " UPDATE devices SET format=:format, rate_value=:rate_value"
							." WHERE gateway_id=:gateway_id AND device_id =:device_id AND device_type = :device_type AND is_deleted =0 ";
					$db->query($uQuery1);	
					$db->bind(':gateway_id',$gateway_id);
					$db->bind(':device_id',$device_id);
					$db->bind(':device_type',$device_type);	
					$db->bind(':format', $set_format);
					$db->bind(':rate_value', $device_value);
					$db->execute();
					
				}

				if($req_type == 'EnD' && $rate_value == $device_value){
					
					if($rate_value == '45')
					{						
						$sensor_active = 'Y';
					}else{					
						$sensor_active = 'N';
					}

					if($device_type == '41'){						
						$sensor_type = 'Accelerometer';
					}
					elseif($device_type == '47'){						
						$sensor_type = 'Gyroscope';
					}
					elseif($device_type == '54'){						
						$sensor_type = 'Temperature';
					}
					elseif($device_type == '48'){						
						$sensor_type = 'Humidity';
					}
					elseif($device_type == '74'){						
						$sensor_type = 'Temperature Stream';
					}
					elseif($device_type == '68'){						
						$sensor_type = 'Humidity Stream';
					}
					elseif($device_type == '61'){						
						$sensor_type = 'Accelerometer Stream';
					}
					
	
					$sQuery = " UPDATE device_settings SET sensor_active=:sensor_active"
                        		. " WHERE gateway_id=:gateway_id AND device_id = :device_id AND device_sensor=:sensor_type AND is_deleted =0";
					$db->query($sQuery);
					$db->bind(':gateway_id', $gateway_id);
					$db->bind(':sensor_type', $sensor_type);
					$db->bind(':device_id', $device_id);
					$db->bind(':sensor_active', $sensor_active);
					$db->execute();
					
				}

				if($req_type == 'DETP' && $rate_value == $device_value){
											
					$uQuery1 = " UPDATE devices SET format=:format, rate_value=:rate_value"
							." WHERE gateway_id=:gateway_id AND device_id =:device_id AND device_type = :device_type AND is_deleted =0 ";
					$db->query($uQuery1);	
					$db->bind(':gateway_id',$gateway_id);
					$db->bind(':device_id',$device_id);
					$db->bind(':device_type',$device_type);	
					$db->bind(':format', $format);
					$db->bind(':rate_value', $rate_value);
					$db->execute();
					
				}

				if($req_type == 'FREQ' && $rate_value == $device_value){
											
					$uQuery1 = " UPDATE gateway_devices SET frequency=:rate_value"
							." WHERE gateway_id=:gateway_id AND device_id =:device_id AND is_deleted =0  AND is_blacklisted='N'";
					$db->query($uQuery1);	
					$db->bind(':gateway_id',$gateway_id);
					$db->bind(':device_id',$device_id);
					$db->bind(':device_type',$device_type);	
					$db->bind(':format', $format);
					$db->bind(':rate_value', $rate_value);
					$db->execute();
					
				}


				$isAuthenticatedDevice   =   checkAuthenticatedDevice($db,$gateway_id,$device_id);				

				if($device_type == '01' || $device_type == '02' || $device_type == '03' || $device_type == '04' || $device_type == '05' || $device_type == '06' || $device_type == '07' || $device_type == '08'){
					$req_type = 'SET';
				}else if($device_type == '09' || $device_type == '10'){
					$req_type = 'SETS';
					$rate_value = $device_value;
				}

				$pubTopic    =  "$gateway_id".'';
				$pubMessage  =  $gateway_id.','.$device_id.','.$device_type.','.$device_value.','.$isAuthenticatedDevice['nick_name'].','.$updated_on.','.$req_type.','.$format.','.$rate_value.','.$ack;
				if($pubTopic!='' && strlen($pubMessage)>12){
					publishGateway($pubTopic,$pubMessage);
				}

			$eQuery = "UPDATE data_dump"
						." SET action = 1"
						." WHERE id=:dump_id";
						$db->query($eQuery);
						$db->bind(':dump_id', $dump_id);
						$db->execute();			

		}
		else{

			
		$dataArr = extractData($data);
		
		if(sizeof($dataArr)==13 && $dataArr[0] != ''){
				$gateway_id   = $dataArr[0];
            
				$device_id    = $dataArr[1];
            	$device_type  = $dataArr[2];
            	$device_value = $dataArr[3];
				$dec_device_value = $dataArr[4];

				$hh = hexdec($dataArr[5]);
				$mm = hexdec($dataArr[6]);
				$ss = hexdec($dataArr[7]);

				$dd = hexdec($dataArr[8]);
				$month = hexdec($dataArr[9]);
				$yy1 = hexdec($dataArr[10]);
				$yy2 = hexdec($dataArr[11]);
				$cm_string = $dataArr[12];

				$full_year = $yy1 . $yy2;

				$user_id = ltrim($gateway_id, "0");


				if($device_id == '00' || $device_type == '00'){

					//Publish on mQTT directly -- topic 'settime'

					$GatewayMacID = getGatewayMacID($db, $gateway_id);
					$gateway_mac_id = $GatewayMacID[0]['gateway_mac_id'];

			
					$data_arr  =  array();
                    			$data_arr['gateway_mac_id']    =  $gateway_mac_id;
                    			$data_arr['gateway_id']    =  $gateway_id;
                    			$data_arr['device_id']     =  'FF';
                    			$data_arr['device_value']   =  'FF00FF';
                    
					$data_arr['dynamic_id']     =  'FF';
                    
					$data_arr['topic']         =  TOPIC_SET_TIME;
            
					$url        =  URL_END_POINT.'sensegiz-mqtt/publish.php';
                
					$response   =  $curl->postRequestData($url, $data_arr);
										

					$eQuery = "UPDATE data_dump"
						." SET action = 12"
						." WHERE id=:dump_id";
					$db->query($eQuery);
					$db->bind(':dump_id', $dump_id);
					$db->execute();

					continue;
				}

				if($dd<=9){
                 	$dd = sprintf("%02s", $dd);
                  	$dd = strtoupper($dd);
		        }
		        if($month<=9){
		                 	$month = sprintf("%02s", $month);
		                  	$month = strtoupper($month);
		        } 

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
				$full_date = $full_year . '-' . $month . '-' . $dd;

				$full_datetime = $full_date . ' ' . $recv_time;
				print_r($full_datetime);


				// If year is less below 2020 then Discard and Change the action status to 7
				if($full_year <= '2020' || $month == '00' || $dd == '00'){										

					$eQuery = "UPDATE data_dump"
						." SET action = 7"
						." WHERE id=:dump_id";
					$db->query($eQuery);
					$db->bind(':dump_id', $dump_id);
					$db->execute();

					continue;
				}


				if($hh > 23 || $mm > 59 || $ss > 59){

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
										

					$eQuery = "UPDATE data_dump"
						." SET action = 10"
						." WHERE id=:dump_id";
					$db->query($eQuery);
					$db->bind(':dump_id', $dump_id);
					$db->execute();

					continue;
				}
	
				$received_on = substr_replace($updated_on, $recv_time, 11, 8);
				//$c_hh = substr($updated_on, 11, 2);

				$diff = strtotime($received_on) - strtotime($updated_on);

				/*if($received_on > $updated_on  && $diff > 60){
					$tquery = "SELECT ((received_on) - INTERVAL '1 DAY') AS c_date from data_dump where id = :dump_id";
					$db->query($tquery);
					$db->bind(':dump_id', $dump_id);
					$res=$db->resultset();
			
					$cdate = $res[0][c_date];
					$updated_on = substr_replace($cdate, $recv_time, 11, 8);

				}else{
					$updated_on = substr_replace($updated_on, $recv_time, 11, 8);
				}*/
			
			$isGwAuthenticated = checkAuthenticatedGateway($db,$gateway_id);
			if(!empty($isGwAuthenticated)){
			
                $sQuery1 = " UPDATE user_gateways "
                        . " SET data_received_on=:updated_on, updated_on=:updated_on, active='Y'"
                        . " WHERE gateway_id=:gateway_id AND is_deleted =0 ";
                $db->query($sQuery1);                    
                $db->bind(':gateway_id',$gateway_id);               
                $db->bind(':updated_on', $full_datetime);
                $db->execute();
				if($device_type!='12' && $device_type!='14' && $device_type!='15') { // Added for Accelerometer Stream
					
					$isAuthenticatedDevice   =   checkAuthenticatedDevice($db,$gateway_id,$device_id);
					
					if(!empty($isAuthenticatedDevice)){
						$isDevGwExists = isDeviceGatewayExists($db,$gateway_id,$device_id,$device_type);
						if(!empty($isDevGwExists)){
							if($device_type!='09' || $device_type!='10' || $device_type!='11' || $device_type!='16' || $device_type!='13') {
								
								$other_device_type = getOtherDeviceType($device_type);
								
								$tQuery1 = "SELECT threshold_value"
										." FROM devices"
										." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type=:device_type  AND is_deleted =0 ORDER BY d_id DESC LIMIT 1";
								$db->query($tQuery1);
								$db->bind(':gateway_id', $gateway_id);
								$db->bind(':device_id', $device_id);
								$db->bind(':device_type', $device_type);
								$res = $db->single();

								$threshold_value = $res[threshold_value];
							
								if($threshold_value == '') {
									$threshold_value = setDefaultThreshold($device_type);
								}
								
								$tQuery2 = "SELECT threshold_value"
										." FROM devices"
										." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type=:device_type  AND is_deleted =0 ORDER BY d_id DESC LIMIT 1";
								$db->query($tQuery2);
								$db->bind(':gateway_id', $gateway_id);
								$db->bind(':device_id', $device_id);							
								$db->bind(':device_type', $other_device_type);
								$res = $db->single();

								$other_threshold_value = $res[threshold_value];

								if($other_threshold_value == '') {
									$other_threshold_value = setDefaultThreshold($other_device_type);
								}
							
								
							}

							$decVal = hexdec($device_value);
							$dec_device_value = hexdec($dec_device_value);
							

							if($device_type == '01' || $device_type == '02')
	 						{
	     							$decVal = $decVal / 10;
	 
	 						}
							if($device_type == '03' || $device_type == '04')
	 						{
	    							$decVal = $decVal * 10;
	 						}
	 						if($device_type == '05' || $device_type == '06' || $device_type == '09')
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
							}

							if(($device_type == '07' || $device_type == '08' || $device_type == '10') && ($decVal > 99 || $decVal < 1)){										

								$eQuery = "UPDATE data_dump"
									." SET action = 11"
									." WHERE id=:dump_id";
								$db->query($eQuery);
								$db->bind(':dump_id', $dump_id);
								$db->execute();

								continue;
							}

							/* Humidity Value reduce for Rockman Customer */
							if(($device_type == '07' || $device_type == '08') && ($isGwAuthenticated['user_id'] == '13')){
								$decVal = $decVal - 20;
							}
							
							$coin_type = $isAuthenticatedDevice['coin_type'];

							if(($device_type == '07' || $device_type == '08') && ($isGwAuthenticated['user_id'] != '13')){
								$latest_temp = getlatestTemperature($db,$gateway_id,$device_id); //,$device_type
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
							
							
							$sQuery3 = " UPDATE devices "
	                                			. " SET threshold_value=:threshold_value, device_value=:device_value, updated_on=:updated_on"
	                                			. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type=:device_type AND is_deleted =0 ";
	                        			$db->query($sQuery3);                    
	                        			$db->bind(':gateway_id',$gateway_id);
	                        			$db->bind(':device_id',$device_id);
	                        			$db->bind(':device_type',$device_type);
	                        			$db->bind(':device_value',$decVal);
	                        			$db->bind(':threshold_value', $threshold_value);
	                        			$db->bind(':updated_on', $full_datetime);
										
							
							if($db->execute()){

	                                			$gQuery = "UPDATE gateway_devices"
	                                        			." SET active='Y', status='Y', updated_on=:updated_on"
	                                        			." WHERE gateway_id=:gateway_id AND device_id=:device_id";
	                                			$db->query($gQuery);
	                                			$db->bind(':gateway_id', $gateway_id);
	                                			$db->bind(':device_id', $device_id);
	                                			$db->bind(':updated_on', $full_datetime);

									
	                                if($db->execute()){
										$sQuery41 = " INSERT INTO devices_stream "
												. " (device_id,gateway_id,device_type,device_value,updated_on, threshold_value)"
												. " VALUES (:device_id,:gateway_id,:device_type,:device_value,:updated_on, :threshold_value)";
										$db->query($sQuery41);
										$db->bind(':gateway_id',$gateway_id);
										$db->bind(':device_id',$device_id);
										$db->bind(':device_type',$device_type);
										$db->bind(':threshold_value', $threshold_value);
										$db->bind(':device_value',$decVal);
										$db->bind(':updated_on', $full_datetime);
										$db->execute();
										

										
										if($device_type=='01' || $device_type=='03' || $device_type=='05' || $device_type=='07'){
											$sQuery5 = " INSERT INTO threshold "
													. " (gateway_id,device_id,device_type,device_value, low_threshold,high_threshold,updated_on)"
													. " VALUES (:gateway_id,:device_id,:device_type,:device_value,:low_threshold,:high_threshold,:updated_on)";
													
											$db->query($sQuery5);
											$db->bind(':gateway_id',$gateway_id);
											$db->bind(':device_id',$device_id);
											$db->bind(':device_type',$device_type);
											$db->bind(':device_value',$decVal);
											$db->bind(':low_threshold', $threshold_value);
											$db->bind(':high_threshold', $other_threshold_value);
											$db->bind(':updated_on', $full_datetime);
											$db->execute();
										}
										
										if($device_type=='02' || $device_type=='04' || $device_type=='06' || $device_type=='08'){	
											
											$sQuery5 = " INSERT INTO threshold "
													. " (gateway_id,device_id,device_type,device_value,low_threshold,high_threshold,updated_on)"
													. " VALUES (:gateway_id,:device_id,:device_type,:device_value,:low_threshold,:high_threshold,:updated_on)";
													
											$db->query($sQuery5);
											$db->bind(':gateway_id',$gateway_id);
											$db->bind(':device_id',$device_id);
											$db->bind(':device_type',$device_type);
											$db->bind(':device_value',$decVal);
											$db->bind(':low_threshold', $other_threshold_value);
											$db->bind(':high_threshold', $threshold_value);
											$db->bind(':updated_on', $full_datetime);
											$db->execute();		
										}	
										
										
										
										$eQuery = "UPDATE data_dump"
												." SET action = 1"
												." WHERE id=:dump_id";
										$db->query($eQuery);
										$db->bind(':dump_id', $dump_id);
										$db->execute();
									}
	                        }
							

							if($device_type == '16') {
								$bQuery = "UPDATE gateway_devices"
										." SET dynamic_id=:device_value, updated_on=:updated_on"
										." WHERE gateway_id=:gateway_id AND device_id=:device_id";
								$db->query($bQuery);
								$db->bind(':device_value', $device_value);
								$db->bind(':gateway_id', $gateway_id);
								$db->bind(':device_id', $device_id);
								$db->bind(':updated_on', $full_datetime);
								$db->execute();
							}

							if($device_type == '13') {
								$bQuery = "UPDATE gateway_devices"
										." SET battery=:device_value, updated_on=:updated_on"
										." WHERE gateway_id=:gateway_id AND device_id=:device_id";
								$db->query($bQuery);
								$db->bind(':device_value', $device_value);
								$db->bind(':gateway_id', $gateway_id);
								$db->bind(':device_id', $device_id);
								$db->bind(':updated_on', $full_datetime);
								$db->execute();
							}
							
							if($device_type == '13' && $device_value == '03'){
								$isGwAuthenticated = checkAuthenticatedGateway($db,$gateway_id);
								$isAuthenticatedDevice   =   checkAuthenticatedDevice($db,$gateway_id,$device_id);
								$userId   = $isGwAuthenticated['user_id'];
								$nickName = $isAuthenticatedDevice['nick_name'];
								$userMailArr   =       getUserEmailIds($db,$userId);                    
								$subject     =  'Alert! Coin Battery Low';
								$messageEmail = '<html>
												<head>
												<title>Alert</title>
												</head>
												<body>
												<h2>!! Alert !!</h2>
												<h3>Gateway - '.$gateway_id.'</h3>
												<h4>Coin nick named - '.$nickName.' battery level is low. Please check and consider replacing the battery</h4>
												</body>
												</html>';
					
								if(!empty($userMailArr)){
									if(sendMails($userMailArr,$subject,$messageEmail)){
										print_r('low battery mail sent');
									}
								}else{
									print_r('user-mail-list-empty');
								}
							}
							
							$loop1 = '05';
							$loop11 = '07';
							
							
							if($device_type!=$loop1 && $device_type!=$loop11 && $threshold_value!= ''){
								if($device_value > $threshold_value){
									$rowSettings  =  checkGatewaySettings($db,$gateway_id,$device_type);
									if(!empty($rowSettings) && $rowSettings['sensor_type']!='Others'){
										$decVal = hexdec($device_value);
										$dec_device_value = hexdec($dec_device_value);

										if($device_type == '01' || $device_type == '02')
										{
											$decVal = $decVal / 10;
										}
										if($device_type == '03' || $device_type == '04')
										{
											$decVal = $decVal * 10;
										}
										if($device_type == '05' || $device_type == '06')
										{
											if($decVal > 126){
												$decVal = $decVal - 126;
												$decVal = -$decVal;
											}
											if($decVal == 126){
												$decVal = 0;
											}
											$decVal = $decVal . '.' . $dec_device_value;
										}

										$nickName = $isAuthenticatedDevice['nick_name'];
										$userId   = $isGwAuthenticated['user_id'] ;
										$userDetails   =     getUserDetails($db,$userId);
										$userMailArr   =       getUserEmailIds($db,$userId);
										$userPhoneArr  =      getUserPhoneNumbers($db,$userId);
										$userEmail     =     @$userDetails['user_email'];
										$userPhone     =     @$userDetails['user_phone'];                                            
										$sensorType    =     $rowSettings['sensor_type'];
										
										if(!empty($userMailArr) && $rowSettings['email_alert']=='Y'){
											$emailsArray = $userMailArr;
											$subject     =  'Alert! '.$sensorType.' value crossed threshold for '.$nickName;
											$messageEmail = '<html>
															<head>
															<title>Alert</title>
															</head>
															<body>
															<h2>!! Alert !!</h2>
															<h3>Gateway - '.$gateway_id.'</h3>
															<h4>'.$nickName.' '.$sensorType.' has exceeded the threshold and current value is '.$decVal.'</h4>
															</body>
															</html>';
											if($rowSettings['sensor_type']!='Others'){
												sendMails($emailsArray,$subject,$messageEmail);
											}
										}
	                                                        
										if(!empty($userPhoneArr) && $rowSettings['sms_alert']=='Y'){
											$messageSms  = 'Gateway - '.$gateway_id.' threshold of '.$sensorType.' of '.$nickName.' is crossed and current value is '.$decVal.'';

											if($rowSettings['sensor_type']!='Others'){
												checkUsersSMSCount($db, $userId, $userEmail, $userPhoneArr, $messageSms);
											}
										}                        
									}
								}
							}
							else
							{
								if($device_type==$loop1 || $device_type==$loop11 && $threshold_value!= ''){
									if($device_value < $threshold_value){
									$rowSettings  =  checkGatewaySettings($db,$gateway_id,$device_type);
	                        
									if(!empty($rowSettings) && $rowSettings['sensor_type']!='Others'){  
										$decVal = hexdec($device_value);
										$dec_device_value = hexdec($dec_device_value);

										if($device_type == '05' || $device_type == '06')
										{
											if($decVal > 126){
												$decVal = $decVal - 126;
												$decVal = -$decVal;
											}
											if($decVal == 126){
												$decVal = 0;
											}
											$decVal = $decVal . '.' . $dec_device_value;
										}

										$nickName = $isAuthenticatedDevice['nick_name'];
										$userId   = $isGwAuthenticated['user_id'] ;
										$userDetails   =     getUserDetails($db,$userId);
										$userMailArr   =       getUserEmailIds($db,$userId);
										$userPhoneArr  =      getUserPhoneNumbers($db,$userId);
										$userEmail     =     @$userDetails['user_email'];
										$userPhone     =     @$userDetails['user_phone'];                                            
										$sensorType    =     $rowSettings['sensor_type'];
	                            
										if(!empty($userMailArr) && $rowSettings['email_alert']=='Y'){
											$emailsArray = $userMailArr;
											$subject     =  'Alert! '.$sensorType.' value crossed threshold for '.$nickName;
											$messageEmail = '<html>
															<head>
															<title>Alert</title>
															</head>
															<body>
															<h2>!! Alert !!</h2>
															<h3>Gateway - '.$gateway_id.'</h3>
															<h4>'.$nickName.' '.$sensorType.' has dropped below the threshold and current value is '.$decVal.'</h4>
															</body>
															</html>';
											if($rowSettings['sensor_type']!='Others'){
												sendMails($emailsArray,$subject,$messageEmail);
											}
										}
	                                                        
										if(!empty($userPhoneArr) && $rowSettings['sms_alert']=='Y'){
											$messageSms  = 'Gateway - '.$gateway_id.' threshold of '.$sensorType.' of '.$nickName.' is crossed and current value is '.$decVal.'';

											if($rowSettings['sensor_type']!='Others'){
												checkUsersSMSCount($db, $userId, $userEmail, $userPhoneArr, $messageSms);
											}
										}
									}
									}
								}
							}
							$pubTopic    =  "$gateway_id".'';
							$pubMessage  =  $gateway_id.','.$device_id.','.$device_type.','.$decVal.','.$isAuthenticatedDevice['nick_name'].','.$full_datetime;
							if($pubTopic!='' && strlen($pubMessage)>12){
								publishGateway($pubTopic,$pubMessage);
							}
						}
						else
						{
							$eQuery = "UPDATE data_dump"
										." SET action = 5"
										." WHERE id=:dump_id";
							$db->query($eQuery);
							$db->bind(':dump_id', $dump_id);
							$db->execute();
						}
					
					}
					else
					{
						$eQuery = "UPDATE data_dump"
										." SET action = 4"
										." WHERE id=:dump_id";
						$db->query($eQuery);
						$db->bind(':dump_id', $dump_id);
						$db->execute();
					}
					
				}else { // Accelerometer Stream
						$isAuthenticatedDevice   =   checkAuthenticatedDevice($db,$gateway_id,$device_id);
				
						if(!empty($isAuthenticatedDevice)){
							$isDevGwExists = isDeviceGatewayExists($db,$gateway_id,$device_id,$device_type);
							if(!empty($isDevGwExists)){
								$dval = $device_value . $dec_device_value;;

								$dvalue = hexdec($dval) - 65536;
								$finalvalue = dataconvert($dvalue);

								$final = $finalvalue * 0.000488;

								if($final < 0){
										$final = $final * -1;
									}

								//Acceleration
								$val = $final*9.8;
								$acceleration_value = round($val, 6);


								if($device_type == '12'){
								   	$acceleration_type= '17';
								   	$velocity_type = '20';
								   	$displacement_type ='23';
									$d_types = array('12','17','20','23');
									
								}
								if($device_type == '14'){
									 $acceleration_type= '18';
								         $velocity_type = '21';
									 $displacement_type ='24';
										$d_types = array('14','18','21','24');			
										
								}
								if($device_type == '15'){
									 $acceleration_type= '19';
									 $velocity_type = '22';
									 $displacement_type ='25';
										$d_types = array('15','19','22','25');
										
								}


								//$t=0.001667;

								$timeQuery = "SELECT time_factor"
									." FROM user_gateways"
									." WHERE gateway_id=:gateway_id AND is_deleted=0 ";
								$db->query($timeQuery);
								$db->bind(':gateway_id', $gateway_id);
								$tf = $db->single();

								$t = $tf[time_factor];

					
								// Velocity
								

								$velocity_val = $acceleration_value*$t;
								$velocity_rms = $velocity_val * 0.707;

								

								// Displacement
								

								$displacement_val = ($velocity_val * $t) + (0.5 * $acceleration_value * $t * $t);
								$displacement_rms = $displacement_val * 0.707;

								
	
								foreach ($d_types as $types) { 

									if($types == '12' || $types == '14' || $types == '15'){
										$ans = $final;
									}
									if($types == '17' || $types == '18' || $types == '19'){
										$ans = $acceleration_value;
									}
									if($types == '20' || $types == '21' || $types == '22'){
										$ans = $velocity_rms;
									}
									if($types == '23' || $types == '24' || $types == '25'){
										$ans = $displacement_rms;
									}
										
									$sQuery6 = " UPDATE devices"
											. " SET device_value=:device_value, updated_on=:updated_on"
											. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type=:device_type AND is_deleted =0 ";
									$db->query($sQuery6);                    
									$db->bind(':gateway_id',$gateway_id); 
									$db->bind(':device_id',$device_id); 
									$db->bind(':device_type',$types);
									$db->bind(':device_value',$ans);                     
									$db->bind(':updated_on', $full_datetime);
              								$db->execute();
         							}

									
									$gQuery = "UPDATE gateway_devices"
                                        					." SET active='Y', status='Y', updated_on=:updated_on"
                                        					." WHERE gateway_id=:gateway_id AND device_id=:device_id";
											$db->query($gQuery);
											$db->bind(':gateway_id', $gateway_id);  
											$db->bind(':device_id',$device_id);                              
											$db->bind(':updated_on', $full_datetime);
								
									if($db->execute()){
										$sQuery40 = " INSERT INTO devices_stream "
											. " (device_id,gateway_id,device_type,device_value,updated_on)"
											. " VALUES (:device_id,:gateway_id,:device_type,:device_value,:updated_on)";
												$db->query($sQuery40);
												$db->bind(':gateway_id',$gateway_id);	
												$db->bind(':device_id',$device_id); 
												$db->bind(':device_type',$device_type);									
												$db->bind(':device_value',$final);
												$db->bind(':updated_on', $full_datetime);
												$db->execute();

												$sQuery41 = " INSERT INTO acceleration"
											. " (device_id,gateway_id,device_type,device_value, added_on)"
											. " VALUES (:device_id,:gateway_id,:device_type,:acceleration_value,:updated_on)";
												$db->query($sQuery41);
												$db->bind(':gateway_id',$gateway_id);	
												$db->bind(':device_id',$device_id); 
												$db->bind(':device_type',$acceleration_type);									
												$db->bind(':acceleration_value',$acceleration_value);
												$db->bind(':updated_on', $full_datetime);
												$db->execute();



												$sQuery42 = " INSERT INTO velocity"
											. " (device_id,gateway_id,device_type,device_value, added_on)"
											. " VALUES (:device_id,:gateway_id,:device_type,:velocity_value,:updated_on)";
												$db->query($sQuery42);
												$db->bind(':gateway_id',$gateway_id);	
												$db->bind(':device_id',$device_id); 
												$db->bind(':device_type',$velocity_type);									
												$db->bind(':velocity_value',$velocity_rms);
												$db->bind(':updated_on', $full_datetime);
												$db->execute();

												$sQuery43 = " INSERT INTO displacement"
											. " (device_id,gateway_id,device_type,device_value, added_on)"
											. " VALUES (:device_id,:gateway_id,:device_type,:displacement_value,:updated_on)";
												$db->query($sQuery43);
												$db->bind(':gateway_id',$gateway_id);	
												$db->bind(':device_id',$device_id); 
												$db->bind(':device_type',$displacement_type);									
												$db->bind(':displacement_value',$displacement_rms);
												$db->bind(':updated_on', $full_datetime);
												$db->execute();

										//Calculate aggregated Value
									if($device_type == '15'){
										
										$xgQuery = "SELECT device_value"
											." FROM devices"
											." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type='12' AND is_deleted =0 LIMIT 1";
										$db->query($xgQuery);
										$db->bind(':gateway_id', $gateway_id);
										$db->bind(':device_id', $device_id);
										$xgres = $db->single();

										$xg_value = $xgres[device_value];

										
										$ygQuery = "SELECT device_value"
											." FROM devices"
											." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type='14' AND is_deleted =0 LIMIT 1";
										$db->query($ygQuery);
										$db->bind(':gateway_id', $gateway_id);
										$db->bind(':device_id', $device_id);
										$ygres = $db->single();

										$yg_value = $ygres[device_value];	

										
										
										$asg_value = sqrt(($xg_value * $xg_value) + ($yg_value * $yg_value) + ($final * $final));
										

										$aval = $asg_value*9.8;
										$aa_value = round($aval, 6);

										
										// Velocity									 																																
								 		$av_val = $aa_value * $t;
										$av_rms = $av_val * 0.707;
															

										// Displacement									 									
										$ad_val = ($av_val * $t) + (0.5 * $aa_value * $t * $t);
										$ad_rms = $ad_val * 0.707;
										
										
										$ag_types = array('28','29','30','31');
										
										foreach ($ag_types as $agtypes) {

											if($agtypes == '28'){ $agans = $asg_value; }
											if($agtypes == '29'){ $agans = $aa_value; }
											if($agtypes == '30'){ $agans = $av_rms; }
											if($agtypes == '31'){ $agans = $ad_rms; }
											
											$sQuerya = " UPDATE devices"
												. " SET device_value=:device_value, updated_on=:updated_on"
												. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type=:device_type AND is_deleted =0 ";
											$db->query($sQuerya);                    
											$db->bind(':gateway_id',$gateway_id); 
											$db->bind(':device_id',$device_id); 
											$db->bind(':device_type',$agtypes);
											$db->bind(':device_value',$agans);                     
											$db->bind(':updated_on', $full_datetime);
              										$db->execute();
										}

										$sQuery60 = " INSERT INTO devices_stream "
											. " (device_id,gateway_id,device_type,device_value,updated_on)"
											. " VALUES (:device_id,:gateway_id,'28',:device_value,:updated_on)";
										$db->query($sQuery60);
										$db->bind(':gateway_id',$gateway_id);	
										$db->bind(':device_id',$device_id); 								
										$db->bind(':device_value',$asg_value);
										$db->bind(':updated_on', $full_datetime);
										$db->execute();

										$sQuery61 = " INSERT INTO acceleration"
											. " (device_id,gateway_id,device_type,device_value, added_on)"
											. " VALUES (:device_id,:gateway_id,'29',:acceleration_value, :updated_on)";
										$db->query($sQuery61);
										$db->bind(':gateway_id',$gateway_id);	
										$db->bind(':device_id',$device_id); 									
										$db->bind(':acceleration_value',$aa_value);
										$db->bind(':updated_on', $full_datetime);
										$db->execute();


										$sQuery62 = " INSERT INTO velocity"
											. " (device_id,gateway_id,device_type,device_value, added_on)"
											. " VALUES (:device_id,:gateway_id,'30',:velocity_value, :updated_on)";
										$db->query($sQuery62);
										$db->bind(':gateway_id',$gateway_id);	
										$db->bind(':device_id',$device_id); 									
										$db->bind(':velocity_value',$av_rms);
										$db->bind(':updated_on', $full_datetime);
										$db->execute();

										$sQuery63 = " INSERT INTO displacement"
											. " (device_id,gateway_id,device_type,device_value, added_on)"
											. " VALUES (:device_id,:gateway_id,'31',:displacement_value, :updated_on)";
										$db->query($sQuery63);
										$db->bind(':gateway_id',$gateway_id);	
										$db->bind(':device_id',$device_id); 									
										$db->bind(':displacement_value',$ad_rms);
										$db->bind(':updated_on', $full_datetime);
										$db->execute();

									}


									
										$eQuery = "UPDATE data_dump"
												." SET action = 1"
												." WHERE id=:dump_id";
											$db->query($eQuery);
											$db->bind(':dump_id', $dump_id);
											$db->execute();
									
									
									
									}	
								
									
									$pubTopic    =  "$gateway_id".'';
									$pubMessage  =  $gateway_id.','.$device_id.','.$device_type.','.$final.','.$isAuthenticatedDevice['nick_name'].','.$full_datetime;
									if($pubTopic!='' && strlen($pubMessage)>12){
										publishGateway($pubTopic,$pubMessage);
									}
							}else
							{
								$eQuery = "UPDATE data_dump"
									." SET action = 5"
									." WHERE id=:dump_id";
								$db->query($eQuery);
								$db->bind(':dump_id', $dump_id);
								$db->execute();
							}
						}
						else
						{
								$eQuery = "UPDATE data_dump"
									." SET action = 4"
									." WHERE id=:dump_id";
								$db->query($eQuery);
								$db->bind(':dump_id', $dump_id);
								$db->execute();
						}
							
				} // else part for stream end
			}
			else
			{
				$eQuery = "UPDATE data_dump"
									." SET action = 3"
									." WHERE id=:dump_id";
					$db->query($eQuery);
					$db->bind(':dump_id', $dump_id);
					$db->execute();
			}		
		}
		}
	}
	elseif(!empty($data) && strlen($data)==24)
	{
		$res=split_on($data, 12);
		$gateway_id = $res[0];
		$gateway_mac_id = $res[1];
		
		$isGwAuthenticated = checkAuthenticatedGateway($db,$gateway_id);
		if(!empty($isGwAuthenticated)){
			$sQuery1 = " UPDATE user_gateways "
                        . " SET gateway_mac_id=:gateway_mac_id, updated_on=:updated_on"
                        . " WHERE gateway_id=:gateway_id AND is_deleted =0 ";
                $db->query($sQuery1);                    
                $db->bind(':gateway_id',$gateway_id); 
				$db->bind(':gateway_mac_id',$gateway_mac_id); 
                $db->bind(':updated_on', $updated_on);
                $db->execute();
				
				$eQuery = "UPDATE data_dump"
						." SET action = 1"
						." WHERE id=:dump_id";
					$db->query($eQuery);
					$db->bind(':dump_id', $dump_id);
					$db->execute();
		}
		else
		{
				$eQuery = "UPDATE data_dump"
						." SET action = 3"
						." WHERE id=:dump_id";
					$db->query($eQuery);
					$db->bind(':dump_id', $dump_id);
					$db->execute();
		}
	}
	else
	{
		$eQuery = "UPDATE data_dump"
                            ." SET action = 2"
                            ." WHERE id=:dump_id";
            $db->query($eQuery);
            $db->bind(':dump_id', $dump_id);
            $db->execute();
	}
	usleep(100);
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
	$finalArr[4] = $arr2[3];
	$finalArr[5] = $arr2[4];
	$finalArr[6] = $arr2[5];
	$finalArr[7] = $arr2[6];
	$finalArr[8] = $arr2[7];  // dd
	$finalArr[9] = $arr2[8];  // mm
	$finalArr[10] = $arr2[9]; // yy 2 digit
	$finalArr[11] = $arr2[10]; // yy 2 digit
	$finalArr[12] = $arr2[11]; // cm text
                
    return $finalArr;
}


function extractAxisData($data){
    $finalArr = [];
    $arr1        = split_on($data, 4);
    
    $finalArr[0] = $arr1[0];
    //It will split 2 digits each from 6 digits i.e $arr2[0]=> 12, $arr2[1]=> 34, $arr2[3]=> 56,
    $arr2        = split_on($arr1[1], 2);
    $finalArr[1] = $arr2[0];
    $arr3 = str_split($arr2[1], 4);
    $finalArr[2] = $arr3[0];
	$finalArr[3] = $arr3[1];
	$finalArr[4] = $arr3[2];
                
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

	
    $mqttPub   = new phpMQTT(SERVER_IP, 8883, $clientId, "sensegiz123", "sg12345"); 
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


function getOtherDeviceType($device_type)
{
	if($device_type=='01'){
        $other_device_type = '02';
    }
    elseif($device_type=='02'){
        $other_device_type = '01';
    }
	elseif($device_type=='03'){
        $other_device_type = '04';
    }
	elseif($device_type=='04'){
        $other_device_type = '03';
    }
	elseif($device_type=='05'){
        $other_device_type = '06';
    }
	elseif($device_type=='06'){
        $other_device_type = '05';
    }
	elseif($device_type=='07'){
        $other_device_type = '08';
    }
	elseif($device_type=='08'){
        $other_device_type = '07';
    }
	
	return $other_device_type;
}

function setDefaultThreshold($device_type)
{
	if($device_type == '01'){
		$threshold = '09';
	}
	if($device_type == '02'){
		$threshold = '1C';
	}
	if($device_type == '03'){
		$threshold = '0A';
	}
	if($device_type == '04'){
		$threshold = '12C';
	}
	if($device_type == '05'){
		$threshold = '05';
	}
	if($device_type == '06'){
		$threshold = '46';
	}
	if($device_type == '07'){
		$threshold = '01';
	}
	if($device_type == '08'){
		$threshold = '5A';
	}

	return $threshold;
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
    
function sendSMS($userPhone,$messageSms){        
   

        $phone = ltrim($userPhone, '+');
    
        $ch=curl_init();
        $message = urlencode($messageSms);   

	$subject = "ALERT";
	$subject = urlencode($subject);                                           
	curl_setopt($ch,CURLOPT_URL,"https://".SERVER_IP.":3000/sns/?message=".$message."&number=".$phone."&subject=".$subject."");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	if($output =curl_exec($ch)) {
               	print_r('-AWS-sms-sent-');
         }
         curl_close($ch); 
    
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

function checkUsersSMSCount($db, $userId, $userEmail, $userPhoneArr, $messageSms)
{		
		$todaysDate = date("Y-m-d");
		
		$sQuery = " SELECT *"
                . " FROM usersSMS"                    
                . " WHERE user_id=:user_id";       
         
        $db->query($sQuery);
        $db->bind(':user_id',$userId);
		
		$SMSDetails = $db->resultSet();
	
	if(!empty($SMSDetails)){	
		$smsDate = $SMSDetails[0][sms_date];
		$smsCount = $SMSDetails[0]['sms'];
		$usedsmsCount = $SMSDetails[0]['used_sms'];
		$paidSMSCount = $SMSDetails[0]['paid_sms'];
		$usedpaidSMSCount = $SMSDetails[0]['used_paid_sms'];
		$alertEmailSent = $SMSDetails[0]['alert_email_sent'];
	}else{
		$uQuery = "INSERT INTO usersSMS(user_id, sms_date) values(:user_id, :todaysDate)";
 
			$db->query($uQuery);	
			$db->bind(':user_id',$userId);
			$db->bind(':todaysDate',$todaysDate);

			$db->execute();

		$smsDate = $todaysDate;
		$smsCount = 5;
		$usedsmsCount = 0;
		$paidSMSCount = -1;
		$usedpaidSMSCount = 0;
		$alertEmailSent = 'N';

	}
		
		if($todaysDate != $smsDate){			
			$uQuery = "UPDATE usersSMS"
					." SET sms_date = :sms_date, used_sms = 0, used_paid_sms = 0, alert_email_sent='N'"
					." WHERE user_id=:user_id"; 
			$db->query($uQuery);
			$db->bind(':user_id',$userId);
			$db->bind(':sms_date',$todaysDate);
			$db->execute();
					
			$usedsmsCount = 0;	
			$usedpaidSMSCount = 0;
			$alertEmailSent = 'N';
		}

		print_r($userPhoneArr);
		
		foreach ($userPhoneArr as $key => $userPhone) {
			$userPhoneNo[] = $userPhone;
			if($paidSMSCount != '-1'){
				// Paid Subscriiption
				if($paidSMSCount != $usedpaidSMSCount){								
								$usedpaidSMSCount = $usedpaidSMSCount + 1;
				
								$uQuery = "UPDATE usersSMS"
								." SET used_paid_sms = :used_paid_sms"
								." WHERE user_id=:user_id"; 
								
								$db->query($uQuery);
								$db->bind(':user_id',$userId);
								$db->bind(':used_paid_sms',$usedpaidSMSCount);
								$db->execute();
								
								sendSMS($userPhone,$messageSms);

								
				}elseif($alertEmailSent == 'N'){
					print_r('SMS Limit Crossed');
					$emailAlert[] = $userEmail;
					$subject     =  'SMS Limit Reached';
					$messageEmail = '<html>
							<head>
							<title>Alert</title>
							</head>
							<body>
							<h2>!! Alert !!</h2>														
							<h4> Your SMS limit for the day has been reached. Please contact SenseGiz representative if you wish to change the limit.</h4>
							</body>
							</html>';
													
					sendMails($emailAlert,$subject,$messageEmail);
					
					$uQuery = "UPDATE usersSMS"
								." SET alert_email_sent = 'Y'"
								." WHERE user_id=:user_id"; 
								
								$db->query($uQuery);
								$db->bind(':user_id',$userId);
								$db->execute();

					break;
				}
				
			}else{
				// Default SMS Subscription
				if($smsCount != $usedsmsCount){
								
					$usedsmsCount = $usedsmsCount + 1;
					
					$uQuery = "UPDATE usersSMS"
						." SET used_sms = :used_sms"
						." WHERE user_id=:user_id"; 
					
					$db->query($uQuery);
					$db->bind(':user_id',$userId);
					$db->bind(':used_sms',$usedsmsCount);
					$db->execute();
								
					sendSMS($userPhone,$messageSms);
				}
				elseif($alertEmailSent == 'N'){
					print_r('SMS Limit Crossed');
					$emailAlert[] = $userEmail;
					$subject     =  'SMS Limit Reached';
					$messageEmail = '<html>
							<head>
							<title>Alert</title>
							</head>
							<body>
							<h2>!! Alert !!</h2>														
							<h4> Your SMS limit for the day has been reached. Please contact SenseGiz representative if you wish to change the limit.</h4>
							</body>
							</html>';
													
					sendMails($emailAlert,$subject,$messageEmail);
					
					$uQuery = "UPDATE usersSMS"
								." SET alert_email_sent = 'Y'"
								." WHERE user_id=:user_id"; 
								
								$db->query($uQuery);
								$db->bind(':user_id',$userId);
								$db->execute();

					break;
				}
		
			}			
		}
		
				
}	


function dataconvert($dval)
{
	$binsize = 15;
	$size = 4;
	$axis_data = $dval;
	$sum = 0;
	
	$b = array();
	$bval = array();
	$d = array();
	$val = array();

	
	if(($dval & 0x8000) == 0x8000)
    	{
		
		$i=0;
		$axis_data = (~$axis_data) + 1;
		
		while($binsize != 0)
		{
			array_push($b, $axis_data & 0x1);			
			$axis_data = $axis_data >> 1;
			$binsize--;			
			
			array_push($bval, $b[$i] * pow(2,$i));
			$sum = $sum + $bval[$i];
			$i++;
			
		}
		
		return(-1 * $sum);
		
		
	}
	else
	{
		$i=0;
				
		while($size != 0)
		{
			array_push($d, $axis_data & 0xF);			
			$axis_data = $axis_data >> 4;
			$size--;									
			
		}
		
		array_push($val, $d[0]);	
		array_push($val, $d[1] * 16);
		array_push($val, $d[2] * 256);
		array_push($val, $d[3] * 4096);	

		for($i=0;$i<4;$i++)
		{				
			$sum = $sum + $val[$i];
		}
		
		return($sum);

	}
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

function getDynamicID($db, $gatewayId, $deviceId){


            $sQuery = " SELECT dynamic_id"
                    . " FROM gateway_devices"                    
                    . " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =0 AND is_blacklisted = 'N'";       

            $db->query($sQuery);  
            $db->bind(':gateway_id',$gatewayId);
            $db->bind(':device_id',$deviceId);

            $row = $db->resultSet();

            return $row;
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