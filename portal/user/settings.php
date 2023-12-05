<?php 
//1 error shows
//0 supresses

//error_reporting(E_ALL);
//ini_set("display_errors", 0);
//ob_start();
include_once('page_header_user1.php');

//session_start();

$status_msg  =  '';
$id  =   0;

//print_r($_SESSION);exit();
?>
<!--<link rel="stylesheet" href="css/jquery-ui.css">-->

<!-- <div class="col-lg-10 pad0 ful_sec"> 
      	<div class="row pad0">-->
        <div class="content userpanel">

<?php 
    include './user_menu.php';
?>
<style>
.table {
    width: 48%;
    float: left;
    margin-right: 22px;
    margin-bottom:35px !important;
}
ul.b {
    list-style-type: square;
    margin-top: -22px;
    margin-left: 25px;
}
.infos{
	width:60%;
}
@media only screen and (min-width: 200px) and (max-width:900px){
.table{
	width:100%;
}
}
</style>
<div class="container">
	<div class="col-sm-12 col-12 mains">

	
            <h1>Settings</h1><hr>
                <!--<h3 id="">Devices</h3>-->
            <div class="detail-content" style="background-color: #fff;">

                <!-- -->
                
                <div class="lp-det" style="margin-top:30px">
                    <div class="alert alert-info alert-dismissable infos">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>NOTE</strong>
			<ul class="b">
  				<li>Email notification for stream based sensors is available on device settings page</li>
  			</ul> 
				 
                    </div>
                        <span class='error-tab'></span>
                        
                        <div class=" gatewaySettingsTables">
                            
                            <div class="table-responsive">
<!--                            <table class="table table-hover table-bordered table-lp" style="width: 50%;">   
                                <tbody>
                                    <tr>
                                        <th colspan="8" style="font-size: 15px;">Gateway - 32429DHJSDKJS</th>
                                    </tr>    
                                    <tr class="active tb-hd">        
                                        <th>Sensor Type</th>        
                                        <th>SMS</th>        
                                        <th>EMAIL</th>       

                                    </tr>    
                                </tbody>
                                <tbody class="users gateways">
                                    <tr>
                                        <td>Accelerometer</td>
                                        <td><input type="checkbox" name="sms" class="sms" data-sensor-type="Accelerometer" data-gateway-id=""/></td>
                                        <td><input type="checkbox" name="email" class="email" data-sensor-type="Accelerometer" data-gateway-id=""/></td>                                     
                                    </tr>
                                    <tr>
                                        <td>Gyro Sensor</td>
                                        <td><input type="checkbox" name="sms" class="sms" data-sensor-type="Gyrosensor" data-gateway-id=""/></td>
                                        <td><input type="checkbox" name="email" class="email" data-sensor-type="Gyrosensor" data-gateway-id=""/></td>                                     
                                    </tr>
                                    <tr>
                                        <td>Temperature</td>
                                        <td><input type="checkbox" name="sms" class="sms" data-sensor-type="Temperature" data-gateway-id=""/></td>
                                        <td><input type="checkbox" name="email" class="email" data-sensor-type="Temperature" data-gateway-id=""/></td>                                     
                                    </tr>
                                    <tr>
                                        <td>Humidity</td>
                                        <td><input type="checkbox" name="sms" class="sms" data-sensor-type="Humidity" data-gateway-id=""/></td>
                                        <td><input type="checkbox" name="email" class="email" data-sensor-type="Humidity" data-gateway-id=""/></td>                                     
                                    </tr>
                                </tbody>
                            </table>-->
                            </div>
                            <div id='loader'><img src="../img/loader.gif"/> Loading Data</div>
                        </div>
    
                </div>                
         
            </div>
        </div>

        </div>
      </div>

 
<?php
    include_once('page_footer_user.php');
?>

<script type="application/javascript">
    getGatewaySettings();
</script>


 