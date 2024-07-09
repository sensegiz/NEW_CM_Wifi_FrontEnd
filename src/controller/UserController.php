<?php 
/*
  Project                     : Sensegiz
  Module                      : Web Console --  Controller Class
  File name                   : UserController.php       
  Description                 : Controller class for User/Gateway related activities
 */
class UserController {
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
      Function            : getGateways()
      Brief               : Function to get all Gateways 
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getGateways(){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];        
        
        $result = $userModel->getGateways($userId);
        $genMethod->generateResult($result);   
    }          
       
     /*
      Function            : getDevices($gatewayId, $coins, $sensors)
      Brief               : Function to get devices connected to the gateway
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getDevices($gatewayId, $coins, $sensors){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        $result = $userModel->getDevices($gatewayId, $coins, $sensors);
        $genMethod->generateResult($result);   
    }  
    
     /*
      Function            : getDeviceValue($gatewayId,$deviceId)
      Brief               : Function to get each device value
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getDeviceValue($gatewayId,$deviceId){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        $result = $userModel->getDeviceValue($gatewayId,$deviceId);
        $genMethod->generateResult($result);   
    }     
     
    /*
      Function            : setDeviceThreshold()
      Brief               : Function to set Threshold
      Details             : Function to set Threshold
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function setDeviceThreshold(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID, $inData) 
            && array_key_exists(JSON_TAG_DEVICE_ID, $inData) 
            && array_key_exists(JSON_TAG_DEVICE_TYPE, $inData)                    
            && array_key_exists(JSON_TAG_THRESHOLD, $inData)) {

                $result = $userModel->setDeviceThreshold($inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }

    public function xyzCombinationChange(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID, $inData) 
            && array_key_exists(JSON_TAG_SENSOR_ACTIVE, $inData) 
            && array_key_exists(JSON_TAG_SENSOR_TYPE, $inData)                    
            ) {

                $result = $userModel->xyzCombinationChange($inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }


/* function to set stream

/*
      Function            : setStream()
      Brief               : Function to set Stream Rate
      Details             : Function to set Stream Rate
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function setStream(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
//         print_r($inData);
//         print_r('-sss-');
////        
////       print_r($_POST);
//         exit();
        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID, $inData) 
            && array_key_exists(JSON_TAG_DEVICE_ID, $inData) 
            && array_key_exists(JSON_TAG_DEVICE_TYPE, $inData)                    
            && array_key_exists(JSON_TAG_RATE_VALUE, $inData)
            && array_key_exists(JSON_TAG_FORMAT, $inData)  )  
                {

                $result = $userModel->setStream($inData);   
        } else {
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }

    /*
      Function            : getCurrentValue()
      Brief               : Function to get Current Value
      Details             : Function to get Current Value
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getCurrentValue(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID, $inData) 
            && array_key_exists(JSON_TAG_DEVICE_ID, $inData) 
            && array_key_exists(JSON_TAG_DEVICE_TYPE, $inData)) {

                $result = $userModel->getCurrentValue($inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }
    
     /*
      Function            : getGatewaySettings()
      Brief               : Function to get Gateways Settings
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getGatewaySettings(){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];        
        
        $result = $userModel->getGatewaySettings($userId);
        $genMethod->generateResult($result);   
    }

    /*
      Function            : updateSmsNotification()
      Brief               : Function to Update SMS Notification
      Details             : Function to Update SMS Notification
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function updateSmsNotification(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID, $inData) 
            && array_key_exists(JSON_TAG_SMS_ALERT, $inData) 
            && array_key_exists(JSON_TAG_SENSOR_TYPE, $inData)                    
            ) {

                $result = $userModel->updateSmsNotification($inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }
    
    /*
      Function            : updateEmailNotification()
      Brief               : Function to Update EMAIL Notification
      Details             : Function to Update EMAIL Notification
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function updateEmailNotification(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID, $inData) 
            && array_key_exists(JSON_TAG_EMAIL_ALERT, $inData) 
            && array_key_exists(JSON_TAG_SENSOR_TYPE, $inData)                    
            ) {

                $result = $userModel->updateEmailNotification($inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }
    
     /*
      Function            : getNotificationEmailIds()
      Brief               : Function to get user notification emails 
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getNotificationEmailIds(){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];        
        
        $result = $userModel->getNotificationEmailIds($userId);
        $genMethod->generateResult($result);   
    } 

    /*
      Function            : addNotificationEmailIds()
      Brief               : Function to Add Email ids
      Details             : Function to Add Email ids
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function addNotificationEmailIds(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];         

        if (is_array($inData) && array_key_exists(JSON_TAG_NOTIFICATION_EMAIL, $inData)
                && array_key_exists(JSON_TAG_ID, $inData)) {

                $result = $userModel->addNotificationEmailIds($userId,$inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }    
        
     /*
      Function            : deleteNotificationEmailIds($Id)
      Brief               : Function to delete notification emails 
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function deleteNotificationEmailIds($Id){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];        
        
        $result = $userModel->deleteNotificationEmailIds($userId,$Id);
        $genMethod->generateResult($result);   
    }  
    
     /*
      Function            : getNotificationPhone()
      Brief               : Function to get user notification phone 
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getNotificationPhone(){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];        
        
        $result = $userModel->getNotificationPhone($userId);
        $genMethod->generateResult($result);   
    } 




    /*
      Function            : addNotificationPhone()
      Brief               : Function to Add Phone
      Details             : Function to Add Phone
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function addNotificationPhone(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];         

        if (is_array($inData) && array_key_exists(JSON_TAG_NOTIFICATION_PHONE, $inData)
                && array_key_exists(JSON_TAG_ID, $inData)) {

                $result = $userModel->addNotificationPhone($userId,$inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }    
        
     /*
      Function            : deleteNotificationPhone($Id)
      Brief               : Function to delete notification phone 
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function deleteNotificationPhone($Id){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];        
        
        $result = $userModel->deleteNotificationPhone($userId,$Id);
        $genMethod->generateResult($result);   
    }         
/*
      Function            : createLocation()
      Brief               : Function to Create User Location
      Details             : Function to Create User Location
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function createLocation(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];         

        if (is_array($inData) && array_key_exists(JSON_TAG_LOCATION_NAME, $inData)
        && array_key_exists(JSON_TAG_LOCATION_DESCRIPTION, $inData)
        && array_key_exists(JSON_TAG_LOCATION_IMAGE, $inData)
        && array_key_exists(JSON_TAG_GATEWAY_ID, $inData)
                && array_key_exists(JSON_TAG_ID, $inData)) {

                $result = $userModel->createLocation($userId,$inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }    

     /*
      Function            : deleteUserLocation($Id)
      Brief               : Function to delete User Locations
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function deleteUserLocation($Id){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];        
        
        $result = $userModel->deleteUserLocation($userId,$Id);
        $genMethod->generateResult($result);   
    }  

/*
      Function            : addCoin()
      Brief               : Function to Add Coin to Map
      Details             : Function to Add Coin to Map
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function addCoin(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];         

       
        if (is_array($inData) ) {

                $result = $userModel->addCoin($userId,$inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }    

     /*
      Function            : getCoin()
      Brief               : Function to get user Coins
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getCoin($gatewayId){

        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];        
        $result = $userModel->getCoin($gatewayId, $userId);
        $genMethod->generateResult($result);   
    } 

     /*
      Function            : addGetCoin()
      Brief               : Function to get Coins associated with gateway assigned to gateway to add on the Map
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function addGetCoin($gatewayId){

        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];        
        $result = $userModel->addGetCoin($gatewayId, $userId);
        $genMethod->generateResult($result);   
    } 


    /*
        Function            : getLocation()
        Brief           : Function to get a list of User Locations
        Input param         : Nill
        Input/Output param  : Array
        Return          : Returns Array.
    */

    public function getLocation(){

        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];        
        $result = $userModel->getLocation($userId);
        $genMethod->generateResult($result);   
    } 

    /*
        Function            : getGatewayLocation()
        Brief           : Function to get a list of gateways assigned to User Locations
        Input param         : Nill
        Input/Output param  : Array
        Return          : Returns Array.
    */

    public function getGatewayLocation($locationId){

        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];
        $result = $userModel->getGatewayLocation($userId, $locationId);
        $genMethod->generateResult($result);   
    } 

    /* Function         : renderAlert(gwId, devId)
       Brief            : Function to get coin latitude and longitude to render alerts on map
       Input param          : $gatewayId, $coinId
       Input/ Output param      : Array
       Return           : Returns Array.
    */

    public function renderAlert($gwId, $devId){

        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];
        $result = $userModel->renderAlert($userId, $gwId, $devId);
        $genMethod->generateResult($result);   
    } 


     /*
      Function            : getHelpDevices($gatewayId)
      Brief               : Function to get help gateway and coin data
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getHelpDevices($gatewayId){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        $result = $userModel->getHelpDevices($gatewayId);
        $genMethod->generateResult($result);   
    }  

     /*
      Function            : getHelpDeviceSensor($gatewayId, $deviceId)
      Brief               : Function to get help coin data for a specific coin
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getHelpDeviceSensor($gatewayId, $deviceId){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        $result = $userModel->getHelpDeviceSensor($gatewayId, $deviceId);
        $genMethod->generateResult($result);   
    }

/*
      Function            : analyticsGatewayDevices()
      Brief               : Function to get coins registered to a gateway
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */

    public function analyticsGatewayDevices($gatewayId){
        $userModel = new UserModel();
        $genMethod = new GeneralMethod();
        $result = $userModel->analyticsGatewayDevices($gatewayId);
        $genMethod->generateResult($result);
    }

     /*
      Function            : analyticsDeviceSensor($gatewayId, $deviceId)
      Brief               : Function to get help coin data for a specific coin
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function analyticsDeviceSensor($gatewayId, $deviceId){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        $result = $userModel->analyticsDeviceSensor($gatewayId, $deviceId);
        $genMethod->generateResult($result);   
    }

 	  /*
      Function            : analyticsDeviceSensorData($gatewayId, $deviceId, $deviceType1, $deviceType2)
      Brief               : Function to get data of a specific coin sensor
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function analyticsDeviceSensorData($gatewayId, $deviceId, $deviceType1, $deviceType2){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        $result = $userModel->analyticsDeviceSensorData($gatewayId, $deviceId, $deviceType1, $deviceType2);
        $genMethod->generateResult($result);   
    }


/*
      Function            : analyticsFilteredChartData($gatewayId, $deviceId, $deviceType1, $deviceType2, $fromDate, $toDate)
      Brief               : Function to get data of a specific coin sensor for a given date range
      Return              : Returns array.
*/

public function analyticsFilteredChartData($gatewayId, $deviceId, $deviceType1, $deviceType2, $fromDate, $toDate){
	$userModel = new UserModel();
	$genMethod  = new GeneralMethod();
	$result = $userModel->analyticsFilteredChartData($gatewayId, $deviceId, $deviceType1, $deviceType2, $fromDate, $toDate);
	$genMethod->generateResult($result);   
}


     /*
      Function            : breadcrumbNickName($gatewayId, $deviceId)
      Brief               : Function to get Coin Nick Name for breadcrumbs
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function breadcrumbNickName($gatewayId, $deviceId){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        $result = $userModel->breadcrumbNickName($gatewayId, $deviceId);
        $genMethod->generateResult($result);   
    }
/*
      Function            : updateCoinLocation()
      Brief               : Function to update coin location on the map
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */

    public function locationCoinUpdate() {
      $instance = \Slim\Slim::getInstance();
      $headers  = $instance->request()->headers();
      $userId   = $headers['uid'];  

      $bodyData = $instance->request()->getBody();
      $inData   = json_decode($bodyData, true);
      $userModel = new UserModel();
      $genMethod = new GeneralMethod();
      $result = $userModel->locationCoinUpdate($userId, $inData);
      $genMethod->generateResult($result);
    }

     /*
      Function            : renameCoin()
      Brief               : Function to update coin nick name
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */

    public function renameCoin() {
      $userModel = new UserModel();
      $genMethod = new GeneralMethod();

      $instance = \Slim\Slim::getInstance();
      $bodyData = $instance->request()->getBody();
      $inData = json_decode($bodyData, true);

	$headers    = $instance->request()->headers();
        $userId     = $headers['uid']; 

      if(is_array($inData)) {
            $result = $userModel->renameCoin($inData);
      } else {
        $result = $this->paramMissing($instance);
      }
      $genMethod->generateResult($result);
    }

 /*
      Function            : getCoinLocation()
      Brief               : Function to get user Coins
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getCoinLocation($gatewayId, $deviceId){

        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];        
        $result = $userModel->getCoinLocation($gatewayId, $deviceId, $userId);
        $genMethod->generateResult($result);   
    }

/*
      Function            : deleteCoinLocation()
      Brief               : Function to delete coin location on the map
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */

    public function deleteCoinLocation() {
      $instance = \Slim\Slim::getInstance();
      $headers  = $instance->request()->headers();
      $userId   = $headers['uid'];  

      $bodyData = $instance->request()->getBody();
      $inData   = json_decode($bodyData, true);
      $userModel = new UserModel();
      $genMethod = new GeneralMethod();
      $result = $userModel->deleteCoinLocation($userId, $inData);
      $genMethod->generateResult($result);
    }

     /*
      Function            : eventAddLog()
      Brief               : Function to log real time event
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function eventAddLog() {

	$instance = \Slim\Slim::getInstance();
	$headers  = $instance->request()->headers();
	$userId   = $headers['uid'];	

	$bodyData = $instance->request()->getBody();
	$inData   = json_decode($bodyData, true);

	$userModel = new UserModel();
	$genMethod = new GeneralMethod();
	$result = $userModel->eventAddLog($userId, $inData);
	$genMethod->generateResult($result);
    }

    /*
        Function            : getEventLogs()
        Brief           : Function to get a event logs for a location
        Input param         : Nill
        Input/Output param  : Array
        Return          : Returns Array.
    */

    public function getEventLogs($locationId){

        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];
        $result = $userModel->getEventLogs($userId, $locationId);
        $genMethod->generateResult($result);   
    } 

     /*
      Function            : getDeviceSettings($gatewayId)
      Brief               : Function to get Device Settings
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getDeviceSettings($gatewayId){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];        
        
        $result = $userModel->getDeviceSettings($userId, $gatewayId);
        $genMethod->generateResult($result);   
    }

    /*
      Function            : updateSensorActive()
      Brief               : Function to enable/disable sensor
      Details             : Function to enable/disable sensor
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function updateSensorActive(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID, $inData) 
            && array_key_exists(JSON_TAG_SENSOR_ACTIVE, $inData) 
            && array_key_exists(JSON_TAG_SENSOR_TYPE, $inData)                    
            ) {

                $result = $userModel->updateSensorActive($inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }

    /*
      Function            : analyticsAccStream($gatewayId, $deviceId, $deviceType)
      Brief               : Function to get 3-axis Accelerometer Stream data for a specific coin
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function analyticsAccStream($gatewayId, $deviceId, $deviceType){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        $result = $userModel->analyticsAccStream($gatewayId, $deviceId, $deviceType);
        $genMethod->generateResult($result);   
    }

/*
      Function            : analyticsStreamFilteredData($gatewayId, $deviceId, $deviceType1, $startTime, $endTime)
      Brief               : Function to get accelerometer stream data of a specific coin sensor for a given date range
      Return              : Returns array.
*/

public function analyticsStreamFilteredData($gatewayId, $deviceId, $deviceType1, $startTime, $endTime){
	$userModel = new UserModel();
	$genMethod  = new GeneralMethod();
	$result = $userModel->analyticsStreamFilteredData($gatewayId, $deviceId, $deviceType1, $startTime, $endTime);
	$genMethod->generateResult($result);   
}

    /*
      Function            : updateRequestAction()
      Brief               : Function to update the action against GET current value request
      Details             : Function to update the action against GET current value request
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function updateRequestAction(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID, $inData) 
            && array_key_exists(JSON_TAG_DEVICE_ID, $inData) 
            && array_key_exists(JSON_TAG_DEVICE_TYPE, $inData)                    
            ) {

                $result = $userModel->updateRequestAction($inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }
       
/*
      Function            : editUserLocation()
      Brief               : Function to edit User Location
      Details             : Function to edit User Location
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function editUserLocation(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];         

        if (is_array($inData)) {

                $result = $userModel->editUserLocation($userId,$inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }

// Set Coin Transmission power
public function setCoinTransmissionPower(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData)) {

                $result = $userModel->setCoinTransmissionPower($inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
}

/*
      Function            : getAccStreamDevices($gatewayId, $coins)
      Brief               : Function to get devices connected to the gateway
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getAccStreamDevices($gatewayId, $coins){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        $result = $userModel->getAccStreamDevices($gatewayId, $coins);
        $genMethod->generateResult($result);   
    } 


public function getGeneralSettings()
{
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];        
        
        $result = $userModel->getGeneralSettings($userId);
        $genMethod->generateResult($result);   
}


public function updateGeneralSettings()
{
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
	$headers    = $instance->request()->headers();
        $userId     = $headers['uid'];
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData)) {
                $result = $userModel->updateGeneralSettings($inData, $userId);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
}


/*
      Function            : getAcceTypesDevices($gatewayId, $coins, $sensors)
      Brief               : Function to get devices connected to the gateway for accelerometer, velocity and displacement
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getAcceTypesDevices($gatewayId, $coins, $sensors){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        $result = $userModel->getAcceTypesDevices($gatewayId, $coins, $sensors);
        $genMethod->generateResult($result);   
    }


public function addLogo(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];         

        if (is_array($inData) && array_key_exists(JSON_TAG_LOGO_IMAGE, $inData)) {
                $result = $userModel->addLogo($userId,$inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }

public function setDetectionPeriod(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData))  
                {

                $result = $userModel->setDetectionPeriod($inData);   
        } else {
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }

// Set Coin Frequency
public function setCoinFrequency(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData)) {

                $result = $userModel->setCoinFrequency($inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
}

public function setCoinStreamThreshold(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData)) {

                $result = $userModel->setCoinStreamThreshold($inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
}

public function updateDeviceEmailNotification(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);

        if (is_array($inData) && array_key_exists(JSON_TAG_GATEWAY_ID, $inData) 
            && array_key_exists(JSON_TAG_DEVICE_ID, $inData) 
            && array_key_exists(JSON_TAG_EMAIL_ALERT, $inData) 
            && array_key_exists(JSON_TAG_SENSOR_TYPE, $inData)                    
            ) {

                $result = $userModel->updateDeviceEmailNotification($inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }

public function updateShownSensors(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];         

        if (is_array($inData)) {
                $result = $userModel->updateShownSensors($userId,$inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }

public function updateGenerateReport(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];         

        if (is_array($inData)) {
                $result = $userModel->updateGenerateReport($userId,$inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);
    }


public function getDailyReportData($userID, $gateway_id, $device_id, $sensor, $startTime, $endTime){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        $result = $userModel->getDailyReportData($userID, $gateway_id, $device_id, $sensor, $startTime, $endTime);
        $genMethod->generateResult($result);   
    }


    public function updateMailRestriction(){
             
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];         

        if (is_array($inData)) {
                $result = $userModel->updateMailRestriction($userId,$inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($userId);
    }


    // Get Data for FFT Plot
    public function getFFTbaseData($gatewayId, $deviceId, $sensor_axis, $frequency){
        $userModel = new UserModel();
        $genMethod = new GeneralMethod();
        $result = $userModel->getFFTbaseData($gatewayId, $deviceId, $sensor_axis, $frequency);
        $genMethod->generateResult($result);

    }

    // Get Data for Filtered tool FFT Plot
    public function getFilteredFFTbaseData($gatewayId, $deviceId, $sensor_axisSelected, $startTime, $endTime, $frequency){
        $userModel = new UserModel();
        $genMethod = new GeneralMethod();
        $result = $userModel->getFilteredFFTbaseData($gatewayId, $deviceId, $sensor_axisSelected, $startTime, $endTime, $frequency);
        $genMethod->generateResult($result);

    }

    public function getfftFilteredDatefrequency($gateway_id, $device_id, $selectedfilter_date){
        $userModel = new UserModel();
        $genMethod = new GeneralMethod();
        $result = $userModel->getfftFilteredDatefrequency($gateway_id, $device_id, $selectedfilter_date);
        $genMethod->generateResult($result);

    }
    

    /*
      Function            : devicesLowBattery()
      Brief               : Function to get all all Low Battery COINs of a perticular user account 
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function devicesLowBattery(){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];        
        
        $result = $userModel->devicesLowBattery($userId);
        $genMethod->generateResult($result);   
    }     


    /*
      Function            : updateNewPassword()
      Brief               : Function to update the Password of a perticular user account from user panel
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function updateNewPassword(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];         

        if (is_array($inData)) {
                $result = $userModel->updateNewPassword($userId,$inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($userId);
        
    } 


    public function updateGatewayNickName(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];         

        if (is_array($inData)) {
                $result = $userModel->updateGatewayNickName($userId,$inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($userId);
        
    } 

    public function updateWhatsAppAlert(){
       
        $userModel   = new UserModel();
        $genMethod   = new GeneralMethod();
       
        $instance   = \Slim\Slim::getInstance();
        $bodyData   = $instance->request()->getBody();
        $inData     = json_decode($bodyData, true);
        
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];         

        if (is_array($inData)) {
                $result = $userModel->updateWhatsAppAlert($userId,$inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($userId);
        
    } 


    /*
     Function            : getGatewayDetail()
     Brief               : Function to get Gateway detail 
     Input param         : Nil    
     Return              : Returns array.
    */
    public function getGatewayDetail($gatewayId){
            $userModel = new UserModel();
            $genMethod  = new GeneralMethod();
            $result = $userModel->getGatewayDetail($gatewayId);
            $genMethod->generateResult($result); 
    }


    /*
      Function            : updateCoinLocation()
      Brief               : Function to Update Coin Location Address
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */

    public function updateCoinLocation() {
        $userModel = new UserModel();
        $genMethod = new GeneralMethod();

        $instance = \Slim\Slim::getInstance();
        $bodyData = $instance->request()->getBody();
        $inData = json_decode($bodyData, true);

        $headers    = $instance->request()->headers();
        $userId     = $headers['uid']; 

        if(is_array($inData)) {
              $result = $userModel->updateCoinLocation($inData);
        } else {
          $result = $this->paramMissing($instance);
        }
        $genMethod->generateResult($result);
    }


    /*
      Function            : getPredStreamDevices($gatewayId, $coins)
      Brief               : Function to get devices connected to the gateway
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function getPredStreamDevices($gatewayId, $coins){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        $result = $userModel->getPredStreamDevices($gatewayId, $coins);
        $genMethod->generateResult($result);   
    } 

    
    /*
      Function            : analyticsAccStream($gatewayId, $deviceId, $deviceType)
      Brief               : Function to get 3-axis Accelerometer Stream data for a specific coin
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */
    public function analyticsPredStream($gatewayId, $deviceId, $deviceType){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        $result = $userModel->analyticsPredStream($gatewayId, $deviceId, $deviceType);
        $genMethod->generateResult($result);   
    }
    
    /*
        Function            : analyticsStreamFilteredData($gatewayId, $deviceId, $deviceType1, $startTime, $endTime)
        Brief               : Function to get accelerometer stream data of a specific coin sensor for a given date range
        Return              : Returns array.
    */

    public function analyticsPredStreamFilteredData($gatewayId, $deviceId, $deviceType1, $startTime, $endTime){
        $userModel = new UserModel();
        $genMethod  = new GeneralMethod();
        $result = $userModel->analyticsPredStreamFilteredData($gatewayId, $deviceId, $deviceType1, $startTime, $endTime);
        $genMethod->generateResult($result);   
    }
    
//
}
?>