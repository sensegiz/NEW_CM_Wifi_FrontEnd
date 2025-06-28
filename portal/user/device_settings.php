<?php
include_once('page_header_user.php');
$status_msg = '';
$id = 0;
?>

<!--<link rel="stylesheet" href="css/jquery-ui.css">-->

<!--<div class="col-lg-10 pad0 ful_sec">-->
<!--<div class="row pad0">-->
<div class="content userpanel">

	<?php
	include './user_menu.php';
	include './mqtt.php';
	?>
	<style>
		.table {
			width: 55%;
		}

		th {

			text-align: center;
		}

		ul.b {
			list-style-type: square;
			margin-top: -22px;
			margin-left: 25px;
		}

		@media only screen and (min-width: 200px) and (max-width:425px) {
			.table {
				width: 100%;
			}
		}
	</style>


	<div class="col-sm-10 col-10 mains">
		<!--toggle sidebar button-->
		<p class="visible-xs">
			<button type="button" class="btn btn-default btn-sm" id="more" id="menu-toggle" data-toggle="offcanvas"
				style="margin-bottom:10px"><span class="glyphicon glyphicon-chevron-left"></span> Gateways </button>
		</p>
		<h1>Device Settings</h1>
		<hr>
		<!--<h3 id="">Devices</h3>-->
		<div class="detail-content" style="background-color: #fff;">
			<div class="alertbar">

			</div>
			<!-- -->

			<div class="lp-det" style="margin-top:30px">

				<span class='error-tab'></span>


				<div class=" deviceSettingsTables">


					<div id='loader'><img src="../img/loader.gif" /> Loading Data</div>
				</div>
				<div class="infobar">

				</div>
			</div>

		</div>
	</div>

</div>
</div>


<!-- Update Nick Name of Coin -->

<div class="modal fade" id="modalEditNickName" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header text-center">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title w-100 font-weight-bold">Update Nick Name of Coin</h4>
			</div>


			<div class="modal-body ">
				<div class="container-fluid">
					<div class="CoinList">
						<span class='successncn'></span>
						<!--<div id="coin" class="collapse">-->
						<form class="form-horizontal">
							<span class='errorncn'></span>
							<input type="hidden" id="id" name="id" value="0" readonly />
							<div class="form-group">
								<div>
									<label>Gateway ID: </label><input class="form-control" type="text" id="gateway"
										name="gateway" value="" disabled style="margin-left:3px" />
								</div>
							</div>
							<div class="form-group">
								<div>
									<label>Current Coin Name: </label><input class="form-control" type="text"
										id="nickname" name="nickname" value="" disabled style="margin-left:3px" />
								</div>
								<div>
									<input class="form-control" type="hidden" id="deviceId" name="deviceId" value="" />
								</div>
							</div>


							<div class="form-group">
								<div>
									<span class='errorloc'></span>
									<label>New Coin Name:</label> <br><input class="form-control" name="newcoinname"
										id="newcoinname" type="text" value="">
								</div>


							</div>
							<div class="form-group">
								<div>
									<button type="button" class="btn btn-primary updatenickname" id="updatenickname"
										style="margin-left:3px">Update Nick Name</button>
								</div>
							</div>
						</form>
					</div>
				</div>

				<!-- Update Coin Location -->
				<div class="col-md-3">
					<div class="CoinListLoc">
						<b style="color: green"><span class='successncnloc'></span></b>
						<form class="form-horizontal" id="coinLocForm">
							<b style="color: red"><span class='errorncnloc'></span></b>
							<div class="form-group">
								<div>
									<input class="form-control" type="hidden" id="gateway_coin_loc"
										name="gateway_coin_loc" value="" disabled style="margin-left:3px" />
								</div>
							</div>
							<div class="form-group">
								<div>
									<label>Current Coin Location: </label><input class="form-control" type="text"
										id="cur_coin_location" name="cur_coin_location" value="" disabled
										style="margin-left:3px" />
								</div>
								<div>
									<input class="form-control" type="hidden" id="deviceId_coin_loc"
										name="deviceId_coin_loc" value="" />
								</div>
							</div>

							<div class="form-group">
								<div>
									<span class='errorloc'></span>
									<label>New Coin Location:</label> <br><input class="form-control"
										name="txtCoinNewloc" id="txtCoinNewloc" type="textStatust" value="">
								</div>
							</div>
							<div class="form-group">
								<div>
									<button type="button" class="btn btn-primary btnUpdateCoinLoc" id="btnUpdateCoinLoc"
										style="margin-left:3px">Update Location</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<!-- End: Update Coin Location -->
				<div class="modal-footer d-flex justify-content-center">

					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
		// var client = new Messaging.Client(server_ip, 8084, "vinkmyclientid_" + parseInt(Math.random() * 100, 10));
		var client = new Paho.MQTT.Client(server_ip, 8084, "vinkmyclientid_" + parseInt(Math.random() * 100, 10));
		//Gets  called if the websocket/mqtt connection gets disconnected for any reason
		client.onConnectionLost = function (responseObject) {

		};

		//Gets called whenever you receive a message for your subscriptions
		client.onMessageArrived = function (message) {
			console.log('--msg recvd--');

			// Retrieve the object from storage

			var fulMsg = message.payloadString;

			var arrMsg = fulMsg.split(',');
			console.log("Device Settings");
			console.log(arrMsg);

			var gwId = arrMsg[0];
			var devId = arrMsg[1];
			var devType = arrMsg[2];
			var devValue = arrMsg[3];
			var devNickname = arrMsg[4];
			var last_updated = arrMsg[5];
			var request_type = arrMsg[6];

			var stillUtc = moment.utc(last_updated).toDate();
			last_updated = moment(stillUtc).local().format('DD-MM-YYYY HH:mm:ss');
			var devTypeText = '';



			if (request_type == 'EnD') {

				if (devType == '41') {
					devTypeText = 'Accelerometer';
				}
				if (devType == '47') {
					devTypeText = 'Gyroscope';
				}
				if (devType == '54') {
					devTypeText = 'Temperature';
				}
				if (devType == '48') {
					devTypeText = 'Humidity';
				}
				if (devType == '74') {
					devTypeText = 'Temperature Stream';
				}
				if (devType == '68') {
					devTypeText = 'Humidity Stream';
				}
				if (devType == '61') {
					devTypeText = 'Accelerometer Stream';
				}

				info_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong>' + devNickname + ' - Active/Inactive request has been successfully processed for ' + devTypeText + '. You can now make the next request.</div>';
				$('.infobar').html(info_html);
				setTimeout(function () { $('.infobar').html(''); }, 90000);
				$(".sensor_active").removeAttr("disabled");
			}

			if (request_type == 'FREQ') {
				info_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong>' + devNickname + ' - SET Frequency request has been successfully processed. You can now make the next request.</div>';
				$('.infobar').html(info_html);
				setTimeout(function () { $('.infobar').html(''); }, 90000);

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

	<script type="application/javascript">

		var server_address = getBaseAddressUser();
		basePathUser = server_address + "/sensegiz-dev/user/";

		var apiUrlGateways = "gateways";
		var apiUrlGetDeviceSettings = "device-settings";
		var apiUrlUpdateSensorActive = "sensor-active";
		var apiUrlSetCoinPower = "set-coin-power";
		var apiUrlSetCoinFrequency = "set-coin-frequency";
		var apiUrlSetStreamThreshold = "set-stream-threshold";
		var apiUrlUpdateDeviceEmailNotification = "device-email-notification";
		var apiUrlrenameCoin = "rename-coin";
		var apiUrlXYZCombiChange = "xyz-combination-change";

		var apiUrlCoinLocation = "coin-location";

		/*
		 * Function             : GetGatewayList()
		 * Brief            : load the list of Gateways  
		 * Input param          : Nil
		 * Input/output param           : NA
		 * Return           : NA
		 */
		function GetGatewayList() {

			$('#loader').show();

			var uid = $('#sesval').data('uid');
			var apikey = $('#sesval').data('key');
			if (uid != '' && apikey != '') {
				$.ajax({
					url: basePathUser + apiUrlGateways,
					headers: {
						'uid': uid,
						'Api-Key': apikey
					},
					type: 'GET',
					contentType: 'application/json; charset=utf-8',
					dataType: 'json',
					async: false,
					beforeSend: function (xhr) {
						xhr.setRequestHeader("uid", uid);
						xhr.setRequestHeader("Api-Key", apikey);
					},
					success: function (data, textStatus, xhr) {
						$('#loader').hide();

						var sc_html = '';
						var gw_li_html = '';
						if (data.records.length > 0) {
							records = data.records;
							$.each(records, function (index, value) {
								var gateway_id = value.gateway_id;
								var status = value.status;

								// var res_gw_nickname = getGateway_nickname(gateway_id, uid, apikey);
								var res_gw_nickname = value.gateway_nick_name;
								console.log('res_gw_nickname devicesetting===' + res_gw_nickname);
								var gateway_name;
								if (res_gw_nickname == '' || res_gw_nickname == null || res_gw_nickname == 'null') {
									gateway_name = gateway_id;
								} else {
									gateway_name = res_gw_nickname;
								}


								if (status == 'Online') {
									status_html = '&nbsp&nbsp&nbsp<img src="../img/online.png"/>';
								} else {
									status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';

								}

								sc_html = '<tr><td>Click on any Gateway</td></tr>';

								gw_li_html += '<li><a href="javascript:;" class="gwlist" data-gateway="' + gateway_id + '">' + gateway_name + '' + status_html + '</a></li>';
							});
						}
						else {
							sc_html = '<tr><td width="300">No Gateways Found</td></tr>';
						}


						$('.gateway-list-lfnav').html(gw_li_html);
						$('.deviceSettingsTables').html(sc_html);


					},
					error: function (errData, status, error) {
						if (errData.status == 401) {
							var resp = errData.responseJSON;
							var description = resp.description;
							$('.logout').click();
							alert(description);

						}
					}
				});
			}
		}


		$(document).on("click", ".gwlist", function (e) {

			e.preventDefault();
			var uid = $('#sesval').data('uid');
			var apikey = $('#sesval').data('key');
			var gateway_id = $(this).data('gateway');


			if (uid != '' && apikey != '') {

				$.ajax({
					url: basePathUser + apiUrlGetDeviceSettings + '/' + gateway_id,
					headers: {
						'uid': uid,
						'Api-Key': apikey
					},
					type: 'GET',
					contentType: 'application/json; charset=utf-8',
					dataType: 'json',
					async: false,
					beforeSend: function (xhr) {
						xhr.setRequestHeader("uid", uid);
						xhr.setRequestHeader("Api-Key", apikey);
					},
					success: function (data, textStatus, xhr) {
						var sc_html = '';
						var deviceIds = [];

						if (data.records.length > 0) {
							records = data.records;

							sc_html += '<div class="alert alert-info alert-dismissable infos1">'
								+ '<a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>NOTE:</strong> <ul class="b"><li>Please make sure that the coin and Gateway are within each others range. A green LED on the coin is the indication for sensor activation/deactivation.</li>'
								+ '<li>For email alerts of the streaming sensors, make sure the low and high thresholds are set correctly.</li>'
								+ '<li>An email will be sent when the value of the corresponding sensor is less than the low threshold set or when it is greater  than the high threshold set.</li></ul>'

								+ '</div>';

							sc_html += '<p> <b> Enable or disable any sensor for your device. </b> </p></br>';
							$.each(records, function (index, value) {
								var device_id = value.device_id;
								var settings = value.settings;
								var nickname = value.nick_name;
								var coinlocation = value.coin_location;
								var firmware_type = value.firmware_type;
								var power = value.power;
								var frequency = value.frequency;

								var freq_html = '';
								var freq1_html = '';

								var res_gw_nickname = getGateway_nickname(gateway_id, uid, apikey);
								console.log('res_gw_nickname devicesetting===' + res_gw_nickname);
								var gateway_name;
								if (res_gw_nickname == '' || res_gw_nickname == null || res_gw_nickname == 'null') {
									gateway_name = gateway_id;
								} else {
									gateway_name = res_gw_nickname;
								}

								deviceIds.push(device_id);

								sc_html += '<table class="table table-hover table-bordered table-lp" style="text-align:center">'
									+ '<tbody>'
									+ '<tr>'
									+ '<th colspan="8" style="font-size: 13px;">' + gateway_name + ' - ' + nickname + '<a href="#"> <span class="editNickName" data-target="#modalEditNickName" data-toggle="modal" data-gateway="' + gateway_id + '" data-device="' + device_id + '" data-nickname="' + nickname + '" data-coinlocation="' + coinlocation + '"><span class="glyphicon glyphicon-edit" style="margin-left:30px"></span></span> </a></th></tr>';


								if (power == '4A') {
									sc_html += '<tr><th>Transmission Power: <select class="sel' + gateway_id + '' + device_id + '"  data-pow="' + power + '"><option value="4A" selected>180 feet</option><option value="4B" >150 feet</option><option value="4C">130 feet</option>'
										+ '<option value="4D">110 feet</option><option value="4E">90 feet</option><option value="4F">80 feet</option></select><button class="setTh1" id="power" style="height: 25px;margin-left:5px" data-gateway="' + gateway_id + '" data-device_id="' + device_id + '" data-pow="' + power + '"> Set </button><span class="powerdone grn"></span></th>';

								}
								if (power == '4B') {
									sc_html += '<tr><th>Transmission Power: <select class="sel' + gateway_id + '' + device_id + '" data-pow="' + power + '"><option value="4A">180 feet</option><option value="4B" selected>150 feet</option><option value="4C">130 feet</option>'
										+ '<option value="4D">110 feet</option><option value="4E">90 feet</option><option value="4F">80 feet</option></select><button class="setTh1" id="power" style="height: 25px;margin-left:5px" data-gateway="' + gateway_id + '" data-device_id="' + device_id + '" data-pow="' + power + '"> Set </button><span class="powerdone grn"></span></th>';

								}
								if (power == '4C') {
									sc_html += '<tr><th>Transmission Power: <select class="sel' + gateway_id + '' + device_id + '"  data-pow="' + power + '"><option value="4A">180 feet</option><option value="4B" >150 feet</option><option value="4C" selected>130 feet</option>'
										+ '<option value="4D">110 feet</option><option value="4E">90 feet</option><option value="4F">80 feet</option></select><button class="setTh1" id="power" style="height: 25px;margin-left:5px" data-gateway="' + gateway_id + '" data-device_id="' + device_id + '" data-pow="' + power + '"> Set </button><span class="powerdone grn"></span></th>';

								}
								if (power == '4D') {
									sc_html += '<tr><th>Transmission Power: <select class="sel' + gateway_id + '' + device_id + '"  data-pow="' + power + '"><option value="4A">180 feet</option><option value="4B" >150 feet</option><option value="4C">130 feet</option>'
										+ '<option value="4D" selected>110 feet</option><option value="4E">90 feet</option><option value="4F">80 feet</option></select><button class="setTh1" id="power" style="height: 25px;margin-left:5px" data-gateway="' + gateway_id + '" data-device_id="' + device_id + '" data-pow="' + power + '"> Set </button><span class="powerdone grn"></span></th>';

								}
								if (power == '4E') {
									sc_html += '<tr><th>Transmission Power: <select class="sel' + gateway_id + '' + device_id + '"  data-pow="' + power + '"><option value="4A">180 feet</option><option value="4B" >150 feet</option><option value="4C">130 feet</option>'
										+ '<option value="4D">110 feet</option><option value="4E" selected>90 feet</option><option value="4F">80 feet</option></select><button class="setTh1" id="power" style="height: 25px;margin-left:5px" data-gateway="' + gateway_id + '" data-device_id="' + device_id + '" data-pow="' + power + '"> Set </button><span class="powerdone grn"></span></th>';

								}
								if (power == '4F') {
									sc_html += '<tr><th>Transmission Power: <select class="sel' + gateway_id + '' + device_id + '"  data-pow="' + power + '"><option value="4A">180 feet</option><option value="4B" >150 feet</option><option value="4C">130 feet</option>'
										+ '<option value="4D">110 feet</option><option value="4E">90 feet</option><option value="4F" selected>80 feet</option></select><button class="setTh1" id="power" style="height: 25px;margin-left:5px" data-gateway="' + gateway_id + '" data-device_id="' + device_id + '" data-pow="' + power + '"> Set </button><span class="powerdone grn"></span></th>';

								}


								if (firmware_type == 'Predictive Maintenance') {
									var freq_array = { '2670': '02', '1330': '03', '592.6': '04', '266.67': '05', '133.33': '06', '66.67': '07', '33.33': '08', '6300': '09' };

									if (frequency == '01') {
										freq1_html += '<option value="01" selected>6670</option>';

									} else {
										freq1_html += '<option value="01">6670</option>';
									}

								} else {
									var freq_array = { '26': '02', '52': '03', '104': '04', '208': '05', '416': '06', '833': '07', '1660': '08', '3330': '09', '6660': '0A' };

									if (frequency == '01') {
										freq1_html += '<option value="01" selected>12.5</option>';

									} else {
										freq1_html += '<option value="01">12.5</option>';
									}
								}



								jQuery.each(freq_array, function (i, val) {

									if (frequency == val) {
										freq_html += '<option value="' + val + '" selected>' + i + '</option>';

									} else {
										freq_html += '<option value="' + val + '">' + i + '</option>';
									}
								});

								sc_html += '<th colspan="4"> Frequency (in Hz): <select class="self' + gateway_id + '' + device_id + '"  data-freq="' + frequency + '">';
								sc_html += freq1_html;
								sc_html += freq_html;
								sc_html += '</select><button class="setTh1" id="frequency" style="height: 25px;margin-left:5px" data-gateway="' + gateway_id + '" data-device_id="' + device_id + '" data-freq="' + frequency + '"> Set </button><span class="frequencydone grn"></span></th></tr>';



								sc_html += '<tr class="active tb-hd">'
									+ '<th width="50%">Sensor Type</th>'
									+ '<th>Active</th>'
									+ '<th>EMAIL</th>'
									+ '<th>Threshold for Notification</th>'
									+ '</tr>'
									+ '</tbody>'
									+ '<tbody class="users devices">';

								if (settings.length > 0) {
									var arrSenTypes = ['Accelerometer', 'Gyroscope', 'Temperature', 'Humidity', 'Temperature Stream', 'Humidity Stream', 'Accelerometer Stream', 'Predictive Maintenance'];
									if (firmware_type == 'Predictive Maintenance') {
										var XYZArr = {
											'NO': 'NO',
											'X': '02',
											'Y': '03',
											'Z': '04',
											'XYZ': '01'
										};
									} else {

										var XYZArr = {
											'NO': 'NO',
											'X': '04',
											'Y': '02',
											'Z': '01',
											'YZ': '03',
											'XZ': '05',
											'XY': '06',
											'XYZ': '07'
										};
									}
									optionXYZ = '';

									$.each(settings, function (indexSett, valueSett) {
										var sensor_type = valueSett.device_sensor;
										var sensor_active = valueSett.sensor_active;
										var activeChecked = "";
										var emailChecked = "";
										var email_alert = valueSett.email_alert;
										var low_threshold = valueSett.low_threshold;
										var high_threshold = valueSett.high_threshold;
										var XYZ_combination = valueSett.xyz_combination;

										if (XYZ_combination) {
											if (XYZ_combination.length == 1) {
												XYZ_combination = '0' + XYZ_combination;
											}
										}

										if (sensor_active == 'Y') {
											activeChecked = "checked='checked'";
										}

										if (email_alert == 'Y') {
											emailChecked = "checked='checked'";
										}

										if (sensor_type == 'Temperature Stream') {
											st = 'ts';
										}
										if (sensor_type == 'Humidity Stream') {
											st = 'hs';
										}
										if (sensor_type == 'Accelerometer Stream') {
											st = 'as';
										}

										if (sensor_type == 'Temperature Stream' || sensor_type == 'Humidity Stream' || sensor_type == 'Accelerometer Stream') {
											sc_html += '<tr>'
												+ '<td>' + sensor_type + '</td>'
												+ '<td><input type="checkbox" ' + activeChecked + ' name="sensor_active" class="sensor_active" value="' + sensor_active + '" data-sensor-type="' + sensor_type + '" data-gateway-id="' + gateway_id + '" data-device-id="' + device_id + '"/><span class="done"></span></td>'
												+ '<td id="set_css"><input type="checkbox" ' + emailChecked + ' name="email" class="deviceemail ' + gateway_id + device_id + st + 'email" value="' + email_alert + '" data-sensor-type="' + sensor_type + '" data-gateway-id="' + gateway_id + '"data-device-id="' + device_id + '"/><span class="done"></span></td>'
												+ '<td style="width:33%"><input type="text"  placeholder="low" class="thinpt2 ' + gateway_id + device_id + st + 'low" maxlength="3"  value="' + low_threshold + '"/><input type="text"  placeholder="high" class="thinpt2 ' + gateway_id + device_id + st + 'high" maxlength="3"style="margin-right:0px" value="' + high_threshold + '"/><button class="setTh1" id="setstreamthreshold" style="height: 28px;margin-left:5px;padding:0px 10px" data-sensor-type="' + sensor_type + '" data-gateway-id="' + gateway_id + '" data-device-id="' + device_id + '">SET</button><span class="setdone"></span> </td>'
												+ '</tr>'

										} else {
											if (sensor_type == 'Predictive Maintenance') {
												jQuery.each(XYZArr, function (i, val) {
													if (XYZ_combination == val) {
														optionXYZ += '<option  class="xyz_option" value= ' + val + ' selected>' + i + ' Axis</option>';

													} else {
														optionXYZ += '<option class="xyz_option" data-gateway-id= ' + gateway_id + ' data-device-id= ' + device_id + ' value= ' + val + '>' + i + ' Axis</option>';
													}
												});


												var drop =
													'<div><select class="xyz_combination" data-gateway-id= ' +
													gateway_id +
													' data-device-id= ' +
													device_id +
													' data-xyz-combination=' +
													XYZ_combination +
													' data-sensor-type=' + sensor_type +
													'>' +
													optionXYZ +
													'</select></div>';
												// var drop =
												//     '<div><form> <label class="checkbox-inline"><input type="checkbox" value="">X axis</label><label class="checkbox-inline"><input type="checkbox" value="">Y axis</label><label class="checkbox-inline"><input type="checkbox" value="">Z axis</label></form></div>';
												sc_html += '<tr>' +
													'<td>' + sensor_type +
													// drop +
													'</td>' +
													'<td>' + drop + '<span class="done"></span></td>' +
													'<td id="set_css">NA </td>' +
													'<td id="set_css">NA </td>' +
													'</tr>'
											} else {
												sc_html += '<tr>' +
													'<td>' + sensor_type +
													'</td>' +
													'<td><input type="checkbox" ' +
													activeChecked +
													' name="sensor_active" class="sensor_active" value="' +
													sensor_active +
													'" data-sensor-type="' +
													sensor_type +
													'" data-gateway-id="' +
													gateway_id + '" data-device-id="' +
													device_id +
													'"/><span class="done"></span></td>' +
													'<td id="set_css">NA </td>' +
													'<td id="set_css">NA </td>' +
													'</tr>'
											}

										}

										var removeItem = sensor_type;

										arrSenTypes = jQuery.grep(arrSenTypes, function (value) {
											return value != removeItem;
										});

									});

									if (arrSenTypes.length > 0) {
										$.each(arrSenTypes, function (indexRem, valueRem) {

											if (valueRem == 'Temperature Stream') {
												st = 'ts';
											}

											if (valueRem == 'Humidity Stream') {
												st = 'hs';
											}
											if (valueRem == 'Accelerometer Stream') {
												st = 'as';
											}


											if (valueRem == 'Temperature Stream' || valueRem == 'Humidity Stream' || valueRem == 'Accelerometer Stream') {
												sc_html += '<tr>'
													+ '<td>' + valueRem + '</td>'
													+ '<td><input type="checkbox" name="sensor_active" class="sensor_active" value="N" data-sensor-type="' + valueRem + '" data-gateway-id="' + gateway_id + '" data-device-id="' + device_id + '"/><span class="done"></span></td>'
													+ '<td id="set_css"><input type="checkbox" name="email" class="deviceemail ' + gateway_id + device_id + st + 'email" value="N" data-sensor-type="' + valueRem + '" data-gateway-id="' + gateway_id + '" data-device-id="' + device_id + '"/><span class="done"></span></td>'
													+ '<td><input type="text"  placeholder="low" class="thinpt2 ' + gateway_id + device_id + st + 'low" maxlength="3"  /><input type="text"  placeholder="high" class="thinpt2 ' + gateway_id + device_id + st + 'high" maxlength="3" /> <button class="setTh1" id="setstreamthreshold" style="height: 28px;margin-left:5px;padding:0px 10px" data-sensor-type="' + sensor_type + '" data-gateway-id="' + gateway_id + '" data-device-id="' + device_id + '">SET</button><span class="setdone"></span></td>'
													+ '</tr>';

											} else {
												sc_html += '<tr>'
													+ '<td>' + valueRem + '</td>'
													+ '<td><input type="checkbox" name="sensor_active" class="sensor_active" value="N" data-sensor-type="' + valueRem + '" data-gateway-id="' + gateway_id + '" data-device-id="' + device_id + '"/><span class="done"></span></td>'
													+ '<td id="set_css">NA </td>'
													+ '<td id="set_css">NA </td>'
													+ '</tr>';
											}
										});
									}
								}
								else {

									sc_html += '<tr>'
										+ '<td>Accelerometer</td>'
										+ '<td><input type="checkbox" name="sensor_active" class="sensor_active" value="N" data-sensor-type="Accelerometer" data-gateway-id="' + gateway_id + '" data-device-id="' + device_id + '"/><span class="done"></span></td>'
										+ '<td id="set_css">NA </td>'
										+ '<td id="set_css">NA </td>'
										+ '</tr>'
										+ '<tr>'
										+ '<td>Gyroscope</td>'
										+ '<td><input type="checkbox" name="sensor_active" class="sensor_active" value="N" data-sensor-type="Gyroscope" data-gateway-id="' + gateway_id + '" data-device-id="' + device_id + '"/><span class="done"></span></td>'
										+ '<td id="set_css">NA </td>'
										+ '<td id="set_css">NA </td>'
										+ '</tr>'
										+ '<tr>'
										+ '<td>Temperature</td>'
										+ '<td><input type="checkbox" name="sensor_active" class="sensor_active" value="N" data-sensor-type="Temperature" data-gateway-id="' + gateway_id + '" data-device-id="' + device_id + '"/><span class="done"></span></td>'
										+ '<td id="set_css">NA </td>'
										+ '<td id="set_css">NA </td>'
										+ '</tr>'
										+ '<tr>'
										+ '<td>Humidity</td>'
										+ '<td><input type="checkbox" name="sensor_active" class="sensor_active" value="N" data-sensor-type="Humidity" data-gateway-id="' + gateway_id + '" data-device-id="' + device_id + '"/><span class="done"></span></td>'
										+ '<td id="set_css">NA </td>'
										+ '<td id="set_css">NA </td>'
										+ '</tr>'
										+ '<tr>'
										+ '<td>Temperature Stream</td>'
										+ '<td id="set_css"><input type="checkbox" name="sensor_active" class="sensor_active" value="N" data-sensor-type="Temperature Stream" data-gateway-id="' + gateway_id + '" data-device-id="' + device_id + '"/><span class="done"></span></td>'

										+ '<td id="set_css"><input type="checkbox"  name="email" class="deviceemail ' + gateway_id + device_id + 'tsemail" value="N" data-sensor-type="Temperature Stream" data-gateway-id="' + gateway_id + '"data-device-id="' + device_id + '"/><span class="done"></span></td>'
										+ '<td><input type="text"  placeholder="low" class="thinpt2 ' + gateway_id + device_id + 'tslow" maxlength="3"  /><input type="text"  placeholder="high" class="thinpt2 ' + gateway_id + device_id + 'tshigh" maxlength="3"  /><button class="setTh1" id="setstreamthreshold" style="height: 28px;margin-left:5px;padding:0px 10px" data-sensor-type="' + sensor_type + '" data-gateway-id="' + gateway_id + '" data-device-id="' + device_id + '">SET</button><span class="setdone"></span></td>'
										+ '</tr>'
										+ '<tr>'
										+ '<td>Humidity Stream</td>'
										+ '<td id="set_css"><input type="checkbox" name="sensor_active" class="sensor_active" value="N" data-sensor-type="Humidity Stream" data-gateway-id="' + gateway_id + '" data-device-id="' + device_id + '"/><span class="done"></span></td>'

										+ '<td id="set_css"><input type="checkbox"  name="email" class="deviceemail ' + gateway_id + device_id + 'hsemail" value="N" data-sensor-type="Humidity Stream" data-gateway-id="' + gateway_id + '"data-device-id="' + device_id + '"/><span class="done"></span></td>'
										+ '<td><input type="text"  placeholder="low" class="thinpt2 ' + gateway_id + device_id + 'hslow" maxlength="3"  /><input type="text"  placeholder="high" class="thinpt2 ' + gateway_id + device_id + 'hshigh" maxlength="3"  /><button class="setTh1" id="setstreamthreshold" style="height: 28px;margin-left:5px;padding:0px 10px" data-sensor-type="' + sensor_type + '" data-gateway-id="' + gateway_id + '" data-device-id="' + device_id + '">SET</button><span class="setdone"></span></td>'
										+ '</tr>'
										+ '<tr>'
										+ '<td>Accelerometer Stream</td>'
										+ '<td id="set_css"><input type="checkbox" name="sensor_active" class="sensor_active" value="N" data-sensor-type="Accelerometer Stream" data-gateway-id="' + gateway_id + '" data-device-id="' + device_id + '"/><span class="done"></span></td>'

										+ '<td id="set_css"><input type="checkbox"  name="email" class="deviceemail ' + gateway_id + device_id + 'asemail" value="N" data-sensor-type="Accelerometer Stream" data-gateway-id="' + gateway_id + '"data-device-id="' + device_id + '"/><span class="done"></span></td>'
										+ '<td><input type="text"  placeholder="low" class="thinpt2 ' + gateway_id + device_id + 'aslow" maxlength="3"  /><input type="text"  placeholder="high" class="thinpt2 ' + gateway_id + device_id + 'ashigh" maxlength="3"  /><button class="setTh1" id="setstreamthreshold" style="height: 28px;margin-left:5px;padding:0px 10px" data-sensor-type="' + sensor_type + '" data-gateway-id="' + gateway_id + '" data-device-id="' + device_id + '">SET</button><span class="setdone"></span></td>'
										+ '</tr>'
								}

								sc_html += '</tbody>'
									+ '</table>';

							});
						}
						else {
							sc_html = '<table><tr><td width="300">No Data Found</td></tr></table>';
						}
						$('.deviceSettingsTables').html(sc_html);

						$.each(deviceIds, function (index, value) {

							$(".sel" + gateway_id + value).change(function () {
								pow = $(this).val();
								$(".sel" + gateway_id + value).data('pow', pow);

							});

							$(".self" + gateway_id + value).change(function () {
								freq = $(this).val();
								$(".self" + gateway_id + value).data('freq', freq);

							});

						});

						loadSubscriber(gateway_id);

					},
					error: function (errData, status, error) {
						if (errData.status == 401) {
							var resp = errData.responseJSON;
							var description = resp.description;
							alert(description);

						}
					}
				});
			}
		});



		$(document).on('click', '#power', function (e) {

			var uid = $('#sesval').data('uid');
			var apikey = $('#sesval').data('key');

			var gateway_id = $(this).data('gateway');
			var device_id = $(this).data('device_id');


			var power = $(".sel" + gateway_id + device_id).data('pow');



			if (power == '') {
				power = $(this).data('pow');

			}


			$(this).data('pow', power);
			$this = $(this);

			var postdata = {
				gateway_id: gateway_id,
				device_id: device_id,
				power: power
			}


			if (uid != '' && apikey != '') {
				$.ajax({
					url: basePathUser + apiUrlSetCoinPower,
					type: 'POST',
					data: JSON.stringify(postdata),
					contentType: 'application/json; charset=utf-8',
					dataType: 'json',
					async: false,
					beforeSend: function (xhr) {
						$this.parent().find('.powerdone').html('wait..');
						xhr.setRequestHeader("uid", uid);
						xhr.setRequestHeader("Api-Key", apikey);
					},
					success: function (data, textStatus, xhr) {
						if (xhr.status == 200 && textStatus == 'success') {
							$this.parent().find('.powerdone').html('Done');
							setTimeout(function () { $this.parent().find('.powerdone').html(''); }, 500);
							//$('.filterSelect').data('pow','');


						}
						else {
							setTimeout(function () { $this.parent().find('.powerdone').html('FAILED'); }, 500);
						}

					},
					error: function (errData, status, error) {
						setTimeout(function () { $this.parent().find('.powerdone').html('FAILED'); }, 500);
						if (errData.status == 401) {
							var resp = errData.responseJSON;
							var description = resp.description;
							$('.logout').click();
							alert(description);
						}
					}
				});
			}
		});

		$(document).on("change", ".xyz_combination", function (e) {
			// debugger;
			e.preventDefault();
			var uid = $('#sesval').data('uid');
			var apikey = $('#sesval').data('key');

			var gateway_id = $(this).data('gateway-id');
			var device_id = $(this).data('device-id');
			var xyz_combi = $(this).val();
			// var sensor_type = $(this).data('sensor-type');
			var sensor_type = "Predictive Maintenance";
			$this = $(this);
			var valactive = 'Y';
			var succ_msg = 'DONE';
			var postdata = {
				device_id: device_id,
				sensor_type: sensor_type,
				gateway_id: gateway_id,
				xyz_combination: xyz_combi,
				sensor_active: valactive
			}


			if (uid != '' && apikey != '') {
				$.ajax({
					url: basePathUser + apiUrlXYZCombiChange,
					type: 'POST',
					data: JSON.stringify(postdata),
					contentType: 'application/json; charset=utf-8',
					dataType: 'json',
					async: false,
					beforeSend: function (xhr) {
						$this.parent().find('.done').html('wait..');
						xhr.setRequestHeader("uid", uid);
						xhr.setRequestHeader("Api-Key", apikey);
					},
					success: function (data, textStatus, xhr) {
						if (xhr.status == 200 && textStatus == 'success') {
							if (data.status == 'success') {
								$this.parent().find('.done').html('Saved');
								setTimeout(function () {
									$this.parent().find('.done').html('');
								}, 500);

								info_html =
									'<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> Active/Inactive request has been sent. </div>';
								$('.infobar').html(info_html);
								$(".sensor_active").attr("disabled", "true");
								setTimeout(function () {
									$('.infobar').html('');
								}, 10000);
								setTimeout(function () {
									$(".sensor_active").removeAttr("disabled");
								}, 180000);

							}
							if (data.status == 'pending_request') {
								setTimeout(function () {
									$this.parent().find('.done').html('');
								}, 500);
								alert_html =
									'<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> GET/SET/Active/Inactive request is already pending. Please try after sometime!</div>';
								$('.alertbar').html(alert_html);
								setTimeout(function () {
									$('.alertbar').html('');
								}, 5000);

							}

						} else {
							setTimeout(function () {
								$this.parent().find('.done').html('Failed');
							}, 500);
						}

					},
					error: function (errData, status, error) {
						setTimeout(function () {
							$this.parent().find('.done').html('Failed');
						}, 500);

						if (errData.status == 401) {
							var resp = errData.responseJSON;
							var description = resp.description;
							$('.logout').click();
							alert(description);
						}
					}
				});
			}
		});
		//Update Sensor Active Settings
		$(document).on("change", ".sensor_active", function (e) {
			e.preventDefault();
			var uid = $('#sesval').data('uid');
			var apikey = $('#sesval').data('key');

			var gateway_id = $(this).data('gateway-id');
			var device_id = $(this).data('device-id');
			var sensor_type = $(this).data('sensor-type');
			var valactive = 'N';
			if (this.checked) {
				valactive = 'Y';
			}


			$this = $(this);
			var succ_msg = 'DONE';
			var postdata = {
				device_id: device_id,
				sensor_type: sensor_type,
				gateway_id: gateway_id,
				sensor_active: valactive
			}


			if (uid != '' && apikey != '') {
				$.ajax({
					url: basePathUser + apiUrlUpdateSensorActive,
					type: 'POST',
					data: JSON.stringify(postdata),
					contentType: 'application/json; charset=utf-8',
					dataType: 'json',
					async: false,
					beforeSend: function (xhr) {
						$this.parent().find('.done').html('wait..');
						xhr.setRequestHeader("uid", uid);
						xhr.setRequestHeader("Api-Key", apikey);
					},
					success: function (data, textStatus, xhr) {
						if (xhr.status == 200 && textStatus == 'success') {
							if (data.status == 'success') {
								$this.parent().find('.done').html('Saved');
								setTimeout(function () { $this.parent().find('.done').html(''); }, 500);

								info_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> Active/Inactive request has been sent. </div>';
								$('.infobar').html(info_html);
								$(".sensor_active").attr("disabled", "true");
								setTimeout(function () { $('.infobar').html(''); }, 10000);
								setTimeout(function () { $(".sensor_active").removeAttr("disabled"); }, 180000);

							}
							if (data.status == 'pending_request') {
								setTimeout(function () { $this.parent().find('.done').html(''); }, 500);
								alert_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> GET/SET/Active/Inactive request is already pending. Please try after sometime!</div>';
								$('.alertbar').html(alert_html);
								setTimeout(function () { $('.alertbar').html(''); }, 5000);

							}

						} else {
							setTimeout(function () { $this.parent().find('.done').html('Failed'); }, 500);
						}

					},
					error: function (errData, status, error) {
						setTimeout(function () { $this.parent().find('.done').html('Failed'); }, 500);

						if (errData.status == 401) {
							var resp = errData.responseJSON;
							var description = resp.description;
							$('.logout').click();
							alert(description);
						}
					}
				});
			}
		});

		$(document).on('click', '#frequency', function (e) {

			var uid = $('#sesval').data('uid');
			var apikey = $('#sesval').data('key');

			var gateway_id = $(this).data('gateway');
			var device_id = $(this).data('device_id');


			var frequency = $(".self" + gateway_id + device_id).data('freq');


			if (frequency == '') {
				frequency = $(this).data('freq');

			}

			$(this).data('freq', frequency);
			$this = $(this);

			var postdata = {
				gateway_id: gateway_id,
				device_id: device_id,
				frequency: frequency
			}


			if (uid != '' && apikey != '') {
				$.ajax({
					url: basePathUser + apiUrlSetCoinFrequency,
					type: 'POST',
					data: JSON.stringify(postdata),
					contentType: 'application/json; charset=utf-8',
					dataType: 'json',
					async: false,
					beforeSend: function (xhr) {
						$this.parent().find('.frequencydone').html('wait..');
						xhr.setRequestHeader("uid", uid);
						xhr.setRequestHeader("Api-Key", apikey);
					},
					success: function (data, textStatus, xhr) {
						if (xhr.status == 200 && textStatus == 'success') {
							if (data.status == 'success') {
								$this.parent().find('.frequencydone').html('Saved');
								setTimeout(function () { $this.parent().find('.frequencydone').html(''); }, 500);

								info_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> SET Frequency request has been sent. </div>';
								$('.infobar').html(info_html);

								setTimeout(function () { $('.infobar').html(''); }, 10000);


							}
							if (data.status == 'pending_request') {
								setTimeout(function () { $this.parent().find('.frequencydone').html(''); }, 500);
								alert_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> GET/SET/Active/Inactive/Frequency request is already pending. Please try after sometime!</div>';
								$('.alertbar').html(alert_html);
								setTimeout(function () { $('.alertbar').html(''); }, 5000);

							}

						} else {
							setTimeout(function () { $this.parent().find('.frequencydone').html('Failed'); }, 500);
						}

					},
					error: function (errData, status, error) {
						setTimeout(function () { $this.parent().find('.frequencydone').html('FAILED'); }, 500);
						if (errData.status == 401) {
							var resp = errData.responseJSON;
							var description = resp.description;
							$('.logout').click();
							alert(description);
						}
					}
				});
			}


		});


		$(document).on('click', '#setstreamthreshold', function (e) {
			console.log("SET STream Threshold");

			e.preventDefault();

			var uid = $('#sesval').data('uid');
			var apikey = $('#sesval').data('key');

			var gateway_id = $(this).data('gateway-id');
			var device_id = $(this).data('device-id');
			var sensor_type = $(this).data('sensor-type');

			var low = '0';
			var high = '0';

			$this = $(this);

			if (sensor_type == "Temperature Stream") {
				low = $('.' + gateway_id + device_id + 'tslow').val();
				high = $('.' + gateway_id + device_id + 'tshigh').val();

				if (low == '' || high == '') {
					return alert('Thresholds cannot be empty. The range for low and high Temperature Stream threshold value is -39 to 124 respectively.');

				}

				if (low == high) {
					return alert('Thresholds cannot be same.');

				}

				if (low < -39 || low > 124 || high < -39 || high > 124) {
					return alert('The range for low and high Temperature Stream threshold value is -39 to 124 respectively.');

				}

			}

			if (sensor_type == "Humidity Stream") {
				low = $('.' + gateway_id + device_id + 'hslow').val();
				high = $('.' + gateway_id + device_id + 'hshigh').val();

				if (low == '' || high == '') {
					return alert('Thresholds cannot be empty. The range for low and high Humidity Stream thresholds value is 2 to 99 respectively.');

				}
				if (low == high) {
					return alert('Thresholds cannot be same.');

				}

				if (low < 2 || low > 99 || high < 2 || high > 99) {
					return alert('The range for low and high Humidity Stream thresholds value is 2 to 99 respectively.');

				}

			}

			if (sensor_type == "Accelerometer Stream") {
				low = $('.' + gateway_id + device_id + 'aslow').val();
				high = $('.' + gateway_id + device_id + 'ashigh').val();

				if (low == '' || high == '') {
					return alert('Thresholds cannot be empty. The range for low and high Accelerometer Stream thresholds value is 0.2 to 15 respectively.');

				}
				if (low == high) {
					return alert('Thresholds cannot be same.');

				}
				if (low < 0.2 || low > 15 || high < 0.2 || high > 15) {
					return alert('The range for low and high Accelerometer Stream threshold value is 0.2 to 15 respectively.');

				}

			}

			var postdata = {
				sensor_type: sensor_type,
				gateway_id: gateway_id,
				device_id: device_id,
				lowthreshold: low,
				highthreshold: high
			}


			if (uid != '' && apikey != '') {
				$.ajax({
					url: basePathUser + apiUrlSetStreamThreshold,
					type: 'POST',
					data: JSON.stringify(postdata),
					contentType: 'application/json; charset=utf-8',
					dataType: 'json',
					async: false,
					beforeSend: function (xhr) {
						$this.parent().find('.setdone').html('wait..');
						xhr.setRequestHeader("uid", uid);
						xhr.setRequestHeader("Api-Key", apikey);
					},
					success: function (data, textStatus, xhr) {
						if (xhr.status == 200 && textStatus == 'success') {
							if (data.status == 'success') {
								$this.parent().find('.setdone').html('Saved');
								setTimeout(function () { $this.parent().find('.setdone').html(''); }, 500);

								info_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> Low and High Threshold are set for Notification. </div>';
								$('.infobar').html(info_html);

								setTimeout(function () { $('.infobar').html(''); }, 10000);


							}


						} else {
							setTimeout(function () { $this.parent().find('.setdone').html('Failed'); }, 500);
						}

					},
					error: function (errData, status, error) {
						setTimeout(function () { $this.parent().find('.setdone').html('FAILED'); }, 500);
						if (errData.status == 401) {
							var resp = errData.responseJSON;
							var description = resp.description;
							$('.logout').click();
							alert(description);
						}
					}
				});
			}


		});




		//Update EMAIL Notification Settings
		$(document).on("change", ".deviceemail", function (e) {
			e.preventDefault();

			var gateway_id = $(this).data('gateway-id');
			var device_id = $(this).data('device-id');
			var sensor_type = $(this).data('sensor-type');

			var valemail = 'N';
			if (this.checked) {
				valemail = 'Y';
			}


			$this = $(this);

			var succ_msg = 'DONE';

			var uid = $('#sesval').data('uid');
			var apikey = $('#sesval').data('key');


			var postdata = {
				sensor_type: sensor_type,
				gateway_id: gateway_id,
				device_id: device_id,
				email_alert: valemail
			}

			if (sensor_type == "Temperature Stream") {
				low = $('.' + gateway_id + device_id + 'tslow').val();
				high = $('.' + gateway_id + device_id + 'tshigh').val();

				if (low == '' || high == '') {
					$('.' + gateway_id + device_id + 'tsemail').prop("checked", false);
					return alert('Thresholds cannot be empty. The range for low and high Temperature Stream threshold value is -39 to 124 respectively.');

				}

				if (low < -39 || low > 124 || high < -39 || high > 124) {
					$('.' + gateway_id + device_id + 'tsemail').prop("checked", false);
					return alert('The range for low and high Temperature Stream threshold value is -39 to 124 respectively.');

				}

			}

			if (sensor_type == "Humidity Stream") {
				low = $('.' + gateway_id + device_id + 'hslow').val();
				high = $('.' + gateway_id + device_id + 'hshigh').val();

				if (low == '' || high == '') {
					$('.' + gateway_id + device_id + 'hsemail').prop("checked", false);
					return alert('Thresholds cannot be empty. The range for low and high Humidity Stream thresholds value is 2 to 99 respectively.');

				}

				if (low < 2 || low > 99 || high < 2 || high > 99) {
					$('.' + gateway_id + device_id + 'hsemail').prop("checked", false);
					return alert('The range for low and high Humidity Stream thresholds value is 2 to 99 respectively.');

				}

			}

			if (sensor_type == "Accelerometer Stream") {
				low = $('.' + gateway_id + device_id + 'aslow').val();
				high = $('.' + gateway_id + device_id + 'ashigh').val();

				if (low == '' || high == '') {
					$('.' + gateway_id + device_id + 'asemail').prop("checked", false);
					return alert('Thresholds cannot be empty. The range for low and high Accelerometer Stream thresholds value is 0.2 to 15 respectively.');

				}

				if (low < 0.2 || low > 15 || high < 0.2 || high > 15) {
					$('.' + gateway_id + device_id + 'asemail').prop("checked", false);
					return alert('The range for low and high Accelerometer Stream threshold value is 0.2 to 15 respectively.');

				}

			}



			if (uid != '' && apikey != '') {
				$.ajax({
					url: basePathUser + apiUrlUpdateDeviceEmailNotification,
					type: 'POST',
					//                            data: postdata,
					data: JSON.stringify(postdata),
					contentType: 'application/json; charset=utf-8',
					dataType: 'json',
					async: false,
					beforeSend: function (xhr) {

						$this.parent().find('.done').html('wait..');

						xhr.setRequestHeader("uid", uid);
						xhr.setRequestHeader("Api-Key", apikey);
					},
					success: function (data, textStatus, xhr) {
						//                                $('#addChannels').val('Save');
						if (xhr.status == 200 && textStatus == 'success') {
							if (data.status == 'success') {
								$this.parent().find('.done').html('Saved');

								setTimeout(function () { $this.parent().find('.done').html(''); }, 5000);
							}
						} else {
							$this.parent().find('.done').html('Failed');
							setTimeout(function () { $this.parent().find('.done').html(''); }, 5000);
						}
					},
					error: function (errData, status, error) {
						$this.parent().find('.done').html('Failed');
						setTimeout(function () { $this.parent().find('.done').html(''); }, 5000);

						if (errData.status == 401) {
							var resp = errData.responseJSON;
							var description = resp.description;
							$('.logout').click();
							alert(description);
						}

					}
				});
			}


		});


		$(document).on("click", ".editNickName", function (e) {

			e.preventDefault();
			$('.success,.error').html('');

			var gateway_id = $(this).data('gateway');
			var device_id = $(this).data('device');
			var nickname = $(this).data('nickname');

			var coin_location = $(this).data('coinlocation');
			$('#gateway_coin_loc').val(gateway_id);
			$('#deviceId_coin_loc').val(device_id);
			if (coin_location != null) {
				$('#cur_coin_location').val(coin_location);
			} else {
				coin_location = 'Location Not Set';
				$('#cur_coin_location').val(coin_location);
			}

			$('#gateway').val(gateway_id);
			$('#deviceId').val(device_id);
			$('#nickname').val(nickname);

		});



		$(document).on("click", ".updatenickname", function (e) {

			e.preventDefault();
			$('.success,.error').html('');

			var gateway_id = $('#gateway').val();
			var device_id = $('#deviceId').val();
			var nickname = $('#nickname').val();
			var newcoinname = $('#newcoinname').val();

			var uid = $('#sesval').data('uid');
			var apikey = $('#sesval').data('key');


			if (nickname == newcoinname) {
				return alert('The new nick name is same as the old one!');
			}

			var postData = {
				gateway_id: gateway_id,
				device_id: device_id,
				nick_name: newcoinname
			}


			if (uid != '' && apikey != '') {
				$.ajax({
					url: basePathUser + apiUrlrenameCoin,
					headers: {
						'uid': uid,
						'Api-Key': apikey
					},
					type: 'POST',
					data: JSON.stringify(postData),
					contentType: 'application/json; charset=utf-8',
					dataType: 'JSON',
					beforeSend: function (xhr) {
						xhr.setRequestHeader("uid", uid);
						xhr.setRequestHeader("Api-Key", apikey);
					},
					success: function (data, textStatus, xhr) {
						$('.errorncn').html('');
						$('.successncn').html("The coin name has been updated!");
						setTimeout(function () { $('.successncn').html(''); }, 5000);

					},
					error: function (error, textStatus, xhr) {
						if (errData.status == 401) {
							var resp = errData.responseJSON;
							var description = resp.description;
							alert(description);
						}

					}
				});
			}


		});



		$(document).on("click", ".btnUpdateCoinLoc", function (e) {

			e.preventDefault();
			$('.success,.error').html('');

			var gateway_id = $('#gateway_coin_loc').val();
			var device_id = $('#deviceId_coin_loc').val();
			var coincurrentloc = $('#cur_coin_location').val();
			var coinnewloc = $('#txtCoinNewloc').val();

			var uid = $('#sesval').data('uid');
			var apikey = $('#sesval').data('key');

			if (coincurrentloc == '') {
				return alert('Current Location:', coincurrentloc);
			}

			if (coincurrentloc == coinnewloc) {
				return alert('The new location name is same as the old one!');
			}

			var postData = {
				gateway_id: gateway_id,
				device_id: device_id,
				coin_location: coinnewloc
			}
			console.log('postData===', postData);

			if (uid != '' && apikey != '') {
				$.ajax({
					url: basePathUser + apiUrlCoinLocation,
					headers: {
						'uid': uid,
						'Api-Key': apikey
					},
					type: 'POST',
					data: JSON.stringify(postData),
					contentType: 'application/json; charset=utf-8',
					dataType: 'JSON',
					beforeSend: function (xhr) {
						xhr.setRequestHeader("uid", uid);
						xhr.setRequestHeader("Api-Key", apikey);
					},
					success: function (data, textStatus, xhr) {
						$('.errorncnloc').html('');
						$('.successncnloc').html("The coin location has been updated!");
						document.getElementById("coinLocForm").reset();
						setTimeout(function () { $('.successncnloc').html(''); }, 5000);
					},
					error: function (error, textStatus, xhr) {
						if (errData.status == 401) {
							var resp = errData.responseJSON;
							var description = resp.description;
							alert(description);
						}

					}
				});
			}


		});

		GetGatewayList();


		$(document).ready(function () {
			$("#modalEditNickName").modal({
				show: false,
				backdrop: 'static'
			});
		});

	</script>