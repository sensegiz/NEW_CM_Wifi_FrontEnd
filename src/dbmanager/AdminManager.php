<?php 
/*
  Project                     : Sensegiz
  Module                      : Web Console --  Manager Class
  File name                   : AdminManager.php
  Description                 : Manager class for Admin related activities
 */
  
class AdminManager {
    
    /*
      Function            : login($inData)
      Brief               : Function to login.      
      Input param         : $inData
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function login($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        try {            
            //print_r($inData);exit();
            $email       = $generalMethod->sanitizeString($inData[JSON_TAG_EMAIL]);
            $password    = $inData[JSON_TAG_PASSWORD];
            $login_type  = $inData[JSON_TAG_LOGIN_TYPE];
            
             // Check Mandatory field    
            if(empty($email) || empty($password) || empty($login_type)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
            $row   =  [];
            if($login_type=='admin'){
                $row = $this->loginCheckAdmin($db, $email, $password);
            }
            elseif($login_type=='user') {
                $row = $this->loginCheckUser($db, $email, $password);
            }
//        print_r($row);
//        exit();
            if(empty($row)){$aList[JSON_TAG_STATUS] = 3; return $aList;  }                        
            
            if($login_type=='user') {
                $userId          =  $row['user_id'];
//                $hasUserApiKey   =  $this->hasUserApiKey($db, $userId);

                $apiKey   =   $generalMethod->generateApiKey();
//                if(!empty($hasUserApiKey)){
//                    $rowUp   = $this->updateApiKey($db,$userId,$apiKey);
//                }
//                else{
                    $rowId  = $this->addApiKey($db,$userId,$apiKey);
//                }

                $row['key_details']   =  $this->hasUserApiKey($db, $rowId);            
            }
//            unset($row['loginStatus']);
            $row[JSON_TAG_LOGIN_TYPE]  =  $login_type;

            $aList[JSON_TAG_RESULT]    =  $row;
            
            $aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 
        return $aList;
    }
   
    //Check Admin    
     public function loginCheckAdmin($db,$email,$password){
        $password = MD5($password);
        $sQuery = " SELECT admin_id,name,email_id "
                . " FROM admin "
                . " WHERE  email_id=:email_id AND password =:password ";
        
//        print_r($sQuery);exit();
        $db->query($sQuery);
        $db->bind(':email_id',$email);
        $db->bind(':password',$password);
        return $db->single();
    }

    
    public function updateCoinOfflineTime($inData){
        $db = new ConnectionManager();
        $db1 = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        $curl     = new CurlRequest();
        
        try {       
            
            echo " updateCoinOfflineTime ", json_encode($inData), "_______   ";

            $u_id = $inData['u_id'];
            $coinofflinetimeValue = $inData['coinofflinetime'];
           
            echo "u_id: " . $u_id . "<br>";
            echo "coinofflinetimeValue:-> " . $coinofflinetimeValue . "<br>";
        
            $query = " UPDATE users "
                . " SET coinofflinetime=:coinofflinetime"
                . " WHERE user_id=:user_id";

            $db->query($query);
            $db->bind(':user_id', $u_id);
            $db->bind(':coinofflinetime', $coinofflinetimeValue);
            $db->execute();
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {

            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }
    
    //Check User     
     public function loginCheckUser($db,$user_email,$user_password){
        $user_password = MD5($user_password);
        $sQuery = " SELECT user_id,admin_id,user_email, date_format, rms_values, logo, temp_unit"
                . " FROM users "
                . " WHERE  UPPER(user_email)=UPPER(:user_email) AND user_password =:user_password ";
        $db->query($sQuery);
        $db->bind(':user_email',$user_email);
        $db->bind(':user_password',$user_password);
        return $db->single();
    }     
     
    //Insert API Key
    function addApiKey($db,$userId,$apiKey){
                
        $query = "INSERT INTO api_keys(user_id, api_key, added_on, updated_on, expires_on) "
                . "VALUES (:user_id, :api_key, NOW(), NOW(), (NOW() + INTERVAL '15' DAY))  RETURNING id";
        $db->query($query);
        $db->bind(':user_id', $userId);
        $db->bind(':api_key', $apiKey);
        $res = $db->resultSet();
	$rowId = $res[0][id];;
        if($rowId){
            return $rowId;
        }
        else{
            return false;
        }
    }
    
    //Insert API Key
    function updateApiKey($db,$userId,$apiKey){
                
        $sQuery = " UPDATE api_keys "
                . " SET user_id=:user_id,api_key=:api_key,expires_on=(NOW() + INTERVAL '15' DAY),updated_on=now()"
                . " WHERE user_id=:user_id AND is_deleted =0";
        $db->query($sQuery);
        $db->bind(':user_id', $userId);
        $db->bind(':api_key', $apiKey);       
        if($db->execute()){
            return true;
        }
        else{
            return false;
        }
    }    
    
    //Check user and key exists
    function hasUserApiKey($db,$rowId){
        $sQuery = " SELECT api_key,expires_on"
                . " FROM api_keys "
                . " WHERE id =:id AND is_deleted =0 ";
        $db->query($sQuery);
        $db->bind(':id',$rowId);
        return  $row = $db->single();        
    }
        
    /*
      Function            : sendInvite($inData)
      Brief               : Function to Send invite to User.  
      Details             : Function to Send invite to User.  
      Input param         : $inData   
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function sendInvite($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        try { 
            $email       = $generalMethod->sanitizeString($inData[JSON_TAG_USER_EMAIL]);
            $adminId     = $inData[JSON_TAG_ADMIN_ID];
            
            $user_phone  = $inData[JSON_TAG_USER_PHONE];
            $uId     = $inData[JSON_TAG_U_ID];
            
            
//     print_r($inData);   exit();    
             // Check Mandatory field    
            if(empty($email) && empty($adminId)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}

            if($uId==0){

                $rowUser  = $this->checkUserEmail($db,$email);
                if(!empty($rowUser)){ $aList[JSON_TAG_STATUS] = 3; return $aList;}

                $verification_code  = md5($email.time());

                $sQuery = "INSERT INTO users (admin_id,user_email,user_phone,verification_code)"
                        . " VALUES (:admin_id,:user_email,:user_phone,:verification_code)";
                $db->query($sQuery);                        
                $db->bind(':admin_id',$adminId);
                $db->bind(':user_email',$email);
                $db->bind(':user_phone',$user_phone);
                $db->bind(':verification_code',$verification_code);
                if($db->execute()){
                    $res   =  true;
                    $emailsArray = array($email);
                    $this->sendInviteMail($emailsArray,$verification_code);
                }
            }
            else{
                $sQueryUp = " UPDATE users "
                       . " SET user_phone=:user_phone,updated_on=DATE_TRUNC('second', NOW())"
                       . " WHERE user_email=:user_email AND user_id=:user_id AND is_deleted =0";
                $db->query($sQueryUp);
                $db->bind(':user_email',$email);
                $db->bind(':user_id',$uId);
                $db->bind(':user_phone',$user_phone);            
                if($db->execute()){
                     $res   =  true;
                }
            }
            
            $aList[JSON_TAG_RESULT]    =  $res;
            
            $aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 
        return $aList;
    }
    
    //To check User Email already exist or not
    function checkUserEmail($db,$userEmail){
        
        $sQuery = " SELECT user_id,admin_id,user_email,user_password,verification_code,added_on,updated_on"
                    . " FROM users"                    
                    . " WHERE user_email=:user_email AND is_deleted =0 ORDER BY user_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':user_email',$userEmail);
        return  $row = $db->single();
    }    
      
    //To send verification mail
    public function sendInviteMail($emailsArray,$verification_code) {
        $generalMethod = new GeneralMethod(); 
         
//        $subject      =  "Important: GoodRipple- Verify your email";
          $subject      =  "Create a New Password for your SenseGiz account";
        
          $link         =   ADMIN_PATH."/create-password.php?ver=$verification_code";

//        $message      =  'Click here to verify your email address <a href="'.$link.'">Click Here</a>';
          $message      =  '<html><head><title>Password Invitation</title></head><body>
                                <div style="text-align: center">                                    
                                    <h1 style="font-size: 25px;">Create new password</h1>
                                    <p>Click the button below to create new password on your SenseGiz account.</p>
                                    <div style="margin-top: 25px;">
                                        <a href="'.$link.'" style="font-weight: 600;padding: 10px 20px;background-color: #26A65B;font-size: 20px;color: #fff;border-radius: 3px;width: 200px;text-decoration: none;">Create Password</a>
                                    </div>                                           
                                </div>
                        </body></html>';        

        $verifyMail  =  $generalMethod->sendMails($emailsArray,$subject,$message);        
    }
    
      /*
      Function            : getInvitedUsers()
      Brief               : Function to get all Invited Users     
      Input param         : Nil
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function getInvitedUsers(){
        $db = new ConnectionManager();
        try {      
            $sQuery = " SELECT user_id,admin_id,user_email,user_phone,added_on,updated_on,coinofflinetime  "
                    . " FROM users "                  
                    . " WHERE is_deleted =0 ORDER BY user_id DESC";                 
            $db->query($sQuery);
            $rowSet=$db->resultSet();
                        
            $aList[JSON_TAG_RESULT]= $rowSet;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }
    
    /*
      Function            : userCreateNewPassword($inData)
      Brief               : Function to create new USER password.
      Details             : Function to create new USER password. 
      Input param         : $inData   
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function userCreateNewPassword($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        try {            
//            print_r($inData);exit();
            $verification_code   = $inData[JSON_TAG_VERIFICATION_CODE];
            $password            = $inData[JSON_TAG_PASSWORD];
            
            
            
            
             // Check Mandatory field    
            if(empty($password) && empty($verification_code)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
            $rowUser  = $this->checkUserPassword($db,$verification_code);
            if(empty($rowUser)){ $aList[JSON_TAG_STATUS] = 3; return $aList;}
            
            if(isset($rowUser['user_password']) && !empty($rowUser['user_password'])){ $aList[JSON_TAG_STATUS] = 4; return $aList;}
            
            $password  = md5($password);

            $sQuery = " UPDATE users "
                   . " SET user_password=:user_password,updated_on=DATE_TRUNC('second', NOW())"
                   . " WHERE verification_code=:verification_code AND is_deleted =0";
            $db->query($sQuery);
            $db->bind(':user_password',$password);
            $db->bind(':verification_code',$verification_code);            
            $db->execute();          
$res['result'] = '';
            if($db->rowCount()>0){
                $res['result']  = TRUE;
            }
            else{
                $res['result']  = FALSE;
            }                      
            
             $aList[JSON_TAG_RESULT] = $res;
            
            $aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 
        return $aList;
    }
    
    //To check User already created the password or not
    function checkUserPassword($db,$verification_code){
        
        $sQuery = " SELECT user_id,user_password,verification_code,added_on,updated_on"
                . " FROM users"                    
                . " WHERE verification_code=:verification_code AND is_deleted =0 ORDER BY user_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':verification_code',$verification_code);
        return  $row = $db->single();
    }    
      
      /*
      Function            : getAdminGateways()
      Brief               : Function to get all gateways for admin  
      Input param         : Nil
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function getAdminGateways($coinmacID){
        $db = new ConnectionManager();
        try {      
            if($coinmacID == 'all'){
                $sQuery = " SELECT g.*,u.user_email "
                        . " FROM user_gateways As g "
                        . " JOIN users As u"                    
                        . " ON g.user_id=u.user_id WHERE g.is_deleted IN (0, 2) AND u.is_deleted =0 ORDER BY g.ug_id DESC";                 
                $db->query($sQuery);
                $rowSet  =  $db->resultSet();
                           
                $aList[JSON_TAG_RESULT]= $rowSet;
                $aList[JSON_TAG_STATUS] = 0;         
            }
            else{

                /*$seQuery = "SELECT * FROM gateway_devices "
                          . "WHERE device_mac_address='$coinmacID'";
                $db->query($seQuery);   
                $row  =  $db->resultSet();
                $gateway_id = $row[0]['gateway_id'];*/
                $sQuery = " SELECT g.*,u.user_email "
                    . " FROM user_gateways As g "
                    . " JOIN users As u"                    
                    . " ON g.user_id=u.user_id WHERE g.is_deleted IN (0, 2) AND u.is_deleted =0"
					. " AND g.gateway_id IN (select gateway_id from gateway_devices where device_mac_address = '$coinmacID')";  
                $db->query($sQuery);
                $rowSet  =  $db->resultSet();
                        
                $aList[JSON_TAG_RESULT]= $rowSet;
                $aList[JSON_TAG_STATUS] = 0; 
            }     
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }
        
    /*
      Function            : blacklistGateways($inData)
      Brief               : Function to Blacklist Gateways
      Details             : Function to Blacklist Gateways 
      Input param         : $inData   
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function blacklistGateways($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        try {            
            $gatewayId          = $inData[JSON_TAG_GATEWAY_ID];
            $blacklistStatus    = $inData[JSON_TAG_BLACKLIST_STATUS];
            
            // Check Mandatory field    
            if(empty($gatewayId) && empty($blacklistStatus)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
            $rowGateway  = $this->checkGateway($db,$gatewayId);
            if(empty($rowGateway)){ $aList[JSON_TAG_STATUS] = 3; return $aList;}
//print_r($rowGateway);

            $res = '';
            $sQuery = "UPDATE user_gateways "
                   . "SET is_blacklisted=:is_blacklisted,updated_on=DATE_TRUNC('second', NOW()) "
                   . "WHERE gateway_id=:gateway_id AND is_deleted =0";
            $db->query($sQuery);
            $db->bind(':is_blacklisted',$blacklistStatus);
            $db->bind(':gateway_id',$gatewayId);                                
            
            if($db->execute()){
                $res  = true;
            }
            else{
                $res  = false;
            }                      
//        print_r($sQuery);
//        exit();
             $aList[JSON_TAG_RESULT] = $res;
            
            $aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 
        return $aList;
    }

    //To check Gateway existed or not
    function checkGateway($db,$gatewayId){
        
        $sQuery = " SELECT *"
                . " FROM user_gateways"                    
                . " WHERE gateway_id=:gateway_id AND is_deleted =0 ORDER BY ug_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':gateway_id',$gatewayId);
        return  $row = $db->single();
    }
    
      /*
      Function            : getAdminDevices($gatewayId)
      Brief               : Function to get devices for admin     
      Input param         : Nil
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function getAdminDevices($gatewayId){
        $db = new ConnectionManager();
        try {      
            $sQuery = " SELECT * "
                    . " FROM gateway_devices "                                   
                    . " WHERE gateway_id =:gateway_id AND is_deleted IN (0, 2) ORDER BY gd_id DESC";                 
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
      Function            : blacklistDevices($inData)
      Brief               : Function to Blacklist Devices 
      Details             : Function to Blacklist Devices 
      Input param         : $inData   
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function blacklistDevices($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        try {            
            $deviceId           =   $inData[JSON_TAG_DEVICE_ID];
            $blacklistStatus    =   $inData[JSON_TAG_BLACKLIST_STATUS];
            $gatewayId          =   $inData[JSON_TAG_GATEWAY_ID];
            
            // Check Mandatory field    
            if(empty($deviceId) && empty($blacklistStatus) && empty($gatewayId)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
            $rowDevice  = $this->checkGatewayDevice($db,$gatewayId,$deviceId);
            if(empty($rowDevice)){ $aList[JSON_TAG_STATUS] = 3; return $aList;}
            
            $res = '';
            $sQuery = " UPDATE gateway_devices "
                   . " SET is_blacklisted=:is_blacklisted,updated_on=DATE_TRUNC('second', NOW())"
                   . " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =0";
            $db->query($sQuery);
            $db->bind(':is_blacklisted',$blacklistStatus);
            $db->bind(':device_id',$deviceId);                                
            $db->bind(':gateway_id',$gatewayId);            
            if($db->execute()){
                $res  = true;
            }
            else{
                $res  = false;
            }                      
            
             $aList[JSON_TAG_RESULT] = $res;
            
            $aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 
        return $aList;
    }

    //To check Device existed or not
    function checkGatewayDevice($db,$gatewayId,$deviceId){
        
        $sQuery = " SELECT *"
                . " FROM gateway_devices"                    
                . " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =0 ORDER BY gd_id DESC";       
         
        $db->query($sQuery);
        $db->bind(':device_id',$deviceId);
        $db->bind(':gateway_id',$gatewayId);
        return  $row = $db->single();
    }    
    
    /*
      Function            : removeGateways($inData)
      Brief               : Function to Remove Gateways 
      Details             : Function to Remove Gateways 
      Input param         : $inData   
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function removeGateways($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        try {            
            $gatewayId          = $inData[JSON_TAG_GATEWAY_ID];
            
            // Check Mandatory field    
            if(empty($gatewayId)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
            $rowGateway  = $this->checkGateway($db,$gatewayId);
            if(empty($rowGateway)){ $aList[JSON_TAG_STATUS] = 3; return $aList;}

            $res = '';
            $sQuery = "UPDATE user_gateways "
                   . "SET is_deleted=1,updated_on=DATE_TRUNC('second', NOW()) "
                   . "WHERE gateway_id='$gatewayId' AND is_deleted =0";
            $db->query($sQuery);
            
//            $db->bind(':gateway_id',$gatewayId);                                
            
            if($db->execute()){
                $res  = true;
                $sQueryDevice = " UPDATE gateway_devices "
                       . " SET is_deleted=1,updated_on=DATE_TRUNC('second', NOW())"
                       . " WHERE gateway_id='$gatewayId'";
                $db->query($sQueryDevice);                         
                if($db->execute()){
                                    
                    $sQueryDeviceData = " UPDATE devices "
                        . " SET is_deleted=1,updated_on=DATE_TRUNC('second', NOW())"
                        . " WHERE gateway_id='$gatewayId'";
                    $db->query($sQueryDeviceData);                         
                    $db->execute();

			$sQueryDeviceStreamData = " UPDATE devices_stream "
                        . " SET is_deleted=1,updated_on=DATE_TRUNC('second', NOW())"
                        . " WHERE gateway_id='$gatewayId'";
                    $db->query($sQueryDeviceStreamData);                         
                    $db->execute();
					
					$sQueryDeviceThresholdData = " UPDATE threshold "
                        . " SET is_deleted=1,updated_on=DATE_TRUNC('second', NOW())"
                        . " WHERE gateway_id='$gatewayId'";
                    $db->query($sQueryDeviceThresholdData);                         
                    $db->execute();
					
					$sQueryGatewaySettings = " UPDATE gateway_settings "
                        . " SET is_deleted=1,updated_on=DATE_TRUNC('second', NOW())"
                        . " WHERE gateway_id='$gatewayId'";
                    $db->query($sQueryGatewaySettings);                         
                    $db->execute();
					
					$sQueryDeviceSettings = " UPDATE device_settings "
                        . " SET is_deleted=1,updated_on=DATE_TRUNC('second', NOW())"
                        . " WHERE gateway_id='$gatewayId'";
                    $db->query($sQueryDeviceSettings);                         
                    $db->execute();	

			
			$ug_id = $rowGateway['ug_id'];
		
			$sQUeryLocation = " SELECT location_id FROM location_gateway "                        
                        . " WHERE gateway_id='$ug_id'";

                    $db->query($sQUeryLocation );                         
                    $loc = $db->single();

		$location_id = $loc['location_id'];	

		$sQueryGatewaySettings = " DELETE FROM  location_gateway "                        
                        . " WHERE gateway_id='$ug_id'";
                    $db->query($sQueryGatewaySettings);                         
                    $db->execute();
					
					
		$sQUeryLocation = " SELECT location_id FROM location_gateway "                        
                        . " WHERE location_id = '$location_id'";

                    $db->query($sQUeryLocation );                         
                    $location = $db->single();
					
		if(empty($location)){		
		$sQueryUserLocation = " DELETE FROM user_locations "                        
                        . " WHERE id='$location_id'";
                    $db->query($sQueryUserLocation );                         
                    $db->execute();
		}									
					
		}

            }
            else{
                $res  = false;
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
      Function            : removeDevices($inData)
      Brief               : Function to Remove Devices
      Details             : Function to Remove Devices
      Input param         : $inData   
      Input/output param  : $aList
      Return              : Returns array.
     */
    public function removeDevices($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        try {            
            $deviceId           =   $inData[JSON_TAG_DEVICE_ID];
            $gatewayId          =   $inData[JSON_TAG_GATEWAY_ID];
            
            // Check Mandatory field    
            if(empty($deviceId) && empty($gatewayId)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
            $rowDevice  = $this->checkGatewayDevice($db,$gatewayId,$deviceId);
            if(empty($rowDevice)){ $aList[JSON_TAG_STATUS] = 3; return $aList;}
            
            $res = '';
            $sQuery = " UPDATE gateway_devices "
                   . " SET is_deleted=1,updated_on=DATE_TRUNC('second', NOW())"
                   . " WHERE gateway_id=:gateway_id AND device_id=:device_id";
            $db->query($sQuery);
            $db->bind(':device_id',$deviceId);                                
            $db->bind(':gateway_id',$gatewayId);            
            if($db->execute()){
                $res  = true;
                
                    $sQueryDeviceData = " UPDATE devices "
                        . " SET is_deleted=1,updated_on=DATE_TRUNC('second', NOW())"
                        . " WHERE gateway_id=:gateway_id AND device_id=:device_id";
                    $db->query($sQueryDeviceData);         
                    $db->bind(':device_id',$deviceId);                                
                    $db->bind(':gateway_id',$gatewayId);                                
                    $db->execute();                    
					
					$sQueryDeviceStreamData = " UPDATE devices_stream "
                        . " SET is_deleted=1,updated_on=DATE_TRUNC('second', NOW())"
                        . " WHERE gateway_id=:gateway_id AND device_id=:device_id";
                    $db->query($sQueryDeviceStreamData);         
                    $db->bind(':device_id',$deviceId);                                
                    $db->bind(':gateway_id',$gatewayId);                                
                    $db->execute();
					
					$sQueryDeviceThresholdData = " UPDATE threshold "
                        . " SET is_deleted=1,updated_on=DATE_TRUNC('second', NOW())"
                        . " WHERE gateway_id=:gateway_id AND device_id=:device_id";
                    $db->query($sQueryDeviceThresholdData);         
                    $db->bind(':device_id',$deviceId);                                
                    $db->bind(':gateway_id',$gatewayId);                                
                    $db->execute();
					
					$sQueryDeviceSettings = " UPDATE device_settings "
                        . " SET is_deleted=1,updated_on=DATE_TRUNC('second', NOW())"
                        . " WHERE gateway_id=:gateway_id AND device_id=:device_id";
                    $db->query($sQueryDeviceSettings);         
                    $db->bind(':device_id',$deviceId);                                
                    $db->bind(':gateway_id',$gatewayId);                                
                    $db->execute();
            }
            else{
                $res  = false;
            }                      
            
             $aList[JSON_TAG_RESULT] = $res;
            
            $aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 
        return $aList;
    }


public function hardDeleteGateways($inData){

        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        try {            
            	$gatewayId = $inData[JSON_TAG_GATEWAY_ID];
		$otp = $inData[JSON_TAG_OTP];

            
            	// Check Mandatory field    
            	if(empty($gatewayId)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
            	$rowGateway  = $this->checkGateway($db,$gatewayId);
            	if(empty($rowGateway)){ $aList[JSON_TAG_STATUS] = 3; return $aList;}


		$sQUery11 = " SELECT otp FROM admin "                        
                        . " WHERE admin_id = 1";

                $db->query($sQUery11);                         
                $chkotp = $db->single();

		$dbotp = $chkotp['otp'];

		$res = '';
		

		if($otp == $dbotp){

			
            		$sQuery = "UPDATE user_gateways SET is_deleted=2, updated_on = DATE_TRUNC('second', NOW())"                   
                   		. "WHERE gateway_id='$gatewayId' AND is_deleted =0";
            		$db->query($sQuery);
                                          
            
            		if($db->execute()){	
        	       		$res  = true;

				$sdQuery = " UPDATE gateway_devices SET is_deleted = 2, updated_on = DATE_TRUNC('second', NOW())"
                   		. " WHERE gateway_id='$gatewayId' AND is_deleted =0";
            			$db->query($sdQuery);           
            			$db->execute();
				
                		                         
                		if($db->execute()){
                                    
                    			$sQueryDeviceData = " UPDATE devices SET is_deleted = 2"
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted =0";
                    			$db->query($sQueryDeviceData);                         
                    			$db->execute();

					$sQueryDeviceStreamData = " UPDATE devices_stream SET is_deleted = 2"                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted =0";
                    			$db->query($sQueryDeviceStreamData);                         
                    			$db->execute();
					
					$sQueryDeviceThresholdData = " UPDATE threshold SET is_deleted = 2"                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted =0";
                    			$db->query($sQueryDeviceThresholdData);                         
                    			$db->execute();
					
					$sQueryGatewaySettings = " UPDATE gateway_settings SET is_deleted = 2"                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted =0";
                    			$db->query($sQueryGatewaySettings);                         
                    			$db->execute();
					
					$sQueryDeviceSettings = " UPDATE device_settings SET is_deleted = 2"                        
                        			. " WHERE gateway_id='$gatewayId'  AND is_deleted =0";
                    			$db->query($sQueryDeviceSettings);                         
                    			$db->execute();			

					$sQueryAcceleration = " UPDATE acceleration SET is_deleted = 2"                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted =0";
                    			$db->query($sQueryAcceleration);                         
                    			$db->execute();
					
					$sQueryVelocity = " UPDATE velocity SET is_deleted = 2"                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted =0";
                    			$db->query($sQueryVelocity);                         
                    			$db->execute();
					
					$sQueryDisplacement = " UPDATE displacement SET is_deleted = 2"                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted =0";
                    			$db->query($sQueryDisplacement);                         
                    			$db->execute();

			  
					$ug_id = $rowGateway['ug_id'];
		
					$sQUeryLocation = " SELECT location_id FROM location_gateway "                        
                        			. " WHERE gateway_id='$ug_id'";

                    			$db->query($sQUeryLocation );                         
                    			$loc = $db->single();

					$location_id = $loc['location_id'];										
															
					if(!empty($loc)){		
						$sQueryUserLocation = " UPDATE user_locations SET is_deleted = 2"                        
                        				. " WHERE id='$location_id' AND is_deleted =0";
                    				$db->query($sQueryUserLocation );                         
                    				$db->execute();
					}	

																	
				}
            		}
		}
            	else{
			$aList[JSON_TAG_STATUS] = 4; return $aList;
            	}             

             	$aList[JSON_TAG_RESULT] = $res;
            
            	$aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 
        return $aList;
}


public function hardDeleteDevices($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        try {            

            	$deviceId           =   $inData[JSON_TAG_DEVICE_ID];
            	$gatewayId          =   $inData[JSON_TAG_GATEWAY_ID];
		$otp = $inData[JSON_TAG_OTP];

            
            	// Check Mandatory field    
            	if(empty($deviceId) && empty($gatewayId)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
            	$rowDevice  = $this->checkGatewayDevice($db,$gatewayId,$deviceId);
            	if(empty($rowDevice)){ $aList[JSON_TAG_STATUS] = 3; return $aList;}

		$sQUery11 = " SELECT otp FROM admin "                        
                        . " WHERE admin_id = 1";

                $db->query($sQUery11);                         
                $chkotp = $db->single();

		$dbotp = $chkotp['otp'];

		$res = '';
		

		if($otp == $dbotp){
		
            
            		$sQuery = " UPDATE gateway_devices SET is_deleted = 2, updated_on = DATE_TRUNC('second', NOW())"
                   		. " WHERE gateway_id=:gateway_id AND device_id=:device_id  AND is_deleted =0";
            		$db->query($sQuery);
            		$db->bind(':device_id',$deviceId);                                
            		$db->bind(':gateway_id',$gatewayId);            
            		if($db->execute()){
                		$res  = true;
                
                    		$sQueryDeviceData = " UPDATE devices SET is_deleted = 2 "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =0";
                    		$db->query($sQueryDeviceData);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();                    
					
				$sQueryDeviceStreamData = " UPDATE devices_stream SET is_deleted = 2 "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =0";
                    		$db->query($sQueryDeviceStreamData);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();
					
				$sQueryDeviceThresholdData = " UPDATE threshold SET is_deleted = 2 "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =0";
                    		$db->query($sQueryDeviceThresholdData);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();
					
				$sQueryDeviceSettings = " UPDATE device_settings SET is_deleted = 2 "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =0";
                    		$db->query($sQueryDeviceSettings);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();

				$sQueryAcceleration = " UPDATE acceleration SET is_deleted = 2 "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =0";
                    		$db->query($sQueryAcceleration);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();
					
				$sQueryDeviceVelocity = " UPDATE velocity SET is_deleted = 2 "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =0";
                    		$db->query($sQueryDeviceVelocity);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();
					
				$sQueryDeviceDisplacement = " UPDATE displacement SET is_deleted = 2 "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =0";
                    		$db->query($sQueryDeviceDisplacement);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();

				
			}
            	}
            	else{
                	$aList[JSON_TAG_STATUS] = 4; return $aList;

            	}                      
            
             $aList[JSON_TAG_RESULT] = $res;
            
            $aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 
        return $aList;
}




public function addUser($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        try { 
            $email       = $generalMethod->sanitizeString($inData[JSON_TAG_USER_EMAIL]);
            $adminId     = $inData[JSON_TAG_ADMIN_ID];
            
            $user_phone  = $inData[JSON_TAG_USER_PHONE];
            $uId     = $inData[JSON_TAG_U_ID];
            $user_pass  = $inData[JSON_TAG_USER_PASSWORD];
          
            
   
             // Check Mandatory field    
            if(empty($email) && empty($adminId)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}

            if($uId==0){

                $rowUser  = $this->checkUserEmail($db,$email);
                if(!empty($rowUser)){ $aList[JSON_TAG_STATUS] = 3; return $aList;}

                $user_password  = md5($user_pass);

                $sQuery = "INSERT INTO users (admin_id,user_email,user_phone,user_password)"
                        . " VALUES (:admin_id,:user_email,:user_phone,:user_password) RETURNING user_id";
                $db->query($sQuery);                        
                $db->bind(':admin_id',$adminId);
                $db->bind(':user_email',$email);
                $db->bind(':user_phone',$user_phone);
                $db->bind(':user_password',$user_password);
                $res1 = $db->resultSet();
		$userId = $res1[0][user_id];


                if($userId){          

			$query = "INSERT INTO general_settings (user_id, accelerometer, gyroscope, temperature, humidity, stream, accelerometerstream, predictivestream) "
				. "VALUES (:user_id, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y')";
			$db->query($query);                	
			$db->bind(':user_id',$userId);
                	$db->execute();	
     
			require("/var/www/html/sensegiz-dev/library/sendgrid-php/sendgrid-php.php");


			$from = new SendGrid\Email(null, "info@sensegiz.com");

			$to = new SendGrid\Email(null, $email);

			$subject     =  'SenseGiz Portal - Registration Confirmation';

	
			$content = new SendGrid\Content("text/html", "<html><head><title>Registration Confirmation</title></head><body><div style=\"text-align: center\"><h3>You have been added as a user on the  SenseGiz Portal!</h3> <h4>To login to the portal, use the beow details:</h4><h4>URL: https://cm-testing.sensegiz.com/sensegiz-dev/portal/ </h4> <h4> UserName: $email </h4> <h4>Password: $user_pass </h4> <h3> To change the password, please <a href='https://cm-testing.sensegiz.com/sensegiz-dev/portal/forgot_password.php'> click here </a></h3></div></body></html>");

			$mail = new SendGrid\Mail($from, $subject, $to, $content);

			$apiKey = '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';
			$sg = new \SendGrid($apiKey);

			$response = $sg->client->mail()->send()->post($mail);
	 		$response->statusCode();
	 		$response->headers();
	 		$response->body();
     				
			$res   =  true;
                }
            }
            
            
            $aList[JSON_TAG_RESULT]    =  $res;
            
            $aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
		print_r($e);
            $aList[JSON_TAG_STATUS] = 1;
        } 
        return $aList;
    }	


public function sendOTP($inData){

        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();
   
        try {            
		$user_email1 = 'tavaneshb5@gmail.com';
		$user_email2 = 'naveen@sensegiz.com';
    		$user_email3 = 'ninad@sensegiz.com'; 

            	$gatewayId          = $inData[JSON_TAG_GATEWAY_ID];
		$deviceId = $inData[JSON_TAG_DEVICE_ID];
            
            	// Check Mandatory field    
            	if(empty($gatewayId)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
            	$rowGateway  = $this->checkGateway($db,$gatewayId);
            	if(empty($rowGateway)){ $aList[JSON_TAG_STATUS] = 3; return $aList;}

		$otp = mt_rand(10000, 99999);

		$subject = 'OTP for hard deleting Gateway/Devices : cm-testing.sensegiz.com';
								
		$this->sendMails($user_email1, $subject, $otp, $gatewayId, $deviceId);
		$this->sendMails($user_email2, $subject, $otp, $gatewayId, $deviceId);   
    $this->sendMails($user_email3, $subject, $otp, $gatewayId, $deviceId);            

		$sQuery1 = " UPDATE admin "
                                . " SET otp=:otp"
                                . " WHERE admin_id = 1";
                        $db->query($sQuery1);                    
			$db->bind(':otp',$otp);
			$db->execute();
            

            $aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }
 
        return $aList;
}
  

public function sendMails($emailsArray, $subject, $otp, $gateway_id, $deviceId){

	/*require("/var/www/html/sensegiz-dev/library/sendgrid-php/sendgrid-php.php");


	$from = new SendGrid\Email(null, "info@sensegiz.com");

	$to = new SendGrid\Email(null, $emailsArray);


	if($deviceId != ''){
		$content = new SendGrid\Content("text/html", "<html><head><title>!Hard Delete!</title></head><body><h3>Gateway - $gateway_id</h3><h3>Device - $deviceId</h3>
                                <div style=\"text-align: center\"><h4>The OTP for the hard delete operation is $otp.</h4> </div>                                   
                                            </body></html>");

	}else{
		$content = new SendGrid\Content("text/html", "<html><head><title>!Hard Delete!</title></head><body><h3>Gateway - $gateway_id</h3>
                                <div style=\"text-align: center\"><h4>The OTP for the hard delete operation is $otp.</h4> </div>                                   
                                            </body></html>");
	}

	$mail = new SendGrid\Mail($from, $subject, $to, $content);

	$apiKey = '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';
	$sg = new \SendGrid($apiKey);

	$response = $sg->client->mail()->send()->post($mail);
	$response->statusCode();
	$response->headers();
	$response->body();*/

    if($deviceId != ''){
        $message = "<html><head><title>!Hard Delete!</title></head><body><h3>Gateway - $gateway_id</h3><h3>Device - $deviceId</h3>
                                <div style=\"text-align: center\"><h4>The OTP for the hard delete operation is $otp.</h4> </div>                                   
                                            </body></html>";

    }else{
        $message = "<html><head><title>!Hard Delete!</title></head><body><h3>Gateway - $gateway_id</h3>
                                  <div style=\"text-align: center\"><h4>The OTP for the hard delete operation is $otp.</h4> </div>                                   
                                              </body></html>";
    }
    
    $message = urlencode($message);
    $subject = urlencode($subject);
    $ch=curl_init();

    curl_setopt($ch,CURLOPT_URL,"http://cm2.sensegiz.com:9000/sensegiz-api/ses?message=".$message."&to=".$emailsArray."&subject=".$subject."");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $output =curl_exec($ch);
    curl_close($ch);
          
}



public function restoreGateway($inData){

        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        try {            
            	$gatewayId = $inData[JSON_TAG_GATEWAY_ID];

            
            	// Check Mandatory field    
            	if(empty($gatewayId)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}           

			            	  

		$sQuery = "UPDATE user_gateways SET is_deleted=0, updated_on = DATE_TRUNC('second', NOW())"                   
                   		. "WHERE gateway_id='$gatewayId' AND is_deleted =2";
            		$db->query($sQuery);
                                          
            
            		if($db->execute()){	
        	       		$res  = true;

				$sdQuery = " UPDATE gateway_devices SET is_deleted = 0, updated_on = DATE_TRUNC('second', NOW())"
                   		. " WHERE gateway_id='$gatewayId' AND is_deleted =2";
            			$db->query($sdQuery);           
            			$db->execute();
				
                		                         
                		if($db->execute()){
                                    
                    			$sQueryDeviceData = " UPDATE devices SET is_deleted = 0"
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted =2";
                    			$db->query($sQueryDeviceData);                         
                    			$db->execute();

					$sQueryDeviceStreamData = " UPDATE devices_stream SET is_deleted = 0"                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted =2";
                    			$db->query($sQueryDeviceStreamData);                         
                    			$db->execute();
					
					$sQueryDeviceThresholdData = " UPDATE threshold SET is_deleted = 0"                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted =2";
                    			$db->query($sQueryDeviceThresholdData);                         
                    			$db->execute();
					
					$sQueryGatewaySettings = " UPDATE gateway_settings SET is_deleted = 0"                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted =2";
                    			$db->query($sQueryGatewaySettings);                         
                    			$db->execute();
					
					$sQueryDeviceSettings = " UPDATE device_settings SET is_deleted = 0"                        
                        			. " WHERE gateway_id='$gatewayId'  AND is_deleted =2";
                    			$db->query($sQueryDeviceSettings);                         
                    			$db->execute();			

					$sQueryAcceleration = " UPDATE acceleration SET is_deleted = 0"                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted =2";
                    			$db->query($sQueryAcceleration);                         
                    			$db->execute();
					
					$sQueryVelocity = " UPDATE velocity SET is_deleted = 0"                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted =2";
                    			$db->query($sQueryVelocity);                         
                    			$db->execute();
					
					$sQueryDisplacement = " UPDATE displacement SET is_deleted = 0"                        
                        			. " WHERE gateway_id='$gatewayId' AND is_deleted =2";
                    			$db->query($sQueryDisplacement);                         
                    			$db->execute();

			  
					$ug_id = $rowGateway['ug_id'];
		
					$sQUeryLocation = " SELECT location_id FROM location_gateway "                        
                        			. " WHERE gateway_id='$ug_id'";

                    			$db->query($sQUeryLocation );                         
                    			$loc = $db->single();

					$location_id = $loc['location_id'];										
															
					if(!empty($loc)){		
						$sQueryUserLocation = " UPDATE user_locations SET is_deleted = 0"                        
                        				. " WHERE id='$location_id' AND is_deleted =2";
                    				$db->query($sQueryUserLocation );                         
                    				$db->execute();
					}	

																	
				}
            		}
         	           

             	$aList[JSON_TAG_RESULT] = $res;
            
            	$aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 
        return $aList;
}


public function restoreDevice($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        try {            

            	$deviceId           =   $inData[JSON_TAG_DEVICE_ID];
            	$gatewayId          =   $inData[JSON_TAG_GATEWAY_ID];

            
            	// Check Mandatory field    
            	if(empty($deviceId) && empty($gatewayId)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
			            
            	$sQuery = " UPDATE gateway_devices SET is_deleted = 0, updated_on = DATE_TRUNC('second', NOW())"
                   		. " WHERE gateway_id=:gateway_id AND device_id=:device_id  AND is_deleted =2";
            		$db->query($sQuery);
            		$db->bind(':device_id',$deviceId);                                
            		$db->bind(':gateway_id',$gatewayId);            
            		if($db->execute()){
                		$res  = true;
                
                    		$sQueryDeviceData = " UPDATE devices SET is_deleted = 0 "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =2";
                    		$db->query($sQueryDeviceData);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();                    
					
				$sQueryDeviceStreamData = " UPDATE devices_stream SET is_deleted = 0 "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =2";
                    		$db->query($sQueryDeviceStreamData);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();
					
				$sQueryDeviceThresholdData = " UPDATE threshold SET is_deleted = 0 "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =2";
                    		$db->query($sQueryDeviceThresholdData);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();
					
				$sQueryDeviceSettings = " UPDATE device_settings SET is_deleted = 0 "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =2";
                    		$db->query($sQueryDeviceSettings);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();

				$sQueryAcceleration = " UPDATE acceleration SET is_deleted = 0 "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =2";
                    		$db->query($sQueryAcceleration);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();
					
				$sQueryDeviceVelocity = " UPDATE velocity SET is_deleted = 0 "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =2";
                    		$db->query($sQueryDeviceVelocity);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();
					
				$sQueryDeviceDisplacement = " UPDATE displacement SET is_deleted = 0 "
                        		. " WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =2";
                    		$db->query($sQueryDeviceDisplacement);         
                    		$db->bind(':device_id',$deviceId);                                
                    		$db->bind(':gateway_id',$gatewayId);                                
                    		$db->execute();


            	        }             
            
             	$aList[JSON_TAG_RESULT] = $res;
            
            	$aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 

        return $aList;
}

public function updateTimeFactor($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();  
 
        try {            
            $gatewayId          = $inData[JSON_TAG_GATEWAY_ID];
		$time_factor = $inData[JSON_TAG_TIME_FACTOR];
            
            // Check Mandatory field    
            if(empty($gatewayId)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
            $rowGateway  = $this->checkGateway($db,$gatewayId);
            if(empty($rowGateway)){ $aList[JSON_TAG_STATUS] = 3; return $aList;}


            $res = '';
            $sQuery = "UPDATE user_gateways "
                   . "SET time_factor=:time_factor,updated_on=DATE_TRUNC('second', NOW()) "
                   . "WHERE gateway_id=:gateway_id AND is_deleted =0";
            $db->query($sQuery);
            $db->bind(':time_factor',$time_factor);
            $db->bind(':gateway_id',$gatewayId);                                
            
            if($db->execute()){
                $res  = true;
            }
            else{
                $res  = false;
            }                      

             $aList[JSON_TAG_RESULT] = $res;
            
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


    public function getCoinDetail($coinMacAddr){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();

        try {      
            $coinQuery = " SELECT * "
                    . " FROM gateway_devices "                                   
                    . " WHERE device_mac_address =:coinMacAddr";                 
            $db->query($coinQuery);
            $db->bind('coinMacAddr', $coinMacAddr);
            $rowCoinSet  =  $db->resultSet();

            // $mac_address = $rowCoinSet['device_mac_address'];

            $aList[JSON_TAG_RESULT]= $rowCoinSet;
            $aList[JSON_TAG_STATUS] = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;   
    }

    
    public function updateGatewayIdRecover($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   

        try {            
              $old_gateway_id           =   $inData[JSON_TAG_OLG_GATEWAY_ID];
              $new_gateway_id          =   $inData[JSON_TAG_NEW_GATEWAY_ID];
            
              // Check Mandatory field    
              if(empty($old_gateway_id) && empty($new_gateway_id)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
                  
              $sQuery = " UPDATE user_gateways SET gateway_id = :new_gateway_id, updated_on = DATE_TRUNC('second', NOW())"
                      . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
              $db->query($sQuery);
              $db->bind(':old_gateway_id',$old_gateway_id);                                
              $db->bind(':new_gateway_id',$new_gateway_id);            
                if($db->execute()){
                    $res  = true;
                
                        $sQueryGatewayDevices = " UPDATE gateway_devices SET gateway_id = :new_gateway_id, updated_on = DATE_TRUNC('second', NOW())"
                                . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
                        $db->query($sQueryGatewayDevices);
                        $db->bind(':old_gateway_id',$old_gateway_id);                                
                        $db->bind(':new_gateway_id',$new_gateway_id);                              
                        
                        if($db->execute()){
          
                            $sQueryAbsoluteHumidity = " UPDATE absolutehumidity SET gateway_id = :new_gateway_id "
                                            . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
                                    $db->query($sQueryAbsoluteHumidity);
                                    $db->bind(':old_gateway_id',$old_gateway_id);                                
                                    $db->bind(':new_gateway_id',$new_gateway_id);                              
                                    $db->execute();
                      
                            $sQueryAcceleration = " UPDATE acceleration SET gateway_id = :new_gateway_id "
                                            . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
                                    $db->query($sQueryAcceleration);
                                    $db->bind(':old_gateway_id',$old_gateway_id);                                
                                    $db->bind(':new_gateway_id',$new_gateway_id);                               
                                    $db->execute();
                      
                            $sQueryAccelerationTypes = " UPDATE acceleration_types SET gateway_id = :new_gateway_id "
                                            . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
                                    $db->query($sQueryAccelerationTypes);
                                    $db->bind(':old_gateway_id',$old_gateway_id);                                
                                    $db->bind(':new_gateway_id',$new_gateway_id);                               
                                    $db->execute();

                            $sQueryDeviceSettings = " UPDATE device_settings SET gateway_id = :new_gateway_id "
                                            . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
                                    $db->query($sQueryDeviceSettings);
                                    $db->bind(':old_gateway_id',$old_gateway_id);                                
                                    $db->bind(':new_gateway_id',$new_gateway_id);                                
                                    $db->execute();
                      
                            $sQueryDevices = " UPDATE devices SET gateway_id = :new_gateway_id "
                                            . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
                                    $db->query($sQueryDevices);
                                    $db->bind(':old_gateway_id',$old_gateway_id);                                
                                    $db->bind(':new_gateway_id',$new_gateway_id);                                
                                    $db->execute();
                      
                            $sQueryDevicesStream = " UPDATE devices_stream SET gateway_id = :new_gateway_id "
                                            . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
                                    $db->query($sQueryDevicesStream);
                                    $db->bind(':old_gateway_id',$old_gateway_id);                                
                                    $db->bind(':new_gateway_id',$new_gateway_id);                                
                                    $db->execute();

                            $sQueryDewpointTemperature = " UPDATE dewpointtemperature SET gateway_id = :new_gateway_id "
                                            . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
                                    $db->query($sQueryDewpointTemperature);
                                    $db->bind(':old_gateway_id',$old_gateway_id);                                
                                    $db->bind(':new_gateway_id',$new_gateway_id);                                
                                    $db->execute();

                            $sQueryDisplacement = " UPDATE displacement SET gateway_id = :new_gateway_id "
                                            . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
                                    $db->query($sQueryDisplacement);
                                    $db->bind(':old_gateway_id',$old_gateway_id);                                
                                    $db->bind(':new_gateway_id',$new_gateway_id);                                
                                    $db->execute();

                            $sQueryGatewaySettings = " UPDATE gateway_settings SET gateway_id = :new_gateway_id "
                                            . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
                                    $db->query($sQueryGatewaySettings);
                                    $db->bind(':old_gateway_id',$old_gateway_id);                                
                                    $db->bind(':new_gateway_id',$new_gateway_id);                                
                                    $db->execute();

                            $sQueryRequestList = " UPDATE request_list SET gateway_id = :new_gateway_id "
                                            . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
                                    $db->query($sQueryRequestList);
                                    $db->bind(':old_gateway_id',$old_gateway_id);                                
                                    $db->bind(':new_gateway_id',$new_gateway_id);                                
                                    $db->execute();

                            $sQueryVelocity = " UPDATE velocity SET gateway_id = :new_gateway_id "
                                            . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
                                    $db->query($sQueryVelocity);
                                    $db->bind(':old_gateway_id',$old_gateway_id);                                
                                    $db->bind(':new_gateway_id',$new_gateway_id);                                
                                    $db->execute();

                            $sQueryThreshold = " UPDATE threshold SET gateway_id = :new_gateway_id"
                                            . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
                                    $db->query($sQueryThreshold);
                                    $db->bind(':old_gateway_id',$old_gateway_id);                                
                                    $db->bind(':new_gateway_id',$new_gateway_id);                              
                                    $db->execute();

                            $sQueryGatewayMapping = " UPDATE gateway_mapping SET gateway_id = :new_gateway_id"
                                            . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
                                    $db->query($sQueryGatewayMapping);
                                    $db->bind(':old_gateway_id',$old_gateway_id);                                
                                    $db->bind(':new_gateway_id',$new_gateway_id);                              
                                    $db->execute();

                            $sQueryEventLog = " UPDATE event_log SET gateway_id = :new_gateway_id "
                                            . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
                                    $db->query($sQueryEventLog);
                                    $db->bind(':old_gateway_id',$old_gateway_id);                                
                                    $db->bind(':new_gateway_id',$new_gateway_id);                              
                                    $db->execute();

                            $sQueryMailAlertTrigger = " UPDATE mail_alert_trigger SET gateway_id = :new_gateway_id "
                                            . " WHERE gateway_id=:old_gateway_id AND is_deleted = 0";
                                    $db->query($sQueryMailAlertTrigger);
                                    $db->bind(':old_gateway_id',$old_gateway_id);                                
                                    $db->bind(':new_gateway_id',$new_gateway_id);                              
                                    $db->execute();

                        }
                }             
            
              $aList[JSON_TAG_RESULT] = $res;
            
              $aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 

        return $aList;
    }


    // Device Data Delete between Two Dates and Set the Temporory Status as '3'
    public function softDelDeviceToFrom($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        try {            

              $deviceId           =   $inData[JSON_TAG_DEVICE_ID];
              $gatewayId          =   $inData[JSON_TAG_GATEWAY_ID];
              $sensorType         =   $inData[JSON_TAG_SENSOR_TYPE];
              $otp                =   $inData[JSON_TAG_OTP];
              $toDate             =   $inData[JSON_TAG_TO_DATE];
              $fromDate           =   $inData[JSON_TAG_FROM_DATE];
            

              if($sensorType == '01'){ $sensorType2 = "'01', '02'"; }
              if($sensorType == '03'){ $sensorType2 = "'03', '04'"; }
              if($sensorType == '05'){ $sensorType2 = "'05', '06'"; }
              if($sensorType == '07'){ $sensorType2 = "'07', '08'"; }
              if($sensorType == '09'){ $sensorType2 = "'09'"; }
              if($sensorType == '10'){ $sensorType2 = "'10'"; }
              if($sensorType == '12'){ $sensorType2 = "'12', '14', '15', '28', '17', '18', '19', '29', '20', '21', '22', '30', '23', '24', '25', '31'"; }
              if($sensorType == 'allSensor')
              { 
                $sensorType2 = "'01', '02', '03', '04', '05', '06', '07', '09', '10', '08', '12', '14', '15', '28', '17', '18', '19', '29', '20', '21', '22', '30', '23', '24', '25', '31'"; 
              }

              // Check Mandatory field    
              if(empty($deviceId) && empty($gatewayId)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
              $rowDevice  = $this->checkGatewayDevice($db,$gatewayId,$deviceId);
              if(empty($rowDevice)){ $aList[JSON_TAG_STATUS] = 3; return $aList; }

              $sQUery11 = " SELECT otp FROM admin "                        
                                  . " WHERE admin_id = 1";

                          $db->query($sQUery11);                         
                          $chkotp = $db->single();

              $dbotp = $chkotp['otp'];

              $res = '';
    

              if($otp == $dbotp){
      
                      $res  = true;
            
                      $sQueryDeviceStreamData = " UPDATE devices_stream SET is_deleted = 3 "
                                          . " WHERE (gateway_id='$gatewayId') AND (device_id='$deviceId') AND (device_type IN ($sensorType2)) AND (added_on BETWEEN '$fromDate' AND '$toDate') AND (is_deleted =0)";
                                      $db->query($sQueryDeviceStreamData);         
                                      $db->execute();
                      
                      $sQueryDeviceThresholdData = " UPDATE threshold SET is_deleted = 3 "
                                          . " WHERE (gateway_id='$gatewayId') AND (device_id='$deviceId') AND (device_type IN ($sensorType2)) AND (updated_on BETWEEN '$fromDate' AND '$toDate') AND (is_deleted =0)";
                                      $db->query($sQueryDeviceThresholdData);         
                                      $db->execute();
                        
                      $sQueryAcceleration = " UPDATE acceleration SET is_deleted = 3 "
                                          . " WHERE (gateway_id='$gatewayId') AND (device_id='$deviceId') AND (device_type IN ($sensorType2)) AND (added_on BETWEEN '$fromDate' AND '$toDate') AND (is_deleted =0)";
                                      $db->query($sQueryAcceleration);         
                                      $db->execute();
                        
                      $sQueryDeviceVelocity = " UPDATE velocity SET is_deleted = 3 "
                                          . " WHERE (gateway_id='$gatewayId') AND (device_id='$deviceId') AND (device_type IN ($sensorType2)) AND (added_on BETWEEN '$fromDate' AND '$toDate') AND (is_deleted =0)";
                                      $db->query($sQueryDeviceVelocity);         
                                      $db->execute();
                        
                      $sQueryDeviceDisplacement = " UPDATE displacement SET is_deleted = 3 "
                                          . " WHERE (gateway_id='$gatewayId') AND (device_id='$deviceId') AND (device_type IN ($sensorType2)) AND (added_on BETWEEN '$fromDate' AND '$toDate') AND (is_deleted =0)";
                                      $db->query($sQueryDeviceDisplacement);         
                                      $db->execute();

                      $sQueryDeviceDewpointtemperature = " UPDATE dewpointtemperature SET is_deleted = 3 "
                                          . " WHERE (gateway_id='$gatewayId') AND (device_id='$deviceId') AND (device_type IN ($sensorType2)) AND (added_on BETWEEN '$fromDate' AND '$toDate') AND (is_deleted =0)";
                                      $db->query($sQueryDeviceDewpointtemperature);         
                                      $db->execute();

                      $sQueryDeviceAbsolutehumidity = " UPDATE absolutehumidity SET is_deleted = 3 "
                                          . " WHERE (gateway_id='$gatewayId') AND (device_id='$deviceId') AND (device_type IN ($sensorType2)) AND (added_on BETWEEN '$fromDate' AND '$toDate') AND (is_deleted =0)";
                                      $db->query($sQueryDeviceAbsolutehumidity);         
                                      $db->execute();
              }
              else{
                  $aList[JSON_TAG_STATUS] = 4; return $aList;

              }                      
            
             $aList[JSON_TAG_RESULT] = $res;
            
            $aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 
        return $aList;
    }

    
    
    public function updateCoinDeviceMacAddr($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   

        try {            
              $otp                =   $inData[JSON_TAG_OTP];
              $old_coindevice_mac =   $inData[JSON_TAG_OLD_COINDEVICE_MAC];
              $new_coindevice_mac =   $inData[JSON_TAG_NEW_COINDEVICE_MAC];
              $deviceId           =   $inData[JSON_TAG_DEVICE_ID];
              $gatewayId          =   $inData[JSON_TAG_GATEWAY_ID];
            
              // Check Mandatory field    
              if(empty($deviceId) && empty($gatewayId) && empty($old_coindevice_mac) && empty($new_coindevice_mac)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
              
              $rowDevice  = $this->checkGatewayDevice($db,$gatewayId,$deviceId);
              if(empty($rowDevice)){ $aList[JSON_TAG_STATUS] = 3; return $aList; }

              $uOtpQUery11 = " SELECT otp FROM admin "                        
                                  . " WHERE admin_id = 1";

                          $db->query($uOtpQUery11);                         
                          $chkotp = $db->single();

              $dbotp = $chkotp['otp'];

              $res = '';
              
              if($otp == $dbotp){
      
                  $res  = true;    
                  
                  $uCoinMacQuery = " UPDATE gateway_devices SET device_mac_address = :new_coindevice_mac, updated_on = DATE_TRUNC('second', NOW())"
                          . " WHERE device_mac_address=:old_coindevice_mac AND gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 0";
                  $db->query($uCoinMacQuery);
                  $db->bind(':old_coindevice_mac',$old_coindevice_mac);                                
                  $db->bind(':new_coindevice_mac',$new_coindevice_mac);    
                  $db->bind(':gateway_id',$gatewayId);
                  $db->bind(':device_id',$deviceId);  
                  $db->execute();      
              }
              else{
                  $aList[JSON_TAG_STATUS] = 4; return $aList;
              }                      
              
              $aList[JSON_TAG_RESULT] = $res;
            
              $aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 

        return $aList;
    }


    public function setCoinTypeSize($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   

        try {            
              $coin_type_size     =   $inData[JSON_TAG_DEVICE_TYPE_SIZE];
              $device_mac_address =   $inData[JSON_TAG_DEVICE_MAC_ADDRESS];
              $deviceId           =   $inData[JSON_TAG_DEVICE_ID];
              $gatewayId          =   $inData[JSON_TAG_GATEWAY_ID];
            
              // Check Mandatory field    
              if(empty($deviceId) && empty($gatewayId) && empty($device_mac_address) && empty($coin_type_size)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
              
              $rowDevice  = $this->checkGatewayDevice($db,$gatewayId,$deviceId);
              if(empty($rowDevice)){ $aList[JSON_TAG_STATUS] = 3; return $aList; }

              $res = '';
                  
              $uCoinTypeQuery = " UPDATE gateway_devices SET coin_type = :coin_type_size, updated_on = DATE_TRUNC('second', NOW())"
                      . " WHERE device_mac_address=:device_mac_address AND gateway_id=:gateway_id AND device_id=:device_id AND is_deleted = 0";
              $db->query($uCoinTypeQuery);
              $db->bind(':coin_type_size',$coin_type_size);                                
              $db->bind(':device_mac_address',$device_mac_address);    
              $db->bind(':gateway_id',$gatewayId);
              $db->bind(':device_id',$deviceId);  
              if($db->execute()){
                  $res = true;
              }else{
                  $res = false;
              }
              
              $aList[JSON_TAG_RESULT] = $res;
            
              $aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 

        return $aList;
    }

    

    public function eraseGatewayData($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   

        try {            
              $gatewayId          =   $inData[JSON_TAG_GATEWAY_ID];
            
              // Check Mandatory field    
              if(empty($gatewayId)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
              $rowDevice  = $this->checkGateway($db,$gatewayId);
              if(empty($rowDevice)){ $aList[JSON_TAG_STATUS] = 3; return $aList; }

              $res = '';
                  
              $uCoinTypeQuery = " INSERT INTO request_list(gateway_id, device_id, device_type, request_type, dynamic_id, action) "
                            . "VALUES (:gateway_id, 'FF', '00', 'EGDERASE', '00', -1)";
              $db->query($uCoinTypeQuery);
              $db->bind(':gateway_id',$gatewayId);
              if($db->execute()){
                  $res = true;
              }else{
                  $res = false;
              }
              
              $aList[JSON_TAG_RESULT] = $res;
            
              $aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 

        return $aList;
    }


    public function setSensorTypeRequest($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        $curl     = new CurlRequest();
        try {            
              $gatewayId          =   $inData[JSON_TAG_GATEWAY_ID];
              $deviceId           =   $inData[JSON_TAG_DEVICE_ID];
            
              // Check Mandatory field    
              if(empty($gatewayId)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
              $rowDevice  = $this->checkGateway($db,$gatewayId);
              if(empty($rowDevice)){ $aList[JSON_TAG_STATUS] = 3; return $aList; }

              $GatewayMacID = $this->getGatewayMacID($db, $gatewayId);
              $gateway_mac_id = $GatewayMacID[0]['gateway_mac_id'];

              $res = '';
                  
              $uCoinTypeQuery = " INSERT INTO request_list(gateway_id, device_id, device_type, request_type, dynamic_id, action,format,rate_value,gateway_mac_id) "
                            . "VALUES (:gateway_id, :deviceId, '32', 'GETSENSOR', '01', 0, '01', '00', :gateway_mac_id)";
              $db->query($uCoinTypeQuery);
              $db->bind(':gateway_id',$gatewayId);
              $db->bind(':deviceId',$deviceId);
              $db->bind(':gateway_mac_id',$gateway_mac_id);
              if($db->execute()){
                // if(!empty($GatewayMacID)){
                
                                $response = TRUE;
                    $data_arr  =  array();
                    
                                $data_arr['gateway_id']    =  $gatewayId;
                                $data_arr['device_id']     =  $deviceId;
                                $data_arr['gateway_mac_id']    =  $gateway_mac_id;
                                $data_arr['device_type']   =  '32';
                    
                                $data_arr['format']        =  '01';
                    
                                $data_arr['rate_value']     =  '00';

                                $data_arr['dynamic_id']     =  '01';  
                    
                                $data_arr['topic']         =  TOPIC_SET_STREAM;
                    
                            $url        =  URL_END_POINT.'sensegiz-mqtt/publish.php';
                
                            $response   =  $curl->postRequestData($url, $data_arr);
                // }
                  $res = true;
              }else{
                  $res = false;
              }
              
              $aList[JSON_TAG_RESULT] = $res;
              $aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 

        return $aList;
    }


    function getGatewayMacID($db, $gateway_id){
            $sQuery = " SELECT gateway_mac_id"
                    . " FROM user_gateways"                    
                    . " WHERE gateway_id=:gateway_id AND is_deleted =0 AND is_blacklisted = 'N'";       

            $db->query($sQuery);  
            $db->bind(':gateway_id',$gateway_id);

            $row = $db->resultSet();
            
            return $row;
    }
    
    public function updateGatewayUser($inData){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod();   

        try {            
              $otp                =   $inData[JSON_TAG_OTP];
              $newUserId           =   $inData[JSON_TAG_USER_ID];
              $gatewayId          =   $inData[JSON_TAG_GATEWAY_ID];

              // Check Mandatory field    
              if(empty($newUserId) && empty($gatewayId)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}

            //   $rowDevice  = $this->checkGatewayDevice($db,$gatewayId,$deviceId);
            //   if(empty($rowDevice)){ $aList[JSON_TAG_STATUS] = 3; return $aList; }

              $uOtpQUery11 = " SELECT otp FROM admin "                        
                                  . " WHERE admin_id = 1";

                          $db->query($uOtpQUery11);                         
                          $chkotp = $db->single();

              $dbotp = $chkotp['otp'];

              $res = '';

              if($otp == $dbotp){

                  $res  = true;    

                  $uCoinMacQuery = " UPDATE gateway_devices SET user_id = :newUserId, updated_on = DATE_TRUNC('second', NOW())"
                          . " WHERE gateway_id=:gateway_id AND is_deleted = 0";
                  $db->query($uCoinMacQuery);
                  $db->bind(':newUserId',$newUserId);    
                  $db->bind(':gateway_id',$gatewayId);
                  $db->execute();     

                  $uQuery = " UPDATE user_gateways SET user_id = :newUserId, updated_on = DATE_TRUNC('second', NOW())"
                          . " WHERE gateway_id=:gateway_id AND is_deleted = 0";
                  $db->query($uQuery);
                  $db->bind(':newUserId',$newUserId);    
                  $db->bind(':gateway_id',$gatewayId);
                  $db->execute();   

                  $sQuery = " UPDATE mail_alert_trigger SET user_id = :newUserId, updated_on = DATE_TRUNC('second', NOW())"
                          . " WHERE gateway_id=:gateway_id ";
                  $db->query($sQuery);
                  $db->bind(':newUserId',$newUserId);    
                  $db->bind(':gateway_id',$gatewayId);
                  $db->execute();
              }
              else{
                  $aList[JSON_TAG_STATUS] = 4; return $aList;
              }                      

              $aList[JSON_TAG_RESULT] = $res;

              $aList[JSON_TAG_STATUS] = 0;          
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        } 

        return $aList;
    }




    public function checkGatewayUser($uid){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod(); 

        try{
            $user_id = $uid;

            // Check Mandatory field    
            if(empty($user_id)) { 
                $aList[JSON_TAG_STATUS] = 1; 
                return $aList;
            }

            $sQuery = "SELECT * "
            . " FROM user_gateways "                                   
            . " WHERE (user_id =:user_id) ORDER BY ug_id DESC";                 
            $db->query($sQuery);  
            $db->bind(':user_id',$user_id);
            
            $row = $db->resultSet();
            $aList[JSON_TAG_RESULT] = $row;
            $aList[JSON_TAG_STATUS] = 0;

        }catch (Exception $e){
            $aList[JSON_TAG_STATUS] = 1;
        }

        return $aList;
    }




    public function deleteGatewayUser($uid){
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod(); 

        try{
            $user_id = $uid;

           // Check Mandatory field    
            if(empty($user_id)) { 
                $aList[JSON_TAG_STATUS] = 1; 
                return $aList;
            }

            $sQuery = "DELETE "
                    . " FROM users "                                   
                    . " WHERE user_id =:user_id";                 
            $db->query($sQuery); 
            $db->bind('user_id',$user_id);
             
            if($db->execute()){
                // $row = $db->resultSet();
                $aList[JSON_TAG_STATUS] = 0;
            }
            else{
                $aList[JSON_TAG_STATUS] = 1;
            }
        }
        catch (Exception $e){
            $aList[JSON_TAG_STATUS] = 1;
        }

        return $aList;
    }



    public function updateGatewayUserN($inData){
        $db = new ConnectionManager();
        $db1 = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        $curl     = new CurlRequest();
        
        try {            
            $u_id = $inData[JSON_TAG_U_ID];
            $user_email = $inData[JSON_TAG_USER_EMAIL];
            
            // Check Mandatory field    
            if(empty($u_id)) { 
                $aList[JSON_TAG_STATUS] = 2; return $aList;
            }

            $sQuery = "SELECT user_email "
            . " FROM users "                                   
            . " WHERE user_email =:user_email";                 
            $db->query($sQuery);  
            $db->bind('user_email',$user_email);
            // //  $row = $db->resultSet();
            if($db->execute()){
                $data = $db->resultSet();
                $aList[JSON_TAG_STATUS] = 0; 
                $aList[JSON_TAG_RESULT] = $data;
                
            }    
            else{
                $aList[JSON_TAG_STATUS] = 1;
            }                
           
            
        }   
        catch (Exception $e){
            $aList[JSON_TAG_STATUS] = 1;
        }

        return $aList;
    }

    public function updateBuzzerStatus($inData){
        $db = new ConnectionManager();
        $db1 = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        $curl     = new CurlRequest();
        
        try {       
            
            echo " updateBuzzerStatus ", json_encode($inData), "_______   ";

            $gateway_id = $inData['gateway_id'];
            $buzzer_status= $inData['buzzer_status'];
           
            echo "gateway_id: " . $gateway_id . "<br>";
            echo "buzzer_status:-> " . $buzzer_status . "<br>";
        
            $query = " UPDATE user_gateways"
                . " SET buzzer=:buzzer_status "
                . " WHERE gateway_id=:gateway_id";

            $db->query($query);
            $db->bind(':gateway_id', $gateway_id);
            $db->bind(':buzzer_status', $buzzer_status);
            $db->execute();
            $aList[JSON_TAG_STATUS] = 0;
        } catch (Exception $e) {

            $aList[JSON_TAG_STATUS] = 1;
        }
        return $aList;
    }


    public function UpdateUser_email($inData){
        $db = new ConnectionManager();
        $db1 = new ConnectionManager();
        $generalMethod = new GeneralMethod();   
        $curl     = new CurlRequest();
        
        try {            
            $u_id = $inData[JSON_TAG_U_ID];
            $user_email = $inData[JSON_TAG_USER_EMAIL];
            
            // Check Mandatory field    
            if(empty($u_id)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
            $res = '';
            $eQuery =" UPDATE users SET user_email = :user_email"
            . " WHERE user_id=:u_id";
            $db->query($eQuery);
            $db->bind(':u_id',$u_id);
            $db->bind(':user_email',$user_email);
             
            if($db->execute()){
                   
                $aList[JSON_TAG_STATUS] = 0;       
            }
            else{
                $aList[JSON_TAG_STATUS] = 1;
            }
            
        }   
        catch (Exception $e){
            $aList[JSON_TAG_STATUS] = 1;
        }

        return $aList;
    }
//    
}


