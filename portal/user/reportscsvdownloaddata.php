<?php  
     require_once '../../src/config/config.php';
// $filename = "export";     

$gateway_id = $_GET['gateway_id'];  
$gateway_name = $_GET['gateway_name'];
$device_id = $_GET['device_id'];
$device_type1 = $_GET['device_type'];
$device_type2 = $_GET['device_type2'];
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$zoneOffset = $_GET['zone_offset'];
$radioval = $_GET['radioval'];
$selectedTimeDuration =$_GET['selectedTimeDuration'];
$toDate = $_GET['toDate'];
$fromDate = $_GET['fromDate'];

$temp_unit = $_GET['temp_unit'];
$date_format = $_GET['date_format'];

pg_connect('host=localhost port=5432 dbname=postgres user=postgres password='.DB_PASS) or die("Couldn't Connect ".pg_last_error()); // Connect to the Database


$zonesql="SELECT name FROM pg_timezone_names WHERE utc_offset = '$zoneOffset' limit 1";
$zone = pg_query($zonesql) or die ("Sql error : " . pg_error());

$row = pg_fetch_row($zone);

if($selectedTimeDuration == '1'){
	$durationCondition = "NOW() - INTERVAL '24 Hours' AND NOW()";
}else if($selectedTimeDuration == '7'){
	$durationCondition = "NOW() - INTERVAL '7 Day' AND NOW()";
}else if($selectedTimeDuration == '30'){
	$durationCondition = "NOW() - INTERVAL '30 Day' AND NOW()";
}else if($selectedTimeDuration == '4'){
	if($toDate != '' && $fromDate != ''){
		$durationCondition = "'$fromDate' AND '$toDate'";
	}
}


if($date_format == 'MM-DD-YYYY HH:mm:ss'){
	$updateOn = "to_char((updated_on at time zone '$row[0]'), 'mm-dd-yyyy hh24:mi:ss')";
	$updateOn1 = "to_char((a.updated_on at time zone '$row[0]'), 'mm-dd-yyyy hh24:mi:ss')";
}else if($date_format == 'YYYY-DD-MM HH:mm:ss'){
	$updateOn = "to_char((updated_on at time zone '$row[0]'), 'yyyy-dd-mm hh24:mi:ss')";
	$updateOn1 = "to_char((a.updated_on at time zone '$row[0]'), 'yyyy-dd-mm hh24:mi:ss')";
}else if($date_format == 'YYYY-MM-DD HH:mm:ss'){
	$updateOn = "to_char((updated_on at time zone '$row[0]'), 'yyyy-mm-dd hh24:mi:ss')";
	$updateOn1 = "to_char((a.updated_on at time zone '$row[0]'), 'yyyy-mm-dd hh24:mi:ss')";
}else{
	$updateOn = "to_char((updated_on at time zone '$row[0]'), 'dd-mm-yyyy hh24:mi:ss')";
	$updateOn1 = "to_char((a.updated_on at time zone '$row[0]'), 'dd-mm-yyyy hh24:mi:ss')";
}

$count_device_type1 = 0;
$device_type1_array = explode(',', $device_type1);
$count_device_type1 = count($device_type1_array);

$count_device_id = 0;
$device_id_array = explode(',', $device_id);
$count_device_id = count($device_id_array);


for($k=0; $k < $count_device_type1; $k++)
{
    $device_type1 = $device_type1_array[$k];

    // If accelerator Low value then accept High value also
	if($device_type1 == '01'){ $device_type2 = "02"; }
	if($device_type1 == '03'){ $device_type2 = "04"; }
	if($device_type1 == '05'){ $device_type2 = "06"; }
	if($device_type1 == '07'){ $device_type2 = "08"; }
	if($device_type1 == '09'){ $device_type2 = "10"; }
	if($device_type1 == '12'){ $device_type2 = "12', '14', '15', '28"; }

    for($m=0; $m < $count_device_id; $m++)
    {
        $device_id = $device_id_array[$m];
    

        if($device_type1 == '01' || $device_type1 == '02')
        {	
			$dval = "device_value";
			$lthres = "(CASE WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint =1 THEN '0.001' WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint =2 THEN '0.1' WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint >=3 THEN (('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint)/8::float END)";
			$hthres = "(CASE WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint =1 THEN '0.001' WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint =2 THEN '0.1' WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint >=3 THEN (('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint)/8::float END)";
		}
		else if($device_type1 == '03' || $device_type1 == '04')
		{
			$dval = "device_value";
			$lthres = "(('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint)*10";
			$hthres = "(('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint)*10";
		}
		else if(($device_type1 == '05' || $device_type1 == '06') && $temp_unit == 'Celsius')
		{
			$dval = "device_value";
			$lthres = "CASE WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint > 126 THEN -((('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint)-126) WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint = 126 THEN 0 ELSE ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint END";
			$hthres = "CASE WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint > 126 THEN -((('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint)-126) WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint = 126 THEN 0 ELSE ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint END";
		}
		else if(($device_type1 == '05' || $device_type1 == '06') && $temp_unit == 'Fahrenheit')
		{
			$dval = "(CAST(device_value AS DOUBLE PRECISION)*1.8)+32";
			$lthres = "CASE WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint > 126 THEN ((-((('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint)-126))*1.8)+32 WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint = 126 THEN 32 ELSE ((('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint)*1.8)+32 END";
			$hthres = "CASE WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint > 126 THEN ((-((('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint)-126))*1.8)+32 WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint = 126 THEN 32 ELSE ((('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint)*1.8)+32 END";
		}
		else if($device_type1 == '09' && $temp_unit == 'Celsius')
		{
			$dval = "device_value";		
			
		}
		else if($device_type1 == '09' && $temp_unit == 'Fahrenheit')
		{
			$dval = "(CAST(device_value AS DOUBLE PRECISION)*1.8)+32";		
			
		}
		else if($device_type1 == '12')
		{
			$deviceValue = "CAST(device_value AS DOUBLE precision)";
		}
		else
		{
			$dval = "device_value";
			$lthres = "('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint";
			$hthres = "('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint";
		}



		if($device_type1 == '09' || $device_type1 == '10')
		{
			$sql = "SELECT $updateOn AS UPDATED_DATE, $dval AS DEVICE_VALUE FROM devices_stream WHERE (gateway_id='$gateway_id') AND (device_id='$device_id') AND (device_type='$device_type1') AND (added_on BETWEEN $durationCondition ) ORDER BY added_on DESC";
		}
		else if($device_type1 == '12')
		{
			$sql = "SELECT CASE WHEN device_type='12' then 'X-Axis' WHEN device_type='14' then 'Y-Axis' WHEN device_type='15' then 'Z-Axis' WHEN device_type='28' then 'Aggregate' END AS AXIS, $deviceValue AS DEVICE_VALUE, $updateOn AS UPDATED_DATE FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id = '$device_id' AND device_type IN ('$device_type2') AND (added_on BETWEEN $durationCondition) ORDER BY added_on DESC, AXIS";
			// print_r($sql); exit();
		}
		else
		{
			$sql = "SELECT $updateOn AS UPDATED_DATE, $dval AS DEVICE_VALUE, $lthres AS LOW_THRESHOLD, $hthres AS HIGH_THRESHOLD FROM threshold WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type IN ('$device_type1', '$device_type2') AND (updated_on BETWEEN $durationCondition) ORDER BY updated_on DESC";
		}

		$qDevice_name = "SELECT * FROM gateway_devices WHERE gateway_id = '$gateway_id' AND device_id = '$device_id' LIMIT 1";
		$qDevice_name1 = pg_query($qDevice_name);
		$qDevice_name2 = pg_fetch_array($qDevice_name1);
		$device_name = $qDevice_name2['nick_name'];

		$filename = $device_id. '-' . $device_type1 .".csv";

		//execute query 
		$result = pg_query($sql) or die ("Sql error : " . pg_error());

		//set headers to download file rather than displayed
	    header('Content-Type: application/csv');
	    header('Content-Disposition: attachment; filename="' . $filename . '";');

	    if($device_type1 == '01' || $device_type2 == '02') {
			$dev_type = 'Accelerometer (g)';
		}

		if($device_type1 == '03' || $device_type2 == '04') {
			$dev_type = 'Gyroscope (DPS)';
		}

		if(($device_type1 == '05' || $device_type2 == '06') && $temp_unit == 'Celsius') {
			$dev_type = "Temperature (" . urldecode('%B0') . "C)";
		}

		if(($device_type1 == '05' || $device_type2 == '06') && $temp_unit == 'Fahrenheit') {
			$dev_type = "Temperature (" . urldecode('%B0') . "F)";
		}

		if($device_type1 == '07' || $device_type2 == '08') {
			$dev_type = 'Humidity (%RH)';
		}	

		if($device_type1 == '09' && $temp_unit == 'Celsius') {
			$dev_type = "Temperature Stream (" . urldecode('%B0') . "C)";
		}

		if($device_type1 == '09' && $temp_unit == 'Fahrenheit') {
			$dev_type = "Temperature Stream (" . urldecode('%B0') . "F)";
		}

		if($device_type1 == '10') {
			$dev_type = 'Humidity Stream (%RH)';
		}

		if($device_type1 == '12') {
			$dev_type = 'Accelerometer Stream (g)';
		}

		//create a file pointer
    	$f = fopen('php://output', 'w');

		echo "Below are the metric details of the coin selected :" . "\n";
		echo "Gateway ID/Nickname : $gateway_id - $gateway_name" . "\n";
		echo "Device ID :  $device_name" . "\n";
		echo "Metric : $dev_type" . "\n";

		print("\n"); 

		if($device_type1 == '12'){
			$avg_x=0;
			$avg_cnt_x = 0;
			$avg_y=0;
			$avg_cnt_y = 0;
			$avg_z=0;
			$avg_cnt_z = 0;
		}

		/*******Start of Formatting for Excel*******/   
		//define separator (defines columns in excel & tabs in word)
		$sep = ",";
		//start of printing column names as names of MySQL fields
		for ($i = 0; $i < pg_num_fields($result); $i++) {
			$fields = pg_field_name($result,$i) .",";
			echo $fields;
			fputcsv($f, $fields, $sep);
		}
		print("\n");
		$thresholdJsonData = array(
			"2020" => "2",
			"2030" => "3",
			"2040" => "4",
			"2050" => "5",
			"2060" => "6",
			"2070" => "7",
			"2080" => "8",
			"2090" => "9",
		);
		//start while loop to get data
		while($row = pg_fetch_row($result))
		{
		        $data_insert = "";

		        if($device_type1 == '12')
		        {
		        	for($j=0; $j<pg_num_fields($result);$j++)
			        {	    
						$type = $row[$j];
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
		            	$data_insert .= '"' . $row[$j] . '",';
			        }

		        }else{
		        	for($j=0; $j<pg_num_fields($result);$j++)
			        {
			            if(!isset($row[$j])){
			                $data_insert .= "NULL".$sep;
			            }elseif ($row[$j] != ""){
							if(array_key_exists("$row[$j]", $thresholdJsonData)){
								$data_insert .= $thresholdJsonData["$row[$j]"].$sep;;
							}else{
								$data_insert .= "$row[$j]".$sep;
							}
			            }else{
			                $data_insert .= "".$sep;
			 	    	}
			        }
		        }

		        $data_insert = str_replace($sep."$", "", $data_insert);
		        $data_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $data_insert);
		        $data_insert .= ",";
		        // print(trim($data_insert));
		        // $data_insert1 = trim($data_insert);
		        echo "$data_insert";
		        fputcsv($f, $data_insert, $sep);
		        print("\n");

		} 

		/*if($device_type1 == '12'){
			$average_x = $avg_x/$avg_cnt_x;
			$average_y = $avg_y/$avg_cnt_y;
			$average_z = $avg_z/$avg_cnt_z;

			echo "AVERAGE:" . "\n";
			echo "X-Axis : $average_x" . "\n";
			echo "Y-Axis : $average_y" . "\n";
			echo "Z-Axis : $average_z" . "\n";
		}*/
		
		//move back to beginning of file
    	fseek($f, 0);

		print("\n");

	    
	    //output all remaining data on a file pointer
	    fpassthru($f);
	
	} // For $m Loop closing

	print("\n");
} // For $k Loop closing 

?>