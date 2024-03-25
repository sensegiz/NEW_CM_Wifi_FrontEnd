<?php  session_start();
/*
  Project                     : Sensegiz
  Module                      : Index
  File name                   : index.php
  Description                 : All routes are defined here.
 */

require_once 'library/Slim/Slim/Slim.php';
require_once './library/Slim/Slim/Middleware.php';
require_once 'src/middleware/VerifyAPIKey.php';

require_once 'src/config/config.php';
require_once 'src/config/connect.php';
require_once 'src/config/messages.php';
require_once 'src/config/jsontags.php';
require_once 'src/config/errorcodes.php';
use Slim\Slim;
Slim::registerAutoloader();

$Rest = new Slim();

include ('autoload.php');

require 'library/vendor/autoload.php';

////*******************Admin APIs*********************************

        $Rest->add(new VerifyAPIKey());

        $AdminCtr = new AdminController();
        
        //To login to Admin Panel
        $Rest->post('/admin/login',array($AdminCtr, 'login')); 
        
        //To invite to User from Admin
        $Rest->post('/admin/sendinvite',array($AdminCtr, 'sendInvite'));
        
        //Get Invited users
        $Rest->get('/admin/invited-users',array($AdminCtr, 'getInvitedUsers')); 
        $Rest->post('/admin/update-coin-offline-time',array($AdminCtr, 'updateCoinOfflineTime')); 
        //To create new USER password
        $Rest->post('/admin/create-password',array($AdminCtr, 'userCreateNewPassword'));
        
        //Get Gateways for Admin
        // $Rest->get('/admin/gateways',array($AdminCtr, 'getAdminGateways')); 
        $Rest->get('/admin/gateways/:coinmacID',array($AdminCtr, 'getAdminGateways'));
        
        //To Blacklist Gateways
        $Rest->post('/admin/blacklist-gateways',array($AdminCtr, 'blacklistGateways'));

        //Get Devices for Admin
        $Rest->get('/admin/devices/:gatewayId',array($AdminCtr, 'getAdminDevices')); 
        
        //To Blacklist Devices
        $Rest->post('/admin/blacklist-devices',array($AdminCtr, 'blacklistDevices'));
        
        //To Remove Gateways
        $Rest->post('/admin/remove-gateways',array($AdminCtr, 'removeGateways'));        
//
        //To Remove Device
        $Rest->post('/admin/remove-devices',array($AdminCtr, 'removeDevices'));  
      
        //To Hard delete Gateways
        $Rest->post('/admin/hard-delete-gateways',array($AdminCtr, 'hardDeleteGateways'));        
//
        //To Hard delete Device
        $Rest->post('/admin/hard-delete-devices',array($AdminCtr, 'hardDeleteDevices')); 

        //To add User from Admin
        $Rest->post('/admin/add-user',array($AdminCtr, 'addUser'));

	$Rest->post('/admin/send-otp',array($AdminCtr, 'sendOTP'));
	$Rest->post('/admin/restore-gateway',array($AdminCtr, 'restoreGateway'));
	$Rest->post('/admin/restore-device',array($AdminCtr, 'restoreDevice'));

	//To Edit Time Factor
        $Rest->post('/admin/time-factor',array($AdminCtr, 'updateTimeFactor'));

    // Get Gateway Detail
        $Rest->get('/admin/gateway-detail/:gatewayId',array($AdminCtr,'getGatewayDetail'));

    // Upadate Gateway ID: recovery of gateway with new gateway(When damage happened in the Gateway)
        $Rest->post('/admin/update-gateway-id-recover',array($AdminCtr, 'updateGatewayIdRecover'));
        
    //Soft Delete device data between two dates
        $Rest->post('/admin/soft-delete-device-data-to-from',array($AdminCtr, 'softDelDeviceToFrom'));   

    // Get COIN Detail
        $Rest->get('/admin/coin-detail/:coinMacAddr',array($AdminCtr,'getCoinDetail'));     

    // Upadate COIN MAC: recovery of COIN with new COIN(When damage happened)
    $Rest->post('/admin/update-coin-device-mac-address',array($AdminCtr, 'updateCoinDeviceMacAddr'));    

    // Set COIN Size type whether COIN is Pro/Cell
        $Rest->post('/admin/set-coin-type-size',array($AdminCtr, 'setCoinTypeSize'));

    // Request for Erase Ethernet Gateway Data
        $Rest->post('/admin/erase-gateway-data',array($AdminCtr, 'eraseGatewayData'));           
    
    // Request for SENSOR TYPE
        $Rest->post('/admin/request-sensor-type',array($AdminCtr, 'setSensorTypeRequest'));

        // Move Gateway from one account to another
        // $Rest->post('/admin/update-gateway-user',array($AdminCtr, 'updateGatewayUser'));

         // Move Gateway from one account to another
         $Rest->post('/admin/update-gateway-user',array($AdminCtr, 'updateGatewayUser'));

         // check gateway user
         $Rest->get('/admin/check-gateway-user/:uid',array($AdminCtr, 'checkGatewayUser'));
 
         // DELETE gateway user
         $Rest->get('/admin/delete-gateway-user/:uid',array($AdminCtr, 'deleteGatewayUser'));
 
           // UPDATE gateway user
           $Rest->post('/admin/update-email-gateway-user',array($AdminCtr, 'updateGatewayUserN'));
 
 
           $Rest->post('/admin/update-email-gateway-userN',array($AdminCtr, 'UpdateUser_email'));

        
//end of ADMIN APIs

////*******************User APIs*********************************        
        $UserCtr = new UserController();
        //Get all Gateways
        $Rest->get('/user/gateways',array($UserCtr, 'getGateways')); //User id from Header
        //Get all Devices
        $Rest->get('/user/devices/:gatewayId/:coins/:sensors',array($UserCtr, 'getDevices'));
        //Get each device value
        $Rest->get('/user/devices/:gatewayId/:deviceId',array($UserCtr, 'getDeviceValue')); //Not using
        
        //Set threshold value
        $Rest->post('/user/threshold',array($UserCtr, 'setDeviceThreshold')); 

        //Get Current Value
        $Rest->post('/user/get-currentvalue',array($UserCtr, 'getCurrentValue')); 
        
        //Get Gateways Settings 
        $Rest->get('/user/gateway-settings',array($UserCtr, 'getGatewaySettings')); //User id from Header
        
        //Save SMS Notification Settings
        $Rest->post('/user/sms-notification',array($UserCtr, 'updateSmsNotification')); 
        
        //Save EMAIl Notification Settings
        $Rest->post('/user/email-notification',array($UserCtr, 'updateEmailNotification')); 

        //Add EMAIL Ids to Get notified
        $Rest->post('/user/notification-email-ids',array($UserCtr, 'addNotificationEmailIds')); 

        //Get Email Ids List
        $Rest->get('/user/notification-email-ids',array($UserCtr, 'getNotificationEmailIds'));         
        
        //Delete Email Id
        $Rest->delete('/user/notification-email-ids/:id',array($UserCtr, 'deleteNotificationEmailIds'));     
        
        //Add mobile number to Get notified
        $Rest->post('/user/notification-phone',array($UserCtr, 'addNotificationPhone')); 

        $Rest->post('/user/xyz-combination-change',array($UserCtr, 'xyzCombinationChange'));

        //Get mobile numbers List
        $Rest->get('/user/notification-phone',array($UserCtr, 'getNotificationPhone'));         
        
        //Delete mobile number
        $Rest->delete('/user/notification-phone/:id',array($UserCtr, 'deleteNotificationPhone'));

         //Set Stream value
        $Rest->post('/user/rate_value',array($UserCtr, 'setStream'));

         //Create User Location
        $Rest->post('/user/create-location',array($UserCtr, 'createLocation'));       

	//Get user Locations
	$Rest->get('/user/get-location', array($UserCtr, 'getLocation'));

	//Delete User Location
        $Rest->delete('/user/delete-user-location/:id',array($UserCtr, 'deleteUserLocation'));

	//Get Gateway Assigned to User Locations
	$Rest->get('/user/get-gateway-location/:locationId', array($UserCtr, 'getGatewayLocation'));

         //Add Coins on the Map
	$Rest->post('/user/add-coin', array($UserCtr, 'addCoin'));

         //Get Coins on the Map
	$Rest->get('/user/get-coin/:gatewayId', array($UserCtr, 'getCoin'));

         //Get Coins associated with gateway assigned to gateway to add on the Map
	$Rest->get('/user/add-get-coin/:gatewayId', array($UserCtr, 'addGetCoin'));

         //Get Coin latitude and longitude and display alert on map
	$Rest->get('/user/render-alert/:gwId/:devId', array($UserCtr, 'renderAlert'));

	//Get help gateway and coin data
        $Rest->get('/user/help-devices/:gatewayId',array($UserCtr, 'getHelpDevices'));

	//Get help gateway and coin data
        $Rest->get('/user/help-get-device-sensor/:gatewayId/:deviceId',array($UserCtr, 'getHelpDeviceSensor'));

	//Get Coins registered to a gateway for analytics
        $Rest->get('/user/analytics-gateway-devices/:gatewayId',array($UserCtr, 'analyticsGatewayDevices'));

	//Get Coins Sensors registered to a gateway for analytics
        $Rest->get('/user/analytics-device-sensor/:gatewayId/:deviceId',array($UserCtr, 'analyticsDeviceSensor'));

	//Get Coins registered to a gateway for analytics
        $Rest->get('/user/analytics-device-sensor-data/:gatewayId/:deviceId/:deviceType1/:deviceType2',array($UserCtr, 'analyticsDeviceSensorData'));

	//Get Coin Nick Name for Breadcrumb navigation
	$Rest->get('/user/breadcrumb-nick-name/:gatewayId/:deviceId', array($UserCtr, 'breadcrumbNickName'));

	//Get analytics chart based on given date range
	$Rest->get('/user/analytics-filtered-chart-data/:gatewayId/:deviceId/:deviceType1/:deviceType2/:fromDate/:toDate',array($UserCtr, 'analyticsFilteredChartData'));

	//Update coin location on Map
	$Rest->post('/user/location-coin-update', array($UserCtr, 'locationCoinUpdate'));

	//Update coin nick name
	$Rest->post('/user/rename-coin', array($UserCtr, 'renameCoin'));	

	//Get Coin location (latitude n longitude) for map
	$Rest->get('/user/get-coin-location/:gatewayId/:deviceId', array($UserCtr, 'getCoinLocation'));        

	//Delete Coin location (latitude n longitude) for map
	$Rest->post('/user/delete-coin-location', array($UserCtr, 'deleteCoinLocation')); 

	//add an event log
	$Rest->post('/user/event-add-log', array($UserCtr, 'eventAddLog'));

	//Get events of a Location
	$Rest->get('/user/get-event-logs/:locationId', array($UserCtr, 'getEventLogs'));

	$Rest->get('/user/device-settings/:gatewayId',array($UserCtr, 'getDeviceSettings'));

	$Rest->post('/user/sensor-active',array($UserCtr, 'updateSensorActive'));

	$Rest->get('/user/analytics-acc-stream/:gatewayId/:deviceId/:deviceType',array($UserCtr, 'analyticsAccStream'));

	
	$Rest->get('/user/analytics-stream-filtered/:gatewayId/:deviceId/:deviceType1/:startTime/:endTime',array($UserCtr, 'analyticsStreamFilteredData'));

	$Rest->post('/user/update-request-action',array($UserCtr, 'updateRequestAction'));


	//Edit or Update User Location
        $Rest->post('/user/edit-user-location',array($UserCtr, 'editUserLocation'));  

	//Set coin transmission power
	$Rest->post('/user/set-coin-power',array($UserCtr, 'setCoinTransmissionPower')); 

	//Get all Devices
        $Rest->get('/user/get-acc-stream-devices/:gatewayId/:coins',array($UserCtr, 'getAccStreamDevices'));

        $Rest->get('/user/get-pred-stream-devices/:gatewayId/:coins',array($UserCtr, 'getPredStreamDevices'));

	$Rest->get('/user/get-general-settings',array($UserCtr, 'getGeneralSettings')); //User id from Header
	$Rest->post('/user/update-general-settings',array($UserCtr, 'updateGeneralSettings'));

        $Rest->get('/user/get-acc-types-devices/:gatewayId/:coins/:sensors',array($UserCtr, 'getAcceTypesDevices'));


	//Add Logo
        $Rest->post('/user/add-logo',array($UserCtr, 'addLogo'));

	//Set Detection Period
        $Rest->post('/user/set-detection',array($UserCtr, 'setDetectionPeriod'));

	//Set coin frequency
	$Rest->post('/user/set-coin-frequency',array($UserCtr, 'setCoinFrequency'));

	//Set coin Stream Threshold
	$Rest->post('/user/set-stream-threshold',array($UserCtr, 'setCoinStreamThreshold'));

	//Save EMAIl Notification Settings
        $Rest->post('/user/device-email-notification',array($UserCtr, 'updateDeviceEmailNotification')); 

	//Sensors to show on dashboard
        $Rest->post('/user/update-shown-sensors',array($UserCtr, 'updateShownSensors'));

	//Daily Report Generation - settings
        $Rest->post('/user/update-generate-report',array($UserCtr, 'updateGenerateReport'));

	//Daily Report - email
        $Rest->get('/user/daily-report-data/:userId/:gatewayId/:deviceId/:sensor/:startTime/:endTime',array($UserCtr, 'getDailyReportData'));

    // Mail Restriction
        $Rest->post('/user/update-mail-restriction',array($UserCtr, 'updateMailRestriction'));  

    // FFT: Get data from devices_stream table
    $Rest->get('/user/get-fft-base-data/:gatewayId/:deviceId/:sensor_axis/:frequency',array($UserCtr, 'getFFTbaseData'));

    // Filtered FFT: Get data as per filtered tools
    $Rest->get('/user/get-filtered-fft-base-data/:gatewayId/:deviceId/:sensor_axisSelected/:startTime/:endTime/:frequency',array($UserCtr, 'getFilteredFFTbaseData'));

    //Get All Low Battery COINs of a  Gateways
    $Rest->get('/user/devices-low-battery',array($UserCtr, 'devicesLowBattery')); //User id from Header
    
    // Update Password from User side
    $Rest->post('/user/update-new-password',array($UserCtr, 'updateNewPassword'));

    // FFT: Get FFT Frequency of perticular Date
    $Rest->get('/user/fft-filtered-date-frequency/:gateway_id/:device_id/:selectedfilter_date',array($UserCtr, 'getfftFilteredDatefrequency'));
    
    // Update Password from User side
    $Rest->post('/user/update-gateway-nickname',array($UserCtr, 'updateGatewayNickName'));

    // Get Gateway Detail
    $Rest->get('/user/gateway-detail/:gatewayId',array($AdminCtr,'getGatewayDetail'));
    
    // Update Coin Location from User side
    $Rest->post('/user/coin-location',array($UserCtr, 'updateCoinLocation'));

    /* Spectrum Stream */
    $Rest->get('/user/analytics-pred-stream/:gatewayId/:deviceId/:deviceType',array($UserCtr, 'analyticsPredStream'));
    $Rest->get('/user/analytics-pred-stream-filtered/:gatewayId/:deviceId/:deviceType1/:startTime/:endTime',array($UserCtr, 'analyticsPredStreamFilteredData'));

    
//-----------******** APP APIs  ***********----------------
        $AppCtr   =   new AppController();
        //To login User
        $Rest->post('/app/login',array($AppCtr, 'loginUser'));         
        //Forgot Password
        $Rest->post('/app/forgotpassword',array($AppCtr, 'forgotPassword'));         
        //Change Password
        $Rest->post('/app/change-password',array($AppCtr, 'changePassword'));                 
        
        //To Add User Gateways
        $Rest->post('/app/gateways',array($AppCtr, 'addGateways'));
        
        //To Get User Gateways
        $Rest->get('/app/gateways',array($AppCtr, 'getGateways'));  //removed user_id in get request at th end      

        //To Add User Devices
        $Rest->post('/app/devices',array($AppCtr, 'addDevices'));
        
        //To Get User Devices
        $Rest->get('/app/devices/:gatewayId',array($AppCtr, 'getDevices')); //removed user_id in get request at th end         

        
//------------------------------------------------------------------------------------------------

$Rest->run();
?>