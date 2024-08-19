<?php
/*
  Project                     : Sensegiz
  Module                      : Web Console --  Model Class
  File name                   : UserModel.php
  Description                 : Controller class for User/Gateway related activities
 */

class UserModel{
    
 
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
      Function            : getGateways()
      Brief               : Function to get all Gateways      
      Input param         : Nil
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getGateways($userId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getGateways($userId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }     
       
      /*
      Function            : getDevices($gatewayId, $coins, $sensors)
      Brief               : Function to get devices connected to the gateway     
      Input param         : Nil
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getDevices($gatewayId, $coins, $sensors){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getDevices($gatewayId, $coins, $sensors);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }
    
      /*
      Function            : getDeviceValue($gatewayId,$deviceId)
      Brief               : Function to get each device value    
      Input param         : Nil
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getDeviceValue($gatewayId,$deviceId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getDeviceValue($gatewayId,$deviceId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }    
    
     /*
      Function            : setDeviceThreshold($inData)
      Brief               : Function to set Threshold
      Details             : Function to set Threshold
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function setDeviceThreshold($inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->setDeviceThreshold($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //invalid Gateway
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_GATEWAY_ID);
            } elseif ($aList[JSON_TAG_STATUS] == 4) { //Device is not associated with the Gateway
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_DEVICE_GATEWAY_ID);
            } elseif ($aList[JSON_TAG_STATUS] == 5) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_PENDING_REQUEST,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            }

            
        return $aResult;
    }   
    
    public function xyzCombinationChange($inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->xyzCombinationChange($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 5) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_PENDING_REQUEST,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            }


            
        return $aResult;
    }


/*
      Function            : setStream($inData)
      Brief               : Function to set Stream
      Details             : Function to set Stream
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function setStream($inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->setStream($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //invalid Gateway
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_GATEWAY_ID);
            } elseif ($aList[JSON_TAG_STATUS] == 4) { //Device is not associated with the Gateway
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_DEVICE_GATEWAY_ID);
            } elseif ($aList[JSON_TAG_STATUS] == 5) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_PENDING_REQUEST,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            }

            
        return $aResult;
    }
        
     /*
      Function            : getCurrentValue($inData)
      Brief               : Function to Current Value
      Details             : Function to Current Value
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getCurrentValue($inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->getCurrentValue($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //invalid Gateway
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_GATEWAY_ID);
            } elseif ($aList[JSON_TAG_STATUS] == 4) { //Device is not associated with the Gateway
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_DEVICE_GATEWAY_ID);
            } elseif ($aList[JSON_TAG_STATUS] == 5) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_PENDING_REQUEST,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            }
            
        return $aResult;
    }    
    
      /*
      Function            : getGatewaySettings($userId)
      Brief               : Function to get Gateways Settings      
      Input param         : $userId
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getGatewaySettings($userId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getGatewaySettings($userId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    } 

     /*
      Function            : updateSmsNotification($inData)
      Brief               : Function to Update SMS Notification
      Details             : Function to Update SMS Notification
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function updateSmsNotification($inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->updateSmsNotification($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            }
            
        return $aResult;
    }    
     
     /*
      Function            : updateEmailNotification($inData)
      Brief               : Function to Update EMAIL Notification
      Details             : Function to Update EMAIL Notification
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function updateEmailNotification($inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->updateEmailNotification($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            }
            
        return $aResult;
    }

      /*
      Function            : getNotificationEmailIds($userId)
      Brief               : Function to get user notification emails      
      Input param         : $userId
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getNotificationEmailIds($userId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getNotificationEmailIds($userId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

         /*
      Function            : addNotificationEmailIds($userId,$inData)
      Brief               : Function to Add Email ids
      Details             : Function to Add Email ids
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function addNotificationEmailIds($userId,$inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->addNotificationEmailIds($userId,$inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Email already added
                $aResult = $this->responseResult(ERRCODE_ERROR,EMAIL_EXISTS);
            }
            
        return $aResult;
    }
    
      /*
      Function            : deleteNotificationEmailIds($userId,$Id)
      Brief               : Function to delete notification emails   
      Input param         : Nil
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function deleteNotificationEmailIds($userId,$Id){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->deleteNotificationEmailIds($userId,$Id);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }


      /*
      Function            : getNotificationPhone($userId)
      Brief               : Function to get user notification phone      
      Input param         : $userId
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getNotificationPhone($userId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getNotificationPhone($userId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

         /*
      Function            : addNotificationPhone($userId,$inData)
      Brief               : Function to Add Phone
      Details             : Function to Add Phone
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function addNotificationPhone($userId,$inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->addNotificationPhone($userId,$inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Phone already exists
                $aResult = $this->responseResult(ERRCODE_ERROR,PHONE_EXISTS);
            }
            
        return $aResult;
    }
    
      /*
      Function            : deleteNotificationPhone($userId,$Id)
      Brief               : Function to delete notification phone 
      Input param         : Nil
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function deleteNotificationPhone($userId,$Id){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->deleteNotificationPhone($userId,$Id);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }
/*
      Function            : createLocation($userId,$inData)
      Brief               : Function to Create User Location
      Details             : Function to Create User Location
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function createLocation($userId,$inData,$lat,$long){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->createLocation($userId,$inData,$lat,$long);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Location already added
                $aResult = $this->responseResult(ERRCODE_ERROR,LOCATION_EXISTS);
	    } elseif ($aList[JSON_TAG_STATUS] == 4) {
		$aResult = $this->responseResult(ERRCODE_ERROR, GATEWAY_EXISTS);
            } elseif ($aList[JSON_TAG_STATUS] == 5) {		
		$aResult = array(JSON_TAG_STATUS => JSON_TAG_NO_COIN_GATEWAY,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            }

            
        return $aResult;
    }

      /*
      Function            : deleteUserLocation($userId,$Id)
      Brief               : Function to delete User Location
      Input param         : Nil
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function deleteUserLocation($userId,$Id){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->deleteUserLocation($userId,$Id);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

/*
      Function            : addCoin($userId,$inData)
      Brief               : Function to add Coin to map
      Details             : Function to add Coin to map
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function addCoin($userId,$inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->addCoin($userId,$inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } else {
                $aResult = $this->httpStatusServer();
            
            }
            
        return $aResult;
    }

      /*
      Function            : getCoin($userId)
      Brief               : Function to get user Coins      
      Input param         : $userId
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getCoin($gatewayId, $userId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getCoin($gatewayId, $userId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

      /*
      Function            : getCoin($userId)
      Brief               : Function to get Coins associated with gateway assigned to gateway to add on the Map
      Input param         : $userId
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function addGetCoin($gatewayId, $userId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->addGetCoin($gatewayId, $userId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

    /*
    Function        : getLocation()
    Brief           : Function to get a list of User Locations
    Input param     : $userId
    Input/Output param  : $array
    return          : Returns array.
    */

        public function getLocation($userId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getLocation($userId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

    /*
    Function        : getGatewayLocation()
    Brief           : Function to get a list of gateways assigned to User Locations
    Input param     : $userId
    Input/Output param  : $array
    return          : Returns array.
    */

        public function getGatewayLocation($userId, $locationId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getGatewayLocation($userId, $locationId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
            return $aResult;
    }

    public function getUserLocationLatLong($userId, $locationId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getUserLocationLatLong($userId, $locationId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

    /* Function         : renderAlert(gwId, devId)
       Brief            : Function to get coin latitude and longitude to render alerts on map
       Input param          : $gatewayId, $coinId
       Input/ Output param      : Array
       Return           : Returns Array.
    */

        public function renderAlert($userId, $gwId, $devId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->renderAlert($userId, $gwId, $devId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
            return $aResult;
    }

     /*
      Function            : getHelpDevices($gatewayId)
      Brief               : Function to get help gateway and coin data
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getHelpDevices($gatewayId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getHelpDevices($gatewayId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

     /*
      Function            : getHelpDeviceSensor($gatewayId, $deviceId)
      Brief               : Function to get help sensor data of a specific coin
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getHelpDeviceSensor($gatewayId, $deviceId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getHelpDeviceSensor($gatewayId, $deviceId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

/*
      Function            : analyticsGatewayDevices()
      Brief               : Function to get coins registered to a gateway
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */

    public function analyticsGatewayDevices($gatewayId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->analyticsGatewayDevices($gatewayId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

     /*
      Function            : analyticsDeviceSensor($gatewayId, $deviceId)
      Brief               : Function to get help sensor data of a specific coin
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function analyticsDeviceSensor($gatewayId, $deviceId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->analyticsDeviceSensor($gatewayId, $deviceId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }


     /*
      Function            : analyticsDeviceSensorData($gatewayId, $deviceId, $deviceType1, $deviceType2)
      Brief               : Function to get data of a specific coin sensor
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function analyticsDeviceSensorData($gatewayId, $deviceId, $deviceType1, $deviceType2){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->analyticsDeviceSensorData($gatewayId, $deviceId, $deviceType1, $deviceType2);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }


/*
  Function            : analyticsFilteredChartData($gatewayId, $deviceId, $deviceType1, $deviceType2, $fromDate, $toDate)
  Brief               : Function to get data of a specific coin sensor for a given date range
  Return              : Returns array.
*/

public function analyticsFilteredChartData($gatewayId, $deviceId, $deviceType1, $deviceType2, $fromDate, $toDate){
    $userManager = new UserManager();
    $aResult = array();
    $aList = array();
    $aList = $userManager->analyticsFilteredChartData($gatewayId, $deviceId, $deviceType1, $deviceType2, $fromDate, $toDate);
        if ($aList[JSON_TAG_STATUS] == 0) {
            $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
            JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
        } elseif ($aList[JSON_TAG_STATUS] == 1) {
            $aResult = $this->httpStatusServer();
        }
    return $aResult;
}


     /*
      Function            : breadcrumbNickName($gatewayId, $deviceId)
      Brief               : Function to get Coin Nick Name for breadcrumbs
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function breadcrumbNickName($gatewayId, $deviceId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->breadcrumbNickName($gatewayId, $deviceId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }
public function locationCoinUpdate($userId, $inData) {
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList   = $userManager->locationCoinUpdate($userId, $inData);
        if ($aList[JSON_TAG_STATUS] == 0) {
            $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
        } elseif ($aList[JSON_TAG_STATUS] == 1) {
            $aResult = $this->httpStatusServer();
        } 

        return $aResult;
    } 


     /*
      Function            : renameCoin()
      Brief               : Function to update coin nick name
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */

    public function renameCoin($inData) {
      $userManager = new UserManager();
      $aResult = array();
      $aList = array();
      $aList = $userManager->renameCoin($inData);

      if($aList[JSON_TAG_STATUS] == 0) {
        $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                      JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
      } else{
        $aResult = $this->httpStatusServer();
      } 

      return $aResult;
    }

      /*
      Function            : getCoinLocation($userId)
      Brief               : Function to get user Coins      
      Input param         : $userId
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getCoinLocation($gatewayId, $deviceId, $userId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getCoinLocation($gatewayId, $deviceId, $userId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

public function deleteCoinLocation($userId, $inData) {
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList   = $userManager->deleteCoinLocation($userId, $inData);
        if ($aList[JSON_TAG_STATUS] == 0) {
            $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
        } elseif ($aList[JSON_TAG_STATUS] == 1) {
            $aResult = $this->httpStatusServer();
        } 

        return $aResult;
    } 

    /*
      Function            : eventAddLog()
      Brief               : Function to log real time event
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function eventAddLog($userId, $inData) {
      	$userManager = new UserManager();
      	$aResult = array();
      	$aList = array();
      	$aList   = $userManager->eventAddLog($userId,$inData);
                  
        if ($aList[JSON_TAG_STATUS] == 0) {
            $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
        } elseif ($aList[JSON_TAG_STATUS] == 1) {
            $aResult = $this->httpStatusServer();
        } 
      	return $aResult;
    }

    /*
    Function        : getEventLogs()
    Brief           : Function to get a event logs of a location
    Input param     : $userId
    Input/Output param  : $array
    return          : Returns array.
    */

        public function getEventLogs($userId, $locationId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getEventLogs($userId, $locationId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
            return $aResult;
    }

      /*
      Function            : getDeviceSettings($userId, $gatewayId)
      Brief               : Function to get Device Settings      
      Input param         : $userId
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getDeviceSettings($userId, $gatewayId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getDeviceSettings($userId, $gatewayId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

	/*
      Function            : updateSensorActive($inData)
      Brief               : Function to enable/disable sensor
      Details             : Function to enable/disable sensor
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function updateSensorActive($inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->updateSensorActive($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 5) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_PENDING_REQUEST,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            }

            
        return $aResult;
    }

     /*
      Function            : analyticsAccStream($gatewayId, $deviceId, $deviceType)
      Brief               : Function to get 3-axis Accelerometer Stream data for a specific coin
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function analyticsAccStream($gatewayId, $deviceId, $deviceType){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->analyticsAccStream($gatewayId, $deviceId, $deviceType);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

	/*
  Function            : analyticsStreamFilteredData($gatewayId, $deviceId, $deviceType1, $startTime, $endTime)
  Brief               : Function to get data of a specific coin sensor for a given date range
  Return              : Returns array.
*/

public function analyticsStreamFilteredData($gatewayId, $deviceId, $deviceType1, $startTime, $endTime){
    $userManager = new UserManager();
    $aResult = array();
    $aList = array();
    $aList = $userManager->analyticsStreamFilteredData($gatewayId, $deviceId, $deviceType1, $startTime, $endTime);
        if ($aList[JSON_TAG_STATUS] == 0) {
            $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
            JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
        } elseif ($aList[JSON_TAG_STATUS] == 1) {
            $aResult = $this->httpStatusServer();
        }
    return $aResult;
}

	/*
      Function            : updateRequestAction($inData)
      Brief               : Function to update the action against GET current value request
      Details             : Function to update the action against GET current value request
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function updateRequestAction($inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->updateRequestAction($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 5) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_RESPONSE_RECEIVED,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            }

            
        return $aResult;
    }

/*
      Function            : editUserLocation($userId,$inData)
      Brief               : Function to edit User Location
      Details             : Function to edit User Location
      Input param         : $inData
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function editUserLocation($userId,$inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->editUserLocation($userId,$inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //Location already added
                $aResult = $this->responseResult(ERRCODE_ERROR,LOCATION_EXISTS);
	    } elseif ($aList[JSON_TAG_STATUS] == 4) {		
		$aResult = array(JSON_TAG_STATUS => JSON_TAG_GATEWAY_EXISTS,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            }
            
        return $aResult;
    }

public function setCoinTransmissionPower($inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->setCoinTransmissionPower($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } 

            
        return $aResult;
}

/*
      Function            : getAccStreamDevices($gatewayId, $coins)
      Brief               : Function to get devices connected to the gateway     
      Input param         : Nil
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getAccStreamDevices($gatewayId, $coins){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getAccStreamDevices($gatewayId, $coins);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

public function getGeneralSettings($userId)
{
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getGeneralSettings($userId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
}  


public function updateGeneralSettings($inData, $userId){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->updateGeneralSettings($inData, $userId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } 

            
        return $aResult;
}

 public function getAcceTypesDevices($gatewayId, $coins, $sensors){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getAcceTypesDevices($gatewayId, $coins, $sensors);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

public function addLogo($userId,$inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->addLogo($userId,$inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } 

            
        return $aResult;
    }



public function setDetectionPeriod($inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->setDetectionPeriod($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 3) { //invalid Gateway
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_GATEWAY_ID);
            } elseif ($aList[JSON_TAG_STATUS] == 4) { //Device is not associated with the Gateway
                $aResult = $this->responseResult(ERRCODE_ERROR,INVALID_DEVICE_GATEWAY_ID);
            } elseif ($aList[JSON_TAG_STATUS] == 5) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_PENDING_REQUEST,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 6) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_DETECTION_REJECT,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            }


            
        return $aResult;
    }

public function setCoinFrequency($inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->setCoinFrequency($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } elseif ($aList[JSON_TAG_STATUS] == 5) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_PENDING_REQUEST,
                JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            }
            
        return $aResult;
}

public function setCoinStreamThreshold($inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->setCoinStreamThreshold($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } 
            
        return $aResult;
}

public function updateDeviceEmailNotification($inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->updateDeviceEmailNotification($inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            }
            
        return $aResult;
    }


public function updateShownSensors($userId,$inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->updateShownSensors($userId,$inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } 

            
        return $aResult;
    }

public function updateGenerateReport($userId,$inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->updateGenerateReport($userId,$inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } 

            
        return $aResult;
    }


public function getDailyReportData($userID, $gateway_id, $device_id, $sensor, $startTime, $endTime){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getDailyReportData($userID, $gateway_id, $device_id, $sensor, $startTime, $endTime);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }


    public function updateMailRestriction($userId,$inData){
        
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->updateMailRestriction($userId,$inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } 

        return $aResult;
    }


    public function getFFTbaseData($gatewayId, $deviceId, $sensor_axis, $frequency){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getFFTbaseData($gatewayId, $deviceId, $sensor_axis, $frequency);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

    public function getFilteredFFTbaseData($gatewayId, $deviceId, $sensor_axisSelected, $startTime, $endTime, $frequency){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getFilteredFFTbaseData($gatewayId, $deviceId, $sensor_axisSelected, $startTime, $endTime, $frequency);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

    public function getfftFilteredDatefrequency($gateway_id, $device_id, $selectedfilter_date){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getfftFilteredDatefrequency($gateway_id, $device_id, $selectedfilter_date);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }



    /*
      Function            : devicesLowBattery()
      Brief               : Function to get all all Low Battery COINs of a perticular user account 
      Input param         : Nil
      Input/output param  : $aResult
      Return              : Returns array.
    */
    public function devicesLowBattery($userId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->devicesLowBattery($userId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    } 


    public function updateNewPassword($userId,$inData)
    {
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->updateNewPassword($userId,$inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } 

        return $aResult;
    }


    public function updateGatewayNickName($userId,$inData)
    {
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->updateGatewayNickName($userId,$inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } 

        return $aResult;
    }

    public function updateWhatsAppAlert($userId,$inData)
    {
        $userManager = new UserManager();        
        $aResult = array();
        $aList   = array();
        $aList   = $userManager->updateWhatsAppAlert($userId,$inData);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS=>$aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            } elseif ($aList[JSON_TAG_STATUS] == 2) { //MANDATORY FIELD REQUIRED
                $aResult = $this->responseResult(ERRCODE_ERROR,MANDATORY_FIELD_REQUIRED);
            } 

        return $aResult;
    }

    

    public function getGatewayDetail($gatewayId){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getGatewayDetail($gatewayId);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    } 

    
    /*
      Function            : updateCoinLocation()
      Brief               : Function to update coin location address
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */

    public function updateCoinLocation($inData) {
      $userManager = new UserManager();
      $aResult = array();
      $aList = array();
      $aList = $userManager->updateCoinLocation($inData);

      if($aList[JSON_TAG_STATUS] == 0) {
        $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                      JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
      } else{
        $aResult = $this->httpStatusServer();
      } 

      return $aResult;
    }

    
    /*
      Function            : getPredStreamDevices($gatewayId, $coins)
      Brief               : Function to get devices connected to the gateway     
      Input param         : Nil
      Input/output param  : $aResult
      Return              : Returns array.
     */
    public function getPredStreamDevices($gatewayId, $coins){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->getPredStreamDevices($gatewayId, $coins);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }


    /*
      Function            : analyticsAccStream($gatewayId, $deviceId, $deviceType)
      Brief               : Function to get 3-axis Accelerometer Stream data for a specific coin
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function analyticsPredStream($gatewayId, $deviceId, $deviceType){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->analyticsPredStream($gatewayId, $deviceId, $deviceType);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                    JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }

    /*
    Function            : analyticsPredStreamFilteredData($gatewayId, $deviceId, $deviceType1, $startTime, $endTime)
    Brief               : Function to get data of a specific coin sensor for a given date range
    Return              : Returns array.
    */

    public function analyticsPredStreamFilteredData($gatewayId, $deviceId, $deviceType1, $startTime, $endTime){
        $userManager = new UserManager();
        $aResult = array();
        $aList = array();
        $aList = $userManager->analyticsPredStreamFilteredData($gatewayId, $deviceId, $deviceType1, $startTime, $endTime);
            if ($aList[JSON_TAG_STATUS] == 0) {
                $aResult = array(JSON_TAG_STATUS => JSON_TAG_SUCCESS,
                JSON_TAG_RECORDS => $aList[JSON_TAG_RESULT]);
            } elseif ($aList[JSON_TAG_STATUS] == 1) {
                $aResult = $this->httpStatusServer();
            }
        return $aResult;
    }


// 
}