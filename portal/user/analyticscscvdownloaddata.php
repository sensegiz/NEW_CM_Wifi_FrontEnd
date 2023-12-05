<?php  
require_once '../../src/config/config.php';
$gateway_id = $_GET['gateway_id'];  
$gateway_name = $_GET['gateway_name'];  
$device_id = $_GET['device_id'];
$device_type = $_GET['device_type'];
$device_type1 = $_GET['device_type1'];
$zoneOffset = $_GET['zone_offset'];
$startTime = $_GET['startTime'];
$endTime = $_GET['endTime'];

$date_format = $_GET['date_format'];
$rms_values = $_GET['rms_values'];

$unit = $_GET['unit'];

$nick_name = $_GET['nick_name'];

pg_connect('host=localhost port=5432 dbname=iot_stream user=postgres password='.DB_PASS) or die("Couldn't Connect ".pg_last_error()); // Connect to the Database


$zonesql="SELECT name FROM pg_timezone_names WHERE utc_offset = '$zoneOffset' limit 1";
$zone = pg_query($zonesql) or die ("Sql error : " . pg_error());

$row = pg_fetch_row($zone);

if($date_format == 'MM-DD-YYYY HH:mm:ss'){
	if($device_type == '12' || $device_type == '51' || $device_type == '11'){ $updateOn = "to_char((updated_on at time zone '$row[0]'), 'mm-dd-yyyy hh24:mi:ss')"; }
	else{ $updateOn = "to_char((added_on at time zone '$row[0]'), 'mm-dd-yyyy hh24:mi:ss')"; }
}
else if($date_format == 'YYYY-DD-MM HH:mm:ss')
{

	if($device_type == '12' || $device_type == '51' || $device_type == '11'){ $updateOn = "to_char((updated_on at time zone '$row[0]'), 'yyyy-dd-mm hh24:mi:ss')"; }
	else{ $updateOn = "to_char((added_on at time zone '$row[0]'), 'yyyy-dd-mm hh24:mi:ss')"; }

}else if($date_format == 'YYYY-MM-DD HH:mm:ss')
{

	if($device_type == '12' || $device_type == '51' || $device_type == '11'){ $updateOn = "to_char((updated_on at time zone '$row[0]'), 'yyyy-mm-dd hh24:mi:ss')"; }
	else{ $updateOn = "to_char((added_on at time zone '$row[0]'), 'yyyy-mm-dd hh24:mi:ss')"; }

}else{ 
	if($device_type == '12' || $device_type == '51' || $device_type == '11'){ $updateOn = "to_char((updated_on at time zone '$row[0]'), 'dd-mm-yyyy hh24:mi:ss')"; }
	else{ $updateOn = "to_char((added_on at time zone '$row[0]'), 'dd-mm-yyyy hh24:mi:ss')"; }
}

if($device_type == '17' || $device_type == '20' || $device_type == '23' || $device_type == '57' || $device_type == '60' || $device_type == '63'){
	$deviceValue = "CAST(device_value AS DOUBLE precision)";
	if($unit == 'mm'){
		$deviceValue = "CAST(device_value AS DOUBLE precision) * 1000";
	}
	if($unit == 'mim'){
		$deviceValue = "CAST(device_value AS DOUBLE precision) * 1000000";

	}								
}else{
	$deviceValue = "CAST(device_value AS DOUBLE precision)";
}

if($rms_values == 0 && ($device_type == '20' || $device_type == '23' || $device_type == '60' || $device_type == '63')){
	$deviceValue = "($deviceValue)/0.707";
}

if($device_type == '11'){
	                 	
		$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME, CASE WHEN device_value='0' then 'Off' WHEN device_value!='0' then 'Running' END AS STATUS, $updateOn AS UPDATED_DATE FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') AND updated_on BETWEEN '$startTime' AND '$endTime' ORDER BY updated_on";
	
}
if($device_type == '12'){
	if($startTime == 'undefined'){
		//$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='12' then 'X-Axis' WHEN device_type='14' then 'Y-Axis' WHEN device_type='15' then 'Z-Axis' WHEN device_type='28' then 'Aggregate' END AS AXIS, $updateOn AS UPDATED_DATE, $deviceValue AS DEVICE_VALUE FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') ORDER BY updated_on DESC, axis LIMIT 100";

		$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME, CASE WHEN device_type='12' then 'X-Axis' END AS AXIS, DEVICE_VALUE, UPDATED_DATE			
			FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME , device_type, $deviceValue AS DEVICE_VALUE , $updateOn AS UPDATED_DATE FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '12' AND is_deleted = 0 ORDER BY updated_on DESC LIMIT 100) AS a UNION ALL
			SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME, CASE WHEN device_type='14' then 'Y-Axis' END AS AXIS, DEVICE_VALUE, UPDATED_DATE
			FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME , device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '14' AND is_deleted = 0 ORDER BY updated_on DESC LIMIT 100) AS b UNION ALL
			SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME, CASE WHEN device_type='15' then 'Z-Axis' END AS AXIS, DEVICE_VALUE, UPDATED_DATE
			FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME , device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '15' AND is_deleted = 0 ORDER BY updated_on DESC LIMIT 100) AS c UNION ALL
			SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME, CASE WHEN device_type='28' then 'Aggregate' END AS AXIS, DEVICE_VALUE, UPDATED_DATE
			FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '28' AND is_deleted = 0 ORDER BY updated_on DESC LIMIT 100) AS d
			ORDER BY UPDATED_DATE DESC, AXIS";

	}else{                 	
		$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='12' then 'X-Axis' WHEN device_type='14' then 'Y-Axis' WHEN device_type='15' then 'Z-Axis' WHEN device_type='28' then 'Aggregate' END AS AXIS, $updateOn AS UPDATED_DATE, $deviceValue AS DEVICE_VALUE FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') AND updated_on BETWEEN '$startTime' AND '$endTime' ORDER BY updated_on DESC, AXIS";
	}
}
if($device_type == '17'){
	if($startTime == 'undefined'){
		//$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='17' then 'Axial' WHEN device_type='18' then 'Horizontal' WHEN device_type='19' then 'Vertical' WHEN device_type='29' then 'Aggregate' END AS AXIS, $updateOn AS UPDATED_DATE, $deviceValue AS DEVICE_VALUE FROM acceleration WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') ORDER BY added_on DESC LIMIT 100";

		$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='17' then 'Axial' END AS AXIS, DEVICE_VALUE, UPDATED_DATE		
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM acceleration WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '17' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS a UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='18' then 'Horizontal' END AS AXIS, DEVICE_VALUE, UPDATED_DATE 
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM acceleration WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '18' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS b UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='19' then 'Vertical' END AS AXIS, DEVICE_VALUE, UPDATED_DATE 
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM acceleration WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '19' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS c UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='29' then 'Aggregate' END AS AXIS, DEVICE_VALUE, UPDATED_DATE
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM acceleration WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '29' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS d
		ORDER BY UPDATED_DATE DESC, AXIS";

	}else{                 	
		$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='17' then 'Axial' WHEN device_type='18' then 'Horizontal' WHEN device_type='19' then 'Vertical' WHEN device_type='29' then 'Aggregate' END AS AXIS, $updateOn AS UPDATED_DATE, $deviceValue AS DEVICE_VALUE FROM acceleration WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') AND added_on BETWEEN '$startTime' AND '$endTime' ORDER BY added_on DESC, AXIS";
	}
}
if($device_type == '20'){
	if($startTime == 'undefined'){
		//$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='20' then 'Axial' WHEN device_type='21' then 'Horizontal' WHEN device_type='22' then 'Vertical' WHEN device_type='30' then 'Aggregate' END AS AXIS, $updateOn AS UPDATED_DATE, $deviceValue AS DEVICE_VALUE FROM velocity WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') ORDER BY added_on DESC LIMIT 100";

		$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='20' then 'Axial' END AS AXIS, DEVICE_VALUE, UPDATED_DATE		
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM velocity WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '20' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS a UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='21' then 'Horizontal' END AS AXIS, DEVICE_VALUE, UPDATED_DATE 
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM velocity WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '21' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS b UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='22' then 'Vertical' END AS AXIS, DEVICE_VALUE, UPDATED_DATE 
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM velocity WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '22' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS c UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='30' then 'Aggregate' END AS AXIS, DEVICE_VALUE, UPDATED_DATE
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM velocity WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '30' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS d
		ORDER BY UPDATED_DATE DESC, AXIS";

	}else{                 	
		$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='20' then 'Axial' WHEN device_type='21' then 'Horizontal' WHEN device_type='22' then 'Vertical' WHEN device_type='30' then 'Aggregate' END AS AXIS, $updateOn AS UPDATED_DATE, $deviceValue AS DEVICE_VALUE FROM velocity WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') AND added_on BETWEEN '$startTime' AND '$endTime' ORDER BY added_on DESC, AXIS";
	}
}
if($device_type == '23'){
	if($startTime == 'undefined'){
		//$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='23' then 'Axial' WHEN device_type='24' then 'Horizontal' WHEN device_type='25' then 'Vertical' WHEN device_type='31' then 'Aggregate' END AS AXIS, $updateOn AS UPDATED_DATE, $deviceValue AS DEVICE_VALUE FROM displacement WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') ORDER BY added_on DESC LIMIT 100";

		$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='23' then 'Axial' END AS AXIS, DEVICE_VALUE, UPDATED_DATE		
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM displacement WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '23' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS a UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='24' then 'Horizontal' END AS AXIS, DEVICE_VALUE, UPDATED_DATE 
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM displacement WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '24' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS b UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='25' then 'Vertical' END AS AXIS, DEVICE_VALUE, UPDATED_DATE 
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM displacement WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '25' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS c UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='31' then 'Aggregate' END AS AXIS, DEVICE_VALUE, UPDATED_DATE
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM displacement WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '31' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS d
		ORDER BY UPDATED_DATE DESC, AXIS";

	}else{                 	
		$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='23' then 'Axial' WHEN device_type='24' then 'Horizontal' WHEN device_type='25' then 'Vertical' WHEN device_type='31' then 'Aggregate' END AS AXIS, $updateOn AS UPDATED_DATE, $deviceValue AS DEVICE_VALUE FROM displacement WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') AND added_on BETWEEN '$startTime' AND '$endTime' ORDER BY added_on DESC, AXIS";
	}
}

if($device_type == '51'){
	if($startTime == 'undefined'){
		//$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='12' then 'X-Axis' WHEN device_type='14' then 'Y-Axis' WHEN device_type='15' then 'Z-Axis' WHEN device_type='28' then 'Aggregate' END AS AXIS, $updateOn AS UPDATED_DATE, $deviceValue AS DEVICE_VALUE FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') ORDER BY updated_on DESC, axis LIMIT 100";

		$sql = "SELECT pdump_id_counter, gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME, CASE WHEN device_type='51' then 'X-Axis' END AS AXIS, DEVICE_VALUE, UPDATED_DATE		
			FROM (SELECT pdump_id_counter, gateway_id, '$nick_name' AS COIN_NAME , device_type, $deviceValue AS DEVICE_VALUE , $updateOn AS UPDATED_DATE FROM predictive_stream WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '51' AND is_deleted = 0 ORDER BY pdump_id_counter DESC LIMIT 100) AS a UNION ALL
			SELECT pdump_id_counter, gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME, CASE WHEN device_type='52' then 'Y-Axis' END AS AXIS, DEVICE_VALUE, UPDATED_DATE
			FROM (SELECT pdump_id_counter, gateway_id, '$nick_name' AS COIN_NAME , device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE FROM predictive_stream WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '52' AND is_deleted = 0 ORDER BY pdump_id_counter DESC LIMIT 100) AS b UNION ALL
			SELECT pdump_id_counter, gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME, CASE WHEN device_type='53' then 'Z-Axis' END AS AXIS, DEVICE_VALUE, UPDATED_DATE
			FROM (SELECT pdump_id_counter, gateway_id, '$nick_name' AS COIN_NAME , device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE FROM predictive_stream WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '53' AND is_deleted = 0 ORDER BY pdump_id_counter DESC LIMIT 100) AS c UNION ALL
			SELECT pdump_id_counter, gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME, CASE WHEN device_type='66' then 'Aggregate' END AS AXIS, DEVICE_VALUE, UPDATED_DATE
			FROM (SELECT pdump_id_counter, gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE FROM predictive_stream WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '66' AND is_deleted = 0 ORDER BY pdump_id_counter DESC LIMIT 100) AS d
			ORDER BY pdump_id_counter DESC, AXIS";

	}else{                 	
		$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='51' then 'X-Axis' WHEN device_type='52' then 'Y-Axis' WHEN device_type='53' then 'Z-Axis' WHEN device_type='66' then 'Aggregate' END AS AXIS, $updateOn AS UPDATED_DATE, $deviceValue AS DEVICE_VALUE FROM predictive_stream WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') AND updated_on BETWEEN '$startTime' AND '$endTime' ORDER BY pdump_id_counter DESC, AXIS";
	}
}
if($device_type == '57'){
	if($startTime == 'undefined'){
		//$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='17' then 'Axial' WHEN device_type='18' then 'Horizontal' WHEN device_type='19' then 'Vertical' WHEN device_type='29' then 'Aggregate' END AS AXIS, $updateOn AS UPDATED_DATE, $deviceValue AS DEVICE_VALUE FROM acceleration WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') ORDER BY added_on DESC LIMIT 100";

		$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='57' then 'Axial' END AS AXIS, DEVICE_VALUE, UPDATED_DATE		
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM acceleration WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '57' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS a UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='58' then 'Horizontal' END AS AXIS, DEVICE_VALUE, UPDATED_DATE 
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM acceleration WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '58' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS b UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='59' then 'Vertical' END AS AXIS, DEVICE_VALUE, UPDATED_DATE 
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM acceleration WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '59' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS c UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='67' then 'Aggregate' END AS AXIS, DEVICE_VALUE, UPDATED_DATE
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM acceleration WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '67' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS d
		ORDER BY UPDATED_DATE DESC, AXIS";

	}else{                 	
		$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='57' then 'Axial' WHEN device_type='58' then 'Horizontal' WHEN device_type='59' then 'Vertical' WHEN device_type='67' then 'Aggregate' END AS AXIS, $updateOn AS UPDATED_DATE, $deviceValue AS DEVICE_VALUE FROM acceleration WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') AND added_on BETWEEN '$startTime' AND '$endTime' ORDER BY added_on DESC, AXIS";
	}
}
if($device_type == '60'){
	if($startTime == 'undefined'){
		//$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='20' then 'Axial' WHEN device_type='21' then 'Horizontal' WHEN device_type='22' then 'Vertical' WHEN device_type='30' then 'Aggregate' END AS AXIS, $updateOn AS UPDATED_DATE, $deviceValue AS DEVICE_VALUE FROM velocity WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') ORDER BY added_on DESC LIMIT 100";

		$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='60' then 'Axial' END AS AXIS, DEVICE_VALUE, UPDATED_DATE		
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM velocity WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '60' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS a UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='21' then 'Horizontal' END AS AXIS, DEVICE_VALUE, UPDATED_DATE 
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM velocity WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '61' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS b UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='22' then 'Vertical' END AS AXIS, DEVICE_VALUE, UPDATED_DATE 
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM velocity WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '62' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS c UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='30' then 'Aggregate' END AS AXIS, DEVICE_VALUE, UPDATED_DATE
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM velocity WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '68' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS d
		ORDER BY UPDATED_DATE DESC, AXIS";

	}else{                 	
		$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='60' then 'Axial' WHEN device_type='61' then 'Horizontal' WHEN device_type='62' then 'Vertical' WHEN device_type='68' then 'Aggregate' END AS AXIS, $updateOn AS UPDATED_DATE, $deviceValue AS DEVICE_VALUE FROM velocity WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') AND added_on BETWEEN '$startTime' AND '$endTime' ORDER BY added_on DESC, AXIS";
	}
}
if($device_type == '63'){
	if($startTime == 'undefined'){
		//$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='23' then 'Axial' WHEN device_type='24' then 'Horizontal' WHEN device_type='25' then 'Vertical' WHEN device_type='31' then 'Aggregate' END AS AXIS, $updateOn AS UPDATED_DATE, $deviceValue AS DEVICE_VALUE FROM displacement WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') ORDER BY added_on DESC LIMIT 100";

		$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='63' then 'Axial' END AS AXIS, DEVICE_VALUE, UPDATED_DATE		
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM displacement WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '63' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS a UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='64' then 'Horizontal' END AS AXIS, DEVICE_VALUE, UPDATED_DATE 
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM displacement WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '64' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS b UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='65' then 'Vertical' END AS AXIS, DEVICE_VALUE, UPDATED_DATE 
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM displacement WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '65' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS c UNION ALL 
		SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='69' then 'Aggregate' END AS AXIS, DEVICE_VALUE, UPDATED_DATE
		FROM (SELECT gateway_id, '$nick_name' AS COIN_NAME, device_type, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE
                  FROM displacement WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type = '69' AND is_deleted = 0
		  ORDER BY added_on DESC LIMIT 100) AS d
		ORDER BY UPDATED_DATE DESC, AXIS";

	}else{                 	
		$sql = "SELECT gateway_id AS GATEWAY_ID, '$nick_name' AS COIN_NAME,  CASE WHEN device_type='63' then 'Axial' WHEN device_type='64' then 'Horizontal' WHEN device_type='65' then 'Vertical' WHEN device_type='69' then 'Aggregate' END AS AXIS, $updateOn AS UPDATED_DATE, $deviceValue AS DEVICE_VALUE FROM displacement WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type1') AND added_on BETWEEN '$startTime' AND '$endTime' ORDER BY added_on DESC, AXIS";
	}
}


// echo $sql; exit();

//execute query 
$result = pg_query($sql) or die ("Sql error : " . pg_error());   


if($device_type == '12') {
	$dev_type = 'Accelerometer Stream';
	$unit_d = 'g';
}
if($device_type == '17' || $device_type == '57') {
	$dev_type = 'Acceleration';
	$unit_d = "m/s"  . urldecode('%B2');
	if($unit == 'mm'){ $unit_d = "mm/s"  . urldecode('%B2'); }
	if($unit == 'mim'){ $unit_d = urldecode('%B5') . "/s"  . urldecode('%B2');}
	
}
if($device_type == '20' || $device_type == '60') {
	$dev_type = 'Velocity';
	$unit_d = "m/s";
	if($unit == 'mm'){ $unit_d = "mm/s"; }
	if($unit == 'mim'){ $unit_d = urldecode('%B5') . "/s"; }
}
if($device_type == '23' || $device_type == '63') {
	$dev_type = 'Displacement';
	$unit_d = "m";
	if($unit == 'mm'){ $unit_d = "mm"; }
	if($unit == 'mim'){ $unit_d = urldecode('%B5'); }
}
if($device_type == '51') {
	$dev_type = 'Spectrum Stream';
	$unit_d = 'g';
}
if($device_type == '11') {
	$dev_type = 'Run/OFF Time';
	$unit_d = 'na';

}

echo "Below are the metric details of the coin selected :" . "\n";
echo "Gateway ID/Nickname : $gateway_id ($gateway_name)" . "\n";
echo "Device ID :  $device_id" . "\n";
echo "Metric : $dev_type" . "\n";
echo "Unit : $unit_d" . "\n";
print("\n"); 

$avg_x=0;
$avg_cnt_x = 0;
$avg_y=0;
$avg_cnt_y = 0;
$avg_z=0;
$avg_cnt_z = 0;
$start_time = $end_time = $offtime = $totalstart = $totalend = $ontime = 0;

$csv_header = '';
//start of printing column names as names of MySQL fields
for ($i = 1; $i < pg_num_fields($result); $i++) {	
	$csv_header .= '"' . pg_field_name($result,$i) . '",';
}
$csv_header .= "\n";    

$data_insert = '';
//start while loop to get data
while($row = pg_fetch_row($result))
{
        
        for($j=1; $j<pg_num_fields($result);$j++)
        {	    
		$type = $row[$j];
	if($device_type != '11'){
		if($type == 'X-Axis' || $type == 'Axial'){
            		$avg_x = $avg_x + $row[3];
			$avg_cnt_x = $avg_cnt_x + 1;
		}
		if($type == 'Y-Axis' || $type == 'Horizontal'){
            		$avg_y = $avg_y + $row[3];
			$avg_cnt_y = $avg_cnt_y + 1;
		}
		if($type == 'Z-Axis' || $type == 'Vertical'){
            		$avg_z = $avg_z + $row[3];
			$avg_cnt_z = $avg_cnt_z + 1;
		}
	}else{

		if($totalstart == 0){
			$totalstart = $row[3];
		}

		if($type == 'Running' && $start_time != 0 && $end_time != 0){
			$end_time = $row[3];
			$offtime = $offtime + (strtotime($end_time) - strtotime($start_time));
		}

		if($type == 'Running'){
			$start_time = $end_time = 0;
		}

		if($type == 'Off'){
			if($start_time == 0){
				$start_time = $row[3];
				$end_time = 0;
			}else{
				$end_time = $row[3];							
										
				$offtime = $offtime + (strtotime($end_time) - strtotime($start_time));										
				$start_time = $end_time;
			}
		}
		
		$totalend = $row[3];
	}

            	$data_insert .= '"' . $row[$j] . '",';
			                	    
        }
	$data_insert .= "\n";
        
}   




if($device_type != '51' && $device_type != '11') {

$average_x = $avg_x/$avg_cnt_x;
$average_y = $avg_y/$avg_cnt_y;
$average_z = $avg_z/$avg_cnt_z;

echo "AVERAGE:" . "\n";
echo "X-Axis : $average_x" . "\n";
echo "Y-Axis : $average_y" . "\n";
echo "Z-Axis : $average_z" . "\n";

}else{

$totaltime = round((strtotime($totalend) - strtotime($totalstart)), 4);
$offtime = round($offtime, 4);
$ontime = $totaltime - $offtime;

$ontime = secondsToHms($ontime);
$offtime = secondsToHms($offtime);

if($ontime == '' ){ $ontime = 0; }
if($offtime == ''){ $offtime = 0; }

echo "Timings:" . "\n";
echo "OFF TIME : $offtime" . "\n";
echo "RUN TIME: $ontime" . "\n";

}


print("\n"); 

//header info for browser
header("Content-Type: application/csv");    
header("Content-Disposition: attachment; filename=export.csv");  
echo $csv_header . $data_insert;
exit;

function secondsToHms($d) {
    
    $h = floor($d / 3600);
    $m = floor($d % 3600 / 60);
    $s = floor($d % 3600 % 60);

	$hDisplay = ($h > 1 ) ? "$h hrs" : "$h hr";
	$mDisplay = ($m > 1 ) ? "$m mins" : "$m min";
	$sDisplay = ($s > 1 ) ? "$s secss" : "$s sec";

    return $hDisplay . ' ' . $mDisplay . ' ' . $sDisplay; 
    
}

?>