<?php
/*
  Project                     : Sensegiz
  Module                      : Device Console --  Manager Class
  File name                   : DeviceManager.php
  Description                 : Manager class for Device related activities
 */

   
class AppManager {

     /*
      Function            : checkUserId($db,$userId)
      Brief               : Check user exist 
      Details             : Check user exist and as well as get user info  
      Input param         : $userId
      Input/output param  : array
      Return              : Returns array.
     */    
    function checkUserId($db,$userId){
        
        $sQuery = " SELECT * "
                . " FROM users "
                . " WHERE user_id =:user_id AND is_deleted =0 ";
        $db->query($sQuery);
        $db->bind(':user_id',$userId);
        return  $row = $db->single();
    }  
  
     /*
      Function            : loginUser($inData)
      Brief               : Function to login normal User
      Details             : Function to login normal User
      Input param         : Nil
      Input/output param  : Nil
      Return              : Returns array.
     */
    public function loginUser($inData){
        
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod(); 
        try {                           
//            print_r($inData);exit();
            $email    = $generalMethod->sanitizeString($inData[JSON_TAG_EMAIL]);
            $password = $generalMethod->sanitizeString($inData[JSON_TAG_PASSWORD]);
            
            // Check Mandatory field    
            if(empty($email) || empty($password)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
            $password   = md5($password);
             
            // Check user already Exist 
            $sQuery = " SELECT user_id,user_email,verification_code,added_on,updated_on"
                    . " FROM  users "
                    . " WHERE user_email =:user_email AND user_password =:user_password AND is_deleted=0 ";
            $db->query($sQuery);
            $db->bind(':user_email',$email);
            $db->bind(':user_password',$password);
            $row = $db->single();
            
            if(empty($row)){
                $aList[JSON_TAG_STATUS] = 3; return $aList;
            }
            $userId          =  $row['user_id'];
//            $hasUserApiKey   =  $this->hasUserApiKey($db, $userId);
//            
            $apiKey   =   $generalMethod->generateApiKey();
//            if(!empty($hasUserApiKey)){
//                $rowUp   = $this->updateApiKey($db,$userId,$apiKey);
//            }
//            else{
//                $rowAdd  = $this->addApiKey($db,$userId,$apiKey);
                $rowId  = $this->addApiKey($db,$userId,$apiKey);
//            }
            if($rowId){
                $row['key_details']   =  $this->hasUserApiKey($db, $rowId);
            }
            else{
                $row['key_details']   = '';
            }
            
              
            $aList[JSON_TAG_RESULT]  = $row;    
            
            $aList[JSON_TAG_STATUS]  = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;    
    }
   
    //Insert API Key
    function addApiKey($db,$userId,$apiKey){
                
        $query = "INSERT INTO api_keys(user_id, api_key, added_on, updated_on, expires_on) "
                . "VALUES (:user_id, :api_key, NOW(), NOW(), (NOW() + INTERVAL '15' DAY))  RETURNING id";
        $db->query($query);
        $db->bind(':user_id', $userId);
        $db->bind(':api_key', $apiKey);
        $res = $db->resultSet();

        $rowId = $res[0][id];
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
      Function            : checkEmail($db,$email)
      Brief               : Check email exist 
      Details             : Check email exist and as well as get user info  
      Input param         : $email
      Input/output param  : array
      Return              : Returns array.
     */    
    function checkEmail($db,$email){
        
        $sQuery = " SELECT user_id,name,email,profile_pic,phone,license_plate_no,is_mail_verified,added_on,updated_on"
                . " FROM users "
                . " WHERE email =:email AND is_deleted =0 ";
        $db->query($sQuery);
        $db->bind(':email',$email);
        return  $row = $db->single();
    } 
    
     /*
      Function            : forgotPassword($inData)
      Brief               : Function to send reset password link
      Details             : Function to send reset password link
      Input param         : Nil
      Input/output param  : Nil
      Return              : Returns array.
     */
    public function forgotPassword($inData){
        
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod(); 
        try {                                 
            $email    = $generalMethod->sanitizeString($inData[JSON_TAG_EMAIL]);            
//            print_r($email);
//            exit();
            // Check Mandatory field    
            if(empty($email)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}            
          
            // Check user already Exist 
            $sQuery = " SELECT user_id,user_email,verification_code,added_on,updated_on"
                    . " FROM  users "
                    . " WHERE user_email=:user_email AND is_deleted=0 ";
            $db->query($sQuery);
            $db->bind(':user_email',$email);            
            $row = $db->single();
            
            if(empty($row)){
                $aList[JSON_TAG_STATUS] = 3; return $aList;
            }
            
            $userId  =  $row['user_id'];
            
            $encId   =  $row['user_id'].rand(111, 999);

            $encodedString =  md5($encId);
            
            //
            $sQuery = " UPDATE users "
                    . " SET encrypted_code=:encrypted_code,updated_on=now()"
                    . " WHERE user_id=:user_id AND is_deleted =0";
            $db->query($sQuery);
            $db->bind(':user_id',$userId);
            $db->bind(':encrypted_code',$encodedString);            
            $db->execute();            
            

            $emailsArray   =  array($email);

            $this->sendResetPasswordLink($emailsArray, $encodedString);                    

            $aList[JSON_TAG_RESULT] = 'Password reset link sent to your email id, please check it.';
                
            
            $aList[JSON_TAG_STATUS]  = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;    
    }
    
    //To send Reset Password Link
    function sendResetPasswordLink($emailsArray, $encodedString) {
        $generalMethod = new GeneralMethod();

        $subject = "Reset password";
//$encodedString  = urlencode($encodedString);
        $link = BASE_URL_STRING . "/resetpass.php?enc=$encodedString";

        $message = 'Click here to reset your password <a href="' . $link . '">Click Here</a>';

        $verifyMail = $generalMethod->sendMails($emailsArray, $subject, $message);
    }      
 
     /*
      Function            : changePassword($inData)
      Brief               : Function to change password
      Details             : Function to change password
      Input param         : Nil
      Input/output param  : Nil
      Return              : Returns array.
     */
    public function changePassword($inData,$userId){
        
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod(); 
        try {                                       
            $currentPassword = $generalMethod->sanitizeString($inData[JSON_TAG_CURRENT_PASSWORD]);

            $newPassword     = $generalMethod->sanitizeString($inData[JSON_TAG_NEW_PASSWORD]);
            
            // Check Mandatory field    
            if(empty($userId) || empty($currentPassword) || empty($newPassword)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}
            
            $rowUser           =    $this->checkUserId($db,$userId);
            if(empty($rowUser)){
                $aList[JSON_TAG_STATUS] = 3; return $aList;
            }            
                        
            $currentPassword   = md5($currentPassword);
             
            // Check user already Exist 
            $sQuery = " SELECT user_id,user_email,verification_code,added_on,updated_on"
                    . " FROM  users "
                    . " WHERE user_id =:user_id AND user_password =:user_password AND is_deleted=0 ";
            $db->query($sQuery);
            $db->bind(':user_id',$userId);
            $db->bind(':user_password',$currentPassword);
            $row = $db->single();
            
            if(empty($row)){
                $aList[JSON_TAG_STATUS] = 4; return $aList;
            }
            
            $res  =  false;
            $newPassword  = md5($newPassword);
            $sQuery = " UPDATE users "
                    . " SET user_password=:user_password,updated_on=now()"
                    . " WHERE user_id=:user_id AND is_deleted =0";
            $db->query($sQuery);
            $db->bind(':user_id',$userId);
            $db->bind(':user_password',$newPassword);            
            if($db->execute()){
                $res  =  true;
            }
              
            $aList[JSON_TAG_RESULT]  = $res;    
            
            $aList[JSON_TAG_STATUS]  = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;    
    }
     
     /*
      Function            : addGateways($inData)
      Brief               : Function to Add Gateways
      Details             : Function to Add Gateways
      Input param         : Nil
      Input/output param  : Nil
      Return              : Returns array.
     */
    public function addGateways($inData){
        
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod(); 
        try {                           
            $userId       =    $inData[JSON_TAG_USER_ID];
            $gatewayId    =    $inData[JSON_TAG_GATEWAY_ID];
            
            // Check Mandatory field    
            if(empty($userId) || empty($gatewayId)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}            
            
            $rowUser           =    $this->checkUserId($db,$userId);
            if(empty($rowUser)){
                $aList[JSON_TAG_STATUS] = 3; return $aList;
            } 
            
            $rowUsergateway = $this->checkUserGateway($db,$userId,$gatewayId);
            
            if(!empty($rowUsergateway)){
                    $sQuery = " UPDATE user_gateways "
                            . " SET updated_on=now()"
                            . " WHERE user_id =:user_id AND gateway_id =:gateway_id AND is_deleted=0 ";
                    $db->query($sQuery);
                    $db->bind(':user_id',$userId);
                    $db->bind(':gateway_id',$gatewayId);      
                    $db->execute();                                
            }
            else{
                $query = "INSERT INTO user_gateways(user_id, gateway_id, gateway_mac_id, added_on, updated_on) "
                        . "VALUES (:user_id, :gateway_id, :gateway_id, NOW(), NOW())";
                $db->query($query);
                $db->bind(':user_id',$userId);
                $db->bind(':gateway_id',$gatewayId);      
                $db->execute();                
            }
            
            //Get user & Gateway details
            $rowUsergateway = $this->checkUserGateway($db,$userId,$gatewayId);
              
            $aList[JSON_TAG_RESULT]  = $rowUsergateway;    
            
            $aList[JSON_TAG_STATUS]  = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;    
    }
    
    
    //check user id & gateway id exists
    function checkUserGateway($db,$userId,$gatewayId){
        
        $sQuery = " SELECT * "
                . " FROM user_gateways "
                . " WHERE user_id =:user_id AND gateway_id =:gateway_id AND is_deleted=0 ";
        $db->query($sQuery);
        $db->bind(':user_id',$userId);
        $db->bind(':gateway_id',$gatewayId);

        return $row = $db->single();        
    }
   
     /*
      Function            : getGateways($userId)
      Brief               : Function to Get Gateways
      Details             : Function to Get Gateways
      Input param         : $userId
      Input/output param  : Nil
      Return              : Returns array.
     */
    public function getGateways($userId){
        
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod(); 
        try {                           
            
            $rowUser           =    $this->checkUserId($db,$userId);
            if(empty($rowUser)){
                $aList[JSON_TAG_STATUS] = 3; return $aList;
            } 
            
                $sQuery = " SELECT * "
                        . " FROM user_gateways "
                        . " WHERE user_id =:user_id AND is_deleted=0 ORDER BY ug_id DESC ";
                $db->query($sQuery);
                $db->bind(':user_id',$userId);               

            $rowUsergateway  =   $db->resultSet();             
            
              
            $aList[JSON_TAG_RESULT]  = $rowUsergateway;    
            
            $aList[JSON_TAG_STATUS]  = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;    
    }
        
     /*
      Function            : addDevices($inData)
      Brief               : Function to Add Devices
      Details             : Function to Add Devices
      Input param         : Nil
      Input/output param  : Nil
      Return              : Returns array.
     */
    public function addDevices($inData){
        
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod(); 
        try {                           
            $deviceMacAddress       =    $inData[JSON_TAG_DEVICE_MAC_ADDRESS];
            $gatewayId              =    $inData[JSON_TAG_GATEWAY_ID];
            $userId                 =    $inData[JSON_TAG_USER_ID];
            $nickName               =    $inData[JSON_TAG_NICK_NAME];
            $coin_location          =    $inData[JSON_TAG_COIN_LOCATION];
//             print_r($inData);
            // Check Mandatory field    
            if(empty($deviceMacAddress) || empty($gatewayId) || empty($userId) || empty($nickName)) { $aList[JSON_TAG_STATUS] = 2; return $aList;}            
            
            $rowUser           =    $this->checkUserId($db,$userId);
            if(empty($rowUser)){
                $aList[JSON_TAG_STATUS] = 3; return $aList;
            } 
            $rowUsergateway = $this->checkUserGateway($db,$userId,$gatewayId);
                if(!empty($rowUsergateway)){
                    $sQuery = " UPDATE user_gateways "
                            . " SET updated_on=now()"
                            . " WHERE user_id =:user_id AND gateway_id =:gateway_id AND is_deleted=0 ";
                    $db->query($sQuery);
                    $db->bind(':user_id',$userId);
                    $db->bind(':gateway_id',$gatewayId);      
                    $db->execute();                                
            }
            else{
                $query = "INSERT INTO user_gateways(user_id, gateway_id, gateway_mac_id, added_on, updated_on) "
                        . "VALUES (:user_id, :gateway_id, :gateway_id, NOW(), NOW())";
                $db->query($query);
                $db->bind(':user_id',$userId);
                $db->bind(':gateway_id',$gatewayId);      
                $db->execute();                
            }
            $rowUserDevice = $this->checkUserDevice($db,$userId,$gatewayId,$deviceMacAddress);
//            print_r($rowUserDevice);
//            exit();
            if(!empty($rowUserDevice)){
                    $sQuery = " UPDATE gateway_devices "
                            . " SET updated_on=now(),nick_name=:nick_name,coin_location=:coin_location"
                            . " WHERE user_id =:user_id AND gateway_id =:gateway_id AND device_mac_address =:device_mac_address AND is_deleted=0 ";
                    $db->query($sQuery);
                    $db->bind(':device_mac_address',$deviceMacAddress);
                    $db->bind(':user_id',$userId);
                    $db->bind(':gateway_id',$gatewayId); 
                    $db->bind(':nick_name',$nickName); 
                    $db->bind(':coin_location',$coin_location);                    
                    $db->execute();                                
            }
            else{
                $query = "INSERT INTO gateway_devices(user_id, gateway_id, device_mac_address, nick_name, added_on, updated_on,coin_location) "
                        . "VALUES (:user_id, :gateway_id, :device_mac_address, :nick_name, NOW(), NOW(),:coin_location)";
//                print_r($query);
//                exit();
                $db->query($query);
                $db->bind(':device_mac_address',$deviceMacAddress);
                $db->bind(':user_id',$userId);
                $db->bind(':gateway_id',$gatewayId);  
                $db->bind(':nick_name',$nickName); 
                $db->bind(':coin_location',$coin_location);               
                $db->execute();                
            }
            
            //Get user & device details
            $rowUserDevice = $this->checkUserDevice($db,$userId,$gatewayId,$deviceMacAddress);
              
            $aList[JSON_TAG_RESULT]  = $rowUserDevice;    
            
            $aList[JSON_TAG_STATUS]  = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;    
    }
        
    //check user id & gateway id & device id exists
    function checkUserDevice($db,$userId,$gatewayId,$deviceMacAddress){
        
        $sQuery = " SELECT * "
                . " FROM gateway_devices "
                . " WHERE user_id =:user_id AND gateway_id =:gateway_id AND device_mac_address =:device_mac_address AND is_deleted=0 ";
        $db->query($sQuery);
        $db->bind(':device_mac_address',$deviceMacAddress);
        $db->bind(':user_id',$userId);
        $db->bind(':gateway_id',$gatewayId);

        return $row = $db->single();        
    }
   
     /*
      Function            : getDevices($gatewayId,$userId)
      Brief               : Function to Get Devices
      Details             : Function to Get Devices
      Input param         : $gatewayId,$userId
      Input/output param  : Nil
      Return              : Returns array.
     */
    public function getDevices($gatewayId,$userId){
        
        $db = new ConnectionManager();
        $generalMethod = new GeneralMethod(); 

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
            
                $rowUser           =    $this->checkUserId($db,$userId);
                if(empty($rowUser)){
                    $aList[JSON_TAG_STATUS] = 3; return $aList;
                } 
            
                $sQuery = " SELECT * "
                        . " FROM gateway_devices "
                        . " WHERE user_id =:user_id AND gateway_id =:gateway_id AND is_deleted=0 ORDER BY gd_id DESC ";
                $db->query($sQuery);
                $db->bind(':user_id',$userId); 
                $db->bind(':gateway_id',$gatewayId);

                $rowUserDevices  =   $db->resultSet();             
                          
                $aList[JSON_TAG_RESULT]  = $rowUserDevices;    

                $aList[JSON_TAG_STATUS]  = 0;         
        } catch (Exception $e) {
            $aList[JSON_TAG_STATUS] = 1;
        }   
        return $aList;    
    }
            
    
//    
}                           