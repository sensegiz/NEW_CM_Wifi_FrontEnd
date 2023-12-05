<?php 
include_once('page_header_user.php');
?>

<style>

.col-sm-1, .col-md-3 {
    background-color:#000;
    padding: 15px 15px 15px 15px;
    border: 2px grey;
    text-align: center;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 12px;
    color: white;
    margin: 10px;
    height: 8%;
    width: 14%;
}

select.filterSelect {
    width:115px;
}

.radio-inline{
	position:relative;
	margin-right:9px;
}
.radio-inline + .radio-inline, .checkbox-inline + .checkbox-inline {

    margin-top: 0;
    margin-left: 0px;

}

input#from.hasDatepicker {
	width:100px;
	height:21px;
}

input#to.hasDatepicker {
	width:100px;
	height:21px;
}


@media only screen and (min-width: 200px) and (max-width:500px){

	
 .col-md-3 {
    background-color:#000;
    padding: 15px 15px 15px 15px;
    border: 2px grey;
    text-align: center;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 12px;
    color: white;
    margin: 10px;
    height: 8%;
    width: 40%;
    float:left;
}
	.col-sm-1{

	 background-color:#000;
    padding: 20px 20px 15px 15px;
    border: 2px grey;
    text-align: center;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 12px;
    color: white;
    margin: 10px;
    height: 9%;
    width: 27%;
    float:left;

	}
.breadcrumb {

    padding: 8px 13px;
    margin-bottom: 20px;
    list-style: none;
    background-color: 

    #f5f5f5;
    border-radius: 4px;
    width: 97%;
	margin-left: -6px

}
.AxisRadios{
	margin-left:-31px;
	margin-right:-39px;
}
}
@media only screen and (min-width: 500px) and (max-width:766px){
	
 .col-md-3 {
    background-color:#000;
    padding: 15px 15px 15px 15px;
    border: 2px grey;
    text-align: center;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 12px;
    color: white;
    margin: 10px;
    height: 17%;
    width: 30%;
    float:left;
}
	.col-sm-1{

	 background-color:#000;
    padding: 15px 15px 15px 15px;
    border: 2px grey;
    text-align: center;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 12px;
    color: white;
    margin: 10px;
    height: 16%;
    width: 17%;
    float:left;

	}
.AxisRadios{
	margin-left:-35px;
}
}
@media only screen and (min-width: 767px) and (max-width:999px){
	
 .col-md-3 {
    background-color:#000;
    padding: 10px 15px 15px 15px;
    border: 2px grey;
    text-align: center;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 12px;
    color: white;
    margin: 10px;
    height: 15%;
    width: 22%;
    float:left;
}
	.col-sm-1{

	 background-color:#000;
    padding: 12px 15px 15px 15px;
    border: 2px grey;
    text-align: center;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 12px;
    color: white;
    margin: 10px;
    height: 15%;
    width: 17%;
    float:left;
    padding-top:11px;
	}
.AxisRadios{
	margin-left:-35px;
}
}



.fa-lg {
    font-size: 1.33333333em;
    line-height: .75em;
    vertical-align: -15%;
    margin-top: 15px;
    margin-left:-5px;
	margin-bottom:12px;
}
#graph{
	//height:500px !important;
	//width:900px !important;
}

.glyphicon {
    position: relative;
    top: 1px;
    display: inline-block !important;
    font-family: 'Glyphicons Halflings';
    font-style: normal;
    font-weight: 400;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.navbar-toggle .icon-bar {
    display: block !important;
    width: 22px;
    height: 2px;
    border-radius: 1px;
}


@media only screen and (min-width: 600px) and (max-width:999px){
.navbar-fixed-top, .navbar-fixed-bottom {

    position:absolute;
    right: 0;
    left: 0;
    z-index: 1030;
    -webkit-transform: translate3d(0,0,0);
    -o-transform: translate3d(0,0,0);
    transform: translate3d(0,0,0);

}
}
th ,td{
 	padding:5px;
	text-align:center;
	
}
td{
	background-color:#dde0e1;
	color:black;
}
//th{
	background-color:#3b5998;
	color:white;
}
.avgtable{
	width:40%;
	font-size:12px;
	margin-top:-23px;
	border-spacing:8px 0px;
	border-collapse:separate;
	margin-left:149px;
}


</style>

<!--<link rel="stylesheet" href="../css/jquery-ui.css">-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



<!--<script type="text/javascript" src="../js/loader.js"></script>-->

<!--<link rel="stylesheet" href="../css/jquery-confirm.min.css">-->
<!--<script type="text/javascript" src="../js/jquery-confirm.min.js"></script>-->

<!--<script type="text/javascript" src="../js/jquery-1.12.4.js"></script>-->
<!--<script type="text/javascript" src="../js/jquery-ui.js"></script>-->




<!--<div class="col-lg-10 pad0 ful_sec"> 
	<div class="row pad0">-->
    	<div class="content userpanel">

<?php 
    include './user_menu.php';
?>
			
			
			<div class="col-sm-10 col-10 mains">
<!--toggle sidebar button-->
           <p class="visible-xs">
            <button type="button" class="btn btn-default btn-sm" id="more" id="menu-toggle" data-toggle="offcanvas" style="margin-bottom:10px"><span  class="glyphicon glyphicon-chevron-left" ></span> Gateways</button>
          </p>
            <h1>Analytics</h1><hr>
        	<div class="mine"></div>
            <div class="detail-content" style="background-color: #fff; z-index:0">
				<div class="lp-det" style="margin-top:8px">
					<span class='error-tab'></span>
					<div class="row">
						<div class="col-md-6 gwInfo">
							<p>Click on any gateway to view Analytics</p>
						</div>
					</div>

					<div class="row gwDevList" style="cursor:pointer">
					</div>
					
					<div class="breadCrum"></div>
					<div class="container-fluid">
					<div class="row filter"></div>
					
	<!-- <div class="row gwCoin"></div> -->
					<div id='loader'><img src="../img/loader.gif"/> Loading Data</div>  <hr/>                    
                 			
					<div class="row filterData"> </div>
					</div>
					<canvas id="graph" width="515" height="175"></canvas>	
                </div>                         
            </div>
        </div>
  


<?php
    include_once('page_footer_user.php');
?>

<script type="text/javascript" src="../js/Chart.js"></script>
<script type="text/javascript" src="../js/jspdf.min.js"></script>
<script type="text/javascript" src="../js/jspdf.plugin.autotable.js"></script>
<script src="http://momentjs.com/downloads/moment.js"></script>

<link rel="stylesheet" href="../css/jquery-ui-timepicker-addon.css" type="text/css"/>
<script src="../js/jquery-ui-timepicker-addon.js" type="text/javascript"></script>

<script type="text/javascript">



var server_address = getBaseAddressUser();

basePathUser      =   server_address+"/sensegiz-dev/user/";
        
var apiUrlGateways            = "gateways";
var apiUrlHelpDevices             = "help-devices";
var apiUrlHelpGetCoinSensor         = 'help-get-device-sensor';
var apiUrlAnalyticsGatewayDevices= 'analytics-gateway-devices';
var apiUrlAnalyticsDeviceSensor = 'analytics-device-sensor';
var apiUrlAnalyticsDeviceSensorData = 'analytics-device-sensor-data';
var getBreadCrumbNickName = 'breadcrumb-nick-name';
var apiUrlAnalyticsFilteredChartData = 'analytics-filtered-chart-data';
var analyticsdownloaddata = 'analytics-download-data';

var analyticsAcceStream = 'analytics-acc-stream';
var analyticsStreamFilteredChartData = 'analytics-stream-filtered';


    /*
     * Function             : helpGetGateways()
     * Brief            : load the list of Gateways  
     * Input param          : Nil
     * Input/output param           : NA
     * Return           : NA
     */     
    function helpGetGateways() {

            $('#loader').show();

            var uid     =  $('#sesval').data('uid');
            var apikey  =  $('#sesval').data('key');
	var date_format  =  $('#sesval').data('date_format');

            if(uid!='' && apikey!=''){
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
                                $('#loader').hide();

                                var sc_html =   '';
                                var gw_li_html =   '';
                                if(data.records.length > 0){
                                    records = data.records;
                                    $.each(records, function (index, value) {
                                        var gateway_id  = value.gateway_id;
                                        var date        = '';
                                        var added_on    = value.added_on;
                                        var updated_on  = value.data_received_on;
                                        var is_blacklisted = value.is_blacklisted;
                                        var is_active = value.active;
					var status = value.status;



                                        if(value.updated_on!=''){
            //                               date      =  added_on +'Z'; 
                                           date         = updated_on;
                                           var stillUtc = moment.utc(date).toDate();
                                           date         = moment(stillUtc).local().format(date_format);
                                        }

					if(status == 'Online'){
						status_html = '&nbsp&nbsp&nbsp<img src="../img/online.png"/>';
					}else{
						status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';

					}

                                        sc_html  =  '<tr><td>Click on any Gateways</td></tr>';

                                        gw_li_html += '<li><a href="javascript:;" class="asGw" data-gateway="'+gateway_id+'"  data-is_active="'+is_active+'" data-is_blacklisted="'+is_blacklisted+'" data-added_on="'+added_on+'" data-updated_on="'+date+'">'+gateway_id+''+status_html+'</a></li>';
                                    });                        
                                }
                                else{
                                        sc_html  =  '<tr><td width="300">No Gateways Found</td></tr>';
                                }
                                $('#gatewaysList').html(sc_html); 

                                $('.gateway-list-lfnav').html(gw_li_html);
                    
                    
                            },
                            error: function(errData, status, error){
                                    if(errData.status==401){
                                        var resp = errData.responseJSON; 
                                        var description = resp.description; 
                                        $('.logout').click();
                                        alert(description);
                                        
                                    }
                            }                            
                        });
            }
    }
  

$(document).on("click", ".asGw, .bCrum", function(e){

    e.preventDefault();
    var uid    = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    var gateway_id = $(this).data('gateway');


	showBreadCrumb(gateway_id, 0, 0, 0, uid, apikey);
	$('.filter').html(" ");
	$('.filterData').html(" ");
	if(myLineChart!=null){
        	myLineChart.destroy();
    	}
	clearInterval(autohandle);

    if(uid!='' && apikey!=''){
        $.ajax({
            url: basePathUser + apiUrlAnalyticsGatewayDevices + '/' + gateway_id,
            headers: {
                'uid':uid,
                'Api-Key': apikey
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr){
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function(data, textStatus, xhr){
                var sc_html = '';
                var gw_di_list = '';
                if(data.records.length > 0){
                    records = data.records;
                    $.each(records, function(index, value){
                        var nick_name = value.nick_name;
                        var device_id = value.device_id;
                        var gateway_id = value.gateway_id;

                        sc_html = '<h4>Click any device to view device level analytics</h4>';

                        gw_di_list += '<div class="col-sm-1 asDvList" href="javascript:;"><p class="asGwDv" data-gateway_id="'+gateway_id+'" data-device_id="'+device_id+'" data-nick_name="'+nick_name+'">'+nick_name+'</p></div>';

                    });
                }
                else{
                    sc_html = '<h4>No Devices registered to this gateway.</h4>';
                }
                $('.gwInfo').html(sc_html);

                $('.gwDevList').html(gw_di_list);
            },
            error: function(errData, status, error){
                if(errData.status = 401){
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);
                }
            }
        });
    }
});



$(document).on('click', '.asGwDv', function(e)
{

	$('.asDvSs').css('background', 'black');
    	$(this).css('background', 'grey');

    	var gateway_id = $(this).data('gateway_id');
    	var device_id = $(this).data('device_id');
	var nick_name = $(this).data('nick_name');
	
    	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
	clearInterval(autohandle);

	if(uid!= '' && apikey!= '')
	{
		sc_html = '<h4>Select a Sensor</h4>';
		dv_ss_list = '<div href="javascript:;" class="col-md-3 asDvSs" data-nick_name="'+nick_name+'" data-gateway_id="'+gateway_id+'" data-device_id="'+device_id+'" data-device_type1="01"><p>Accelerometer</p></div>';
		dv_ss_list += '<div href="javascript:;" class="col-md-3 asDvSs" data-nick_name="'+nick_name+'" data-gateway_id="'+gateway_id+'" data-device_id="'+device_id+'" data-device_type1="03"><p>Gyroscope</p></div>';
		dv_ss_list += '<div href="javascript:;" class="col-md-3 asDvSs" data-nick_name="'+nick_name+'" data-gateway_id="'+gateway_id+'" data-device_id="'+device_id+'" data-device_type1="05"><p>Temperature</p></div>';
		dv_ss_list += '<div href="javascript:;" class="col-md-3 asDvSs" data-nick_name="'+nick_name+'" data-gateway_id="'+gateway_id+'" data-device_id="'+device_id+'" data-device_type1="07"><p>Humidity</p></div>';
		dv_ss_list += '<div href="javascript:;" class="col-md-3 asDvSs" data-nick_name="'+nick_name+'" data-gateway_id="'+gateway_id+'" data-device_id="'+device_id+'" data-device_type1="09"><p>Stream</p></div>';	
		dv_ss_list += '<div href="javascript:;" class="col-md-3 accTypes" data-nick_name="'+nick_name+'" data-gateway_id="'+gateway_id+'" data-device_id="'+device_id+'" data-device_type1="12">Accelerometer Types <span class="glyphicon glyphicon-chevron-right"></span></div>';		
	

		$('.gwInfo').html(sc_html);
		$('.gwDevList').html(dv_ss_list);
		    	
		showBreadCrumb(gateway_id, device_id, nick_name, 0, uid, apikey);
	}
    
    
});




$(document).on('click', '.asDvSs', function(e)
{	
	$('.filterData').html('');
	$('.accStream').css('background', 'black');
	$('.asDvSs').css('background', 'black');
	if(myLineChart!=null){
        	myLineChart.destroy();
    	}
    	$(this).css('background', 'grey');
	clearInterval(autohandle);

    	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
    	var gateway_id = $(this).data('gateway_id');
    	var device_id = $(this).data('device_id');
    	var device_type1 = $(this).data('device_type1');
	var nick_name = $(this).data('nick_name');
	
	var device_type2;

	if(device_type1 == '01'){ device_type2 = "02"; }
	if(device_type1 == '03'){ device_type2 = "04"; }
	if(device_type1 == '05'){ device_type2 = "06"; }
	if(device_type1 == '07'){ device_type2 = "08"; }
	if(device_type1 == '09'){ device_type2 = "10"; }

    	if(uid!= '' && apikey!= '') { 	
		getAnalyticsDeviceSensorData(gateway_id, device_id, device_type1, device_type2, nick_name);
		autohandle = setInterval(function(){ getAnalyticsDeviceSensorData(gateway_id, device_id, device_type1, device_type2, nick_name); }, 60000);
		console.log(autohandle);
    	}
});

$(document).on('click', '.accTypes,bCrum', function(e)
{
	$('.filter').html('');
	$('.accStream').css('background', 'black');
	//$('.accTypes').css('background', 'black');

    	$(this).css('background', 'grey');

	if(myLineChart!=null){
        	myLineChart.destroy();
    	}
	clearInterval(autohandle);

    	var gateway_id = $(this).data('gateway_id');
    	var device_id = $(this).data('device_id');
	var nick_name = $(this).data('nick_name');

	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
	clearInterval(autohandle);



	if(uid!= '' && apikey!= '')
	{
		
			sc_html = '<h4>Select Accelerations Types</h4>';
			acc_types = '<div href="javascript:;" class="col-md-3 accStream" data-nick_name="'+nick_name+'" data-gateway_id="'+gateway_id+'" data-device_id="'+device_id+'" data-device_type1="12"><p>Accelerometer Stream</p></div>';		
			acc_types += '<div href="javascript:;" class="col-md-3 accStream " data-nick_name="'+nick_name+'" data-gateway_id="'+gateway_id+'" data-device_id="'+device_id+'" data-device_type1="17"><p>Acceleration </p></div>';
			acc_types += '<div href="javascript:;" class="col-md-3 accStream" data-nick_name="'+nick_name+'" data-gateway_id="'+gateway_id+'" data-device_id="'+device_id+'" data-device_type1="20"><p>Velocity</p></div>';
			acc_types += '<div href="javascript:;" class="col-md-3 accStream" data-nick_name="'+nick_name+'" data-gateway_id="'+gateway_id+'" data-device_id="'+device_id+'" data-device_type1="23"><p>Displacement</p></div>';
				
		
			
		$('.gwInfo').html(sc_html);
		$('.gwDevList').html(acc_types);
		    	
		showBreadCrumb(gateway_id, device_id, nick_name, 0, uid, apikey);
	}
    
    
});


function getAnalyticsDeviceSensorData(gateway_id, device_id, device_type1, device_type2, nick_name)
{
	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
	var date_format  =  $('#sesval').data('date_format');
	var temp_unit  =  $('#sesval').data('temp_unit');

	$.ajax({
            url: basePathUser + apiUrlAnalyticsDeviceSensorData + '/' + gateway_id + '/' + device_id + '/' + device_type1 + '/' + device_type2,
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
				if(device_type1 == '09'){
					if(data.records.length>1){
						var records = data.records.reverse();
						//var bread_html = '';
												
						drawChart(device_type1);
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
							
						
						});
						setUnit(device_type1, 0);
						myLineChart.update();																		
						
						showBreadCrumb(gateway_id, device_id, nick_name, device_type1, uid, apikey);					}
					else{
						var ww_html = '<h4>No Data Found!</h4>';
						$('#download-xls').prop('disabled', true);
						$('#download-pdf').prop('disabled', true);
						$('.filterData').html(ww_html);
						showBreadCrumb(gateway_id, device_id, nick_name, device_type1, uid, apikey);
					}
					showFilter(gateway_id, device_id, device_type1, device_type2, nick_name);
				}
				else{
					if(data.records.length>1){
						var records = data.records.reverse();
						//var bread_html = '';
												
						drawChart(device_type1);
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
						
						showBreadCrumb(gateway_id, device_id, nick_name, device_type1, uid, apikey);

						
					}else{
							var ww_html = '<h4>No Data Found!</h4>';
							$('#download-xls').prop('disabled', true);
							$('#download-pdf').prop('disabled', true);
							$('.filterData').html(ww_html);
							showBreadCrumb(gateway_id, device_id, nick_name, device_type1, uid, apikey);
                   			 }            	   				
		
					// add filters 
					showFilter(gateway_id, device_id, device_type1, device_type2, nick_name);
				
				}
				
			},			
            		error: function(errData, status, error) {

           		 }

        });
}



$(document).on('click', '.accStream', function(e)
{
	
	$('.filterData').html('');
	$('.filter').html('');


	$('.accStream').css('background', 'black');
	$('.asDvSs').css('background', 'black');
	if(myLineChart!=null){
        	myLineChart.destroy();
    	}
    	$(this).css('background', 'grey');
	clearInterval(autohandle);

    	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
    	var gateway_id = $(this).data('gateway_id');
    	var device_id = $(this).data('device_id');
    	var device_type1 = $(this).data('device_type1');
	var nick_name = $(this).data('nick_name');

	if(uid != '' && apikey != '') {
		
		getanalyticsAcceStream(gateway_id, device_id, nick_name, device_type1);
		autohandle = setInterval(function(){ getanalyticsAcceStream(gateway_id, device_id, nick_name, device_type1); }, 60000);

	}

});



function getanalyticsAcceStream(gateway_id, device_id, nick_name, device_type1)
{
	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
	var date_format  =  $('#sesval').data('date_format');
	var rms_values  =  $('#sesval').data('rms_values');

	var unit = $('.unit :selected').val();
	
	$.ajax({
            		
            		url: basePathUser + analyticsAcceStream + '/' + gateway_id + '/' + device_id + '/' + device_type1,
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

				if(data.records.length>1){
					var records = data.records.reverse();
						//var bread_html = '';

					var avg_x = 0;
					var avg_y = 0;
					var avg_z = 0;
					var avg_cnt_x = 0;
					var avg_cnt_y = 0;
					var avg_cnt_z = 0;
					var average_x = 0;
					var average_y = 0;
					var average_Z = 0;
							
					drawChart(device_type1);
						$.each(records, function (index, value){
							var devType = value.device_type;
							
							var device_value = value.device_value;

							if(device_type1 == '17' || device_type1 == '20' || device_type1 == '23'){
								if(unit == 'mm'){
									device_value = device_value * 1000;
								}
								if(unit == 'mim'){
									device_value = device_value * 1000000;
								}
								
							}
						
							if(rms_values == 0 && (device_type1 == '20' || device_type1 == '23')){
								device_value = device_value/0.707;
							}

							var last_updated_on      =  '';
							last_updated_on         = value.updated_on;
							var stillUtc = moment.utc(last_updated_on).toDate();							
							last_updated_on         =  moment(stillUtc).local().format(date_format);							
						

				
							
							if(devType == '12' || devType == '17' || devType == '20' || devType == '23'){
								myLineChart.data.datasets[0].data.push(device_value);	
								avg_x = parseFloat(avg_x) + parseFloat(device_value);
								avg_cnt_x = avg_cnt_x + 1;						
							}

							if(devType == '14' || devType == '18' || devType == '21' || devType == '24'){
								myLineChart.data.datasets[1].data.push(device_value);	
								avg_y = parseFloat(avg_y) + parseFloat(device_value);	
								avg_cnt_y = avg_cnt_y + 1;						
							}

							if(devType == '15' || devType == '19' || devType == '22' || devType == '25'){
								myLineChart.data.datasets[2].data.push(device_value);
								var parts = last_updated_on.split(' ');							
								myLineChart.data.labels.push([parts[0],parts[1]]);	
								avg_z = parseFloat(avg_z) + parseFloat(device_value);
								avg_cnt_z = avg_cnt_z + 1;						
							}

							if(devType == '28' || devType == '29' || devType == '30' || devType == '31'){
								myLineChart.data.datasets[3].data.push(device_value);	
														
							}
						
						});
						setUnit(device_type1, unit);
						showBreadCrumb(gateway_id, device_id, nick_name, device_type1, uid, apikey);

						myLineChart.update();	

						average_x = (avg_x/avg_cnt_x).toFixed(7);
						average_y = (avg_y/avg_cnt_y).toFixed(7);						
						average_z = (avg_z/avg_cnt_z).toFixed(7);											
																
					}
					else{
							var ww_html = '<h4>No Data Found!</h4>';
							$("input[type=radio]").prop('disabled', true);
							$('#download-xls').prop('disabled', true);
							$('#download-pdf').prop('disabled', true);
							$('.filterData').html(ww_html);
							showBreadCrumb(gateway_id, device_id, nick_name, device_type1, uid, apikey);

							
                   			 }
				showStreamFilter(gateway_id, device_id, device_type1, nick_name);

				var avg_html = '<tr><th style="background-color:#3b5998;color:white;">X axis</th><td>'+average_x+'</td><th style="background-color:#1dcaff;color:white;">Y axis</th>'
								+'<td >'+average_y+'</td><th style="background-color:#b00;color:white;">Z axis</th><td>'+average_z+'</td></tr>';

				$('.avgtable').html(avg_html);

			},
            		error: function(errData, status, error) {

            		}

        	});

}



function setUnit(device_type, unit)
{
	var temp_unit  =  $('#sesval').data('temp_unit');

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




$(document).on('click', '#filter', function(e)
{
	$('.filterData').html('');
    	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
    	var gateway_id = $('.filter').data('gateway_id');
    	var device_id = $('.filter').data('device_id');
    	var device_type1 = $('.filter').data('device_type1');
	var device_type2 = $('.filter').data('device_type2');
	var fromDate = $("#from").val();
	var toDate = $("#to").val();
		

	if(fromDate == "" || toDate == ""){

		alert('Please select both the dates!');
	}


	$('.filter').data('fromDate', fromDate);
	$('.filter').data('toDate', toDate);
	clearInterval(autohandle);


    	if(uid!= '' && apikey!= '') {
		drawFilteredChart(gateway_id, device_id, device_type1, device_type2, fromDate, toDate);
		autohandle = setInterval(function(){ drawFilteredChart(gateway_id, device_id, device_type1, device_type2, fromDate, toDate); }, 60000);        
	}
});




function drawChart(device_type1)
{
	$('#download-xls').prop('disabled', false);
	$('#download-pdf').prop('disabled', false);
	$("input[type=radio]").attr('disabled', false);
	if(myLineChart!=null){
        	myLineChart.destroy();
    	}
	
	// Get the context of the canvas element we want to select
	var ctx = $('#graph');


	//var high = "{label: 'High Threshold',fill: false,lineTension: 0.1,	backgroundColor: 'rgba(187, 0, 0, 1)',	borderColor: 'rgba(187, 0, 0, 1)',pointRadius: 0,hitRadius: 5,data: []} ";
	
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



function showFilter(gateway_id, device_id, device_type1, device_type2, nick_name)
{
	var str, val=0;

	if(device_type1 == "09"){
		var filter_html = '&nbsp;&nbsp;&nbsp;&nbsp; Filters:&nbsp;&nbsp; <select class="filterSelect"><option value="0" selected>Select</option><option value="1" >Today</option><option value="2">Last 7 days</option>' +
			'<option value="3">Last 30 days</option><option value="4">Custom Report</option></select>' +
			'&nbsp;&nbsp;&nbsp;&nbsp; <span> From : <input type="text" id="from" display="none" style="margin-top:12px"> &nbsp;&nbsp;&nbsp;&nbsp; To : <input type="text" id="to"style="margin-top:12px" />' +
			'&nbsp;&nbsp; <button id="filter"> Submit </button> </span>' +
			'<label class="radio-inline"><input type="radio" name="streams" onclick="changeGraph();" value="temp" checked>Temperature</label> &nbsp;' +
			'<label class="radio-inline"><input type="radio" name="streams" onclick="changeGraph();" value="humid">Humidity</label> &nbsp;' + 
			'<label class="radio-inline"><input type="radio" name="streams" onclick="changeGraph();" value="dptah">Derived Values</label> &nbsp;' + 		
			'&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-file-excel-o fa-lg" id="download-xls" title="XLS Download" style="cursor:pointer;color:green"></i>' +
			'&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-file-pdf-o fa-lg" id="download-pdf" title="PDF Download" style="cursor:pointer;color:red"></i>';
	}
	else
	{
		
		var filter_html = '&nbsp;&nbsp;&nbsp;&nbsp; Filters:&nbsp;&nbsp; <select class="filterSelect"><option value="0" selected>Select</option><option value="1" >Today</option><option value="2">Last 7 days</option>' +
			'<option value="3">Last 30 days</option><option value="4">Custom Report</option></select>' +
			'&nbsp;&nbsp;&nbsp;&nbsp; <span> From : <input type="text" id="from" display="none"style="width:130px;margin-top:12px"> &nbsp;&nbsp;&nbsp;&nbsp; To : <input type="text" id="to"style="width:130px;margin-top:12px" />' +
			'&nbsp;&nbsp; <button id="filter"> Submit </button> </span>' +
			'<label class="radio-inline"><input type="radio" name="threshold" onclick="changeGraph();" value="both" checked>Both Thresholds</label> &nbsp;' +
			'<label class="radio-inline"><input type="radio" name="threshold" onclick="changeGraph();" value="low">Low</label> &nbsp;' + 
			'<label class="radio-inline"><input type="radio" name="threshold" onclick="changeGraph();" value="high">High</label>' + 
			'&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-file-excel-o fa-lg" id="download-xls" title="XLS Download" style="cursor:pointer;color:green"></i>' +
			'&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-file-pdf-o fa-lg" id="download-pdf" title="PDF Download" style="cursor:pointer;color:red"></i>';

	}
					
	$('.filter').html(filter_html);
	$("span").hide();

	$('.filter').data('gateway_id', gateway_id);
	$('.filter').data('device_id', device_id);
	$('.filter').data('device_type1', device_type1);
	$('.filter').data('device_type2', device_type2);
	$('.filter').data('nick_name', nick_name);
	// val = $("select option:selected").val();

	$('.filter').data('val', val);


	$(function() {
						
		$( "#from" ).datepicker({ dateFormat: 'yy-mm-dd'}); 
		$( "#to" ).datepicker({ dateFormat: 'yy-mm-dd'});  
	});

	$("select").change(function() {
		$("select option[value='0']").attr('disabled','disabled');
		var fromDate, toDate;
    		// str = $("select option:selected").text();
    		val = $("select option:selected").val();
		$('.filter').data('val', val);
		var today = new Date();
		var dd = today.getDate();
		if(dd<10){dd='0'+dd;}
		var mm = today.getMonth()+1; 
		if(mm<10){mm='0'+mm;} 
		var yyyy = today.getFullYear();
		var todayDate = yyyy+'-'+mm+'-'+dd;

		var newDate = new Date();
		if(val==1){
			fromDate = toDate = todayDate;	
			clearInterval(autohandle);								 			
			drawFilteredChart(gateway_id, device_id, device_type1, device_type2, fromDate, toDate);	
			autohandle = setInterval(function(){ drawFilteredChart(gateway_id, device_id, device_type1, device_type2, fromDate, toDate); }, 60000);        

		}
		else if(val==2){		 			
			newDate.setDate(newDate.getDate() - 7);
			var dd = newDate.getDate();
			if(dd<10){dd='0'+dd;}
			var mm = newDate.getMonth()+1; 
			if(mm<10){mm='0'+mm;}
			var yyyy = newDate.getFullYear();			
			var last7 = yyyy + "-"+ mm +"-"+ dd;
			
			fromDate = last7;
			toDate = todayDate;
			clearInterval(autohandle);
			drawFilteredChart(gateway_id, device_id, device_type1, device_type2, fromDate, toDate);
			autohandle = setInterval(function(){ drawFilteredChart(gateway_id, device_id, device_type1, device_type2, fromDate, toDate); }, 60000);        
			
		}
		else if(val==3){
			newDate.setDate(newDate.getDate() - 30);
			var dd = newDate.getDate();
			if(dd<10){dd='0'+dd;}
			var mm = newDate.getMonth()+1; 
			if(mm<10){mm='0'+mm;}
			var yyyy = newDate.getFullYear();			
			var last30 = yyyy + "-"+ mm +"-"+ dd;
			
			fromDate = last30;
			toDate = todayDate;
			clearInterval(autohandle);
			drawFilteredChart(gateway_id, device_id, device_type1, device_type2, fromDate, toDate);
			autohandle = setInterval(function(){ drawFilteredChart(gateway_id, device_id, device_type1, device_type2, fromDate, toDate); }, 60000);
		}
		else if(val==4){			
			$("span").show();
		}

  		$('.filter').data('fromDate', fromDate);
		$('.filter').data('toDate', toDate);
		$('.filter').data('val', val);
	})

	
}



function showStreamFilter(gateway_id, device_id, device_type1, nick_name)
{
	var str, val=0;

	var startTime, endTime;

	if(device_type1 == '12'){
		var filter_html = '&nbsp;&nbsp;&nbsp;&nbsp; Filters:&nbsp;&nbsp; <select class="filterSelect"><option value="0" selected>Select</option><option value="1" >Last Minute</option><option value="2">Last 10 Minutes</option>' +
			'<option value="3">Last Hour</option><option value="4">Custom Report</option></select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
			'&nbsp;&nbsp;&nbsp;&nbsp; <span class="dateTimeInputs"> Date :&nbsp;&nbsp; <input type="text" id="startTime" display="none" style="width:130px;margin-top:12px"> &nbsp;&nbsp;&nbsp;&nbsp; <input type="text" id="endTime" display="none" style="width:80px;margin-top:12px"/>' +
			'&nbsp;&nbsp; <button id="sfilter"> Submit </button> &nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-file-excel-o fa-lg" id="download-custom-csv" title="CSV Download" style="cursor:pointer;color:green"></i> </span>' +
			'<span class="AxisRadios"> <label class="radio-inline"><input type="radio" name="Accstream" onclick="changeStreamGraph();" value="all" checked>All</label> &nbsp;' +
			'<label class="radio-inline"><input type="radio" name="Accstream" onclick="changeStreamGraph();" value="xaxis">X-Axis</label> &nbsp;' +
			'<label class="radio-inline"><input type="radio" name="Accstream" onclick="changeStreamGraph();" value="yaxis">Y-Axis</label> &nbsp;' +
			'<label class="radio-inline"><input type="radio" name="Accstream" onclick="changeStreamGraph();" value="zaxis">Z-axis</label> &nbsp;' +
			'<label class="radio-inline"><input type="radio" name="Accstream" onclick="changeStreamGraph();" value="aggregate">Aggregated Value</label> &nbsp; ' +
			'&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-file-excel-o fa-lg" id="download-csv" title="CSV Download" style="cursor:pointer;color:green"></i> </span> <br>' +
			'<br><b style="margin-left:19px">Average values:</b><table class="avgtable"></table>';
	}else{
		var filter_html = '&nbsp;&nbsp;&nbsp;&nbsp; Filters:&nbsp;&nbsp; <select class="filterSelect"><option value="0" selected>Select</option><option value="1" >Last Minute</option><option value="2">Last 10 Minutes</option>' +
			'<option value="3">Last Hour</option><option value="4">Custom Report</option></select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
			'&nbsp;&nbsp;&nbsp;&nbsp; <span class="dateTimeInputs"> Date :&nbsp;&nbsp; <input type="text" id="startTime" display="none" style="width:130px;margin-top:12px"> &nbsp;&nbsp;&nbsp;&nbsp; <input type="text" id="endTime" display="none" style="width:80px;margin-top:12px"/>' +
			'&nbsp;&nbsp; <button id="sfilter"> Submit </button> &nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-file-excel-o fa-lg" id="download-custom-csv" title="CSV Download" style="cursor:pointer;color:green"></i> </span>' +
			'<span class="AxisRadios"> <label class="radio-inline"><input type="radio" name="Accstream" onclick="changeStreamGraph();" value="all" checked>All</label> &nbsp;' +
			'<label class="radio-inline"><input type="radio" name="Accstream" onclick="changeStreamGraph();" value="xaxis">Axial</label> &nbsp;' +
			'<label class="radio-inline"><input type="radio" name="Accstream" onclick="changeStreamGraph();" value="yaxis">Horizontal</label> &nbsp;' +
			'<label class="radio-inline"><input type="radio" name="Accstream" onclick="changeStreamGraph();" value="zaxis">Vertical</label> &nbsp; ' +
			'<label class="radio-inline"><input type="radio" name="Accstream" onclick="changeStreamGraph();" value="aggregate">Aggregated Value</label> &nbsp; ' +
			'&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-file-excel-o fa-lg" id="download-csv" title="CSV Download" style="cursor:pointer;color:green"></i> ' +
			'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Unit:&nbsp;&nbsp; <select class="unit"><option value="m" selected>Meter</option><option value="mm" >MilliMeter</option><option value="mim">Microns</option>' +
			'</select> </span> <br>' +
			'<br><b style="margin-left:19px">Average values:</b><table class="avgtable"></table>';

	}
	
					
	$('.filter').html(filter_html);
	$('.dateTimeInputs').hide();

	$('.filter').data('gateway_id', gateway_id);
	$('.filter').data('device_id', device_id);
	$('.filter').data('device_type1', device_type1);
	$('.filter').data('nick_name', nick_name);
	
	$('.filter').data('val', val);

	$(function() {
		 			
		$( "#startTime" ).datetimepicker({ dateFormat: 'yy-mm-dd' }); 
		$( "#endTime" ).timepicker(); 
	});

	
	$("select").change(function() {
		$("select option[value='0']").attr('disabled','disabled');
		var selectedDate;

    		val = $("select option:selected").val();
		$('.filter').data('val', val);


		var unit = $('.unit :selected').val();
		$('.filter').data('unit',unit);

		var today = new Date();

		var dd = today.getDate();
		if(dd<10){dd='0'+dd;}
		var mm = today.getMonth()+1; 
		if(mm<10){mm='0'+mm;} 
		var yyyy = today.getFullYear();
		var todayDate = yyyy+'-'+mm+'-'+dd;

		var newDate = new Date();
		if(val==1){
			$('.dateTimeInputs').hide();
			$('.AxisRadios').show();
			endTime = moment.utc().format('YYYY-MM-DD HH:mm:ss');
			startTime = moment.utc().subtract(1, "minutes").format('YYYY-MM-DD HH:mm:ss');
			
			clearInterval(autohandle);

			drawStreamFilteredChart(gateway_id, device_id, device_type1, startTime, endTime);
			autohandle = setInterval(function(){ drawStreamFilteredChart(gateway_id, device_id, device_type1, startTime, endTime); }, 60000);

			

		}
		else if(val==2){		 			
			$('.dateTimeInputs').hide();
			$('.AxisRadios').show();
			endTime = moment.utc().format('YYYY-MM-DD HH:mm:ss');
			startTime = moment.utc().subtract(10, "minutes").format('YYYY-MM-DD HH:mm:ss');


			clearInterval(autohandle);


			drawStreamFilteredChart(gateway_id, device_id, device_type1, startTime, endTime);
			autohandle = setInterval(function(){ drawStreamFilteredChart(gateway_id, device_id, device_type1, startTime, endTime); }, 60000);

		}
		else if(val==3){
			$('.dateTimeInputs').hide();
			$('.AxisRadios').show();
			selectedDate = todayDate;						
			endTime = moment.utc().format('YYYY-MM-DD HH:mm:ss');
			startTime = moment.utc().subtract(60, "minutes").format('YYYY-MM-DD HH:mm:ss');			 			
			

			clearInterval(autohandle);

			drawStreamFilteredChart(gateway_id, device_id, device_type1, startTime, endTime);
			autohandle = setInterval(function(){ drawStreamFilteredChart(gateway_id, device_id, device_type1, startTime, endTime); }, 60000);


		}
		else if(val==4){			
			$('.dateTimeInputs').show();
			$('.AxisRadios').hide();
			clearInterval(autohandle);

		}
		else if(val==5){
			selectedDate = todayDate;									 			
			
			startTime = moment.utc().startOf('day').format('YYYY-MM-DD HH:mm:ss');
			endTime = moment.utc().endOf('day').format('YYYY-MM-DD HH:mm:ss');
			clearInterval(autohandle);

			drawStreamFilteredChart(gateway_id, device_id, device_type1, startTime, endTime);
			autohandle = setInterval(function(){ drawStreamFilteredChart(gateway_id, device_id, device_type1, startTime, endTime); }, 60000);


		}else{
			changeStreamGraph();
		}



		$('.filter').data('val', val);
		$('.filter').data('startTime', startTime);
		$('.filter').data('endTime', endTime);
	})

	

	
}



$(document).on('click', '#sfilter', function(e)
{
	$('.filterData').html('');
//	$('.filter').html(" ");
    	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
    	var gateway_id = $('.filter').data('gateway_id');
    	var device_id = $('.filter').data('device_id');
    	var device_type1 = $('.filter').data('device_type1');
	var device_type2 = $('.filter').data('device_type2');
	var startT = $("#startTime").val();
	var endT = $("#endTime").val();
	var radiostreamval = $("input[name='Accstream']:checked").val();


	endT = startT.substring(0, 11) + endT;

	var startTime = moment(startT).utc().format('YYYY-MM-DD HH:mm');
	var endTime = moment(endT).utc().format('YYYY-MM-DD HH:mm');

	var zone_offset = moment().local().format('Z');


	var diff = moment(endT).utc() - moment(startT).utc();
	diff = diff/1000/60/60;


	if(startTime == "Invalid date" || endTime == "Invalid date"){

		alert('Please select both start and end time!');
	}

	if(startTime > endTime){
		alert('Start Time cannot be greater than the end time. Please reselect!');
	}

	//if(diff > 1){
	//	alert('The graph will not be readable. Request you to please select duration of Max. 1 hour! You can download CSV file for a day!');
	//}

	$('.filter').data('startTime', startTime);
	$('.filter').data('endTime', endTime);
	clearInterval(autohandle);

    	if(uid!= '' && apikey!= '') {
		// window.open("http://cm-jp.sensegiz.com/sensegiz-dev/portal/user/analyticscscvdownloaddata.php?gateway_id=" + gateway_id + "&device_id=" + device_id + "&radiostreamval=" + radiostreamval + "&startTime=" + startTime + "&endTime=" + endTime + "&zone_offset=" + encodeURIComponent(zone_offset),"_blank");
		drawStreamFilteredChart(gateway_id, device_id, device_type1, startTime, endTime);

	}
});



function drawStreamFilteredChart(gateway_id, device_id, device_type1, startTime, endTime)
{
    	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
	var date_format  =  $('#sesval').data('date_format');
	var rms_values  =  $('#sesval').data('rms_values');

	var unit = $('.unit :selected').val();

	$('input[name="Accstream"][value="all"]').prop('checked', 'checked');

	$('.filterData').html(" ");

	if(myLineChart!=null){
        	myLineChart.destroy();
    	}


	if(uid!= '' && apikey!= '') {
        	$.ajax({
            		url: basePathUser + analyticsStreamFilteredChartData + '/' + gateway_id + '/' + device_id + '/' + device_type1 + '/' + startTime + '/' + endTime,
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

				if(data.records.length>1){
					var records = data.records.reverse();
						//var bread_html = '';
												
					drawChart(device_type1);

					var avg_x = 0;
					var avg_y = 0;
					var avg_z = 0;
					var avg_cnt_x = 0;
					var avg_cnt_y = 0;
					var avg_cnt_z = 0;
					var average_x = 0;
					var average_y = 0;
					var average_Z = 0;

						$.each(records, function (index, value){
							var devType = value.device_type;
							
							var device_value = value.device_value;

							if(device_type1 == '17' || device_type1 == '20' || device_type1 == '23'){
								if(unit == 'mm'){
									device_value = device_value * 1000;
								}
								if(unit == 'mim'){
									device_value = device_value * 1000000;
								}
								
							}

							if(rms_values == 0 && (device_type1 == '20' || device_type1 == '23')){
								device_value = device_value/0.707;
							}
						
							var last_updated_on      =  '';
							last_updated_on         = value.updated_on;
							var stillUtc = moment.utc(last_updated_on).toDate();							
							last_updated_on         =  moment(stillUtc).local().format(date_format);							
						

													
							
							if(devType == '12'  || devType == '17' || devType == '20' || devType == '23'){
								myLineChart.data.datasets[0].data.push(device_value);	
								avg_x = parseFloat(avg_x) + parseFloat(device_value);
								avg_cnt_x = avg_cnt_x + 1;						
							}

							if(devType == '14' || devType == '18' || devType == '21' || devType == '24'){
								myLineChart.data.datasets[1].data.push(device_value);	
								avg_y = parseFloat(avg_y) + parseFloat(device_value);	
								avg_cnt_y = avg_cnt_y + 1;						
							}

							if(devType == '15' || devType == '19' || devType == '22' || devType == '25'){
								myLineChart.data.datasets[2].data.push(device_value);
								var parts = last_updated_on.split(' ');							
								myLineChart.data.labels.push([parts[0],parts[1]]);	
								avg_z = parseFloat(avg_z) + parseFloat(device_value);
								avg_cnt_z = avg_cnt_z + 1;						
							}

							if(devType == '28'  || devType == '29' || devType == '30' || devType == '31'){
								myLineChart.data.datasets[3].data.push(device_value);	
														
							}
						
						});
						setUnit(device_type1, unit);
						

						myLineChart.update();	

																	
						average_x = (avg_x/avg_cnt_x).toFixed(7);
						average_y = (avg_y/avg_cnt_y).toFixed(7);						
						average_z = (avg_z/avg_cnt_z).toFixed(7);										
					}
					else{
							var ww_html = '<h4>No Data Found!</h4>';
							$("input[type=radio]").attr('disabled', true);
							
							$('.filterData').html(ww_html);
							

							
                   			 }
					var avg_html = '<tr><th style="background-color:#3b5998;color:white;">X axis</th><td>'+average_x+'</td><th style="background-color:#1dcaff;color:white;">Y axis</th>'
								+'<td >'+average_y+'</td><th style="background-color:#b00;color:white;">Z axis</th><td>'+average_z+'</td></tr>';

					$('.avgtable').html(avg_html);

			},
			error: function(errData, status, error) {

            		}



		});
	}
}



function changeStreamGraph() 
{
	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
    	var gateway_id = $('.filter').data('gateway_id');
    	var device_id = $('.filter').data('device_id');
	var nick_name = $('.filter').data('nick_name');
    	var device_type1 = $('.filter').data('device_type1');


	var startTime = $('.filter').data('startTime');
	var endTime = $('.filter').data('endTime');

	var dropVal = $('.filter').data('val');


	var radiostreamval = $("input[name='Accstream']:checked").val();

	

	if(myLineChart!=null){
        	myLineChart.destroy();
    	}

	if(dropVal==0){
		var newurl = basePathUser + analyticsAcceStream + '/' + gateway_id + '/' + device_id + '/' + device_type1;
	}else{
		var newurl = basePathUser + analyticsStreamFilteredChartData + '/' + gateway_id + '/' + device_id + '/' + device_type1 + '/' + startTime + '/' + endTime;
	}

	if(uid!= '' && apikey!= '') {
		clearInterval(autohandle);
		autoChangeStreamGraph(gateway_id, device_id, device_type1, nick_name, startTime, endTime, radiostreamval, newurl); 		
		autohandle = setInterval(function(){ autoChangeStreamGraph(gateway_id, device_id, device_type1, nick_name, startTime, endTime, radiostreamval, newurl); }, 60000);

	}
}


function autoChangeStreamGraph(gateway_id, device_id, device_type1, nick_name, startTime, endTime, radiostreamval, newurl) 
{
	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
	var date_format  =  $('#sesval').data('date_format');
	var rms_values  =  $('#sesval').data('rms_values');

	var unit = $('.unit :selected').val();

	$.ajax({
            		
            		url: newurl,
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
				if(data.records.length>1){
					var records = data.records.reverse();						
												
					drawChart(device_type1);

					var avg_x = 0;
					var avg_y = 0;
					var avg_z = 0;
					var avg_cnt_x = 0;
					var avg_cnt_y = 0;
					var avg_cnt_z = 0;
					var average_x = 0;
					var average_y = 0;
					var average_Z = 0;

						$.each(records, function (index, value){
							var devType = value.device_type;
							
							var device_value = value.device_value;
						
							var last_updated_on      =  '';
							last_updated_on         = value.updated_on;
							var stillUtc = moment.utc(last_updated_on).toDate();							
							last_updated_on         =  moment(stillUtc).local().format(date_format);							
						

							if(device_type1 == '17' || device_type1 == '20' || device_type1 == '23'){
								if(unit == 'mm'){
									device_value = device_value * 1000;
								}
								if(unit == 'mim'){
									device_value = device_value * 1000000;
								}
								
							}


							if(rms_values == 0 && (device_type1 == '20' || device_type1 == '23')){
								device_value = device_value/0.707;
							}

							if((devType == '12' || devType == '17' || devType == '20' || devType == '23') && radiostreamval == "xaxis")
							{
								myLineChart.data.datasets[0].data.push(device_value);	
								var parts = last_updated_on.split(' ');							
								myLineChart.data.labels.push([parts[0],parts[1]]);
								avg_x = parseFloat(avg_x) + parseFloat(device_value);
								avg_cnt_x = avg_cnt_x + 1;						
							}
							if((devType == '14' || devType == '18' || devType == '21' || devType == '24') && radiostreamval == "yaxis"){
								myLineChart.data.datasets[1].data.push(device_value);
								var parts = last_updated_on.split(' ');							
								myLineChart.data.labels.push([parts[0],parts[1]]);	
								avg_y = parseFloat(avg_y) + parseFloat(device_value);	
								avg_cnt_y = avg_cnt_y + 1;						
							}

							if((devType == '15' || devType == '19' || devType == '22' || devType == '25') && radiostreamval == "zaxis"){
								myLineChart.data.datasets[2].data.push(device_value);
								var parts = last_updated_on.split(' ');							
								myLineChart.data.labels.push([parts[0],parts[1]]);	
								avg_z = parseFloat(avg_z) + parseFloat(device_value);
								avg_cnt_z = avg_cnt_z + 1;						
							}

							if((devType == '28' || devType == '29' || devType == '30' || devType == '31') && radiostreamval == "aggregate"){
								myLineChart.data.datasets[3].data.push(device_value);
								var parts = last_updated_on.split(' ');							
								myLineChart.data.labels.push([parts[0],parts[1]]);	
														
							}


							if(radiostreamval == "all"){
								if(devType == '12' || devType == '17' || devType == '20' || devType == '23'){
									myLineChart.data.datasets[0].data.push(device_value);
									var parts = last_updated_on.split(' ');							
									myLineChart.data.labels.push([parts[0],parts[1]]);	
									avg_x = parseFloat(avg_x) + parseFloat(device_value);
									avg_cnt_x = avg_cnt_x + 1;					
								}

								if(devType == '14' || devType == '18' || devType == '21' || devType == '24'){
									myLineChart.data.datasets[1].data.push(device_value);	
									avg_y = parseFloat(avg_y) + parseFloat(device_value);	
									avg_cnt_y = avg_cnt_y + 1;					
								}

								if(devType == '15' || devType == '19' || devType == '22' || devType == '25'){
									myLineChart.data.datasets[2].data.push(device_value);
									avg_z = parseFloat(avg_z) + parseFloat(device_value);
									avg_cnt_z = avg_cnt_z + 1;
														
								}

								if(devType == '28' || devType == '29' || devType == '30' || devType == '31'){
									myLineChart.data.datasets[3].data.push(device_value);
									
														
								}


							}
						});

					setUnit(device_type1, unit);
					showBreadCrumb(gateway_id, device_id, nick_name, device_type1, uid, apikey);

					myLineChart.update();
				
					average_x = (avg_x/avg_cnt_x).toFixed(7);
					average_y = (avg_y/avg_cnt_y).toFixed(7);						
					average_z = (avg_z/avg_cnt_z).toFixed(7);

					var avg_html = '<tr><th style="background-color:#3b5998;color:white;">X axis</th><td>'+average_x+'</td><th style="background-color:#1dcaff;color:white;">Y axis</th>'
								+'<td >'+average_y+'</td><th style="background-color:#b00;color:white;">Z axis</th><td>'+average_z+'</td></tr>';

					$('.avgtable').html(avg_html);
				}
				else {
					var ww_html = '<h4>No Data Found!</h4>';
					$("input[type=radio]").attr('disabled', true);
					$('.filterData').html(ww_html);
					showBreadCrumb(gateway_id, device_id, nick_name, device_type1, uid, apikey);

				}
			},
			error: function(errData, status, error) {

            		}

        	});
}


function drawFilteredChart(gateway_id, device_id, device_type1, device_type2, fromDate, toDate)
{
    	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
	var date_format  =  $('#sesval').data('date_format');
	var temp_unit  =  $('#sesval').data('temp_unit');

	$('input[name="threshold"][value="both"]').prop('checked', 'checked');
	$('input[name="streams"][value="temp"]').prop('checked', 'checked');

	$('.filterData').html(" ");

	if(myLineChart!=null){
        	myLineChart.destroy();
    	}

	if(fromDate != '' && toDate != ''){
		var fdate = moment(fromDate).toDate().toISOString();
		var tdate = moment(toDate).add(24, 'hours').toDate().toISOString();

		var res = fdate.replace('T',' ');
		fromDate = res.replace('.000Z','');


		var res1 = tdate.replace('T',' ');
		toDate = res1.replace('.000Z','');
	}

	if(uid!= '' && apikey!= '') {
        $.ajax({
            url: basePathUser + apiUrlAnalyticsFilteredChartData + '/' + gateway_id + '/' + device_id + '/' + device_type1 + '/' + device_type2 + '/' + fromDate + '/' + toDate,
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
			if(device_type1 == '26'){
					if(data.records.length>1){
						var records = data.records.reverse();
						//var bread_html = '';
												
						drawChart(device_type1);
						$.each(records, function (index, value){
							var temp = value.temperature;
							
							var humid = value.humidity;

							var abshumid = value.absolutehumidity;
							
							var dewpoint = value.dewpointtemperature;
						
							var last_updated_on      =  '';
							last_updated_on         = value.updated_date;
							var stillUtc = moment.utc(last_updated_on).toDate();							
							last_updated_on         =  moment(stillUtc).local().format(date_format);							
							

								myLineChart.data.datasets[0].data.push(temp);	
								var parts = last_updated_on.split(' ');							
								myLineChart.data.labels.push([parts[0],parts[1]]);
														
							


								myLineChart.data.datasets[1].data.push(humid);	
														
							
								myLineChart.data.datasets[2].data.push(dewpoint);	
														
							
								myLineChart.data.datasets[3].data.push(abshumid);
						
						});
						//setUnit(device_type1, 0);
						myLineChart.update();																		
																
					}
					else{
					 	var ww_html = '<h4>No Data Found!</h4>'; 
						$("input[type=radio]").attr('disabled', true);
						$('#download-xls').prop('disabled', true);
						$('#download-pdf').prop('disabled', true);
						$('.filterData').html(ww_html);						
					}				
				
				}
				else if(device_type1 == '09'){
					if(data.records.length>1){
						var records = data.records.reverse();
						//var bread_html = '';
												
						drawChart(device_type1);
						$.each(records, function (index, value){
							var devType = value.device_type;
							
							var device_value = value.device_value;
						
							var last_updated_on      =  '';
							last_updated_on         = value.updated_on;
							var stillUtc = moment.utc(last_updated_on).toDate();							
							last_updated_on         =  moment(stillUtc).local().format(date_format);							
							
							if(devType=='09' && temp_unit == 'Fahrenheit'){                                                            
								if(device_value!=null && device_value!='null' && device_value!=''){ 
									device_value = (device_value * 1.8) + 32;	
								}
								device_value = device_value.toFixed(3);
                                                        }

							
							myLineChart.data.datasets[0].data.push(device_value);							
							var parts = last_updated_on.split(' ');							
							myLineChart.data.labels.push([parts[0],parts[1]]);
							
						
						});
						setUnit(device_type1, 0);
						myLineChart.update();																		
																
					}
					else{
						var ww_html = '<h4>No Data Found!</h4>';
						$("input[type=radio]").attr('disabled', true);
						$('#download-xls').prop('disabled', true);
						$('#download-pdf').prop('disabled', true);
						$('.filterData').html(ww_html);						
					}				
				
				}
				else{
					if(data.records.length>1){
						var records = data.records.reverse();
						drawChart(device_type1);

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

							if(devType == 01 || devType==02){
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

							if(devType == 03 || devType==04){
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
	
												
					}else{
							var ww_html = '<h4>No Data Found!</h4>';
							$("input[type=radio]").attr('disabled', true);
							$('#download-xls').prop('disabled', true);
							$('#download-pdf').prop('disabled', true);						
							$('.filterData').html(ww_html);
					}            	   					
				
				}
			},
            error: function(errData, status, error) {
                console.log('Error');
            }
		});
	}
}


function changeGraph()
{
	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
    	var gateway_id = $('.filter').data('gateway_id');
    	var device_id = $('.filter').data('device_id');
    	var device_type1 = $('.filter').data('device_type1');
	var device_type2 = $('.filter').data('device_type2');
	var fromDate = $("#from").val();
	var toDate = $("#to").val();
	var dropVal = $('.filter').data('val');
	var from = $('.filter').data('fromDate');
	var to = $('.filter').data('toDate');


	var radioval = $("input[name='threshold']:checked").val();
	var radiostreamval = $("input[name='streams']:checked").val();

	

	if(myLineChart!=null){
        	myLineChart.destroy();
    	}

	if(radioval == "low"){
		device_type2 = device_type1;
	}
	if(radioval == "high"){
		device_type1 = device_type2;
	}
	if(radiostreamval == "temp"){
		device_type2 = device_type1;
	}
	if(radiostreamval == "humid"){
		device_type1 = device_type2;
	}
	if(radiostreamval == "dptah"){
		device_type1 = '26';
		device_type2 = '27';
	}


	if(dropVal != 0){
		fromDate = from;
		toDate = to;
	}
	//console.log(device_type1);
	//console.log(device_type2);
	if(fromDate != '' && toDate != ''){
		var fdate = moment(fromDate).toDate().toISOString();
		var tdate = moment(toDate).add(24, 'hours').toDate().toISOString();

		var res = fdate.replace('T',' ');
		fromDate = res.replace('.000Z','');

		var res1 = tdate.replace('T',' ');
		toDate = res1.replace('.000Z','');
	}

	var url1 = basePathUser + apiUrlAnalyticsDeviceSensorData + '/' + gateway_id + '/' + device_id + '/' + device_type1 + '/' + device_type2;	
	var url2 = basePathUser + apiUrlAnalyticsFilteredChartData + '/' + gateway_id + '/' + device_id + '/' + device_type1 + '/' + device_type2 + '/' + fromDate + '/' + toDate;

    	if(uid!= '' && apikey!= '') 
	{	
		clearInterval(autohandle);	
		autoChangeGraph(gateway_id, device_id, device_type1, device_type2, fromDate, toDate, url1, url2, radioval);
		autohandle = setInterval(function(){ autoChangeGraph(gateway_id, device_id, device_type1, device_type2, fromDate, toDate, url1, url2, radioval); }, 60000);

	}

}


function autoChangeGraph(gateway_id, device_id, device_type1, device_type2, fromDate, toDate, url1, url2, radioval)
{
	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
	var date_format  =  $('#sesval').data('date_format');
	var temp_unit  =  $('#sesval').data('temp_unit');

	$.ajax({
            		
            		url: (fromDate == '') ? url1 : url2,
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
				if(device_type1 == '26'){
					if(data.records.length>1){
						var records = data.records.reverse();
						//var bread_html = '';
												
						drawChart(device_type1);
						$.each(records, function (index, value){
							var temp = value.temperature;
							
							var humid = value.humidity;

							var abshumid = value.absolutehumidity;
							
							var dewpoint = value.dewpointtemperature;
						
							var last_updated_on      =  '';
							last_updated_on         = value.updated_date;
							var stillUtc = moment.utc(last_updated_on).toDate();							
							last_updated_on         =  moment(stillUtc).local().format(date_format);							
							

							myLineChart.data.datasets[0].data.push(temp);	
							var parts = last_updated_on.split(' ');							
							myLineChart.data.labels.push([parts[0],parts[1]]);
																					
							myLineChart.data.datasets[1].data.push(humid);																						
							myLineChart.data.datasets[2].data.push(dewpoint);																						
							myLineChart.data.datasets[3].data.push(abshumid);																			
						
						});
						//setUnit(device_type1, 0);
						myLineChart.update();																		
						
						
					}
					else{
						var ww_html = '<h4>No Data Found!</h4>'; 
						$("input[type=radio]").attr('disabled', true);
						$('#download-xls').prop('disabled', true);
						$('#download-pdf').prop('disabled', true);
						$('.filterData').html(ww_html);
						
					}
				}
				else if(device_type1 == '09' || device_type1 == '10'){
					if(data.records.length>1){
						var records = data.records.reverse();
						//var bread_html = '';
												
						drawChart(device_type1);
						$.each(records, function (index, value){
							var devType = value.device_type;
							
							var device_value = value.device_value;
						
							var last_updated_on      =  '';
							last_updated_on         = value.updated_on;
							var stillUtc = moment.utc(last_updated_on).toDate();							
							last_updated_on         =  moment(stillUtc).local().format(date_format);							
							
							if(devType=='09' && temp_unit == 'Fahrenheit'){                                                            
								if(device_value!=null && device_value!='null' && device_value!=''){ 
									device_value = (device_value * 1.8) + 32;	
								}
								device_value = device_value.toFixed(3);
							}

							myLineChart.data.datasets[0].data.push(device_value);							
							var parts = last_updated_on.split(' ');							
							myLineChart.data.labels.push([parts[0],parts[1]]);
							
						
						});
						setUnit(device_type1, 0);
						myLineChart.update();																		
						
						
					}
					else{
						var ww_html = '<h4>No Data Found!</h4>';
						$("input[type=radio]").attr('disabled', true);
						$('#download-xls').prop('disabled', true);
						$('#download-pdf').prop('disabled', true);
						$('.filterData').html(ww_html);
						
					}
				}
				else
				{
					if(data.records.length>1){
						var records = data.records.reverse();						
												
						drawChart(device_type1);
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


							if(devType == 01 || devType==02){
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

							if(devType == 03 || devType==04){
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

							if(radioval == "low"){
								myLineChart.data.datasets[0].data.push(decLowThres);							
								myLineChart.data.datasets[1].data.push(device_value);
							}
							if(radioval == "high"){
								myLineChart.data.datasets[2].data.push(decHighThres);																																					
								myLineChart.data.datasets[1].data.push(device_value);
							}
							if(radioval == "both"){
								myLineChart.data.datasets[0].data.push(decLowThres);
								myLineChart.data.datasets[2].data.push(decHighThres);																																					
								myLineChart.data.datasets[1].data.push(device_value);
							}
														
							var parts = last_updated_on.split(' ');							
							myLineChart.data.labels.push([parts[0],parts[1]]);
							
						
						});
						setUnit(device_type1, 0);
						myLineChart.update();																		
												
					}else{
							var ww_html = '<h4>No Data Found!</h4>';
							$("input[type=radio]").attr('disabled', true);
							$('#download-xls').prop('disabled', true);
							$('#download-pdf').prop('disabled', true);
							$('.filterData').html(ww_html);
							
                   			 }
            	   
				}	

        		},
            		error: function(errData, status, error) {
                		console.log("Error...");
            		}

        	});

}


function accStreamDataconvert(dval)
{
	var binsize = 15;
	var size = 4;
	var axis_data = dval;
	var b = [];
	var bval = [];
	var sum = 0;
	var d = [];
	var val = [];

	if((dval & 0x8000)==0x8000)
    	{	
		var i=0;
		axis_data = (~axis_data) + 1;
		
		while(binsize != 0)
		{
			b.push(axis_data & 0x1);			
			axis_data = axis_data >> 1;
			binsize--;			
			
			bval.push(b[i] * Math.pow(2,i));
			sum = sum + bval[i];
			i++;
			
		}
		
		return(-1 * sum);
		
		
	}
	else
	{
		var i=0;
				
		while(size != 0)
		{
			d.push(axis_data & 0xF);			
			axis_data = axis_data >> 4;
			size--;									
			
		}
		
		val.push(d[0]);	
		val.push(d[1] * 16);
		val.push(d[2] * 256);
		val.push(d[3] * 4096);	

		for(i=0;i<4;i++)
		{				
			sum = sum + val[i];
		}
		
		return(sum);

	}
	
}

$(document).on('click', '#download-custom-csv', function(e)
{

    	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
	var date_format  =  $('#sesval').data('date_format');
	var rms_values  =  $('#sesval').data('rms_values');

    	var gateway_id = $('.filter').data('gateway_id');
    	var device_id = $('.filter').data('device_id');
    	var device_type = $('.filter').data('device_type1');

    	//var device_type1 = "12', '14', '15'";


	if(device_type == '12'){ device_type1 = "12', '14', '15', '28"; }
	else if(device_type == '17'){ device_type1 = "17', '18', '19', '29"; }
	else if(device_type == '20'){ device_type1 = "20', '21', '22', '30"; }
	else if(device_type == '23'){ device_type1 = "23', '24', '25', '31"; }


	var startT = $("#startTime").val();
	var endT = $("#endTime").val();
	var radiostreamval = $("input[name='Accstream']:checked").val();
		
	endT = startT.substring(0, 11) + endT;

	var unit = $('.unit :selected').val();
	var startTime = moment(startT).utc().format('YYYY-MM-DD HH:mm');
	var endTime = moment(endT).utc().format('YYYY-MM-DD HH:mm');

	var zone_offset = moment().local().format('Z');

	var nick_name = $('.filter').data('nick_name');			

	if(uid!= '' && apikey!= '') {
         	window.open(server_address+"/sensegiz-dev/portal/user/analyticscscvdownloaddata.php?gateway_id=" + gateway_id + "&device_id=" + device_id + "&nick_name=" + nick_name + "&device_type=" + device_type + "&device_type1=" + device_type1 + "&startTime=" + startTime + "&endTime=" + endTime + "&unit=" + unit + "&date_format=" + date_format + "&rms_values=" + rms_values + "&zone_offset=" + encodeURIComponent(zone_offset),"_blank");
	}

});



$(document).on('click', '#download-csv', function(e)
{
	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
	var date_format  =  $('#sesval').data('date_format');
	var rms_values  =  $('#sesval').data('rms_values');

    	var gateway_id = $('.filter').data('gateway_id');
    	var device_id = $('.filter').data('device_id');
    	var device_type = $('.filter').data('device_type1');

	var unit = $('.unit :selected').val();
	
	var dropVal = $('.filter').data('val');

	var startTime = $('.filter').data('startTime');
	var endTime = $('.filter').data('endTime');

	var unit = $('.unit :selected').val();

	var radiostreamval = $("input[name='Accstream']:checked").val();

	var nick_name = $('.filter').data('nick_name');

	if(radiostreamval == "xaxis")
	{
		device_type1 = device_type;
	}
	if(radiostreamval == "yaxis")
	{
		if(device_type == '12'){ device_type1='14'; }
		else if(device_type == '17'){ device_type1='18'; }
		else if(device_type == '20'){ device_type1='21'; }
		else if(device_type == '23'){ device_type1='24'; }
	}
	if(radiostreamval == "zaxis")
	{
		if(device_type == '12'){ device_type1='15'; }
		else if(device_type == '17'){ device_type1='19'; }
		else if(device_type == '20'){ device_type1='22'; }
		else if(device_type == '23'){ device_type1='25'; }
	}
	if(radiostreamval == "all")
	{
		if(device_type == '12'){ device_type1 = "12', '14', '15', '28"; }
		else if(device_type == '17'){ device_type1 = "17', '18', '19', '29"; }
		else if(device_type == '20'){ device_type1 = "20', '21', '22', '30"; }
		else if(device_type == '23'){ device_type1 = "23', '24', '25', '31"; }
	
	}
	if(radiostreamval == "aggregate")
	{
		if(device_type == '12'){ device_type1='28'; }
		else if(device_type == '17'){ device_type1='29'; }
		else if(device_type == '20'){ device_type1='30'; }
		else if(device_type == '23'){ device_type1='31'; }
	}


	

	var zone_offset = moment().local().format('Z');

	
	if(uid!= '' && apikey!= '') {
         	window.open(server_address+"/sensegiz-dev/portal/user/analyticscscvdownloaddata.php?gateway_id=" + gateway_id + "&device_id=" + device_id + "&nick_name=" + nick_name + "&device_type=" + device_type + "&device_type1=" + device_type1 + "&startTime=" + startTime + "&endTime=" + endTime + "&unit=" + unit + "&date_format=" + date_format + "&rms_values=" + rms_values  + "&zone_offset=" + encodeURIComponent(zone_offset),"_blank");
	}

});


$(document).on('click', '#download-xls', function(e)
{
	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
	var date_format  =  $('#sesval').data('date_format');
	var temp_unit  =  $('#sesval').data('temp_unit');

    	var gateway_id = $('.filter').data('gateway_id');
    	var device_id = $('.filter').data('device_id');
    	var device_type1 = $('.filter').data('device_type1');
	var device_type2 = $('.filter').data('device_type2');
	var fromDate = $("#from").val();
	var toDate = $("#to").val();
	var dropVal = $('.filter').data('val');
	var from = $('.filter').data('fromDate');
	var to = $('.filter').data('toDate');

	var radioval = $("input[name='threshold']:checked").val();
	var radiostreamval = $("input[name='streams']:checked").val();

	var nick_name = $('.filter').data('nick_name');

	
	var zone_offset = moment().local().format('Z');
	

	if(radioval == "low"){
		device_type2 = device_type1;
	}
	if(radioval == "high"){
		device_type1 = device_type2;
	}
	if(radiostreamval == "temp"){
		device_type2 = device_type1;
	}
	if(radiostreamval == "humid"){
		device_type1 = device_type2;
	}
	if(radiostreamval == "dptah"){
		device_type1 = '26';
		 device_type2 = '27';
	}


	if(dropVal != 0){
		fromDate = from;
		toDate = to;
	}

	if(fromDate != '' && toDate != ''){
		var fdate = moment(fromDate).toDate().toISOString();
		var tdate = moment(toDate).add(24, 'hours').toDate().toISOString();

		var res = fdate.replace('T',' ');
		fromDate = res.replace('.000Z','');

		var res1 = tdate.replace('T',' ');
		toDate = res1.replace('.000Z','');
	}

	if(uid!= '' && apikey!= '') {
         	window.open(server_address+"/sensegiz-dev/portal/user/analyticsdownloaddata.php?gateway_id=" + gateway_id + "&date_format=" + date_format + "&device_id=" + device_id + "&device_type1=" + device_type1 + "&device_type2=" + device_type2 + "&fromDate=" + fromDate + "&toDate=" + toDate + "&radioval=" + radioval + "&temp_unit=" + temp_unit + "&zone_offset=" + encodeURIComponent(zone_offset),"_blank");
	}

});




$(document).on('click', '#download-pdf', function(e)
{
	var nick_name = $('.filter').data('nick_name');


	var uid = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
	var date_format  =  $('#sesval').data('date_format');
	var temp_unit  =  $('#sesval').data('temp_unit');

    	var gateway_id = $('.filter').data('gateway_id');
    	var device_id = $('.filter').data('device_id');
    	var device_type1 = $('.filter').data('device_type1');
	var device_type2 = $('.filter').data('device_type2');
	var fromDate = $("#from").val();
	var toDate = $("#to").val();
	var dropVal = $('.filter').data('val');
	var from = $('.filter').data('fromDate');
	var to = $('.filter').data('toDate');


	var radioval = $("input[name='threshold']:checked").val();
	var radiostreamval = $("input[name='streams']:checked").val();

	var zone_offset = moment().local().format('Z');


	if(radioval == "low"){
		device_type2 = device_type1;
	}
	if(radioval == "high"){
		device_type1 = device_type2;
	}
	if(radiostreamval == "temp"){
		device_type2 = device_type1;
	}
	if(radiostreamval == "humid"){
		device_type1 = device_type2;
	}
	if(radiostreamval == "dptah"){
		device_type1 = '26';
		 device_type2 = '27';
	}



	if(dropVal != 0){
		fromDate = from;
		toDate = to;
	}

	
	if(fromDate != '' && toDate != ''){
		var fdate = moment(fromDate).toDate().toISOString();
		var tdate = moment(toDate).add(24, 'hours').toDate().toISOString();

		var res = fdate.replace('T',' ');
		fromDate = res.replace('.000Z','');


		var res1 = tdate.replace('T',' ');
		toDate = res1.replace('.000Z','');

	}

	var url1 = basePathUser + apiUrlAnalyticsDeviceSensorData + '/' + gateway_id + '/' + device_id + '/' + device_type1 + '/' + device_type2;	
	var url2 = basePathUser + apiUrlAnalyticsFilteredChartData + '/' + gateway_id + '/' + device_id + '/' + device_type1 + '/' + device_type2 + '/' + fromDate + '/' + toDate;

	if(uid!= '' && apikey!= '') {
		$.ajax({
            		
            		url: (fromDate == '') ? url1 : url2,
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
				if(device_type1 == '26'){
					if(data.records.length>1){
						var records = data.records;
						var rows = [];
						var columns = [];
						//var bread_html = '';											
						$.each(records, function (index, value){																			

							var temp = value.temperature;
							
							var humid = value.humidity;

							var abshumid = value.absolutehumidity;
							
							var dewpoint = value.dewpointtemperature;
						
							var last_updated_on      =  '';
							last_updated_on         = value.updated_date;
							var stillUtc = moment.utc(last_updated_on).toDate();							
							last_updated_on         =  moment(stillUtc).local().format(date_format);							
							

							rows.push([last_updated_on, temp, humid, dewpoint, abshumid]);
																		
						
						});
						var columns = ["Updated On (date)", "Temperature", "Humidity", "DewPoint Temperature", "Absolute Humidity"];																		
						downloadPDF(columns, rows, gateway_id, device_id, device_type1, nick_name, dropVal, fromDate, toDate, 0, 0);
																		
						
						
					}
					
				}
				else if(device_type1 == '09' || device_type1 == '10'){
					if(data.records.length>1){
						var records = data.records;
						//var bread_html = '';
						var rows = [];
						var columns = [];
						$.each(records, function (index, value){
							var devType = value.device_type;
							
							var device_value = value.device_value;
						
							var last_updated_on      =  '';
							last_updated_on         = value.updated_on;
							var stillUtc = moment.utc(last_updated_on).toDate();							
							last_updated_on         =  moment(stillUtc).local().format(date_format);							
									
							if(devType=='09' && temp_unit == 'Fahrenheit'){                                                            
								if(device_value!=null && device_value!='null' && device_value!=''){ 
									device_value = (device_value * 1.8) + 32;	
								}
								device_value = device_value.toFixed(3);
                                                        }
					
							rows.push([last_updated_on, device_value]);
							
						
						});
						var columns = ["Updated On (date)", "Device Value"];																		
						downloadPDF(columns, rows, gateway_id, device_id, device_type1, nick_name, dropVal, fromDate, toDate, 0, 0);
						
					}
					
				}
				else
				{
					if(data.records.length>1){
						var records = data.records;						
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


							if(devType == 01 || devType==02){
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

							if(devType == 03 || devType==04){
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

							if(device_value > decLowThres)
								lowcross++;

							if(device_value > decHighThres)
								highcross++;

							if(radioval == "low"){								
								columns = ["Updated On (date)", "Device Value", "Threshold"];
								rows.push([last_updated_on, device_value, decLowThres]);
							}
							if(radioval == "high"){
								rows.push([last_updated_on, device_value, decHighThres]);
								columns = ["Updated On (date)", "Device Value", "Threshold"];
							}
							if(radioval == "both"){								
								rows.push([last_updated_on, device_value, decLowThres, decHighThres]);
								columns = ["Updated On (date)", "Device Value", "Low Threshold", "High Threshold"];
							}
																											
						});
					

						downloadPDF(columns, rows, gateway_id, device_id, device_type1, nick_name, dropVal, fromDate, toDate, lowcross, highcross);																		
												
					}
            	   
				}	

        		},
            		error: function(errData, status, error) {
                		console.log("Error...");
            		}

        	});


	}
	
});



function downloadPDF(columns, rows, gateway_id, device_id, device_type, nick_name, dropVal, fromDate, toDate, lowcross, highcross)
{		
	 var gateway_id = $('.filter').data('gateway_id');
    	var device_id = $('.filter').data('device_id');
    	var device_type1 = $('.filter').data('device_type1');
	var temp_unit  =  $('#sesval').data('temp_unit');

	var dev_type;
	if(device_type == '01' || device_type == '02') {
		dev_type = 'Accelerometer (g)';
	}
	
	if(device_type == '03' || device_type == '04') {
		dev_type = 'Gyroscope (DPS)';
	}
	
	if((device_type == '05' || device_type == '06') && temp_unit == 'Celsius') {
		dev_type = "Temperature (" + decodeURI('%C2%B0') + "C)";
	}

	if((device_type == '05' || device_type == '06') && temp_unit == 'Fahrenheit') {
		dev_type = "Temperature (" + decodeURI('%C2%B0') + "F)";
	}
	
	if(device_type == '07' || device_type == '08') {
		dev_type = 'Humidity (% RH)';
	}	
	
	if(device_type == '09' && temp_unit == 'Celsius') {
		dev_type = 'Temperature Stream (' + decodeURI('%C2%B0') + "C)";

	}

	if(device_type == '09' && temp_unit == 'Fahrenheit') {
		dev_type = 'Temperature Stream (' + decodeURI('%C2%B0') + "F)";

	}

	if(device_type == '10') {
		dev_type = 'Humidity Stream (% RH)';
	}

	
	var doc = new jsPDF();
												
	var canvas = document.getElementById('graph');
	var canvasImg = canvas.toDataURL();
						

	var img = new Image();
	img.src = server_address+'/sensegiz-dev/portal/img/logo.png';
        						         
	img.onload = function(){
		doc.setFontSize(15);
        	doc.addImage(img, 'PNG', 160, 5, 40 , 18);
		doc.text(90, 20, "Report");
		doc.setFontSize(10);
		doc.text(15, 40, "Below are the metric details of the coin selected : ");
		doc.text(15, 45, "Gateway ID : " + gateway_id);
		doc.text(15, 50, "Device ID : " + device_id);
		doc.text(15, 55, "Nick Name : " + nick_name);
		
		if(device_type != '26'){
			doc.text(15, 60, "Metric : " + dev_type);
		}				


		if(dropVal == 0)
			doc.text(15, 70, "Below table shows the last 10 values received from the sensor : ");
		if(dropVal == 1)
			doc.text(15, 70, "Below table shows the values received today ("+fromDate+") :");
		if(dropVal == 2)
			doc.text(15, 70, "Below table shows the values received in the last 7 days ("+fromDate+" to "+toDate+") :" );
		if(dropVal == 3)
			doc.text(15, 70, "Below table shows the values received in the last 30 days ("+fromDate+" to "+toDate+") : ");

		if(device_type != '09' && device_type != '10' && device_type != '26')
		{
			doc.text(15, 75, "No. of times value crossed the low threshold :" + lowcross);
			doc.text(15, 80, "No. of times value crossed the high threshold :" + highcross);
		}

		doc.autoTable(columns, rows, {startY: 85});
		doc.addPage();
		doc.internal.scaleFactor = 1.8;
		var width = doc.internal.pageSize.width;    	
		doc.addImage(canvasImg, 'JPEG', 5, 20, width-20 , 70);

		doc.save('canvas.pdf');
        }

}


function showBreadCrumb(gateway_id, device_id, nick_name, device_type, uid, apikey)
{

	var rms_values  =  $('#sesval').data('rms_values');


	if(rms_values == 0){
		var rms = 'Non-RMS';
	}else{
		var rms = 'RMS';
	}


	var dev_type = '';
	var bread_html = '';

	if(device_id== 0 && device_type == 0) {
		
		bread_html = '<ol class="breadcrumb"><li class="breadcrumb-item">Gateway: <a href="javascript:;" class="bCrum">'+gateway_id+'</a></li></ol>';
		
		$('.breadCrum').html(bread_html);
		$('.bCrum').data('gateway', gateway_id);

	}
	if(device_id!=0 && device_type == 0) {
		
		bread_html = '<ol class="breadcrumb"><li class=breadcrumb-item">Gateway: <a href="javascript:;" class="bCrum">'+gateway_id+'</a></li><li class="breadcrumb-item">'+nick_name+'</li></ol>';
	
		$('.breadCrum').html(bread_html);
		$('.bCrum').data('gateway', gateway_id);

	}
	if(device_id!=0 && device_type != 0) {
	
			
                	if(device_type == '01') {
                	    dev_type = 'Accelerometer';
                	}
                	if(device_type == '03') {
                 	   dev_type = 'Gyroscope';
                	}

                	if(device_type == '05') {
	                    dev_type = 'Temperature';
        	        }
                	
        	        if(device_type == '07') {
                	    dev_type = 'Humidity';
	                }	
        	        
        	        if(device_type == '09') {
                	    dev_type = 'Streams';
                	}
      
			if(device_type == '12') {
                	    dev_type = 'Accelerometer Stream';
                	}

			if(device_type == '17') {
                	   	dev_type = 'Acceleration (' + rms + ')'; 
                	}

		if(device_type == '20') {
                	   	dev_type = 'Velocity (' + rms + ')'; 
                	}

		if(device_type == '23') {
                	   	dev_type = 'Displacement (' + rms + ')'; 
                	}


                bread_html = '<ol class="breadcrumb"><li class="breadcrumb-item">Gateway: <a href="javascript:;" class="bCrum">'+gateway_id+'</a></li><li class="breadcrumb-item"> '+nick_name+'</a></li><li class="breadcrumb-item">'+dev_type+'</li></ol>';		
       
		$('.breadCrum').html(bread_html);
		$('.bCrum').data('gateway', gateway_id);

        
	}
}



var myLineChart = null;
var autohandle = null;
helpGetGateways();		
</script>