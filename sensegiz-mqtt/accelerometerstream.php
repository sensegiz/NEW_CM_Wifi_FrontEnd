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
        	." FROM accstream_dump"
        	." WHERE (action=0) AND ((ad_id % 2)=0) ORDER BY ad_id ASC LIMIT 1"; // AND ((ad_id % 2)=0) 
    	$db->query($aQuery);
    	$row=$db->single();

    	$data = $row[accstream_data];
    	$dump_id = $row[ad_id];
    	$updated_on = $row[received_on];

	if(strlen($data) == 26){
		$dataArr = extractData($data);
	}else{
		$dataArr = extractAxisData($data);
	}
		
	if(sizeof($dataArr)==8){

		$gateway_id   = $dataArr[0];
            	$device_id    = $dataArr[1];
            	$device_type  = $dataArr[2];
            	$device_value = $dataArr[3];
		$dec_device_value = $dataArr[4];
				
		$hh = hexdec($dataArr[5]);
		$mm = hexdec($dataArr[6]);
		$ss = hexdec($dataArr[7]);

		$startCmdData = getStartCmdData($db,$gateway_id,$device_id);
        if($startCmdData){
            $start_time_id = $startCmdData[id];
            $start_time = $startCmdData[start_time];
            $current_time = date("Y-m-d H:i:s");
            $diff_from_start_cmd = strtotime($current_time) - strtotime($start_time);
			print_r($diff_from_start_cmd."==||");
            if($diff_from_start_cmd > 2400){
                $start_time = $current_time;
                $eQuery = "UPDATE pm_start_cmd"
                    ." SET start_time = '$current_time'"
                    ." WHERE id=:start_time_id";
                $db->query($eQuery);
                $db->bind(':start_time_id', $start_time_id);
                $db->execute();
            }
            $start_time_sec  = strtotime($start_time);
            $hh = date('H', $start_time_sec);
            $mm = date('i', $start_time_sec);
            $ss = date('s', $start_time_sec);
            $updated_on = $start_time;
			print_r($updated_on);
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

			$eQuery = "UPDATE accstream_dump"
				." SET action = 10"
				." WHERE ad_id=:dump_id";
			$db->query($eQuery);
			$db->bind(':dump_id', $dump_id);
			$db->execute();

			continue;
		}
	
		$received_on = substr_replace($updated_on, $recv_time, 11, 8);

		//$c_hh = substr($updated_on, 11, 2);

		$diff = strtotime($received_on) - strtotime($updated_on);

		if($received_on > $updated_on  && $diff > 60){
			$tquery = "SELECT ((received_on) - INTERVAL '1 DAY') AS c_date from accstream_dump where ad_id = :dump_id";
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

    			$isAuthenticatedDevice   =   checkAuthenticatedDevice($db,$gateway_id,$device_id);
				
			if(!empty($isAuthenticatedDevice)){

				$isDevGwExists = isDeviceGatewayExists($db,$gateway_id,$device_id,$device_type);
				if(!empty($isDevGwExists)){

					$dval = $device_value . $dec_device_value;

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
					$velocity_val = $acceleration_value * $t;
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
						$db->bind(':updated_on', $updated_on);
              					$db->execute();
         				}
																																													
								
					$gQuery = "UPDATE gateway_devices"
                                 		." SET active='Y', status='Y', updated_on=:updated_on"
                                        	." WHERE gateway_id=:gateway_id AND device_id=:device_id";
					$db->query($gQuery);
					$db->bind(':gateway_id', $gateway_id);   
					$db->bind(':device_id',$device_id); 
					$db->bind(':updated_on', $updated_on);
								
					if($db->execute()){
						$sQuery40 = " INSERT INTO devices_stream "
							. " (device_id,gateway_id,device_type,device_value,updated_on)"
							. " VALUES (:device_id,:gateway_id,:device_type,:device_value,:updated_on)";
						$db->query($sQuery40);
						$db->bind(':gateway_id',$gateway_id);	
						$db->bind(':device_id',$device_id); 
						$db->bind(':device_type',$device_type);									
						$db->bind(':device_value',$final);
						$db->bind(':updated_on', $updated_on);
						$db->execute();

						$sQuery41 = " INSERT INTO acceleration"
							. " (device_id,gateway_id,device_type,device_value, added_on)"
							. " VALUES (:device_id,:gateway_id,:device_type,:acceleration_value, :updated_on)";
						$db->query($sQuery41);
						$db->bind(':gateway_id',$gateway_id);	
						$db->bind(':device_id',$device_id); 
						$db->bind(':device_type',$acceleration_type);									
						$db->bind(':acceleration_value',$acceleration_value);
						$db->bind(':updated_on', $updated_on);
						$db->execute();

						$sQuery42 = " INSERT INTO velocity"
							. " (device_id,gateway_id,device_type,device_value, added_on)"
							. " VALUES (:device_id,:gateway_id,:device_type,:velocity_value, :updated_on)";
						$db->query($sQuery42);
						$db->bind(':gateway_id',$gateway_id);	
						$db->bind(':device_id',$device_id); 
						$db->bind(':device_type',$velocity_type);									
						$db->bind(':velocity_value',$velocity_rms);
						$db->bind(':updated_on', $updated_on);
						$db->execute();

						$sQuery43 = " INSERT INTO displacement"
							. " (device_id,gateway_id,device_type,device_value, added_on)"
							. " VALUES (:device_id,:gateway_id,:device_type,:displacement_value, :updated_on)";
						$db->query($sQuery43);
						$db->bind(':gateway_id',$gateway_id);	
						$db->bind(':device_id',$device_id); 
						$db->bind(':device_type',$displacement_type);									
						$db->bind(':displacement_value',$displacement_rms);
						$db->bind(':updated_on', $updated_on);
						$db->execute();

						// Store Accelerometer Values for FFT with Frequency
						$device_frequency = $isAuthenticatedDevice['frequency'];
						$fftQuery44 = " INSERT INTO fft_basevalue "
							. " (device_id,gateway_id,device_type,device_value,updated_on,frequency_level)"
							. " VALUES (:device_id,:gateway_id,:device_type,:device_value,:updated_on,:frequency)";
						$db->query($fftQuery44);
						$db->bind(':gateway_id',$gateway_id);	
						$db->bind(':device_id',$device_id); 
						$db->bind(':device_type',$device_type);									
						$db->bind(':device_value',$final);
						$db->bind(':updated_on', $updated_on);
						$db->bind(':frequency',$device_frequency);
						$db->execute();


						$rowSettings  =  checkDeviceSettings($db, $gateway_id, $device_id, $device_type);

						if(!empty($rowSettings) && $rowSettings['device_sensor']=='Accelerometer Stream'){						
							$sensorType = $rowSettings['device_sensor'];
							$low_threshold = $rowSettings['low_threshold'];
							$high_threshold = $rowSettings['high_threshold'];

							if($low_threshold != '' && $high_threshold != ''){

								if($final < $low_threshold || $final > $high_threshold){
									$nickName = $isAuthenticatedDevice['nick_name'];
									$userId   = $isGwAuthenticated['user_id'] ;
									$userDetails   =     getUserDetails($db,$userId);
									$userMailArr   =       getUserEmailIds($db,$userId);
									$userEmail     =     @$userDetails['user_email'];

									if(!empty($userMailArr) && $rowSettings['email_alert']=='Y'){
										$emailsArray = $userMailArr;
				
										
							
											$subject     =  'Alert! '.$sensorType.' value crossed the set threshold for '.$nickName;
											$messageEmail = '<html>
												<head>
												<title>Alert</title>
												</head>
											<body>
												<h2>!! Alert !!</h2>
												<h3>Gateway - '.$gateway_id.'</h3>
												<h4>'.$nickName.' '.$sensorType.' has crossed the threshold and current value is '.$final.'</h4>
											</body>
											</html>';
										


										if($rowSettings['device_sensor']!='Others')
										{
											// sendMails($emailsArray,$subject,$messageEmail);
											$mail_limit_value = $userDetails['mail_restriction_limit'];
											$setMailInterval = $userDetails['mail_restriction_interval'];

											if($userDetails['mail_alert_restriction'] == 'Y')
											{
												$res_mailInterval = getTodayMailTriggerData($db, $userId, $gateway_id, $device_id, $sensorType);

												$mail_trigger_time = $res_mailInterval['mail_trigger_time'];
												$mail_trigger_time1 = date('H:i:s', $mail_trigger_time);
												
												$updatedTime = $res_mailInterval['updated_on'];
												
												// $getTime = strtotime($updatedTime);
												// $getTime1 = date('H:i', $getTime);

												// $getCurrentHM = strtotime($currentTime);
												// $getCurrentHM1 = date('H:i',$getCurrentHM);

												/*$minutediff = round((($getCurrentHM - $getTime))/60);*/

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
								$db->bind(':updated_on', $updated_on);
           							$db->execute();
							}

							$sQuery60 = " INSERT INTO devices_stream "
								. " (device_id,gateway_id,device_type,device_value,updated_on)"
								. " VALUES (:device_id,:gateway_id,'28',:device_value,:updated_on)";
							$db->query($sQuery60);
							$db->bind(':gateway_id',$gateway_id);	
							$db->bind(':device_id',$device_id); 								
							$db->bind(':device_value',$asg_value);
							$db->bind(':updated_on', $updated_on);
							$db->execute();

							$sQuery61 = " INSERT INTO acceleration"
								. " (device_id,gateway_id,device_type,device_value, added_on)"
								. " VALUES (:device_id,:gateway_id,'29',:acceleration_value, :updated_on)";
							$db->query($sQuery61);
							$db->bind(':gateway_id',$gateway_id);	
							$db->bind(':device_id',$device_id); 									
							$db->bind(':acceleration_value',$aa_value);
							$db->bind(':updated_on', $updated_on);
							$db->execute();


							$sQuery62 = " INSERT INTO velocity"
								. " (device_id,gateway_id,device_type,device_value, added_on)"
								. " VALUES (:device_id,:gateway_id,'30',:velocity_value, :updated_on)";
							$db->query($sQuery62);
							$db->bind(':gateway_id',$gateway_id);	
							$db->bind(':device_id',$device_id); 									
							$db->bind(':velocity_value',$av_rms);
							$db->bind(':updated_on', $updated_on);
							$db->execute();

							$sQuery63 = " INSERT INTO displacement"
								. " (device_id,gateway_id,device_type,device_value, added_on)"
								. " VALUES (:device_id,:gateway_id,'31',:displacement_value, :updated_on)";
							$db->query($sQuery63);
							$db->bind(':gateway_id',$gateway_id);	
							$db->bind(':device_id',$device_id); 									
							$db->bind(':displacement_value',$ad_rms);
							$db->bind(':updated_on', $updated_on);
							$db->execute();

							// Store Accelerometer Values for FFT with Frequency
							$device_frequency = $isAuthenticatedDevice['frequency'];
							$fftQuery64 = " INSERT INTO fft_basevalue "
								. " (device_id,gateway_id,device_type,device_value,updated_on,frequency_level)"
								. " VALUES (:device_id,:gateway_id,'28',:device_value,:updated_on,:frequency)";
							$db->query($fftQuery64);
							$db->bind(':gateway_id',$gateway_id);	
							$db->bind(':device_id',$device_id); 								
							$db->bind(':device_value',$asg_value);
							$db->bind(':updated_on', $updated_on);
							$db->bind(':frequency',$device_frequency);
							$db->execute();

						}
				
						$eQuery = "UPDATE accstream_dump"
							." SET action = 1"
							." WHERE ad_id=:dump_id";
						$db->query($eQuery);
						$db->bind(':dump_id', $dump_id);
						$db->execute();									
					}

					$pubTopic    =  "$gateway_id".'';
					$pubMessage  =  $gateway_id.','.$device_id.','.$device_type.','.$final.','.$isAuthenticatedDevice['nick_name'].','.$updated_on;
					if($pubTopic!='' && strlen($pubMessage)>12){
						publishGateway($pubTopic,$pubMessage);
					}	

				}else
				{

					$eQuery = "UPDATE accstream_dump"
						." SET action = 5"
						." WHERE ad_id=:dump_id";
					$db->query($eQuery);
					$db->bind(':dump_id', $dump_id);
					$db->execute();
				}
			}else
			{

				$eQuery = "UPDATE accstream_dump"
					." SET action = 4"
					." WHERE ad_id=:dump_id";
				$db->query($eQuery);
				$db->bind(':dump_id', $dump_id);
				$db->execute();
			}

		}else
		{

			$eQuery = "UPDATE accstream_dump"
				." SET action = 3"
				." WHERE ad_id=:dump_id";
			$db->query($eQuery);
			$db->bind(':dump_id', $dump_id);
			$db->execute();
		}

	}	//End IF seizeof length is 8
	else if(sizeof($dataArr)==9)
	{
		$gateway_id   = $dataArr[0];
            	$device_id    = $dataArr[1];
            	$device_type  = $dataArr[2];
            	// $device_value = $dataArr[3];
		// $dec_device_value = $dataArr[4];
		
		$XData = $dataArr[3];
		$YData = $dataArr[4];
		$ZData = $dataArr[5];

		$hh = hexdec($dataArr[6]);
		$mm = hexdec($dataArr[7]);
		$ss = hexdec($dataArr[8]);

		$startCmdData = getStartCmdData($db,$gateway_id,$device_id);
        if($startCmdData){
            $start_time_id = $startCmdData[id];
            $start_time = $startCmdData[start_time];
            $current_time = date("Y-m-d H:i:s");
            $diff_from_start_cmd = strtotime($current_time) - strtotime($start_time);
			print_r($diff_from_start_cmd."==||");
            if($diff_from_start_cmd > 2400){
                $start_time = $current_time;
                $eQuery = "UPDATE pm_start_cmd"
                    ." SET start_time = '$current_time'"
                    ." WHERE id=:start_time_id";
                $db->query($eQuery);
                $db->bind(':start_time_id', $start_time_id);
                $db->execute();
            }
            $start_time_sec  = strtotime($start_time);
            $hh = date('H', $start_time_sec);
            $mm = date('i', $start_time_sec);
            $ss = date('s', $start_time_sec);
            $updated_on = $start_time;
			print_r($updated_on);
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

			$eQuery = "UPDATE accstream_dump"
				." SET action = 10"
				." WHERE ad_id=:dump_id";
			$db->query($eQuery);
			$db->bind(':dump_id', $dump_id);
			$db->execute();

			continue;
		}
	
		$received_on = substr_replace($updated_on, $recv_time, 11, 8);

		//$c_hh = substr($updated_on, 11, 2);

		$diff = strtotime($received_on) - strtotime($updated_on);

		if($received_on > $updated_on  && $diff > 60){
			$tquery = "SELECT ((received_on) - INTERVAL '1 DAY') AS c_date from accstream_dump where ad_id = :dump_id";
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

    			$isAuthenticatedDevice   =   checkAuthenticatedDevice($db,$gateway_id,$device_id);
				
			if(!empty($isAuthenticatedDevice)){

				$isDevGwExists = isDeviceGatewayExists($db,$gateway_id,$device_id,$device_type);
				if(!empty($isDevGwExists)){

					// $dval = $device_value . $dec_device_value;

					$device_types = array( 12, 14, 15);

					foreach( $device_types as $value ) {
						$device_type = $value;	

						if($device_type == '12'){
							$dval = $XData;
							$acceleration_type= '17';
					   		$velocity_type = '20';
					   		$displacement_type ='23';
					   		$d_types = array('12','17','20','23');

						}elseif($device_type == '14'){
							$dval = $YData;
							$acceleration_type= '18';
				           		$velocity_type = '21';
					   		$displacement_type ='24';
							$d_types = array('14','18','21','24');

						}elseif($device_type == '15'){
							$dval = $ZData;
							$acceleration_type= '19';
					   		$velocity_type = '22';
					   		$displacement_type ='25';
							$d_types = array('15','19','22','25');
						}


						$dvalue = hexdec($dval) - 65536;
						$finalvalue = dataconvert($dvalue);
						$final = $finalvalue * 0.000488;

						if($final < 0){
							$final = $final * -1;
						}

						//Acceleration
						$val = $final*9.8;
						$acceleration_value = round($val, 6);																

						/*if($device_type == '12'){
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
									
						}*/

						//$t=0.001667;

						$timeQuery = "SELECT time_factor"
							." FROM user_gateways"
							." WHERE gateway_id=:gateway_id AND is_deleted=0 ";
						$db->query($timeQuery);
						$db->bind(':gateway_id', $gateway_id);
						$tf = $db->single();

						$t = $tf[time_factor];

						// Velocity									 																																
						$velocity_val = $acceleration_value * $t;
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
							$db->bind(':updated_on', $updated_on);
	              					$db->execute();
	         			}
																																														
									
						$gQuery = "UPDATE gateway_devices"
	                                 		." SET active='Y', status='Y', updated_on=:updated_on"
	                                        	." WHERE gateway_id=:gateway_id AND device_id=:device_id";
						$db->query($gQuery);
						$db->bind(':gateway_id', $gateway_id);   
						$db->bind(':device_id',$device_id); 
						$db->bind(':updated_on', $updated_on);
									
						if($db->execute()){
							$sQuery40 = " INSERT INTO devices_stream "
								. " (device_id,gateway_id,device_type,device_value,updated_on)"
								. " VALUES (:device_id,:gateway_id,:device_type,:device_value,:updated_on)";
							$db->query($sQuery40);
							$db->bind(':gateway_id',$gateway_id);	
							$db->bind(':device_id',$device_id); 
							$db->bind(':device_type',$device_type);									
							$db->bind(':device_value',$final);
							$db->bind(':updated_on', $updated_on);
							$db->execute();

							$sQuery41 = " INSERT INTO acceleration"
								. " (device_id,gateway_id,device_type,device_value, added_on)"
								. " VALUES (:device_id,:gateway_id,:device_type,:acceleration_value, :updated_on)";
							$db->query($sQuery41);
							$db->bind(':gateway_id',$gateway_id);	
							$db->bind(':device_id',$device_id); 
							$db->bind(':device_type',$acceleration_type);									
							$db->bind(':acceleration_value',$acceleration_value);
							$db->bind(':updated_on', $updated_on);
							$db->execute();

							$sQuery42 = " INSERT INTO velocity"
								. " (device_id,gateway_id,device_type,device_value, added_on)"
								. " VALUES (:device_id,:gateway_id,:device_type,:velocity_value, :updated_on)";
							$db->query($sQuery42);
							$db->bind(':gateway_id',$gateway_id);	
							$db->bind(':device_id',$device_id); 
							$db->bind(':device_type',$velocity_type);									
							$db->bind(':velocity_value',$velocity_rms);
							$db->bind(':updated_on', $updated_on);
							$db->execute();

							$sQuery43 = " INSERT INTO displacement"
								. " (device_id,gateway_id,device_type,device_value, added_on)"
								. " VALUES (:device_id,:gateway_id,:device_type,:displacement_value, :updated_on)";
							$db->query($sQuery43);
							$db->bind(':gateway_id',$gateway_id);	
							$db->bind(':device_id',$device_id); 
							$db->bind(':device_type',$displacement_type);									
							$db->bind(':displacement_value',$displacement_rms);
							$db->bind(':updated_on', $updated_on);
							$db->execute();

							// Store Accelerometer Values for FFT with Frequency
							$device_frequency = $isAuthenticatedDevice['frequency'];
							$fftQuery44 = " INSERT INTO fft_basevalue "
								. " (device_id,gateway_id,device_type,device_value,updated_on,frequency_level)"
								. " VALUES (:device_id,:gateway_id,:device_type,:device_value,:updated_on,:frequency)";
							$db->query($fftQuery44);
							$db->bind(':gateway_id',$gateway_id);	
							$db->bind(':device_id',$device_id); 
							$db->bind(':device_type',$device_type);									
							$db->bind(':device_value',$final);
							$db->bind(':updated_on', $updated_on);
							$db->bind(':frequency',$device_frequency);
							$db->execute();
						}

							
							
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
									$db->bind(':updated_on', $updated_on);
	           							$db->execute();
								}

								$sQuery60 = " INSERT INTO devices_stream "
									. " (device_id,gateway_id,device_type,device_value,updated_on)"
									. " VALUES (:device_id,:gateway_id,'28',:device_value,:updated_on)";
								$db->query($sQuery60);
								$db->bind(':gateway_id',$gateway_id);	
								$db->bind(':device_id',$device_id); 								
								$db->bind(':device_value',$asg_value);
								$db->bind(':updated_on', $updated_on);
								$db->execute();

								$sQuery61 = " INSERT INTO acceleration"
									. " (device_id,gateway_id,device_type,device_value, added_on)"
									. " VALUES (:device_id,:gateway_id,'29',:acceleration_value, :updated_on)";
								$db->query($sQuery61);
								$db->bind(':gateway_id',$gateway_id);	
								$db->bind(':device_id',$device_id); 									
								$db->bind(':acceleration_value',$aa_value);
								$db->bind(':updated_on', $updated_on);
								$db->execute();


								$sQuery62 = " INSERT INTO velocity"
									. " (device_id,gateway_id,device_type,device_value, added_on)"
									. " VALUES (:device_id,:gateway_id,'30',:velocity_value, :updated_on)";
								$db->query($sQuery62);
								$db->bind(':gateway_id',$gateway_id);	
								$db->bind(':device_id',$device_id); 									
								$db->bind(':velocity_value',$av_rms);
								$db->bind(':updated_on', $updated_on);
								$db->execute();

								$sQuery63 = " INSERT INTO displacement"
									. " (device_id,gateway_id,device_type,device_value, added_on)"
									. " VALUES (:device_id,:gateway_id,'31',:displacement_value, :updated_on)";
								$db->query($sQuery63);
								$db->bind(':gateway_id',$gateway_id);	
								$db->bind(':device_id',$device_id); 									
								$db->bind(':displacement_value',$ad_rms);
								$db->bind(':updated_on', $updated_on);
								$db->execute();

								// Store Accelerometer Values for FFT with Frequency
								$device_frequency = $isAuthenticatedDevice['frequency'];
								$fftQuery64 = " INSERT INTO fft_basevalue "
									. " (device_id,gateway_id,device_type,device_value,updated_on,frequency_level)"
									. " VALUES (:device_id,:gateway_id,'28',:device_value,:updated_on,:frequency)";
								$db->query($fftQuery64);
								$db->bind(':gateway_id',$gateway_id);	
								$db->bind(':device_id',$device_id); 								
								$db->bind(':device_value',$asg_value);
								$db->bind(':updated_on', $updated_on);
								$db->bind(':frequency',$device_frequency);
								$db->execute();


								$rowSettings  =  checkDeviceSettings($db, $gateway_id, $device_id, $device_type);

								if(!empty($rowSettings) && $rowSettings['device_sensor']=='Accelerometer Stream'){						
									$sensorType = $rowSettings['device_sensor'];
									$low_threshold = $rowSettings['low_threshold'];
									$high_threshold = $rowSettings['high_threshold'];

									if($low_threshold != '' && $high_threshold != ''){

										if($final < $low_threshold || $final > $high_threshold){
											$nickName = $isAuthenticatedDevice['nick_name'];
											$userId   = $isGwAuthenticated['user_id'] ;
											$userDetails   =     getUserDetails($db,$userId);
											$userMailArr   =       getUserEmailIds($db,$userId);
											$userEmail     =     @$userDetails['user_email'];

											if(!empty($userMailArr) && $rowSettings['email_alert']=='Y'){
												$emailsArray = $userMailArr;
						
												
									
													$subject     =  'Alert! '.$sensorType.' value crossed the set threshold for '.$nickName;
													$messageEmail = '<html>
														<head>
														<title>Alert</title>
														</head>
													<body>
														<h2>!! Alert !!</h2>
														<h3>Gateway - '.$gateway_id.'</h3>
														<h4>'.$nickName.' '.$sensorType.' has crossed the threshold and current value is '.$final.'</h4>
													</body>
													</html>';
												


												if($rowSettings['device_sensor']!='Others')
												{
													// sendMails($emailsArray,$subject,$messageEmail);
													$mail_limit_value = $userDetails['mail_restriction_limit'];
													$setMailInterval = $userDetails['mail_restriction_interval'];

													if($userDetails['mail_alert_restriction'] == 'Y')
													{
														$res_mailInterval = getTodayMailTriggerData($db, $userId, $gateway_id, $device_id, $sensorType);

														$mail_trigger_time = $res_mailInterval['mail_trigger_time'];
														$mail_trigger_time1 = date('H:i:s', $mail_trigger_time);
														
														$updatedTime = $res_mailInterval['updated_on'];
														
														// $getTime = strtotime($updatedTime);
														// $getTime1 = date('H:i', $getTime);

														// $getCurrentHM = strtotime($currentTime);
														// $getCurrentHM1 = date('H:i',$getCurrentHM);

														/*$minutediff = round((($getCurrentHM - $getTime))/60);*/

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
							}

					} //foreach device_types end
				
						$eQuery = "UPDATE accstream_dump"
							." SET action = 1"
							." WHERE ad_id=:dump_id";
						$db->query($eQuery);
						$db->bind(':dump_id', $dump_id);
						$db->execute();									
					

					$pubTopic    =  "$gateway_id".'';
					$pubMessage  =  $gateway_id.','.$device_id.','.$device_type.','.$final.','.$isAuthenticatedDevice['nick_name'].','.$updated_on;
					if($pubTopic!='' && strlen($pubMessage)>12){
						publishGateway($pubTopic,$pubMessage);
					}	

				}else
				{

					$eQuery = "UPDATE accstream_dump"
						." SET action = 5"
						." WHERE ad_id=:dump_id";
					$db->query($eQuery);
					$db->bind(':dump_id', $dump_id);
					$db->execute();
				}
			}else
			{

				$eQuery = "UPDATE accstream_dump"
					." SET action = 4"
					." WHERE ad_id=:dump_id";
				$db->query($eQuery);
				$db->bind(':dump_id', $dump_id);
				$db->execute();
			}

		}else
		{

			$eQuery = "UPDATE accstream_dump"
				." SET action = 3"
				." WHERE ad_id=:dump_id";
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

function getStartCmdData($db,$gateway_id,$device_id){
    $sQuery = " SELECT *"
    . " FROM pm_start_cmd"                    
    . " WHERE gateway_id=:gateway_id AND device_id=:device_id ORDER BY id DESC";       

$db->query($sQuery);
$db->bind(':gateway_id',$gateway_id);
$db->bind(':device_id',$device_id);

$row = $db->single();

return  $row = $db->single();    
}


function extractAxisData($data){

    	$finalArr = [];
    	$arr1        = split_on($data, 12);
    
    	$finalArr[0] = $arr1[0];

    	//It will split 2 digits each from 6 digits i.e $arr2[0]=> 12, $arr2[1]=> 34, $arr2[3]=> 56,
    	$arr2        = split_on($arr1[1], 2);
    	$finalArr[1] = $arr2[0];

	$arr3 = split_on($arr2[1], 2);
    	$finalArr[2] = $arr3[0];

    	$arr4 = split_on($arr3[1], 4);
    	$finalArr[3] = $arr4[0];

	$arr5 = split_on($arr4[1], 4);
    	$finalArr[4] = $arr5[0];

	$arr6 = split_on($arr5[1], 4);
    	$finalArr[5] = $arr6[0];


	$arr7 = str_split($arr6[1], 2);
	$finalArr[6] = $arr7[0];
	$finalArr[7] = $arr7[1];
	$finalArr[8] = $arr7[2];
                
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
    elseif($device_type=='12' || $device_type=='14' || $device_type=='15'){
        $sensor_type = 'Accelerometer Stream';
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

?>