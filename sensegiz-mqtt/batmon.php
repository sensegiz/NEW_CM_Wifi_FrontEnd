<?php

// battery monitor.
// checks for coins with low batery and sends email alerts to user.

ini_set('max_execution_time', 0);

$fulDirpath  =  realpath( __DIR__);//  /var/www/devsensegiz/public_html/sensegiz-dev/sensegiz-mqtt
$projPath    =  substr($fulDirpath, 0, strrpos( $fulDirpath, '/'));//  /var/www/devsensegiz/public_html/sensegiz-dev

require_once ($projPath.'/src/config/config.php');
require_once ($projPath.'/src/config/connect.php');
require_once ($projPath.'/src/utils/ConnectionManager.php');
require_once ($projPath.'/src/utils/GeneralMethod.php');
require_once ($projPath.'/src/utils/CurlRequest.php');


$db = new ConnectionManager();

$aQuery = "SELECT *"
	." FROM devices"
	." WHERE device_type = 13 AND device_value = 03 AND mail_sent = 'N'";

$db->query($aQuery);
$row = $db->resultSet();

if(!empty($row)){

	foreach($row as $key => $value){

		$device_id = $value[device_id];
		$gateway_id = $value[gateway_id];
		$updated_on = $value[updated_on];
	
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
			sendMails($userMailArr,$subject,$messageEmail);
				$fQuery = "UPDATE devices"
					." SET mail_sent = 'Y'"
					." WHERE gateway_id=:gateway_id AND device_id=:device_id AND device_type=13";
				$db->query($fQuery);
				$db->bind(':gateway_id', $gateway_id);
				$db->bind(':device_id', $device_id);
				if($db->execute()){
					print_r('mail-sent-flag-updated');
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
//       print_r($row);
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

                        $sendgrid = new SendGrid(SENDGRID_APIKEY);
                        $email    = new SendGrid\Email();

                        //To send to multiple emails can use ->setTos(array of emails)
                         //        ->addTo("test@sendgrid.com")
                        $email->setSmtpapiTos($emailsArray)
                              ->setFrom("info@sensegiz.com")
                              ->setFromName('SenseGiz')
                              ->setSubject($subject)
                              ->setHtml($message);

                        $sendgrid->send($email);                
                //end of sendgrid
                print_r('-email sent-'); 
//              print_r('---email sent--msg--'.$message.'--');  
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