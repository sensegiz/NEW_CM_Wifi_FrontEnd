<?php
ini_set('max_execution_time', 0);
require("phpMQTT.php");
require("/var/www/html/safralert/sendgrid-php/sendgrid-php.php");
//Connect to MQTT server
$mqtt = new phpMQTT("40.121.214.120", 1883, "phpMQTT Sub Example"); 

if(!$mqtt->connect()){
	exit(1);
}
else{
    echo "Connected to 1883";
}

//require("ConnectionManager.php");

$topics['safralert'] = array("qos"=>0, "function"=>"save_safr_data");

$mqtt->subscribe($topics,0);

while($mqtt->proc()){
		
}

$mqtt->close();

$config = parse_ini_file('/var/www/html/dbconfig.ini');

$connection = mysqli_connect('localhost','root','%$#@!timebasedaws','safr_alert');

if($connection == false){
	echo "Database not connected properly";
}

function db_connect(){
	//static $connection;
	
	if(!isset($connection)){
		$config = parse_ini_file('/var/www/html/dbconfig.ini');

       // $connection = mysqli_connect('localhost',$config['username'],$config['password'],$config['dbname']);
$connection = mysqli_connect('localhost','root','%$#@!timebasedaws','safr_alert');
	}
	
	if($connection === false){
		return mysqli_connect_error();
	}

	return $connection;
}

function db_query($query) {
	
	$connection = db_connect();
	
	$result = mysqli_query($connection, $query);
	
	return $result;
}
function save_safr_data($topic,$data){
	
//$result = db_query("INSERT INTO 'safr_data' ('gateway_id','safr_id','alert_type','value','added_on') VALUES ('CC78AB878A00','83','01','01','2.45pm')");

//if($result === false) {
	//echo "error da nayae !!";
//}

//else{
//echo "row inserted";
//}

$mysqli = '';
//	$mysqli = $GLOBALS['mysqli'];
//        $mysqli = new mysqli("localhost","root","sensegiz12#$","sensegiz");

    if(!empty($data)){
    	 echo "GW_DEV Recieved: ".date('d-m-Y h:i:s')."\nData:$data\n";
        //old format=> gateway_id,device/sensor_id,device_type,sensor value 
//	$dataArr = explode(',',$data);

       $db = db_connect();
    
    //check string/packet length is 18 chars=> 
    //new format=> 12 digits gateway_id, 2 digits device/sensor_id, 2 digits device_type, 2 digits value     
    if(strlen($data)==18){
        
        
         $dataArr = extractData($data);

	if(sizeof($dataArr)==4){

		$gateway_id   = $dataArr[0];
		$coin_id    = $dataArr[1];
		$safr_id  = $dataArr[2];
		$alert_type = $dataArr[3];
	/*	
		$sQuery = " INSERT INTO safr_data (gateway_id,safr_id,alert_type,value,added_on) VALUES ('$gateway_id','$safr_id','$alert_type','$value','2:45pm')";
                   // $db->query($sQuery41);
                    //$db->bind(':gateway_id',$gateway_id);
                    //$db->bind(':safr_id',$safr_id);
                    //$db->bind(':alert_type',$alert_type);
                    //$db->bind(':value',$value);
                   // $db->execute();
					 $result = mysqli_query($db,$sQuery);
					 echo $gateway_id ;
if($result === false) {
    echo "error";// Handle failure - log the error, notify administrator, etc.
} else {
    // We successfully inserted a row into the database
}
*/
 //echo $gateway_id ;
// echo $coin_id;
 // echo $safr_id ; echo $alert_type ;

$sql = "INSERT INTO safr_data (gateway_id,coin_id,safr_id,alert_type,added_on) 
VALUES ('$gateway_id','$coin_id','$safr_id','$alert_type',now())";
if (mysqli_query($db, $sql)) {
    echo " /n New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($db);
}


if($alert_type == 01)
	$type = "Emergency Panic Button pressed";
else
	$type = "Crash/Fall detected";



$getinfo = "SELECT location FROM coin_location WHERE coin_id IN($coin_id) AND safr_id IN($safr_id) ";

$getinfo1 = "SELECT user_name FROM safr_contacts WHERE safr_id IN($safr_id) ";

$getinfo2 = "SELECT emr1_name, erm2_name, emr3_name, emr1_mail, emr2_mail, emr3_mail, emr1_number, emr2_number, emr3_number FROM safr_contacts WHERE safr_id IN($safr_id) ";

//$getinfo1 = "SELECT user_name FROM safr_contacts WHERE safr_id IN($safr_id) ";

$query = mysqli_query($db, $getinfo);

$query1 = mysqli_query($db, $getinfo1);

$query2 = mysqli_query($db, $getinfo2);

$row = mysqli_fetch_assoc($query);

$row1 = mysqli_fetch_assoc($query1);

$row2 = mysqli_fetch_assoc($query2);

$location = $row['location'];

$user_name = $row1['user_name'];

$emr1_name = $row2['emr1_name'];

$emr2_name = $row2['emr2_name'];

$emr3_name = $row2['emr3_name'];

$emr1_mail = $row2['emr1_mail'];

$emr2_mail = $row2['emr2_mail'];

$emr3_mail = $row2['emr3_mail'];

$emr1_number = $row2['emr1_number'];

$emr2_number = $row2['emr2_number'];

$emr3_number = $row2['emr3_number'];

echo " \n\n $user_name \n $type \n $location \n  $emr1_name &nbsp &nbsp $emr1_mail &nbsp &nbsp $emr1_number";

mysqli_close($db);


//require 'vendor/autoload.php';

// If you are not using Composer
// require("path/to/sendgrid-php/sendgrid-php.php");

//$from = new SendGrid\Email(null, "info@sensegiz.com");
//$subject = "EMERGENCY ALERT!!!";
//$to = new SendGrid\Email(null, "[gourangdnaik@gmail.com, manunandhanmn@gmail.com]");
//$content = new SendGrid\Content("text/plain", "Username: $user_name &nbsp &nbsp has been detected with following SAFR alert: \n $type \n\n and the location of detection is $location");


//$list = array('gourangdnaik@gmail.com', 'manunandhanmn@gmail.com', 'manunandhan21@gmail.com');
// or
//$list = 'one@example.com, two@example.com, three@example.com';

//$this->$to($list);

//$mail = new SendGrid\Mail($from, $subject, $to, $content);

//$apiKey = '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';
//$sg = new \SendGrid($apiKey);

//$response = $sg->client->mail()->send()->post($mail);
//echo $response->statusCode();
//echo $response->headers();
//echo $response->body();



}
	}
	}
}
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

?>