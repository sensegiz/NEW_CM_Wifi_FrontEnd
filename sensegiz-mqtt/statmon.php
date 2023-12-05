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


function coin_status(){

	$db = new ConnectionManager();

    	$sQuery3 = 	"SELECT gd.gateway_id, gd.device_id, MAX(d.updated_on) AS updated_on"
		." FROM gateway_devices gd, devices d"
		." WHERE gd.gateway_id = d.gateway_id AND gd.device_id = d.device_id"
		." AND gd.active = 'Y' AND gd.is_deleted = 0 AND d.is_deleted = 0"
		." group by gd.gateway_id, gd.device_id";

    	$db->query($sQuery3);
    	$res = $db->resultSet();

    	if(is_array($res) && !empty($res)){
        	foreach ($res as $key => $value) {
        		$device_id = $value[device_id];
        		$gateway_id = $value[gateway_id];
        		$updated_on = $value[updated_on];
        		$active = $value[active];
        		$date = date('Y-m-d H:i:s');
        
			
        		$minutediff = round((strtotime($date) - strtotime($updated_on))/60, 1);

        		if($minutediff>1440) {               
				    markAsInactive($db, $gateway_id, $device_id);
                }                                                                                                           
            }           
        }
		
				
	
}

function gateway_status(){

	$db = new ConnectionManager();

    	$sQuery = 	"SELECT gateway_id, updated_on"
		." FROM user_gateways"
		." WHERE is_deleted=0";	

    	$db->query($sQuery);
    	$res = $db->resultSet();

    	if(is_array($res) && !empty($res)){
        	foreach ($res as $key => $value) {
        		$gateway_id = $value[gateway_id];
        		$updated_on = $value[updated_on];
        		$date = date('Y-m-d H:i:s');
        
			
        		$minutediff = round((strtotime($date) - strtotime($updated_on))/60, 1);

        		if($minutediff>2){ 
				$bQuery = "UPDATE user_gateways"
                                        ." SET status='Offline', active='N'"
                                        ." WHERE gateway_id=:gateway_id AND is_deleted = 0";
                                    $db->query($bQuery);
                                    $db->bind(':gateway_id', $gateway_id);

                                    $db->execute();             
				
			}                                                                                                           
            	}           
        }
		
				
	
}

coin_status();
gateway_status();

function markAsInactive($db, $gateway_id, $device_id){
	$bQuery = "UPDATE gateway_devices"
                                        ." SET active='N', status='N'"
                                        ." WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 0";
                                    $db->query($bQuery);
                                    $db->bind(':gateway_id', $gateway_id);
                                    $db->bind(':device_id', $device_id);
                                    if($db->execute()){
                
                                        $isGwAuthenticated = checkAuthenticatedGateway($db,$gateway_id);
                                        $isAuthenticatedDevice   =   checkAuthenticatedDevice($db,$gateway_id,$device_id);
                                        $userId   = $isGwAuthenticated['user_id'];
                                        $nickName = $isAuthenticatedDevice['nick_name'];
                        
                                        $userMailArr   =       getUserEmailIds($db,$userId);                    
                    
                                        $subject     =  'Alert! Coin is not responding!';
                                                            $messageEmail = '<html>
                                                                                        <head>
                                                                                            <title>Alert</title>
                                                                                        </head>
                                                                                        <body>
                                                                                            <h2>!! Alert !!</h2>
                                                                                            <h3>Gateway - '.$gateway_id.'</h3>
                                                                                            <h4>Coin nick named - '.$nickName.' is not responding. Please check!</h4>
                                                                                        </body>
                                                                                 </html>';
                                        if(!empty($userMailArr)){
                                            if(sendMails($userMailArr,$subject,$messageEmail)){
                                                print_r($gateway_id.'-'.$device_id.'-unresponsive mail sent to-'.$userMailArr);
                                            }
                                        }else{
                                                print_r('user-mail-list-empty');
                                            }
                                    }
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

    /*
      Function            : sendMails($emails,$subject,$message)
      Brief               : Function used to display the result in json format.
      Details             : Function used to display the result in json format.
      Input param         : $emails,$subject,$message
      Input/output param  : Nil
      Return              : bool
     */
    function sendMails($emailsArray,$subject,$message){
       
//                        require 'library/sendgrid-php/vendor/autoload.php';

                        /*$sendgrid = new SendGrid(SENDGRID_APIKEY);
                        $email    = new SendGrid\Email();

                        $email->setSmtpapiTos($emailsArray)
                              ->setFrom("info@sensegiz.com")
                              ->setFromName('SenseGiz')
                              ->setSubject($subject)
                              ->setHtml($message);

                        $sendgrid->send($email); */               

        $message = urlencode($message);
        $subject = urlencode($subject);
        $ch=curl_init();

        curl_setopt($ch,CURLOPT_URL,"http://".SERVER_IP.":3000/ses/?message=".$message."&to=".$emailsArray."&subject=".$subject."");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $output =curl_exec($ch);
        curl_close($ch);
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

?>