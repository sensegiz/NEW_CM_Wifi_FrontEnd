<?php

include_once ('page_header_user.php');

?>

<div class="content userpanel">
	<style>
		//.content {
		height:100%;
		margin-left: 0px;
		padding: 0px 0px;
		/* overflow: scroll;*/
		}



		.active a label {
			background-color: #fff !important;
			color: #333;
		}

		.accordion {
			background-color: #fff;
			color: #444;
			cursor: pointer;
			padding: 10px;
			width: 99.8%;
			//  border-bottom: 1px black;
			text-align: left;
			outline: none;
			font-size: 15px;
			transition: 0.4s;

		}

		.active1,
		.accordion:hover {
			background-color: #ccc;

		}

		.accordion:after {
			content: '\002B';
			color: #777;
			font-weight: bold;
			float: right;
			margin-left: 5px;
		}

		.active1:after {
			content: "\2212";
		}

		.panel {
			padding: 0 1px;
			display: none;
			background-color: white;
			overflow: hidden;
		}

		@media only screen and (min-width: 200px) and (max-width:445px) {
			.col-sm-3 {
				width: 46%;
				margin-left: -35px;
			}

			.col-sm-5 {
				float: right;
				width: 46%;
				margin-top: -69px;
				margin-right: 70px;
			}

		}

		@media only screen and (min-width: 440px) and (max-width:665px) {
			.col-sm-3 {
				width: 37%;
				margin-left: -35px;
			}

			.col-sm-5 {
				float: right;
				width: 43%;
				margin-top: -70px;
				margin-right: 127px;
			}

			.dashboardfilter {
				margin-right: 35px;
				float: right;
				margin-top: -70px;


			}
		}

		@media only screen and (min-width: 665px) and (max-width:767px) {
			.col-sm-3 {
				width: 37%;
				margin-left: -35px;
			}

			.col-sm-5 {
				float: left;
				width: 43%;
				margin-top: -70px;
				margin-left: 168px;
			}

			.dashboardfilter {
				margin-right: 196px;
				float: right;
				margin-top: -70px;


			}
		}

		@media only screen and (min-width: 768px) and (max-width:900px) {
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

			.dashboardfilters {
				margin-right: 272px;
				float: right;
				margin-top: -31px;
			}
		}

		@media only screen and (min-width: 1000px) and (max-width:20000px) {
			.col-sm-5 {

				width: 25.66%;

			}
		}

		.panel>.table-bordered,
		.panel>.table-responsive>.table-bordered {

			border: 1px solid #ddd;

		}

		.panel {

			margin-bottom: 0px;
			background-color: #fff;
			border: 1px solid transparent;

			border-radius: 4px;

			-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);

			box-shadow: 0 1px 1px rgba(0, 0, 0, .05);

		}

		//.btn{
		white-space:normal;
		}
	</style>




	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>


	<link rel="stylesheet"
		href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"
		type="text/css" />
	<script
		src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

	<?php
	include './user_menu.php';


	include './mqtt.php';


	?>
	<div class="col-sm-10 col-10 mains">

		<!--toggle sidebar button-->
		<p class="visible-xs">
			<button type="button" class="btn btn-default btn-sm" id="more" id="menu-toggle" data-toggle="offcanvas"
				style="margin-bottom:10px"><span class="glyphicon glyphicon-chevron-left"></span> Gateways </button>
		</p>


		<h1>Coins</h1>
		<hr>

		<div class="mine"></div>
		<div class="detail-content" style="background-color: #fff;">
			<div class=" col-4 col-sm-4 alertbar">

			</div>


			<span class='error-tab'></span>

			<div class="row coins" style="margin-left:20px">

				<div class="showclicktext"></div>

			</div><br /><br />
			<div class=" devicesTables">

				<div id='loader'><img src="../img/loader.gif" /> Loading Data</div>

			</div>
			<div class="predStreamTable"> </div>
			<div class="accStreamTable"> </div>

			<div class="col-4 col-sm-4 infobar">
			</div>
			<div class="fixfooter" style="z-index: 0;">

				<h3>Recent updates: <span id="slideCtrl"><img id="imgCtrl" src="../img/arrow-down-wht.png" width="20px"
							height="20px" /></span></h3>
				<div class="ntfn-box">
					<p id="notifyBar"></p>
				</div>

				<!--<span id="notifyBar"></span>-->
			</div>




		</div>
	</div>

</div>


<script src="../js/mqttws31.js" type="text/javascript"></script>
<script type="text/javascript">

	var retrievedObject = localStorage.getItem('location_id');
	console.log('retrievedObject: ', JSON.parse(retrievedObject));

	//wifi
	//Using the HiveMQ public Broker, with a random client Id
	// var client = new Messaging.Client("broker.mqttdashboard.com", 8000, "vkmyclientid_" + parseInt(Math.random() * 100, 10));
	var server_ip = window.location.host;

	//var client = new Messaging.Client(server_ip, 8084, "vinkmyclientid_" + parseInt(Math.random() * 100, 10));
	client = new Paho.MQTT.Client(server_ip, 8084, "vinkmyclientid_" + parseInt(Math.random() * 100, 10));
	console.log("mqtt connect===", client);
	//Gets  called if the websocket/mqtt connection gets disconnected for any reason
	client.onConnectionLost = function (responseObject) {
		//Depending on your scenario you could implement a reconnect logic here
		//     alert("connection lost: " + responseObject.errorMessage);
	};

	




	//Gets called whenever you receive a message for your subscriptions
	client.onMessageArrived = function (message) {
		 console.log('--msg recvd--',message);

		// Retrieve the object from storage

		// console.log(message.destinationName);
		console.log(message.payloadString);
		var fulMsg = message.payloadString;

		var arrMsg = fulMsg.split(',');
		console.log("first index of message", arrMsg[0]);
		if (arrMsg[0] == 'coinStatus') {


			console.log("it coin status: ", arrMsg[0]);
			var coinStatusMsg = arrMsg[1];
			var notMsg = '<p class="notifyline">' + coinStatusMsg + '</p>';
			$("#notifyBar").after(notMsg);
		} else {


			console.log('arrMsg==', arrMsg);
			//     if(arrMsg.lenght>2){
			var gwId = arrMsg[0];
			var devId = arrMsg[1];
			var devType = arrMsg[2];
			var devValue = arrMsg[3];
			var devNickname = arrMsg[4];
			var last_updated = arrMsg[5];
			var request_type = arrMsg[6];
			var format = arrMsg[7];
			console.log('format==', format);
			var rate_value = arrMsg[8];

			var date_format = $('#sesval').data('date_format');
			var temp_unit = $('#sesval').data('temp_unit');

			var stillUtc = moment.utc(last_updated).toDate();
			last_updated = moment(stillUtc).local().format(date_format);
			var devTypeText = '';
			var formatText = '';

			var gid = localStorage.getItem("gateway_id");


			if (gwId == gid) {

				if (devType == '09') {
					devTypeText = 'Temperature';
				}

				if (devType == '10') {
					devTypeText = 'Humidity';
				}

				if (devType == '12') {
					devTypeText = 'Accelerometer';
				}

				if (devType == '51' || devType == '71') {
					devTypeText = 'Spectrum';
				}

				if (request_type == 'SETS') {
					if (format == 15) { formatText = ' Seconds'; }
					if (format == 16) { formatText = ' Minutes'; }
					if (format == 21) { formatText = ' Hours'; }
					var rate_value = hexToDec(rate_value);
					var notMsg = '<p class="notifyline">' + devNickname + ' - SET Stream for ' + devTypeText + ' done. Value: ' + rate_value + ' ' + formatText + '</p>';

				}

				if (devType == '13') {
					if (devValue == '1' || devValue == '01') {
						devTypeText = 'Battery is Full.';
					}
					if (devValue == '2' || devValue == '02') {
						devTypeText = 'Battery is Medium.';
					}
					if (devValue == '3' || devValue == '03') {
						devTypeText = 'Battery is Low.';
					}
					var notMsg = '<p class="notifyline">' + devNickname + ' - ' + devTypeText + '</p>';
				}


				if (devType != '09' && devType != '10' && devType != '11' && devType != '12' && devType != '13' && devType != '14' && devType != '15' && devType != '16' && devType != '51' && devType != '71' && request_type != 'EnD') {
					//var decDevValue = hexToDec(devValue);

					updatedRequestActionTaken(gwId, devId, devType);

					if (devType == '01') {
						devTypeText = 'Accelerometer Low';
					}

					if (devType == '02') {
						devTypeText = 'Accelerometer High';
					}

					if (devType == '03') {


						devTypeText = 'Gyroscope Low';
					}

					if (devType == '04') {

						devTypeText = 'Gyroscope High';
					}

					if (devType == '05') {
						devTypeText = 'Temperature Low';
						if (temp_unit == 'Fahrenheit') {
							devValue = (devValue * 1.8) + 32;
							devValue = parseFloat(devValue).toFixed(3);
						}
					}

					if (devType == '06') {
						devTypeText = 'Temperature High';
						if (temp_unit == 'Fahrenheit') {
							devValue = (devValue * 1.8) + 32;
							devValue = parseFloat(devValue).toFixed(3);
						}
					}

					if (devType == '07') {
						devTypeText = 'Humidity Low';
					}

					if (devType == '08') {
						devTypeText = 'Humidity High';
					}

					if (request_type == 'SET') {

						console.log("devValue___Acceleration", devValue);
						let thresholdManualRange = ["0.024","0.041","0.061","0.081"];

						if(!thresholdManualRange.includes(devValue)){
							devValue = hexToDec(devValue);
						}
                    
					
						if (devType == '01' || devType == '02') {
							if(devValue == '0.024' || devValue == '0.041'||devValue == '0.061' || devValue == '0.081'){

							}
							else if (devValue == 1)
								devValue = 0.001;
							else if (devValue == 2)
								devValue = 0.1;
							else
								devValue = devValue / 8;
						}
						if (devType == '03' || devType == '04') {
							devValue = devValue * 10;
							console.log("devValue________devType______", devValue);
							var thresholdJsonData2 = {
								"2020": "2",
								"2030": "3",
								"2040": "4",
								"2050": "5",
								"2060": "6",
								"2070": "7",
								"2080": "8",
								"2090": "9"
							};
							if (thresholdJsonData2.hasOwnProperty(devValue)) {

								devValue = thresholdJsonData2[devValue]
								console.log("devValue______________", devValue);
							}
						}
						if (devType == '05' || devType == '06') {
							if (devValue > 126) {
								devValue = devValue - 126;
								devValue = -devValue;
							}
							if (devValue == 126) {
								devValue = 0;

							}
							if (temp_unit == 'Fahrenheit') {
								devValue = (devValue * 1.8) + 32;
								devValue = parseFloat(devValue).toFixed(3);
							}
						}

						var notMsg = '<p class="notifyline">' + devNickname + ' - SET Threshold for ' + devTypeText + ' done. Value: ' + devValue + '</p>';

					} else {
						var notMsg = '<p class="notifyline">' + devNickname + '-' + devTypeText + '-Value-' + devValue + '-Updated on-' + last_updated + '</p>';

					}

				}

				$("#notifyBar").after(notMsg);


				//        var el = document.getElementById('notifyBar'),
				//            // Make a new div
				//            elChild = document.createElement("p");
				//
				//        // Give the new div some content
				//        elChild.innerHTML = 'Last Updated: Gateway- '+gwId+'  --'+devNickname;
				//
				//        // Chug in into the parent element
				//        el.appendChild(elChild);         
				//     }

				//Do something with the push message you received
				//     $('#messages').append('<span>Topic: ' + message.destinationName + '  | ' + message.payloadString + '</span><br/>');

			}
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
			// alert("Connected");
			client.subscribe('myname');
			//var autohandle = null;

			//getGateways();
			//autohandle = setInterval(function(){ getGateways(); }, 60000);
		},
		//Gets Called if the connection could not be established
		onFailure: function (message) {
			//    alert("Connection failed: " + message.errorMessage);
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
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>-->
<?php
include_once ('page_footer_user.php');
?>



<script type="text/javascript">

	var autohandle = null;

	getGateways();
	autohandle = setInterval(function () { getGateways(); }, 60000);

	$(document).ready(function () {


		$('.coin-list').multiselect({
			maxHeight: 150,
			includeSelectAllOption: true,
			numberDisplayed: 2

		});
	});


	var panel_list = [];


	$(document).on("click", ".accordion", function (e) {

		var cln = $(this).attr('class').split(' ').pop();


		this.classList.toggle("active1");


		var d = this.classList.contains("active1");

		if (d == true) {
			panel_list.push(cln);
		} else {
			panel_list.splice($.inArray(cln, panel_list), 1);
		}

		localStorage.setItem("panel_list", panel_list);

		var panel = this.nextElementSibling;

		if (panel.style.display === "block") {
			panel.style.display = "none";
		} else {
			panel.style.display = "block";
		}


	});
</script>