<?php
/*
  Project                     : Sensegiz
  Module                      : App Console --  Controller Class
  File name                   : AppController.php 
  Description                 : Controller class for App related activities
 */

class AppController {

     /*
      Function            : paramMissing($instance)
      Brief               : Function for Missing Param
      Details             : Function for Missing Param
      Input param         : $instance
      Return              : Returns array.
     */      
    public function paramMissing($instance){
        $result = array(JSON_TAG_STATUS => JSON_TAG_ERROR,
                        JSON_TAG_DESC => INSUFFICIENT_PARAM);   
        $instance->response()->header(CUSTOM_ERROR_CODE, ERRCODE_INSUFFICIENT_PARAM);
        $instance->status(ERRCODE_CLIENT);
        return $result;
    }    
    
     /*
      Function            : loginUser()
      Brief               : Function to login normal User
      Details             : Function to login normal User
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */    
    public function loginUser(){
        $appModel = new AppModel();
        $genMethod   = new GeneralMethod();
        $instance    = \Slim\Slim::getInstance();
        $bodyData    = $instance->request()->getBody();
     
        $inData      = json_decode($bodyData, true);
      
        if (is_array($inData) && array_key_exists(JSON_TAG_EMAIL, $inData) 
            && array_key_exists(JSON_TAG_PASSWORD, $inData)) {

                $result = $appModel->loginUser($inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);        
    }
    
     /*
      Function            : forgotPassword()
      Brief               : Function to send reset password link
      Details             : Function to send reset password link
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */    
    public function forgotPassword(){
        $appModel    = new AppModel();
        $genMethod   = new GeneralMethod();
        $instance    = \Slim\Slim::getInstance();
        $bodyData    = $instance->request()->getBody();
     
        $inData      = json_decode($bodyData, true);
      
        if (is_array($inData) && array_key_exists(JSON_TAG_EMAIL, $inData) 
            ) {

                $result = $appModel->forgotPassword($inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);        
    }
    
     /*
      Function            : changePassword()
      Brief               : Function to change password
      Details             : Function to change password
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */    
    public function changePassword(){
        $appModel    = new AppModel();
        $genMethod   = new GeneralMethod();
        $instance    = \Slim\Slim::getInstance();
        $bodyData    = $instance->request()->getBody();
     
        $inData      = json_decode($bodyData, true);
      
        if(is_array($inData) && array_key_exists(JSON_TAG_CURRENT_PASSWORD, $inData)
                && array_key_exists(JSON_TAG_NEW_PASSWORD, $inData)) {
                
                $userId     =   $genMethod->getUserId();
                
                $result = $appModel->changePassword($inData,$userId);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);        
    }
    
     /*
      Function            : addGateways()
      Brief               : Function to Add Gateways
      Details             : Function to Add Gateways
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */    
    public function addGateways(){
        $appModel = new AppModel();
        $genMethod   = new GeneralMethod();
        $instance    = \Slim\Slim::getInstance();
        $bodyData    = $instance->request()->getBody();
     
        $inData      = json_decode($bodyData, true);
      
        if (is_array($inData) && array_key_exists(JSON_TAG_USER_ID, $inData) 
            && array_key_exists(JSON_TAG_GATEWAY_ID, $inData)) {

                $result = $appModel->addGateways($inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);        
    }
    
     /*
      Function            : getGateways($userId)
      Brief               : Function to Get Gateways
      Details             : Function to Get Gateways
      Input param         : $userId
      Input/output param  : Array
      Return              : Returns array.
     */    
    public function getGateways(){
        $appModel    = new AppModel();
        $genMethod   = new GeneralMethod();
        
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];
        
//        print_r($headers);exit();
//        $headers    = $this->app->request->headers();

      
        $result = $appModel->getGateways($userId);   
       
        $genMethod->generateResult($result);        
    }    
       
     /*
      Function            : addDevices()
      Brief               : Function to Add Devices
      Details             : Function to Add Devices
      Input param         : Nil
      Input/output param  : Array
      Return              : Returns array.
     */    
    public function addDevices(){
        $appModel    = new AppModel();
        $genMethod   = new GeneralMethod();
        $instance    = \Slim\Slim::getInstance();
        $bodyData    = $instance->request()->getBody();
     
        $inData      = json_decode($bodyData, true);
      
        if (is_array($inData) && array_key_exists(JSON_TAG_USER_ID, $inData) 
            && array_key_exists(JSON_TAG_GATEWAY_ID, $inData)
            && array_key_exists(JSON_TAG_DEVICE_MAC_ADDRESS, $inData) ) {

                $result = $appModel->addDevices($inData);   
        } else {  
                $result = $this->paramMissing($instance);                    
        }
        $genMethod->generateResult($result);        
    }
    
     /*
      Function            : getDevices($gatewayId,$userId)
      Brief               : Function to Get Devices
      Details             : Function to Get Devices
      Input param         : $gatewayId,$userId
      Input/output param  : Array
      Return              : Returns array.
     */    
    public function getDevices($gatewayId){
        $appModel    = new AppModel();
        $genMethod   = new GeneralMethod();
      
        $instance   = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();
        $userId     = $headers['uid'];
        
        $result = $appModel->getDevices($gatewayId,$userId);   
       
        $genMethod->generateResult($result);        
    }    
        
    
    
    
//
}