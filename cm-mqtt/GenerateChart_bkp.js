var express = require('express');
var router = express.Router();

var moment = require('moment');
var XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest;

const ChartjsNode = require('chartjs-node');
var chartNode = new ChartjsNode(1000, 400);

const { Client } = require('pg');
var connection = require("./dbConfig.js");


function getDailyReport(){

	const dailyreport = "SELECT * FROM daily_report WHERE send_report='Y'";

	connection.query(dailyreport, (err, res) => {			
  		if(err){
    			console.log(err.stack);
  		}else{
			var reportcount = res.rows.length;

			for(i=0; i<reportcount; i++){

				var uid = res.rows[i]['user_id'];

				console.log(uid);

				const userDetails = "SELECT * FROM users WHERE user_id = "+uid+" limit 1";
				connection.query(userDetails, (e, r) => {
					date_format = r.rows[0]['date_format'];
					temp_unit = r.rows[0]['temp_unit'];
				});    


				var acc = res.rows[i]['accelerometer'];
				var gyro = res.rows[i]['gyroscope'];
				var temp = res.rows[i]['temperature'];
				var humid = res.rows[i]['humidity'];
				var tempstream = res.rows[i]['temperaturestream'];
				var humidstream = res.rows[i]['humiditystream'];
				var starttime = res.rows[i]['report_start_time'];
				var endtime = res.rows[i]['report_end_time'];

            			
				starttime = moment(starttime).format('YYYY-MM-DD HH:mm:ss');							
				endtime = moment(endtime).format('YYYY-MM-DD HH:mm:ss');


				//starttime = '2020-01-01 00:00:00+00';
				//endtime = '2020-01-31 00:00:00+00';

				var timezone = res.rows[i]['timezone'];

				
        			const zoneName = "SELECT name FROM pg_timezone_names WHERE utc_offset = '"+timezone+"' limit 1";
				connection.query(zoneName, (e, r) => {
					zone = r.rows[0]['name'];
				});    

				const gateways = "SELECT gateway_id FROM user_gateways WHERE user_id = '"+uid+"' AND gateway_id = '546C0E833E13' AND is_deleted =0 AND is_blacklisted='N'";

				connection.query(gateways, (err1, res1) => {			
  					if(err1){
    						console.log(err1.stack);
  					}else{
						var gatewayscount = res1.rows.length;

						for(j=0; j<gatewayscount; j++){

							var gateway_id = res1.rows[j]['gateway_id'];
							
							const coins = "SELECT * FROM gateway_devices WHERE gateway_id = '"+gateway_id+"' AND is_deleted =0 AND is_blacklisted='N'";

							connection.query(coins, (err2, res2) => {			
  								if(err2){
    									console.log(err2.stack);
  								}else{
									var coinscount = res2.rows.length;

									for(k=0; k<coinscount; k++){
										//var gateway_id = res2.rows[k]['gateway_id'];
										var device_id = res2.rows[k]['device_id'];

							            		
										if(device_id != ''){

											if(acc == 'Y'){ getDailyReportData(uid, gateway_id, device_id, '01', starttime, endtime, zone, date_format, temp_unit); }
											if(gyro == 'Y'){ getDailyReportData(uid, gateway_id, device_id, '03', starttime, endtime, zone, date_format, temp_unit); }
											if(temp == 'Y'){ getDailyReportData(uid, gateway_id, device_id, '05', starttime, endtime, zone, date_format, temp_unit); }
											if(humid == 'Y'){ getDailyReportData(uid, gateway_id, device_id, '07', starttime, endtime, zone, date_format, temp_unit); }
											
											if(tempstream == 'Y'){ getDailyReportData(uid, gateway_id, device_id, '09', starttime, endtime, zone, date_format, temp_unit); }
											if(humidstream == 'Y'){ getDailyReportData(uid, gateway_id, device_id, '10', starttime, endtime, zone, date_format, temp_unit); }

										}
									}
								}
							});


						}
					}
				});
			}

							
		}
	});
}


getDailyReport();



function getDailyReportData(uid, gateway_id, device_id, device_type, starttime, endtime, zone, date_format, temp_unit){

	console.log(uid);

	if(device_type == '01'){ device_type1 = '02'}
	if(device_type == '03'){ device_type1 = '04'}
	if(device_type == '05'){ device_type1 = '06'}
	if(device_type == '07'){ device_type1 = '07'}


	if(device_type == '09' || device_type == '10'){

		const reportdata = "SELECT device_type, device_value, (updated_on at time zone '"+zone+"') AS updated_on FROM devices_stream WHERE gateway_id='"+gateway_id+"' AND device_id='"+device_id+"' AND device_type='"+device_type+"' AND updated_on between '"+starttime+"' AND '"+endtime+"' AND is_deleted = 0 ORDER BY updated_on DESC";

		connection.query(reportdata, (err3, res3) => {			
  			if(err3){
    				console.log(err3.stack);
  			}else{
				var reportstdatacount = res3.rows.length;
				
				if(reportstdatacount != 0){
					var devVal=new Array();				
					var label=new Array();

					for(i=0; i<reportstdatacount; i++){
						var device_value = res3.rows[i]['device_value'];
						var last_updated_on =  '';
						last_updated_on = res3.rows[i]['updated_on'];						
						last_updated_on = moment(last_updated_on).format(date_format);

						if(device_type=='09' && temp_unit == 'Fahrenheit'){                                                            
							if(device_value!=null && device_value!='null' && device_value!=''){ 
								device_value = (device_value * 1.8) + 32;	
							}
							device_value = device_value.toFixed(3);
                                        	}
	
						devVal.push(device_value);
					
						var parts = last_updated_on.split(' ');							
						label.push([parts[0],parts[1]]);
					}

					devVal.reverse();				
					label.reverse();
					getChartOptions(gateway_id, device_id, device_type, devVal, '', '', label);
				}else{
					deleteOldCanvas(gateway_id, device_id, device_type);

				}
			}
		});	

	}else{
		const reportdata = "SELECT device_type, device_value, low_threshold, high_threshold, (updated_on at time zone '"+zone+"') AS updated_on FROM threshold WHERE gateway_id='"+gateway_id+"' AND device_id='"+device_id+"' AND device_type IN ('"+device_type+"', '"+device_type1+"') AND updated_on between '"+starttime+"' AND '"+endtime+"' AND is_deleted = 0 ORDER BY updated_on DESC";

		connection.query(reportdata, (err4, res4) => {			
  			if(err4){
    				console.log(err4.stack);
  			}else{
				var reportdatacount = res4.rows.length;

				if(reportdatacount != 0){

					var devVal=new Array();
					var lth=new Array();
					var hth=new Array();
					var label=new Array();

					for(i=0; i<reportdatacount; i++){
					
						var device_value = res4.rows[i]['device_value'];
						var low_threshold_value = res4.rows[i]['low_threshold'];
						var high_threshold_value = res4.rows[i]['high_threshold'];

						var last_updated_on =  '';
						last_updated_on = res4.rows[i]['updated_on'];						
						last_updated_on = moment(last_updated_on).format(date_format);							
							

						var decLowThres = hexToDec(low_threshold_value);
						var decHighThres = hexToDec(high_threshold_value);

						if(device_type == '01'){							
								
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
						if(device_type == '03'){					
							decLowThres = decLowThres * 10;
							decHighThres = decHighThres * 10;
						}

						if(device_type == '05'){
								
							if(decLowThres > 126){
								decLowThres = decLowThres - 126;
								decLowThres = -decLowThres;
							}
							if(decLowThres == 126){
								decLowThres = 0;
							}
						}

						if(device_type == '05' && temp_unit == 'Fahrenheit'){                                                            
							if(device_value!=null && device_value!='null' && device_value!=''){ 
								device_value = (device_value * 1.8) + 32;	
							}
							device_value = device_value.toFixed(3);

							decLowThres = (decLowThres * 1.8) + 32;
							decHighThres = (decHighThres * 1.8) + 32;
                                         	}
					
						devVal.push(device_value);
						lth.push(decLowThres);
						hth.push(decHighThres);

						var parts = last_updated_on.split(' ');							
						label.push([parts[0],parts[1]]);
						

					}//end of for

					devVal.reverse();
					lth.reverse();
					hth.reverse();
					label.reverse();
					getChartOptions(gateway_id, device_id, device_type, devVal, lth, hth, label);

				}else{
					deleteOldCanvas(gateway_id, device_id, device_type);

				}
			}
		});

	}

}


function hexToDec(hexNum){
    var dec = parseInt(hexNum, 16);
    
    return dec;
}

function deleteOldCanvas(gateway_id, device_id, device_type)
{		
			
	var fname = gateway_id + '_' + device_id + '_' + device_type + '.png';

	var xhr = new XMLHttpRequest();
	xhr.open('POST', '../sensegiz-mqtt/deleteOldCanvas.php?filename='+fname+'.png', true);
	xhr.send();
			    
			       						      	    
	
}


function getChartOptions(gateway_id, device_id, device_type, devVal, lth, hth, label){

console.log(gateway_id);

	var chartJsOptions = null;

	if(device_type == '09' || device_type == '10'){
 		chartJsOptions = {
    		type: 'line',

    		data: {
      			labels: label,

      			datasets: [{
        		
				label: "Device Value",
				fill: false,
				lineTension: 0.1,
				backgroundColor: "rgba(29, 202, 255, 1)",
				borderColor: "rgba(29, 202, 255, 1)",							
				pointRadius: 0,							
				hitRadius: 5,
				data: devVal,
      			}]
    		},

		options: {      
      			scales: {
        			yAxes: [{
          			
				ticks: {
                			beginAtZero: true
            			},
				scaleLabel: {
        				display: true,
        				labelString: ''
      				}

        			}],
        			xAxes: [{

				ticks: {
        				autoSkip: true,
        				maxTicksLimit: 9,
					minRotation:0,
					maxRotation: 0,
    				}
        			}]
      			}
    		}
		}
	}else{
		chartJsOptions = {
    		type: 'line',

    		data: {
      			labels: label,

			datasets: [{
        		
				label: "Low Threshold",
				fill: false,
				lineTension: 0.1,
				backgroundColor: "rgba(59, 89, 152, 1)",
				borderColor: "rgba(59, 89, 152, 1)",							
				pointRadius: 0,							
				hitRadius: 5,
				data: lth,
      				},
				{
        		
				label: "Device Value",
				fill: false,
				lineTension: 0.1,
				backgroundColor: "rgba(29, 202, 255, 1)",
				borderColor: "rgba(29, 202, 255, 1)",							
				pointRadius: 0,							
				hitRadius: 5,
				data: devVal,
      				},
				{
        		
				label: "High Threshold",
				fill: false,
				lineTension: 0.1,
				backgroundColor: "rgba(187, 0, 0, 1)",
				borderColor: "rgba(187, 0, 0, 1)",							
				pointRadius: 0,							
				hitRadius: 5,
				data: hth,
      				}
			]

      			
    		},

		options: {      
      			scales: {
        			yAxes: [{
          			
				ticks: {
                			beginAtZero: true
            			},
				scaleLabel: {
        				display: true,
        				labelString: ''
      				}

        			}],
        			xAxes: [{

				ticks: {
        				autoSkip: true,
        				maxTicksLimit: 9,
					minRotation:0,
					maxRotation: 0,
    				}
        			}]
      			}
    		}
		}

	}

	drawChartImage(chartJsOptions, gateway_id, device_id, device_type);
}


function drawChartImage(chartJsOptions, gateway_id, device_id, device_type){

	var fname = gateway_id + '_' + device_id + '_' + device_type;

console.log(fname);

	chartNode.drawChart(chartJsOptions)
	.then(streamResult => {
    		// using the length property you can do things like
    		// directly upload the image to s3 by using the
    		// stream and length properties
    		streamResult.stream // => Stream object
    		streamResult.length // => Integer length of stream

    		// write to a file
    		return chartNode.writeImageToFile('image/png', '../sensegiz-mqtt/daily_report/'+fname+'.png');
	});
}



module.exports = router;