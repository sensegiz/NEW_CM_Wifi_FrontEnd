<?php 
/*
  Project                     : Sensegiz
  Module                      : Web Console --  Controller Class
  File name                   : AdminController.php       
  Description                 : Controller class for Admin related activities
 */
class AdminController {
//// starts
    /*
      Function            : paramMissing($instance)
      Brief               : Function for Missing Param
      Details             : Function for Missing Param
      Input param         : $instance     
      Return              : Returns array.
     */        
    public function paramMissing($instance){
        $result = array(JSON_TAG_STATUS => JSON_TAG_ERROR,
                        JSON_TAG_CODE => ERRCODE_INSUFFICIENT_PARAM,
                        JSON_TAG_DESC => INSUFFICIENT_PARAM);   
        $instance->response()->header(CUSTOM_ERROR_CODE, ERRCODE_INSUFFICIENT_PARAM);
        $instance->status(ERRCODE_CLIENT);
        return $result;
    }
        
    /*
      Function            : checkAdminCredential($loginId,$password)
      Brief               : Function used to basic authentication with $loginId and password
      Details             : Function used to basic authentication with $loginId and password
      Input param         : loginId and password     
      Return              : Returns bool.
     */    
    public function checkAdminCredential($loginId,$password){
        $adminModel = new AdminModel();
        $aResult = $adminModel->checkAdminCredential($loginId,$password);
        return $aResult; 
    }
        
      /*
      Function            : login()
      Brief               : Function to login.      
      Input param         : Nil    
      Return              : Returns array.
     */
    public function login(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
//        print_r($inData);exit();
        if (is_array($inData) && array_key_exists(JSON_TAG_EMAIL,$inData)
                && array_key_exists(JSON_TAG_PASSWORD, $inData)
                && array_key_exists(JSON_TAG_LOGIN_TYPE, $inData)) {
                $result = $adminModel->login($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }
        $genMethod->generateResult($result); 
    }
     
      /*
      Function            : sendInvite()
      Brief               : Function to Send invite to User.    
      Input param         : Nil    
      Return              : Returns array.
     */
    public function sendInvite(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
//        print_r($inData);exit();
        if (is_array($inData) && array_key_exists(JSON_TAG_USER_EMAIL,$inData)
                && array_key_exists(JSON_TAG_ADMIN_ID, $inData)) {
                $result = $adminModel->sendInvite($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }
        $genMethod->generateResult($result); 
    }
        
     /*
      Function            : getInvitedUsers()
      Brief               : Function to get all Invited Users    
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getInvitedUsers(){
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $result = $adminModel->getInvitedUsers();
        $genMethod->generateResult($result);   
    }          
    
      /*
      Function            : userCreateNewPassword()
      Brief               : Function to create new USER password.    
      Input param         : Nil    
      Return              : Returns array.
     */
    public function userCreateNewPassword(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
//        print_r($inData);exit();
        if (is_array($inData) && array_key_exists(JSON_TAG_PASSWORD,$inData)
                ) {
                $result = $adminModel->userCreateNewPassword($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }
        $genMethod->generateResult($result); 
    }
           
     /*
      Function            : getAdminGateways()
      Brief               : Function to get all gateways for admin   
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getAdminGateways($coinmacID){
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $result = $adminModel->getAdminGateways($coinmacID);
        $genMethod->generateResult($result);   
    } 

      /*
      Function            : blacklistGateways()
      Brief               : Function to Blacklist Gateways 
      Input param         : Nil    
      Return              : Returns array.
     */
    public function blacklistGateways(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
//        print_r($inData);exit();
        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID,$inData)
                && array_key_exists(JSON_TAG_BLACKLIST_STATUS,$inData) ) {
            $result = $adminModel->blacklistGateways($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }
        $genMethod->generateResult($result); 
    }

     /*
      Function            : getAdminDevices($gatewayId)
      Brief               : Function to get devices for admin   
      Input param         : $gatewayId
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getAdminDevices($gatewayId){
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $result = $adminModel->getAdminDevices($gatewayId);
        $genMethod->generateResult($result);   
    } 

      /*
      Function            : blacklistDevices()
      Brief               : Function to Blacklist Devices 
      Input param         : Nil    
      Return              : Returns array.
     */
    public function blacklistDevices(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_DEVICE_ID,$inData)
                && array_key_exists(JSON_TAG_BLACKLIST_STATUS,$inData)
                && array_key_exists(JSON_TAG_GATEWAY_ID,$inData)  ) {
            $result = $adminModel->blacklistDevices($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }
        $genMethod->generateResult($result); 
    }
    
      /*
      Function            : removeGateways()
      Brief               : Function to Remove Gateways 
      Input param         : Nil    
      Return              : Returns array.
     */
    public function removeGateways(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID,$inData)
                ) {
            $result = $adminModel->removeGateways($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }
        $genMethod->generateResult($result); 
    }

      /*
      Function            : removeDevices()
      Brief               : Function to Remove Devices 
      Input param         : Nil    
      Return              : Returns array.
     */
    public function removeDevices(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_DEVICE_ID,$inData)
                && array_key_exists(JSON_TAG_GATEWAY_ID,$inData)  ) {
            $result = $adminModel->removeDevices($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }
        $genMethod->generateResult($result); 
    }


 /*
      Function            : hardDeleteGateways()
      Brief               : Function to Remove Gateways 
      Input param         : Nil    
      Return              : Returns array.
     */
    public function hardDeleteGateways(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID,$inData)
                ) {
            $result = $adminModel->hardDeleteGateways($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }
        $genMethod->generateResult($result); 
    }

      /*
      Function            : hardDeleteDevices()
      Brief               : Function to Remove Devices 
      Input param         : Nil    
      Return              : Returns array.
     */
    public function hardDeleteDevices(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_DEVICE_ID,$inData)
                && array_key_exists(JSON_TAG_GATEWAY_ID,$inData)  ) {
            $result = $adminModel->hardDeleteDevices($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }
        $genMethod->generateResult($result); 
    }

public function addUser(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData)) {
                $result = $adminModel->addUser($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }

        $genMethod->generateResult($result); 

}

public function sendOTP(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();

        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID,$inData)
                ) {
            $result = $adminModel->sendOTP($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }
        $genMethod->generateResult($result); 
    }


public function restoreGateway(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();

        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID,$inData)
                ) {
            $result = $adminModel->restoreGateway($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }
        $genMethod->generateResult($result); 
    }


public function restoreDevice(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();

        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID,$inData)
                ) {
            $result = $adminModel->restoreDevice($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }
        $genMethod->generateResult($result); 
    }

    public function updateTimeFactor(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();

        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID,$inData)) {
            $result = $adminModel->updateTimeFactor($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }
        $genMethod->generateResult($result); 
    }


    /*
     Function            : getGatewayDetail()
     Brief               : Function to get Gateway detail 
     Input param         : Nil    
     Return              : Returns array.
    */
    public function getGatewayDetail($gatewayId){
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $result = $adminModel->getGatewayDetail($gatewayId);
        $genMethod->generateResult($result); 
    }


    public function getCoinDetail($coinMacAddr){
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $result = $adminModel->getCoinDetail($coinMacAddr);
        $genMethod->generateResult($result); 
    }


    /*
      Function            : updateGatewayIdRecover()
      Brief               : Function to Update/Change the Gateway ID (Gateway ID Recovery Strategy)
      Input param         : Nil    
      Return              : Returns array.
     */
    public function updateGatewayIdRecover(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_OLG_GATEWAY_ID,$inData) && array_key_exists(JSON_TAG_NEW_GATEWAY_ID,$inData) ) {
            $result = $adminModel->updateGatewayIdRecover($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }

        $genMethod->generateResult($result); 

    }


    /*
      Function            : softDelDeviceToFrom()
      Brief               : Function to Soft Delete the Data between two date of a Device (It will set the 'is_deleted = 3')
      Input param         : Nil    
      Return              : Returns array.
     */
    public function softDelDeviceToFrom(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_DEVICE_ID,$inData) && array_key_exists(JSON_TAG_GATEWAY_ID,$inData) && array_key_exists(JSON_TAG_SENSOR_TYPE,$inData) && array_key_exists(JSON_TAG_FROM_DATE,$inData) && array_key_exists(JSON_TAG_FROM_DATE,$inData)) {
            $result = $adminModel->softDelDeviceToFrom($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }
        $genMethod->generateResult($result); 
    }

    
    public function updateCoinDeviceMacAddr(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_OLD_COINDEVICE_MAC,$inData) && array_key_exists(JSON_TAG_NEW_COINDEVICE_MAC,$inData) ) {
            $result = $adminModel->updateCoinDeviceMacAddr($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }

        $genMethod->generateResult($result); 

    }

    
    public function setCoinTypeSize(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID,$inData) && array_key_exists(JSON_TAG_DEVICE_MAC_ADDRESS,$inData) ) {
            $result = $adminModel->setCoinTypeSize($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }

        $genMethod->generateResult($result); 

    }

    
    public function eraseGatewayData(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID,$inData)) {
            $result = $adminModel->eraseGatewayData($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }

        $genMethod->generateResult($result); 

    }


    public function setSensorTypeRequest(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID,$inData)) {
            $result = $adminModel->setSensorTypeRequest($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }

        $genMethod->generateResult($result); 

    }
    
    /*
      Function            : updateGatewayUser()
      Brief               : Function to Update gateway user
      Input param         : Nil    
      Return              : Returns array.
    */
    public function updateGatewayUser(){

        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData)) {
            $result = $adminModel->updateGatewayUser($inData);
        } 
        else { 
            $result = $this->paramMissing($instance);             
        }

        $genMethod->generateResult($result); 

    }

    public function checkGatewayUser($uid){
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $result = $adminModel->checkGatewayUser($uid);
        $genMethod->generateResult($result); 
    }





    public function deleteGatewayUser($uid){
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $result = $adminModel->deleteGatewayUser($uid);
        $genMethod->generateResult($result); 
    }


    public function updateGatewayUserN(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
       if (is_array($inData)) {
            $result = $adminModel->updateGatewayUserN($inData);
       } 
       else { 
           $result = $this->paramMissing($instance);             
       }

        $genMethod->generateResult($result); 

    }



    public function UpdateUser_email(){
  
        $adminModel = new AdminModel();
        $genMethod  = new GeneralMethod();
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
       if (is_array($inData)) {
            $result = $adminModel->UpdateUser_email($inData);
       } 
       else { 
           $result = $this->paramMissing($instance);             
       }

        $genMethod->generateResult($result); 

    }


//  
}