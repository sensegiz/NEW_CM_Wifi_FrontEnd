<?php 
/*
  Project                     : Sensegiz
  Module                      : Web Console --  Manager Class
  File name                   : UserManager.php
  Description                 : Manager class for Admin related activities
 */  
class UserManager {

      /*
      Function            : getGateways()
      Brief               : Function to get all Gateways      
      Input param         : Nil
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function getGateways($userId){
        $db       = new ConnectionManager();
        $curl     = new CurlRequest();
        try {      
            $sQuery = " SELECT *"
                    . " FROM user_gateways"                    
                    . " WHERE user_id=:user_id AND is_deleted =0 AND is_blacklisted='N' ORDER BY ug_id DESC";       

            $db->query($sQuery);  
            $db->bind(':user_id',$userId);
            $row = $db->resultSet();
            
            $aList[JSON_TAG_RESULT]= $row;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }
    
    //To check Gateway existed or not
    function checkGateway($db,$gatewayId){
        
//        $sQuery = " SELECT *"
//                . " FROM gateways"                    
//                . " WHERE gateway_id=:gateway_id AND is_deleted =0 ORDER BY g_id DESC";       
        $sQuery = " SELECT *"
                . " FROM user_gateways"                    
                . " WHERE gateway_id=:gateway_id AND is_deleted =0 AND is_blacklisted='N' ORDER BY ug_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':gateway_id',$gatewayId);
        return  $row = $db->single();
    }   
    
//    //To check Gateway existed or not
//    function gatewaysList($db){
//        
//        $sQuery = " SELECT *"
//                . " FROM gateways"                    
//                . " WHERE is_deleted =0 ORDER BY g_id DESC";       
//         
//        $db->query($sQuery);        
//        return  $row = $db->resultSet();
//    }     

    //To check Device and Gatewayexisted or not
    function checkDeviceGateway($db, $deviceId, $gatewayId, $device_type){
        
        $sQuery = " SELECT *"
                . " FROM devices"                    
                . " WHERE device_id=:device_id AND device_type=:device_type AND gateway_id=:gateway_id AND is_deleted =0 ORDER BY d_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':device_id',$deviceId);
        $db->bind(':device_type',$device_type);
        $db->bind(':gateway_id',$gatewayId);
        
        return  $row = $db->single();
    } 
    
      /*
      Function            : getDevices($gatewayId, $coins, $sensors)
      Brief               : Function to get devices connected to the gateway       
      Input param         : Nil
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function getDevices($gatewayId, $coins, $sensors){
        $db       = new ConnectionManager();


	$sensor = '';
	if($sensors != "0"){
		$sen='';
		$arr = explode(',',$sensors);

		for($i=0; $i<sizeof($arr); $i++  ){
			$sen = "'" . $arr[$i] . "',";
			$sensor .= $sen;
		}

		$sensors = rtrim($sensor,',');
	}

        try {      
		if($coins == "0" && $sensors == "0"){	 
        		$sQuery = " SELECT gd.gd_id,gd.gateway_id,gd.device_mac_address,gd.nick_name,gd.coin_location,gd.device_id,gd.active,gd.is_blacklisted,d.d_id,d.device_type,d.device_value,d.get_current_value,d.threshold_value,d.format,d.rate_value,d.added_on,d.updated_on "
                	. " FROM gateway_devices as gd"
                	. " LEFT JOIN devices as d"                   
                	. " ON gd.device_id=d.device_id AND gd.gateway_id=d.gateway_id WHERE gd.gateway_id=:gateway_id AND gd.is_blacklisted='N' AND gd.is_deleted =0 AND d.is_deleted = 0 ORDER BY d.updated_on DESC";             
        	}elseif($coins != "0" && $sensors == "0"){
			$sQuery = " SELECT gd.gd_id,gd.gateway_id,gd.device_mac_address,gd.nick_name,gd.coin_location,gd.device_id,gd.active,gd.is_blacklisted,d.d_id,d.device_type,d.device_value,d.get_current_value,d.threshold_value,d.format,d.rate_value,d.added_on,d.updated_on "
                	. " FROM gateway_devices as gd"
                	. " LEFT JOIN devices as d"                   
                	. " ON gd.device_id=d.device_id AND gd.gateway_id=d.gateway_id WHERE gd.gateway_id=:gateway_id AND gd.is_blacklisted='N' AND gd.is_deleted =0 AND d.is_deleted = 0 AND gd.device_id IN ($coins) ORDER BY d.updated_on DESC";             
		}elseif($coins != "0" && $sensors != "0"){
			$sQuery = " SELECT gd.gd_id,gd.gateway_id,gd.device_mac_address,gd.nick_name,gd.coin_location,gd.device_id,gd.active,gd.is_blacklisted,d.d_id,d.device_type,d.device_value,d.get_current_value,d.threshold_value,d.format,d.rate_value,d.added_on,d.updated_on "
                	. " FROM gateway_devices as gd"
                	. " LEFT JOIN devices as d"                   
                	. " ON gd.device_id=d.device_id AND gd.gateway_id=d.gateway_id WHERE gd.gateway_id=:gateway_id AND gd.is_blacklisted='N' AND gd.is_deleted =0 AND d.is_deleted = 0 AND gd.device_id IN ($coins) AND d.device_type IN ($sensors) ORDER BY d.updated_on DESC";             
		}elseif($coins == "0" && $sensors != "0"){
			$sQuery = " SELECT gd.gd_id,gd.gateway_id,gd.device_mac_address,gd.nick_name,gd.coin_location,gd.device_id,gd.active,gd.is_blacklisted,d.d_id,d.device_type,d.device_value,d.get_current_value,d.threshold_value,d.format,d.rate_value,d.added_on,d.updated_on "
                	. " FROM gateway_devices as gd"
                	. " LEFT JOIN devices as d"                   
                	. " ON gd.device_id=d.device_id AND gd.gateway_id=d.gateway_id WHERE gd.gateway_id=:gateway_id AND gd.is_blacklisted='N' AND gd.is_deleted =0 AND d.is_deleted = 0 AND d.device_type IN ($sensors) ORDER BY d.updated_on DESC";             
		}



        $db->query($sQuery);        
        $db->bind(':gateway_id',$gatewayId);
        $res = $db->resultSet();
        
//        $finalResult = [];
        $finalResult['accelerometer']   = [];
        $finalResult['gyrosensor']      = [];
        $finalResult['temperature']     = [];
        $finalResult['humidity']        = [];
        $finalResult['stream']          = [];
        $finalResult['other']           = [];
	$finalResult['accStream']       = [];
	$finalResult['tempstream']          = [];
		$finalResult['humidstream']          = [];

        if(is_array($res) && !empty($res)){
            foreach ($res as $key => $value) {
                $device_ids = $value['device_id'];
                $gateway_ids = $value['gateway_id'];
                if($value['device_type'] == '01' || $value['device_type'] == '02'){
                    $sQuery = " SELECT * FROM device_settings WHERE device_sensor ='Accelerometer' AND gateway_id ='$gatewayId' AND device_id = '$device_ids'  ORDER BY ds_id DESC LIMIT 1";
                    $db->query($sQuery);
                    $sen_status = $db->single();
                    $res[$key]['sensor_status'] = $sen_status['sensor_active'];
                    $finalResult['accelerometer'][] = $res[$key];
                }
                elseif($value['device_type'] == '03' || $value['device_type'] == '04') {
                    $sQuery = " SELECT * FROM device_settings WHERE device_sensor ='Gyroscope' AND gateway_id ='$gatewayId' AND device_id = '$device_ids'  ORDER BY ds_id DESC LIMIT 1";
                    $db->query($sQuery);
                    $sen_status = $db->single();
                    $res[$key]['sensor_status'] = $sen_status['sensor_active'];
                    $finalResult['gyrosensor'][] = $res[$key];
                }
                elseif($value['device_type'] == '05' || $value['device_type'] == '06') {
                    $sQuery = " SELECT * FROM device_settings WHERE device_sensor ='Temperature' AND gateway_id ='$gatewayId' AND device_id = '$device_ids'  ORDER BY ds_id DESC LIMIT 1";
                    $db->query($sQuery);
                    $sen_status = $db->single();
                    $res[$key]['sensor_status'] = $sen_status['sensor_active'];
                    $finalResult['temperature'][] = $res[$key];
                }                
                elseif($value['device_type'] == '07' || $value['device_type'] == '08') {
                    $sQuery = " SELECT * FROM device_settings WHERE device_sensor ='Humidity' AND gateway_id ='$gatewayId' AND device_id = '$device_ids'  ORDER BY ds_id DESC LIMIT 1";
                    $db->query($sQuery);
                    $sen_status = $db->single();
                    $res[$key]['sensor_status'] = $sen_status['sensor_active'];
                    $finalResult['humidity'][] = $res[$key];
                }
                elseif($value['device_type'] == '09' || $value['device_type'] == '10') {
                    if($value['device_type'] == '09'){
                        $sQuery = " SELECT * FROM device_settings WHERE device_sensor ='Temperature Stream' AND gateway_id ='$gatewayId' AND device_id = '$device_ids'  ORDER BY ds_id DESC LIMIT 1";
                        $db->query($sQuery);
                        $sen_status = $db->single();
                        $res[$key]['temp_stream_sensor_status'] = $sen_status['sensor_active'];
                        $finalResult['tempstream'][] = $res[$key];
                    }else{
                        $tQuery = " SELECT * FROM device_settings WHERE device_sensor ='Humidity Stream' AND gateway_id ='$gatewayId' AND device_id = '$device_ids'  ORDER BY ds_id DESC LIMIT 1";
                        $db->query($tQuery);
                        $sen_status = $db->single();
                        $res[$key]['humid_stream_sensor_status'] = $sen_status['sensor_active'];
                        $finalResult['humidstream'][] = $res[$key];
                    }
                    // $finalResult['stream'][] = $res[$key];
        }
	elseif($value['device_type'] == '12' || $value['device_type'] == '14' || $value['device_type'] == '15') {
                    $sQuery = " SELECT * FROM device_settings WHERE device_sensor ='Accelerometer Stream' AND gateway_id ='$gatewayId' AND device_id = '$device_ids'  ORDER BY ds_id DESC LIMIT 1";
                    $db->query($sQuery);
                    $sen_status = $db->single();
                    $res[$key]['accelerometer_stream_sensor_status'] = $sen_status['sensor_active'];
            		$finalResult['accStream'][] = $res[$key];
       		 }

	else
        {
                    $finalResult['other'][] = $res[$key]; 
                }
                
            }
        }
//            print_r($finalResult);exit();
            $aList[JSON_TAG_RESULT]= $finalResult;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }
        
      /*
      Function            : getDeviceValue($gatewayId,$deviceId)
      Brief               : Function to get each device value       
      Input param         : Nil
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function getDeviceValue($gatewayId,$deviceId){
        $db       = new ConnectionManager();
        $curl     = new CurlRequest();
        try {      
            $url       =  URL_END_POINT.'/'.$gatewayId.'/'.$deviceId.'/connect';
//            print_r($url);exit();
            $resp      =  $curl->getRequestData($url);
            
            $data      =  json_decode($resp,true);
            
            
            if(isset($data['result']) && $data['result']=TRUE){
                $result['device_status'] = TRUE; 
                 
                $urlVal       =  URL_END_POINT.'/'.$gatewayId.'/'.$deviceId.'/read/24';
                $respVal      =  $curl->getRequestData($urlVal);
                $dataVal      =  json_decode($respVal,true);
                if(isset($dataVal['result']) && $dataVal['result']=TRUE){
                    $result['device_value']         = $dataVal['value'];
                }
                else{
                    $result['device_value']         = '';
                }
                
                 
            }
            else{
                $result['device_status'] = FALSE; 
                $result['device_value']  = '';
            }
                
                                    
            $aList[JSON_TAG_RESULT]= $result;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }
        
    
    /*
      Function            : setDeviceThreshold($inData)
      Brief               : Function to set Threshold
      Details             : Function to set Threshold
      Input param         : Nil
      Input/output param  : Nil
      Return              : Returns array.
     */
    function setDeviceThreshold($inData) {
        
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();
        try {
            
            $gatewayId       =     $inData[JSON_TAG_GATEWAY_ID];
            $deviceId        =     $inData[JSON_TAG_DEVICE_ID];
            
            $device_type     =     $inData[JSON_TAG_DEVICE_TYPE];
            $device_value    =     $inData[JSON_TAG_DEVICE_VALUE];
            
            $threshold_value       =     $inData[JSON_TAG_THRESHOLD];
            

            // Check Mandatory field    
            if (empty($gatewayId) || empty($deviceId)) {
                $aList[JSON_TAG_STATUS] = 2;
                return $aList;
            }

            //Check Gateway exusts or not
            $rowGateway = $this->checkGateway($db, $gatewayId);
            if (empty($rowGateway)) {
                $aList[JSON_TAG_STATUS] = 3;
                return $aList;
            }
            
            //Check Device Gateway Exists or not
            $rowDeviceGateway = $this->checkDeviceGateway($db, $deviceId, $gatewayId, $device_type);
            if (empty($rowDeviceGateway)) {
                $aList[JSON_TAG_STATUS] = 4;
                return $aList;
            }            

		if($device_type == '01' || $device_type == '02')
		{

			if($threshold_value == 0.001)
				$threshold_value = 1;
			else if($threshold_value == 0.1)
				$threshold_value = 2;
			else if($threshold_value > 0.001 && $threshold_value < 0.1)
				$threshold_value = 1;
			else if($threshold_value > 0.1 && $threshold_value < 0.375)
				$threshold_value = 2;
			else if($threshold_value >= 0.375)
				$threshold_value = ceil($threshold_value * 8);
		}
		if($device_type == '03' || $device_type == '04')
		{
            $thresholdJsonData = array(
                "2" => "2020",
                "3" => "2030",
                "4" => "2040",
                "5" => "2050",
                "6" => "2060",
                "7" => "2070",
                "8" => "2080",
                "9" => "2090"
            );

            if (array_key_exists($realHexThreshold, $thresholdJsonData)) {
                // Access the value associated with the key
                $threshold_value = $thresholdJsonData[$realHexThreshold] / 10;
                echo "The threshold_value is: " . $threshold_value;
            }else {
                $threshold_value = $threshold_value / 10;
            }
		}
                if($device_type == '05' || $device_type == '06')
		{
			if($threshold_value < 0){
				
				$threshold_value = abs($threshold_value) + 126;
			}
			if($threshold_value == 0){
				
				$threshold_value = 126;
			}
		}

                //convert dec to hex
                $threshold_value    = dechex($threshold_value);
                

                //append 0 if less than 10
               if($threshold_value<=9){
                    $threshold_value = sprintf("%02s", $threshold_value);
                    $threshold_value = strtoupper($threshold_value);
                } 

		$pendingRequest = $this->checkPendingRequest($db, $gatewayId, $deviceId, $device_type, 'SET');

		if(empty($pendingRequest)){
			$GatewayMacID = $this->getGatewayMacID($db, $gatewayId);
			if(!empty($GatewayMacID)){
				$gateway_mac_id = $GatewayMacID[0]['gateway_mac_id'];

				$dynamicID = $this->getDynamicID($db, $gatewayId, $deviceId);
				$dynamic_id = $dynamicID[0][dynamic_id];

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

				 		

			$query = "INSERT INTO request_list(gateway_id, gateway_mac_id, device_id, device_type, request_type, threshold, dynamic_id, action) "
                       		. "VALUES (:gatewayId, :gateway_mac_id, :deviceId, :device_type, 'SET', :threshold, :dynamic_id, 0)";
        		
			$db->query($query);
        	$db->bind(':gatewayId', $gatewayId);
        	$db->bind(':gateway_mac_id', $gateway_mac_id);
			$db->bind(':deviceId', $deviceId);
			$db->bind(':device_type', $device_type);
			$db->bind(':threshold', $threshold_value);
			// $db->bind(':dynamic_id', $dynamic_id);
            $db->bind(':dynamic_id', !empty($dynamic_id) ? $dynamic_id : $deviceId);

			if($db->execute()){
				$response = 'TRUE';
			}

			$aList[JSON_TAG_STATUS] = 0;
			}
			else{
				$aList[JSON_TAG_STATUS] = 1;

			}
		}
		else{
			$response = 'TRUE';
			$aList[JSON_TAG_STATUS] = 5;
		}


            $aList[JSON_TAG_RESULT] = $response;
            
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }

    /*
      Function            : setDeviceStream($inData)
      Brief               : Function to set Stream
      Details             : Function to set Stream
      Input param         : Nil
      Input/output param  : Nil
      Return              : Returns array.
     */
    function setStream($inData) {
        

        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();
        try {
            
            $gatewayId       =     $inData[JSON_TAG_GATEWAY_ID];
            $deviceId        =     $inData[JSON_TAG_DEVICE_ID];
            
            $device_type     =     $inData[JSON_TAG_DEVICE_TYPE];
            $format          =     $inData[JSON_TAG_FORMAT];
            
            $rate_value       =     $inData[JSON_TAG_RATE_VALUE];
            

            // Check Mandatory field   
            if (empty($gatewayId) || empty($deviceId) || empty($rate_value)) {
                $aList[JSON_TAG_STATUS] = 2;
                return $aList;
            }

            //Check Gateway exists or not
            $rowGateway = $this->checkGateway($db, $gatewayId);
            if (empty($rowGateway)) {
                $aList[JSON_TAG_STATUS] = 3;
                return $aList;
            }
            
            //Check Device Gateway Exists or not
            $rowDeviceGateway = $this->checkDeviceGateway($db, $deviceId, $gatewayId, $device_type);
            if (empty($rowDeviceGateway)) {
                $aList[JSON_TAG_STATUS] = 4;
                return $aList;
            }    


		//convert dec to hex
		$format        = dechex($format);

	if($device_type == '12'){
		if($format == 16){
			//Check the stream rate to compare
            		$rowDetectionPeriod = $this->checkDetectionPeriod($db, $deviceId, $gatewayId);

            		if(!empty($rowDetectionPeriod)) {
				$det_period_rate = $rowDetectionPeriod[rate_value];
				$det_period_rate = hexdec($det_period_rate);
				
				if($rate_value <= $det_period_rate){
                			$aList[JSON_TAG_STATUS] = 6;
                			return $aList;
				}

				if($rate_value < ($det_period_rate * 10)){
					$aList[JSON_TAG_STATUS] = 6;
                			return $aList;
				}
            		} 
		}  

	}


	if($device_type == '51'){
		$coinFirmware = $this->getCoinFirmwareType($db, $gatewayId, $deviceId);
		$firmware_type = $coinFirmware[0][firmware_type];
		if($firmware_type == 'Predictive Maintenance'){
			$device_type = '71';
		}
	}

		$rate_value    = dechex($rate_value);
		
		if($rate_value<=9){
                    $rate_value = sprintf("%02s", $rate_value);
                    $rate_value = strtoupper($rate_value);
                } 

		$pendingRequest = $this->checkPendingRequest($db, $gatewayId, $deviceId, $device_type, 'SET');

		if(empty($pendingRequest)){       
			$GatewayMacID = $this->getGatewayMacID($db, $gatewayId);
			if(!empty($GatewayMacID)){
					$gateway_mac_id = $GatewayMacID[0]['gateway_mac_id'];

				$dynamicID = $this->getDynamicID($db, $gatewayId, $deviceId);
				$dynamic_id = $dynamicID[0][dynamic_id];

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

				
				$query = "INSERT INTO request_list(gateway_id, gateway_mac_id, device_id, device_type, request_type, format, rate_value, dynamic_id, action) "
                       		. "VALUES (:gatewayId, :gateway_mac_id, :deviceId, :device_type, 'SETS', :format, :rate_value, :dynamic_id, 0)";
        		
			$db->query($query);
        		$db->bind(':gatewayId', $gatewayId);
        		$db->bind(':gateway_mac_id', $gateway_mac_id);
			$db->bind(':deviceId', $deviceId);
			$db->bind(':device_type', $device_type);
			$db->bind(':format', $format);
			$db->bind(':rate_value', $rate_value);
			$db->bind(':dynamic_id', $dynamic_id);


			if($db->execute()){
				$response = 'TRUE';
			}

				$aList[JSON_TAG_STATUS] = 0;
			}
			else{
				$aList[JSON_TAG_STATUS] = 1;
			}
		}
		else{
			$response = 'TRUE';
			$aList[JSON_TAG_STATUS] = 5;
		}

            $aList[JSON_TAG_RESULT] = $response;

        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }
        
    /*
      Function            : getCurrentValue($inData)
      Brief               : Function to get Current Value
      Details             : Function to get Current Value
      Input param         : Nil
      Input/output param  : Nil
      Return              : Returns array.
     */
    function getCurrentValue($inData) {

        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();
        try {
            
            $gatewayId       =     $inData[JSON_TAG_GATEWAY_ID];
            $deviceId        =     $inData[JSON_TAG_DEVICE_ID];
            
            $device_type     =     $inData[JSON_TAG_DEVICE_TYPE];

            //Check Mandatory field    
            if (empty($gatewayId) || empty($deviceId) || empty($device_type)) {
                $aList[JSON_TAG_STATUS] = 2;
                return $aList;
            }

            //Check Gateway exusts or not
            $rowGateway = $this->checkGateway($db, $gatewayId);
            if (empty($rowGateway)) {
                $aList[JSON_TAG_STATUS] = 3;
                return $aList;
            }
            
            //Check Device Gateway Exists or not
            $rowDeviceGateway = $this->checkDeviceGateway($db, $deviceId, $gatewayId, $device_type);
            if (empty($rowDeviceGateway)) {
                $aList[JSON_TAG_STATUS] = 4;
                return $aList;
            }            

		$pendingRequest = $this->checkPendingRequest($db, $gatewayId, $deviceId, $device_type, 'GET');

		if(empty($pendingRequest)){
			$GatewayMacID = $this->getGatewayMacID($db, $gatewayId);
			if(!empty($GatewayMacID)){
				$gateway_mac_id = $GatewayMacID[0]['gateway_mac_id'];

				$dynamicID = $this->getDynamicID($db, $gatewayId, $deviceId);
				$dynamic_id = $dynamicID[0][dynamic_id];

                	$data_arr  =  array();
			$data_arr['gateway_mac_id']    =  $gateway_mac_id;
                	$data_arr['gateway_id']    =  $gatewayId;
                	$data_arr['device_id']     =  $deviceId;
                	$data_arr['device_type']   =  $device_type;      
                    		$data_arr['dynamic_id']     =  $dynamic_id;                                  

                	$data_arr['topic']         =  TOPIC_GET_CURRENTVALUE;

                	$url        =   URL_END_POINT.'sensegiz-mqtt/publish.php';

                	$response   =  $curl->postRequestData($url, $data_arr);

			$query = "INSERT INTO request_list(gateway_id, gateway_mac_id, device_id, device_type, request_type, dynamic_id, action) "
                       	. "VALUES (:gatewayId, :gateway_mac_id, :deviceId, :device_type, 'GET', :dynamic_id, 0)";
        		
			$db->query($query);
        		$db->bind(':gatewayId', $gatewayId);
        		$db->bind(':gateway_mac_id', $gateway_mac_id);
			$db->bind(':deviceId', $deviceId);
			$db->bind(':device_type', $device_type);
			$db->bind(':dynamic_id', $dynamic_id);
			
			if($db->execute()){
				$response = 'TRUE';
			}

			$aList[JSON_TAG_STATUS] = 0;
			}
			else{
				$aList[JSON_TAG_STATUS] = 1;

			}
		}
		else{
			$response = 'TRUE';
			$aList[JSON_TAG_STATUS] = 5;
		}

            $aList[JSON_TAG_RESULT] = $response;
            
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }
   

function checkPendingRequest($db, $gatewayId, $deviceId, $device_type, $req_type)
{
	$curl     = new CurlRequest();

	$sQuery = " SELECT *"
               . " FROM request_list"                    
                . " WHERE gateway_id = :gatewayId AND device_id = :deviceId AND action = 0";       
        
       	$db->query($sQuery);         
	$db->bind(':gatewayId', $gatewayId);
	$db->bind(':deviceId', $deviceId);
	//$db->bind(':req_type', $req_type);

       	$row = $db->single();

	if(!empty($row))
	{
		$requestType = $row['request_type'];
		$re_sent = $row['resent'];

		$currentTime = date("Y-m-d H:i:s");
		$requestSentTime = $row['published_on'];
		$seconds = strtotime($currentTime) - strtotime($requestSentTime);

		if($requestType == 'SET' && $seconds >= 180){
			$uQuery = " UPDATE request_list "
                        	. " SET action=2,updated_on=DATE_TRUNC('second', NOW())"
                        	. " WHERE action=0 AND gateway_id = :gatewayId AND device_id = :deviceId AND request_type = 'SET'";
			$db->query($uQuery);
			$db->bind(':gatewayId', $gatewayId);
			$db->bind(':deviceId', $deviceId);
			$db->execute();
		}
		if($requestType == 'SETS' && $seconds >= 180){
			$uQuery = " UPDATE request_list "
                        	. " SET action=2,updated_on=DATE_TRUNC('second', NOW())"
                        	. " WHERE action=0 AND gateway_id = :gatewayId AND device_id = :deviceId AND request_type = 'SETS'";
			$db->query($uQuery);
			$db->bind(':gatewayId', $gatewayId);
			$db->bind(':deviceId', $deviceId);
			$db->execute();
		}
		if($requestType == 'EnD' && $seconds >= 180){
			$uQuery = " UPDATE request_list "
                        	. " SET action=2,updated_on=DATE_TRUNC('second', NOW())"
                        	. " WHERE action=0 AND gateway_id = :gatewayId AND device_id = :deviceId AND request_type = 'EnD'";
			$db->query($uQuery);
			$db->bind(':gatewayId', $gatewayId);
			$db->bind(':deviceId', $deviceId);
			$db->execute();
		}

		if($requestType == 'GET' && $seconds >= 300){
			$uQuery = " UPDATE request_list "
                        	. " SET action=2,updated_on=DATE_TRUNC('second', NOW())"
                        	. " WHERE action=0 AND gateway_id = :gatewayId AND device_id = :deviceId AND request_type = 'GET'";
			$db->query($uQuery);
			$db->bind(':gatewayId', $gatewayId);
			$db->bind(':deviceId', $deviceId);
			$db->execute();
		}
		if($requestType == 'DETP' && $seconds >= 180){
			$uQuery = " UPDATE request_list "
                        	. " SET action=2,updated_on=DATE_TRUNC('second', NOW())"
                        	. " WHERE action=0 AND gateway_id = :gatewayId AND device_id = :deviceId AND request_type = 'DETP'";
			$db->query($uQuery);
			$db->bind(':gatewayId', $gatewayId);
			$db->bind(':deviceId', $deviceId);
			$db->execute();
		}
		if($requestType == 'FREQ' && $seconds >= 180){
			$uQuery = " UPDATE request_list "
                        	. " SET action=2,updated_on=DATE_TRUNC('second', NOW())"
                        	. " WHERE action=0 AND gateway_id = :gatewayId AND device_id = :deviceId AND request_type = 'FREQ'";
			$db->query($uQuery);
			$db->bind(':gatewayId', $gatewayId);
			$db->bind(':deviceId', $deviceId);
			$db->execute();
		}

	}


	$rQuery = " SELECT *"
               . " FROM request_list"                    
                . " WHERE gateway_id = :gatewayId AND device_id = :deviceId AND action = 0";       
        
       	$db->query($rQuery);         
	$db->bind(':gatewayId', $gatewayId);
	$db->bind(':deviceId', $deviceId);
       	return  $res = $db->single();

}

      /*
      Function            : getGatewaySettings()
      Brief               : Function to get Gateways Settings     
      Input param         : $userId
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function getGatewaySettings($userId){
        $db       = new ConnectionManager();
        $curl     = new CurlRequest();
        try {      
            $sQuery = " SELECT *"
                    . " FROM user_gateways"                    
                    . " WHERE user_id=:user_id AND is_deleted =0 AND is_blacklisted='N' ORDER BY ug_id DESC";       

            $db->query($sQuery);  
            $db->bind(':user_id',$userId);
            $row = $db->resultSet();
            $finalArr  = [];
            if(!empty($row)){
                foreach ($row as $key => $value) {
                    $gatewayId  =  $value['gateway_id'];
		    $gatewayNickName = $value['gateway_nic_name'];
                    $finalArr[$key]['gateway_id'] = $gatewayId;
                    $finalArr[$key]['gateway_nick_name'] = $gatewayNickName;
                    $finalArr[$key]['settings']   = $this->getSettings($db,$gatewayId);
                }
            }
//            print_r($finalArr);
//            exit();
            
            $aList[JSON_TAG_RESULT]= $finalArr;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }    
     
    //Get Gateway settings
    function getSettings($db, $gatewayId){
            $sQuery = " SELECT *"
                    . " FROM gateway_settings"                    
                    . " WHERE gateway_id=:gateway_id AND is_deleted =0 ORDER BY s_id ASC";       

            $db->query($sQuery);  
            $db->bind(':gateway_id',$gatewayId);
            $row = $db->resultSet();
            
            return $row;
    }
    
    /*
      Function            : updateSmsNotification($inData)
      Brief               : Function to Update SMS Notification
      Details             : Function to Update SMS Notification
      Input param         : Nil
      Input/output param  : Nil
      Return              : Returns array.
     */
    function updateSmsNotification($inData) {
        

        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();
        try {
            
            $gatewayId       =     $inData[JSON_TAG_GATEWAY_ID];
            $sms_alert       =     $inData[JSON_TAG_SMS_ALERT];            
            $sensor_type     =     $inData[JSON_TAG_SENSOR_TYPE];
            
            // Check Mandatory field    
            if (empty($gatewayId) || empty($sms_alert) || empty($sensor_type)) {
                $aList[JSON_TAG_STATUS] = 2;
                return $aList;
            }
            
            $response = FALSE;
            //Check Gateway Settings Exists or not
            $rowGatewaySettings = $this->checkGatewaySettingsExists($db, $gatewayId, $sensor_type);
            if (empty($rowGatewaySettings)) {
                //Insert newly
                $query = "INSERT INTO gateway_settings(gateway_id, sensor_type, sms_alert, added_on, updated_on) "
                        . "VALUES (:gateway_id, :sensor_type, :sms_alert, NOW(), NOW())";
                $db->query($query);
                $db->bind(':gateway_id', $gatewayId);
                $db->bind(':sensor_type', $sensor_type);
                $db->bind(':sms_alert', $sms_alert);
                if($db->execute()){
                    $response = TRUE;
                }               
                
            }      
            else{
                //Update existed
                $sQuery = " UPDATE gateway_settings "
                        . " SET sms_alert=:sms_alert,updated_on=DATE_TRUNC('second', NOW())"
                        . " WHERE gateway_id=:gateway_id AND sensor_type=:sensor_type AND is_deleted =0";
                $db->query($sQuery);
                $db->bind(':gateway_id', $gatewayId);
                $db->bind(':sensor_type', $sensor_type);
                $db->bind(':sms_alert', $sms_alert);
                if($db->execute()){
                    $response = TRUE;
                }                
            }


            $aList[JSON_TAG_RESULT] = $response;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }
    
    //To check Gateway Settings Exists or not
    function checkGatewaySettingsExists($db, $gatewayId, $sensor_type){
        
        $sQuery = " SELECT *"
                . " FROM gateway_settings"                    
                . " WHERE sensor_type=:sensor_type AND gateway_id=:gateway_id AND is_deleted =0 ORDER BY s_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':sensor_type',$sensor_type);
        $db->bind(':gateway_id',$gatewayId);
        
        return  $row = $db->single();
    }     
 
    /*
      Function            : updateEmailNotification($inData)
      Brief               : Function to Update EMAIL Notification
      Details             : Function to Update EMAIL Notification
      Input param         : Nil
      Input/output param  : Nil
      Return              : Returns array.
     */
    function updateEmailNotification($inData) {
        

        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();
        try {
            
            $gatewayId       =     $inData[JSON_TAG_GATEWAY_ID];
            $email_alert     =     $inData[JSON_TAG_EMAIL_ALERT];            
            $sensor_type     =     $inData[JSON_TAG_SENSOR_TYPE];
            
            // Check Mandatory field    
            if (empty($gatewayId) || empty($email_alert) || empty($sensor_type)) {
                $aList[JSON_TAG_STATUS] = 2;
                return $aList;
            }
            
            $response = FALSE;
            //Check Gateway Settings Exists or not
            $rowGatewaySettings = $this->checkGatewaySettingsExists($db, $gatewayId, $sensor_type);
            if (empty($rowGatewaySettings)) {
                //Insert newly
                $query = "INSERT INTO gateway_settings(gateway_id, sensor_type, email_alert) "
                        . "VALUES (:gateway_id, :sensor_type, :email_alert)";
                $db->query($query);
                $db->bind(':gateway_id', $gatewayId);
                $db->bind(':sensor_type', $sensor_type);
                $db->bind(':email_alert', $email_alert);
                if($db->execute()){
                    $response = TRUE;
                }               
                
            }      
            else{
                //Update existed
                $sQuery = " UPDATE gateway_settings "
                        . " SET email_alert=:email_alert,updated_on=DATE_TRUNC('second', NOW())"
                        . " WHERE gateway_id=:gateway_id AND sensor_type=:sensor_type AND is_deleted =0";
                $db->query($sQuery);
                $db->bind(':gateway_id', $gatewayId);
                $db->bind(':sensor_type', $sensor_type);
                $db->bind(':email_alert', $email_alert);
                if($db->execute()){
                    $response = TRUE;
                }                
            }


            $aList[JSON_TAG_RESULT] = $response;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }

       
      /*
      Function            : getNotificationEmailIds()
      Brief               : Function to get user notification emails   
      Input param         : Nil
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function getNotificationEmailIds($userId){
        $db       = new ConnectionManager();
        $curl     = new CurlRequest();
        try {      
            $sQuery = " SELECT *"
                    . " FROM notification_emails"                    
                    . " WHERE user_id=:user_id AND is_deleted =0 ORDER BY id DESC";       

            $db->query($sQuery);  
            $db->bind(':user_id',$userId);
            $row = $db->resultSet();
            
            $aList[JSON_TAG_RESULT]= $row;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }

    /*
      Function            : addNotificationEmailIds($userId,$inData)
      Brief               : Function to Add Email ids
      Details             : Function to Add Email ids
      Input param         : Nil
      Input/output param  : Nil
      Return              : Returns array.
     */
    function addNotificationEmailIds($userId,$inData) {
        

        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();
        try {
            
            $notification_email       =     $inData[JSON_TAG_NOTIFICATION_EMAIL];
            $Id                       =     $inData[JSON_TAG_ID];
            

            //Check Mandatory field    
            if (empty($notification_email) && $Id !='') {
                $aList[JSON_TAG_STATUS] = 2;
                return $aList;
            }
            
            //Check email already added or not
            $rowMail  =  $this->isNotificationEmailExists($db,$userId,$notification_email);
            
              if($Id>0){
                    if (!empty($rowMail) && $id!=$rowMail['id']) {
                        $aList[JSON_TAG_STATUS] = 3;
                        return $aList;
                    }                  
              }
              else{
                    if (!empty($rowMail)) {
                        $aList[JSON_TAG_STATUS] = 3;
                        return $aList;
                    }                  
              }
            

            $rowId  =   '';
            if($Id>0){
                $sQuery = " UPDATE notification_emails "
                        . " SET notification_email=:notification_email,updated_on=DATE_TRUNC('second', NOW())"
                        . " WHERE id=:id AND user_id=:user_id";
                $db->query($sQuery);
                $db->bind(':notification_email', $notification_email);
                $db->bind(':id', $Id);
                $db->bind(':user_id', $userId);
                $db->execute(); 
                
                $rowId  = $Id;
            }
            else{
                $query = "INSERT INTO notification_emails(user_id, notification_email) "
                        . "VALUES (:user_id, :notification_email)";
                $db->query($query);
                $db->bind(':user_id', $userId);
                $db->bind(':notification_email', $notification_email);                   
                
                 $res = $db->resultSet();    
                $rowId  = $res[0][id];

            }
            
            //Check Notification email
            $row = $this->checkNotificationEmailId($db, $rowId);
            

            $aList[JSON_TAG_RESULT] = $row;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }
    
    //To check notification email id
    function checkNotificationEmailId($db, $rowId){
        
        $sQuery = " SELECT *"
                . " FROM notification_emails"                    
                . " WHERE id=:id AND is_deleted =0";       
         
        $db->query($sQuery);
        $db->bind(':id',$rowId);
        
        return  $row = $db->single();
    } 
    
    //To check notification email exists or not
    function isNotificationEmailExists($db,$userId,$notification_email){
        $sQuery = " SELECT *"
                . " FROM notification_emails"                    
                . " WHERE user_id=:user_id AND notification_email=:notification_email AND is_deleted =0";       
         
        $db->query($sQuery);
        $db->bind(':user_id',$userId);
        $db->bind(':notification_email', $notification_email); 
        
        return  $row = $db->single();    
    }    
    
      /*
      Function            : deleteNotificationEmailIds($userId,$Id)
      Brief               : Function to delete notification emails 
      Input param         : Nil
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function deleteNotificationEmailIds($userId,$Id){
        $db       = new ConnectionManager();
        $curl     = new CurlRequest();
        try {      
            $sQuery = " DELETE FROM notification_emails"              
                    . " WHERE user_id=:user_id AND id=:id";       
            $res = FALSE;
            
            $db->query($sQuery);  
            $db->bind(':user_id',$userId);
            $db->bind(':id',$Id);
            if($db->execute()){
                $res = TRUE;
            }
            
            $aList[JSON_TAG_RESULT] = $res;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }    
        
      /*
      Function            : getNotificationPhone()
      Brief               : Function to get user notification phone   
      Input param         : Nil
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function getNotificationPhone($userId){
        $db       = new ConnectionManager();
        $curl     = new CurlRequest();
        try {      
            $sQuery = " SELECT *"
                    . " FROM notification_phone"                    
                    . " WHERE user_id=:user_id AND is_deleted =0 ORDER BY id DESC";       

            $db->query($sQuery);  
            $db->bind(':user_id',$userId);
            $row = $db->resultSet();
            
            $aList[JSON_TAG_RESULT]= $row;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }
    
    /*
      Function            : addNotificationPhone($userId,$inData)
      Brief               : Function to Add Phone
      Details             : Function to Add Phone
      Input param         : Nil
      Input/output param  : Nil
      Return              : Returns array.
     */
    function addNotificationPhone($userId,$inData) {
        

        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();
        try {
            
            $notification_phone       =     $inData[JSON_TAG_NOTIFICATION_PHONE];
            $Id                       =     $inData[JSON_TAG_ID];
                        
            //Check Mandatory field    
            if (empty($notification_phone) && $Id !='') {
                $aList[JSON_TAG_STATUS] = 2;
                return $aList;
            }
                        
            //Check phone already added or not
            $rowPhone  =  $this->isNotificationPhoneExists($db,$userId,$notification_phone);
             
            if($Id>0){
                if (!empty($rowPhone) && $Id!=$rowPhone['id']) {
                    $aList[JSON_TAG_STATUS] = 3;
                    return $aList;
                } 
            }
            else{
                if (!empty($rowPhone)) {
                    $aList[JSON_TAG_STATUS] = 3;
                    return $aList;
                }                 
            }

            $rowId  =   '';
            if($Id>0){
                $sQuery = " UPDATE notification_phone "
                        . " SET notification_phone=:notification_phone,updated_on=DATE_TRUNC('second', NOW())"
                        . " WHERE id=:id AND user_id=:user_id";
                $db->query($sQuery);
                $db->bind(':notification_phone', $notification_phone);
                $db->bind(':id', $Id);
                $db->bind(':user_id', $userId);
                $db->execute(); 
                
                $rowId  = $Id;
            }
            else{
                $query = "INSERT INTO notification_phone(user_id, notification_phone) "
                        . "VALUES (:user_id, :notification_phone)";                  
                $db->query($query);
                $db->bind(':user_id', $userId);
                $db->bind(':notification_phone', $notification_phone);                
                $db->execute();    
                
                $rowId  = $db->lastInsertId();
            }
//            exit();
            //Check Notification phone
            $row = $this->checkNotificationPhone($db, $rowId);
            

            $aList[JSON_TAG_RESULT] = $row;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }    
    
    //To check notification phone
    function checkNotificationPhone($db, $rowId){
        
        $sQuery = " SELECT *"
                . " FROM notification_phone"                    
                . " WHERE id=:id AND is_deleted =0";       
         
        $db->query($sQuery);
        $db->bind(':id',$rowId);
        
        return  $row = $db->single();
    } 
    
    //To check notification phone exists or not
    function isNotificationPhoneExists($db,$userId,$notification_phone){
        $sQuery = " SELECT *"
                . " FROM notification_phone"                    
                . " WHERE user_id=:user_id AND notification_phone=:notification_phone AND is_deleted =0";       
         
        $db->query($sQuery);
        $db->bind(':user_id',$userId);
        $db->bind(':notification_phone', $notification_phone); 
        
        return  $row = $db->single();    
    }    
        
      /*
      Function            : deleteNotificationPhone($userId,$Id)
      Brief               : Function to delete notification phone 
      Input param         : Nil
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function deleteNotificationPhone($userId,$Id){
        $db       = new ConnectionManager();
        $curl     = new CurlRequest();
        try {      
            $sQuery = " DELETE FROM notification_phone"              
                    . " WHERE user_id=:user_id AND id=:id";       
            $res = FALSE;
            
            $db->query($sQuery);  
            $db->bind(':user_id',$userId);
            $db->bind(':id',$Id);
            if($db->execute()){
                $res = TRUE;
            }
            
            $aList[JSON_TAG_RESULT] = $res;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }    
            
/*
      Function            : createLocation($userId,$inData)
      Brief               : Function to Create User Location
      Details             : Function to Create User Location
      Input param         : Nil
      Input/output param  : Nil
      Return              : Returns array.
     */
    function createLocation($userId,$inData,$lat,$long) {
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();
        try {
            $location_name       =     $inData[JSON_TAG_LOCATION_NAME];
        $location_description = $inData[JSON_TAG_LOCATION_DESCRIPTION];
        $location_image = $inData[JSON_TAG_LOCATION_IMAGE];
        $gateway_list = $inData[JSON_TAG_GATEWAY_ID];
        $Id = $inData[JSON_TAG_ID]; 

            //Check Mandatory field    
            if (empty($location_name) && empty($location_description) && empty($location_image) && empty($gateway_list) && $Id !='') {
                $aList[JSON_TAG_STATUS] = 2;
                return $aList;
            }

	//Check if any of the gateway selected has no coins
	$noCoinGateway = $this->checkNoCoinGateway($db, $gateway_list);
		
	if(empty($noCoinGateway)) {
            	$aList[JSON_TAG_STATUS] = 5;
		$aList[JSON_TAG_RESULT] = $noCoinGateway;
            	return $aList;            
        }

	$rowGateway=$this->isGatewayAdded($db, $gateway_list);
        if(!empty($rowGateway)) {
            $aList[JSON_TAG_STATUS] = 4;
            $a = TRUE;
        }
            
            //Check location already added or not
            $rowLocation  =  $this->isLocationExists($db,$userId,$location_name, $location_description, $location_image, $gateway_list);
            if($Id>0){
                    if (!empty($rowLocation) && $id!=$rowLocation['id']) {
                        $aList[JSON_TAG_STATUS] = 3;
                        return $aList;
                    }                  
              }
              else{
                    if (!empty($rowLocation)) {
                        $aList[JSON_TAG_STATUS] = 3;
                        return $aList;
                    }                  
              }
            $rowId  =   '';

            if($a != TRUE) {
                if($Id>0){
                    $sQuery = " UPDATE user_locations "
                            . " SET location_name=:location_name,location_description=:location_description,location_image=:location_image,updated_on=DATE_TRUNC('second', NOW())"
                            . " WHERE id=:id AND user_id=:user_id";
                    $db->query($sQuery);
                    $db->bind(':location_name', $location_name);
                    $db->bind(':location_description', $location_description);
                    $sb->bind(':location_image', $location_image);
                    $db->bind(':id', $Id);
                    $db->bind(':user_id', $userId);
                    $db->execute(); 
                
                $rowId  = $Id;
            }
            else{
                $query = "INSERT INTO user_locations(user_id, location_name, location_description, location_image,latitude,longitude) "
                        . "VALUES (:user_id, :location_name, :location_description, :location_image,:latitude,:longitude)  RETURNING id";
                $db->query($query);
                $db->bind(':user_id', $userId);
                $db->bind(':location_name', $location_name);                
            	$db->bind(':location_description', $location_description);
            	$db->bind(':location_image', $location_image);
                $db->bind(':latitude', $lat);
                $db->bind(':longitude', $long );
                $res = $db->resultSet();    
                $rowId  = $res[0][id];

                    foreach($gateway_list as $key => $value) {

                        $gateway_id = $value;
                        $gQuery = "INSERT INTO location_gateway(location_id, gateway_id)"
                                ." VALUES (:location_id, :gateway_id)";
                        $db->query($gQuery);
                        $db->bind(':gateway_id', $gateway_id);
                        $db->bind(':location_id', $rowId);
                        $db->execute();
                    }
                }

                            //Check Location
            $row = $this->checkLocation($db, $rowId);
            

            $aList[JSON_TAG_RESULT] = $row;
            $aList[JSON_TAG_STATUS] = 0;
		}
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }
    
//Check if gateway has any coins or no
function checkNoCoinGateway($db, $gateway_list)
{
	$gateway = [];

	foreach($gateway_list as $key => $value) {
        	$gateway_id = $value;

        	$aQuery = "SELECT distinct gateway_id"
            		." FROM gateway_devices"
            		." WHERE is_deleted = 0 AND is_blacklisted='N' AND gateway_id=(SELECT gateway_id FROM user_gateways WHERE ug_id=:gateway_id AND is_deleted = 0)";
        	$db->query($aQuery);
        	$db->bind(':gateway_id', $gateway_id);
        	$res=$db->single();

		if(!empty($res)){
	        	array_push($gateway, $res['gateway_id']);
		}
    	}

	return $gateway;

}


//To check gateway assigned to location or not
    function isGatewayAdded($db, $gateway_list) {

    $gateway = [];

    foreach($gateway_list as $key => $value) {
        $gateway_id = $value;
        $aQuery = "SELECT location_id"
            ." FROM location_gateway"
            ." WHERE gateway_id=:gateway_id";
        $db->query($aQuery);
        $db->bind(':gateway_id', $gateway_id);
        $res=$db->single();
	if(!empty($res)){
	        array_push($gateway, $res[location_id]);
	}
    }

    return $gateway;

    }

    //To check Location Details
    function checkLocation($db, $rowId){
        
        $sQuery = " SELECT *"
                . " FROM user_locations"                    
                . " WHERE id=:id AND is_deleted =0";       
         
        $db->query($sQuery);
        $db->bind(':id',$rowId);
        
        return  $row = $db->single();
    } 
    
    //To check location exists or not
    function isLocationExists($db,$userId,$location_name, $location_description, $location_image){
        $sQuery = " SELECT *"
                . " FROM user_locations"                    
                . " WHERE user_id=:user_id AND location_name=:location_name AND location_description=:location_description AND location_image=:location_image AND is_deleted =0";       
         
        $db->query($sQuery);
        $db->bind(':user_id',$userId);
    $db->bind(':location_name', $location_name);
    $db->bind(':location_description', $location_description);
    $db->bind(':location_image', $location_image);       
        return  $row = $db->single();
    }

      /*
      Function            : deleteUserLocation($userId,$Id)
      Brief               : Function to delete User Location
      Input param         : Nil
      Input/output param  : $aList
      Return              : Returns array.
     */
    
public function deleteUserLocation($userId,$Id){
        $db       = new ConnectionManager();
        $curl     = new CurlRequest();
        try {      
            $uquery = " UPDATE gateway_devices "
                    ." SET coin_lat='', coin_lng='' "
					." WHERE is_deleted = 0 AND is_blacklisted='N' AND gateway_id IN (SELECT gateway_id FROM user_gateways WHERE is_deleted = 0 AND ug_id IN "
					."(SELECT gateway_id FROM location_gateway WHERE location_id=:id))";				
					        
					$db->query($uquery);
					$db->bind(':id', $Id);
					                 
					$res = FALSE;                        
            if($db->execute()){
				$eQuery = " DELETE FROM event_log"
             ." WHERE gateway_id IN (SELECT gateway_id FROM user_gateways WHERE is_deleted =0 AND ug_id IN "
					."(SELECT gateway_id FROM location_gateway WHERE location_id=:id))";
        
				$db->query($eQuery);
				$db->bind(':id', $Id);
				
				if($db->execute()){

					$qQuery = " DELETE FROM location_gateway"
					." WHERE location_id=:id";
        
					$db->query($qQuery);
					$db->bind(':id', $Id);
					if($db->execute()){
						$sQuery = " DELETE FROM user_locations"              
						. " WHERE user_id=:user_id AND id=:id"; 
					
						$db->query($sQuery);  
						$db->bind(':user_id',$userId);
						$db->bind(':id',$Id);
			
						if($db->execute()){
							$res = TRUE;
						}
					}
				}
            }
            $aList[JSON_TAG_RESULT] = $res;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
        print_r($e);
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }    



    /*
      Function            : addCoin($userId,$inData)
      Brief               : Function to Add Coins
      Details             : Function to Add Coins
      Input param         : $inData (coin data - name, nick name, coin id, coin lat, coin lng, id(for editing))
      Input/output param  : status
      Return              : Returns array.
     */
    function addCoin($userId,$inData) {
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();
        try {
            
        $device_id                =     $inData[JSON_TAG_COIN_ID];
        $coin_lat             =     $inData[JSON_TAG_COIN_LAT];
        $coin_lng                 =     $inData[JSON_TAG_COIN_LNG];
        $gateway_id                     =     $inData[JSON_TAG_GATEWAY_ID];

            $query = " UPDATE gateway_devices "
                    ." SET coin_lat=:coin_lat, coin_lng=:coin_lng "
                ." WHERE device_id=:device_id AND gateway_id=:gateway_id AND is_deleted = 0 AND is_blacklisted='N'";

                    $db->query($query);
            $db->bind(':coin_lat', $coin_lat);
            $db->bind(':coin_lng', $coin_lng);
            $db->bind(':device_id', $device_id);
            $db->bind(':gateway_id', $gateway_id);
                    $db->execute(); 
                
                $aList[JSON_TAG_RESULT] = $row;
                $aList[JSON_TAG_STATUS] = 0;

        } catch (Exception $e) {
        
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }

//   
  /*
      Function            : getCoin($userId,$gatewayId)
      Brief               : Function to Return added Coins
      Details             : Function to Return added Coins
      Input param         : userId
      Output param        : array (coin list)
      Return              : Returns array.
     */

function getCoin($gatewayId, $userId) {
    	$list = explode(",", $gatewayId);
	
        $db   = new ConnectionManager();
        $curl = new CurlRequest();

    	$res = [];

        try{        
    		foreach($list as $value){
    			$gateway_id = $value;

        		$aQuery = " SELECT *"
               			 ." FROM gateway_devices"
               			 ." WHERE gateway_id=:gateway_id AND is_deleted = 0 AND is_blacklisted='N' ORDER BY added_on DESC";
            			$db->query($aQuery);
            			$db->bind(':gateway_id', $gateway_id);
           			 $row = $db->resultSet();

    			if(is_array($row) && !empty($row)) {
        			foreach($row as $key => $value) {
                        $device_id = $value['device_id'];
                        $tQuery = " SELECT *"
               			 ." FROM devices"
               			 ." WHERE gateway_id=:gateway_id AND is_deleted = 0 AND device_id='$device_id' AND device_type='09' ORDER BY updated_on DESC";
            			$db->query($tQuery);
            			$db->bind(':gateway_id', $gateway_id);
                        $tempStreamRow = $db->single();

                        $hQuery = " SELECT *"
                           ." FROM devices"
                           ." WHERE gateway_id=:gateway_id AND is_deleted = 0 AND device_id='$device_id' AND device_type='10' ORDER BY updated_on DESC";
                        $db->query($hQuery);
                        $db->bind(':gateway_id', $gateway_id);
                        $humidStreamRow = $db->single();

                        $row[$key]['latest_humid_stream'] = $humidStreamRow['device_value'];
                        $row[$key]['latest_temp_stream'] = $tempStreamRow['device_value'];
            			$res[$gateway_id][] = $row[$key];
        			}
    			} elseif (empty($row)) {
				$res[$gateway_id][] = ['no_coin'];
			}   
    		}

        	$aList[JSON_TAG_RESULT] = $res;
            	$aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            print_r($e);
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }

        function addGetCoin($gatewayId, $userId) {

    $list = explode(",", $gatewayId);

    $res = [];

        $db   = new ConnectionManager();
        $curl = new CurlRequest();
    
        try{        
    
    foreach($list as $value){
    
    $gateway_id = $value;

        $aQuery = " SELECT *"
                ." FROM gateway_devices"
                ." WHERE gateway_id=:gateway_id AND is_deleted = 0 AND is_blacklisted='N'";
            $db->query($aQuery);
            $db->bind(':gateway_id', $gateway_id);
            $row = $db->resultSet();

        if(is_array($row) && !empty($row)) {
            foreach($row as $key => $value) {
                $res[$gateway_id][] = $row[$key];
            }
        } elseif (empty($row)) {
                $res[$gateway_id][] = ['no_coin','no'];
	}
    }

                $aList[JSON_TAG_RESULT] = $res;
                $aList[JSON_TAG_STATUS] = 0;
            } catch (Exception $e) {
                $aList[JSON_TAG_STATUS] = 1;
            }
            return $aList;
        }

    /*
    Function        : getLocation()
    Brief           : Function to get a list of user Locations
    Input param     : $userId
    Input/Output param  : array
    Return          : Returns Array
    */

    function getLocation($userId) {
        $db   = new ConnectionManager();
        $curl = new CurlRequest();
        try{
            $sQuery = " SELECT *"
                    . " FROM user_locations"                    
                    . " WHERE user_id=:user_id"
            . " ORDER BY id DESC";     

                $db->query($sQuery);  
                $db->bind(':user_id',$userId);
                $row = $db->resultSet();
 
            $aList[JSON_TAG_RESULT] = $row;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }

    /*
    Function        : getGatewayLocation()
    Brief           : Function to get a list of user Locations
    Input param     : $userId
    Input/Output param  : array
    Return          : Returns Array
    */

    function getGatewayLocation($userId, $locationId) {
        $db   = new ConnectionManager();
        $curl = new CurlRequest();

        try{
            $sQuery = " SELECT user_gateways.*"
                    . " FROM user_gateways"
            . " INNER JOIN location_gateway ON user_gateways.ug_id=location_gateway.gateway_id"                    
                    . " WHERE location_id=:location_id AND user_id=:user_id AND user_gateways.is_deleted =0";     

                $db->query($sQuery);  
                $db->bind(':user_id',$userId);
                $db->bind(':location_id',$locationId);
                $row = $db->resultSet();
            $aList[JSON_TAG_RESULT] = $row;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }

    function getUserLocationLatLong($userId, $locationId)
    {
        $db = new ConnectionManager();
        $curl = new CurlRequest();

        try {

            $sQuery = " SELECT *"
            . " FROM user_locations"
            . " WHERE id=:id AND is_deleted =0";

            $db->query($sQuery);
            $db->bind(':user_id', $userId);
            $db->bind(':id', $locationId);
            $row = $db->resultSet();
            $aList[JSON_TAG_RESULT] = $row;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }
    
    /* Function         : renderAlert(gwId, devId)
       Brief            : Function to get coin latitude and longitude to render alerts on map
       Input param          : $gatewayId, $coinId
       Input/ Output param      : Array
       Return           : Returns Array.
    */

    function renderAlert($userId, $gwId, $devId) {
        $db   = new ConnectionManager();
        $curl = new CurlRequest();

        try{
            $sQuery = " SELECT *"
                        . " FROM gateway_devices"
                        . " WHERE device_id=:devId AND gateway_id=:gwId AND user_id=:user_id AND is_deleted = 0 AND is_blacklisted='N'";     

                $db->query($sQuery);  
                $db->bind(':user_id',$userId);
            $db->bind(':devId', $devId);
                $db->bind(':gwId',$gwId);
                $row = $db->resultSet();
            $aList[JSON_TAG_RESULT] = $row;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            print_r($e);
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }

    /*
    Function        : getHelpDeviceSensor($gatewayId, $deviceId)
    Brief           : Function to get help sensor data of a specific coin
    Input param     : Nil
    Output param        : Array
    Return          : Returns array
*/

    public function getHelpDeviceSensor($gatewayId, $deviceId){
        $db = new ConnectionManager();

        try{
            $dQuery = "SELECT *"
                 ." FROM devices"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 0";
            $db->query($dQuery);
            $db->bind(':gateway_id', $gatewayId);
            $db->bind(':device_id', $deviceId);
            $res = $db->resultSet();
            $aList[JSON_TAG_RESULT] = $res;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
    return $aList;
    }

     /*
      Function            : getHelpDevices($gatewayId)
      Brief               : Function to get help gateway and coin data
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */

    public function getHelpDevices($gatewayId){

        $db       = new ConnectionManager();
      
        try {      
        $sQuery = " SELECT nick_name, device_id, battery, gateway_devices.status,  gateway_devices.active, gateway_devices.updated_on"
                . " FROM user_gateways"
                . " INNER JOIN gateway_devices ON user_gateways.gateway_id=gateway_devices.gateway_id"                   
                . " WHERE user_gateways.gateway_id=:gateway_id AND user_gateways.is_deleted = 0 AND gateway_devices.is_deleted =0 ORDER BY gateway_devices.device_id ASC";             
        $db->query($sQuery);        
        $db->bind(':gateway_id',$gatewayId);
        $res = $db->resultSet();
        
            $aList[JSON_TAG_RESULT]= $res;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
    print_r($e);
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }

/*
      Function            : analyticsGatewayDevices()
      Brief               : Function to get coins registered to a gateway
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */

    public function analyticsGatewayDevices($gatewayId){
        $db = new ConnectionManager();

        try {
            $aQuery = "SELECT gateway_id, device_id, nick_name, frequency, firmware_type"
                    ." FROM gateway_devices"
                    ." WHERE gateway_id=:gateway_id AND is_deleted = 0 AND is_blacklisted='N'  order by device_id";
            $db->query($aQuery);
            $db->bind(':gateway_id', $gatewayId);
            $res = $db->resultSet();

            $aList[JSON_TAG_RESULT] = $res;
            $aList[JSON_TAG_STATUS] = 0;
        } catch(Exception $e) {
            print_r($e);
            $aList[JSON_TAG_STATUS] = 1;
        }
        
        return $aList;
    }

/*
    Function        : analyticsDeviceSensor($gatewayId, $deviceId)
    Brief           : Function to get help sensor data of a specific coin
    Input param     : Nil
    Output param        : Array
    Return          : Returns array
*/

    public function analyticsDeviceSensor($gatewayId, $deviceId){
        $db = new ConnectionManager();

        try{
            $dQuery = "SELECT DISTINCT device_type"
                 ." FROM devices"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =0 AND device_type!=11 AND device_type!=12 AND device_type!=13";
            $db->query($dQuery);
            $db->bind(':gateway_id', $gatewayId);
            $db->bind(':device_id', $deviceId);
            $res = $db->resultSet();
            $aList[JSON_TAG_RESULT] = $res;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
    return $aList;
    }


/*
    Function        : analyticsDeviceSensorData($gatewayId, $deviceId, $deviceType1, $deviceType2)
    Brief           : Function to get help sensor data of a specific coin
    Input param     : Nil
    Output param    : Array
    Return          : Returns array
*/
        public function analyticsDeviceSensorData($gatewayId, $deviceId, $deviceType1, $deviceType2){
        $db = new ConnectionManager();
	

        try{
		if($deviceType1 == "09" || $deviceType1 == "10"){
			$dQuery = "SELECT device_type, device_value, updated_on"
                 		." FROM devices_stream"
                 		." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type='$deviceType1' AND is_deleted = 0"
		 		." ORDER BY updated_on DESC LIMIT 10";
		
		}
		elseif($deviceType1 == "26")
		{
			$dQuery = "SELECT a.updated_on AS UPDATED_DATE, temp AS TEMPERATURE, humid AS HUMIDITY, dpt AS DEWPOINTTEMPERATURE, ah AS ABSOLUTEHUMIDITY FROM (SELECT device_type, device_value as temp, updated_on FROM devices_stream WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type='09' AND is_deleted = 0 ORDER BY updated_on DESC LIMIT 10) a INNER JOIN (SELECT device_type, device_value AS dpt, added_on AS updated_on FROM dewpointtemperature WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type='26' AND is_deleted = 0 ORDER BY added_on DESC LIMIT 10) b ON a.updated_on = b.updated_on INNER JOIN  (SELECT device_type, device_value AS ah, humidity as humid, added_on AS updated_on FROM absolutehumidity WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type='27' AND is_deleted = 0 ORDER BY added_on DESC LIMIT 10) c ON b.updated_on = c.updated_on ORDER BY a.updated_on DESC";

		}
		else
		{
	    		$dQuery = "SELECT device_type, device_value, low_threshold, high_threshold, updated_on"
                 		." FROM threshold"
                 		." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type IN ('$deviceType1', '$deviceType2') AND is_deleted = 0"
		 		." ORDER BY updated_on DESC LIMIT 10";

		}

            $db->query($dQuery);
            $db->bind(':gateway_id', $gatewayId);
            $db->bind(':device_id', $deviceId);
            
            $res = $db->resultSet();

            $aList[JSON_TAG_RESULT] = $res;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
		print_r($e);
        }
    return $aList;
    }



/*
    Function        : analyticsFilteredChartData($gatewayId, $deviceId, $deviceType1, $deviceType2, $fromDate, $toDate)
    Brief           : Function to get help sensor data of a specific coin
    Input param     : Nil
    Output param    : Array
    Return          : Returns array
*/

public function analyticsFilteredChartData($gatewayId, $deviceId, $deviceType1, $deviceType2, $fromDate, $toDate){
    $db = new ConnectionManager();
	
    try{
	if($deviceType1 == "09" || $deviceType1 == "10")
	{
		$dQuery = "SELECT device_type, device_value, updated_on"
                 	." FROM devices_stream"
                 	." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type='$deviceType1' AND is_deleted = 0"
			." AND updated_on BETWEEN :from_date AND :to_date"
		 	." ORDER BY updated_on DESC";
		
	}
	elseif($deviceType1 == "26")
	{
		$dQuery = "SELECT a.updated_on AS UPDATED_DATE, temp AS TEMPERATURE, humid AS HUMIDITY, dpt AS DEWPOINTTEMPERATURE, ah AS ABSOLUTEHUMIDITY FROM (SELECT device_type, device_value as temp, updated_on FROM devices_stream WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type='09' AND is_deleted = 0 AND updated_on BETWEEN :from_date AND :to_date ORDER BY updated_on DESC) a INNER JOIN (SELECT device_type, device_value AS dpt, added_on AS updated_on FROM dewpointtemperature WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type='26' AND is_deleted = 0 AND added_on BETWEEN :from_date AND :to_date ORDER BY added_on DESC) c ON a.updated_on = c.updated_on INNER JOIN  (SELECT device_type, device_value AS ah, humidity as humid, added_on AS updated_on FROM absolutehumidity WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type='27' AND is_deleted = 0 AND added_on BETWEEN :from_date AND :to_date ORDER BY added_on DESC) d ON c.updated_on = d.updated_on ORDER BY a.updated_on DESC";

		
	}
	else
	{
        	$dQuery = "SELECT device_type, device_value, low_threshold, high_threshold, updated_on"
             		." FROM threshold"
             		." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type IN ('$deviceType1', '$deviceType2') AND is_deleted = 0"
			." AND updated_on BETWEEN :from_date AND :to_date"
			." ORDER BY updated_on DESC";
	}

        $db->query($dQuery);
        $db->bind(':gateway_id', $gatewayId);
        $db->bind(':device_id', $deviceId);
        
	$db->bind(':from_date', $fromDate);
	$db->bind(':to_date', $toDate);
        $res = $db->resultSet();

        $aList[JSON_TAG_RESULT] = $res;
        $aList[JSON_TAG_STATUS] = 0;
    } catch (Exception $e) {
        $aList[JSON_TAG_STATUS] = 1;
	print_r($e);
    }
return $aList;
}


     /*
      Function            : breadcrumbNickName($gatewayId, $deviceId)
      Brief               : Function to get Coin Nick Name for breadcrumbs
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
        public function breadcrumbNickName($gatewayId, $deviceId){
        $db = new ConnectionManager();

        try{
            $dQuery = "SELECT nick_name"
                 ." FROM gateway_devices"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 0 AND is_blacklisted='N'";
            $db->query($dQuery);
            $db->bind(':gateway_id', $gatewayId);
            $db->bind(':device_id', $deviceId);
            $res = $db->resultSet();

            $aList[JSON_TAG_RESULT] = $res;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
    return $aList;
    }

public function locationCoinUpdate($userId, $inData) {
        $db = new ConnectionManager();

        $gateway_id = $inData[JSON_TAG_GATEWAY_ID];
        $device_id = $inData[JSON_TAG_DEVICE_ID];
        $device_type = $inData[JSON_TAG_DEVICE_TYPE];
        $coin_lat = $inData[JSON_TAG_COIN_LAT];
        $coin_lng = $inData[JSON_TAG_COIN_LNG];
        try {
            $aQuery = "UPDATE gateway_devices"
                    ." SET coin_lat=:coin_lat, coin_lng=:coin_lng"
                    ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 0 AND is_blacklisted='N'";
            $db->query($aQuery);
            $db->bind(':coin_lat', $coin_lat);
            $db->bind(':coin_lng', $coin_lng);
            $db->bind(':gateway_id', $gateway_id);
            $db->bind(':device_id', $device_id);
            $res = $db->execute();
            $aList[JSON_TAG_STATUS] = 0;
            $aList[JSON_TAG_RESULT] = $res;

        } catch(Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
    }

     /*
      Function            : renameCoin()
      Brief               : Function to update coin nick name
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */

    function renameCoin($inData) {
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();

        try {
            $gateway_id = $inData[JSON_TAG_GATEWAY_ID];
            $device_id = $inData[JSON_TAG_DEVICE_ID];
            $new_nick_name = $inData[JSON_TAG_NICK_NAME];

            
            $aQuery = "UPDATE gateway_devices"
                     ." SET nick_name=:new_nick_name"
                     ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 0 AND is_blacklisted='N'";
            $db->query($aQuery);
            $db->bind(':gateway_id', $gateway_id);
            $db->bind(':device_id', $device_id);
            $db->bind(':new_nick_name', $new_nick_name);

            $row = $db->execute();

            $aList[JSON_TAG_RESULT] = $row;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }


    //function to check whether new nickname is same as current nickname

    function checkNewNickname($db, $gateway_id, $device_id, $new_nick_name) {

        $aQuery = "SELECT * FROM gateway_devices"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND nick_name=:new_nick_name AND is_deleted = 0 AND is_blacklisted='N'";
        $db->query($aQuery);
        $db->bind(':gateway_id', $gateway_id);
        $db->bind(':device_id', $device_id);
        $db->bind(':new_nick_name', $new_nick_name);
        
        return $row = $db->resultSet();
    }

/*
      Function            : getCoinLocation($userId,$gatewayId)
      Brief               : Function to Return added Coins
      Details             : Function to Return added Coins
      Input param         : userId
      Output param        : array (coin list)
      Return              : Returns array.
     */

function getCoinLocation($gatewayId, $deviceId, $userId) {
   
        $db   = new ConnectionManager();

	try{
		$dQuery = "SELECT coin_lat, coin_lng"
                 	." FROM gateway_devices"
                 	." WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 0 AND is_blacklisted='N'";
		
		$db->query($dQuery);
        	$db->bind(':gateway_id', $gatewayId);
        	$db->bind(':device_id', $deviceId);
        
		$res = $db->resultSet();

        	$aList[JSON_TAG_RESULT] = $res;
        	$aList[JSON_TAG_STATUS] = 0;
    	} catch (Exception $e) {
        	$aList[JSON_TAG_STATUS] = 1;
		print_r($e);
	}
	return $aList;
}

public function deleteCoinLocation($userId, $inData) {
        $db = new ConnectionManager();

        $gateway_id = $inData[JSON_TAG_GATEWAY_ID];
        $device_id = $inData[JSON_TAG_DEVICE_ID];     
       
        try {
            $aQuery = "UPDATE gateway_devices"
                    ." SET coin_lat='', coin_lng=''"
                    ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 0 AND is_blacklisted='N'";
            $db->query($aQuery);
            $db->bind(':gateway_id', $gateway_id);
            $db->bind(':device_id', $device_id);
            $res = $db->execute();
            $aList[JSON_TAG_STATUS] = 0;
            $aList[JSON_TAG_RESULT] = $res;

        } catch(Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
    }

/*
      Function            : eventAddLog()
      Brief               : Function to log real time event
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */

public function eventAddLog($userId, $inData) {

    $db = new ConnectionManager();

    $gateway_id = $inData[JSON_TAG_GATEWAY_ID];
    $device_id  = $inData[JSON_TAG_DEVICE_ID];
    $device_type = $inData[JSON_TAG_DEVICE_TYPE];
    $device_value = $inData[JSON_TAG_DEVICE_VALUE];
    $log_description = $inData[JSON_TAG_LOG_DESCRIPTION];
    $updated_on = $inData[JSON_TAG_UPDATED_ON];

    try{

       $lQuery = "INSERT INTO event_log"
            ." (gateway_id, device_id, device_type, device_value, log_description)"
            ." VALUES (:gateway_id, :device_id, :device_type, :device_value, :log_description)";
        $db->query($lQuery);
        $db->bind(':gateway_id', $gateway_id);
        $db->bind(':device_id', $device_id);
        $db->bind(':device_type', $device_type);
        $db->bind(':device_value', $device_value);
        $db->bind(':log_description', $log_description);
        $res = $db->execute();
            $aList[JSON_TAG_STATUS] = 0;
            $aList[JSON_TAG_RESULT] = $res;
    } catch(Exception $e) {
        print_r($e);
        $aList[JSON_TAG_STATUS] = 1;
    }
    return $aList;
}

    function checkEventLog($db, $rowId) {
        $query = "SELECT *"
            ." FROM event_log"
            ." WHERE id=:id";
        $db->query($query);
        $db->bind(':id', $rowId);
        
        return $row = $db->single();
    }

    /*
    Function        : getEventLogs()
    Brief           : Function to get events logged
    Input param     : $userId
    Input/Output param  : array
    Return          : Returns Array
    */

    function getEventLogs($userId, $locationId) {
        $db   = new ConnectionManager();
        

        try{
            $sQuery = " SELECT * FROM event_log WHERE gateway_id IN (SELECT ug.gateway_id"
                    . " FROM user_gateways ug"
            . " INNER JOIN location_gateway lg ON ug.ug_id=lg.gateway_id"                    
                    . " WHERE location_id=:location_id AND user_id=:user_id AND ug.is_deleted = 0)"
		. " ORDER BY updated_on DESC";     

                $db->query($sQuery);  
                $db->bind(':user_id',$userId);
                $db->bind(':location_id',$locationId);
                $row = $db->resultSet();
            $aList[JSON_TAG_RESULT] = $row;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }

/*
      Function            : getDeviceSettings()
      Brief               : Function to get Device Settings     
      Input param         : $userId
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function getDeviceSettings($userId, $gatewayId){
        $db       = new ConnectionManager();
        $curl     = new CurlRequest();
        try {      
            $sQuery = " SELECT *"
                    . " FROM gateway_devices"                    
                    . " WHERE user_id=:user_id AND gateway_id=:gateway_id AND is_deleted =0 AND is_blacklisted='N' ORDER BY device_id";       

            $db->query($sQuery);  
            $db->bind(':user_id',$userId);
	    $db->bind(':gateway_id',$gatewayId);
            $row = $db->resultSet();
            $finalArr  = [];
            if(!empty($row)){
                foreach ($row as $key => $value) {
                    $deviceId  =  $value['device_id'];
		    $nickname  =  $value['nick_name'];
			$power = $value['power'];
		    $frequency = $value['frequency'];
            	$coinlocation  =  $value['coin_location'];
            	$firmware_type  =  $value['firmware_type'];

                    $finalArr[$key]['device_id'] = $deviceId;
			$finalArr[$key]['nick_name'] = $nickname;
            	$finalArr[$key]['coin_location']  = $coinlocation;
            	$finalArr[$key]['firmware_type']  = $firmware_type;
			$finalArr[$key]['power'] = $power;
			$finalArr[$key]['frequency'] = $frequency;
                    $finalArr[$key]['settings']   = $this->getCoinSettings($db,$gatewayId, $deviceId);
			$finalArr[$key]['generalsettings']   = $this->getUserSettings($db,$userId);
                }
            }
//            print_r($finalArr);
//            exit();
            
            $aList[JSON_TAG_RESULT]= $finalArr;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }    
     
    //Get Device settings
    function getCoinSettings($db, $gatewayId, $deviceId){
            $sQuery = " SELECT *"
                    . " FROM device_settings"                    
                    . " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =0 ORDER BY ds_id ASC";       

            $db->query($sQuery);  
            $db->bind(':gateway_id',$gatewayId);
	    $db->bind(':device_id',$deviceId);

            $row = $db->resultSet();
            
            return $row;
    }


//To check Device Settings Exists or not
function checkDeviceSettingsExists($db, $gateway_id, $device_id, $sensor_type){        
		
       $sQuery = " SELECT *"
                . " FROM device_settings"                    
                . " WHERE device_sensor=:sensor_type AND gateway_id=:gateway_id AND device_id = :device_id AND is_deleted =0 ORDER BY ds_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':sensor_type',$sensor_type);
        $db->bind(':gateway_id',$gateway_id);
	$db->bind(':device_id',$device_id);
        
        return  $row = $db->single();
}


function getSensorTypeVal($sensor_type){

	if($sensor_type == 'Accelerometer'){
		$sen_type = '41';
	}
	if($sensor_type == 'Gyroscope'){
		$sen_type = '47';
	}
	if($sensor_type == 'Temperature'){
		$sen_type = '54';
	}
	if($sensor_type == 'Humidity'){
		$sen_type = '48';
	}
	if($sensor_type == 'Temperature Stream'){
		$sen_type = '74';
	}
	if($sensor_type == 'Humidity Stream'){
		$sen_type = '68';
	}
	if($sensor_type == 'Accelerometer Stream'){
		$sen_type = '61';
    }
    if($sensor_type == 'Predictive Maintenance'){                       
        $sen_type = '38';
    }
	
	return $sen_type;

}

function xyzCombinationChange($inData) {
        

    $db = new ConnectionManager();
    $generalMethod = new GeneralMethod();
    $curl     = new CurlRequest();
    try {
        
            	$gatewayId       =     $inData[JSON_TAG_GATEWAY_ID];
        	$device_id  = $inData[JSON_TAG_DEVICE_ID];
    		$sensor_active     =   $inData[JSON_TAG_SENSOR_ACTIVE]; 
    		$sensor_type     =     $inData[JSON_TAG_SENSOR_TYPE];            
    		$xyz_combination     =     $inData[JSON_TAG_XYZ_COMBINATION];
    		$format = $xyz_combination;
    		//$sensor_type_val = $this->getSensorTypeVal($sensor_type);
    		
    		$rate_value = '00';
    
	
		$coinFirmware = $this->getCoinFirmwareType($db, $gatewayId, $device_id);
		$firmware_type = $coinFirmware[0][firmware_type];
		if($firmware_type == 'Predictive Maintenance'){
			$sensor_type_val = '75';
		}else{
			$sensor_type_val = '38';
		}
	
        
            // Check Mandatory field    
            if (empty($gatewayId) || empty($device_id) || empty($sensor_active) || empty($sensor_type)) {
                $aList[JSON_TAG_STATUS] = 2;
                return $aList;
            }
        
            $response = FALSE;
            //Check Gateway Settings Exists or not
            $rowDeviceSettings= $this->checkDeviceSettingsExists($db, $gatewayId, $device_id, $sensor_type);

    $pendingRequest = $this->checkPendingRequest($db, $gatewayId, $deviceId, $device_type, 'SETS');

    if(empty($pendingRequest)){ 

      

        $dynamicID = $this->getDynamicID($db, $gatewayId, $device_id);
        $dynamic_id = $dynamicID[0][dynamic_id];

                if (empty($rowDeviceSettings)) {
                        //Insert newly
                    $query = "INSERT INTO device_settings(gateway_id, device_id, device_sensor, sensor_active) "
                        . "VALUES (:gateway_id, :device_id, :sensor_type, :sensor_active)";
                    $db->query($query);
                    $db->bind(':gateway_id', $gatewayId);
                    $db->bind(':sensor_type', $sensor_type);
            		$db->bind(':device_id', $device_id);
            		$db->bind(':sensor_active', $sensor_active);

                    if($db->execute()){                 

                    
                    $GatewayMacID = $this->getGatewayMacID($db, $gatewayId);
                    if(!empty($GatewayMacID)){
                        $gateway_mac_id = $GatewayMacID[0]['gateway_mac_id'];
            
                                    $format = $xyz_combination;

                                    $response = TRUE;
                        $data_arr  =  array();
                
                                    $data_arr['gateway_id']    =  $gatewayId;
                                    $data_arr['device_id']     =  $device_id;
                                    $data_arr['device_type']   =  $sensor_type_val;
                        $data_arr['gateway_mac_id']    =  $gateway_mac_id;
                
                                    $data_arr['format']        =  $format;
                
                                    $data_arr['rate_value']     =  $rate_value;
                                    $data_arr['dynamic_id']     =  $dynamic_id;  
                
                                    $data_arr['topic']         =  TOPIC_SET_STREAM;
        
                                $url        =  URL_END_POINT.'sensegiz-mqtt/publish.php';
            
                                $response   =  $curl->postRequestData($url, $data_arr);
                                $aList[JSON_TAG_STATUS] = 0;
                    }

                    }               
            
                }else{
               
            
                
                $GatewayMacID = $this->getGatewayMacID($db, $gatewayId);
                if(!empty($GatewayMacID)){
                    $gateway_mac_id = $GatewayMacID[0]['gateway_mac_id'];
                    $dynamicID = $this->getDynamicID($db, $gatewayId, $device_id);
                    $dynamic_id = $dynamicID[0][dynamic_id];

                    $format = $xyz_combination;


                                $response = TRUE;
                    $data_arr  =  array();
                
                                $data_arr['gateway_id']    =  $gatewayId;
                                $data_arr['device_id']     =  $device_id;
                                $data_arr['device_type']   =  $sensor_type_val;
                    $data_arr['gateway_mac_id']    =  $gateway_mac_id;
                
                                $data_arr['format']        =  $format;
                
                                $data_arr['rate_value']     =  $rate_value;
                                $data_arr['dynamic_id']     =  $dynamic_id;  
                
                                $data_arr['topic']         =  TOPIC_SET_STREAM;
        
                            $url        =  URL_END_POINT.'sensegiz-mqtt/publish.php';
            
                            $response   =  $curl->postRequestData($url, $data_arr);


                    $query = "INSERT INTO request_list(gateway_id, gateway_mac_id, device_id, device_type, request_type, format, rate_value, dynamic_id, action) "
                                       . "VALUES (:gatewayId, :gateway_mac_id, :deviceId, :device_type, 'SETS', :format, :rate_value, :dynamic_id, 0)";
            
                    $db->query($query);
                        $db->bind(':gatewayId', $gatewayId);
                        $db->bind(':gateway_mac_id', $gateway_mac_id);
                    $db->bind(':deviceId', $device_id);
                    $db->bind(':device_type', $sensor_type_val);
                    $db->bind(':format', $format);
                    $db->bind(':rate_value', $rate_value);
                    $db->bind(':dynamic_id', $dynamic_id);
    
                    if($db->execute()){

                        $response = 'TRUE';
                    } 
                    
                    $aList[JSON_TAG_STATUS] = 0;
                }       

                }

    }else{
        $response = 'TRUE';
        $aList[JSON_TAG_STATUS] = 5;
    }   

        $aList[JSON_TAG_RESULT] = $response;

    } catch (Exception $e) {
        $aList[JSON_TAG_STATUS] = 1;
    }
    return $aList;
}

/*
      Function            : updateSensorActive($inData)
      Brief               : Function to Update EMAIL Notification
      Details             : Function to Update EMAIL Notification
      Input param         : Nil
      Input/output param  : Nil
      Return              : Returns array.
     */

function updateSensorActive($inData) {
        

        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();

        try {
            
            	$gatewayId       =     $inData[JSON_TAG_GATEWAY_ID];
	    	$device_id  = $inData[JSON_TAG_DEVICE_ID];
		$sensor_active     =   $inData[JSON_TAG_SENSOR_ACTIVE];             
            	$sensor_type     =     $inData[JSON_TAG_SENSOR_TYPE];
		
		$sensor_type_val = $this->getSensorTypeVal($sensor_type);

		if($sensor_active == 'Y')
		{
			$rate_value = '45';
		}else{
			$rate_value = '44';
		}
            
            // Check Mandatory field    
            if (empty($gatewayId) || empty($device_id) || empty($sensor_active) || empty($sensor_type)) {
                $aList[JSON_TAG_STATUS] = 2;
                return $aList;
            }
            
            $response = FALSE;
            //Check Gateway Settings Exists or not
            $rowDeviceSettings= $this->checkDeviceSettingsExists($db, $gatewayId, $device_id, $sensor_type);

		$pendingRequest = $this->checkPendingRequest($db, $gatewayId, $deviceId, $device_type, 'EnD');


	if(empty($pendingRequest)){ 

            if (empty($rowDeviceSettings)) {
                //Insert newly
                $query = "INSERT INTO device_settings(gateway_id, device_id, device_sensor, sensor_active) "
                        . "VALUES (:gateway_id, :device_id, :sensor_type, :sensor_active)";
                $db->query($query);
                $db->bind(':gateway_id', $gatewayId);
                $db->bind(':sensor_type', $sensor_type);
		$db->bind(':device_id', $device_id);
		$db->bind(':sensor_active', $sensor_active);

                if($db->execute()){
			$GatewayMacID = $this->getGatewayMacID($db, $gatewayId);
			if(!empty($GatewayMacID)){
				$gateway_mac_id = $GatewayMacID[0]['gateway_mac_id'];

				$dynamicID = $this->getDynamicID($db, $gatewayId, $deviceId);
				$dynamic_id = $dynamicID[0][dynamic_id];

                    		$format = '00';


                    		$response = TRUE;
				$data_arr  =  array();
                    
                    		$data_arr['gateway_id']    =  $gatewayId;
                    		$data_arr['device_id']     =  $device_id;
                    		$data_arr['device_type']   =  $sensor_type_val;
				$data_arr['gateway_mac_id']    =  $gateway_mac_id;
                    
                    	$data_arr['format']        =  $format;
                    
                    	$data_arr['rate_value']     =  $rate_value;
                    	$data_arr['dynamic_id']     =  $dynamic_id;  
                    
                    	$data_arr['topic']         =  TOPIC_SET_STREAM;
            
                	$url        =  URL_END_POINT.'sensegiz-mqtt/publish.php';
                
                	$response   =  $curl->postRequestData($url, $data_arr);
			            $aList[JSON_TAG_STATUS] = 0;
			}

                }               
                
            }      
            else{
                
		$GatewayMacID = $this->getGatewayMacID($db, $gatewayId);
		if(!empty($GatewayMacID)){
			$gateway_mac_id = $GatewayMacID[0]['gateway_mac_id'];
			$dynamicID = $this->getDynamicID($db, $gatewayId, $device_id);
			$dynamic_id = $dynamicID[0][dynamic_id];

			$format = '00';


                    	$response = TRUE;
			$data_arr  =  array();
                    
                    	$data_arr['gateway_id']    =  $gatewayId;
                    	$data_arr['device_id']     =  $device_id;
                    	$data_arr['device_type']   =  $sensor_type_val;
			$data_arr['gateway_mac_id']    =  $gateway_mac_id;
                    
                    	$data_arr['format']        =  $format;
                    
                    	$data_arr['rate_value']     =  $rate_value;
                    	$data_arr['dynamic_id']     =  $dynamic_id;  
                    
                    	$data_arr['topic']         =  TOPIC_SET_STREAM;
            
                	$url        =  URL_END_POINT.'sensegiz-mqtt/publish.php';
                
                	$response   =  $curl->postRequestData($url, $data_arr);


			$query = "INSERT INTO request_list(gateway_id, gateway_mac_id, device_id, device_type, request_type, format, rate_value, dynamic_id, action) "
                       		. "VALUES (:gatewayId, :gateway_mac_id, :deviceId, :device_type, 'EnD', :format, :rate_value, :dynamic_id, 0)";
        		
			$db->query($query);
        		$db->bind(':gatewayId', $gatewayId);
        		$db->bind(':gateway_mac_id', $gateway_mac_id);
			$db->bind(':deviceId', $device_id);
			$db->bind(':device_type', $sensor_type_val);
			$db->bind(':format', $format);
			$db->bind(':rate_value', $rate_value);
			$db->bind(':dynamic_id', $dynamic_id);

			if($db->execute()){

					$response = 'TRUE';
			}
			            $aList[JSON_TAG_STATUS] = 0;
		}		
		                                
            }

	}else{
			$response = 'TRUE';
			$aList[JSON_TAG_STATUS] = 5;
		}	

            $aList[JSON_TAG_RESULT] = $response;

        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
}

/*
    Function        : analyticsAccStream($gatewayId, $deviceId, $deviceType)
    Brief           : Function to get 3-axis Accelerometer Stream data for a specific coin
    Input param     : Nil
    Output param        : Array
    Return          : Returns array
*/

    public function analyticsAccStream($gatewayId, $deviceId, $deviceType){
        $db = new ConnectionManager();

        try{
            if($deviceType == '12'){
                  $dQuery = "SELECT * FROM (SELECT device_type, device_value, updated_on"
                 ." FROM devices_stream"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '12' AND is_deleted = 0"
		 ." ORDER BY asdump_id_counter DESC LIMIT 100) AS a UNION ALL " //updated_on,
		."SELECT * FROM (SELECT device_type, device_value, updated_on"
                 ." FROM devices_stream"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '14' AND is_deleted = 0"
		 ." ORDER BY asdump_id_counter DESC LIMIT 100) AS b UNION ALL " //updated_on,
		."SELECT * FROM (SELECT device_type, device_value, updated_on"
                 ." FROM devices_stream"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '15' AND is_deleted = 0"
		 ." ORDER BY asdump_id_counter DESC LIMIT 100) AS c UNION ALL " //updated_on,
		."SELECT * FROM (SELECT device_type, device_value, updated_on"
                 ." FROM devices_stream"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '28' AND is_deleted = 0"
		 ." ORDER BY asdump_id_counter DESC LIMIT 100) AS d"; //updated_on,


	    }

	     elseif($deviceType == '17'){
            	$dQuery = "SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                 ." FROM acceleration"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '17' AND is_deleted = 0"
		 ." ORDER BY added_on DESC LIMIT 100) AS a UNION ALL "
		."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                 ." FROM acceleration"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '18' AND is_deleted = 0"
		 ." ORDER BY added_on DESC LIMIT 100) AS b UNION ALL "
		."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                 ." FROM acceleration"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '19' AND is_deleted = 0"
		 ." ORDER BY added_on DESC LIMIT 100) AS c  UNION ALL "
		."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                 ." FROM acceleration"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '29' AND is_deleted = 0"
		 ." ORDER BY added_on DESC LIMIT 100) AS d";

		 
	    }

	    elseif($deviceType == '20'){
            	$dQuery = "SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                 ." FROM velocity"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '20' AND is_deleted = 0"
		 ." ORDER BY added_on DESC LIMIT 100) AS a UNION ALL "
		."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                 ." FROM velocity"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '21' AND is_deleted = 0"
		 ." ORDER BY added_on DESC LIMIT 100) AS b UNION ALL "
		."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                 ." FROM velocity"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '22' AND is_deleted = 0"
		 ." ORDER BY added_on DESC LIMIT 100) AS c UNION ALL "
		."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                 ." FROM velocity"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '30' AND is_deleted = 0"
		 ." ORDER BY added_on DESC LIMIT 100) AS d";

		 
	    }
	    
	    elseif($deviceType == '23'){
            	$dQuery = "SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                 ." FROM displacement"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '23' AND is_deleted = 0"
		 ." ORDER BY added_on DESC LIMIT 100) AS a UNION ALL "
		."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                 ." FROM displacement"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '24' AND is_deleted = 0"
		 ." ORDER BY added_on DESC LIMIT 100) AS b UNION ALL "
		."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                 ." FROM displacement"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '25' AND is_deleted = 0"
		 ." ORDER BY added_on DESC LIMIT 100) AS c UNION ALL "
		."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                 ." FROM displacement"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '31' AND is_deleted = 0"
		 ." ORDER BY added_on DESC LIMIT 100) AS d";

		 
	    }




            $db->query($dQuery);
            $db->bind(':gateway_id', $gatewayId);
            $db->bind(':device_id', $deviceId);
            $res = $db->resultSet();
            $aList[JSON_TAG_RESULT] = $res;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
    return $aList;
    }

/*
    Function        : analyticsStreamFilteredData($gatewayId, $deviceId, $deviceType1, $startTime, $endTime)
    Brief           : Function to get help sensor data of a specific coin
    Input param     : Nil
    Output param    : Array
    Return          : Returns array
*/

public function analyticsStreamFilteredData($gatewayId, $deviceId, $deviceType1, $startTime, $endTime){
    $db = new ConnectionManager();
	
    try{
				
		if($deviceType1 == '12'){		
			$dQuery = "SELECT device_type, device_value, updated_on"
                 	." FROM devices_stream"
                 	." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type IN ('$deviceType1', '14', '15', '28') AND is_deleted = 0"
			." AND updated_on BETWEEN :from_date AND :to_date"
		 	." ORDER BY updated_on DESC";
		}
		elseif($deviceType1 == '17'){		
			$dQuery = "SELECT device_type, device_value, added_on as updated_on"
                 	." FROM acceleration"
                 	." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type IN ('$deviceType1', '18', '19', '29') AND is_deleted = 0"
			." AND added_on BETWEEN :from_date AND :to_date"
		 	." ORDER BY added_on DESC";
		}
		elseif($deviceType1 == '20'){		
			$dQuery = "SELECT device_type, device_value, added_on as updated_on"
                 	." FROM velocity"
                 	." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type IN ('$deviceType1', '21', '22', '30') AND is_deleted = 0"
			." AND added_on BETWEEN :from_date AND :to_date"
		 	." ORDER BY added_on DESC";
		}
		elseif($deviceType1 == '23'){		
			$dQuery = "SELECT device_type, device_value, added_on as updated_on"
                 	." FROM displacement"
                 	." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type IN ('$deviceType1', '24', '25', '31') AND is_deleted = 0"
			." AND added_on BETWEEN :from_date AND :to_date"
		 	." ORDER BY added_on DESC";
		}

		
				
        	$db->query($dQuery);
       		$db->bind(':gateway_id', $gatewayId);
        	$db->bind(':device_id', $deviceId);

		$db->bind(':from_date', $startTime);
		$db->bind(':to_date', $endTime);

		
        	$res = $db->resultSet();

        $aList[JSON_TAG_RESULT] = $res;
        $aList[JSON_TAG_STATUS] = 0;
    } catch (Exception $e) {
        $aList[JSON_TAG_STATUS] = 1;
	print_r($e);
    }
return $aList;
}


/*
      Function            : updateRequestAction($inData)
      Brief               : Function to update the action against GET current value request
      Details             : Function to update the action against GET current value request
      Input param         : Nil
      Input/output param  : Nil
      Return              : Returns array.
     */

function updateRequestAction($inData) {        

        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
	$response = FALSE;
	$curl     = new CurlRequest();

	$rdata = '';

        try {
            
            	$gatewayId       =     $inData[JSON_TAG_GATEWAY_ID];
	    	$device_id  = $inData[JSON_TAG_DEVICE_ID];            
            	$device_type     =     $inData[JSON_TAG_DEVICE_TYPE];

		$ResponseReceived = $this->checkResponseReceived($db, $gatewayId, $device_id, $device_type);
            	if (!empty($ResponseReceived)) {

			$sQuery = " UPDATE request_list "
                        	. " SET action=1, updated_on=DATE_TRUNC('second', NOW())"
                        	. " WHERE gateway_id = :gatewayId AND device_id = :deviceId AND device_type = :device_type AND request_type = 'GET' AND action=0";
        	        $db->query($sQuery);
                	$db->bind(':gatewayId', $gatewayId);
			$db->bind(':deviceId', $device_id);
			$db->bind(':device_type', $device_type);
                
                	if($db->execute()){
				$response = 'TRUE';
				$aList[JSON_TAG_STATUS] = 5;
                	} 
		}
		else{
			$pendingRequest = $this->checkPendingRequest($db, $gatewayId, $deviceId, $device_type, 'GET');
			$aList[JSON_TAG_STATUS] = 0;
		}

		$aList[JSON_TAG_RESULT] = $response;
        	
        } catch (Exception $e) {
		print_r($e);
            	$aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
}

function checkResponseReceived($db, $gatewayId, $device_id, $device_type){

	$sQuery = " SELECT *"
               . " FROM request_list"                    
                . " WHERE gateway_id = :gatewayId AND device_id = :deviceId AND device_type = :device_type AND request_type = 'GET' AND action=0";       
        
       	$db->query($sQuery);         
		$db->bind(':gatewayId', $gatewayId);
		$db->bind(':deviceId', $device_id);
		$db->bind(':device_type', $device_type);
   
       	return  $row = $db->single();
}


/*
  Function            : editUserLocation($userId,$inData)
  Brief               : Function to edit User Location
  Details             : Function to edit User Location
  Input param         : Nil
  Input/output param  : Nil
  Return              : Returns array.
 */
function editUserLocation($userId,$inData) {
    $db = new ConnectionManager();
    $generalMethod = new GeneralMethod();
   
    try {
		$location_id = $inData[JSON_TAG_LOCATION_ID];
        	$location_name = $inData[JSON_TAG_LOCATION_NAME];
		$location_description = $inData[JSON_TAG_LOCATION_DESCRIPTION];        
		$gateway_list = $inData[JSON_TAG_GATEWAY_ID];

        	//Check Mandatory field    
        	if (empty($location_id) && empty($location_name) && empty($location_description) && empty($gateway_list)) {
            		$aList[JSON_TAG_STATUS] = 2;			
            		return $aList;
        	}
            
		//Check gateway already added to some other location
		foreach($gateway_list as $key => $value) {
                        $gateway_id = $value;
			$checkOtherLocationsGatewayAdded = $this->checkOtherLocationsGatewayAdded($db, $gateway_id, $location_id);
			if(!empty($checkOtherLocationsGatewayAdded)){
				$aList[JSON_TAG_STATUS] = 4;
				$aList[JSON_TAG_RESULT] = $gateway_id;
            			return $aList;
			}
		}


		$currentGateways = $this->getExistingUserLocationGateways($db, $location_id);
		foreach($currentGateways as $key => $value) 
		{
                        $g_id = $value['gateway_id'];
			if(!in_array($g_id, $gateway_list)){				
				$sQuery = " DELETE FROM location_gateway"              
						. " WHERE gateway_id=:gateway_id AND location_id=:location_id"; 
					
				$db->query($sQuery);  
				$db->bind(':gateway_id', $g_id);
                       		$db->bind(':location_id', $location_id);
				if($db->execute()){
					//update all coin locations for this gateway
					$aQuery = "UPDATE gateway_devices"
                    				." SET coin_lat='', coin_lng=''"
                    			." WHERE is_deleted = 0 AND is_blacklisted='N' AND gateway_id=(SELECT gateway_id FROM user_gateways WHERE is_deleted = 0 AND ug_id=:g_id)";
            				$db->query($aQuery);
            				$db->bind(':g_id', $g_id);
            			
            				$res = $db->execute();
				}							
			}

		}

		$sQuery = " UPDATE user_locations "
                        . " SET location_name=:location_name, location_description=:location_description, updated_on=DATE_TRUNC('second', NOW())"
                        . " WHERE id=:location_id AND user_id=:user_id";
                $db->query($sQuery);
                $db->bind(':location_name', $location_name);
                $db->bind(':location_description', $location_description);                    
                $db->bind(':location_id', $location_id);
                $db->bind(':user_id', $userId);
                $db->execute();
			
		foreach($gateway_list as $key => $value) 
		{
                        $gateway_id = $value;
			$checkLocationGatewayAdded = $this->checkLocationGatewayAdded($db, $gateway_id, $location_id);
		
			if(empty($checkLocationGatewayAdded))
			{																				
				$gQuery = "INSERT INTO location_gateway(location_id, gateway_id)"
                                		." VALUES (:location_id, :gateway_id)";
                        	$db->query($gQuery);
                        	$db->bind(':gateway_id', $gateway_id);
                       		$db->bind(':location_id', $location_id);
                       		if($db->execute()){
					$response = 'TRUE';
				}
			
			}
		}
           	
                           
        $aList[JSON_TAG_RESULT] = $response;
        $aList[JSON_TAG_STATUS] = 0;
	
    } catch (Exception $e) {
        $aList[JSON_TAG_STATUS] = 1;
	print_r($e);
    }
    return $aList;
}

function getExistingUserLocationGateways($db, $location_id)
{

	$sQuery = " SELECT gateway_id"
                    . " FROM location_gateway"                    
                    . " WHERE location_id=:location_id";       

        $db->query($sQuery);  
        $db->bind(':location_id',$location_id);	   

	$row = $db->resultSet();
            
        return $row;
}


function checkLocationGatewayAdded($db, $gateway_id, $location_id)
{	
	$sQuery = " SELECT gateway_id"
                    . " FROM location_gateway"                    
                    . " WHERE location_id=:location_id AND gateway_id = :gateway_id";       

        $db->query($sQuery);  
        $db->bind(':location_id',$location_id);
	$db->bind(':gateway_id',$gateway_id);	   

	$row = $db->resultSet();
            
        return $row;
}


function checkOtherLocationsGatewayAdded($db, $gateway_id, $location_id)
{
	$sQuery = " SELECT gateway_id"
                    . " FROM location_gateway"
		." WHERE gateway_id=:gateway_id AND location_id!=:location_id";      

        $db->query($sQuery);     
	$db->bind(':gateway_id',$gateway_id);
	$db->bind(':location_id',$location_id);

        $row = $db->resultSet();
            
        return $row;
}

function setCoinTransmissionPower($inData) {
        

        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();
        try {
            
            	$gatewayId       =     $inData[JSON_TAG_GATEWAY_ID];
	    	$device_id  = $inData[JSON_TAG_DEVICE_ID];
		$power     =   $inData[JSON_TAG_TRANSMISSION_POWER];             
		
		            
            // Check Mandatory field    
            if (empty($gatewayId) || empty($device_id) || empty($power)) {
                $aList[JSON_TAG_STATUS] = 2;
                return $aList;
            }
            
            $response = FALSE;
	
            $query = "UPDATE gateway_devices SET power=:power WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted=0 AND is_blacklisted='N'";
                $db->query($query);
                $db->bind(':gateway_id', $gatewayId);
		$db->bind(':device_id', $device_id);
		$db->bind(':power', $power);

                if($db->execute()){
			$GatewayMacID = $this->getGatewayMacID($db, $gatewayId);
			if(!empty($GatewayMacID)){
				$gateway_mac_id = $GatewayMacID[0]['gateway_mac_id'];

				$dynamicID = $this->getDynamicID($db, $gatewayId, $device_id);
				$dynamic_id = $dynamicID[0][dynamic_id];
				
                    	$response = TRUE;
			$data_arr  =  array();
                    
                    	$data_arr['gateway_id']    =  $gatewayId;
                    	$data_arr['device_id']     =  $device_id;
			$data_arr['gateway_mac_id']    =  $gateway_mac_id;
                    	$data_arr['device_type']   =  '45';
                    
                    	$data_arr['format']        =  '00';
                    
                    	$data_arr['rate_value']     =  $power;

                    	$data_arr['dynamic_id']     =  $dynamic_id;  
                    
                    	$data_arr['topic']         =  TOPIC_SET_STREAM;
            
                	$url        =  URL_END_POINT.'sensegiz-mqtt/publish.php';
                
                	$response   =  $curl->postRequestData($url, $data_arr);
			}
                }               
                                 
            	

            $aList[JSON_TAG_RESULT] = $response;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
}

/*
      Function            : getAccStreamDevices($gatewayId, $coins)
      Brief               : Function to get devices connected to the gateway       
      Input param         : Nil
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function getAccStreamDevices($gatewayId, $coins){
        $db       = new ConnectionManager();
      
        try {      
		
        	if($coins == "0"){
                $sQuery = " SELECT gd.gd_id,gd.gateway_id,gd.device_mac_address,gd.nick_name,gd.coin_location,gd.device_id,gd.active,gd.is_blacklisted,d.d_id,d.device_type,d.device_value,d.get_current_value,d.threshold_value,d.format,d.rate_value,d.added_on,d.updated_on "
                        . " FROM gateway_devices as gd"
                        . " LEFT JOIN devices as d"                   
                        . " ON gd.device_id=d.device_id AND gd.gateway_id=d.gateway_id"
                . " WHERE gd.gateway_id=:gateway_id AND gd.is_blacklisted='N' AND gd.is_deleted =0 AND d.is_deleted = 0 AND d.device_type IN ('12','14','15','28')"
                . " ORDER BY d.device_id, d.device_type, d.updated_on DESC";
            }else{
                $sQuery = " SELECT gd.gd_id,gd.gateway_id,gd.device_mac_address,gd.nick_name,gd.coin_location,gd.device_id,gd.active,gd.is_blacklisted,d.d_id,d.device_type,d.device_value,d.get_current_value,d.threshold_value,d.format,d.rate_value,d.added_on,d.updated_on "
                        . " FROM gateway_devices as gd"
                        . " LEFT JOIN devices as d"                   
                        . " ON gd.device_id=d.device_id AND gd.gateway_id=d.gateway_id"
                . " WHERE gd.gateway_id=:gateway_id AND gd.is_blacklisted='N' AND gd.is_deleted =0 AND d.is_deleted = 0 AND d.device_type IN ('12','14','15','28') AND gd.device_id IN ($coins)"
                . " ORDER BY d.device_id, d.device_type, d.updated_on DESC";
            
            }  

            $db->query($sQuery);        
            $db->bind(':gateway_id',$gatewayId);
            $res = $db->resultSet();
            
        
            $finalResult['accStream']       = [];

            if(is_array($res) && !empty($res)){
                foreach ($res as $key => $value) {
                    $device_ids = $value['device_id']; 
                    $gateway_ids = $value['gateway_id'];
                    $sQuery = " SELECT * FROM device_settings WHERE device_sensor ='Accelerometer Stream' AND gateway_id ='$gatewayId' AND device_id = '$device_ids'  ORDER BY ds_id DESC LIMIT 1";
                        $db->query($sQuery); 
                        $sen_status = $db->single();
                        $pQuery = " SELECT * FROM device_settings WHERE device_sensor ='Predictive Maintenance' AND gateway_id ='$gatewayId' AND device_id = '$device_ids'  ORDER BY ds_id DESC LIMIT 1";
                        $db->query($pQuery); 
                        $predective_sen_status = $db->single();
                        if($sen_status['sensor_active'] == 'Y'){
                            $res[$key]['accelerometer_stream_sensor_status'] = $sen_status['sensor_active'];
                        }elseif($predective_sen_status['sensor_active'] == 'Y'){
                            $res[$key]['accelerometer_stream_sensor_status'] = $predective_sen_status['sensor_active'];
                        }else{
                            $res[$key]['accelerometer_stream_sensor_status'] = $sen_status['sensor_active'];
                        }
                                                    
                        $finalResult['accStream'][] = $res[$key];
                
                    
                }
            }

            $aList[JSON_TAG_RESULT]= $finalResult;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
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

public function getGeneralSettings($userId){
        $db       = new ConnectionManager();

        try {      
            $sQuery = " SELECT  date_format, rms_values, temp_unit, mail_alert_restriction, mail_restriction_limit, mail_restriction_interval,starttime,endtime"
                    . " FROM users"                    
                    . " WHERE user_id=:user_id AND is_deleted =0";       

            $db->query($sQuery);  
            $db->bind(':user_id',$userId);
            $row = $db->resultSet();
            
            $finalArr  = [];
			foreach ($row as $key => $value) {
                $date_format  =  $value['date_format'];
				$rms_values  =  $value['rms_values'];
				$temp_unit = $value['temp_unit'];
                $mail_alert_restriction = $value['mail_alert_restriction'];
                $mail_restriction_limit = $value['mail_restriction_limit'];
                $mail_restriction_interval = $value['mail_restriction_interval'];
                $starttime = $value['starttime'];
                $endtime = $value['endtime'];
		   
				$finalArr[$key]['date_format'] = $date_format;
				$finalArr[$key]['rms_values'] = $rms_values;
				$finalArr[$key]['temp_unit'] = $temp_unit;        
                $finalArr[$key]['mail_restriction_limit'] = $mail_restriction_limit;
                $finalArr[$key]['mail_alert_restriction'] = $mail_alert_restriction;  
                $finalArr[$key]['mail_restriction_interval'] = $mail_restriction_interval;        
                $finalArr[$key]['starttime'] = $starttime;   
                $finalArr[$key]['endtime'] = $endtime;       
				$finalArr[$key]['generalsettings']   = $this->getUserSettings($db,$userId);
				$finalArr[$key]['dailyreportsettings']   = $this->getDailyReportSettings($db,$userId);

            }

					
            
            $aList[JSON_TAG_RESULT]= $finalArr;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }

function updateGeneralSettings($inData, $userId) {
        
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();
        try {           
            	$date_format = $inData[JSON_TAG_DATE_FORMAT]; 
		$rms_values = $inData[JSON_TAG_RMS_VALUES];
		$temp_unit = $inData[JSON_TAG_TEMP_UNIT];      		
		            
                        
            	$response = FALSE;
	
		
		
		if($date_format != ''){
            		$query = "UPDATE users SET date_format=:date_format WHERE user_id=:user_id AND is_deleted =0";
                	$db->query($query);
                	$db->bind(':date_format', $date_format);
			$db->bind(':user_id',$userId);

                	if($db->execute()){
				$_SESSION['date_format'] = $date_format;
				$response = TRUE;
			}
		}

		if($rms_values != ''){
            		$query = "UPDATE users SET rms_values=:rms_values WHERE user_id=:user_id AND is_deleted =0";
                	$db->query($query);
                	$db->bind(':rms_values', $rms_values);
			$db->bind(':user_id',$userId);

                	if($db->execute()){
				$_SESSION['rms_values'] = $rms_values;
				$response = TRUE;
			}
		}

		if($temp_unit != ''){
            		$query = "UPDATE users SET temp_unit=:temp_unit WHERE user_id=:user_id AND is_deleted =0";
                	$db->query($query);
                	$db->bind(':temp_unit', $temp_unit);
			$db->bind(':user_id',$userId);

                	if($db->execute()){
				$_SESSION['temp_unit'] = $temp_unit;
				$response = TRUE;
			}
		}
			                                                                         	

            $aList[JSON_TAG_RESULT] = $response;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
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


public function getAcceTypesDevices($gatewayId, $coins, $sensors){
        $db       = new ConnectionManager();
	                  	 
		
		$sensor = '';
		if($sensors != "0"){
			$sen='';
			$arr = explode(',',$sensors);

			for($i=0; $i<sizeof($arr); $i++  ){
				$sen = "'" . $arr[$i] . "',";
				$sensor .= $sen;
			}

			$sensors = rtrim($sensor,',');
		}

      
        try {      
		if($coins == "0" && $sensors == "0"){	 

        		$sQuery = " SELECT gd.gd_id,gd.gateway_id,gd.nick_name,gd.device_id,d.d_id,d.device_type,d.device_value,d.threshold_value,d.format,d.rate_value,d.added_on,d.updated_on "
                	. " FROM gateway_devices as gd"
                	. " LEFT JOIN devices as d"                   
                	. " ON gd.device_id=d.device_id AND gd.gateway_id=d.gateway_id" 
			. " WHERE gd.gateway_id=:gateway_id AND gd.is_blacklisted='N' AND gd.is_deleted =0 AND d.is_deleted = 0 AND d.device_type IN ('17','18','19', '20','21','22', '23','24','25','29','30','31') ORDER BY d.updated_on DESC";             

        	}elseif($coins != "0" && $sensors == "0"){

			$sQuery = " SELECT gd.gd_id,gd.gateway_id,gd.nick_name,gd.device_id,d.d_id,d.device_type,d.device_value,d.threshold_value,d.format,d.rate_value,d.added_on,d.updated_on "
                	. " FROM gateway_devices as gd"
                	. " LEFT JOIN devices as d"                   
                	. " ON gd.device_id=d.device_id AND gd.gateway_id=d.gateway_id" 
			. " WHERE gd.gateway_id=:gateway_id AND gd.is_blacklisted='N' AND gd.is_deleted =0 AND d.is_deleted = 0 AND gd.device_id IN ($coins) AND d.device_type IN ('17','18','19', '20','21','22', '23','24','25','29','30','31') ORDER BY d.updated_on DESC";			

		}elseif($coins != "0" && $sensors != "0"){

			$sQuery = " SELECT gd.gd_id,gd.gateway_id,gd.nick_name,gd.device_id,d.d_id,d.device_type,d.device_value,d.threshold_value,d.format,d.rate_value,d.added_on,d.updated_on "
                	. " FROM gateway_devices as gd"
                	. " LEFT JOIN devices as d"                   
                	. " ON gd.device_id=d.device_id AND gd.gateway_id=d.gateway_id" 
			. " WHERE gd.gateway_id=:gateway_id AND gd.is_blacklisted='N' AND gd.is_deleted =0 AND d.is_deleted = 0 AND gd.device_id IN ($coins) AND d.device_type IN ($sensors) ORDER BY d.updated_on DESC";	
			
		}elseif($coins == "0" && $sensors != "0"){

			$sQuery = " SELECT gd.gd_id,gd.gateway_id,gd.nick_name,gd.device_id,d.d_id,d.device_type,d.device_value,d.threshold_value,d.format,d.rate_value,d.added_on,d.updated_on "
                	. " FROM gateway_devices as gd"
                	. " LEFT JOIN devices as d"                   
                	. " ON gd.device_id=d.device_id AND gd.gateway_id=d.gateway_id" 
			. " WHERE gd.gateway_id=:gateway_id AND gd.is_blacklisted='N' AND gd.is_deleted =0 AND d.is_deleted = 0 AND d.device_type IN ($sensors) ORDER BY d.updated_on DESC";	
			
		}

	 
        	
        	            
        	
    		$db->query($sQuery);        
        	$db->bind(':gateway_id',$gatewayId);
        	$res = $db->resultSet();
        

        	$finalResult['acceleration']   = [];
        	$finalResult['velocity']   = [];
        	$finalResult['displacement']   = [];
        
		if(is_array($res) && !empty($res)){
            		foreach ($res as $key => $value) {
                
                		if($value['device_type'] == '17' || $value['device_type'] == '18' || $value['device_type'] == '19'){
                    			$finalResult['acceleration'][] = $res[$key];
                		}
				elseif($value['device_type'] == '20' || $value['device_type'] == '21' || $value['device_type'] == '22'){
                    			$finalResult['velocity'][] = $res[$key];
                		}
				elseif($value['device_type'] == '23' || $value['device_type'] == '24' || $value['device_type'] == '25'){
                    			$finalResult['displacement'][] = $res[$key];
                		}
			}
		}
	
                

            $aList[JSON_TAG_RESULT]= $finalResult;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
}


function addLogo($userId,$inData) {
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();

        try {

       	        $logo = $inData[JSON_TAG_LOGO_IMAGE];

		$sQuery = " UPDATE users"
                        . " SET logo=:logo"
                        . " WHERE user_id=:user_id";       
                $db->query($sQuery);
		$db->bind(':user_id', $userId);
		$db->bind(':logo', $logo);                
		
                $db->execute(); 

            	$aList[JSON_TAG_RESULT] = $row;
            	$aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            	$aList[JSON_TAG_STATUS] = 1;
        }

        return $aList;
} 



function setDetectionPeriod($inData) {
        

        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();

        try {
            
            $gatewayId       =     $inData[JSON_TAG_GATEWAY_ID];
            $deviceId        =     $inData[JSON_TAG_DEVICE_ID];
            
            $device_type     =     $inData[JSON_TAG_DEVICE_TYPE];
            $format          =     $inData[JSON_TAG_FORMAT];
            
            $rate_value       =     $inData[JSON_TAG_RATE_VALUE];

            			
            // Check Mandatory field   
            if (empty($gatewayId) || empty($deviceId) || empty($rate_value)) {
                $aList[JSON_TAG_STATUS] = 2;
                return $aList;
            }

            //Check Gateway exists or not
            $rowGateway = $this->checkGateway($db, $gatewayId);
            if (empty($rowGateway)) {
                $aList[JSON_TAG_STATUS] = 3;
                return $aList;
            }
            
            //Check Device Gateway Exists or not
            $rowDeviceGateway = $this->checkDeviceGateway($db, $deviceId, $gatewayId, $device_type);
            if (empty($rowDeviceGateway)) {
                $aList[JSON_TAG_STATUS] = 4;
                return $aList;
            }  

		  

			               
                //convert dec to hex
		$format        = dechex($format);

		if($format == 16){
			 
			if($rate_value >= 6){
				
				//Check the stream rate to compare
            			$rowAccStreamRate = $this->checkAccStreamRate($db, $deviceId, $gatewayId);		
            			if(!empty($rowAccStreamRate)) {

					$aList[JSON_TAG_STATUS] = 6;
                			return $aList;

				}
				
				//Check the stream rate to compare
            			$HourAccStreamRate = $this->checkAccStreamRateHour($db, $deviceId, $gatewayId);
		
            			if(empty($HourAccStreamRate)) {
					$aList[JSON_TAG_STATUS] = 6;
                			return $aList;
            			} 

			}else{
				//Check the stream rate to compare
            			$rowAccStreamRate = $this->checkAccStreamRate($db, $deviceId, $gatewayId);
		
            			if(!empty($rowAccStreamRate)) {
        					$accstream_rate = $rowAccStreamRate[0][rate_value];
        					$accstream_rate = hexdec($accstream_rate);

        					if($rate_value >= $accstream_rate){
                        				$aList[JSON_TAG_STATUS] = 6;
                        				return $aList;
        					}

        					if($accstream_rate < ($rate_value * 10)){
        						$aList[JSON_TAG_STATUS] = 6;
                        				return $aList;
        					}
            			}

			}
		}


		$rate_value    = dechex($rate_value);
		
		if($rate_value<=9){
                    $rate_value = sprintf("%02s", $rate_value);
                    $rate_value = strtoupper($rate_value);
                }
                                

		
		$pendingRequest = $this->checkPendingRequest($db, $gatewayId, $deviceId, $device_type, 'SETS');

		if(empty($pendingRequest)){       	
			$GatewayMacID = $this->getGatewayMacID($db, $gatewayId);
			if(!empty($GatewayMacID)){
				$gateway_mac_id = $GatewayMacID[0]['gateway_mac_id'];
				$dynamicID = $this->getDynamicID($db, $gatewayId, $deviceId);
				$dynamic_id = $dynamicID[0][dynamic_id];									

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


				$query = "INSERT INTO request_list(gateway_id, gateway_mac_id, device_id, device_type, request_type, format, rate_value, dynamic_id, action) "
                       			. "VALUES (:gatewayId, :gateway_mac_id, :deviceId, :device_type, 'DETP', :format, :rate_value, :dynamic_id, 0)";
        		
				$db->query($query);
        			$db->bind(':gatewayId', $gatewayId);
        			$db->bind(':gateway_mac_id', $gateway_mac_id);
				$db->bind(':deviceId', $deviceId);
				$db->bind(':device_type', $device_type);
				$db->bind(':format', $format);
				$db->bind(':rate_value', $rate_value);
				$db->bind(':dynamic_id', $dynamic_id);

				

				if($db->execute()){
					$response = 'TRUE';
				}
	
				$aList[JSON_TAG_STATUS] = 0;
			}else{
				$aList[JSON_TAG_STATUS] = 1;
			}
			
		}
		else{
			$response = 'TRUE';
			$aList[JSON_TAG_STATUS] = 5;
		}

            $aList[JSON_TAG_RESULT] = $response;

        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }


function checkDetectionPeriod($db, $deviceId, $gatewayId){
        
        $sQuery = " SELECT rate_value"
                . " FROM devices"                    
                . " WHERE device_id=:device_id AND device_type='14' AND format = '16' AND gateway_id=:gateway_id AND is_deleted =0 ORDER BY d_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':device_id',$deviceId);
        $db->bind(':gateway_id',$gatewayId);
        
        return  $row = $db->single();
} 


function checkAccStreamRate($db, $deviceId, $gatewayId){
        
        $sQuery = " SELECT rate_value"
                . " FROM devices"                    
                . " WHERE device_id=:device_id AND device_type='12' AND format = '16' AND gateway_id=:gateway_id AND is_deleted =0 ORDER BY d_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':device_id',$deviceId);
        $db->bind(':gateway_id',$gatewayId);
        
        return  $row = $db->single();
} 


function checkAccStreamRateHour($db, $deviceId, $gatewayId){
        
        $sQuery = " SELECT rate_value"
                . " FROM devices"                    
                . " WHERE device_id=:device_id AND device_type='12' AND format = '21' AND gateway_id=:gateway_id AND is_deleted =0 ORDER BY d_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':device_id',$deviceId);
        $db->bind(':gateway_id',$gatewayId);
        
        return  $row = $db->single();
} 

function setCoinFrequency($inData) {
        

        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();
        try {
            
            	$gatewayId       =     $inData[JSON_TAG_GATEWAY_ID];
	    	$device_id  = $inData[JSON_TAG_DEVICE_ID];
		$frequency     =   $inData[JSON_TAG_FREQUENCY];             
		
		            
            // Check Mandatory field    
            if (empty($gatewayId) || empty($device_id) || empty($frequency)) {
                $aList[JSON_TAG_STATUS] = 2;
                return $aList;
            }
            
            $response = FALSE;
	
            $pendingRequest = $this->checkPendingRequest($db, $gatewayId, $device_id, $device_type, 'FREQ');

		if(empty($pendingRequest)){ 
			$format = '00';

			$GatewayMacID = $this->getGatewayMacID($db, $gatewayId);
			if(!empty($GatewayMacID)){

				$gateway_mac_id = $GatewayMacID[0]['gateway_mac_id'];

				$dynamicID = $this->getDynamicID($db, $gatewayId, $device_id);
				$dynamic_id = $dynamicID[0][dynamic_id];

				$coinFirmware = $this->getCoinFirmwareType($db, $gatewayId, $device_id);
				$firmware_type = $coinFirmware[0][firmware_type];
				
				if($firmware_type == 'Predictive Maintenance'){
					$device_type = '76';
				}else{
					$device_type = '18';
				}

                    		$data_arr  =  array();
                    		$data_arr['gateway_mac_id']    =  $gateway_mac_id;
                    		$data_arr['gateway_id']    =  $gatewayId;
                    		$data_arr['device_id']     =  $device_id;
                    		$data_arr['device_type']   =  $device_type;
                    
                    		$data_arr['format']        =  $format;
                    
                    		$data_arr['rate_value']     =  $frequency;
                    		$data_arr['dynamic_id']     =  $dynamic_id;
                    
                    		$data_arr['topic']         =  TOPIC_SET_STREAM;
            
                		$url        =  URL_END_POINT.'sensegiz-mqtt/publish.php';
                
                		$response   =  $curl->postRequestData($url, $data_arr);

			

			
				$query = "INSERT INTO request_list(gateway_id, gateway_mac_id, device_id, device_type, request_type, format, rate_value, dynamic_id, action) "
                       			. "VALUES (:gatewayId, :gateway_mac_id, :deviceId, :device_type, 'FREQ', :format, :rate_value, :dynamic_id, 0)";
        		
				$db->query($query);
        			$db->bind(':gatewayId', $gatewayId);
        			$db->bind(':gateway_mac_id', $gateway_mac_id);
				$db->bind(':deviceId', $device_id);
				$db->bind(':device_type', $device_type);
				$db->bind(':format', $format);
				$db->bind(':rate_value', $frequency);
				$db->bind(':dynamic_id', $dynamic_id);

				if($db->execute()){
					$response = 'TRUE';
				}   

			 	$aList[JSON_TAG_RESULT] = $response;
            			$aList[JSON_TAG_STATUS] = 0; 

			}else{
				$aList[JSON_TAG_STATUS] = 1;
			}       			

                }else{
			$response = 'TRUE';
			$aList[JSON_TAG_STATUS] = 5;
		}
               
                                 
            	

           
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
}

function setCoinStreamThreshold($inData) {
        

        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();

        try {
            
            	$gatewayId       =     $inData[JSON_TAG_GATEWAY_ID];
	    	$device_id  = $inData[JSON_TAG_DEVICE_ID];            
            	$sensor_type     =     $inData[JSON_TAG_SENSOR_TYPE];

		$lowthreshold = $inData[JSON_TAG_LOW_THRESHOLD];
	    	$highthreshold = $inData[JSON_TAG_HIGH_THRESHOLD];

	
            
            	// Check Mandatory field    
            	if (empty($gatewayId) || empty($device_id) || empty($sensor_type)  || empty($lowthreshold) || empty($highthreshold)) {
                	$aList[JSON_TAG_STATUS] = 2;
                	return $aList;
            	}
            
            $response = FALSE;
            //Check Device Settings Exists or not
            $rowDeviceSettings= $this->checkDeviceSettingsExists($db, $gatewayId, $device_id, $sensor_type);	

            if(empty($rowDeviceSettings)) {
                	//Insert newly
                	$query = "INSERT INTO device_settings(gateway_id, device_id, device_sensor, low_threshold, high_threshold) "
                       		 . "VALUES (:gateway_id, :device_id, :sensor_type, :lowthreshold, :highthreshold)";
                	$db->query($query);
                	$db->bind(':gateway_id', $gatewayId);
                	$db->bind(':sensor_type', $sensor_type);
			$db->bind(':device_id', $device_id);
			$db->bind(':lowthreshold', $lowthreshold);
			$db->bind(':highthreshold', $highthreshold);
			
			if($db->execute()){
					$response = 'TRUE';
			}
				
            		$aList[JSON_TAG_STATUS] = 0;		
                               
                
            }      
            else{
               			
                    	$query = "UPDATE device_settings SET low_threshold=:lowthreshold , high_threshold=:highthreshold, updated_on=DATE_TRUNC('second', NOW())"
                       		. "WHERE gateway_id =:gateway_id AND device_id=:device_id AND device_sensor=:sensor_type AND is_deleted = 0";
        		
			$db->query($query);
        		$db->bind(':gateway_id', $gatewayId);
                	$db->bind(':sensor_type', $sensor_type);
			$db->bind(':device_id', $device_id);
			$db->bind(':lowthreshold', $lowthreshold);
			$db->bind(':highthreshold', $highthreshold);

			if($db->execute()){
					$response = 'TRUE';
			}
            		$aList[JSON_TAG_STATUS] = 0;		
                               
            }

		

            $aList[JSON_TAG_RESULT] = $response;

        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
}


function updateDeviceEmailNotification($inData) {
        

        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();
        try {
            
            $gatewayId       =     $inData[JSON_TAG_GATEWAY_ID];
            $deviceId       =     $inData[JSON_TAG_DEVICE_ID];
            $email_alert     =     $inData[JSON_TAG_EMAIL_ALERT];            
            $sensor_type     =     $inData[JSON_TAG_SENSOR_TYPE];
            
            // Check Mandatory field    
            if (empty($gatewayId) || empty($deviceId) || empty($email_alert) || empty($sensor_type)) {
                $aList[JSON_TAG_STATUS] = 2;
                return $aList;
            }
            
            $response = FALSE;
            //Check Gateway Settings Exists or not
            $rowDeviceSettings= $this->checkDeviceSettingsExists($db, $gatewayId, $deviceId, $sensor_type);

            if (empty($rowDeviceSettings)) {
                //Insert newly
                $query = "INSERT INTO device_settings(gateway_id, device_id, device_sensor, email_alert) "
                        . "VALUES (:gateway_id, :device_id, :sensor_type, :email_alert)";
                $db->query($query);
                $db->bind(':gateway_id', $gatewayId);
                $db->bind(':device_id', $deviceId);
                $db->bind(':sensor_type', $sensor_type);
                $db->bind(':email_alert', $email_alert);
                if($db->execute()){
                    $response = TRUE;
                }               
                
            }      
            else{
                //Update existed
                $sQuery = " UPDATE device_settings "
                        . " SET email_alert=:email_alert, updated_on=DATE_TRUNC('second', NOW())"
                        . " WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_sensor=:sensor_type AND is_deleted =0";
                $db->query($sQuery);
                $db->bind(':gateway_id', $gatewayId);
                $db->bind(':device_id', $deviceId);
                $db->bind(':sensor_type', $sensor_type);
                $db->bind(':email_alert', $email_alert);
                if($db->execute()){
                    $response = TRUE;
                }                
            }


            $aList[JSON_TAG_RESULT] = $response;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
}


function updateShownSensors($userId, $inData) {
        
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();

        try {
            	


            	$acc = $inData[JSON_TAG_ACCELEROMETER]; 
		$gyro = $inData[JSON_TAG_GYROSCOPE]; 
		$temp = $inData[JSON_TAG_TEMPERATURE]; 
		$humid = $inData[JSON_TAG_HUMIDITY];                        
            	$stream = $inData[JSON_TAG_STREAM];
		$accstream = $inData[JSON_TAG_ACCSTREAM];
		$tempstream = $inData[JSON_TAG_TEMPSTREAM];
		$humidstream = $inData[JSON_TAG_HUMIDSTREAM];
		$spectrumstream = $inData[JSON_TAG_SPECTRUMSTREAM];

            	$response = FALSE;
					
            	$rowUserSensorSettings= $this->checkUserSensorSettings($db, $userId);		
		
		if(empty($rowUserSensorSettings)){
			$query = "INSERT INTO general_settings (user_id, accelerometer, gyroscope, temperature, humidity, stream, accelerometerstream, temperaturestream, humiditystream, predictivestream) "
				. "VALUES (:user_id, :acc, :gyro, :temp, :humid, :stream, :accstream, :tempstream, :humidstream, :spectrumstream)";
			$db->query($query);
                	$db->bind(':acc', $acc);
                	$db->bind(':gyro', $gyro);
                	$db->bind(':temp', $temp);
                	$db->bind(':humid', $humid);
                	$db->bind(':stream', $stream);
                	$db->bind(':accstream', $accstream);
			$db->bind(':tempstream', $tempstream);
			$db->bind(':humidstream', $humidstream);
			$db->bind(':spectrumstream', $spectrumstream);
			$db->bind(':user_id',$userId);

                	if($db->execute()){
				$response = TRUE;
			}

		}else{

            		$query = "UPDATE general_settings SET accelerometer=:acc, gyroscope=:gyro, temperature=:temp, humidity=:humid, stream=:stream, accelerometerstream=:accstream, temperaturestream=:tempstream, humiditystream=:humidstream, predictivestream=:spectrumstream, updated_on=DATE_TRUNC('second', NOW()) "
				." WHERE user_id=:user_id";
                	$db->query($query);
                	$db->bind(':acc', $acc);
                	$db->bind(':gyro', $gyro);
                	$db->bind(':temp', $temp);
                	$db->bind(':humid', $humid);
                	$db->bind(':stream', $stream);
                	$db->bind(':accstream', $accstream);
			$db->bind(':tempstream', $tempstream);
			$db->bind(':humidstream', $humidstream);
			$db->bind(':spectrumstream', $spectrumstream);
			$db->bind(':user_id',$userId);

                	if($db->execute()){
				$response = TRUE;
			}
		}	                                                                         	

            	$aList[JSON_TAG_RESULT] = $response;
           	$aList[JSON_TAG_STATUS] = 0;

        } catch (Exception $e) {
            	$aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
}


function checkUserSensorSettings($db, $userId){

	$sQuery = " SELECT *"
               . " FROM general_settings"                    
                . " WHERE user_id = :user_id";       
        
       	$db->query($sQuery);         
	$db->bind(':user_id',$userId);
   
       	return  $row = $db->single();

}


function getUserSettings($db, $userId){
            $sQuery = " SELECT *"
                    . " FROM general_settings"                    
                    . " WHERE user_id = :user_id";       

            $db->query($sQuery);  
           $db->bind(':user_id',$userId);

            $row = $db->resultSet();
            
            return $row;
    }

function updateGenerateReport($userId, $inData) {
        
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();

        try {
            	


            	$acc = $inData[JSON_TAG_ACCELEROMETER]; 
		$gyro = $inData[JSON_TAG_GYROSCOPE]; 
		$temp = $inData[JSON_TAG_TEMPERATURE]; 
		$humid = $inData[JSON_TAG_HUMIDITY];                        
            	$tempstream = $inData[JSON_TAG_TEMPSTREAM];
		$humidstream = $inData[JSON_TAG_HUMIDSTREAM];

		$starttime = $inData[JSON_TAG_STARTTIME];
		$endtime = $inData[JSON_TAG_ENDTIME];

		$sendreport = $inData[JSON_TAG_DAILYREPORT];

		$zone = $inData[JSON_TAG_TIMEZONE];

		$sendtime = $inData[JSON_TAG_SENDTIME];

            	$response = FALSE;
					
            	$rowDailyReportSettings= $this->checkDailyReportSettings($db, $userId);		
		
		if(empty($rowDailyReportSettings)){
			$query = "INSERT INTO daily_report (user_id, accelerometer, gyroscope, temperature, humidity, temperaturestream, humiditystream, report_start_time, report_end_time, send_report, timezone, report_send_time) "
				. "VALUES (:user_id, :acc, :gyro, :temp, :humid, :tempstream, :humidstream, :starttime, :endtime, :sendreport, :zone, :sendtime)";
			$db->query($query);
                	$db->bind(':acc', $acc);
                	$db->bind(':gyro', $gyro);
                	$db->bind(':temp', $temp);
                	$db->bind(':humid', $humid);
                	$db->bind(':tempstream', $tempstream);
                	$db->bind(':humidstream', $humidstream);
			$db->bind(':user_id',$userId);
			$db->bind(':starttime',$starttime);
			$db->bind(':endtime',$endtime);
			$db->bind(':sendreport',$sendreport);
			$db->bind(':zone',$zone);
			$db->bind(':sendtime',$sendtime);

                	if($db->execute()){
				$response = TRUE;
			}

		}else{

            		$query = "UPDATE daily_report SET accelerometer=:acc, gyroscope=:gyro, temperature=:temp, humidity=:humid, temperaturestream=:tempstream, humiditystream=:humidstream, report_start_time=:starttime, report_end_time=:endtime, send_report=:sendreport, timezone=:zone , report_send_time=:sendtime, updated_on=DATE_TRUNC('second', NOW()) "
				." WHERE user_id=:user_id";
                	$db->query($query);
                	$db->bind(':acc', $acc);
                	$db->bind(':gyro', $gyro);
                	$db->bind(':temp', $temp);
                	$db->bind(':humid', $humid);
                	$db->bind(':tempstream', $tempstream);
                	$db->bind(':humidstream', $humidstream);
			$db->bind(':user_id',$userId);
			$db->bind(':starttime',$starttime);
			$db->bind(':endtime',$endtime);
			$db->bind(':sendreport',$sendreport);
			$db->bind(':zone',$zone);
			$db->bind(':sendtime',$sendtime);

                	if($db->execute()){
				$response = TRUE;
			}
		}	                                                                         	

            	$aList[JSON_TAG_RESULT] = $response;
           	$aList[JSON_TAG_STATUS] = 0;

        } catch (Exception $e) {
            	$aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
}


function checkDailyReportSettings($db, $userId){

	$sQuery = " SELECT *"
               . " FROM daily_report"                    
                . " WHERE user_id = :user_id";       
        
       	$db->query($sQuery);         
	$db->bind(':user_id',$userId);
   
       	return  $row = $db->single();

}


function getDailyReportSettings($db, $userId){
            $sQuery = " SELECT *"
                    . " FROM daily_report"                    
                    . " WHERE user_id = :user_id";       

            $db->query($sQuery);  
           $db->bind(':user_id',$userId);

            $row = $db->resultSet();
            
            return $row;
    }



public function getDailyReportData($userID, $gateway_id, $device_id, $deviceType1, $startTime, $endTime){
      
	$db = new ConnectionManager();


	if($deviceType1 == '01'){
		$deviceType2 = '02';
	}
	if($deviceType1 == '03'){
		$deviceType2 = '04';
	}
	if($deviceType1 == '05'){
		$deviceType2 = '06';
	}
	if($deviceType1 == '07'){
		$deviceType2 = '08';
	}	

        try{

		
		if($deviceType1 == "09" || $deviceType1 == "10"){
			$dQuery = "SELECT device_type, device_value, updated_on"
                 		." FROM devices_stream"
                 		." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type='$deviceType1' AND is_deleted = 0"
				." AND updated_on BETWEEN :startTime AND :endTime"
		 		." ORDER BY updated_on DESC ";
		
		}		
		else
		{
	    		$dQuery = "SELECT device_type, device_value, low_threshold, high_threshold, updated_on"
                 		." FROM threshold"
                 		." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type IN ('$deviceType1', '$deviceType2') AND is_deleted = 0"
				." AND updated_on BETWEEN :startTime AND :endTime"
		 		." ORDER BY updated_on DESC ";

		}


            $db->query($dQuery);


            $db->bind(':gateway_id', $gateway_id);
            $db->bind(':device_id', $device_id);
            $db->bind(':startTime', $startTime);
            $db->bind(':endTime', $endTime);
            
            $res = $db->resultSet();

            $aList[JSON_TAG_RESULT] = $res;
            $aList[JSON_TAG_STATUS] = 0;

        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }

    return $aList;

}


/* Set Mail Restriction Limit General Settings */
    function updateMailRestriction($userId, $inData) {
        
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();

        try 
        {
                $mail_restriction_condition = $inData[JSON_TAG_MAIL_RESTRICTION]; 
                $mail_limit = $inData[JSON_TAG_MAIL_RESTRICTION_LIMIT]; 
                $mail_interval = $inData[JSON_TAG_MAIL_INTERVAL_TIME];

                $response = FALSE;
                      
                $query = "UPDATE users SET mail_alert_restriction=:mail_restriction_condition, mail_restriction_limit=:mail_limit, mail_restriction_interval=:mail_interval"
                        ." WHERE user_id=:user_id";
                $db->query($query);
                $db->bind(':mail_restriction_condition', $mail_restriction_condition);
                $db->bind(':mail_limit', $mail_limit);
                $db->bind(':mail_interval', $mail_interval);
                $db->bind(':user_id',$userId);

                if($db->execute()){
                    $response = TRUE;
                }

                $aList[JSON_TAG_RESULT] = $response;
            $aList[JSON_TAG_STATUS] = 0;

        } catch (Exception $e) {
                $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }


    function getFFTbaseData($gatewayId, $deviceId, $sensor_axis, $frequency)
    {
        $db = new ConnectionManager();
        
        try
        {
            $dsQuery = "SELECT * "
                        . " FROM fft_basevalue"
                        . " WHERE is_deleted = 0 AND device_type=:sensor_axis AND gateway_id=:gatewayId AND device_id=:deviceId AND frequency_level=:frequency"
                        . " ORDER BY ffb_id DESC LIMIT 1024"; //AND device_value != '0' AND (added_on BETWEEN NOW() - INTERVAL '24 Hours' AND NOW())

            $db->query($dsQuery);
            $db->bind(':gatewayId',$gatewayId);
            $db->bind(':deviceId',$deviceId);
            $db->bind(':sensor_axis',$sensor_axis);
            $db->bind(':frequency',$frequency);
            $res=$db->resultSet();

            // return $res;
            $aList[JSON_TAG_RESULT] = $res;
            $aList[JSON_TAG_STATUS] = 0;
   
        }
        catch (Exception $e){
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;

    }


    function getFilteredFFTbaseData($gatewayId, $deviceId, $sensor_axisSelected, $startTime, $endTime, $frequency)
    {
        $db = new ConnectionManager();
        
        try
        {
            $dsQuery = "SELECT * "
                        . " FROM fft_basevalue"
                        . " WHERE is_deleted = 0 AND device_type=:sensor_axis AND gateway_id=:gatewayId AND device_id=:deviceId AND frequency_level=:frequency AND updated_on BETWEEN :startTime AND :endTime"
                        . " ORDER BY ffb_id DESC"; //AND device_value != '0'

            $db->query($dsQuery);
            $db->bind(':gatewayId',$gatewayId);
            $db->bind(':deviceId',$deviceId);
            $db->bind(':sensor_axis',$sensor_axisSelected);
            $db->bind(':startTime',$startTime);
            $db->bind(':endTime',$endTime);
            $db->bind(':frequency',$frequency);
            $res=$db->resultSet();

            // return $res;
            $aList[JSON_TAG_RESULT] = $res;
            $aList[JSON_TAG_STATUS] = 0;
   
        }
        catch (Exception $e){
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;

    }


    function getfftFilteredDatefrequency($gateway_id, $device_id, $selectedfilter_date)
    {
        $db = new ConnectionManager();
        
        $selected_date = substr($selectedfilter_date, 0, strrpos($selectedfilter_date, ' '));
        $selectedfilter_to_date = $selected_date . ' 23:59';

        try
        {
            $fdQuery = "SELECT DISTINCT(frequency_level) "
                        . " FROM fft_basevalue"
                        . " WHERE is_deleted = 0 AND gateway_id=:gatewayId AND device_id=:deviceId AND (added_on BETWEEN :selectedfilter_date AND :selectedfilter_to_date)"; //AND device_value != '0'

            $db->query($fdQuery);
            $db->bind(':gatewayId',$gateway_id);
            $db->bind(':deviceId',$device_id);
            $db->bind(':selectedfilter_date',$selectedfilter_date);
            $db->bind(':selectedfilter_to_date',$selectedfilter_to_date);
            $res=$db->resultSet();

            // return $res;
            $aList[JSON_TAG_RESULT] = $res;
            $aList[JSON_TAG_STATUS] = 0;
   
        }
        catch (Exception $e){
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;

    }


    /*
      Function            : devicesLowBattery()
      Brief               : Function to get all all Low Battery COINs of a perticular user account     
      Input param         : Nil
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function devicesLowBattery($userId){
        $db       = new ConnectionManager();
        $curl     = new CurlRequest();
        try {      
            /*$sQuery = " SELECT *"
                    . " FROM user_gateways"                    
                    . " WHERE user_id=:user_id AND is_deleted =0 AND is_blacklisted='N' ORDER BY ug_id DESC";  */     
            $sQuery = " SELECT nick_name, device_id, battery, gateway_id, updated_on"
                . " FROM gateway_devices"
                . " WHERE (user_id = :user_id) AND (is_deleted = 0) AND (battery = '03') AND (updated_on BETWEEN NOW() - INTERVAL '7 Day' AND NOW()) ORDER BY updated_on DESC"; 

            $db->query($sQuery);  
            $db->bind(':user_id',$userId);
            $row = $db->resultSet();
            
            $aList[JSON_TAG_RESULT]= $row;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }


    function updateNewPassword($userId, $inData) {
        
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();

        try 
        {
                $newPassword = $inData[JSON_TAG_NEW_PASSWORD]; 

                $response = FALSE;
                $user_new_password = md5($newPassword);
                      
                $query = "UPDATE users SET user_password=:user_new_password "
                        ." WHERE user_id=:user_id";
                $db->query($query);
                $db->bind(':user_new_password', $user_new_password);
                $db->bind(':user_id',$userId);

                if($db->execute()){
                    $response = TRUE;
                }

                $aList[JSON_TAG_RESULT] = $response;
            $aList[JSON_TAG_STATUS] = 0;

        } catch (Exception $e) {
                $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }


    function updateGatewayNickName($userId, $inData) 
    {
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl     = new CurlRequest();

        try 
        {
                $nickname = $inData[JSON_TAG_GATEWAY_NICKNAME]; 
                $gateway_id = $inData[JSON_TAG_GATEWAY_ID];

                $response = FALSE;
                      
                $query = "UPDATE user_gateways SET gateway_nick_name=:nickname "
                        ." WHERE user_id=:user_id AND gateway_id=:gateway_id AND is_deleted=0 AND is_blacklisted='N'";
                $db->query($query);
                $db->bind(':user_id',$userId);
                $db->bind(':gateway_id',$gateway_id);
                $db->bind(':nickname',$nickname);

                if($db->execute()){
                    $response = TRUE;
                }

                $aList[JSON_TAG_RESULT] = $response;
            $aList[JSON_TAG_STATUS] = 0;

        } catch (Exception $e) {
                $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }

    function updateWhatsAppAlert($userId, $inData)
    {
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
        $curl = new CurlRequest();

        try {
            $startTime = $inData['startTime'];
            $endTime = $inData['endTime'];



            $response = FALSE;

            $query = "UPDATE users SET starttime=:starttime , endtime=:endtime "
                . " WHERE user_id=:user_id";
            $db->query($query);
            $db->bind(':user_id', $userId);
            $db->bind(':starttime', $startTime);
            $db->bind(':endtime', $endTime);

            if ($db->execute()) {
                $response = TRUE;
            }

            $aList[JSON_TAG_RESULT] = $response;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }
    
    
    /*
      Function            : getGatewayDetail($gatewayId)
      Brief               : Function to get Gateway Information for admin     
      Input param         : Nil
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function getGatewayDetail($gatewayId){
        $db = new ConnectionManager();
        try {      
            $sQuery = " SELECT * "
                    . " FROM user_gateways "                                   
                    . " WHERE (gateway_id =:gateway_id) AND (is_deleted=0 OR is_deleted=2) ORDER BY ug_id DESC";                 
            $db->query($sQuery);
            $db->bind('gateway_id', $gatewayId);
            $rowSet  =  $db->resultSet();
                       
            $aList[JSON_TAG_RESULT]= $rowSet;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }
    

    /*
      Function            : updateCoinLocation()
      Brief               : Function to update coin location address
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */

    function updateCoinLocation($inData) {
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();

        try {
            $gateway_id = $inData[JSON_TAG_GATEWAY_ID];
            $device_id = $inData[JSON_TAG_DEVICE_ID];
            $coin_new_location = $inData[JSON_TAG_COIN_LOCATION];

            
            $aQuery = "UPDATE gateway_devices"
                     ." SET coin_location=:coin_new_location"
                     ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 0 AND is_blacklisted='N'";
            $db->query($aQuery);
            $db->bind(':gateway_id', $gateway_id);
            $db->bind(':device_id', $device_id);
            $db->bind(':coin_new_location', $coin_new_location);

            $row = $db->execute();

            $aList[JSON_TAG_RESULT] = $row;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }


    /*
      Function            : getPredStreamDevices($gatewayId, $coins)
      Brief               : Function to get devices connected to the gateway       
      Input param         : Nil
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function getPredStreamDevices($gatewayId, $coins){
        $db       = new ConnectionManager();
      
        try {      
		
        	if($coins == "0"){
                $sQuery = " SELECT gd.gd_id,gd.gateway_id,gd.device_mac_address,gd.nick_name,gd.coin_location,gd.device_id,gd.active,gd.is_blacklisted,d.d_id,d.device_type,d.device_value,d.get_current_value,d.threshold_value,d.format,d.rate_value,d.added_on,d.updated_on "
                        . " FROM gateway_devices as gd"
                        . " LEFT JOIN devices as d"                   
                        . " ON gd.device_id=d.device_id AND gd.gateway_id=d.gateway_id"
                . " WHERE gd.gateway_id=:gateway_id AND gd.is_blacklisted='N' AND gd.is_deleted =0 AND d.is_deleted = 0 AND d.device_type IN ('51','52','53','66')"
                . " ORDER BY d.device_id, d.device_type, d.updated_on DESC";
            }else{
                $sQuery = " SELECT gd.gd_id,gd.gateway_id,gd.device_mac_address,gd.nick_name,gd.coin_location,gd.device_id,gd.active,gd.is_blacklisted,d.d_id,d.device_type,d.device_value,d.get_current_value,d.threshold_value,d.format,d.rate_value,d.added_on,d.updated_on "
                        . " FROM gateway_devices as gd"
                        . " LEFT JOIN devices as d"                   
                        . " ON gd.device_id=d.device_id AND gd.gateway_id=d.gateway_id"
                . " WHERE gd.gateway_id=:gateway_id AND gd.is_blacklisted='N' AND gd.is_deleted =0 AND d.is_deleted = 0 AND d.device_type IN ('51','52','53','66') AND gd.device_id IN ($coins)"
                . " ORDER BY d.device_id, d.device_type, d.updated_on DESC";
            
            }  

            $db->query($sQuery);        
            $db->bind(':gateway_id',$gatewayId);
            $res = $db->resultSet();
            
            $finalResult['predStream'] = [];

            if(is_array($res) && !empty($res)){
                foreach ($res as $key => $value) {
                    $device_ids = $value['device_id']; 
                    $gateway_ids = $value['gateway_id'];
                    $sQuery = " SELECT * FROM device_settings WHERE device_sensor ='Accelerometer Stream' AND gateway_id ='$gatewayId' AND device_id = '$device_ids'  ORDER BY ds_id DESC LIMIT 1";
                        $db->query($sQuery); 
                        $sen_status = $db->single();
                        $pQuery = " SELECT * FROM device_settings WHERE device_sensor ='Predictive Maintenance' AND gateway_id ='$gatewayId' AND device_id = '$device_ids'  ORDER BY ds_id DESC LIMIT 1";
                        $db->query($pQuery); 
                        $predective_sen_status = $db->single();
                        if($sen_status['sensor_active'] == 'Y'){
                            $res[$key]['accelerometer_stream_sensor_status'] = $sen_status['sensor_active'];
                        }elseif($predective_sen_status['sensor_active'] == 'Y'){
                            $res[$key]['accelerometer_stream_sensor_status'] = $predective_sen_status['sensor_active'];
                        }else{
                            $res[$key]['accelerometer_stream_sensor_status'] = $sen_status['sensor_active'];
                        }
                                                    
                        $finalResult['predStream'][] = $res[$key];
                }
            }

            $aList[JSON_TAG_RESULT]= $finalResult;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }


    /*
        Function        : analyticsPredStream($gatewayId, $deviceId, $deviceType)
        Brief           : Function to get 3-axis Predictive Stream data for a specific coin
        Input param     : Nil
        Output param        : Array
        Return          : Returns array
    */

    public function analyticsPredStream($gatewayId, $deviceId, $deviceType){
        $db = new ConnectionManager();

        try{
            if($deviceType == '51'){
                  $dQuery = "SELECT * FROM (SELECT device_type, device_value, updated_on"
                         ." FROM predictive_stream"
                         ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '51' AND is_deleted = 0"
                         ." AND updated_on between date_trunc('hour', current_timestamp) and date_trunc('hour', current_timestamp + interval '1 hour')"
                ." ORDER BY updated_on,pdump_id_counter DESC LIMIT 8000) AS a UNION ALL " //updated_on
                ."SELECT * FROM (SELECT device_type, device_value, updated_on"
                        ." FROM predictive_stream"
                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '52' AND is_deleted = 0"
                        ." AND updated_on between date_trunc('hour', current_timestamp) and date_trunc('hour', current_timestamp + interval '1 hour')"
                ." ORDER BY updated_on,pdump_id_counter DESC LIMIT 8000) AS b UNION ALL "
                ."SELECT * FROM (SELECT device_type, device_value, updated_on"
                        ." FROM predictive_stream"
                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '53' AND is_deleted = 0"
                        ." AND updated_on between date_trunc('hour', current_timestamp) and date_trunc('hour', current_timestamp + interval '1 hour')"
                ." ORDER BY updated_on,pdump_id_counter DESC LIMIT 8000) AS c UNION ALL "
                ."SELECT * FROM (SELECT device_type, device_value, updated_on"
                        ." FROM predictive_stream"
                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '66' AND is_deleted = 0"
                        ." AND updated_on between date_trunc('hour', current_timestamp) and date_trunc('hour', current_timestamp + interval '1 hour')"
                ." ORDER BY updated_on,pdump_id_counter DESC LIMIT 8000) AS d";

            }

	        elseif($deviceType == '57'){
            	$dQuery = "SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                 ." FROM acceleration"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '57' AND is_deleted = 0"
                ." ORDER BY added_on DESC LIMIT 100) AS a UNION ALL "
                ."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                        ." FROM acceleration"
                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '58' AND is_deleted = 0"
                ." ORDER BY added_on DESC LIMIT 100) AS b UNION ALL "
                ."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                        ." FROM acceleration"
                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '59' AND is_deleted = 0"
                ." ORDER BY added_on DESC LIMIT 100) AS c  UNION ALL "
                ."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                        ." FROM acceleration"
                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '67' AND is_deleted = 0"
                ." ORDER BY added_on DESC LIMIT 100) AS d";    
            }

	        elseif($deviceType == '60'){
            	$dQuery = "SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                 ." FROM velocity"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '60' AND is_deleted = 0"
                ." ORDER BY added_on DESC LIMIT 100) AS a UNION ALL "
                ."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                        ." FROM velocity"
                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '61' AND is_deleted = 0"
                ." ORDER BY added_on DESC LIMIT 100) AS b UNION ALL "
                ."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                        ." FROM velocity"
                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '62' AND is_deleted = 0"
                ." ORDER BY added_on DESC LIMIT 100) AS c UNION ALL "
                ."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                        ." FROM velocity"
                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '68' AND is_deleted = 0"
                ." ORDER BY added_on DESC LIMIT 100) AS d";
            }
	    
	        elseif($deviceType == '63'){
            	$dQuery = "SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                 ." FROM displacement"
                 ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '63' AND is_deleted = 0"
                ." ORDER BY added_on DESC LIMIT 100) AS a UNION ALL "
                ."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                        ." FROM displacement"
                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '64' AND is_deleted = 0"
                ." ORDER BY added_on DESC LIMIT 100) AS b UNION ALL "
                ."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                        ." FROM displacement"
                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '65' AND is_deleted = 0"
                ." ORDER BY added_on DESC LIMIT 100) AS c UNION ALL "
                ."SELECT * FROM (SELECT device_type, device_value, added_on AS updated_on"
                        ." FROM displacement"
                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type = '69' AND is_deleted = 0"
                ." ORDER BY added_on DESC LIMIT 100) AS d";
	        }

            $db->query($dQuery);
            $db->bind(':gateway_id', $gatewayId);
            $db->bind(':device_id', $deviceId);
            $res = $db->resultSet();
            $aList[JSON_TAG_RESULT] = $res;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }


    /*
    Function        : analyticsPredStreamFilteredData($gatewayId, $deviceId, $deviceType1, $startTime, $endTime)
    Brief           : Function to get help sensor data of a specific coin
    Input param     : Nil
    Output param    : Array
    Return          : Returns array
    */

    public function analyticsPredStreamFilteredData($gatewayId, $deviceId, $deviceType1, $startTime, $endTime){
        $db = new ConnectionManager();
        
        try{
                    
            if($deviceType1 == '51'){		
                $dQuery = "SELECT device_type, device_value, updated_on"
                        ." FROM predictive_stream"
                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type IN ('$deviceType1', '52', '53', '66') AND is_deleted = 0"
                ." AND updated_on BETWEEN :from_date AND :to_date"
                ." ORDER BY pdump_id_counter DESC";
            }
            elseif($deviceType1 == '57'){		
                $dQuery = "SELECT device_type, device_value, added_on as updated_on"
                        ." FROM acceleration"
                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type IN ('$deviceType1', '58', '59', '67') AND is_deleted = 0"
                ." AND added_on BETWEEN :from_date AND :to_date"
                ." ORDER BY added_on DESC";
            }
            elseif($deviceType1 == '60'){		
                $dQuery = "SELECT device_type, device_value, added_on as updated_on"
                        ." FROM velocity"
                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type IN ('$deviceType1', '61', '62', '68') AND is_deleted = 0"
                ." AND added_on BETWEEN :from_date AND :to_date"
                ." ORDER BY added_on DESC";
            }
            elseif($deviceType1 == '63'){		
                $dQuery = "SELECT device_type, device_value, added_on as updated_on"
                        ." FROM displacement"
                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type IN ('$deviceType1', '64', '65', '69') AND is_deleted = 0"
                ." AND added_on BETWEEN :from_date AND :to_date"
                ." ORDER BY added_on DESC";
            }
                    
            $db->query($dQuery);
            $db->bind(':gateway_id', $gatewayId);
            $db->bind(':device_id', $deviceId);

            $db->bind(':from_date', $startTime);
            $db->bind(':to_date', $endTime);

            $res = $db->resultSet();

            $aList[JSON_TAG_RESULT] = $res;
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
            print_r($e);
        }
        return $aList;
    }



// Function to get the coin firmware type
function getCoinFirmwareType($db, $gatewayId, $deviceId){


            $sQuery = " SELECT firmware_type"
                    . " FROM gateway_devices"                    
                    . " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =0 AND is_blacklisted = 'N'";       

            $db->query($sQuery);  
            $db->bind(':gateway_id',$gatewayId);
            $db->bind(':device_id',$deviceId);

            $row = $db->resultSet();

            return $row;
}

//

}