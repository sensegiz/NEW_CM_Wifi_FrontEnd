<?php 
include_once('page_header_user1.php');
?>

<!--<div class="col-lg-10 pad0 ful_sec"> 
        <div class="row pad0">-->
        	<div class="content userpanel">

  <script  type="text/javascript" src="../js/jquery.min.js"></script>




   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="../js/Chart.js"></script>

<script src="https://momentjs.com/downloads/moment.js"></script>
	
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
<?php 


	include './user_menu.php';
 
?>

<style>
.form-control .short {
    display: block;
    width: 25% !important;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555; 
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}

.form-control {
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: 
#555;
background-color:
#fff;
background-image: none;
border: 1px solid
#ccc;
border-radius: 4px;
-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
box-shadow: inset 0 1px 1px
    rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
.table{
	width:100%;
	font-size:14px;
}
@media only screen and (min-width: 1000px) and (max-width:1205px){
.col-sm-3 {
    width: 31%;
}
.col-sm-4 {
    width: 37.33%;
}
.col-sm-1 {
    width: 19.33%;
}
}
@media only screen and (min-width: 768px) and (max-width:1000px){
.col-sm-3 {
    width: 38%;
    margin-right: 25px;
}
.col-sm-4 {
    width: 48.33%;
    margin-left: 27px;
}
.col-sm-1 {
    width: 14.33%;
}
.col-sm-5 {
    width: 46.66%;
}
.form-control {
    display: block;
    width: 100%  !important;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
.starttime{
  width:100% !important;
  float:right;
  margin-right:0px;
}

}
@media only screen and (min-width: 560px) and (max-width:767px){
.form-control {
    display: block;
    width: 100% !important;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
.starttime{
  width:100% !important;
  float:right;
  margin-right:0px;
}

.col-sm-3 {
    width: 48%;
    
}
.col-sm-4 {
    width: 50.33%;
    
}
.col-sm-1 {
    width: 14.33%;
}
.col-sm-5 {
    width: 51.66%;
}
[class*="col-"] {


    float: left;

}

}

.a{
	float:right;
}

.bootstrap-datetimepicker-widget {
    top: 0;
    left: 10;
    width: 50px !important;
    padding: 5px;
    margin-top: 1px;
    z-index: 99999!important;
    border-radius: 4px;
    height: 195px;
    font-size:10px;
} 
.bootstrap-datetimepicker-widget table {
    width: 100%;
    margin: 0;
    margin-top: -8px;
   font-size:12px;

}
.bootstrap-datetimepicker-widget>ul {
    list-style-type: none;
    margin: 0;
   // width: 3px;
  //  height: 150px;
}
.bootstrap-datetimepicker-widget td span { 
    display: inline-block;
    width: 68px;
    height: 50px;
    line-height: 46px;
    margin: 2px 1.5px;
    cursor: pointer;
    border-radius: 4px;
}
 
.starttime{
  width:30%;
  float:right;
  margin-right:28px;
}

/* Remove counter from input type='number' */
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0; 
}
.card {
  box-shadow: 0 4px 10px 0 rgba(0,0,0,0.2);
  transition: 0.3s;
  //width: 33%;
  border-radius: 5px;
  padding:20px;
}
.column {
    float: left;
    width: 33%;
    padding: 10px 10px;
}
.column1 {
    float: left;
    width: 40.5%;
    padding: 10px 10px;
}
  .form-inline  .form-control {

    display: inline-block;
    width: 106px;
    vertical-align: middle;
}
@media (min-width:300px) and (max-width:730px) {
 .column1, .column {
    width: 100%;
    display: block;
    margin-bottom: 20px; 
  }
#short, #daily_report{ 
    display: inline-block;
    width: 100%;
    vertical-align: middle;
}
}
@media (min-width:731px) and (max-width:1308px) {
 .column1, .column { 
    width: 50%;
    display: block;
    margin-bottom: 20px;  
  }
}
</style>


     <script type="text/javascript"> 
            $(function () {
                $('#datetimepicker').datetimepicker({
                    format: 'HH:mm'
                });

		 $('#schdatetimepicker').datetimepicker({
                    format: 'HH:mm'
                });
            });
        </script>
   <script>
  function toggleOn() {
    $('#toggle-trigger').bootstrapToggle('on')
  }
  function toggleOff() {
    $('#toggle-trigger').bootstrapToggle('off')  
  }

 // $(".time-interval-tip").tooltip();
 </script>
		<div class="container">
			<div class="col-12 col-sm-12 mains">

			<h1>General Settings</h1><hr>
			<div class="detail-content" style="background-color: #fff;">
                		
                		<div class="lp-det" style="margin-top:30px">
					
                			<span class='error'></span>
                			<span class='success'></span>
					
							<form class="form-horizontal">
								
								<div class="column">
								<div class="card">
									<!--<div class="col-sm-4" style="margin-bottom:15px">-->
										<label for="sensors">Sensors shown on activity dashboard:</label>
										  <table class="table table-bordered checkboxes">
										
										</table>
										
									</div>
									</div>
									<div class="column">
				 					<div class="card"style="padding-bottom:42px">
								
										<label for="date_format">Select your preferred Date Format:</label> <br/><br/>
 
	 									<select class="form-control date_format" id="date_format"style="margin-bottom:25px" >
	 	   							
										</select>
										<br><br> 
										<button type="button" class="btn btn-primary dateformat">Update Date Format</button>
									</div>
								</div>
 
								
						<div class="column">
							<div class="card"style="margin-bottom:20px;padding-bottom:41px">							
 							
									<label for="temp_unit">Select unit  for temperature:</label> <br/><br/>

 									<select class="form-control temp_unit" id="temp_unit"style="margin-bottom:23px"  >
 	   							
									</select>
									<br> <br>  
									<button type="button" class="btn btn-primary tempunit">Update Temperature Unit</button>
								</div></div>
									</form>								
						<div class="col-sm-12"><span class="success1"></span></div>		
						<div class="column">
			 				<div class="card"style="padding-bottom:22px">	
								
									<label for="rms_values">Select your preferred setting for Velocity and Displacement:</label> <br><br/><br/>

	 								<select class="form-control rms_values" id="rms_values" style="margin-bottom:33px" >
	 	   							
									</select>
									<br> <br> <br>
									<button type="button" class="btn btn-primary rmsvalues">Update RMS setting</button>
								</div></div>
							</div>
							<div class="column">
			 				<div class="card"style="margin-bottom:20px;padding-bottom:22px">	
 
									<label for="logo">Upload your own logo:</label> <br/><br> <br> <br/>

									<input type="file" id="file" name="file" />
									<p style="margin-bottom:25px !important" >* Only .jpeg .png .jpg file formats accepted.</p><br>
									<br> <br> 															
									<button type="button" class="btn btn-primary logo">Upload Logo</button>       							
								</div>								 				
							
								
							</div>
						<!-- Start Mail Retriction  -->
							<div class="column">
							<div class="card"style="margin-bottom:20px">	
									<form class="form-inline ">  
									<label for="mail_restriction">Set Mail Restriction:</label>&nbsp;<span class='glyphicon glyphicon-info-sign time-interval-tip' title="Restriction on the number of alert emails received per day for streaming (Temperature Stream, Humidity Stream and Accelerometer Stream)."></span> <br/>
									
																					<label for="mail_restriction"style="margin-right:44px;">Enable/Disable:</label>
											
											<select class="form-control  mail_restriction" id="mail_restriction" name="mail_restriction" >
												<!-- <option value="N">Disable</option>
												<option value="Y">Enable</option> -->
											</select><br>
										
										
											<br><label for="txtlimitMail" >Set Mail Limit:</label>
											<span class='glyphicon glyphicon-info-sign time-interval-tip' title="No. of emails received per day per coin." style="margin-right:38px;"></span>
											<input type="number" class="form-control  txtlimitMail" name="txtlimitMail" id="txtlimitMail"   placeholder="Default 3 Set"><br>
											<small>By default mail limit set to 3</small>
										
										<br><br>
											<label for="txtMailTimeInt">Time Interval (min): </label>
											<span class='glyphicon glyphicon-info-sign time-interval-tip' title="The time gap between 2 emails in case the threshold is crossing everytime for a stream value." style="margin-left:5px;"></span>
											<select class="form-control  txtMailTimeInt" id="txtMailTimeInt" name="txtMailTimeInt" >
												<option value="3">3</option>
												<option value="5">5</option>
												<option value="10">10</option> 
												<option value="15">15</option>
												<option value="20">20</option>
												<option value="25">25</option>
												<option value="30">30</option> 
												<option value="35">35</option>
												<option value="40">40</option>
						 						<option value="45">45</option>
												<option value="50">50</option>
												<option value="55">55</option>
												<option value="60">60</option>
											</select>
											<br><small>Default time interval set to 5</small>
										</form>
								
									<button type="button" class="btn btn-primary mail_restrictionUpdate">Update Mail Restriction</button>
								</div>
							</div>
							<!-- Ends Mail Restriction -->
							
							<!--New Password-->
							<div class="column">
								<div class="card"style="margin-bottom:20px">
									<form class="pure-form"> 
	  								 	<fieldset>
	    								    <label for="daily_report">Change Password</label>
	    								    <span class='glyphicon glyphicon-info-sign time-interval-tip' title="Update/Change Existing Password"></span>
	    								    <br><br>
											<!-- <input type="password"class="form-control" placeholder="Current Password"  required> -->
											<label for="password">New Password: * </label>
	    							    	<input type="password" class="form-control" placeholder="Enter New Password *" id="password" name="password" required> <br />
	    							    	<label for="confirm_password">Confirm New Password: * </label>
	      							    	<input type="password" class="form-control" placeholder="Re-Confirm Password *" id="confirm_password" name="confirm_password" required><br/>
	   							     		<button type="submit" class="btn btn-primary btn-md update_password">Update</button>
	 							   		</fieldset>
									</form>
								</div>
							</div>
							<!-- end -->

							<!--Update Gateway Nick Name -->
							<div class="column">
								<div class="card"style="margin-bottom:20px">
									<form class="pure-form"> 
	  								 	<fieldset>
	    								    <label for="daily_report">Update Gateway Nick Name:</label>
	    								    <br><br>
											<label for="txtGatewaySelect">Select Gateway: </label>
											<select class="form-control  txtGatewaySelect" id="txtGatewaySelect" name="txtGatewaySelect" style="margin-left:8px;">
												<option value='0'>Select Gateway</option>
											</select><br />
	    							    	<label for="gnick_name">Gateway Nick Name: * </label>
	      							    	<input type="text" class="form-control" placeholder="Nick Name" id="gnick_name" name="gnick_name" required><br/>
	   							     		<button type="submit" class="btn btn-primary btn-md update_gnickname">Update</button>
	 							   		</fieldset>
									</form>
								</div>
							</div>

							<div class="column">
					<div class="card" style="margin-bottom:20px">
						<form class="pure-form">
							<fieldset>
								<label for="daily_report">Set Whatsapp Alert Time:</label>
								<br><br>

								<label for="start_time">Start Time:</label>
								<input type="time" id="start_time" name="start_time">
								<br>

								<label for="end_time">End Time:</label>
								<input type="time" id="end_time" name="end_time">
								<br>

								<button type="submit"
									class="btn btn-primary btn-md submit_whatapp_alert_time">Submit</button>
							</fieldset>
						</form>
					</div>
				</div>
							<!-- end -->

								<div class="column1">
							<div class="card"style="margin-bottom:20px">	
										<span class='drerror'></span>
	                        			<span class='drsuccess'></span>					
							
										<label for="daily_report">Daily Report Generation:</label>
											 
										<div class="btn-group btn-toggle toggle"> 
	  									  
										</div><br/><br/>
										<label>Sensors:</label>
							 			<div class="table-responsive">
											<table class="table table-borderless dailyreportcheckbox">
  											 

											</table>
										</div>	<br/>							
										<form class="form-inline">   
										          							
											<b>Duration:&nbsp;&nbsp;</b>
											<select class="form-control short daily_report" id="daily_report" id="short" >
			 	   								<option value="0" >Select</option>
			 	   								<option value="1" >1 Day</option>
			 	   								<option value="2" >2 Days</option>
							 					<option value="3" >3 Days</option>
												<option value="7" >7 Days</option>
				 							</select>
								             
										  	<b style="margin-left:18px">Start Time:</b> 
										  	<div class='input-group date' id='datetimepicker'style="width:100px">
              									<input type='text' class="form-control short picker" id="short" />
               									<span class="input-group-addon">
                    				 				<span class="glyphicon glyphicon-time"></span>
           										</span>
	            							</div></br></br><br/>	
											<b>Schedule Report Time:</b> 
											<div class='input-group date' id='schdatetimepicker'style="width:100px">
	          									<input type='text' class="form-control short schpicker" id="short"/>
	           									<span class="input-group-addon">
	                								<span class="glyphicon glyphicon-time"></span>
	       										</span>
	            							</div>	
			 	 							
										</form>
										
										<button type="button" class="btn btn-primary dailyreport" style="margin:8px;">Update Report Details</button>
										<button type="button" class="btn btn-primary dailyreportnow"  style="margin-left:8px;">Generate now</button>
									</div>
								</div> 
								<br><br>

							

							<br /><br />
							

					<div id="dynamicCanvas"></div>    						
					

			</div>
		</div>

        </div>
	
</div>

<?php
    include_once('page_footer_user.php');
?>

<script type="text/javascript">

var myLineChart = null;

getGeneralSettings();

var basePathUser      =   getBasePathUser();
var apiUrlAddLogo = "add-logo";
var apiUrlUpdateShownSensors = "update-shown-sensors";
var apiUrlUpdateGenerateReport = "update-generate-report";


var apiUrlDailyReportData = 'daily-report-data';
var apiUrlGetGeneralSettings = 'get-general-settings';
var apiUrlGateways            = "gateways";
var apiUrlAnalyticsGatewayDevices= 'analytics-gateway-devices';

var apiUrlUpdateMailRestriction = "update-mail-restriction";

var apiUrlUpdateGatewayNickName = "update-gateway-nickname";


var selected_date_format = $(".date_format :selected").val();
var selected_rms_values = $(".rms_values :selected").val();
var selected_temp_unit = $(".temp_unit :selected").val();

var apiUrlUpdateNewPassword = "update-new-password";
var apiUrlUpdateWhatsappAlertTime = "update-whatsapp-alert-time";

$(document).on("click", ".dateformat", function(e){

	var date_format = $(".date_format :selected").val();
	console.log(date_format);

	
	if(selected_date_format == date_format){
		info_html='The preferred date format is already set to ' + date_format;	
		//info_html = '<div class="alert alert-info alert-dismissable col-sm-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> The preferred date format is already set to ' + date_format + '</div>';
		$('.success').html(info_html);
		setTimeout(function(){  $('.success').html(''); }, 5000);
	}else{
		updateGeneralSettings(date_format,'', '');
		window.location.reload();
	}
	
});

$(document).on("click", ".rmsvalues", function(e){

	var rms_values = $(".rms_values :selected").val();

	
	if(selected_rms_values == rms_values){
		info_html='The preferred RMS setting is already set to ' + rms_values;	
		
		$('.success').html(info_html);
		setTimeout(function(){  $('.success').html(''); }, 5000);
	}else{
		updateGeneralSettings('', rms_values, '');
		window.location.reload();
	}
	
});

$(document).on("click", ".logo", function(e){

	var uid     =  $('#sesval').data('uid');
	var apikey  =  $('#sesval').data('key');

	var logo  =  $('#file').val();
	var file_extn = logo.substr(logo.lastIndexOf('.') + 1 ).toUpperCase();	 	

	if(logo ==''){
		$('.error').html('*Please select file for logo.');
		$('#file').focus();
		return false;
        }

	
	if(file_extn != 'JPG' && file_extn != 'JPEG' && file_extn != 'PNG'){
		$('.error').html('Only jpeg .png .jpg file formats are accepted.');
                $('#file').focus();
                return false;
	}


	var file_data = $('#file').prop('files')[0];
	var file_n = logo.substring(logo.lastIndexOf('\\') + 1, logo.lastIndexOf('.'));
	file_n = file_n + '_' + uid;

	var new_file_name = file_n + '.' + file_extn.toLowerCase();

	var form_data = new FormData();
        form_data.append('file', file_data, new_file_name); 

	$('.success').html('Uploading the new logo...');

	
	$.ajax({
		url: 'logo_upload.php', // point to server-side PHP script 
		dataType: 'text', // what to expect back from the PHP script
		cache: false,
		contentType: false,
		processData: false,
		data: form_data,
		type: 'post',                    
		success: function (response) {
			var fn = response.split(":");
						
			var res = response.split('.',1)[0];

			if(res == "File already exists")
			{
				$('.loc-msg').html('');
				$('.success').html('');
				$('.error').html('*File name already exists! Please choose a different name for the file.');
                    		return false;

			}
			else{
				var l_image = fn[1];
				var postdata = {
					logo:l_image,
                            		user_id:uid                            		
				}

                        	$('#apikey').html(apikey);
                        	$('#uid').html(uid);
				$('.success').html('');
				
                        	addLogo(postdata); 
				window.location.reload();
			}			                       		                        
                    					
		},
	   	error: function (response) {
			$('.error').html(response); // display error response from the PHP script
		}
	});

});


function addLogo(postdata) {
	var uid     =  $('#sesval').data('uid');
	var apikey  =  $('#sesval').data('key');


	$.ajax({

                url: basePathUser + apiUrlAddLogo,
                type: 'POST',                       
                data: JSON.stringify(postdata),
		contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                async: false,                           
                beforeSend: function (xhr){                                                                                               
                        xhr.setRequestHeader("uid", uid);
                        xhr.setRequestHeader("Api-Key", apikey);                                  
                },
                success: function(data, textStatus, xhr) {
                
                        if(xhr.status == 200 && textStatus == 'success') {
                                if(data.status == 'success') {
					$('.error').html('');
					$('.success').html('Logo has been successfully uploaded.');
                                    	setTimeout(function(){ $('.success').html(''); }, 5000);
					
				}				
			}
               	},
		error: function(errData, status, error){
                        var resp = errData.responseJSON; 
			
                        if(errData.status==401){
                                    var resp = errData.responseJSON; 
                                    var description = resp.description; 
                                    $('.logout').click();
                                    alert(description);
                        }                            
                }
        });                                   

}

$(document).on("click", ".tempunit", function(e){

	var temp_unit = $(".temp_unit :selected").val();

	
	if(selected_temp_unit == temp_unit){
		info_html='The temperature unit is set to ' + temp_unit;	
		
		$('.success').html(info_html);
		setTimeout(function(){  $('.success').html(''); }, 5000);
	}else{
		updateGeneralSettings('', '', temp_unit);
		window.location.reload();
	}
	
});

$(document).on("click", ".sensors", function(e){

	var acc =  'N';
	var gyro =  'N';
	var temp =  'N';
	var humid =  'N';
	var stream =  'N';
	var accstream =  'N';
	var tempstream =  'N';
	var humidstream =  'N';
	var spectrumstream = 'N';

	$('input[type="checkbox"]:checked').each(function() {

		if(this.value == 'acc'){ acc = 'Y'; }
		else if(this.value == 'gyro'){ gyro = 'Y'; }
		else if(this.value == 'temp'){ temp = 'Y'; }
		else if(this.value == 'humid'){ humid = 'Y'; }
		else if(this.value == 'stream'){ stream = 'Y'; }
		else if(this.value == 'accstream'){ accstream = 'Y'; }
		else if(this.value == 'tempstream'){ tempstream = 'Y'; }
		else if(this.value == 'humidstream'){ humidstream = 'Y'; }
		else if(this.value == 'spectrumstream'){ spectrumstream = 'Y'; }
		
	});
	


	var uid     =  $('#sesval').data('uid');
	var apikey  =  $('#sesval').data('key');

	var postdata = {
			accelerometer: acc,
			gyroscope: gyro,
			temperature: temp,
			humidity: humid,
			stream: stream,
			accstream: accstream,
			temperaturestream: tempstream,
			humiditystream: humidstream,
			spectrumstream: spectrumstream
		}

	$.ajax({

                url: basePathUser + apiUrlUpdateShownSensors,
                type: 'POST',                       
                data: JSON.stringify(postdata),
		contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                async: false,                           
                beforeSend: function (xhr){                                                                                               
                        xhr.setRequestHeader("uid", uid);
                        xhr.setRequestHeader("Api-Key", apikey);                                  
                },
                success: function(data, textStatus, xhr) {
                
                        if(xhr.status == 200 && textStatus == 'success') {
                                if(data.status == 'success') {
					$('.error').html('');
					$('.success').html('Sensor List for activity dashboard has been successfully updated.');
                                    	setTimeout(function(){ $('.success').html(''); }, 5000);
					
				}	
				window.location.reload();			
			}

               	},
		error: function(errData, status, error){
                        var resp = errData.responseJSON; 
			
                        if(errData.status==401){
                                    var resp = errData.responseJSON; 
                                    var description = resp.description; 
                                    $('.logout').click();
                                    alert(description);
                        }                            
                }
        }); 
	
});


$(document).on("click", ".dailyreport, .dailyreportnow", function(e){

	var acc =  'N';
	var gyro =  'N';
	var temp =  'N';
	var humid =  'N';
	var tempstream =  'N';
	var humidstream = 'N';
	//var accstream =  'N';
	var send_report = 'N';

	var zone_offset = moment().local().format('Z');

	var myClass = $(this).attr("class");
	console.log(myClass);

	//console.log(myClass.hasClass('dailyreportnow'));

	


	$('input[type="checkbox"]:checked').each(function() {

		if(this.value == 'Accelerometer'){ acc = 'Y'; }
		else if(this.value == 'Gyroscope'){ gyro = 'Y'; }
		else if(this.value == 'Temperature'){ temp = 'Y'; }
		else if(this.value == 'Humidity'){ humid = 'Y'; }
		else if(this.value == 'TemperatureStream'){ tempstream = 'Y'; }
		else if(this.value == 'HumidityStream'){ humidstream = 'Y'; }

	});
	


	var uid     =  $('#sesval').data('uid');
	var apikey  =  $('#sesval').data('key');


	var on = $('#on').hasClass('active');

	if(on == false){
		alert("The daily report option is set to OFF. Please turn it on to receive daily reports.");	
	}else{
		send_report = 'Y';
		var report_duration = $(".daily_report :selected").val();


	if(report_duration == 0){
		return alert("Please select the report duration");
	}

	var picker = $('.picker').val();


	var start_time, end_time, send_time;

	var today = new Date();
	var dd = today.getDate();
	if(dd<10){dd='0'+dd;}
	var mm = today.getMonth()+1; 
	if(mm<10){mm='0'+mm;} 
	var yyyy = today.getFullYear();
	var todayDate = yyyy+'-'+mm+'-'+dd;

	
	var newDate = new Date();

	if(report_duration == 1){
		//sdate = edate = todayDate;

		newDate.setDate(newDate.getDate() - 1);

		var dd = newDate.getDate();
		if(dd<10){dd='0'+dd;}
		var mm = newDate.getMonth()+1; 
		if(mm<10){mm='0'+mm;}
		var yyyy = newDate.getFullYear();			
		var last1 = yyyy + "-"+ mm +"-"+ dd;
			
		sdate = last1;
		edate = todayDate;
	}
	else if(report_duration == 2){
		newDate.setDate(newDate.getDate() - 2);

		var dd = newDate.getDate();
		if(dd<10){dd='0'+dd;}
		var mm = newDate.getMonth()+1; 
		if(mm<10){mm='0'+mm;}
		var yyyy = newDate.getFullYear();			
		var last2 = yyyy + "-"+ mm +"-"+ dd;
			
		sdate = last2;
		edate = todayDate;
		

	}
	else if(report_duration == 3){
		newDate.setDate(newDate.getDate() - 3);
		var dd = newDate.getDate();
		if(dd<10){dd='0'+dd;}
		var mm = newDate.getMonth()+1; 
		if(mm<10){mm='0'+mm;}
		var yyyy = newDate.getFullYear();			
		var last3 = yyyy + "-"+ mm +"-"+ dd;
			
		sdate = last3;
		edate = todayDate;

	}
	else if(report_duration == 7){
		newDate.setDate(newDate.getDate() - 7);
		var dd = newDate.getDate();
		if(dd<10){dd='0'+dd;}
		var mm = newDate.getMonth()+1; 
		if(mm<10){mm='0'+mm;}
		var yyyy = newDate.getFullYear();			
		var last7 = yyyy + "-"+ mm +"-"+ dd;
			
		sdate = last7;
		edate = todayDate;

	}	


	if(picker != ''){
		sdate = new Date(sdate + ' ' + picker); 
		edate = new Date(edate + ' ' + picker); 
		

	 	start_time = moment(sdate).local().format('YYYY-MM-DDTHH:mm:ssZ');
		start_time = moment.utc(start_time).format('YYYY-MM-DD HH:mm:ss');

		end_time = moment(edate).local().format('YYYY-MM-DDTHH:mm:ssZ');
		end_time = moment.utc(end_time).format('YYYY-MM-DD HH:mm:ss');
	}else{

		start_time = sdate;
		end_time = edate;

		start_time = moment(start_time).local().format('YYYY-MM-DDTHH:mm:ssZ');
		start_time = moment.utc(start_time).format('YYYY-MM-DD HH:mm:ss');

		end_time = moment(end_time).local().format('YYYY-MM-DDTHH:mm:ssZ');
		end_time = moment.utc(end_time).format('YYYY-MM-DD HH:mm:ss');
	}


		
		var schpicker = $('.schpicker').val();


		if(schpicker != ''){
			newDate.setDate(newDate.getDate() - 1);

			var dd = newDate.getDate();
			if(dd<10){dd='0'+dd;}
			var mm = newDate.getMonth()+1; 
			if(mm<10){mm='0'+mm;}
			var yyyy = newDate.getFullYear();			
			var last1 = yyyy + "-"+ mm +"-"+ dd;
			
			sddate = last1;

			sddate = new Date(sddate + ' ' + schpicker); 
		

	 		send_time = moment(sddate).local().format('YYYY-MM-DDTHH:mm:ssZ');
			send_time = moment.utc(send_time).format('YYYY-MM-DD HH:mm:ss');

		}
		
	}
	
	

	var postdata = {
			accelerometer: acc,
			gyroscope: gyro,
			temperature: temp,
			humidity: humid,
			temperaturestream: tempstream,
			humiditystream: humidstream,
			starttime: start_time,
			endtime: end_time,
			sendreport: send_report,
			timezone: zone_offset,
			sendtime: send_time
		}

	$.ajax({

                url: basePathUser + apiUrlUpdateGenerateReport,
                type: 'POST',                       
                data: JSON.stringify(postdata),
		contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                async: false,                           
                beforeSend: function (xhr){                                                                                               
                        xhr.setRequestHeader("uid", uid);
                        xhr.setRequestHeader("Api-Key", apikey);                                  
                },
                success: function(data, textStatus, xhr) {
                
                        if(xhr.status == 200 && textStatus == 'success') {
                                if(data.status == 'success') {
	
					$('.drerror').html('');
					$('.drsuccess').html('Report Generation details has been successfully updated.');
                                    	setTimeout(function(){ $('.drsuccess').html(''); }, 5000);
					
				}
				//window.location.reload();				
			}

               	},
		error: function(errData, status, error){
                        var resp = errData.responseJSON; 
			
                        if(errData.status==401){
                                    var resp = errData.responseJSON; 
                                    var description = resp.description; 
                                    $('.logout').click();
                                    alert(description);
                        }                            
                }
        });

	var genNow = myClass.indexOf('dailyreportnow');
	if(genNow != -1){

		/*$.ajax({
  			url: '/sensegiz-dev/portal/user/generatereport.php?user_id=' + uid,
  			success: function(data) {
    				console.log('Report Generated');
				$('.drsuccess').html('Report has been generated and sent over email.');
                                setTimeout(function(){ $('.drsuccess').html(''); }, 5000);

 			 }
		});*/


		$('#dynamicCanvas').html('');

		$.ajax({
			url: basePathUser + apiUrlGetGeneralSettings,
			headers: {
                                    'uid':uid,
                                    'Api-Key':apikey
			},                            
			type: 'GET',						
			contentType: 'application/json; charset=utf-8',
			dataType: 'json',
			async: false,                           
			beforeSend: function (xhr){                                
                                xhr.setRequestHeader("uid", uid);
                                xhr.setRequestHeader("Api-Key", apikey);                                
			},
			success: function(data, textStatus, xhr) {
                                
                                if(data.records.length > 0){
                                    records = data.records;
                                    $.each(records, function (index, value) {
						var date_format = value.date_format;						
						var temp_unit = value.temp_unit;
						var dailyreportsettings = value.dailyreportsettings;
			
									
						if(dailyreportsettings.length>0){
							$.each(dailyreportsettings, function (indexSett, valueSett) {
										
								dracc = valueSett.accelerometer;
								drgyro = valueSett.gyroscope;
								drtemp = valueSett.temperature;
								drhumid = valueSett.humidity;
								drtempstrm = valueSett.temperaturestream;
								drhumidstrm = valueSett.humiditystream;
								//sendreport = valueSett.send_report;
								starttime = valueSett.report_start_time.slice(0, -3);
								endtime = valueSett.report_end_time.slice(0, -3);


								$.ajax({
                            						url: basePathUser + apiUrlGateways,
                            						headers: {
                                   						'uid':uid,
                                    						'Api-Key':apikey
                            						},                            
                            						type: 'GET',						
                            						contentType: 'application/json; charset=utf-8',
                            						dataType: 'json',
                            						async: false,                           
                            						beforeSend: function (xhr){                                
                                						xhr.setRequestHeader("uid", uid);
                                						xhr.setRequestHeader("Api-Key", apikey);                                
                            						},
                            						success: function(data, textStatus, xhr) {                                				

                                						if(data.records.length > 0){
                                    							records = data.records;					
					
                                    							$.each(records, function (index, value) {
                                        							gateway_id  =  value.gateway_id;

												$.ajax({
                            										url: basePathUser + apiUrlAnalyticsGatewayDevices + '/' + gateway_id,
                            										headers: {
                                   										'uid':uid,
                                    										'Api-Key':apikey
                            										},                            
                            										type: 'GET',						
                            										contentType: 'application/json; charset=utf-8',
                            										dataType: 'json',
                            										async: false,                           
                            										beforeSend: function (xhr){                                
                                										xhr.setRequestHeader("uid", uid);
                                										xhr.setRequestHeader("Api-Key", apikey);                                
                            										},
                            										success: function(data, textStatus, xhr) {                                				
									
                                										if(data.records.length > 0){
                    													coins = data.records;

															$.each(coins, function(index, value){
                        													var device_id = value.device_id;
                        													var gateway_id = value.gateway_id;
																																	
																if(device_id != ''){
																	if(dracc == 'Y'){ 
																		canvas_html = '<canvas id='+gateway_id+''+device_id+'01'+' width="515" height="175" style="display:none;"></canvas>';					
																		$('#dynamicCanvas').append(canvas_html);
																	}
																	if(drgyro == 'Y'){ 
																		canvas_html = '<canvas id='+gateway_id+''+device_id+'03'+' width="515" height="175" style="display:none;"></canvas>';					
																		$('#dynamicCanvas').append(canvas_html);
																	}
																	if(drtemp == 'Y'){
																		canvas_html = '<canvas id='+gateway_id+''+device_id+'05'+' width="515" height="175" style="display:none;"></canvas>';	 
																		$('#dynamicCanvas').append(canvas_html);
																	}
																	if(drhumid == 'Y'){ 
																		canvas_html = '<canvas id='+gateway_id+''+device_id+'07'+' width="515" height="175" style="display:none;"></canvas>';	
																		$('#dynamicCanvas').append(canvas_html);
																	}
																	if(drtempstrm == 'Y'){ 
																		canvas_html = '<canvas id='+gateway_id+''+device_id+'09'+' width="515" height="175" style="display:none;"></canvas>';
																		$('#dynamicCanvas').append(canvas_html);
																	}
																	if(drhumidstrm == 'Y'){ 
																		canvas_html = '<canvas id='+gateway_id+''+device_id+'10'+' width="515" height="175" style="display:none;"></canvas>';
																		$('#dynamicCanvas').append(canvas_html);
																	}


																}
                      														

                    													});
															
                    													$.each(coins, function(index, value){
                        													var nick_name = value.nick_name;
                        													var device_id = value.device_id;
                        													var gateway_id = value.gateway_id;
																	
																
																if(device_id != ''){

																	if(dracc == 'Y'){ getDailyReportData(uid, gateway_id, device_id, '01', starttime, endtime, date_format, temp_unit); }
																	if(drgyro == 'Y'){ getDailyReportData(uid, gateway_id, device_id, '03', starttime, endtime, date_format, temp_unit); }
																	if(drtemp == 'Y'){ getDailyReportData(uid, gateway_id, device_id, '05', starttime, endtime, date_format, temp_unit); }
																	if(drhumid == 'Y'){ getDailyReportData(uid, gateway_id, device_id, '07', starttime, endtime, date_format, temp_unit); }
																	if(drtempstrm == 'Y'){ getDailyReportData(uid, gateway_id, device_id, '09', starttime, endtime, date_format, temp_unit); }
																	if(drhumidstrm == 'Y'){ getDailyReportData(uid, gateway_id, device_id, '10', starttime, endtime, date_format, temp_unit); }

																	
																}
                      
                    													});
                												}
                    
                            										},
                            										error: function(errData, status, error){
                                                                       						console.log("Error");
                            										}                            
                        									});
												
											});
										}
                    
                            						},
                            						error: function(errData, status, error){
                                                                       		console.log("Error");
                            						}                            
                        					});


								
							});
						}												

                                    	});    


                                }                                                  
			},
			error: function(errData, status, error){
                                    console.log("Error");
			}                            
		}); 

		setTimeout(function(){ generateDailyReport(uid); }, 45000); 
	}

	
});


function generateDailyReport(uid) {

	$.ajax({
  			url: '/sensegiz-dev/portal/user/generatereport.php?user_id=' + uid,
  			success: function(data) {
				$('.drsuccess').html('Report has been generated and sent over email.');
                                setTimeout(function(){ $('.drsuccess').html(''); }, 5000);

 			 }
		});

}



function getDailyReportData(userID, gateway_id, device_id, device_type1, startTime, endTime, date_format, temp_unit)
{

	var uid     =  $('#sesval').data('uid');
	var apikey  =  $('#sesval').data('key');


	//var zone_offset = moment().local().format('Z');
	

	

	$.ajax({
            url: basePathUser + apiUrlDailyReportData + '/' + userID + '/' +  gateway_id + '/' + device_id  + '/' +  device_type1 + '/' + startTime + '/' + endTime,
            type: 'GET',
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            contentType: 'application/json; charset=utf-8;',
            dataType: 'json',
            async: false,
            beforesend: function(xhr){
                setRequestHeader("uid", uid);
                setRequestHeader("Api-Key", apikey);
            },
            success: function(data, textStatus, xhr) 
			{
				if(device_type1 == '09' || device_type1 == '10'){
					if(data.records.length>1){
						var records = data.records.reverse();
						//var bread_html = '';
						var rows = [];
						var columns = [];						
						drawChart(device_type1, gateway_id, device_id);
						$.each(records, function (index, value){
							var devType = value.device_type;
							
							var device_value = value.device_value;
							if(devType=='09' && temp_unit == 'Fahrenheit'){                                                            
								if(device_value!=null && device_value!='null' && device_value!=''){ 
									device_value = (device_value * 1.8) + 32;	
								}
								device_value = device_value.toFixed(3);
                                                        }
						
							var last_updated_on      =  '';
							last_updated_on         = value.updated_on;
							
							var stillUtc = moment.utc(last_updated_on).toDate();	
				
							last_updated_on         =  moment(stillUtc).local().format(date_format);							
						
							myLineChart.data.datasets[0].data.push(device_value);							
							var parts = last_updated_on.split(' ');							
							myLineChart.data.labels.push([parts[0],parts[1]]);
							
							
							rows.push([last_updated_on, device_value]);

							
						
						});
						setUnit(device_type1, 0);
						myLineChart.update();																		
						setTimeout(function(){ downloadPDF(gateway_id, device_id, device_type1); }, 5000);
														
						
				}
					else{
						myLineChart = null;
						deleteOldCanvas(gateway_id, device_id, device_type1);


					}

				}
				else{
					if(data.records.length>1){
						var records = data.records.reverse();
						//var bread_html = '';
												
						drawChart(device_type1, gateway_id, device_id);
						var rows = [];
						var columns = [];	
						var lowcross = 0;
						var highcross = 0;
						$.each(records, function (index, value){
							var devType = value.device_type;
							
							var device_value = value.device_value;
							var low_threshold_value = value.low_threshold;
							var high_threshold_value = value.high_threshold;

							var last_updated_on      =  '';
							last_updated_on         = value.updated_on;
							var stillUtc = moment.utc(last_updated_on).toDate();							
							last_updated_on         =  moment(stillUtc).local().format(date_format);							
							

							//var decVal = hexToDec(device_value);
							var decLowThres = hexToDec(low_threshold_value);
							var decHighThres = hexToDec(high_threshold_value);

							if(devType == '01' || devType=='02'){
								//decVal = decVal / 10;								
								
								if(decLowThres == 1)
									decLowThres = 0.001;
								if(decHighThres == 1)
									decHighThres = 0.001;
								if(decLowThres == 2)
									decLowThres = 0.1;
								if(decHighThres == 2)
									decHighThres = 0.1;
								if(decLowThres >= 3)
									decLowThres = decLowThres / 8;
								if(decHighThres >= 3)
									decHighThres = decHighThres / 8;				
							} 

							if(devType == '03' || devType=='04'){
								//decVal = decVal * 10;
								decLowThres = decLowThres * 10;
								decHighThres = decHighThres * 10;
							}

							if(devType == '05' || devType=='06'){
								
								if(decLowThres > 126){
									decLowThres = decLowThres - 126;
									decLowThres = -decLowThres;
								}
								if(decLowThres == 126){
									decLowThres = 0;
								}
							}

							if((devType == '05' || devType=='06') && temp_unit == 'Fahrenheit'){                                                            
								if(device_value!=null && device_value!='null' && device_value!=''){ 
									device_value = (device_value * 1.8) + 32;	
								}
								device_value = device_value.toFixed(3);

								decLowThres = (decLowThres * 1.8) + 32;
								decHighThres = (decHighThres * 1.8) + 32;
                                                        }

							myLineChart.data.datasets[0].data.push(decLowThres);
							myLineChart.data.datasets[2].data.push(decHighThres);																																					
							myLineChart.data.datasets[1].data.push(device_value);							

							var parts = last_updated_on.split(' ');							
							myLineChart.data.labels.push([parts[0],parts[1]]);

														
						
						});
						setUnit(device_type1, 0);
						myLineChart.update();																		
						setTimeout(function(){ downloadPDF(gateway_id, device_id, device_type1); }, 5000);
																																	
						
					}else{
						myLineChart = null;
						deleteOldCanvas(gateway_id, device_id, device_type1);
	
                   			 }            	   				
		

				
				}
				
			},			
            		error: function(errData, status, error) {

           		 }

        });

}



function drawChart(device_type1, gateway_id, device_id)
{

	/*if(myLineChart!=null){
        	myLineChart.destroy();
    	} */
	
	// Get the context of the canvas element we want to select

	//var ctx = $('#graph1');	
	var ctx = $('#' + gateway_id + device_id + device_type1);
	
	
	// Instantiate a new chart
	if(device_type1 == '09' || device_type1 == '10')
	{						
		myLineChart = new Chart(ctx , {
			type: 'line',
			
			data: {
					labels : [],
							
					datasets : [					
						{
							label: "Device Value",
							fill: false,
							lineTension: 0.1,
							backgroundColor: "rgba(29, 202, 255, 1)",
							borderColor: "rgba(29, 202, 255, 1)",							
							pointRadius: 0,							
							hitRadius: 5,
							data: []
						}
									
					]
				},
			options: {
					scales: {
						xAxes: [{
							ticks: {
        							autoSkip: true,
        							maxTicksLimit: 9,
								minRotation:0,
								maxRotation: 0,
    							}
						}],
						yAxes: [{
            						ticks: {
                						beginAtZero: true
            						},
							scaleLabel: {
        							display: true,
        							labelString: ''
      							}
       						 }]
					}
			}
		
		});
	}
	else if(device_type1 == '26'){
		myLineChart = new Chart(ctx , {
			type: 'line',
			
			data: {
					labels : [],
							
					datasets : [{
							label : "Temperature",
							fill		      : false,										
							lineTension: 0.1,
							backgroundColor: "rgba(59, 89, 152, 1)",
							borderColor: "rgba(59, 89, 152, 1)",							
							pointRadius: 0,
							hitRadius: 5,
							data                  : []
						},						
						{
							label: "Humidity",
							fill: false,
							lineTension: 0.1,
							backgroundColor: "rgba(29, 202, 255, 1)",
							borderColor: "rgba(29, 202, 255, 1)",							
							pointRadius: 0,							
							hitRadius: 5,
							data: []
						},
						{
							label: "Dew Point Temperature",
							fill: false,
							lineTension: 0.1,
							backgroundColor: "rgba(187, 0, 0, 1)",
							borderColor: "rgba(187, 0, 0, 1)",							
							pointRadius: 0,
							hitRadius: 5,
							data: []
						},
						{
							label: "Relative Humidity",
							fill: false,
							lineTension: 0.1,
							backgroundColor: "rgba(0, 153, 51, 1)",
							borderColor: "rgba(0, 153, 51, 1)",							
							pointRadius: 0,
							hitRadius: 5,
							data: []
						}


									
					]
				},
			options: {
					scales: {
						xAxes: [{
							ticks: {
        							autoSkip: true,
        							maxTicksLimit: 9,
								minRotation:0,
								maxRotation: 0,
    							}
						}],
						yAxes: [{
            						ticks: {
                						beginAtZero: true
            						},
							scaleLabel: {
        							display: true,
        							labelString: ''
      							}
       						 }]
					}
			}
		
		});

	}
	else if(device_type1 == '12'){
		myLineChart = new Chart(ctx , {
			type: 'line',
			
			data: {
					labels : [],
							
					datasets : [{
							label : "X-Axis",
							fill		      : false,										
							lineTension: 0.1,
							backgroundColor: "rgba(59, 89, 152, 1)",
							borderColor: "rgba(59, 89, 152, 1)",							
							pointRadius: 0,
							hitRadius: 5,
							data                  : []
						},						
						{
							label: "Y-Axis",
							fill: false,
							lineTension: 0.1,
							backgroundColor: "rgba(29, 202, 255, 1)",
							borderColor: "rgba(29, 202, 255, 1)",							
							pointRadius: 0,							
							hitRadius: 5,
							data: []
						},
						{
							label: "Z-Axis",
							fill: false,
							lineTension: 0.1,
							backgroundColor: "rgba(187, 0, 0, 1)",
							borderColor: "rgba(187, 0, 0, 1)",							
							pointRadius: 0,
							hitRadius: 5,
							data: []
						},
						{
							label: "Aggregate",
							fill: false,
							lineTension: 0.1,
							backgroundColor: "rgba(0, 153, 51, 1)",
							borderColor: "rgba(0, 153, 51, 1)",							
							pointRadius: 0,
							hitRadius: 5,
							data: []
						}
									
					]
				},
			options: {
					scales: {
						xAxes: [{
							ticks: {
        							autoSkip: true,
        							maxTicksLimit: 9,
								minRotation:0,
								maxRotation: 0,
    							}
						}],
						yAxes: [{
            						ticks: {
                						beginAtZero: true
            						},
							scaleLabel: {
        							display: true,
        							labelString: ''
      							}
       						 }]
					}
			}
		
		});

	}else if(device_type1 == '17' || device_type1 == '20' || device_type1 == '23'){
		myLineChart = new Chart(ctx , {
			type: 'line',
			
			data: {
					labels : [],
							
					datasets : [{
							label : "Axial",
							fill		      : false,										
							lineTension: 0.1,
							backgroundColor: "rgba(59, 89, 152, 1)",
							borderColor: "rgba(59, 89, 152, 1)",							
							pointRadius: 0,
							hitRadius: 5,
							data                  : []
						},						
						{
							label: "Horizontal",
							fill: false,
							lineTension: 0.1,
							backgroundColor: "rgba(29, 202, 255, 1)",
							borderColor: "rgba(29, 202, 255, 1)",							
							pointRadius: 0,							
							hitRadius: 5,
							data: []
						},
						{
							label: "Vertical",
							fill: false,
							lineTension: 0.1,
							backgroundColor: "rgba(187, 0, 0, 1)",
							borderColor: "rgba(187, 0, 0, 1)",							
							pointRadius: 0,
							hitRadius: 5,
							data: []
						},
						{
							label: "Aggregate",
							fill: false,
							lineTension: 0.1,
							backgroundColor: "rgba(0, 153, 51, 1)",
							borderColor: "rgba(0, 153, 51, 1)",							
							pointRadius: 0,
							hitRadius: 5,
							data: []
						}
									
					]
				},
			options: {
					scales: {
						xAxes: [{
							ticks: {
        							autoSkip: true,
        							maxTicksLimit: 9,
								minRotation:0,
								maxRotation: 0,
    							}
						}],
						yAxes: [{
            						ticks: {
                						beginAtZero: true
            						},
							scaleLabel: {
        							display: true,
        							labelString: ''
      							}
       						 }]
					}
			}
		
		});

	}
	else
	{
		myLineChart = new Chart(ctx , {
			type: 'line',
			
			data: {
					labels : [],
							
					datasets : [{
							label : "Low Threshold",
							fill		      : false,										
							lineTension: 0.1,
							backgroundColor: "rgba(59, 89, 152, 1)",
							borderColor: "rgba(59, 89, 152, 1)",							
							pointRadius: 0,
							hitRadius: 5,
							data                  : []
						},						
						{
							label: "Device Value",
							fill: false,
							lineTension: 0.1,
							backgroundColor: "rgba(29, 202, 255, 1)",
							borderColor: "rgba(29, 202, 255, 1)",							
							pointRadius: 0,							
							hitRadius: 5,
							data: []
						},
						{
							label: "High Threshold",
							fill: false,
							lineTension: 0.1,
							backgroundColor: "rgba(187, 0, 0, 1)",
							borderColor: "rgba(187, 0, 0, 1)",							
							pointRadius: 0,
							hitRadius: 5,
							data: []
						}
									
					]
				},
			options: {
					scales: {
						xAxes: [{
							ticks: {
        							autoSkip: true,
        							maxTicksLimit: 9,
								minRotation:0,
								maxRotation: 0,
    							}
						}],
						yAxes: [{
            						ticks: {
                						beginAtZero: true
            						},
							scaleLabel: {
        							display: true,
        							labelString: ''
      							}
       						 }]
					}
			}
		
		});

	}
	
}


function setUnit(device_type, unit)
{


	if(device_type == '01' || device_type == '02' || device_type == '12')
		myLineChart.options.scales.yAxes[0].scaleLabel.labelString = "g";		
	if(device_type == 03 || device_type == 04)
		myLineChart.options.scales.yAxes[0].scaleLabel.labelString = "DPS";

	if((device_type == 05 || device_type == 06) && temp_unit == 'Fahrenheit')
		myLineChart.options.scales.yAxes[0].scaleLabel.labelString = decodeURI('%C2%B0') + "Fahrenheit";

	if((device_type == 05 || device_type == 06) && temp_unit == 'Celsius')
		myLineChart.options.scales.yAxes[0].scaleLabel.labelString = decodeURI('%C2%B0') + "Celsius";


	if(device_type == 07 || device_type == 08)
		myLineChart.options.scales.yAxes[0].scaleLabel.labelString = "% RH";

	if(device_type == 09 && temp_unit == 'Fahrenheit')
		myLineChart.options.scales.yAxes[0].scaleLabel.labelString = decodeURI('%C2%B0') + "Fahrenheit";

	if(device_type == 09 && temp_unit == 'Celsius')
		myLineChart.options.scales.yAxes[0].scaleLabel.labelString = decodeURI('%C2%B0') + "Celsius";


	if(device_type == 10)
		myLineChart.options.scales.yAxes[0].scaleLabel.labelString = "% RH";
	if(device_type == '17'){
		myLineChart.options.scales.yAxes[0].scaleLabel.labelString = "m/s" + decodeURI('%C2%B2');	
		if(unit == 'mm'){
			myLineChart.options.scales.yAxes[0].scaleLabel.labelString = "mm/s" + decodeURI('%C2%B2');
		}
		if(unit == 'mim'){
			myLineChart.options.scales.yAxes[0].scaleLabel.labelString = decodeURI('%C2%B5') + "/s" + decodeURI('%C2%B2');
		}

	}
	if(device_type == '20'){
		myLineChart.options.scales.yAxes[0].scaleLabel.labelString = "m/s";
		if(unit == 'mm'){
			myLineChart.options.scales.yAxes[0].scaleLabel.labelString = "mm/s";
		}
		if(unit == 'mim'){
			myLineChart.options.scales.yAxes[0].scaleLabel.labelString = decodeURI('%C2%B5') + "/s";
		}
	}
	if(device_type == '23'){
		myLineChart.options.scales.yAxes[0].scaleLabel.labelString = "m";
		if(unit == 'mm'){
			myLineChart.options.scales.yAxes[0].scaleLabel.labelString = "mm";
		}
		if(unit == 'mim'){
			myLineChart.options.scales.yAxes[0].scaleLabel.labelString = decodeURI('%C2%B5');
		}
	}


	myLineChart.options.scales.yAxes[0].scaleLabel.fontSize = 15;
}



function downloadPDF(gateway_id, device_id, device_type)
{		

	var uid     =  $('#sesval').data('uid');
	var apikey  =  $('#sesval').data('key');

		
	var fname = gateway_id + '_' + device_id + '_' + device_type;

												
	var canvas1 = document.getElementById(gateway_id + device_id + device_type);
	var canvasImg1 = canvas1.toDataURL("image/png");
						
	
	var u8Image  = b64ToUint8Array(canvasImg1);

	var formData = new FormData();
	formData.append("file", new Blob([ u8Image ], {type: "image/png"}));

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'store.php?filename='+fname+'.png', true);
	xhr.send(formData);
			       						      	    
	
}


function b64ToUint8Array(b64Image) {
   var img = atob(b64Image.split(',')[1]);
   var img_buffer = [];
   var i = 0;
   while (i < img.length) {
      img_buffer.push(img.charCodeAt(i));
      i++;
   }
   return new Uint8Array(img_buffer);
}



function deleteOldCanvas(gateway_id, device_id, device_type)
{		

	var uid     =  $('#sesval').data('uid');
	var apikey  =  $('#sesval').data('key');

		
	var fname = gateway_id + '_' + device_id + '_' + device_type + '.png';

	$.ajax({
		url: 'deleteOldCanvas.php?filename='+fname, // point to server-side PHP script 		
		type: 'post',                    
		success: function (response) {
			console.log("Success");			                       		                        
                    					
		},
	   	error: function (response) {
			$('.error').html(response); // display error response from the PHP script
		}
	});
			       						      	    
	
}


</script>

<script>
     
$('.btn-toggle').click(function() {
    $(this).find('.btn').toggleClass('active');  
    
    if ($(this).find('.btn-primary').length>0) {
    	$(this).find('.btn').toggleClass('btn-primary');
    }
    if ($(this).find('.btn-danger').length>0) {
    	$(this).find('.btn').toggleClass('btn-danger');
    }
    if ($(this).find('.btn-success').length>0) {
    	$(this).find('.btn').toggleClass('btn-success');
    }
    if ($(this).find('.btn-info').length>0) {
    	$(this).find('.btn').toggleClass('btn-info');
    }
    
    $(this).find('.btn').toggleClass('btn-default');
       
});

$('form').submit(function(){
  var radioValue = $("input[name='options']:checked").val();
  if(radioValue){
     alert("You selected - " + radioValue);
   };
    return false;
});


/* Start Set Mail Restriction */
$(document).on("click", ".mail_restrictionUpdate", function(e){

	var mail_restriction = $(".mail_restriction :selected").val();
	var txtMaillimit = $(".txtlimitMail").val();
	var mailTimeInterval = $(".txtMailTimeInt").val();
	
	if((txtMaillimit == '0') || (txtMaillimit < 0))
	{
		alert('Minimum Limit should be 1 OR Cannot be blank');
	}
	else
	{
		var uid     =  $('#sesval').data('uid');
		var apikey  =  $('#sesval').data('key');

		var postdata = {
				mail_restriction: mail_restriction,
				txtMaillimit: txtMaillimit,
				mailTimeInterval: mailTimeInterval
			}

		$.ajax({

                url: basePathUser + apiUrlUpdateMailRestriction,
                type: 'POST',                       
                data: JSON.stringify(postdata),
				contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                async: false,                           
                beforeSend: function (xhr){                                                                                               
                        xhr.setRequestHeader("uid", uid);
                        xhr.setRequestHeader("Api-Key", apikey);                                  
                },
                success: function(data, textStatus, xhr) {
                		
                        if(xhr.status == 200 && textStatus == 'success') {
                            if(data.status == 'success') {
                            	alert('Mail restriction has been successfully updated.');
								$('.success').html('Mail restriction has been successfully updated.');
								setTimeout(function(){ $('.success').html(''); }, 5000);
							}				
						}
						window.location.reload();
               	}
		
        }); 
    }
	
});
/* Ends Set Mail restriction */

/* New password */
var password = document.getElementById("password"), 
	confirm_password = document.getElementById("confirm_password");

function validatePassword(){
  	if(password.value != confirm_password.value) {
    	confirm_password.setCustomValidity("Passwords Don't Match");
  	} else {
    	confirm_password.setCustomValidity('');
  	}
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;


/* Start Password Change */
$(document).on("click", ".update_password", function(e){

	var new_password = $("#password").val();
	var confirm_password = $("#confirm_password").val();
	
	if(confirm_password == '' || new_password == ''){
		alert('Please fill required fieldset');
		return false;
	}

	if(new_password != confirm_password) {
    	confirm_password.setCustomValidity("Passwords Don't Match");
  	} 
	else
	{
		var uid     =  $('#sesval').data('uid');
		var apikey  =  $('#sesval').data('key');

		var postdata = {
				new_password: new_password,
				confirm_password: confirm_password,
				uid: uid
			}

		$.ajax({

                url: basePathUser + apiUrlUpdateNewPassword,
                headers: {
                            'uid':uid,
                            'Api-Key':apikey
                },  
                type: 'POST',                       
                data: JSON.stringify(postdata),
				contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                async: false,                           
                beforeSend: function (xhr){ 
                    xhr.setRequestHeader("uid", uid);
                    xhr.setRequestHeader("Api-Key", apikey);                                  
                },
                success: function(data, textStatus, xhr) {

                    if(xhr.status == 200 && textStatus == 'success') {
                        	alert('New Password Updated Successfully.');
							$('.success').html('New Password Updated Successfully.');
							setTimeout(function(){ $('.success').html(''); }, 5000);
					}
					window.location.reload();
               	}
		
        }); 
    }
	
});
/* Ends Password Change */


/* Update Gateway Nick Name */
getGatewayList();
function getGatewayList(){

	var uid     =  $('#sesval').data('uid');
	var apikey  =  $('#sesval').data('key');

	$.ajax({
                url: basePathUser + apiUrlGateways,
				headers: {
						'uid':uid,
						'Api-Key':apikey
				},                            
				type: 'GET',						
				contentType: 'application/json; charset=utf-8',
				dataType: 'json',
				async: false,                          
                beforeSend: function (xhr){                                                                                               
                        xhr.setRequestHeader("uid", uid);
                        xhr.setRequestHeader("Api-Key", apikey);                                  
                },
                success: function(data, textStatus, xhr) {
                		
                        if(xhr.status == 200 && textStatus == 'success') {
                            if(data.status == 'success') {

								var records = data.records;
								var gateway_list_html = [];
								var option_html = '';

								$.each(records, function (index, value) {
                                    gateway_id  =  value.gateway_id;

                                    if(gateway_id != ''){ 
										option_html = '<option value="'+gateway_id+'">'+gateway_id+'</option><br />';	
										// $('#txtGatewaySelect').append(option_html);
										gateway_list_html.push(option_html);
										
									}else{
										option_html = '<option value="0">No Gateways</option>';
										gateway_list_html.push(option_html);
									}
									
                                });
                                $('#txtGatewaySelect').html(gateway_list_html); 
							}
						}
               	}
        });

}

$(document).on("click", ".update_gnickname", function(e){

	var gateway_id = $("#txtGatewaySelect").val();
	var gateway_nickname = $("#gnick_name").val();
	
	if(gateway_id == '' || gateway_nickname == ''){
		alert('Please fill required fieldset');
		return false;
	}
	else
	{
		var uid     =  $('#sesval').data('uid');
		var apikey  =  $('#sesval').data('key');

		var postdata = {
				gateway_id: gateway_id,
				gateway_nickname: gateway_nickname,
				uid: uid
			}

		$.ajax({

                url: basePathUser + apiUrlUpdateGatewayNickName,
                headers: {
                            'uid':uid,
                            'Api-Key':apikey
                },  
                type: 'POST',                       
                data: JSON.stringify(postdata),
				contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                async: false,                           
                beforeSend: function (xhr){ 
                    xhr.setRequestHeader("uid", uid);
                    xhr.setRequestHeader("Api-Key", apikey);                                  
                },
                success: function(data, textStatus, xhr) {

                    if(xhr.status == 200 && textStatus == 'success') {
                        	alert('Gateway Nick Name Updated Successfully.');
							$('.success').html('Gateway Nick-Name Updated Successfully.');
							setTimeout(function(){ $('.success').html(''); }, 5000);
					}
					window.location.reload();
               	}
		
        }); 
    }
	
});

$(document).on("click", ".submit_whatapp_alert_time", function (e) {

e.preventDefault();
	
	var startTime = $("#start_time").val();
	var endTime = $("#end_time").val();
	
	if (!startTime) {
		alert("Start Time cannot be empty");
		return;
	}
	
	if (!endTime) {
		alert("End Time cannot be empty");
		return;
	}

	var uid = $('#sesval').data('uid');
	
	// Additional validation can be added here
	
	// If validation passes, you can proceed with further actions
	console.log("Start Time: " + startTime);
	console.log("End Time: " + endTime);
	console.log("uid--> ",uid);
	var apikey = $('#sesval').data('key');


	var postdata = {
		uid: uid,
		startTime: startTime,
		endTime: endTime
	}

	$.ajax({

		url: basePathUser + apiUrlUpdateWhatsappAlertTime,
		headers: {
			'uid': uid,
			'Api-Key': apikey
		},
		type: 'POST',
		data: JSON.stringify(postdata),
		contentType: 'application/json; charset=utf-8',
		dataType: 'json',
		async: false,
		beforeSend: function (xhr) {
			xhr.setRequestHeader("uid", uid);
			xhr.setRequestHeader("Api-Key", apikey);
		},
		success: function (data, textStatus, xhr) {

			if (xhr.status == 200 && textStatus == 'success') {
				alert('WhatsApp Alert Time Updated Successfully.');
				$('.success').html('WhatsApp Alert Time  Updated Successfully.');
				setTimeout(function () { $('.success').html(''); }, 5000);
			}
			window.location.reload();
		}

	});


});

/* Ends Gateway Nick Name */


</script>
