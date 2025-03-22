<?php
/*
  Project                     : Sensegiz
  Module                      : Web Console --  Model Class
  File name                   : AdminModel.php
  Description                 : Controller class for Admin related activities
 */

class AdminModel{
    
 
     /*
      Function            : responseResult($errorCode,$errorDesc)
      Brief               : Function for result response for failure
      Details             : Function for result response for failure with error code and description
      Input param         : $errorCode,$errorDesc
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function responseResult($errorCode,$errorDesc){
        $aResult = array(JSON_TAG_STATUS => JSON_TAG_FAIL,
//                  JSON_TAG_CODE => $errorCode,
                  JSON_TAG_DESC => $errorDesc);
        $this->httpStatus(ERRCODE_CLIENT_REFUSED,$errorCode);   
        return $aResult;
    }
    
     /*
      Function            : responseResultCustErrCode($custCode,$errorCode,$errorDesc)
      Brief               : Function for result response for failure with custom code 
      Details             : Function for result response for failure with error code and description
      Input param         : $custCode,$errorCode,$errorDesc
      Input/output param  : $aResult
      Return              : Returns array.
     */     
    public function responseResultCustErrCode($custCode,$errorCode,$errorDesc){
        $aResult = array(JSON_TAG_STATUS => JSON_TAG_FAIL,
                  JSON_TAG_CODE => $errorCode,
                  JSON_TAG_DESC => $errorDesc);
        $this->httpStatus($custCode,$errorCode);   
        return $aResult;
    }
    
     /*
      Function            : httpStatus($statusCode,$customCode)
      Brief               : Function to set the header 
      Details             : Function to set the header
      Input param         : $statusCode,$customCode
      Input/output param  : $aResult
      Return              : Returns array.
     */    
    public function httpStatus($statusCode,$customCode){
        $instance = \Slim\Slim::getInstance();    
        $instance->response()->header(CUSTOM_ERROR_CODE,$customCode);
        $instance->status($statusCode);
    }
    
     /*
      Function            : httpStatusServer()
      Brief               : Function to set the header for server error
      Details             : Function to set the header for server error
      Input param         : Nil
      Input/output param  : $aResult
      Return              : Returns array.
     */    
    public function httpStatusServer(){
        $aResult = array(JSON_TAG_STATUS => JSON_TAG_ERROR,
//                 JSON_TAG_CODE => ERRCODE_SERVER_EXCEPTION,
                 JSON_TAG_DESC => SERVER_EXCEPTION);
        $instance = \Slim\Slim::getInstance();    
        $instance->status(ERRCODE_SERVER);
        return $aResult;
    }

     /*
      Function            : checkAdminCredential($loginId,$password)
      Brief               : Function used to basic authentication with $loginId and password
      Details             : Function used to basic authentication with $loginId and password
      Input param         : loginId and password
      Input/output param  : $resultSet
      Return              : Returns bool.
     */   
    
    public function checkAdminCredential($loginId,$password){
        $adminManager = new AdminManager();   
        $aList = $adminManager->checkAdminCredential($loginId,$password);
            if ($aList[JSON_TAG_STATUS] == 0) { $resultSet =TRUE;  }
            else { $resultSet =FALSE;  }
        return $resultSet;
    }
    
    /*
      Function            : login($inData)
      Brief               : Function to login.   
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function login($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->login($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Authentication Failed 
                $aResult = $this->responseResult(ERRCODE_ERROR,WRONG_DATA);     
            }
        return $aResult;
    }
       
    /*
      Function            : sendInvite($inData)
      Brief               : Function to Send invite to User.      
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function sendInvite($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->sendInvite($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //User email already exists
                $aResult = $this->responseResult(ERRCODE_ERROR,EMAIL_EXISTS);     
            }
        return $aResult;
    }
    
      /*
      Function            : getInvitedUsers()
      Brief               : Function to get all Invited Users          
      Input param         : Nil
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getInvitedUsers(){
        $adminManager = new AdminManager();
        $aResult = array();
        $aList = array();
        $aList = $adminManager->getInvitedUsers();
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }     
       
    /*
      Function            : userCreateNewPassword($inData)
      Brief               : Function to create new USER password      
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function userCreateNewPassword($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->userCreateNewPassword($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Verification failed
                $aResult = $this->responseResult(ERRCODE_ERROR,VERIFICATION_FAIL);     
            } elseif ($aList[JSON_TAG_STATUS] == 4) { //User Password created already
                $aResult = $this->responseResult(ERRCODE_ERROR,PASSWORD_EXISTS);     
            }
        return $aResult;
    }
        
      /*
      Function            : getAdminGateways()
      Brief               : Function to get all gateways for admin           
      Input param         : Nil
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getAdminGateways($coinmacID){
        $adminManager = new AdminManager();
        $aResult = array();
        $aList = array();
        $aList = $adminManager->getAdminGateways($coinmacID);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }
    
    /*
      Function            : blacklistGateways($inData)
      Brief               : Function to Blacklist Gateways      
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function blacklistGateways($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->blacklistGateways($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Invalid Gateway
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_GATEWAY_ID);     
            }
        return $aResult;
    }
    
      /*
      Function            : getAdminDevices($gatewayId)
      Brief               : Function to get devices for admin            
      Input param         : Nil
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getAdminDevices($gatewayId){
        $adminManager = new AdminManager();
        $aResult = array();
        $aList = array();
        $aList = $adminManager->getAdminDevices($gatewayId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    } 
    
    /*
      Function            : blacklistDevices($inData)
      Brief               : Function to Blacklist Devices    
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function blacklistDevices($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->blacklistDevices($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Invalid Device Id
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_DEVICE_ID);     
            }
        return $aResult;
    }    
    
    /*
      Function            : removeGateways($inData)
      Brief               : Function to Remove Gateways       
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function removeGateways($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->removeGateways($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Invalid Gateway
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_GATEWAY_ID);     
            }
        return $aResult;
    }
           
    /*
      Function            : removeDevices($inData)
      Brief               : Function to Remove Devices  
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function removeDevices($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->removeDevices($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Invalid Device Id
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_DEVICE_ID);     
            }
        return $aResult;
    }    

/*
      Function            : hardDeleteGateways($inData)
      Brief               : Function to hard delete Gateways       
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function hardDeleteGateways($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->hardDeleteGateways($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Invalid Gateway
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_GATEWAY_ID);     
            } elseif ($aList[JSON_TAG_STATUS] == 4) { //Invalid OTP  
		$aResult = array(JSON_TAG_STATUS => JSON_TAG_INVALID_OTP,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
   
            }

        return $aResult;
    }
           
    /*
      Function            : hardDeleteDevices($inData)
      Brief               : Function to hard delete Devices  
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function hardDeleteDevices($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->hardDeleteDevices($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Invalid Device Id
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_DEVICE_ID);     
            } elseif ($aList[JSON_TAG_STATUS] == 4) { //Invalid OTP  
		$aResult = array(JSON_TAG_STATUS => JSON_TAG_INVALID_OTP,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
   
            }

        return $aResult;
    }       

public function addUser($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->addUser($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //User email already exists
                $aResult = $this->responseResult(ERRCODE_ERROR,EMAIL_EXISTS);     
            }
        return $aResult;
    }

public function sendOTP($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();

        $aList = $adminManager->sendOTP($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Invalid Gateway
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_GATEWAY_ID);     
            }
        return $aResult;
}


public function restoreGateway($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->restoreGateway($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } 

        return $aResult;
}
           
  


public function restoreDevice($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->restoreDevice($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } 

        return $aResult;
} 

public function updateTimeFactor($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();

        $aList = $adminManager->updateTimeFactor($inData);

            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Invalid Gateway
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_GATEWAY_ID);     
            }
        return $aResult;
    }


    public function getGatewayDetail($gatewayId){
        $adminManager = new AdminManager();
        $aResult = array();
        $aList = array();
        $aList = $adminManager->getGatewayDetail($gatewayId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    } 


    public function getCoinDetail($coinMacAddr){
        $adminManager = new AdminManager();
        $aResult = array();
        $aList = array();
        $aList = $adminManager->getCoinDetail($coinMacAddr);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    } 
    

    public function updateGatewayIdRecover($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->updateGatewayIdRecover($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //User email already exists
                $aResult = $this->responseResult(ERRCODE_ERROR,EMAIL_EXISTS);     
            }
        return $aResult;
    }


    /*
      Function            : softDelDeviceToFrom($inData)
      Brief               : Function to Soft Delete the Data between two date of a Device (It will set the 'is_deleted = 3')  
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function softDelDeviceToFrom($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->softDelDeviceToFrom($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Invalid Device Id
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_DEVICE_ID);     
            } elseif ($aList[JSON_TAG_STATUS] == 4) { //Invalid OTP  
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_INVALID_OTP,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
   
            }

        return $aResult;
    }   


    public function updateCoinDeviceMacAddr($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->updateCoinDeviceMacAddr($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //User email already exists
                $aResult = $this->responseResult(ERRCODE_ERROR,EMAIL_EXISTS);     
            }
        return $aResult;
    }


    public function setCoinTypeSize($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->setCoinTypeSize($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //User email already exists
                $aResult = $this->responseResult(ERRCODE_ERROR,EMAIL_EXISTS);     
            }
        return $aResult;
    }


    public function eraseGatewayData($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->eraseGatewayData($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } 
        return $aResult;
    }
    
    
    public function setSensorTypeRequest($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->setSensorTypeRequest($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } 
        return $aResult;
    }

   
       
    
    public function updateGatewayUser($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->updateGatewayUser($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //User email already exists
                $aResult = $this->responseResult(ERRCODE_ERROR,EMAIL_EXISTS);     
            }
        return $aResult;
    }
    public function checkGatewayUser($uid){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList = $adminManager->checkGatewayUser($uid);
        
        if ($aList[JSON_TAG_STATUS] == 0) {
            $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS, JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
        }
        elseif ($aList[JSON_TAG_STATUS] == 1) {
            $aResult = $this->httpStatusServer();
        } 

        return $aResult;
    } 





    public function deleteGatewayUser($uid){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList        = $adminManager->deleteGatewayUser($uid);
        
        if ($aList[JSON_TAG_STATUS] == 0) {
            $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS);
        }
        elseif ($aList[JSON_TAG_STATUS] == 1) {
            $aResult = $this->httpStatusServer();
        } 

        return $aResult;
    } 

    public function  updateGatewayUserN($inData){
        $adminManager  = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList        = $adminManager-> updateGatewayUserN($inData);
        
        if ($aList[JSON_TAG_STATUS] == 0) {
            
            $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS, JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
        }
        elseif ($aList[JSON_TAG_STATUS] == 1) {
            $aResult = $this->httpStatusServer();
        } 
        elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
            $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
        }
        
        return $aResult;
    } 

    public function UpdateUser_email($inData){
        $adminManager = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList        = $adminManager->UpdateUser_email($inData);
        if ($aList[JSON_TAG_STATUS] == 0) {
            $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS, JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
        }
        elseif ($aList[JSON_TAG_STATUS] == 1) {
            $aResult = $this->httpStatusServer();
        }
        elseif ($aList[JSON_TAG_STATUS] == 2) { 
            $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
        } 
        

        return $aResult;
    } 

    public function  updateBuzzerStatus($inData){
        $adminManager  = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList        = $adminManager-> updateBuzzerStatus($inData);
        
        if ($aList[JSON_TAG_STATUS] == 0) {
            
            $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS, JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
        }
        elseif ($aList[JSON_TAG_STATUS] == 1) {
            $aResult = $this->httpStatusServer();
        } 
        elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
            $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
        }
        
        return $aResult;
    } 

    public function  updateCoinOfflineTime($inData){
        $adminManager  = new AdminManager();
        $aResult      = array();
        $aList        = array();
        $aList        = $adminManager-> updateCoinOfflineTime($inData);
        
        if ($aList[JSON_TAG_STATUS] == 0) {
            
            $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS, JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
        }
        elseif ($aList[JSON_TAG_STATUS] == 1) {
            $aResult = $this->httpStatusServer();
        } 
        elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
            $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);    
        }
        
        return $aResult;
    } 
    
//    
}