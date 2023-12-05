<?php
/*
  Project                     : Sensegiz
  Module                      : App Console --  Model Class
  File name                   : AppModel.php
  Description                 : Model class for App related activities
 */

class AppModel {

           
     /*
      Function            : responseResult($errorCode,$errorDesc)
      Brief               : Function for result response for failure
      Details             : Function for result response for failure with error code and description
      Input param         : $errorCode,$errorDesc
      Input/output param  : $aResult
      Return              : Returns array.
     */    
    public function responseResult($errorCode,$errorDesc){
        $aResult = array(JSON_TAG_STATUS => JSON_TAG_ERROR,
                  JSON_TAG_DESC => $errorDesc);
        $this->httpStatus(ERRCODE_CLIENT_REFUSED);   
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
    public function httpStatus($statusCode){
        $instance = \Slim\Slim::getInstance();    
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
                JSON_TAG_DESC => SERVER_EXCEPTION);
        $instance = \Slim\Slim::getInstance();    
        $instance->status(ERRCODE_SERVER);
        return $aResult;
    }
    
     /*
      Function            : loginUser($inData)
      Brief               : Function to Login User
      Details             : Function to Login User
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function loginUser($inData){
        
        $appManager = new AppManager();
        $aResult = array();
        $aList   = array();
        $aList   = $appManager->loginUser($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Wrong email id/password
                $aResult = $this->responseResult(ERRCODE_ERROR,WRONG_DATA);
            }
            
        return $aResult;
    }   
    
    
     /*
      Function            : forgotPassword($inData)
      Brief               : Function to send reset password link
      Details             : Function to send reset password link
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function forgotPassword($inData){
        
        $appManager = new AppManager();
        $aResult = array();
        $aList   = array();
        $aList   = $appManager->forgotPassword($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Email Id not exists
                $aResult = $this->responseResult(ERRCODE_ERROR,EMAIL_ID_NOT_EXISTS);
            } 
            
        return $aResult;
    }   

     /*
      Function            : changePassword($inData)
      Brief               : Function to change password
      Details             : Function to change password
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function changePassword($inData,$userId){
        
        $appManager = new AppManager();
        $aResult = array();
        $aList   = array();
        $aList   = $appManager->changePassword($inData,$userId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Invalid User Id
                $aResult = $this->responseResult(ERRCODE_ERROR,ERROR_INVALID_USER_ID);
            } elseif ($aList[JSON_TAG_STATUS] == 4) { //Wrong current password
                $aResult = $this->responseResult(ERRCODE_ERROR,WRONG_CURRENT_PASSWORD);
            } 
            
        return $aResult;
    }   
    
     /*
      Function            : addGateways($inData)
      Brief               : Function to Add Gateways
      Details             : Function to Add Gateways
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function addGateways($inData){
        
        $appManager = new AppManager();
        $aResult = array();
        $aList   = array();
        $aList   = $appManager->addGateways($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Invalid User Id
                $aResult = $this->responseResult(ERRCODE_ERROR,ERROR_INVALID_USER_ID);
            }
            
        return $aResult;
    }
    
     /*
      Function            : getGateways($userId)
      Brief               : Function to Get Gateways
      Details             : Function to Get Gateways
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getGateways($userId){
        
        $appManager = new AppManager();
        $aResult = array();
        $aList   = array();
        $aList   = $appManager->getGateways($userId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Invalid User Id
                $aResult = $this->responseResult(ERRCODE_ERROR,ERROR_INVALID_USER_ID);
            }
            
        return $aResult;
    }     
  
     /*
      Function            : addDevices($inData)
      Brief               : Function to Add Devices
      Details             : Function to Add Devices
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function addDevices($inData){
        
        $appManager = new AppManager();
        $aResult = array();
        $aList   = array();
        $aList   = $appManager->addDevices($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Invalid User Id
                $aResult = $this->responseResult(ERRCODE_ERROR,ERROR_INVALID_USER_ID);
            }
            
        return $aResult;
    }    
    
     /*
      Function            : getDevices($gatewayId,$userId)
      Brief               : Function to Get Devices
      Details             : Function to Get Devices
      Input param         : $gatewayId,$userId
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getDevices($gatewayId,$userId){
        
        $appManager = new AppManager();
        $aResult = array();
        $aList   = array();
        $aList   = $appManager->getDevices($gatewayId,$userId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Invalid User Id
                $aResult = $this->responseResult(ERRCODE_ERROR,ERROR_INVALID_USER_ID);
            }
            
        return $aResult;
    }     
      
    
}
?>