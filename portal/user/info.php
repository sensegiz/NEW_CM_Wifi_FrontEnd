<?php 
//1 error shows
//0 supresses

//error_reporting(E_ALL);
//ini_set("display_errors", 0);
//ob_start();
include_once('page_header_user.php');

//session_start();

//print_r($_SESSION);exit();
?>
<!--<link rel="stylesheet" href="css/jquery-ui.css">-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<style>
.table {

    width: 48%;
    max-width: 100%;
    margin-bottom: 30px;
    text-align: center;
    float:left;
  //margin-left: 194px;
    padding-right: 38px;
	//box-shadow: 18px 7px 15px 0 rgba(17, 6, 6, 0.23);
    
    margin-right: 10px;
}

.table-lp td {

    color: #bbbbbb;
    color: #2C3E50;
    font-size: 12px;
    padding-bottom: 0px !important;
    padding-top:4px !important;
    vertical-align: middle !important;

}
.batLevel {
	padding: 15px 15px 15px 15px;
	border: 2px grey;
	text-align: center;
	text-transform: uppercase;
	font-weight: bold;
	font-size: 12px;
	color: white;
	margin: 10px;
	width: 37%

}

.col-sm-1, .col-md-4{
	background-color:#e7e7e7;
	padding: 15px 15px 15px 15px;
	border: 2px grey;
	text-align: center;
	text-transform: uppercase;
	font-weight: bold;
	font-size: 11px;
	color: white;
	margin: 10px;
	height:auto;
	//width: 17%
}


.black{
	border-style: groove;
	background-color:#ffffff;
	color: #000000;
}

.green{
	background-color: #44ADA9;
}

.green:hover{
	background-color: #44ADA9;
}

.orange{
	background-color: #ff9800;
}

.orange:hover{
	background-color: #e68a00;
}

.red{
	background-color: #f44336;
}

.red:hover{
	background-color: #da190b;
}

.blue{
	background-color: #2196F3;
}

.blue:hover{
	background-color: #0b7dda;
}

.grey{
	background-color: #b3a7a7;
	color:#000;
}

.grey:hover{
	background-color: #C8C4C4;
	color:#000;
}

.box{
	width:20px;
	height:10px;
	padding: 5px 5px 5px 5px;
	border: 2px grey;
}

.battery img{
	height:15px; 
	width:25px;
}

.battery {
	text-align: center;
}
.col-1{
	width:17%;
	float:left;
	margin-left:17px;
	margin-bottom:7px;
}
@media only screen and (min-width: 200px) and (max-width:495px){

.table {

    width: 100%;
    max-width: 100%;
    margin-bottom: 30px;
    text-align: center;
    float: left;
    //margin-left: 194px;
    padding-right: 38px;
    //box-shadow: 18px 7px 15px 0 rgba(17, 6, 6, 0.23);
    margin-right: 10px;
 
}
.col-1{
	width:40%;
	float:left;
	margin-left:17px;
	margin-bottom:7px;
}
.col-sm-1, .col-md-4{
	background-color:#e7e7e7;
	padding: 15px 15px 15px 15px;
	border: 2px grey;
	text-align: center;
	text-transform: uppercase;
	font-weight: bold;
	font-size: 10px;
	color: white;
	margin: 10px;
	height:auto;
	width: 25%;
	float:left;
}

.black{
	border-style: groove;
	background-color:#ffffff;
	color: #000000;
}

.green{
	background-color: #44ADA9;
}

.green:hover{
	background-color: #44ADA9;
}

.orange{
	background-color: #ff9800;
}

.orange:hover{
	background-color: #e68a00;
}

.red{
	background-color: #f44336;
}

.red:hover{
	background-color: #da190b;
}

.blue{
	background-color: #2196F3;
}

.blue:hover{
	background-color: #0b7dda;
}

.grey{
	background-color: #b3a7a7;
	color:#000;
}

.grey:hover{
	background-color: #DDD;
	color:#000;
}

.box{
	width:20px;
	height:10px;
	padding: 5px 5px 5px 5px;
	border: 2px grey;
}

.battery img{
	height:15px; 
	width:25px;
}

.battery {
	text-align: left;
}

}
@media only screen and (min-width: 496px) and (max-width:900px){

.col-1{
	//width:40%;
	float:left;
	margin-left:17px;
	margin-bottom:7px;
}
.col-sm-1, .col-md-4{
	background-color:#e7e7e7;
	padding: 15px 15px 15px 15px;
	border: 2px grey;
	text-align: center;
	text-transform: uppercase;
	font-weight: bold;
	font-size: 10px;
	color: white;
	margin: 10px;
	height: auto;
	width: 21%;
	float:left;
}
.black{
	border-style: groove;
	background-color:#ffffff;
	color: #000000;
}

.green{
	background-color: #44ADA9;
}

.green:hover{
	background-color: #44ADA9;
}

.orange{
	background-color: #ff9800;
}

.orange:hover{
	background-color: #e68a00;
}

.red{
	background-color: #f44336;
}

.red:hover{
	background-color: #da190b;
}

.blue{
	background-color: #2196F3;
}

.blue:hover{
	background-color: #0b7dda;
}

.grey{
	background-color: #b3a7a7;
	color:#000;
}

.grey:hover{
	background-color: #DDD;
	color:#000;
}

.box{
	width:20px;
	height:10px;
	padding: 5px 5px 5px 5px;
	border: 2px grey;
}

.battery img{
	height:15px; 
	width:25px;
}

.battery {
	text-align: left;
}
}
@media only screen and (min-width: 560px) and (max-width:900px){
	[class*="col-"] {
   	 float: left;

}
}

@media only screen and (min-width: 496px) and (max-width:650px){
.table {

    width: 100%;
    max-width: 100%;
    margin-bottom: 30px;
    text-align: center;
    float:left;
  //margin-left: 194px;
    padding-right: 38px;
	//box-shadow: 18px 7px 15px 0 rgba(17, 6, 6, 0.23);
    
    margin-right: 10px;
}

}
@media only screen and (min-width: 650px) and (max-width:900px){
.table {

    width: 48%;
    max-width: 100%;
    margin-bottom: 30px;
    text-align: center;
    float:left;
  //margin-left: 194px;
    padding-right: 38px;
	//box-shadow: 18px 7px 15px 0 rgba(17, 6, 6, 0.23);
    
    margin-right: 10px;
}

}
</style>




<!--<div class="col-lg-10 pad0 ful_sec"> 
      	<div class="row pad0">-->
        <div class="content userpanel">
<?php 
    include './user_menu.php';
?>

<div class="container-fluid">
<div class="col-sm-10 col-10 mains">
         
          <!--toggle sidebar button-->
           <p class="visible-xs">
            <button type="button" class="btn btn-default btn-sm" id="more" id="menu-toggle" data-toggle="offcanvas" style="margin-bottom:10px"><span  class="glyphicon glyphicon-chevron-left"></span> Gateways </button>
          </p>
            <h1>Gateway and Coin Info</h1><hr>
	    <div class="mine"></div>
                <!--<h3 id="">Devices</h3>-->
            <div class="detail-content" style="background-color: #fff; z-index:0">

                <!-- -->
                
                <div class="lp-det" style="margin-top:30px">
                    
                        <span class='error-tab'></span>
				<div class="row">
					<div class="breadCrum" style="margin-left: 16px;margin-right: 25px;"></div>

					<div class="col-sm-6 deviceImg">
						<p>Click on any gateway</p>
					</div>
					
					<div class="col-sm-6 gwData" style="z-index:0">
					</div>
				</div>
<br/>
				<div class="coinId">
				</div>

				 <div class="row colCode">
					

				</div>


                        	<div class="row gwCoin"></div>

                        	                            
                            <div id='loader'><img src="../img/loader.gif"/ > Loading Data</div>
                        
                  
                </div>                
         
            </div>
        </div>

        </div>
      </div>

<?php
    include_once('page_footer_user.php');
?>

<script type="text/javascript">
var server_address = getBaseAddressUser();

basePathUser      =   server_address+"/sensegiz-dev/user/";
        
var apiUrlGateways            = "gateways";
var apiUrlHelpDevices             = "help-devices";
var apiUrlHelpGetCoinSensor         = 'help-get-device-sensor';

var subscribersArr  =   [];


	/*
	 * Function 			: helpGetGateways()
	 * Brief 			: load the list of Gateways	 
	 * Input param 			: Nil
	 * Input/output param           : NA
	 * Return			: NA
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
                                        var gateway_mac_id  = value.gateway_mac_id;
                                        var date        = '';
					var added_on    = value.added_on;
					var updated_on  = value.data_received_on;
					var is_blacklisted = value.is_blacklisted;
					var is_active = value.active;
					var gateway_version = value.gateway_version;
					var status = value.status;

										// var res_gw_nickname = getGateway_nickname(gateway_id, uid, apikey);
										var res_gw_nickname = value.gateway_nick_name;
										var gateway_name;
										if (res_gw_nickname == '' || res_gw_nickname == null || res_gw_nickname == 'null') {
											gateway_name = gateway_id;
										} else {
											gateway_name = res_gw_nickname;
										}

                                        if(value.updated_on!=''){
            //                               date      =  added_on +'Z'; 
                                           date         = updated_on;
                                           var stillUtc = moment.utc(date).toDate();
                                           date         = moment(stillUtc).local().format(date_format);
                                        }

					if(added_on != '')
					{
                                        	var stillUtc = moment.utc(added_on).toDate();							
						added_on =  moment(stillUtc).local().format(date_format);														
                                        }
					
					if(status == 'Online'){
						status_html = '&nbsp&nbsp&nbsp<img src="../img/online.png"/>';
					}else{
						status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';

					}

		                      sc_html  =  '<tr><td>Click on any Gateways</td></tr>';

                                        gw_li_html += '<li><a href="javascript:;" class="helpGateway" data-gateway="'+gateway_id+'"  data-gateway_mac="'+gateway_mac_id+'"  data-gateway_version="'+gateway_version+'"  data-is_active="'+is_active+'" data-is_blacklisted="'+is_blacklisted+'" data-added_on="'+added_on+'" data-updated_on="'+date+'">'+gateway_name+''+status_html+'</a></li>';
                                    });                        
                                }
                                else{
                                        sc_html  =  '<tr><td width="300">No Gateways Found</td></tr>';
                                }
                                $('#gatewaysList').html(sc_html); 

                                $('.gateway-list-lfnav').html(gw_li_html);
				$('.colCode').hide();
                    
                    
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

	/*
	 * Function 			: helpGetDevices(gatewayId, date, addedOn, updatedOn, isBlacklisted)
	 * Brief 			: load the list of Devices for a gateway	 
	 * Input param 			: gatewayId
	 * Input/output param           : NA
	 * Return			: NA
	 */		
	function helpGetDevices(gatewayId, gateway_mac_id, addedOn, updatedOn, isBlacklisted, active, gateway_version) {
             $('#loader').show();
             //Remove sticky notfn box
             
            var uid     =  $('#sesval').data('uid');
            var apikey  =  $('#sesval').data('key');
	var date_format  =  $('#sesval').data('date_format');

            if(uid!='' && apikey!=''){
			showBreadCrumb(gatewayId, 0, 0, uid, apikey, addedOn, updatedOn, isBlacklisted, active);
                            $.ajax({
                                    url: basePathUser + apiUrlHelpDevices +'/' + gatewayId,
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
					$('.colCode').show();
                                        $('#loader').hide();
					$('.deviceImg').html('<img class="img-responsive" src="../img/gateway.jpg" style="height:200px; width: 400px; padding:10px;margin-left:-7px;"></img>');
					
					var sc_html = '';
					var gc_html = '';
					var cc_html = '';
					var color_html = '';
					var on = '';
						length = data.records.length;

					if(length == 0){ 
						sc_html +='<p>No Coins Registered to this Gateway.</p>';
					}else{
						
						var now = moment().format(date_format);
						ms = moment(now,date_format).diff(moment(updatedOn,date_format));
						d = moment.duration(ms);
						s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");

						a =  s.split(':');
						hour   = a[0];
						minute = a[1];
						second = a[2]; 
						sc_html += '<p>Gateway BLE Mac Address: '+gatewayId+' </p><br/>'
							  +'<p>Gateway Wifi Mac Address: '+gateway_mac_id+' </p><br/>'
							  +'<p>Registration Date: '+addedOn+'</p><br/>';
						
							
						sc_html +=  '<p>Blacklisted: '+isBlacklisted+'</p><br/>';
	
						sc_html +=  '<p>Number Of Coins registered to this Gateway: '+length+'</p><br/>';

						if(gateway_version != null){

							sc_html +=  '<p>Current Version: '+gateway_version.substring(18)+'</p><br/>';

							sc_html +=  '<p>Next Update: '+gateway_version.substring(0, 17)+'</p><br/>';
					
						}

						$('.gwData').html(sc_html); 

						color_html = '<h5 style="font-weight:bold"> &nbsp;&nbsp;&nbsp;&nbsp;Color Code Info.</h5>'
							+'<div class="col-1"><div class="box green"></div><p>Device Active</p></div>'					
							+'<div class="col-1"><div class="box grey"></div><p>Device Inactive</p></div>'
							+'<div class="col-1 battery"><div><img src="../img/full_battery.png"></div><p>Battery Full</p></div>'
							+'<div class="col-1 battery"><div><img src="../img/medium_battery.png"></div><p>Battery Medium</p></div>'
							+'<div class="col-1 battery"><div><img src="../img/low_battery.png"></div><p>Battery Low</p></div><br/><br/><br/>';

						$('.colCode').html(color_html);
 
						i = 0;
						iClass = '';
						for(i=0; i<data.records.length; i++){
							nick_name  = data.records[i].nick_name;
							updated_on = data.records[i].updated_on;
							device_id  = data.records[i].device_id;
							battery    = data.records[i].battery;
							active     = data.records[i].active;
							status     = data.records[i].status;

							//sc_html +=  '<p>Number Of Coins registered to this Gateway: '+length+'</p><br/>';

                                                        stillUtc = moment.utc(updated_on).toDate();
                                                        updated_on         =  moment(stillUtc).local().format(date_format);

						var now = moment().format(date_format);
						ms = moment(now,date_format).diff(moment(updated_on,date_format));
						d = moment.duration(ms);
						
						s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");

						var chk = s.charAt(0);
						if(chk == '-'){
							s = s.slice(1, s.length);
						}

						a =  s.split(':');
						hour   = a[0];
						minute = a[1];

						second = a[2]; 

						if(active == 'Y'){
							iClass = 'green';
						}else
						if(active == 'N'){
							iClass = 'grey';
						} 

						if(battery == '01'){
							var bat_html = '<div class="battery"><img src="../img/full_battery.png"></div>';
						}else
						if(battery == '02'){
							var bat_html = '<div class="battery"><img src="../img/medium_battery.png"></div>';
						}else
						if(battery == '03'){
							var bat_html = '<div class="battery"><img src="../img/low_battery.png"></div>';
						}else{
							var bat_html = '<div class="battery"><img src="../img/full_battery.png"></div>';
						}

						

						if(hour == 0 && minute == 0 && second < 60) {
							//gc_html += '<div class="col-sm-1'+iClass+'" href="javascript:;" data-coin_id="'+device_id+'" data-gw="'+gatewayId+'" data-nick_name="'+nick_name+'" data-added_on="'+addedOn+'" data-updated_on="'+updatedOn+'" data-is_blacklisted="'+isBlacklisted+'" data-is_active="'+active+'"><span>'+ bat_html +'<img src="../img/marker.png" style="length:30px; width:25px;">'
								  // +''+nick_name+'<br/>Last Seen: '+second+' seconds ago</p><br/></span></div>';
							
							gc_html += '<table class="table  table-bordered table-lp '+iClass+'"  " style="text-align:center" href="javascript:;">'
								+'<td data-coin_id="'+device_id+'" data-gw="'+gatewayId+'"  data-nick_name="'+nick_name+'" data-added_on="'+addedOn+'" data-updated_on="'+updatedOn+'" data-is_blacklisted="'+isBlacklisted+'" data-is_active="'+active+'"style="width:50px"><span>'+ bat_html +'</span>'
								+'<td style="width:180px;font-weight:700;font-size:12px">Last Seen: '+second+' seconds ago<br/></td>'
								+'<td><img src="../img/marker.png" style="padding-top:10px; width:25px;"><br>'+nick_name+'</p><br/></td>'


						} else

						if(hour == 0 && minute>0) {
							
							//gc_html += '<div class="col-sm-1  '+iClass+'" href="javascript:;" data-coin_id="'+device_id+'" data-gw="'+gatewayId+'"  data-nick_name="'+nick_name+'" data-added_on="'+addedOn+'" data-updated_on="'+updatedOn+'" data-is_blacklisted="'+isBlacklisted+'" data-is_active="'+active+'"><span>'+ bat_html +'<img src="../img/marker.png" style="length:30px; width:25px;">'
								  // +''+nick_name+'<br/>Last Seen: '+minute+' minutes ago</p><br/></span></div>';
							gc_html += '<table class="table  table-bordered table-lp '+iClass+'"  " style="text-align:center" href="javascript:;">'
								+'<td data-coin_id="'+device_id+'" data-gw="'+gatewayId+'"  data-nick_name="'+nick_name+'" data-added_on="'+addedOn+'" data-updated_on="'+updatedOn+'" data-is_blacklisted="'+isBlacklisted+'" data-is_active="'+active+'"style="width:50px"><span>'+ bat_html +'</span>'
								+'<td style="width:180px;font-weight:700;font-size:12px">Last Seen: '+minute+' minutes ago<br/></td>'
								+'<td><img src="../img/marker.png" style="padding-top:10px; width:25px;"><br>'+nick_name+'</p><br/></td>'

						} else
						
						if(hour!=0) {
							if(hour>23){
								day = Math.floor(hour / 24);
								if(day<29){

									//gc_html += '<div class="col-sm-1  '+iClass+'" href="javascript:;" data-coin_id="'+device_id+'" data-gw="'+gatewayId+'"  data-nick_name="'+nick_name+'" data-added_on="'+addedOn+'" data-updated_on="'+updatedOn+'" data-is_blacklisted="'+isBlacklisted+'" data-is_active="'+active+'"><span>'+ bat_html +'<img src="../img/marker.png" style="length:30px; width:25px;">'
									  // +''+nick_name+'<br/>Last Seen: '+day+' days ago</p><br/></span></div>';
										
									gc_html += '<table class="table  table-bordered table-lp '+iClass+'"  " style="text-align:center" href="javascript:;">'
										+'<td data-coin_id="'+device_id+'" data-gw="'+gatewayId+'"  data-nick_name="'+nick_name+'" data-added_on="'+addedOn+'" data-updated_on="'+updatedOn+'" data-is_blacklisted="'+isBlacklisted+'" data-is_active="'+active+'"style="width:50px"><span>'+ bat_html +'</span>'
										+'<td style="width:180px;font-weight:700;font-size:12px">Last Seen: '+day+' days ago<br/></td>'
										+'<td><img src="../img/marker.png" style="padding-top:10px; width:25px;"><br>'+nick_name+' </p><br/></td>'



								} else {

									month = Math.floor(day / 30);
									if(month>11)
									{
										year = Math.floor(month / 12);
										//gc_html += '<div class="col-sm-1  '+iClass+'" href="javascript:;" data-coin_id="'+device_id+'" data-gw="'+gatewayId+'"  data-nick_name="'+nick_name+'" data-added_on="'+addedOn+'" data-updated_on="'+updatedOn+'" data-is_blacklisted="'+isBlacklisted+'" data-is_active="'+active+'"><span>'+ bat_html +'<img src="../img/marker.png" style="length:30px; width:25px;">' 
									  // +''+nick_name+'<br/>Last Seen: '+years+' years ago</p><br/></span></div>';
										
										gc_html += '<table class="table  table-bordered table-lp '+iClass+'"  " style="text-align:center" href="javascript:;">'
											+'<td data-coin_id="'+device_id+'" data-gw="'+gatewayId+'"  data-nick_name="'+nick_name+'" data-added_on="'+addedOn+'" data-updated_on="'+updatedOn+'" data-is_blacklisted="'+isBlacklisted+'" data-is_active="'+active+'"style="width:50px"><span>'+ bat_html +'</span>'
										 	+'<td style="width:180px;font-weight:700;font-size:12px">Last Seen: '+years+' years ago<br/></td>'
										 	+'<td><img src="../img/marker.png" style="padding-top:10px; width:25px;"><br>'+nick_name+'</p><br/></td>'

	
									} else 
										{
											gc_html += '<table class="table  table-bordered table-lp '+iClass+'"  " style="text-align:center" href="javascript:;">'
												+'<td data-coin_id="'+device_id+'" data-gw="'+gatewayId+'"  data-nick_name="'+nick_name+'" data-added_on="'+addedOn+'" data-updated_on="'+updatedOn+'" data-is_blacklisted="'+isBlacklisted+'" data-is_active="'+active+'"style="width:50px"><span>'+ bat_html +'</span>'
										 		+'<td style="width:180px;font-weight:700;font-size:12px">Last Seen: '+month+' months ago<br/></td>'
										 		+'<td><img src="../img/marker.png" style="padding-top:10px; width:25px;"><br>'+nick_name+'</p><br/></td>'
										
										}
								}
							} else {
								//gc_html += '<div class="col-sm-1  '+iClass+'" data-coin_id="'+device_id+'" href="javascript:;" data-gw="'+gatewayId+'"  data-nick_name="'+nick_name+'" data-added_on="'+addedOn+'" data-updated_on="'+updatedOn+'" data-is_blacklisted="'+isBlacklisted+'" data-is_active="'+active+'"><span>'+ bat_html +'<img src="../img/marker.png" style="length:30px; width:25px;">'
								  // +''+nick_name+'<br/>Last Seen: '+hour+' hours  ago</p><br/></span></div>';
								
									gc_html += '<table class="table  table-bordered table-lp '+iClass+'"  " style="text-align:center" href="javascript:;">'
										+'<td data-coin_id="'+device_id+'" data-gw="'+gatewayId+'"  data-nick_name="'+nick_name+'" data-added_on="'+addedOn+'" data-updated_on="'+updatedOn+'" data-is_blacklisted="'+isBlacklisted+'" data-is_active="'+active+'"style="width:50px"><span>'+ bat_html +'</span>'
										+'<td style="width:180px;font-weight:700;font-size:12px">Last Seen: '+hour+' hours  ago<br/></td>'
										+'<td><img src="../img/marker.png" style="padding-top:10px; width:25px;"><br>'+nick_name+'</p><br/></td>'
										

								}
						}	
							'</table><br>';			
							$('.gwCoin').html(gc_html);
							
						}	
										

	

                                    }},
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


        /*
	 * Function 			: Click event on GET devices registered to gateway
	 */	
	$(document).on( "click", ".helpGateway, .bCrum",function(e){
		$('.gwCoin').html('');
		$('.gwData').html('');
		$('.coinId').html('');
                e.preventDefault();

	        var gatewayId  =  $(this).data('gateway');
		var gateway_mac_id  =  $(this).data('gateway_mac');
	        var gateway_version  =  $(this).data('gateway_version');
		var addedOn = $(this).data('added_on');
		var updatedOn = $(this).data('updated_on');
		var isBlacklisted = $(this).data('is_blacklisted');
		var isActive = $(this).data('is_active');
		
		clearInterval(autohandle);
		

		if(gatewayId!=''){                        
                	helpGetDevices(gatewayId, gateway_mac_id, addedOn, updatedOn, isBlacklisted, isActive, gateway_version);
			autohandle = setInterval(function(){ helpGetDevices(gatewayId, gateway_mac_id, addedOn, updatedOn, isBlacklisted, isActive, gateway_version); }, 60000);
		}
        });

$(document).on("click", ".helpCoinList", function(e){
	$('.gwCoin').html('');
	$('#loader').show();
	e.preventDefault();
	var uid = $('#sesval').data('uid');
	var apikey = $('#sesval').data('key');
	var date_format  =  $('#sesval').data('date_format');

	var gateway_id = $(this).data('gw');
	var device_id = $(this).data('coin_id');
	var nick_name = $(this).data('nick_name');

	var addedOn = $(this).data('added_on');
	var updatedOn =	$(this).data('updated_on');
	var isBlacklisted = $(this).data('is_blacklisted');
	var active = $(this).data('is_active');

	if(uid!= '' & apikey!= ''){
		showBreadCrumb(gateway_id, device_id, nick_name, uid, apikey, addedOn, updatedOn, isBlacklisted, active);

		$.ajax({
			url: basePathUser + apiUrlHelpGetCoinSensor + '/' + gateway_id + '/' + device_id,
			type: 'get',
			header: {
				'uid':uid,
				'Api-Key':apikey
			},
			contentType: 'application/json; charset=utf-8',
			dataType: 'json',
			async: false,
			beforeSend: function(xhr) {
				xhr.setRequestHeader("uid", uid);
				xhr.setRequestHeader("Api-Key", apikey);
			},

			success: function(data, textStatus, xhr) {

				$('.colCode').hide();
				$('#loader').hide();
				$('.deviceImg').html('<img src="../img/marker.png" style="height:200px; width: 225px; padding:10px;"></img>');
				$('.gwData').html('');
				var dis_dev_type = '';
				var sc_html = '';
				var vc_html = '';
				var dev_html = '';
				var iClass = '';
				if(data.records.length>0) {
					records = data.records;
					$.each(records, function(index, value){

						device_type = value.device_type;
						last_seen = value.updated_on;
						device_value = value.device_value;
						

                                                        var date      =  '';
                                                        if(value.updated_on!='' && value.updated_on!=null){
                                                            date         = value.updated_on;
                                                            var stillUtc = moment.utc(date).toDate();
                                                            date         =  moment(stillUtc).local().format(date_format);
                                                        }

						var now = moment().format(date_format);
						ms = moment(now,date_format).diff(moment(date,date_format));
						d = moment.duration(ms);
						s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");

						a =  s.split(':');
						hour   = a[0];
						minute = a[1];
						second = a[2];

					
						if(device_type == 01) {
							dis_dev_type = 'accelerometer low';
						}else if(device_type == 02) {
							dis_dev_type = 'accelerometer high';
						}else if(device_type == 03) {
							dis_dev_type = 'gyroscope low';
						}else if(device_type == 04) {
							dis_dev_type = 'gyroscope high';
						}else if(device_type == 05) {
							dis_dev_type = 'temperature low';
						}else if(device_type == 06) {
							dis_dev_type = 'temperature high';
						}else if(device_type == 07) {
							dis_dev_type = 'humidity low';
						}else if(device_type == 08) {
							dis_dev_type = 'humidity high';
						}else if(device_type == 09) {
							dis_dev_type = 'temperature stream';
						}else if(device_type == 10) {
							dis_dev_type = 'humidity stream';
						}else if(device_type == 12) {
							dis_dev_type = 'accelerometer stream';
						}else if(device_type == 11){
							dis_dev_type = 'other';
						}else if(device_type == 13){
							dis_dev_type = 'bat';
						}
					

						if(dis_dev_type != 'other' && dis_dev_type != 'bat'){					
								if(hour == 0 && minute == 0 && second < 60) {
									iClass = 'green';
									sc_html += '<div class="col-sm-1 black"><span>s'
								   		+''+dis_dev_type+'<br/>Last Seen: '+second+' seconds ago</p></span></div>';
								} else if(hour == 0 && minute>0) {
									iClass = 'orange';
									sc_html += '<div class="col-sm-1 black"><span>'
								   		+''+dis_dev_type+'<br/>Last Seen: '+minute+' minutes ago</p></span></div>';
								} else if(hour!=0) {
									if(hour>23){
										day = Math.floor(hour / 24);
										if(day<29){
											sc_html += '<div class="col-sm-1 black"><span>'
								   			+''+dis_dev_type+'<br/>Last Seen: '+day+' days ago</p></span></div>';

										} else {

											month = Math.floor(day / 30);
											if(month>11)
											{
												year = Math.floor(month / 12);
												sc_html += '<div class="col-sm-1 black"><span>'
									   			+''+dis_dev_type+'<br/>Last Seen: '+year+' years ago</p></span></div>';
	
											} else 
											{
												sc_html += '<div class="col-sm-1 black"><span>'
										   		+''+dis_dev_type+'<br/>Last Seen: '+month+' months ago</p></span></div>';
											}
										}
									} else {
										sc_html += '<div class="col-sm-1 black"><span>'
								   		+''+dis_dev_type+'<br/>Last Seen: '+hour+' hours  ago</p></span></div>';
									}
								}
							
						}

						if(device_type == 13){
							if(device_value == 01){
								vc_html += '<div class="col batLevel green"><span><img src="../img/battery_full.png" style="length:40px; width:40px;"><br/>'
									   +'Battery Level: High<br/></span><p>Last Updated: '+date+'</p></div>';
							} else 
							if(device_value == 02 ){
								vc_html += '<div class="col batLevel orange"><span><img src="../img/battery_medium.png" style="length:40px; width:40px;"><br/>'
									   +'Battery Level: Medium<br/></span><p>Last Updated: '+date+'</p></div>';
							}else if(device_value == 03 ){
								vc_html += '<div class="col batLevel red"><span><img src="../img/battery_low.png" style="length:40px; width:40px;"><br/>'
									   +'Battery Level: Low<br/>You should consider replacing the battery<br/></span><p>Last Updated: '+date+'</p></div>';
							}else{
								vc_html += '<div class="col batLevel green"><span><img src="../img/battery_full.png" style="length:40px; width:40px;"><br/>'
									   +'Battery Level: High<br/></span><p>Last Updated: '+date+'</p></div>';
							}
						}
					});
				} else {
					sc_html = '<div class="col-sm-12"><p>no data Found.</p></div>';
					}
				dev_html += '<h4>Coin '+device_id+' Individual Sensor Activity</h4>';
				$('.gwCoin').html(sc_html);
				$('.gwData').html(vc_html);
				$('.coinId').html(dev_html);
			},
			error: function(errData, status, error){
				if(errData.status == 401){
					var resp = errData.responseJSON;
					var description = resp.description;
					$('.logout').click();
					alert(description);
				}
			}
		
		});
	}
});


function showBreadCrumb(gateway_id, device_id, nick_name, uid, apikey, addedOn, updatedOn, isBlacklisted, active)
{
	var bread_html = '';
	var res_gw_nickname = getGateway_nickname(gateway_id, uid, apikey);
	var gateway_name;
	if (res_gw_nickname == '' || res_gw_nickname == null || res_gw_nickname == 'null') {
		gateway_name = gateway_id;
	} else {
		gateway_name = res_gw_nickname;
	}

	if(device_id== 0) {
		
		bread_html = '<ol class="breadcrumb"><li class="breadcrumb-item">Gateway: <a href="javascript:;" class="bCrum">'+gateway_id+ ' ('+ gateway_name +')'  +'</a></li></ol>';
		
		$('.breadCrum').html(bread_html);
		$('.bCrum').data('gateway', gateway_id);
		$('.bCrum').data('added_on', addedOn);
		$('.bCrum').data('updated_on', updatedOn);
		$('.bCrum').data('is_blacklisted', isBlacklisted);
		$('.bCrum').data('is_active', active);
	}

	if(device_id != 0) {
		
		bread_html = '<ol class="breadcrumb"><li class=breadcrumb-item">Gateway: <a href="javascript:;" class="bCrum">'+gateway_id+ ' ('+ gateway_name +')'  +'</a></li><li class="breadcrumb-item">'+nick_name+'</li></ol>';
	
		$('.breadCrum').html(bread_html);
		$('.bCrum').data('gateway', gateway_id);
		$('.bCrum').data('added_on', addedOn);
		$('.bCrum').data('updated_on', updatedOn);
		$('.bCrum').data('is_blacklisted', isBlacklisted);
		$('.bCrum').data('is_active', active);

	}

	
}


var autohandle = null;
helpGetGateways();
</script>