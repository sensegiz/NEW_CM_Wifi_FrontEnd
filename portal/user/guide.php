<?php
include_once('page_header_user.php');
?>

 <!--<div class="col-lg-10 pad0 ful_sec"> 
      	<div class="row pad0">-->
        <div class="content userpanel">
        	
<?php 
    include './user_menu.php';
?>

<!--<link href="../css/bootstrap.min.css" rel="stylesheet">-->
<script src="../js/bootstrap.min.js"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>





<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
.img-responsive, .thumbnail > img, .thumbnail a > img, .carousel-inner > .item > img, .carousel-inner > .item > a > img {

    display: inline-block;
    width: 100% \9;
    max-width: 100%;
    height: auto;

}

@media only screen and (min-width: 560px) and (max-width:768px){
[class*="col-"] {

    width: 100%;

}

}
</style>
	<div class="container-fluid">
	<div class="col-10 col-sm-10 mains">
	<!--toggle sidebar button-->
           <p class="visible-xs">
            <button type="button" class="btn btn-default btn-sm" id="more" id="menu-toggle" data-toggle="offcanvas" style="margin-bottom:10px"><span  class="glyphicon glyphicon-chevron-left"></span>  </button>
          </p>
        <h1>Guide</h1><hr>
	    <div class="mine"></div>
                <!--<h3 id="">Devices</h3>-->
            <div class="detail-content" style="margin-top:0px; background-color: #fff;">

                <!-- -->
                
            <div class="lp-det" style="margin-top:0px; text-align: center;">
                    
            <span class='error-tab'></span>
  
			<div class="row" id="genset" style="">
				<h2>General Settings</h2><br>
			<div class="col-sm-6" style="text-align: left;">			
				<p><b>You can set the format in which the date is to be seen on the dashboard.</b></p>
<hr>

				<p>The date can be seen in the following 2 formats: </p> 
				<p> 1) DD-MM-YYYY HH:mm:ss </p>
				<p> 2) MM-DD-YYYY HH:mm:ss </p>

				
			</div>
			
				<a><img class="img-responsive sa" src="https://cm-testing.sensegiz.com/sensegiz-dev/portal/user/how-to-docs/public/img/general-settings.png"  alt="gateway-list"  style="border:1px solid blue;margin-left:0px;float:right"/></a>
			

			
		</div>
<hr>
		<div class="row" id="dash" style="">
				<h2>How to use the COIN dashboard</h2><br>
			<div class="col-sm-6" style="text-align: left;">			
				<h4>The dashboard has two sections.</h4>
				<p>On the left, below the SenseGiz logo is a list of the Gateways registered to the user, displayed in black.</p>
				<p>Click on any gateway to view COIN data on the right.</p>
<hr>

				<p>On the right(screenshot below), the table view of different sensors of each COIN registered to a particular gateway.</p>
				<p>The sensor data is categorized in different tables as accelerometer, gyroscope, temperature, humidity and streaming.</p>
			</div>
				<div class="col-sm-6">
				<h4 style="margin-right:107px;float:right">Screenshot</h4></div>
				<a><img class="img-responsive" src="https://cm-testing.sensegiz.com/sensegiz-dev/portal/user/how-to-docs/public/img/how-to-gateway.png" alt="gateway-list" style="border:1px solid blue;margin-bottom:20px;margin-right:57px;float:right"/></a>
			


				<div class="container-fluid">
				<img src="https://cm-testing.sensegiz.com/sensegiz-dev/portal/user/how-to-docs/public/img/how-to-devices.png" class="img-responsive"  alt="gateway-list" style="border:1px solid blue;float:left;margin-inline:0px"/>
			</div>
			
		</div>

		<div class="row" id="data">
			<h2>COIN Data</h2>
			<h4>What does each row on the COIN dashboard mean?</h4>
			<p>The columns:</p><br>
			<p><div class="container-fluid"><img class="img-responsive" src="https://cm-testing.sensegiz.com/sensegiz-dev/portal/user/how-to-docs/public/img/how-to-coin-data.png" alt="coin-table-header" style="border:1px solid blue;width:100%;float:left"/></div></p>
			<hr>
			<ol style="text-align: left;">
				<li>Sl.No: A serial number for reference.</li>
				<li>Nick Name: The nick name given to the COIN during registration. 
				Example: If you have placed COIN 1 in your Living Room, you can nick name it Living Room for easy understanding.</li>
				<li>Threshold: This refers to the LOW or HIGH threshold value of the particular sensor.</li>
				<li>Value: This is the current/last updated value of the sensor.</li>
				<li>Get Current Value: Click this button to retrieve the specific COIN sensor's current value. </li>
				<li>Last Updated: Date and time of the latest data received from the COIN.</li>
				<li>Set Threshold: Set threshold for the specific COIN sensor. </li>
				<li>Set Stream: Find out how to set the streaming interval. </li>
			</ol>

		</div>
<hr>
		<div class="row" id="get">
			<h2>How to Get the Latest COIN Sensor Value</h2>
			<p>Each COIN sensor data can be retrieved using the GET button</p><br>

			<p><div class="col-md-6"><img class="img-responsive"src="https://cm-testing.sensegiz.com/sensegiz-dev/portal/user/how-to-docs/public/img/how-to-get-current-value-1.png" alt="get-current-value" style="border:1px solid blue;float:left;margin-bottom:10px"/></div></p>

			<br><p>Once the COIN responds back with the latest value, it can be viewed on the table with the latest value and timestamp and is also updated on the Recent Updates Windows.</p>
	
			<br><p><img class="img-responsive" src="https://cm-testing.sensegiz.com/sensegiz-dev/portal/user/how-to-docs/public/img/how-to-get-current-value-2.png" alt="current-value-on-recent-updates-ticker" style="border:1px solid blue;"/></p>
		</div>
<hr>
		<div class="row" id="threshold">
			<h2>Threshold</h2>
			<p>Let us take a look at what LOW and HIGH represent and how their behavior affects the functionality of the dashboard.</p>
			<p><strong>Each sensor has a LOW threshold and a HIGH threshold.</strong></p><br>
			<p><div class="col-md-6"><img class="img-responsive" src="https://cm-testing.sensegiz.com/sensegiz-dev/portal/user/how-to-docs/public/img/how-to-set-threshold.png" alt="set-threshold" style="border:1px solid blue;float:left;margin-bottom:10px"/></div></p><br>
			<div class="container-fluid">
			<div style="text-align:left;">
			<p><strong>LOW</strong>: The COIN sensor will communicate data to the cloud dashboard whenever the sensor value drops below the LOW threshold.</p>
			<p>Example: Consider we set the LOW temperature threshold of COIN 1 to 20 degrees.The COIN will communicate data to the cloud if the COIN senses a temperature below 20 degrees.</p>
			<p><strong>High</strong>: The COIN sensor will communicate data to the cloud dashboard whenever the sensor value goes above the HIGH threshold.</p>
			<p>Example: Consider we set the HIGH temperature threshold of COIN 1 to 30 degrees. Here, the COIN will communicate data to the cloud if the COIN senses a temperature above 30 degrees.</p>
			<p><strong>When SMS and Email alerts are enabled, the cloud sends emails and SMS when the COIN sends data regarding threshold changes for each sensor based on user settings.</strong></p></div>
			

			<br>
			<p><strong>To set the threshold for each Sensor, use the following guidelines:</strong></p>
			<p>ACCELEROMETER : The range for setting the accelerometer threshold is 0.001 to 15.875(The value can be selected from the dropdown given on the activity dashboard.)</p> 
				
			<p>GYROSCOPE : The range for setting the gyroscope threshold is 10 to 1990. The value should be a multiple of 10.</p>
			<p>TEMPERATURE : The range for setting the temperature threshold is -39 to 124 (in degree celcius). The value should be an integer.</p>
			<p>HUMIDITY : The range for setting the humidity threshold is 2 to 99. The value should be a whole number.</p>

		</div>
			</div>
<hr>

		<div class="row" id="stream">
			<h2>Streaming</h2>
			<p>Each COIN can stream temperature, humidity and accelerometer data as per custom user settings</p>
			<p>Streaming rate can be set using the set stream option on the dashboard in the stream Table.</p><br>
			<p><div class="container-fluid"><img class="img-responsive" src="https://cm-testing.sensegiz.com/sensegiz-dev/portal/user/how-to-docs/public/img/get-stream.png" alt="get-stream" style="float:left"/></div></p><br>

			<p> You can also see the acceleration (in meters/second square), velocity (in m/s) and displacement (in m) values in X, y and z-axis.</p>

			<p><div class="container-fluid"><img class="img-responsive" src="https://cm-testing.sensegiz.com/sensegiz-dev/portal/user/how-to-docs/public/img/acceleration-types.png" alt="acceleration-types" style="float:left" /></div></p><br>
			<p>Streaming rate can be set in either Seconds, Minutes or Hours.</p>
			<p> <div class="container-fluid"><img class="img-responsive" src="https://cm-testing.sensegiz.com/sensegiz-dev/portal/user/how-to-docs/public/img/how-to-set-stream.png" alt="set-stream" /></div></p><br>

		</div>
<hr>
		<div class="container-fluid" id="range">
			<h2> Range of Values supported by COIN sensors<h2>
			<div class="table-responsive" style="width:95%">
			<table>
			 <tr>
			    <th>Sensor</th>
			    <th>Minimum Value</th>
			    <th>Maximum</th>
			  </tr>
			  <tr>
			    <td>Accelerometer</td>
			    <td>0.001 G</td>
			    <td>16 G</td>
			  </tr>
			  <tr>
			    <td>Gyroscope</td>
			    <td>1 RMS</td>
			    <td>2000 RMS</td>
			  </tr>
			  <tr>
			    <td>Temperature</td>
			    <td>-40 &#x2103;</td>
			    <td>125 &#x2103;</td>
			  </tr>
			  <tr>
			    <td>Humidity</td>
			    <td>1 %</td>
			    <td>100%</td>
			  </tr>
			</table></div>
		</div>
<hr>
		<div class="row" id="analytics">
			<h2>Analytics</h2>
			<p>You can view the graphical representation of the values received for each sernsor of a particular coin.</p>
			<p><strong>Graph shows the LOW threshold, HIGH threshold and the device value of that sensor.</strong></p><br>
			<p><div class="col-12 col-sm-12"><img class="img-responsive" src="https://cm-testing.sensegiz.com/sensegiz-dev/portal/user/how-to-docs/public/img/analytics.png" alt="analytics" style="border:1px solid blue;float:left;margin-bottom:14px;margin-left:13px"/></div></p><br>						
			<p><strong>You can also disable or enable a sensor for a particular coin. If a sensor is disabled, the coin will stop sending the values of that particular sensor.</strong></p>
			<p>Example: The below image shows that Acceleromter, Gyroscope and Humidty of a Coin is enabled i.e., only these 3 sensor values will be received from that particular coin.</p><br>
			<p><img class="img-responsive"src="https://cm-testing.sensegiz.com/sensegiz-dev/portal/user/how-to-docs/public/img/sensor-settings.png" alt="sensor-settings" style="border:1px solid blue;"/></p><br>						

		</div>
<hr>
		<div class="row" id="maps">
			<h2>Map Center</h2>
			<p>You can visualize COIN activities on virtual location maps. All you need to do is upload the floor plan image and then drag and drop the markers to point to the physical location of the COIN.</p>			
			<p><div class="col-12 col-sm-12"><img class="img-responsive" src="https://cm-testing.sensegiz.com/sensegiz-dev/portal/user/how-to-docs/public/img/map-editor.png" alt="map-editor" style="float:left;margin-left:5px;margin-bottom:10px"/></div></p><br>
			<p>Monitoring page shows the real time alerts on the indoor map. You can also log the event for furure reference.</p><br>
			<p><img class="img-responsive" src="https://cm-testing.sensegiz.com/sensegiz-dev/portal/user/how-to-docs/public/img/map-alert.png" alt="map-alert"style="float:left;margin-left:21px" /></p><br>
		</div>
<hr>

</div>
</div>
</div>
</div>
</div>
</div>

<?php
    include_once('page_footer_user.php');
?>

<script type="text/javascript">
	var html = '<li><a href="#genset">General Settings</a></li><li><a href="#dash">Dashboard</a></li><li><a href="#data">Decoding COIN data</a></li><li><a href="#get">Get Latest Value</a></li><li><a href="#threshold">Threshold</a></li><li><a href="#stream">Streaming</a></li><li><a href="#range">COIN sensor value range</a></li><li><a href="#analytics">Analytics</a></li><li><a href="#maps">Map Center</a></li>';
	$('.gateway-list-lfnav').html(html);

</script>