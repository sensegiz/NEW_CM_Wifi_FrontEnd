<?php
require_once '../../src/config/config.php';


$gateway_id = $_GET['gateway_id'];
$gateway_name = $_GET['gateway_name'];
$device_id = $_GET['device_id'];
$device_type1 = $_GET['device_type1'];
$device_type2 = $_GET['device_type2'];
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$zoneOffset = $_GET['zone_offset'];
$radioval = $_GET['radioval'];

$temp_unit = $_GET['temp_unit'];

$date_format = $_GET['date_format'];

pg_connect('host=localhost port=5432 dbname=postgres user=postgres password=' . DB_PASS) or die("Couldn't Connect " . pg_last_error()); // Connect to the Database


$zonesql = "SELECT name FROM pg_timezone_names WHERE utc_offset = '$zoneOffset' limit 1";
$zone = pg_query($zonesql) or die("Sql error : " . pg_error());

$row = pg_fetch_row($zone);


if ($date_format == 'MM-DD-YYYY HH:mm:ss') {
	$updateOn = "to_char((updated_on at time zone '$row[0]'), 'mm-dd-yyyy hh24:mi:ss')";
	$updateOn1 = "to_char((a.updated_on at time zone '$row[0]'), 'mm-dd-yyyy hh24:mi:ss')";
} else if ($date_format == 'YYYY-DD-MM HH:mm:ss') {
	$updateOn = "to_char((updated_on at time zone '$row[0]'), 'yyyy-dd-mm hh24:mi:ss')";
	$updateOn1 = "to_char((a.updated_on at time zone '$row[0]'), 'yyyy-dd-mm hh24:mi:ss')";

} else if ($date_format == 'YYYY-MM-DD HH:mm:ss') {
	$updateOn = "to_char((updated_on at time zone '$row[0]'), 'yyyy-mm-dd hh24:mi:ss')";
	$updateOn1 = "to_char((a.updated_on at time zone '$row[0]'), 'yyyy-mm-dd hh24:mi:ss')";

} else {
	$updateOn = "to_char((updated_on at time zone '$row[0]'), 'dd-mm-yyyy hh24:mi:ss')";
	$updateOn1 = "to_char((a.updated_on at time zone '$row[0]'), 'dd-mm-yyyy hh24:mi:ss')";
}

if ($device_type1 == '01' || $device_type1 == '02') {
	$dval = "device_value";
	$lthres = "(CASE WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint =1 THEN '0.001' WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint =2 THEN '0.1' WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint >=3 THEN (('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint)/8::float END)";
	$hthres = "(CASE WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint =1 THEN '0.001' WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint =2 THEN '0.1' WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint >=3 THEN (('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint)/8::float END)";
} else if ($device_type1 == '03' || $device_type1 == '04') {
	$dval = "device_value";
	$lthres = "(('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint)*10";
	$hthres = "(('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint)*10";
} else if (($device_type1 == '05' || $device_type1 == '06') && $temp_unit == 'Celsius') {

	$dval = "device_value";
	$lthres = "CASE WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint > 126 THEN -((('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint)-126) WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint = 126 THEN 0 ELSE ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint END";
	$hthres = "CASE WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint > 126 THEN -((('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint)-126) WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint = 126 THEN 0 ELSE ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint END";
} else if (($device_type1 == '05' || $device_type1 == '06') && $temp_unit == 'Fahrenheit') {

	$dval = "(CAST(device_value AS DOUBLE PRECISION)*1.8)+32";
	$lthres = "CASE WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint > 126 THEN ((-((('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint)-126))*1.8)+32 WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint = 126 THEN 32 ELSE ((('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint)*1.8)+32 END";
	$hthres = "CASE WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint > 126 THEN ((-((('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint)-126))*1.8)+32 WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint = 126 THEN 32 ELSE ((('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint)*1.8)+32 END";
} else if ($device_type1 == '09' && $temp_unit == 'Celsius') {
	$dval = "device_value";

} else if ($device_type1 == '09' && $temp_unit == 'Fahrenheit') {
	$dval = "(CAST(device_value AS DOUBLE PRECISION)*1.8)+32";

} else {
	$dval = "device_value";
	$lthres = "('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint";
	$hthres = "('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint";
}

//create MySQL connection   
if ($fromDate == '') {
	if ($device_type1 == '09' || $device_type1 == '10') {
		$sql = "SELECT $updateOn AS UPDATED_DATE, $dval AS DEVICE_VALUE FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type='$device_type1' ORDER BY updated_on DESC LIMIT 10";
	} elseif ($device_type1 == '26') {
		$sql = "SELECT $updateOn1 AS UPDATED_DATE, temp AS TEMPERATURE, humid AS HUMIDITY, dpt AS DEWPOINTTEMPERATURE, ah AS ABSOLUTEHUMIDITY FROM (SELECT device_type, device_value as temp, updated_on FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id='$device_id'  AND device_type='09' AND is_deleted = 0 ORDER BY updated_on DESC LIMIT 10) a INNER JOIN (SELECT device_type, device_value AS dpt, added_on AS updated_on FROM dewpointtemperature WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type='26' AND is_deleted = 0 ORDER BY added_on DESC LIMIT 10) b ON a.updated_on = b.updated_on INNER JOIN  (SELECT device_type, device_value AS ah, humidity as humid, added_on AS updated_on FROM absolutehumidity WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type='27' AND is_deleted = 0 ORDER BY added_on DESC LIMIT 10) c ON b.updated_on = c.updated_on ";

	} else {
		if ($radioval == "low")
			$sql = "SELECT $updateOn AS UPDATED_DATE, $dval AS DEVICE_VALUE, $lthres AS LOW_THRESHOLD FROM threshold WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type IN ('$device_type1', '$device_type2') ORDER BY updated_on DESC LIMIT 10";
		if ($radioval == "high")
			$sql = "SELECT $updateOn AS UPDATED_DATE, $dval AS DEVICE_VALUE, $hthres AS HIGH_THRESHOLD FROM threshold WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type IN ('$device_type1', '$device_type2') ORDER BY updated_on DESC LIMIT 10";
		if ($radioval == "both")
			$sql = "SELECT $updateOn AS UPDATED_DATE, $dval AS DEVICE_VALUE, $lthres AS LOW_THRESHOLD, $hthres AS HIGH_THRESHOLD FROM threshold WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type IN ('$device_type1', '$device_type2') ORDER BY updated_on DESC LIMIT 10";
	}

} else {
	if ($device_type1 == '09' || $device_type1 == '10') {
		$sql = "SELECT $updateOn AS UPDATED_DATE, $dval AS DEVICE_VALUE FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type='$device_type1' AND updated_on BETWEEN '$fromDate' AND '$toDate' ORDER BY updated_on DESC";

	} elseif ($device_type1 == '26') {
		$sql = "SELECT $updateOn1 AS UPDATED_DATE, temp AS TEMPERATURE, humid AS HUMIDITY, dpt AS DEWPOINTTEMPERATURE, ah AS ABSOLUTEHUMIDITY FROM (SELECT device_type, device_value as temp, updated_on FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id='$device_id'  AND device_type='09' AND is_deleted = 0 AND updated_on BETWEEN '$fromDate' AND '$toDate' ORDER BY updated_on DESC) a INNER JOIN (SELECT device_type, device_value AS dpt, added_on AS updated_on FROM dewpointtemperature WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type='26' AND is_deleted = 0 AND added_on BETWEEN '$fromDate' AND '$toDate' ORDER BY added_on DESC) c ON a.updated_on = c.updated_on INNER JOIN  (SELECT device_type, device_value AS ah, humidity as humid, added_on AS updated_on FROM absolutehumidity WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type='27' AND is_deleted = 0 AND added_on BETWEEN '$fromDate' AND '$toDate' ORDER BY added_on DESC) d ON c.updated_on = d.updated_on ";

	} else {
		if ($radioval == "low")
			$sql = "SELECT $updateOn AS UPDATED_DATE, $dval AS DEVICE_VALUE, $lthres AS LOW_THRESHOLD FROM threshold WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type IN ('$device_type1', '$device_type2') AND updated_on BETWEEN '$fromDate' AND '$toDate' ORDER BY updated_on DESC";
		if ($radioval == "high")
			$sql = "SELECT $updateOn AS UPDATED_DATE, $dval AS DEVICE_VALUE, $hthres AS HIGH_THRESHOLD FROM threshold WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type IN ('$device_type1', '$device_type2') AND updated_on BETWEEN '$fromDate' AND '$toDate' ORDER BY updated_on DESC";
		if ($radioval == "both")
			$sql = "SELECT $updateOn AS UPDATED_DATE, $dval AS DEVICE_VALUE, $lthres AS LOW_THRESHOLD, $hthres AS HIGH_THRESHOLD FROM threshold WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type IN ('$device_type1', '$device_type2') AND updated_on BETWEEN '$fromDate' AND '$toDate' ORDER BY updated_on DESC";
	}
}


//execute query 
$result = pg_query($sql) or die("Sql error : " . pg_error());
$file_ending = "xls";

//header info for browser
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=export.xls");
header("Pragma: no-cache");
header("Expires: 0");



if ($device_type1 == '01' || $device_type2 == '02') {
	$dev_type = 'Accelerometer (g)';
}

if ($device_type1 == '03' || $device_type2 == '04') {
	$dev_type = 'Gyroscope (DPS)';
}

if (($device_type1 == '05' || $device_type2 == '06') && $temp_unit == 'Celsius') {
	$dev_type = "Temperature (" . urldecode('%B0') . "C)";
}

if (($device_type1 == '05' || $device_type2 == '06') && $temp_unit == 'Fahrenheit') {
	$dev_type = "Temperature (" . urldecode('%B0') . "F)";
}

if ($device_type1 == '07' || $device_type2 == '08') {
	$dev_type = 'Humidity (%RH)';
}

if ($device_type1 == '09' && $temp_unit == 'Celsius') {
	$dev_type = "Temperature Stream (" . urldecode('%B0') . "C)";
}

if ($device_type1 == '09' && $temp_unit == 'Fahrenheit') {
	$dev_type = "Temperature Stream (" . urldecode('%B0') . "F)";
}

if ($device_type1 == '10') {
	$dev_type = 'Humidity Stream (%RH)';
}


echo "Below are the metric details of the coin selected :" . "\n";
echo "Gateway ID/Nickname : $gateway_id - $gateway_name" . "\n";
$device_id = hexdec($device_id); 
echo "Device ID :  $device_id" . "\n";
echo "Device Nick Name : $nick_name" . "\n";
echo "Metric : $dev_type" . "\n";

print("\n");


/*******Start of Formatting for Excel*******/
//define separator (defines columns in excel & tabs in word)
$sep = "\t";
//start of printing column names as names of MySQL fields
for ($i = 0; $i < pg_num_fields($result); $i++) {
	echo pg_field_name($result, $i) . "\t";
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
while ($row = pg_fetch_row($result)) {
	$data_insert = "";
	for ($j = 0; $j < pg_num_fields($result); $j++) {
		if (!isset($row[$j]))
			$data_insert .= "NULL" . $sep;
		elseif ($row[$j] != "")
			if (array_key_exists("$row[$j]", $thresholdJsonData)) {
				$data_insert .= $thresholdJsonData["$row[$j]"] . $sep;
				;
			} else {
				$data_insert .= "$row[$j]" . $sep;
			} else
			$data_insert .= "" . $sep;
	}
	$data_insert = str_replace($sep . "$", "", $data_insert);
	$data_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $data_insert);
	$data_insert .= "\t";
	print(trim($data_insert));
	print "\n";
}



?>