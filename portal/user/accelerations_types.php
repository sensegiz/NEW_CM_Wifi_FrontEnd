<?php 
include_once('page_header_user.php');
?>

<!--<div class="col-lg-10 pad0 ful_sec"> 
      	<div class="row pad0">-->
        <div class="content userpanel">

<style>

.active a label{
    background-color: #fff !important;
    color: #333;
}
@media only screen and (min-width: 200px) and (max-width:425px){
.col-sm-3 {
	width:46%;
	margin-left:-35px;	
}

.col-sm-5 {
	float:right;
	width:46%;
	margin-top:-69px;
	margin-right:70px;
}

}
@media only screen and (min-width: 440px) and (max-width:665px){
.col-sm-3 {
	width:37%;
	margin-left:-35px;	
}

.col-sm-5 {
	float:right;
	width:43%;
	margin-top:-70px;
	margin-right:127px;
}
.dashboardfilters{
	margin-right: 35px;
	float: right;
	margin-top: -70px;
	

} 
}
@media only screen and (min-width: 665px) and (max-width:767px){
.col-sm-3 {
	width:37%;
	margin-left:-35px;	
}

.col-sm-5 {
	float:left;
	width:43%;
	margin-top:-70px;
	margin-left:168px;
}
.dashboardfilters{
	margin-right: 196px;
	float: right;
	margin-top: -70px;
	

}
}
@media only screen and (min-width: 768px) and (max-width:900px){
.col-sm-3 {
	    width: 37%;
    margin-left: -35px;
}

.col-sm-5 {
	    float: left;
    width: 43%;
    margin-top: 0px;
    margin-left: -68px;

}
.dashboardfilters{
	 margin-right: 272px;
    	float: right;
    	margin-top: -31px;
}
}
@media only screen and (min-width: 1000px) and (max-width:20000px){
.col-sm-5 {

    width: 25.66%;

}
}

</style>

 


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        	
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" type="text/css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

        	
<?php 

	include './user_menu.php';
	include './mqtt.php';

?>


	<div class="col-sm-10 col-10 mains">
<!--toggle sidebar button-->
           <p class="visible-xs">
            <button type="button" class="btn btn-default btn-sm" id="more" id="menu-toggle" data-toggle="offcanvas" style="margin-bottom:10px"><span  class="glyphicon glyphicon-chevron-left"></span> Gateways</button>
          </p>

	<div id="lang-set"></div>
		<hr>
	    <div class="mine"></div>                
            <div class="detail-content" style="background-color: #fff;">
		<div class="col-4 alertbar">
			
		</div>
               
                <div class="lp-det" style="margin-top:30px">
                    
                        <span class='error-tab'></span>
                        
                        <div class="row coins" style="margin-left:20px">
								
				<p> Click on any Gateway</p>				
			</div><br/><br/>
			<div class=" accelTables">
                            
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

<script src="../js/mqttws31.js" type="text/javascript"></script> 
<script type="text/javascript">

	var retrievedObject = localStorage.getItem('location_id');
	console.log('retrievedObject: ', JSON.parse(retrievedObject));

	var server_ip = getBaseIPUser();
    console.log('server_ip==',server_ip);
           //Using the HiveMQ public Broker, with a random client Id
// var client = new Messaging.Client("broker.mqttdashboard.com", 8000, "vkmyclientid_" + parseInt(Math.random() * 100, 10));
 var client = new Messaging.Client(server_ip, 8084, "vinkmyclientid_" + parseInt(Math.random() * 100, 10));


 //Gets  called if the websocket/mqtt connection gets disconnected for any reason
 client.onConnectionLost = function (responseObject) {
     //Depending on your scenario you could implement a reconnect logic here
//     alert("connection lost: " + responseObject.errorMessage);
 };

 //Gets called whenever you receive a message for your subscriptions
client.onMessageArrived = function (message) {
     	console.log('--msg recvd--');

	// Retrieve the object from storage
     
    	 var fulMsg = message.payloadString;
     
     	var arrMsg = fulMsg.split(',');
     	console.log(arrMsg);

         var gwId        = arrMsg[0];
         var devId       = arrMsg[1];
         var devType     = arrMsg[2];
         var devValue     = arrMsg[3];
         var devNickname = arrMsg[4];
         var last_updated  = arrMsg[5]; 
	 var request_type  = arrMsg[6];
	var format  = arrMsg[7];
	var rate_value  = arrMsg[8];

	var date_format  =  $('#sesval').data('date_format');

	 var stillUtc = moment.utc(last_updated).toDate();							
	 last_updated =  moment(stillUtc).local().format(date_format); 	

	console.log(devType);

	if(devType=='12' || devType=='14' || devType=='15')
	{			
		var coins = localStorage.getItem("coins_filters");
		var sensors = localStorage.getItem("sensors_filters");

		
		getAccTypesDevices(gwId, coins, sensors);
	}


	
};

 //Connect Options
 var options = {
    useSSL: true,
	userName: "<?php echo $username ?>",
	password: "<?php echo $password ?>",
     timeout: 3,
     //Gets Called if the connection has sucessfully been established
     onSuccess: function () {
//         alert("Connected");
         client.subscribe('myname');
     },
     //Gets Called if the connection could not be established
     onFailure: function (message) {
//         alert("Connection failed: " + message.errorMessage);
     }
 };

 //Creates a new Messaging.Message Object and sends it to the HiveMQ MQTT Broker
 var publish = function (payload, topic, qos) {
     //Send your message (also possible to serialize it as JSON or protobuf or just use a string, no limitations)
     var message = new Messaging.Message(payload);
     message.destinationName = topic;
     message.qos = qos;
     client.send(message);
     console.log('-Publish--');
 }
 client.connect(options);

</script>

<?php
    include_once('page_footer_user.php');
?>




<script type="text/javascript">

var server_address = getBaseAddressUser();

basePathUser      =   server_address+"/sensegiz-dev/user/";
        
var apiUrlGateways = "gateways";
var apiUrlAccTypes = "get-acc-types-devices";


$(document).ready(function(){

	dropd = '<h1>Vibration Monitoring</h1>';		

	
	$('#lang-set').html(dropd);

	
});

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

					if(status == 'Online'){
						status_html = '&nbsp&nbsp&nbsp<img src="../img/online.png"/>';
					}else{
						status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';

					}


                                        sc_html  =  '<tr><td>Click on any Gateways</td></tr>';

                                        gw_li_html += '<li><a href="javascript:;" class="atGw" data-gateway="'+gateway_id+'"  data-is_active="'+is_active+'" data-is_blacklisted="'+is_blacklisted+'" data-added_on="'+added_on+'" data-updated_on="'+date+'">'+gateway_name+''+status_html+'</a></li>'; //gateway_id
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


$(document).on("click", ".atGw", function(e){

    	e.preventDefault();
    	var uid    = $('#sesval').data('uid');
    	var apikey = $('#sesval').data('key');
    	var gateway_id = $(this).data('gateway');

	localStorage.setItem("coins_filters", 0);
	localStorage.setItem("sensors_filters", 0);

	getDashboardCoins(gateway_id);    
	getAccTypesDevices(gateway_id, 0, 0);
    
});


function getAccTypesDevices(gatewayId, coins, sensors) {
             $('#loader').show();
             
            var uid     =  $('#sesval').data('uid');
            var apikey  =  $('#sesval').data('key');

            if(uid!='' && apikey!=''){
                            $.ajax({
                                    url: basePathUser + apiUrlAccTypes +'/' + gatewayId +'/' + coins +'/' + sensors,
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
                                            var sc_html     =  '';                                      
                                            var acc_html    =  '';                                        
                                            var vel_html   =  '';                                        
                                            var disp_html   =  '';                                        
                                                                                                        
                                            records = data.records;
                                            var acceleration = records.acceleration;
                                            var velocity = records.velocity;
                                            var displacement = records.displacement;


						if(acceleration.length>0){
                                                		acc_html =  getFormattedAccTypesData(acceleration,'Acceleration', 0);                                                
                                            		}
						if(velocity.length>0){
                                                		vel_html =  getFormattedAccTypesData(velocity,'Velocity', 0);                                                
                                            		}
						if(displacement.length>0){
                                                		disp_html =  getFormattedAccTypesData(displacement,'Displacement', 0);                                                
                                            		}


						sc_html += ''+acc_html+''+vel_html+''+disp_html;
 
						$('.accelTables').html(sc_html);

						loadSubscriber(gatewayId);  

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
                                            
							                                                

function getFormattedAccTypesData(dataArr, tableName, diff)
{
	var date_format  =  $('#sesval').data('date_format');
	var rms_values  =  $('#sesval').data('rms_values');

	var data_html  =   '<div class="table-responsive"><table class="table table-hover table-bordered table-lp">'
                                +'   <tr><th colspan="10" style="font-size: 15px;">'+tableName+'</th></tr>'
                                +'    <tr class="active tb-hd">'                                                          
                                +'        <th>Sl. No</th>'
                                +'        <th>Nick Name</th>'
                                +'        <th>Sensor Name</th>'
                                +'        <th>Value</th>'
                                +'        <th>Last Updated</th>'	                            
                                +'    </tr>'                                                         
                                +'    <tbody class="users gateways" style="text-align:center;">'; 


	 $.each(dataArr, function (index, value) {
		var device_id     =  value.device_id;
		var gateway_id    =  value.gateway_id;
		var device_type   =  value.device_type;
		var device_value  =  value.device_value;  
		var nick_name = value.nick_name;                                                     
                                                        
		var dis_dev_type = '';
							
							         
		if(rms_values == 0 && (device_type!='17' && device_type!='18' && device_type!='19')){
			device_value = device_value/0.707;
		}					

		if(device_type==null || device_type==''){
                        dis_dev_type   = 'No data';
                }
                                                        
		if(device_type=='17' || device_type=='20' || device_type=='23'){
			dis_dev_type = 'Axial'; 							    
		}else
		if(device_type=='18' || device_type=='21' || device_type=='24'){
                            dis_dev_type = 'Horizontal'; 
		}else
		if(device_type=='19' || device_type=='22' || device_type=='25'){
                            dis_dev_type = 'Vertical'; 
		}else
		if(device_type=='29' || device_type=='30' || device_type=='31'){
                            dis_dev_type = 'Aggregate'; 
		}

						                                                        								
                                                                                                                                                                  		                                                
		var last_updated_on      =  '';
		if(value.updated_on!=null){
			last_updated_on         = value.updated_on;
			var stillUtc = moment.utc(last_updated_on).toDate();
			last_updated_on         =  moment(stillUtc).local().format(date_format);
							
		}
							 
		data_html += '<td>'+(index+1)+'</td>'
			+'<td>'+nick_name+'</a></td>'
                            +'<td>'+dis_dev_type+'</td>'
                            +'<td>'+device_value+'</td>';

		data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>';
							 		

		data_html  +=  '</tr>';                                                                                                        
	});
                                            
	data_html  +=     '</tbody>'
		+'</table></div>';
                                        
	return data_html;
	

}


$(document).on("click", ".dashboardfilters",function(e){                
                
	var uid     =  $('#sesval').data('uid');
	var apikey  =  $('#sesval').data('key');

	var gatewayId  =  $(this).data('gateway');
	$('.error-tab').html('');

	var sensors = [];
	var coins = [];
	var c = '';


        $.each($(".coin-list option:selected"), function(){            
                    c = $(this).val();  
		c = "'" + c + "'";        
		coins.push(c);
        });


        $.each($(".sensor-list option:selected"), function(){            
                    sensors.push($(this).val());
        });


	if(coins.length == 0 && sensors.length == 0){
		
			$('.error-tab').html('*Please select a coin or a sensor or both.');
			return;
				
	}


		
	if(coins.length == 0){
		coins.push(0);
	}

	if(sensors.length == 0){
		sensors.push(0);
	}

	localStorage.setItem("coins_filters", coins);
	localStorage.setItem("sensors_filters", sensors);

	getAccTypesDevices(gatewayId, coins, sensors);	
	
});


function getDashboardCoins(gatewayId) {
	var uid     =  $('#sesval').data('uid');
        var apikey  =  $('#sesval').data('key');


        if(uid!='' && apikey!=''){
		$.ajax({
                	url: basePathUser + apiUrlGetCoin + '/' + gatewayId,
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

				var gw_split = gatewayId.split(",");

				var coin_li_html = '';
                		var i = 0;

                		var y = gw_split.length;


                		for(i=0; i < y; i++) {
                			var j = data.records[gw_split[i]].length;

					
				    	
				     		if(data.records[gw_split[i]][0][0] == "no_coin"){
							coin_li_html = '    There are no coins under this Gateway.';

							$('.coins').html(coin_li_html);
							return; 

						}
						var coin_html='<div class="col-sm-3"><h6 class="labh6">Coins</h6><select multiple class="coin-list"></select></div><div class="col-sm-5"><h6 class="labh6">Sensors</h6><select multiple class="sensor-list"></select></div><br/><br/><button class="dashboardfilters" data-gateway="'+gatewayId+'" style="margin-left:93px">Apply Filter</button>';
						$('.coins').html(coin_html);
					


                			var q = 0;

                		     $.each(data.records[gw_split[i]], function (index, value) {
                    			var gd_id = data.records[gw_split[i]][q]['gd_id'];
                    			var gateway_id = data.records[gw_split[i]][q]['gateway_id'];
                    			var device_id = data.records[gw_split[i]][q]['device_id'];
                    			var nick_name = data.records[gw_split[i]][q]['nick_name'];					                    				

					coin_li_html += '<option value="'+device_id+'">'+nick_name+'</option>';

                    			
                    			q++;
                			});
				
                		}
				$('.coin-list').html(coin_li_html);
				
					var sensor_li_html = '<option value="17,18,19,29">Acceleration</option><option value="20,21,22,30">Velocity</option><option value="23,24,25,31">Displacement</option>';
				

				$('.sensor-list').html(sensor_li_html); 


				$('.coin-list').multiselect({
					maxHeight: 130, 
					includeSelectAllOption: true, 
					numberDisplayed: 2
			
				});

				$('.sensor-list').multiselect({
					maxHeight: 130, 
					includeSelectAllOption: true, 
					numberDisplayed: 2
			
				});
 
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


  
helpGetGateways();	
</script>