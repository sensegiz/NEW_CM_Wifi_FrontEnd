<?php

// battery monitor.
// checks for coins with low batery and sends email alerts to user.

ini_set('max_execution_time', 0);

$fulDirpath  =  realpath( __DIR__);
$projPath    =  substr($fulDirpath, 0, strrpos( $fulDirpath, '/'));


require_once ($projPath.'/src/config/config.php');
require_once ($projPath.'/src/config/connect.php');
require_once ($projPath.'/src/utils/ConnectionManager.php');
require_once ($projPath.'/src/utils/GeneralMethod.php');


function coin_deletion(){

	$db = new ConnectionManager();

    	$sQuery3 = "SELECT gd_id, gateway_id, device_id, updated_on"
		." FROM gateway_devices"
		." WHERE is_deleted=2";	

    	$db->query($sQuery3);
    	$res = $db->resultSet();

    	if(is_array($res) && !empty($res)){
        	foreach ($res as $key => $value) {
        		$gd_id = $value[gd_id];
        		$deviceId = $value[device_id];
        		$gatewayId = $value[gateway_id];
        		$updated_on = $value[updated_on];

        		$date = date('Y-m-d H:i:s');
        
			
        		$minutediff = round((strtotime($date) - strtotime($updated_on))/60, 1);
			

        		if($minutediff>2880){  

				$sQueryDeviceData = " DELETE FROM devices "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 2";
                    		$db->query($sQueryDeviceData);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();                    
				
				$sQueryDeviceStreamData = " DELETE FROM devices_stream "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 2";
                    		$db->query($sQueryDeviceStreamData);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();

				$sQueryDeviceThresholdData = " DELETE FROM threshold "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 2";
                    		$db->query($sQueryDeviceThresholdData);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();

				$sQueryDeviceSettings = " DELETE FROM device_settings "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 2";
                    		$db->query($sQueryDeviceSettings);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();

				$sQueryAcceleration = " DELETE FROM acceleration "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 2";
                    		$db->query($sQueryAcceleration);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();

				$sQueryDeviceVelocity = " DELETE FROM velocity "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 2";
                    		$db->query($sQueryDeviceVelocity);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();

				$sQueryDeviceDisplacement = " DELETE FROM displacement "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 2";
                    		$db->query($sQueryDeviceDisplacement);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();
				
				$sQueryGwDevice = " DELETE FROM gateway_devices "                       
                       			. " WHERE gd_id=:gd_id";
               		 	$db->query($sQueryGwDevice);                                
                    		$db->bind(':gd_id',$gd_id);                          
                		$db->execute();

				
			}                                                                                                           
            	}           
        }
		
				
	
}



function gateway_deletion(){

	$db = new ConnectionManager();

    	$sQuery = "SELECT ug_id, gateway_id, updated_on"
		." FROM user_gateways"
		." WHERE is_deleted=2";	

    	$db->query($sQuery);
    	$res = $db->resultSet();

    	if(is_array($res) && !empty($res)){
        	foreach ($res as $key => $value) {
        		$ug_id = $value[ug_id];
        		$gatewayId = $value[gateway_id];
        		$updated_on = $value[updated_on];
        		$date = date('Y-m-d H:i:s');
        			
        		$minutediff = round((strtotime($date) - strtotime($updated_on))/60, 1);

        		if($minutediff>2880){				
				 
				$sQueryDevice = " DELETE FROM gateway_devices "                       
                       			. " WHERE gateway_id='$gatewayId' AND is_deleted = 2";
               		 	$db->query($sQueryDevice);                         
                		if($db->execute()){
                                    
                    			$sQueryDeviceData = " DELETE FROM  devices "
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted = 2";
                    			$db->query($sQueryDeviceData);                         
                    			$db->execute();

					$sQueryDeviceStreamData = " DELETE FROM  devices_stream "                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted = 2";
                    			$db->query($sQueryDeviceStreamData);                         
                    			$db->execute();
					
					$sQueryDeviceThresholdData = " DELETE FROM  threshold "                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted = 2";
                    			$db->query($sQueryDeviceThresholdData);                         
                    			$db->execute();
					
					$sQueryGatewaySettings = " DELETE FROM  gateway_settings "                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted = 2";
                    			$db->query($sQueryGatewaySettings);                         
                    			$db->execute();
					
					$sQueryDeviceSettings = " DELETE FROM device_settings "                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted = 2";
                    			$db->query($sQueryDeviceSettings);                         
                    			$db->execute();			

					$sQueryAcceleration = " DELETE FROM  acceleration "                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted = 2";
                    			$db->query($sQueryAcceleration);                         
                    			$db->execute();
					
					$sQueryVelocity = " DELETE FROM  velocity "                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted = 2";
                    			$db->query($sQueryVelocity);                         
                    			$db->execute();
					
					$sQueryDisplacement = " DELETE FROM displacement "                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted = 2";
                    			$db->query($sQueryDisplacement);                         
                    			$db->execute();
			 
		
					$sQUeryLocation = " SELECT location_id FROM location_gateway "                        
                        			. " WHERE gateway_id='$ug_id'";

                    			$db->query($sQUeryLocation );                         
                    			$loc = $db->single();

					$location_id = $loc['location_id'];	

					$sQUeryLocationGw = " DELETE FROM  location_gateway "                        
                        			. " WHERE gateway_id='$ug_id'";
                    			$db->query($sQUeryLocationGw);                         
                    			$db->execute();
					
					
					$sQUeryLocation = " SELECT location_id FROM location_gateway "                        
                        			. " WHERE location_id = '$location_id'";

                    			$db->query($sQUeryLocation );                         
                    			$location = $db->single();
					
					if(empty($location)){		
						$sQueryUserLocation = " DELETE FROM user_locations "                        
                        				. " WHERE id='$location_id' AND is_deleted = 2";
                    				$db->query($sQueryUserLocation );                         
                    				$db->execute();
					}	

					$sQueryUserGw = " DELETE FROM user_gateways "                        
                        			. " WHERE ug_id='$ug_id'";
                    			$db->query($sQueryUserGw);                         
                    			$db->execute();
	
			
				}												             				
			}                                                                                                           
            	}           
        }
		
				
	
}


gateway_deletion();
coin_deletion();


?>