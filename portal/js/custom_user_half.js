
basePathUser      =   getBasePathUser();
 
basePathApp       =   getBasePathApp();
        

var apiUrlGateways            = "gateways";
var apiUrlDevices             = "devices";
var apiUrlSetThreshold        = "threshold";
var apiUrlGetCurrentValue     = "get-currentvalue";

var apiUrlGetGatewaySettings        = "gateway-settings";

var apiUrlUpdateSMSNotification     = 'sms-notification';
var apiUrlUpdateEmailNotification   = 'email-notification';

var apiUrlNotificationEmailIds      = 'notification-email-ids';

var apiUrlNotificationPhone         = 'notification-phone';

var apiUrlSetStream                 = 'rate_value';

var apiUrlAddLocation               = 'create-location';

var apiUrlDeleteUserLocation              = 'delete-user-location';

var apiUrlGetCoin                   = 'get-coin';

var apiUrlAddGetCoin                = 'add-get-coin';

var apiUrlGetLocation               = 'get-location';

var apiUrlGetGatewayLocation        = 'get-gateway-location';

var urlAddLocationCoin              = 'a.php';

var apiRenderAlert                  = 'render-alert';

var apiUrlAddCoin                   = 'add-coin';

var apiUrlGetCoin                   = 'get-coin';

var apiUrlEventAddLog               = 'event-add-log';
var apiUrlGetEventLogs = 'get-event-logs';

var apiUrlGetDeviceSettings =  "device-settings";

var subscribersArr  =   [];

var apiUpdateRequestAction = 'update-request-action';

var apiUrlAccStreamDevices = 'get-acc-stream-devices';

var apiUrlGetGeneralSettings = 'get-general-settings';
var apiUpdateGeneralSettings = 'update-general-settings';

var apiUrlSetDetection  = 'set-detection';

var autohandle1 = null;

function getBasePathUser(){
  
        var baseurl  = window.location.origin;//http://sensegiz.com
        
        var basePath = baseurl+"/sensegiz-dev/user/";//Staging or Dev

    return basePath;
}

function getBasePathApp(){
 
        var baseurl  = window.location.origin;//http://sensegiz.com
        
        var basePath = baseurl+"/app/";//Staging or Dev

        return basePath;    
     
}

	/*
	 * Function 			: getGateways()
	 * Brief 			: load the list of Gateways	 
	 * Input param 			: Nil
	 * Input/output param           : NA
	 * Return			: NA
	 */		
	function getGateways() {
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
				var status_html = '';
                                if(data.records.length > 0){
                                    records = data.records;
                                    $.each(records, function (index, value) {
                                        gateway_id  =  value.gateway_id;
					status = value.status;

                                        var date      =  '';
                                        if(value.updated_on!=''){
            //                               date      =  value.added_on +'Z'; 
                                           date         = value.updated_on;
                                           var stillUtc = moment.utc(date).toDate();
                                           date         = moment(stillUtc).local().format(date_format);
                                        }

					if(status == 'Online'){
						status_html = '&nbsp&nbsp&nbsp<img src="../img/online.png"/>';
					}else{
						status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';

					}
                                        sc_html  =  '<p>Click on any Gateway to view dashboard</p>';
					

                                        gw_li_html += '<li><a href="javascript:;" class="eachGateway" data-gateway="'+gateway_id+'">'+gateway_id+''+status_html+'</a>  <span class="date-new">Last updated:</span><span class="date-new" data-localtime3434-format="d-M-yyyy HH:mm:ss">'+date+'</span> </li>';    
                                    });                        
                                }
                                else{
                                        sc_html  =  '<tr><td width="300">No Gateways Found</td></tr>';
                                }

				if(data.records.length == 1){
					sc_html = '';

				}

                                $('.showclicktext').html(sc_html); 

                                $('.gateway-list-lfnav').html(gw_li_html); 
                    
                    
				if(data.records.length == 1){
					$('.eachGateway').click();

				}

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
	 * Function 			: getDevices(gatewayId)
	 * Brief 			: load the list of Devices for a gateway	 
	 * Input param 			: gatewayId
	 * Input/output param           : NA
	 * Return			: NA
	 */		
	function getDevices(gatewayId, coins, sensors) {
             $('#loader').show();
             //Remove sticky notfn box
             
             
        //new
            var uid     =  $('#sesval').data('uid');
            var apikey  =  $('#sesval').data('key');
            if(uid!='' && apikey!=''){
                            $.ajax({
                                    url: basePathUser + apiUrlDevices +'/' + gatewayId + '/' + coins + '/' + sensors,
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
                                            var gyro_html   =  '';                                        
                                            var temp_html   =  '';                                        
                                            var hum_html    =  '';
					    var stream_html =  '';                                        
                                            var other_html  =  '';
						var acc_stream_html = '';
                                                            
                                            records = data.records;
                                            var accelerometer   = records.accelerometer;
                                            var gyrosensor      = records.gyrosensor;
                                            var temperature     = records.temperature;
                                            var humidity        = records.humidity;
					    var stream          = records.stream;
                                            var other           = records.other;
					    var accStream       = records.accStream;

					$.ajax({
                                    		url: basePathUser + apiUrlGetDeviceSettings + '/' + gatewayId,
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
							records = data.records;
								var acc_settings = [];
								var gyr_settings = [];
								var temp_settings = [];
								var humid_settings = [];
								var stream_settings = [];
								var generalsettings = '';

								var acc = '';
								var gyro = ''; var temp = ''; var humid = ''; var strm = '';

							$.each(records, function (index, value) {
								var device_id  =  value.device_id;
                                        			var settings    =  value.settings;
								generalsettings    =  value.generalsettings;
								
							
								if(settings.length>0){
									$.each(settings, function (indexSett, valueSett) {
										
										var sensor_type = valueSett.device_sensor;
										var sensor_active = valueSett.sensor_active;
										

										if(sensor_type == "Accelerometer"){
											acc_settings.push(valueSett);
											
										}
										if(sensor_type == "Gyroscope"){
											gyr_settings.push(valueSett);
											
										}
										if(sensor_type == "Temperature"){
											temp_settings.push(valueSett);
											
										}
										if(sensor_type == "Humidity"){		
											humid_settings.push(valueSett);
											
										}
										if(sensor_type == "Temperature Stream"){		
											stream_settings.push(valueSett);
											
										}
										if(sensor_type == "Humidity Stream"){		
											stream_settings.push(valueSett);
											
										}

									
									});
								}
							});

							if(generalsettings.length>0){
									$.each(generalsettings, function (indexSett, valueSett) {
										
										acc = valueSett.accelerometer;
										gyro = valueSett.gyroscope;
										temp = valueSett.temperature;
										humid = valueSett.humidity;
										strm = valueSett.stream;
										accstream = valueSett.accelerometerstream;

									});
							}

							if(accelerometer.length>0  && acc == 'Y'){
                                                		acc_html =  getFormattedDevicesData(accelerometer,'Accelerometer', acc_settings, 0);                                                
                                            		}
							if(gyrosensor.length>0 && gyro == 'Y'){
                                                		gyro_html =  getFormattedDevicesData(gyrosensor,'Gyroscope', gyr_settings, 0);                                                
                                            		}                                            
                                            	
                                            		if(temperature.length>0 && temp == 'Y'){
                                                		temp_html =  getFormattedDevicesData(temperature,'Temperature', temp_settings, 0);                                                
                                            		}      
                                            
                                            		if(humidity.length>0 && humid == 'Y'){
                                                		hum_html =  getFormattedDevicesData(humidity,'Humidity', humid_settings, 0);                                                
                                            		}  

							if(stream.length>0 && strm == 'Y'){
					    			stream_html = getFormattedDevicesData(stream, 'Stream', stream_settings , 1);
					   		 }

							 if(accStream.length>0){
					    	acc_stream_html = getFormattedDevicesData(accStream, 'Accelerometer Stream', 0, 2);
					    }
                                            		if(accelerometer.length==0 && gyrosensor.length==0 && temperature.length==0 && humidity.length==0 && stream.length==0 && other.length!=0 && accStream.length!=0){
                                                		sc_html =  '<h3>No data found</h3>';
                                            		}
                                         
							info_html = '<p style="color:green;text-align: right;"> <i> For details on how to set the thresholds, please refer the Guide section. </i> </p>';

                                            		sc_html += ''+acc_html+''+gyro_html+''+temp_html+''+hum_html+''+stream_html; 
							if(sc_html != ''){
                    						//sc_html += '<p style="color:red;"> <i> *Accelerometer Stream is a customized feature and might not be available for your device. Please contact SenseGiz representative for more details (support@sensegiz.com). </i> </p>';
								sc_html = ''+info_html+''+sc_html;

							}
                                            		$('.devicesTables').html(sc_html);  
                    
                                            		loadSubscriber(gatewayId);                   
					    
                        				moment().format();

										
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



//format and return data
function getFormattedDevicesData(dataArr, tableName, dev_settings, diff){
            
	var deviceIds = [];
	var senDeviceIds = [];
	var date_format  =  $('#sesval').data('date_format');
	var temp_unit  =  $('#sesval').data('temp_unit');

	if(tableName == 'Accelerometer'){ var cbutton = 'p1'; var unit = 'g'; }
	if(tableName == 'Gyroscope'){ var cbutton = 'p3'; var unit = 'DPS'; }
	if(tableName == 'Temperature'){ var cbutton = 'p5'; var unit = temp_unit; }
	if(tableName == 'Humidity'){ var cbutton = 'p7'; var unit = '%RH'; }



	if(dev_settings != 0){
		$.each(dev_settings, function (sindex, svalue) {
			senDeviceIds.push(svalue.device_id);

		});
	}

	

	if(diff ==0 ){
	
                var data_html  =   '<button class="accordion '+cbutton+'">'+tableName+' ('+unit+')</button><div class="panel d'+cbutton+'"><div class="table-responsive"><table class="table table-hover table-bordered table-lp">'
                                +'    <tr class="active tb-hd">'                                                          
                                +'        <th>Sl. No</th>'
                                +'        <th>Nick Name</th>'
                                +'        <th>Threshold</th>'
                                +'        <th>Value</th>'
                                +'        <th>Get Current Value</th>'
                                +'        <th>Last Updated</th>'
                                +'        <th>Set Threshold</th>'
                                +'    </tr>'                                                         
                                +'    <tbody class="users gateways" style="text-align:center;">';                                                
                                                
		$.each(dataArr, function (index, value) {
                                                        
			var device_id     =  value.device_id;

			
                        var gateway_id    =  value.gateway_id;
			var active = value.active;
			
			if(active == 'Y'){
				coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/online.png"/>';
			}else{
				coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';

			}

                                                        var device_type   =  value.device_type;
                                                        var device_value  =  value.device_value;
                                                        
                                                        var nick_name     =  value.nick_name;

                                                        var current_value       =  value.get_current_value;
                                                        var d_id  =  value.d_id;
                                                        var dis_dev_type = '';
							var threshold_value    =  value.threshold_value;

                                                        if((device_type=='05' || device_type=='06') && temp_unit == 'Fahrenheit'){                                                            
								if(device_value!=null && device_value!='null' && device_value!=''){ 
									device_value = (device_value * 1.8) + 32;
									device_value = parseFloat(device_value).toFixed(3);	
								}
								
								
                                                        }

                                                        //convert hex val to dec
                                                        if(device_type==null || device_type=='null' || device_type==''){
                                                            dis_dev_type   = 'No data';
                                                        }
                                                        
							if(device_type=='01' || device_type=='03' || device_type=='05' || device_type=='07'){
                                                            dis_dev_type = 'low';
                                                        }else
							if(device_type=='02' || device_type=='04' || device_type=='06' || device_type=='08'){
                                                            dis_dev_type = 'high';
                                                        }else
							if(device_type=='09'){
                                                            dis_dev_type = 'temperature';
                                                        }else
							if(device_type=='10'){
                                                            dis_dev_type = 'humidity';
                                                        }else
							if(device_type=='12'){
                                                            dis_dev_type = 'accelerometer';
                                                        }
							else{
                                                            dis_dev_type = device_type;
                                                        }

                                                           
                                                         //convert hex val to dec
                                                         if(threshold_value!=null && threshold_value!=''){
                                                             threshold_value  =   hexToDec(threshold_value); 
								if(device_type=='01' || device_type=='02'){
									if(threshold_value == 1)
										threshold_value = 0.001;
									else if(threshold_value == 2)
										threshold_value = 0.1;
									else
										threshold_value = threshold_value/8;
								}
								if(device_type=='03' || device_type=='04'){
									threshold_value = threshold_value * 10;
								}
								if(device_type=='05' || device_type=='06'){
									if(threshold_value > 126){
										threshold_value = threshold_value - 126;
										threshold_value = -threshold_value;
									}
									if(threshold_value == 126){
										threshold_value = 0;
									}
									
									if(temp_unit == 'Fahrenheit'){
										threshold_value = (threshold_value * 1.8) + 32;
									}
								}

								
                                                         }
                                                         else{
                                                             threshold_value  =  '';
                                                         }

							var rate_value         =  value.rate_value;                                       
                                       			var format         =  value.format; 
                                    
		                                        //convert hex val to dec
                                    
                                                                            
		                                        if(rate_value!=null && rate_value!=''){
                    		    	                    rate_value  =   rate_value;  
		                                        } else{
			                                        rate_value  =  '';
		                                        }

                                                        var date      =  '';
                                                        if(value.added_on!='' && value.added_on!=null){
                                                            date         = value.added_on;
                                                            var stillUtc = moment.utc(date).toDate();
                                                            date         =  moment(stillUtc).local().format(date_format);
                                                        }   

                                                        var last_updated_on      =  '';
                                                        if(value.updated_on !='' && value.added_on!=null){
                                                           last_updated_on         = value.updated_on;
                                                           var stillUtc = moment.utc(last_updated_on).toDate();
                                                           last_updated_on         =  moment(stillUtc).local().format(date_format);
							var now = moment().format(date_format);
							//var duration  = moment.duration(now.diff(last_updated_on));
							//var hours = duration.asHours();

							//var diff = now.diff(now, last_updated_on);

							var ms = moment(now,"DD-MM-YYYY HH:mm:ss").diff(moment(last_updated_on,"DD-MM-YYYY HH:mm:ss"));
							var d = moment.duration(ms);
							var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");

                                                        }
							var a =  s.split(':');
							var hour   = a[0];
							var minute = a[1];
							var second = a[2]; 
							data_html += '<td>'+(index+1)+'</td>'
								+'<td>'+nick_name+''+coin_status_html+'</a></td>'
                                                                +'<td>'+dis_dev_type+'</td>'
                                                                +'<td>'+device_value+'</td>';
																

					if(d_id!=null && d_id!=''){
						if(jQuery.inArray(device_id , senDeviceIds) != '-1'){
							$.each(dev_settings, function (sindex, svalue) {
								var sen_dev_id = svalue.device_id;
								var sen_active = svalue.sensor_active;

								if(device_id == sen_dev_id && sen_active == 'Y'){
									if(device_type=='01' || device_type=='02')							
									{		
										data_html  +=    '<td><button class="getCurrentValue" type="button" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">GET</button><span class="done grn"></span></td></td>';
										data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>'; 
										accVal = getAccelerometerValues(threshold_value);	
										data_html  +=  '<td><select class="accVal' + device_id + device_type + ' ">';
										data_html += accVal;
										data_html += '</select><button class="setTh" type="button"  data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';
                                                        
									}
									else if(device_type=='03' || device_type=='04')
									{
										data_html  +=    '<td><button class="getCurrentValue" type="button" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">GET</button><span class="done grn"></span></td></td>';
										data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>'; 
										data_html  +=  '<td><input type="text" onkeypress="return validateNumber(event);" value="'+threshold_value+'" class="thinpt" maxlength="4"/><button class="setTh" type="button" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';

									}
									else if(device_type=='05' || device_type=='06')
									{
										data_html  +=    '<td><button class="getCurrentValue" type="button" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">GET</button><span class="done grn"></span></td></td>';
										data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>'; 
										data_html  +=  '<td><input type="text" onkeypress="return validateNegativeNumber(this, event);" value="'+threshold_value+'" class="thinpt" maxlength="5"/><button class="setTh" type="button" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';
									}
									else if(device_type=='07' || device_type=='08')
									{
										data_html  +=    '<td><button class="getCurrentValue" type="button" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">GET</button><span class="done grn"></span></td></td>';
										data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>'; 
										data_html  +=  '<td><input type="text" onkeypress="return validateNumber(event);" value="'+threshold_value+'" class="thinpt" maxlength="3"/><button class="setTh" type="button" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';
									}

								}
								if(device_id == sen_dev_id && sen_active == 'N'){
									if(device_type=='01' || device_type=='02')							
									{		
										data_html  +=    '<td><button class="getCurrentValue" type="button" disabled style="background-color: #757E79;" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">GET</button><span class="done grn"></span></td></td>';
										data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>'; 
										accVal = getAccelerometerValues(threshold_value);	
										data_html  +=  '<td><select class="accVal' + device_id + device_type + ' ">';
										data_html += accVal;
										data_html += '</select><button class="setTh" type="button" disabled style="background-color: #757E79;"  data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';
                                                        
									}
									else if(device_type=='03' || device_type=='04')
									{
										data_html  +=    '<td><button class="getCurrentValue" type="button" disabled style="background-color: #757E79;" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">GET</button><span class="done grn"></span></td></td>';
										data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>'; 
										data_html  +=  '<td><input type="text" onkeypress="return validateNumber(event);" value="'+threshold_value+'" class="thinpt" maxlength="4"/><button class="setTh" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';

									}
									else if(device_type=='05' || device_type=='06')
									{
										data_html  +=    '<td><button class="getCurrentValue" type="button" disabled style="background-color: #757E79;" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">GET</button><span class="done grn"></span></td></td>';
										data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>'; 
										data_html  +=  '<td><input type="text" onkeypress="return validateNegativeNumber(this, event);" value="'+threshold_value+'" class="thinpt" maxlength="5"/><button class="setTh" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';
									}
									else if(device_type=='07' || device_type=='08')
									{
										data_html  +=    '<td><button class="getCurrentValue" type="button" disabled style="background-color: #757E79;" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">GET</button><span class="done grn"></span></td></td>';
										data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>'; 
										data_html  +=  '<td><input type="text" onkeypress="return validateNumber(event);" value="'+threshold_value+'" class="thinpt" maxlength="3"/><button class="setTh" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';
									}

								}
							});


						}else{
							if(device_type=='01' || device_type=='02')							
									{		
										data_html  +=    '<td><button class="getCurrentValue" type="button" disabled style="background-color: #757E79;" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">GET</button><span class="done grn"></span></td></td>';
										data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>'; 
										accVal = getAccelerometerValues(threshold_value);	
										data_html  +=  '<td><select class="accVal' + device_id + device_type + ' ">';
										data_html += accVal;
										data_html += '</select><button class="setTh" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';
                                                        
                							}
							if(device_type=='03' || device_type=='04')
									{
										data_html  +=    '<td><button class="getCurrentValue" type="button" disabled style="background-color: #757E79;" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">GET</button><span class="done grn"></span></td></td>';
										data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>'; 
										data_html  +=  '<td><input type="text" onkeypress="return validateNumber(event);" value="'+threshold_value+'" class="thinpt" maxlength="4"/><button class="setTh" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';

									}
							if(device_type=='05' || device_type=='06' )
									{
										data_html  +=    '<td><button class="getCurrentValue" type="button" disabled style="background-color: #757E79;" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">GET</button><span class="done grn"></span></td></td>';
										data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>'; 
										data_html  +=  '<td><input type="text" onkeypress="return validateNegativeNumber(this, event);" value="'+threshold_value+'" class="thinpt" maxlength="5"/><button class="setTh" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';
									}
							if(device_type=='07' || device_type=='08')
									{
										data_html  +=    '<td><button class="getCurrentValue" type="button" disabled style="background-color: #757E79;" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">GET</button><span class="done grn"></span></td></td>';
										data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>'; 
										data_html  +=  '<td><input type="text" onkeypress="return validateNumber(event);" value="'+threshold_value+'" class="thinpt" maxlength="3"/><button class="setTh" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';
									}

						}

					}
					else
					{
								data_html  += '<td></td>';
								data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>'; 
								data_html  += '<td></td>';
					}

                                                    data_html  +=  '</tr>';  

					
                                                                                                      
                                            });
                                            
						
                                                    data_html  +=     '</tbody>'
                                                +'</table></div></div>';
                                        
                                        return data_html;


	} else if(diff == 1){


                var data_html  =   '<button class="accordion p9">'+tableName+' ('+temp_unit+' / %RH)</button><div class="panel dp9"><div class="table-responsive"><table class="table table-hover table-bordered table-lp">'
                                +'    <tr class="active tb-hd">'                                                          
                                +'        <th>Sl. No</th>'
                                +'        <th>Nick Name</th>'
                                +'        <th>Sensor Name</th>'
                                +'        <th>Value</th>'
                                +'        <th>Last Updated</th>'
				+'	  <th>Set Stream</th>'                              
                                +'    </tr>'                                                         
                                +'    <tbody class="users gateways" style="text-align:center;">';                                                
                                                $.each(dataArr, function (index, value) {
                                                        var device_id     =  value.device_id;
                                                        var gateway_id    =  value.gateway_id;
                                                        var device_type   =  value.device_type;
                                                        var device_value  =  value.device_value;
                                                        
                                                        var nick_name     =  value.nick_name;

                                                        var current_value       =  value.get_current_value;

							var coin_status_html = '';
							var active = value.active;
			
							if(active == 'Y'){
								coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/online.png"/>';
							}else{
								coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';

							}

                                                        var d_id  =  value.d_id;
                                                        var dis_dev_type = '';

                                                        if(device_type=='09' && temp_unit == 'Fahrenheit'){                                                            
								if(device_value!=null && device_value!='null' && device_value!=''){ 
									device_value = (device_value * 1.8) + 32;
									device_value = parseFloat(device_value).toFixed(3);	
								}
								
                                                        }

                                                        //convert hex val to dec
                                                        if(device_type==null || device_type=='null' || device_type==''){
                                                            dis_dev_type   = 'No data';
                                                        }
                                                        
							if(device_type=='01' || device_type=='03' || device_type=='05' || device_type=='07'){
                                                            dis_dev_type = 'low';
                                                        }else
							if(device_type=='02' || device_type=='04' || device_type=='06' || device_type=='08'){
                                                            dis_dev_type = 'high';
                                                        }else
							if(device_type=='09'){
                                                            dis_dev_type = 'temperature';
                                                        }else
							if(device_type=='10'){
                                                            dis_dev_type = 'humidity';
                                                        }else
							if(device_type=='12'){
                                                            dis_dev_type = 'accelerometer';
                                                        }
							else{
                                                            dis_dev_type = device_type;
                                                        }

                                                            var threshold_value    =  value.threshold_value;
                                                         //convert hex val to dec
                                                         if(threshold_value!=null && threshold_value!=''){
                                                             threshold_value  =   hexToDec(threshold_value);  
                                                         }
                                                         else{
                                                             threshold_value  =  '';
                                                         }

							var rate_value         =  value.rate_value;                                       
                                       			var format         =  value.format; 
							format = hexToDec(format);
							         
							if(format == 21){
								var dropdown_html = '<select class="thinpt1" value"'+format+'" ><option value="21" selected>Seconds</option><option value="22">Minutes</option><option value="33">Hours</option></select>';
							}
							else if(format == 22){
								var dropdown_html = '<select class="thinpt1" value"'+format+'" ><option value="21">Seconds</option><option value="22" selected>Minutes</option><option value="33">Hours</option></select>';
							}
							else if(format == 33){
								var dropdown_html = '<select class="thinpt1" value"'+format+'" ><option value="21">Seconds</option><option value="22">Minutes</option><option value="33" selected>Hours</option></select>';
							} 
							else{
								var dropdown_html = '<select class="thinpt1" value"'+format+'" ><option value="21" selected>Seconds</option><option value="22">Minutes</option><option value="33">Hours</option></select>';
							}                          

		                                        //convert hex val to dec
                                    			                    
		                                        if(rate_value!=null && rate_value!=''){
                    		    	                    rate_value  =   hexToDec(rate_value);  
		                                        } else{
			                                        rate_value  =  '';
		                                        }

                                                        var date      =  '';
                                                        if(value.added_on!='' && value.added_on!=null){
                                                            date         = value.added_on;
                                                            var stillUtc = moment.utc(date).toDate();
                                                            date         =  moment(stillUtc).local().format(date_format);
                                                        }   

                                                        var last_updated_on      =  '';
                                                        if(value.updated_on !='' && value.added_on!=null){
                                                           last_updated_on         = value.updated_on;
                                                           var stillUtc = moment.utc(last_updated_on).toDate();
                                                           last_updated_on         =  moment(stillUtc).local().format(date_format);
							var now = moment().format(date_format);
							//var duration  = moment.duration(now.diff(last_updated_on));
							//var hours = duration.asHours();

							//var diff = now.diff(now, last_updated_on);

							var ms = moment(now,"DD-MM-YYYY HH:mm:ss").diff(moment(last_updated_on,"DD-MM-YYYY HH:mm:ss"));
							var d = moment.duration(ms);
							var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");

                                                        }
							var a =  s.split(':');
							var hour   = a[0];
							var minute = a[1];
							var second = a[2]; 
							data_html += '<td>'+(index+1)+'</td>'
								+'<td>'+nick_name+' '+coin_status_html+'</a></td>'
                                                                +'<td>'+dis_dev_type+'</td>'
                                                                +'<td>'+device_value+'</td>';

                                                         data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>';   

							if(d_id!=null && d_id!='' && dis_dev_type == 'temperature' || dis_dev_type == 'humidity') {
								
								if(jQuery.inArray(device_id , senDeviceIds) != '-1'){
									
									
									

									$.each(dev_settings, function (sindex, svalue) {
										var sen_dev_id = svalue.device_id;
										var sen_active = svalue.sensor_active;
										var sensor_type = svalue.device_sensor;

										var occur = $.grep(senDeviceIds, function(elem) {
  												return elem == sen_dev_id;
											}).length;
									

										

										if(occur > 1) {
										if(sensor_type == 'Temperature Stream' && dis_dev_type == 'temperature'){
											if(device_id == sen_dev_id && sen_active == 'Y'){
											
											data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="'+rate_value+'" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';

											}
											else if(device_id == sen_dev_id && sen_active == 'N'){
											
											data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="'+rate_value+'" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';	
											}
												
										

										}
										else if(sensor_type == 'Humidity Stream' && dis_dev_type == 'humidity'){
											if(device_id == sen_dev_id && sen_active == 'Y'){
											
											data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="'+rate_value+'" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';


											}
											else if(device_id == sen_dev_id && sen_active == 'N'){
											
											data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="'+rate_value+'" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';	
											}
											
										}
										
										}else{
											if(device_id == sen_dev_id){
												if(sensor_type == 'Temperature Stream' && dis_dev_type == 'temperature'){
													if(sen_active == 'Y'){
														data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="'+rate_value+'" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';

													}
													else{
														data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="'+rate_value+'" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';	
	
													}
												}
												else if(sensor_type == 'Humidity Stream' && dis_dev_type == 'humidity')
												{
													if(sen_active == 'Y'){
														data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="'+rate_value+'" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';

													}
													else{
														data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="'+rate_value+'" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';	
	
													}

												}
												else{
														data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="'+rate_value+'" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';	
	
													}

											}

										}
									});
									
									
								}
								else{
									
									data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="'+rate_value+'" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';
								}
								
								
	
							}else{
	    							data_html += '<td></td>';
							}
                                                    data_html  +=  '</tr>';                                                                                                        
                                            });
                                            
                                                    data_html  +=     '</tbody>'
                                                +'</table></div></div>';
                                        
                                        return data_html;


	}
	else{
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
                                                        var nick_name     =  value.nick_name;                                                       
                                                        var d_id  =  value.d_id;
                                                        var dis_dev_type = '';

							                                                                                                                                                                       								
                                                        //$dval = hexToDec(device_value) - 65536;
							
							//var finalvalue = dataconvert($dval);
							//finalvalue = finalvalue * 0.000244;

                                                        if(device_type==null || device_type==''){
                                                            dis_dev_type   = 'No data';
                                                        }
                                                        
							if(device_type=='12'){
                                                            dis_dev_type = 'Accelerometer X-axis';
                                                        }else
							if(device_type=='14'){
                                                            dis_dev_type = 'Accelerometer Y-axis';
                                                        }else
							if(device_type=='15'){
                                                            dis_dev_type = 'Accelerometer Z-axis';
                                                        }							
                                                                                                                                                                  		                                                
                                                        var last_updated_on      =  '';
                                                        if(value.updated_on !='' && value.added_on!=null){
                                                           last_updated_on         = value.updated_on;
                                                           var stillUtc = moment.utc(last_updated_on).toDate();
                                                           last_updated_on         =  moment(stillUtc).local().format(date_format);
							var now = moment().format(date_format);
							
							var ms = moment(now,"DD-MM-YYYY HH:mm:ss").diff(moment(last_updated_on,"DD-MM-YYYY HH:mm:ss"));
							var d = moment.duration(ms);
							var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");

                                                        }
							var a =  s.split(':');
							var hour   = a[0];
							var minute = a[1];
							var second = a[2]; 
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


        }


/*
	 * Function 			: getAccStreamDevices(gatewayId)
	 * Brief 			: load the list of Devices for a gateway	 
	 * Input param 			: gatewayId
	 * Input/output param           : NA
	 * Return			: NA
	 */		
	function getAccStreamDevices(gatewayId, coins) {
             $('#loader').show();
             //Remove sticky notfn box
             
             
        //new
            var uid     =  $('#sesval').data('uid');
            var apikey  =  $('#sesval').data('key');
            if(uid!='' && apikey!=''){
                            $.ajax({
                                    url: basePathUser + apiUrlAccStreamDevices +'/' + gatewayId +'/' + coins,
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
                                                               
                                        var other_html  =  '';
					var acc_stream_html = '';
                                                            
                                        records = data.records;
                                           
                                        var other           = records.other;
					var accStream       = records.accStream;

					$.ajax({
                                    		url: basePathUser + apiUrlGetDeviceSettings + '/' + gatewayId,
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
							records = data.records;
								
							var stream_settings = [];
							var generalsettings = '';
							var accstrm = '';

							$.each(records, function (index, value) {
								var device_id  =  value.device_id;
                                        			var settings    =  value.settings;
								generalsettings = value.generalsettings;
															
								if(settings.length>0){
									$.each(settings, function (indexSett, valueSett) {
										
										var sensor_type = valueSett.device_sensor;
										var sensor_active = valueSett.sensor_active;
																				
										if(sensor_type == "Accelerometer Stream"){		
											stream_settings.push(valueSett);
											
										}
									
									});
								}
							});
							

							if(generalsettings.length>0){
									$.each(generalsettings, function (indexSett, valueSett) {
										
										accstrm = valueSett.accelerometerstream;
										
									});
							}

							console.log("Acc STream");
							console.log(accstrm);

							if(accStream.length>0 && accstrm == 'Y'){
								acc_stream_html = getFormattedAccStreamData(accStream, 'Accelerometer Stream', stream_settings);
							}
                                           		                                                                                     		
							if(acc_stream_html != ''){
                    						acc_stream_html += '<p style="color:red;"> <i> *Accelerometer Stream is a customized feature and might not be available for your device. Please contact SenseGiz representative for more details (support@sensegiz.com). </i> </p>';
							}
                                         		$('.accStreamTable').html(acc_stream_html);  

                                         		loadSubscriber(gatewayId);                   
					    
                        				moment().format();

										
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



//format and return data
function getFormattedAccStreamData(dataArr, tableName, dev_settings)
{            
	var date_format  =  $('#sesval').data('date_format');

	var deviceIds = [];
	var senDeviceIds = [];

	if(dev_settings != 0){
		$.each(dev_settings, function (sindex, svalue) {
			senDeviceIds.push(svalue.device_id);

		});
	}



			var data_html  =   '<button class="accordion p12">'+tableName+' (g)</button><div class="panel dp12"><div class="table-responsive"><table class="table table-hover table-bordered table-lp">'
                                +'    <tr class="active tb-hd">'                                                          
                                +'        <th>Sl. No</th>'
                                +'        <th>Nick Name</th>'
                                +'        <th>Sensor Name</th>'
                                +'        <th>Value</th>'
                                +'        <th>Last Updated</th>'
				+'        <th>Set Detection Period</th>'
				+'        <th>Set Stream</th>'		                            
                                +'    </tr>'                                                         
                                +'    <tbody class="users gateways" style="text-align:center;">';                                                
                                                $.each(dataArr, function (index, value) {
                                                        var device_id     =  value.device_id;
                                                        var gateway_id    =  value.gateway_id;
                                                        var device_type   =  value.device_type;
                                                        var device_value  =  value.device_value;                                                       
                                                        var nick_name     =  value.nick_name;                                                       
                                                        var d_id  =  value.d_id;
                                                        var dis_dev_type = '';
							
							var coin_status_html = '';
							var active = value.active;
			
							if(active == 'Y'){
								coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/online.png"/>';
							}else{
								coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';

							}

							var rate_value         =  value.rate_value;                                       
                                       			var format         =  value.format; 
							format = hexToDec(format);


							if(rate_value != null){
								rate_value  =   hexToDec(rate_value); 
							}else{
								rate_value  =  '';
							}


							if(format == '21'){
								var dropdown_html = '<select class="thinpt1" value"'+format+'" ><option value="22">Minutes</option><option value="33">Hours</option></select>';
								var det_dropdown_html = '<select class="thinpt1" value"'+format+'" ><option value="21" selected>Seconds</option><option value="22">Minutes</option></select>';

							}
							else if(format == '22'){
								var dropdown_html = '<select class="thinpt1" value"'+format+'" ><option value="22" selected>Minutes</option><option value="33">Hours</option></select>';
								var det_dropdown_html = '<select class="thinpt1" value"'+format+'" ><option value="21" >Seconds</option><option value="22" selected>Minutes</option></select>';

							}
							else if(format == '33'){
								var dropdown_html = '<select class="thinpt1" value"'+format+'" ><option value="22">Minutes</option><option value="33" selected>Hours</option></select>';
							} 
							else{
								var dropdown_html = '<select class="thinpt1" value"'+format+'" ><option value="22">Minutes</option><option value="33">Hours</option></select>';
								var det_dropdown_html = '<select class="thinpt1" value"'+format+'" ><option value="21" selected>Seconds</option><option value="22">Minutes</option></select>';

							}


							                                                                                                                                                                       								
                                                        
                                                        if(device_type==null || device_type==''){
                                                            dis_dev_type   = 'No data';
                                                        }
                                                        
							if(device_type=='12'){
                                                            dis_dev_type = 'Accelerometer X-axis';
                                                        }else
							if(device_type=='14'){
                                                            dis_dev_type = 'Accelerometer Y-axis';
                                                        }else
							if(device_type=='15'){
                                                            dis_dev_type = 'Accelerometer Z-axis';
                                                        }else
							if(device_type=='28'){
                                                            dis_dev_type = 'Aggregate';
                                                        }							
                                                                                                                                                                  		                                                
                                                        var last_updated_on      =  '';
                                                        if(value.updated_on !='' && value.added_on!=null){
                                                           last_updated_on         = value.updated_on;
                                                           var stillUtc = moment.utc(last_updated_on).toDate();
                                                           last_updated_on         =  moment(stillUtc).local().format(date_format);
							var now = moment().format(date_format);
							
							var ms = moment(now,"DD-MM-YYYY HH:mm:ss").diff(moment(last_updated_on,"DD-MM-YYYY HH:mm:ss"));
							var d = moment.duration(ms);
							var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");

                                                        }
							var a =  s.split(':');
							var hour   = a[0];
							var minute = a[1];
							var second = a[2]; 
							data_html += '<td>'+(index+1)+'</td>'
								+'<td>'+nick_name+' '+coin_status_html+'</a></td>'
                                                                +'<td>'+dis_dev_type+'</td>'
                                                                +'<td>'+device_value+'</td>';

                                                         data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>';   

							if(jQuery.inArray(device_id , senDeviceIds) != '-1'){
								$.each(dev_settings, function (sindex, svalue) {
									console.log("In if");
									var sen_dev_id = svalue.device_id;
									var sen_active = svalue.sensor_active;
									
									if(device_id == sen_dev_id && sen_active == 'Y'){
										if(device_type=='12'){
                                                          				data_html  += '<td style="border-right:0;border-bottom:0"></td><td rowspan="4"><input type="text" onkeypress="return validateNumber(event);" placeholder="'+rate_value+'" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';
                                                        			}
										if(device_type=='14'){
                                                          				
											data_html  += '<td rowspan="3"style="border-top:0;border-right:1px solid #ddd;"><div style="margin-top:-31px"><input type="text" onkeypress="return validateNumber(event);" placeholder="'+rate_value+'" class="thinpt" maxlength="3"  />' + det_dropdown_html + '<button class="setdetection" type="button" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></div></td>';

                                                        			}
									}
									if(device_id == sen_dev_id && sen_active == 'N'){
										if(device_type=='12'){
                                                          				data_html  += '<td style="border-right:0;border-bottom:0"></td><td rowspan="4"><input type="text" onkeypress="return validateNumber(event);" placeholder="'+rate_value+'" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';
                                                        			}
										if(device_type=='14'){
                                                          				
											data_html  += '<td rowspan="3" style="border-top:0;border-right:1px solid #ddd;><input type="text" onkeypress="return validateNumber(event);" placeholder="'+rate_value+'" class="thinpt" maxlength="3"  />' + det_dropdown_html + '<button class="setdetection" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';

                                                        			}
									}

								});
										

							}else{
								if(device_type=='12'){
                                                          		data_html  += '<td style="border-right:0;border-bottom:0"></td><td rowspan="4"><input type="text" onkeypress="return validateNumber(event);" placeholder="'+rate_value+'" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';
                                                        	}
								if(device_type=='14'){
                                                          		
									data_html  += '<td rowspan="3" style="border-top:0;border-right:1px solid #ddd;><input type="text" onkeypress="return validateNumber(event);" placeholder="'+rate_value+'" class="thinpt" maxlength="3"  />' + det_dropdown_html + '<button class="setdetection" type="button" disabled style="background-color: #757E79;" data-devicevalue="'+device_value+'" data-device="'+device_id+'" data-devicetype="'+device_type+'" data-gateway="'+gateway_id+'">SET</button><span class="done grn"></span></td>';
                                                        	}
							}
							

							                   data_html  +=  '</tr>';                                                                                                        
                                            });
                                            
                                                    data_html  +=     '</tbody>'
                                                +'</table></div></div>';
                                        
                                        return data_html;
	
	


}
		
	
function dataconvert(dval)
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
	
		
        /*
	 * Function 			: Click event on SET THRESHOLD
	 * Brief 			: events which should happen while clicking on SET button in Devices page
	 * Detail 			: events which should happen while clicking on SET button in Devices page
	 * Input param 			: event
	 * Input/output param   	: NA
	 * Return			: NA
	 */	
	$(document).on( "click", ".setTh",function(e){
                    e.preventDefault();
                    $('.success').html('');
                    $('.error,.error-tab').html('');
                    
                    
                    
                    var device         = $(this).data('device');
                    var devicetype     = $(this).data('devicetype');
                    var devicevalue    = $(this).data('devicevalue');
	var temp_unit  =  $('#sesval').data('temp_unit');
                                        
                    var gateway    = $(this).data('gateway');
		if(devicetype == '01' || devicetype == '02'){
			var threshold = $('.accVal'+device+devicetype+' :selected').val();
				
		}else{
                    var threshold  = $(this).parent().find('input').val().trim();
		}                   
                    
                    if(threshold==''){
                        $(this).parent().find('input').focus();
                        return false;
                    }
			else{
				if(devicetype == '01' || devicetype == '02'){
							if(threshold < 0.001 || threshold > 15.875){
								return alert('The range for Accelerometer threshold value is 0.001g to 15.875g');
					}
				}
				if(devicetype == '03' || devicetype == '04'){
							if(threshold < 10 || threshold > 1990){
								return alert('The range for Gyroscope threshold value is 10DPS to 1990DPS');
							}
				}
				if(devicetype == '05' || devicetype == '06'){
					if(temp_unit == 'Fahrenheit'){
						if(threshold < -102.2 || threshold > 255.2){
							return alert('The range for Temperature threshold value is -102.2 to 255.2 degree Fahrenheit');
						}
						threshold = Math.round((threshold - 32) / 1.8);	
					}
					else{
						if(threshold < -39 || threshold > 124){
							return alert('The range for Temperature threshold value is -39 to 124 degree Celcius');
						}
					}
				}
				if(devicetype == '07' || devicetype == '08'){
							if(threshold < 2 || threshold > 99){
								return alert('The range for Humidity threshold value is 2% RH to 99% RH');
							}
				}
			}
                    if(devicetype == '03' || devicetype == '04'){				
				if(threshold % 10 != 0){
					return alert('The value for Gyroscope should be a multiple of 10 ranging from 10DPS to 1990DPS');
				}
			}
                    $this = $(this);
               
                    var succ_msg  =  'DONE';                  
                                       
                    var postdata = {device_id:device,
                                    device_type:devicetype,
                                    device_value:devicevalue,
                                    gateway_id:gateway,
                                    threshold:threshold                        
                                   }
                   
                   
                   
        var uid     =  $('#sesval').data('uid');
        var apikey  =  $('#sesval').data('key');
        if(uid!='' && apikey!=''){                   
                    $.ajax({
                            url: basePathUser + apiUrlSetThreshold,
                            type: 'POST',						
//                            data: postdata,
                            data: JSON.stringify(postdata),
                              contentType: 'application/json; charset=utf-8',
                              dataType: 'json',
                              async: false,                           
                              beforeSend: function (xhr){                                
//                                $('#addChannels').val('Saving..');
                                $this.parent().find('.done').html('wait..');
                                
                                xhr.setRequestHeader("uid", uid);
                                xhr.setRequestHeader("Api-Key", apikey);                                  
                            },
                            success: function(data, textStatus, xhr) {
//                                $('#addChannels').val('Save');
                                    if(xhr.status == 200 && textStatus == 'success') {
                                            	if(data.status == 'success') {
                                                   	$this.parent().find('.done').html('DONE');                                                
                                                   
                                                   	setTimeout(function(){  $this.parent().find('.done').html(''); }, 500);
		
							info_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> SET Threshold request has been sent and is in progress. Please wait for its response and then make the next GET/SET request..</div>';
							$('.infobar').html(info_html);

							setTimeout(function(){  $('.infobar').html(''); }, 10000);

                                            	}
						if(data.status == 'pending_request') {
							setTimeout(function(){  $this.parent().find('.done').html(''); }, 500);
							alert_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> GET/SET request is already pending. Please try after sometime!</div>';
						   	$('.alertbar').html(alert_html);
							setTimeout(function(){  $('.alertbar').html(''); }, 5000);
                                            	}

                                    } else {
                                            setTimeout(function(){  $this.parent().find('.done').html('FAILED'); }, 500);
                                    }
                            },
                            error: function(errData, status, error){
//                                    var resp = errData.responseJSON; 
//                                    $('.error').html(resp.description);
                                    setTimeout(function(){  $this.parent().find('.done').html('FAILED'); }, 500);
                                    
                                    if(errData.status==401){
                                        var resp = errData.responseJSON; 
                                        var description = resp.description; 
                                        $('.logout').click();
                                        alert(description);
                                    }                            
                                    
                            }
                    });
        }
                    
	});


	//click event to set stream

	$(document).on( "click", ".setstream",function(e){
                    e.preventDefault();
                    $('.success').html('');
                    $('.error,.error-tab').html('');
                                                                                
                    
                    var device         = $(this).data('device');
                    var devicetype     = $(this).data('devicetype');  
                    var gateway    = $(this).data('gateway');
                    var rate_value  = $(this).parent().find('input').val();
                    var format  = $(this).parent().find('select').val();
                   
			if(rate_value == ''){
				
				$(this).parent().find('.done').html('field blank');
				return false;
			}    
		
			if(format == '21' && (rate_value > 59 || rate_value < 20)){
				return alert('You can set values between 20 and 59 for seconds.');

			}      
			
		if(devicetype == '12'){
			if(format == '22' && (rate_value > 59 || rate_value < 2)){
				return alert('You can set values between 2 and 59 for minutes.');

			}
		}else{
			if(format == '22' && (rate_value > 59 || rate_value < 1)){
				return alert('You can set values between 1 and 59 for minutes.');

			}

		}
			if(format == '33' && (rate_value > 96 || rate_value < 1)){
				return alert('You can set values between 1 and 96 for hours.');

			}          

                    $this = $(this);
              
                    var succ_msg  =  'DONE';                  
                                       
                    var postdata = {device_id:device,
                                    device_type:devicetype,
                                    format:format,
                                    gateway_id:gateway,
                                    rate_value:rate_value                        
                                   }

        var uid     =  $('#sesval').data('uid');
        var apikey  =  $('#sesval').data('key');
        if(uid!='' && apikey!=''){      
		              
                    $.ajax({
                            url: basePathUser + apiUrlSetStream,
                            type: 'POST',                       
                            data: JSON.stringify(postdata),
                              contentType: 'application/json; charset=utf-8',
                              dataType: 'json',
                              async: false,                           
                              beforeSend: function (xhr){                                
                                $this.parent().find('.done').html('wait..');
                                
                                xhr.setRequestHeader("uid", uid);
                                xhr.setRequestHeader("Api-Key", apikey);                                  
                            },
                            success: function(data, textStatus, xhr) {
                                    if(xhr.status == 200 && textStatus == 'success') {
                                            	if(data.status == 'success') {                                                
                                                   	$this.parent().find('.done').html('DONE');                                                
                                                   
                                                   	setTimeout(function(){  $this.parent().find('.done').html(''); }, 500);

							info_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> SET Stream request has been sent and is in progress. Please wait for its response and then make the next GET/SET request..</div>';
						   	$('.infobar').html(info_html);
							setTimeout(function(){  $('.infobar').html(''); }, 10000);
                                            	}
						if(data.status == 'pending_request') {                                                                                                 
							setTimeout(function(){  $this.parent().find('.done').html(''); }, 500);
							alert_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> GET/SET request is already pending. Please try after sometime!</div>';
						   	$('.alertbar').html(alert_html);
							setTimeout(function(){  $('.alertbar').html(''); }, 5000);
						
                                            	}
                                    } else {
                                            setTimeout(function(){  $this.parent().find('.done').html('FAILED'); }, 500);
                                    }
                            },
                            error: function(errData, status, error){
//                                    var resp = errData.responseJSON; 
//                                    $('.error').html(resp.description);
                                    setTimeout(function(){  $this.parent().find('.done').html('FAILED'); }, 500);
                            }
                    });
		
        }
                    
    });	    


        
        /*
	 * Function 			: Click event on GET Current Value
	 * Brief 			: events which should happen while clicking on GET button in Devices page
	 * Detail 			: events which should happen while clicking on GET button in Devices page
	 * Input param 			: event
	 * Input/output param   	: NA
	 * Return			: NA
	 */	
	$(document).on( "click", ".getCurrentValue",function(e){
                    e.preventDefault();
                    $('.success').html('');
                    $('.error,.error-tab').html('');
                                        
                    
                    var device         = $(this).data('device');
                    var devicetype     = $(this).data('devicetype');                    
                    var gateway        = $(this).data('gateway');                   
                    
                    
                    $this = $(this);
               
                    var succ_msg  =  'DONE';                  
                                       
                    var postdata = {device_id:device,
                                    device_type:devicetype,
                                    gateway_id:gateway                       
                                   }
            var uid     =  $('#sesval').data('uid');
            var apikey  =  $('#sesval').data('key');
            if(uid!='' && apikey!=''){              
	      
                    $.ajax({
                            url: basePathUser + apiUrlGetCurrentValue,
                            type: 'POST',						
//                            data: postdata,
                            data: JSON.stringify(postdata),
                              contentType: 'application/json; charset=utf-8',
                              dataType: 'json',
                              async: false,                           
                              beforeSend: function (xhr){                                
                                $this.parent().find('.done').html('wait..');
                                
                                xhr.setRequestHeader("uid", uid);
                                xhr.setRequestHeader("Api-Key", apikey);                                  
                            },
                            success: function(data, textStatus, xhr) {
//                                $('#addChannels').val('Save');
                                    if(xhr.status == 200 && textStatus == 'success') {
                                            if(data.status == 'success') {
                                                
                                                   $this.parent().find('.done').html('Request Sent');                                                
                                                   
                                                   setTimeout(function(){  $this.parent().find('.done').html(''); }, 500);

						   info_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> GET request has been sent and is in progress. Please wait for its response and then make the next GET/SET request.</div>';
						   $('.infobar').html(info_html);
						
                                            }
					    if(data.status == 'pending_request') {
                                                	setTimeout(function(){  $this.parent().find('.done').html(''); }, 500);						
                                                  
							alert_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> GET/SET request is already pending. Please try after sometime!</div>';
						   	$('.alertbar').html(alert_html);
							setTimeout(function(){  $('.alertbar').html(''); }, 5000);
							
                                            }

						
                                    } else {
                                            setTimeout(function(){  $this.parent().find('.done').html('Request Failed'); }, 500);
                                    }
                            },
                            error: function(errData, status, error){
//                                    var resp = errData.responseJSON; 
//                                    $('.error').html(resp.description);
                                    setTimeout(function(){  $this.parent().find('.done').html('FAILED'); }, 5000);
                                    if(errData.status==401){
                                        var resp = errData.responseJSON; 
                                        var description = resp.description;     
                                        $('.logout').click();                                        
                                        alert(description);
                                    }                                    
                            }
                    });		
                    
            }
        });	  
        
        /*
	 * Function 			: Click event on GET Current Value
	 * Brief 			: events which should happen while clicking on GET button in Devices page
	 * Detail 			: events which should happen while clicking on GET button in Devices page
	 * Input param 			: event
	 * Input/output param   	: NA
	 * Return			: NA
	 */	
	$(document).on( "click", ".eachGateway",function(e){
                    e.preventDefault();
                    
            var gatewayId  =  $(this).data('gateway');
		$('.devicesTables').html('');
		$('.accStreamTable').html('');
		$('.coin-list').html('');
		$('.sensor-list').html('');
		$('.coins').html('');
		localStorage.setItem("coins_filter", null);
		localStorage.setItem("sensors_filter", null);
		localStorage.setItem("gateway_id", gatewayId);
		localStorage.setItem("panel_list", null);

            if(gatewayId!=''){
                $('.gateway-list-lfnav').find('li').find('a').removeClass('ancYellow');
                $(this).addClass('ancYellow');
                        
                //Remove old notifications        
               $('.detail-content').find('.fixfooter').find('.notifyline').html('');         
               //$('.detail-content').find('.fixfootere').find('.notifyline').html('');         
                        
		getDashboardCoin(gatewayId);
						
                getDevices(gatewayId, 0, 0);
		getAccStreamDevices(gatewayId, 0);

		clearInterval(autohandle1);

		autohandle1 = setInterval(function(){ refreshDashboard(); }, 30000);

            }
        });
       
//Example 2 =>http://www.hivemq.com/blog/build-javascript-mqtt-web-application
//        loadSubscriber();
 function loadSubscriber(sub_gatewayid) {
    
	$('.success').html('Connected to the gateway');
 
 
// client.connect(options);

 //Unsubscribe old all topics
// client.unsubscribe('#');
 
 
 var gwstr = sub_gatewayid.toString();
 client.subscribe(gwstr);
 
 
// if(subscribersArr.length>0){
//     $.each(subscribersArr,function(indSub,subVal){
        // var gwunSubStr = subVal.toString();
      //   if(gwstr!=gwunSubStr){

    //        client.unsubscribe(gwunSubStr); 
  //       }

  //   });
     
     subscribersArr  =  [];
// }
 
 subscribersArr.push(sub_gatewayid);

  }


	/*
	 * Function 			: getGatewaySettings()
	 * Brief 			: load the list of Gateway Settings	 
	 * Input param 			: Nil
	 * Input/output param           : NA
	 * Return			: NA
	 */		
	function getGatewaySettings() {
            $('#loader').show();

            var uid     =  $('#sesval').data('uid');
            var apikey  =  $('#sesval').data('key');
            if(uid!='' && apikey!=''){
                    $.ajax({
                            url: basePathUser + apiUrlGetGatewaySettings,
                            headers: {
           