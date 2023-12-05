<?php 
include_once('page_header_user.php');
?>

<!--<div class="col-lg-10 pad0 ful_sec"> 
        <div class="row pad0">-->
        	<div class="content userpanel">

  <script type="text/javascript" src="../js/jquery.min.js"></script>


  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<?php 

	include './user_menu.php';

?>

<style>



.form-control {
    display: block;
    width: 85%;
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
}
@media only screen and (min-width: 560px) and (max-width:767px){
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

</style>
			<div class="col-10 col-sm-10 mains">
			<h1>General Settings</h1><hr>
			<div class="detail-content" style="background-color: #fff;">
                		
                		<div class="lp-det" style="margin-top:30px">
					

                        			<span class='error'></span>
                        			<span class='success'></span>
						
					
						<form class="form-horizontal">
							

							<div class="row">
								<div class="col-sm-4" style="margin-bottom:20px">
									<label for="sensors">Sensors shown on activity dashboard:</label>
									  <table class="table table-bordered checkboxes">
									
									</table>
									
								</div>

								<div class="col-sm-2">
															
								</div>

								<div class="col-sm-3">
							
									<label for="date_format">Select your preferred Date Format:</label> <br/><br/>

 									<select class="form-control date_format" id="date_format" >
 	   							
									</select>
									<br> 
									<button type="button" class="btn btn-primary dateformat">Update Date Format</button>
								</div>


							</div>
							<br><br><br>
							<div class="row"><div class="col-sm-3"style="margin-bottom:20px">							
							
									<label for="temp_unit">Select unit for temperature:</label> <br/><br/>

 									<select class="form-control temp_unit" id="temp_unit" >
 	   							
									</select>
									<br> 
									<button type="button" class="btn btn-primary tempunit">Update Temperature Unit</button>
								</div>
																
							<div class="col-sm-3">
															
							</div>

							<div class="col-sm-4">
							
								<label for="rms_values">Select your preferred setting for Velocity and Displacement:</label> <br/><br/>

 								<select class="form-control rms_values" id="rms_values">
 	   							
								</select>
								<br> 
								<button type="button" class="btn btn-primary rmsvalues">Update RMS setting</button>
							</div>
							</div>
							<br><br><br>
							<div class="row">
								<div class="col-sm-5" style="margin-bottom:20px">
									<label for="logo">Upload your own logo:</label> <br/><br/>

									<input type="file" id="file" name="file" />
									<p>* Only .jpeg .png .jpg file formats accepted.</p>
										<br> 																
								<button type="button" class="btn btn-primary logo">Upload Logo</button>       							
      							      																
								</div>

								<div class="col-sm-1">
															
								</div>

								

							</div>
							<br><br><br>

							

    						</form> 
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

getGeneralSettings();

var basePathUser      =   getBasePathUser();
var apiUrlAddLogo = "add-logo";
var apiUrlUpdateShownSensors = "update-shown-sensors";

var selected_date_format = $(".date_format :selected").val();
var selected_rms_values = $(".rms_values :selected").val();
var selected_temp_unit = $(".temp_unit :selected").val();


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

	$('input[type="checkbox"]:checked').each(function() {

		if(this.value == 'acc'){ acc = 'Y'; }
		else if(this.value == 'gyro'){ gyro = 'Y'; }
		else if(this.value == 'temp'){ temp = 'Y'; }
		else if(this.value == 'humid'){ humid = 'Y'; }
		else if(this.value == 'stream'){ stream = 'Y'; }
		else if(this.value == 'accstream'){ accstream = 'Y'; }

	});
	


	var uid     =  $('#sesval').data('uid');
	var apikey  =  $('#sesval').data('key');

	var postdata = {
			accelerometer: acc,
			gyroscope: gyro,
			temperature: temp,
			humidity: humid,
			stream: stream,
			accstream: accstream

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

</script>