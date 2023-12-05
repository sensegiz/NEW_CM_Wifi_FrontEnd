<?php 
$fulDirpath  =  realpath( __DIR__);
$projPath    =  substr($fulDirpath, 0, strrpos( $fulDirpath, '/'));


require_once ($projPath.'/src/config/config.php');
require_once ($projPath.'/src/config/connect.php');
require_once ($projPath.'/src/utils/ConnectionManager.php');
require_once ($projPath.'/src/utils/GeneralMethod.php');


$db = new ConnectionManager();

while(true){
    $aQuery = "SELECT DISTINCT gateway_id FROM device_settings";
    	$db->query($aQuery);
        $row=$db->resultset();
        foreach ($row as $key => $value) {
            $gateway_id = $value['gateway_id'];
            $aQuery = "SELECT DISTINCT device_id FROM device_settings WHERE gateway_id = '$gateway_id'";
            $db->query($aQuery);
            $row=$db->resultset();
            foreach ($row as $key => $value) {
                $device_id = $value['device_id'];
                $aQuery = "SELECT * FROM device_settings WHERE gateway_id = '$gateway_id' AND device_id = '$device_id' AND device_sensor='Predictive Maintenance'";
                $db->query($aQuery);
                $isPrevSensorAvailable=$db->single();
                if(!$isPrevSensorAvailable){
                    $device_sensor = 'Predictive Maintenance';
                    $sQuery = " INSERT INTO device_settings (gateway_id,device_id,device_sensor,sensor_active,is_deleted) VALUES (:gateway_id,:device_id,:device_sensor,'N',0)";
							$db->query($sQuery);
							$db->bind(':gateway_id',$gateway_id);	
							$db->bind(':device_id',$device_id); 
                            $db->bind(':device_sensor',$device_sensor);
                            $db->execute();
                }
            }
        }
        die;
}
?>