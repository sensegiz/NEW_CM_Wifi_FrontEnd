<?php
$file_name = basename($_SERVER['REQUEST_URI']);

$active1 = $active2 = $active3 = $active4 = $active5 = $active6 = '';
if ($file_name == 'gateways.php') {
	$active1 = 'activecl';
} else if ($file_name == 'devices.php') {
	$active2 = 'activecl';
}

ob_start();
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>

	<title>SenseGiz Portal</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="../css/master.css" rel="stylesheet">
	<link href="../css/responsive.css" rel="stylesheet">
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/developer.css" rel="stylesheet">
	<link rel="stylesheet" href="../css/jquery-ui.css">
	<script src="../js/jquery-1.11.0.min.js"></script>
	<link href="../css/intlTelInput.css" rel="stylesheet" type="text/css">


	<link rel="stylesheet" href="../public/leaflet/leaflet.css">
	</script>
	<script src="../public/leaflet/leaflet.js"></script>
	<link rel="stylesheet" href="../public/leaflet/leaflet.rrose.css">
	</script>
	<script src="../public/leaflet/leaflet.rrose-src.js"></script>

	<!-- <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"/> -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<!-- <script type="text/javascript" src="js/bootstrap.min.js"></script> -->
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- Leaflet CSS -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />


	<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

	<style>
		#map {
			width: 750px;
			height: 600px;
		}

		html {
			height: 100%;
		}

		body {
			height: 100%;

		}

		#locationMap {
			width: 750px;
			height: 600px;
		}

		footer {
			display: none;
		}

		.col-sm-12 {
			margin: 10px 10px 10px 10px;
		}

		.navbar {
			margin-bottom: 0px;
			border-radius: 0px;
			background: #fff;
			transition: border-color .15s;
			border-bottom: 1px solid #f1f1f1;
		}

		.leaflet-container img.leaflet-image-layer {
			max-width: none !important;
		}

		/* Set black background color, white text and some padding */
		footer {
			background-color: white;
			color: black;
			padding: 15px;
			position: absolute;
			left: 0;
			bottom: 0;
			height: 50px;
			width: 100%;
			overflow: hidden;
			border-top: 1px solid #f1f1f1;
		}

		li:hover {
			//background-color: #e6e6e6;
			color: black;
			cursor: pointer;
		}

		.addedCoin {
			background-color: white;
			color: black;
		}

		#searchGateway,
		#searchCoin {
			background-image: url('../img/searchicon.png');
			/* Add a search icon to input */
			background-position: 10px 12px;
			/* Position the search icon */
			background-repeat: no-repeat;
			/* Do not repeat the icon image */
			width: 100%;
			/* Full-width */
			font-size: 16px;
			/* Increase font-size */
			padding: 12px 20px 12px 40px;
			/* Add some padding */
			border: 1px solid #ddd;
			/* Add a grey border */
			margin-bottom: 12px;
			/* Add some space below the input */
		}

		#gatewayList,
		#coinList {
			/* Remove default list styling */
			list-style-type: none;
			padding: 0;
			margin: 0;
			text-align: center;
			//overflow-y:auto;
			//height:500px;

		}

		/* For desktop: */
		.col-1 {
			width: 8.33%;
		}

		.col-2 {
			width: 16.66%;
		}

		.col-3 {
			width: 25%;
		}

		.col-4 {
			width: 33.33%;
		}

		.col-5 {
			width: 41.66%;
		}

		.col-6 {
			width: 50%;
		}

		.col-7 {
			width: 58.33%;
		}

		.col-8 {
			width: 66.66%;
		}

		.col-9 {
			width: 75%;
		}

		.col-10 {
			width: 83.33%;
		}

		.col-11 {
			width: 91.66%;
		}

		.col-12 {
			width: 100%;
		}

		@media only screen and (max-width: 768px) {

			/* For mobile phones: */
			[class*="col-"] {
				width: 100%;
			}
		}

		.leaflet-popup-content-wrapper {
			text-align: center;
		}

		.btn {
			margin: 5px 5px 5px 5px;
		}

		.content {
			padding: 0;
			margin-left: 10px;
			margin-right: 10px;
		}

		.well,
		#addInfo {
			text-align: center;

		}

		.pull-right {
			float: right !important;
			margin-top: 24px;
		}

		.hp {
			width: 194px;
		}

		@media only screen and (min-width: 768px) and (max-width:900px) {
			[class*="col-  gate"] {
				width: 60%;
			}

			[class*="col-  coin"] {
				width: 60%;
				float: right;
				margin-top: -149px;
				margin-right: -70px;


			}
		}
	</style>

</head>

<body>
	<!--<div class="outer-div">-->
	<div class="content userpanel">
		<?php
		if (isset($_SESSION['userId']) && isset($_SESSION['apikey'])) {
			echo '<input type="hidden"name="sesval" id="sesval" value="" data-uid="' . $_SESSION['userId'] . '" data-key="' . $_SESSION['apikey'] . '" /> ';
		}

		?>
		<?php

		include './user_menu_map.php';

		?>
		<!--<div class="row">
	<div class="col-md-4" style="margin-left:20px; margin-top:10px;"><a href="../index.php"><img width="174" height="76" src="../img/logo.png" alt="logo"/></a></div> 
	<div class="col-md-4"> <h1> Map Studio Editor </h1> </div>     
	<?php

	//include './user_menu.php';
	
	?>	  
   </div>-->

		<div class="container-fluid text-center">
			<div class="row content" style="margin-top:85px">
				<div id="map"></div>
				<!-- <div class="col-md-6 col-xs-12 col-lg-6 col-sm-6" id="locationMap"></div> -->
				<div class="col-md-6 col-xs-12 col-lg-4 col-sm-6 productDetails">
					<div class="row">
						<div style="margin-top:6px">
							<h1>Map Studio Editor</h1>
						</div>
						<hr>

						<div id="addInfo" style="height:100%; width:100%;">
							<div class="col-md-12">
								<div class="well">Select a Gateway</div>
							</div>
						</div>
					</div>
					<div class="row deviceInfo">
						<div class="col-md-6 col-xs-12 col-lg-6 col-sm-6 gate" style="height:100%;  position: inherit;">
							<h4 style="text-align:center;">Gateways</h4>
							<input type="text" name="gateway" id="searchGateway" onkeyup="searchGateway()"
								placeholder="Search Gateway...">
							<ul class="list-group" id="gatewayList">
							</ul>
						</div>
						<div class="col-md-6 col-xs-12 col-lg-6 col-sm-6 coin" style="height:200px; ">
							<h4 style="text-align:center;">Coins</h4>
							<ul class="list-group" id="coinList">
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
		include_once('page_footer_user.php');
		?>


		<footer class="container-fluid text-center">
			<p>&copy; 2017 SenseGiz</p>
		</footer>

		<script>

			// Map initialization 
			var map = L.map('map').setView([15.8316955, 74.4609259], 12);

			//osm layer
			var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);
			map.on('load', getStoredMapLatLong(localStorage.getItem("location_id")));

			var server_address = getBaseAddressUser();
			var basePathUser = server_address + '/sensegiz-dev/user/';
			var apiUrlGetCoin = 'get-coin';
			var apiUrlUpdateCoinLocation = 'location-coin-update';
			var apiUrlrenameCoin = 'rename-coin';
			var apiUrlGetGatewayLocation = 'get-gateway-location';
			var apiUrlGetCoinLocation = 'get-coin-location';
			var apiUrlDeleteCoinLocation = 'delete-coin-location';
			var apiUrlCoinLocation = "coin-location";

			var location_id = localStorage.getItem("location_id");
			var location_name = localStorage.getItem("location_name");
			var location_image = localStorage.getItem("location_image");

			var added_coin_counter = 0;

			getGatewayLocation(location_id);

			// retrieve gateway list for this location, set in custom_user.js when user clicks "edit" location
			var gatewayId = localStorage.getItem("gatewayId");




			// create the map
			// var map = L.map('locationMap', {
			// 	minZoom: 1,
			// 	maxZoom: 5,
			// 	center: [0, 0],
			// 	zoom: 1,
			// 	crs: L.CRS.Simple,
			// 	maxBoundsViscosity: 1.0
			// });



			 var newMarker;

			// // leaflet map overlay image(location) url
			// url = server_address + '/sensegiz-dev/portal/user/user_uploads/' + location_image + '';

			// // assign image boundries to map window bounds
			 var bounds = map.getBounds();


			// // add the image overlay, so that it covers the entire map
			// L.imageOverlay(url, bounds).addTo(map);



			// // tell leaflet that the map is exactly as big as the image
			// map.setMaxBounds(bounds);



			// //disable map drag for zoom level 0 so blank space is not displayed when user drags map
			// map.dragging.disable();

			// //toggle map dragging on zoom
			// map.on('zoomend', function () {
			// 	currentZoom = map.getZoom();

			// 	if (currentZoom > 1) {
			// 		map.dragging.enable();

			// 	} else {
			// 		map.dragging.disable();

			// 	}
			// });

			//
			var coin_marker;




			var gateways = gatewayId.split(",");



			var gateway_list = '';

			//display list of gateways
			$.each(gateways, function (index, value) {
				var gateway_id = value;
				var uid = $('#sesval').data('uid');
				var apikey = $('#sesval').data('key');

				var res_gw_nickname = getGateway_nickname(gateway_id, uid, apikey);
				var gateway_name;
				if (res_gw_nickname == '' || res_gw_nickname == null || res_gw_nickname == 'null') {
					gateway_name = gateway_id;
				} else {
					gateway_name = res_gw_nickname;
				}

				gateway_list += '<li class="list-group-item thisGw" id="' + gateway_id + '" data-gateway_id="' + gateway_id + '"><a>' + gateway_name + '</a></li>';
			});
			$('#gatewayList').html(gateway_list);




			// event to display this gateway's coins as list(unadded) and on the map(alreadyadded)
			$('.thisGw').on('click', function () {

				//clear the map of any previously rendered markers to ensure markers of only this gateway are displayed

				if (!$('.leaflet-marker-pane').is(':empty')) {
					$('.leaflet-marker-pane').html('');
				}

				//css to set custom colors to list elements
				$('.thisGw').css({ 'background-color': 'transparent', 'color': 'black' });
				$(this).css({ 'background-color': '#e6e6e6', 'color': 'black' });

				// selected gateway
				var gateway_id = $(this).data('gateway_id');

				var uid = $('#sesval').data('uid');
				var apikey = $('#sesval').data('key');

				//ajax request to display 
				if (uid != '' && apikey != '') {
					$.ajax({
						url: basePathUser + apiUrlGetCoin + '/' + gateway_id,
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
							var cl_html = '<input type="text" name="coin" id="searchCoin" onkeyup="searchCoin()" placeholder="Search Coin...">';

							var msg_html = '';
							var records = data.records;


							//condition to check whether this gateway has coins registered, display message if not
							if (records[gateway_id][0] == "no_coin") {
								var cl_html = '<div class="col-md-12"><div class="well"><h5>Gateway has no devices. Register devices using the COIN app or select another Gateway</h5></div></div>';
								$('#addInfo').html(cl_html);
								$('#coinList').html(cl_html);

							} else if (records[gateway_id].length > 0) {
								//condition to display list of coins of this gateway
								//display message to select a coin
								var this_html = '<div class="col-md-12"><div class="well">Select a Coin</div></div>';
								$('#addInfo').html(this_html);

								//counter to keep a track of number of added and unadded coins of the gateway
								added_coin_counter = records[gateway_id].length;

								$.each(records[gateway_id], function (index, value) {
									//if coin is not mapped to location, display it in the list of unadded coins
									if (value.coin_lat === '' || coin_lng === '') {
										var gateway_id = value.gateway_id;
										var device_id = value.device_id;
										var nick_name = value.nick_name;
										cl_html += '<li class="list-group-item newCoin" id="' + device_id + '" data-gateway_id="' + gateway_id + '" data-id="' + device_id + '" data-coin_location ="' + coin_location + '">' + nick_name + '</li>';
									} else if (value.coin_lat != '' && coin_lng != '') {
										// if coin is mapped to location, display a marker on the map
										var gateway_id = value.gateway_id;
										var device_id = value.device_id;
										var nick_name = value.nick_name;
										var coin_lat = value.coin_lat;
										var coin_lng = value.coin_lng;
										var coin_location = value.coin_location;

										var res_gw_nickname = getGateway_nickname(gateway_id, uid, apikey);
										var gateway_name;
										if (res_gw_nickname == '' || res_gw_nickname == null || res_gw_nickname == 'null') {
											gateway_name = gateway_id;
										} else {
											gateway_name = res_gw_nickname;
										}

										added_coin_counter--;

										//coin marker icon
										var icon = L.icon({
											iconUrl: 'uploads/marker.png',
											iconSize: [25, 25],
										});

										//display marker with a custom popup message with edit/delete options
										var location = [coin_lat, coin_lng];


										var coinDataPop = '<p>' + gateway_name + ' - ' + nick_name + ' - ' + coin_location + ' </p><button class="btn btn-primary editCoin" data-gateway_id="' + gateway_id + '" data-device_id="' + device_id + '" data-nick_name="' + nick_name + '" data-coin_lat="' + coin_lat + '" data-coin_lng="' + coin_lng + '"  data-coin_location="' + coin_location + '">Edit</button><button class="btn btn-danger deleteCoin" data-id="' + gateway_id + '" data-device_id="' + device_id + '">Delete</button>';


										coin_marker = new L.marker(location, { icon: icon, riseOnHover: true })
											.addTo(map).on('click', function (e) {
												var popup = new L.Rrose({ offset: new L.Point(0, 10) })
													.setLatLng(location)
													.setContent(coinDataPop)
													.openOn(map);


												$('.editCoin').on('click', function (e) {
													e.preventDefault();
													map.closePopup();
													editCoin(gateway_id, device_id, nick_name, coin_lat, coin_lng, coin_location);

													$('.renameCoin').on('click', function () {
														var newNickname = $('#renameCoinInput').val();
														if (nick_name == newNickname) {
															return alert('The new nick name is same as the old one!');
														} else {
															renameCoinName(gateway_id, device_id, newNickname);
														}
													});
													$('.renameCoinLocation').on('click', function () {

														var newCoinLocation = $('#renameCoinLocationInput').val();
														if (coin_location == newCoinLocation) {
															return alert('The new coin location is same as the old one!');
														} else {
															renameCoinLocationfun(gateway_id, device_id, newCoinLocation);
														}
													});


												});

												$('.deleteCoin').on('click', function (e) {
													map.closePopup();
													deleteCoinLocation(gateway_id, device_id, nick_name);
												});


											});

										cl_html += '<li class="list-group-item addedCoin" title="Added to Map" id="' + device_id + '" data-coin_lat="' + coin_lat + '" data-coin_lng="' + coin_lng + '" data-gateway_id="' + gateway_id + '" data-device_id="' + device_id + '" data-coin_location="' + coin_location + '"><a>' + nick_name + '</a></li>';
									}
								});

								$('#coinList').html(cl_html);

								if (added_coin_counter == 0) {
									var added_html = '<div class="col-md-12"><div class="well"><p>All Coins are mapped. Select a coin on the map or from the list to edit or delete</p></div></div>';
									$('#addInfo').html(added_html);
								}

							}
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



			//custom event when user clicks added coin from coin list display option to edit/delete coin
			$(document).on("click", '.addedCoin', function (e) {
				//css to set custom colors to list elements
				$('.newCoin').css({ "color": "black", "background-color": "transparent" });
				$('.addedCoin').css('background-color', 'white');
				$('.addedCoin').css('color', 'black');
				$(this).css('background-color', '#E6E6E6');

				var gateway_id = $(this).data('gateway_id');
				var device_id = $(this).data('device_id');
				var nick_name = $(this).text();
				var coin_location = $(this).data('coin_location');

				var uid = $('#sesval').data('uid');
				var apikey = $('#sesval').data('key');

				//ajax request to display 
				if (uid != '' && apikey != '') {
					$.ajax({
						url: basePathUser + apiUrlGetCoinLocation + '/' + gateway_id + '/' + device_id,
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
							records = data.records;
							$.each(records, function (index, value) {
								var coin_lat = value.coin_lat;
								var coin_lng = value.coin_lng;

								$('.leaflet-marker-pane').html('');

								//coin marker icon
								var icon = L.icon({
									iconUrl: 'uploads/marker.png',
									iconSize: [25, 25],
								});

								coin_marker = new L.marker([coin_lat, coin_lng], { icon: icon, riseOnHover: true }).addTo(map);

								var popContent = '<p>Device Details: ' + gateway_id + ' - ' + nick_name + ' </p><button class="btn btn-primary editCoin" data-gateway_id="' + gateway_id + '" data-device_id="' + device_id + '" data-coin_lat="' + coin_lat + '" data-coin_lng="' + coin_lng + '">Edit</button><button class="btn btn-danger deleteCoin" data-id="' + gateway_id + '" data-device_id="' + device_id + '">Delete</button>';

								// var popUp = new L.popup({autoPan: false}).setLatLng([coin_lat, coin_lng]).setContent(popContent).openOn(map);

								var popUp = new L.Rrose({ offset: new L.Point(0, 10) }).setLatLng([coin_lat, coin_lng]).setContent(popContent).openOn(map);


								setTimeout(function () {
									map.closePopup();
								}, 1500);


								var addedInfo = '<div class="col-md-12"><div class="well"><p>Device Details: ' + gateway_id + ' - ' + nick_name + '</p><button class="btn btn-primary editCoin" data-gateway_id="' + gateway_id + '" data-device_id="' + device_id + '">Edit</button><button class="btn btn-danger deleteCoin" data-gateway_id="' + gateway_id + '" data-device_id="' + device_id + '">Delete</button></div>';
								$('#addInfo').html(addedInfo);

								// function to handle edit coin button click event
								$('.editCoin').on('click', function (e) {
									e.preventDefault();
									map.closePopup();
									editCoin(gateway_id, device_id, nick_name, coin_lat, coin_lng, coin_location);

									$('.renameCoin').on('click', function () {
										var newNickname = $('#renameCoinInput').val();
										if (nick_name == newNickname) {
											return alert('The new nick name is same as the old one!');
										} else {
											renameCoinName(gateway_id, device_id, newNickname);
										}
										$('.renameCoinLocation').on('click', function () {

											var newCoinLocation = $('#renameCoinLocationInput').val();
											if (coin_location == newCoinLocation) {
												return alert('The new coin location is same as the old one!');
											} else {
												renameCoinLocationfun(gateway_id, device_id, newCoinLocation);
											}
										});
									});

								});

								$('.deleteCoin').on('click', function (e) {
									map.closePopup();
									deleteCoinLocation(gateway_id, device_id, nick_name);
								});

							});
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


			//event to handle click on an unadded coin from the coin list function to let user add a coin to the location map
			$(document).on("click", '.newCoin', function (e) {
				$('.leaflet-marker-pane').html('');

				//css to set custom colors to list elements
				$('.newCoin').css({ 'background-color': 'transparent', 'color': 'black' });
				$(this).css({ 'background-color': '#e6e6e6', 'color': 'black' });
				$('.addedCoin').css('background-color', 'white');
				$('.addedCoin').css('color', 'white');


				var gateway_id = $(this).data('gateway_id');
				var device_id = $(this).data('id');
				var nick_name = $(this).text();
				var uid = $('#sesval').data('uid');
				var apikey = $('#sesval').data('key');

				var addedInfo = '<div class="col-md-12"><div class="well"><p>Place a marker on the map to add ' + gateway_id + ' - ' + nick_name + ' to the map.<br>You can drag the marker to place it accurately.</p></div>';
				$('#addInfo').html(addedInfo);

				var lat;
				var lng;

				var icon = L.icon({
					iconUrl: 'uploads/marker.png',
					iconSize: [25, 25]
				});

				//marker add event. record lat,lng and add html to allow user to map this coin at this point on the map.
				map.on('click', function (e) {
					$('#addInfo').html('You can drag the marker to place it accurately');
					if (newMarker) {
						map.removeLayer(newMarker);
					}

					newMarker = new L.marker(e.latlng, { icon: icon, draggable: 'true' }).addTo(map);

					lat = e.latlng.lat;
					lng = e.latlng.lng;

					var mapBounds = map.getBounds();

					newMarker.on('dragend', function (event) {
						$('#addInfo').html('');

						var marker = event.target;
						var position = marker.getLatLng();

						if (mapBounds.contains(position)) {

							lat = position.lat;
							lng = position.lng;

							var addedInfo = '<div class="col-md-12"><div class="well"><p>Gateway: ' + gateway_id + ', Coin: ' + nick_name + ' location updated on the map.</p><button class="btn btn-primary AddCoinToMap">Add to Map</button></div>';
							$('#addInfo').html(addedInfo);

							$('.AddCoinToMap').on('click', function () {
								if (gateway_id !== '' && device_id !== '' && lat != '' && lng != '') {
									addCoinToMap(gateway_id, device_id, lat, lng, nick_name);
								} else {
									$('#addInfo').html('One or More selections missing. Please ensure you have selected the gateway, coin and a marker on the map.');
								}
							});
						}
						else {
							var msg = '<div class="col-md-12"><div class="well"><p>Coin cannot be placed out of the Map view.</p></div>';


							$('#addInfo').html(msg);
						}
					});

					var addedInfo = '<div class="col-md-12"><div class="well"><p>Add Gateway: ' + gateway_id + ', Coin: ' + nick_name + ' to the map.</p><button class="btn btn-primary AddCoinToMap">Add to Map</button></div>';
					$('#addInfo').html(addedInfo);

					$('.AddCoinToMap').on('click', function () {
						if (gateway_id !== '' && device_id !== '' && lat != '' && lng != '') {
							addCoinToMap(gateway_id, device_id, lat, lng, nick_name);
						} else {
							$('#addInfo').html('One or More selections missing. Please ensure you have selected the gateway, coin and a marker on the map.');
						}
					});
				});
			});


			function addCoinToMap(gateway_id, device_id, lat, lng, nick_name) {
				var postData = {
					gateway_id: gateway_id,
					coin_id: device_id,
					coin_lat: lat,
					coin_lng: lng
				}


				var uid = $('#sesval').data('uid');
				var apikey = $('#sesval').data('key');
				var did = '#' + device_id;

				$.ajax({
					url: basePathUser + apiUrlAddCoin,
					headers: {
						'uid': uid,
						'Api-Key': apikey
					},
					type: 'POST',
					data: JSON.stringify(postData),
					contentType: 'application/json; charset=utf-8',
					dataType: 'JSON',
					async: false,
					beforeSend: function (xhr) {
						xhr.setRequestHeader("uid", uid);
						xhr.setRequestHeader("Api-Key", apikey);
					},
					success: function (data, textStatus, xhr) {
						var th = 'li#' + gateway_id;
						var addedInfo = '<div class="col-md-12"><div class="well">' + gateway_id + ' - ' + nick_name + ' Successfully mapped to location.</div></div>';
						$('#addInfo').html(addedInfo);
						$(did).remove();
						cl_html = '<li class="list-group-item addedCoin" title="Added to Map" id="' + device_id + '" data-coin_lat="' + lat + '" data-coin_lng="' + lng + '" data-gateway_id="' + gateway_id + '" data-device_id="' + device_id + '"><a>' + nick_name + '</a></li>';

						$('#coinList').append(cl_html);
						//$('.leaflet-marker-pane').html('');
						map.off('click');
						added_coin_counter--;

						if (added_coin_counter == 0) {
							var added_html = '<div class="col-md-12"><div class="well"><p>All Coins are mapped. Go to monitoring page or select a coin from the list to edit/delete.</p><a href="map-studio.php"><br><button class="btn btn-primary">Back to Map Center</button></a></div></div>';
							$('#addInfo').html(added_html);
						}
					},
					error: function (errData, status, xhr) {
						if (errData.status == 401) {
							var resp = errData.responseJSON;
							var description = resp.description;
							alert(description);
						}

					}
				});
			}


			var globalGatewayID;
			var currentDeviceID;
			var currentCoinLocation;
			// editor function to edit coin location on the location map and option to rename coin nick name.
			function editCoin(gateway_id, device_id, nick_name, coin_lat, coin_lng, coin_location) {

				globalGatewayID = gateway_id;
				currentDeviceID = device_id;
				currentCoinLocation = coin_location;

				var addedInfo = '<div class="col-md-12"><div class="well">'
					+ '<h4>Editing Options</h4>'
					+ '<label>Rename: </label>'
					+ '<input type="text" value="' + nick_name + '" id="renameCoinInput">'
					+ '</label><button class="btn btn-primary renameCoin">Update Nickname</button><hr>'
					+ '<label>Location: </label>'
					+ '<input type="text" value="' + coin_location + '" id="renameCoinLocationInput" placeholder="Coin Location Name">'
					+ '</label><button class="btn btn-primary renameCoinLocation" onclick="updateLocation()">Update Location</button><hr>'
					+ '<button class="btn btn-primary relocateCoin">Relocate</button>'
					+ '</div></div>';

				$('#addInfo').html(addedInfo);



				$('.relocateCoin').on('click', function () {


					//coin marker icon
					var icon = L.icon({
						iconUrl: 'uploads/marker.png',
						iconSize: [25, 25],
					});


					$('.leaflet-marker-pane').html('');
					var location = [coin_lat, coin_lng];
					var editMarker = L.marker(location, { icon: icon, draggable: true }).addTo(map);

					var popContent = '<p>Hi! You can drag me around to relocate me.</p>';
					var popUp = new L.Rrose({ offset: new L.Point(0, 10) }).setLatLng(location).setContent(popContent).addTo(map);

					setTimeout(function () {
						map.closePopup(popUp);
					}, 1500);

					var mapBounds = map.getBounds();


					editMarker.on('dragend', function (e) {


						var newCoinPosition = this.getLatLng();
						if (mapBounds.contains(newCoinPosition)) {


							var new_lat = newCoinPosition.lat;
							var new_lng = newCoinPosition.lng;
							var msg = '<div class="col-md-12"><div class="well">Coin Location updated. Click to save the updated location.<button class="btn btn-primary coinUpdateLocation">Update Location</button></div></div>';

							$('#addInfo').html(msg);


							//event handler to update coin location
							$('.coinUpdateLocation').on('click', function () {
								postData = {
									gateway_id: gateway_id,
									device_id: device_id,
									coin_lat: new_lat,
									coin_lng: new_lng
								}

								var uid = $('#sesval').data('uid');
								var apikey = $('#sesval').data('key');

								if (uid !== '' && apikey !== '') {
									$.ajax({
										url: basePathUser + apiUrlUpdateCoinLocation,
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
											$(this).data('coin_lat', new_lat);
											$(this).data('coin_lng', new_lng);
											var msg = '<div class="col-md-12"><div class="well"><p>The coin location has been updated.</p></div></div>';
											$('#addInfo').html(msg);
										},
										error: function (errData, status, xhr) {
											var msg = '<div class="col-md-12"><div class="well"><p>There was an error while updating the coin location.</p></div></div>';
											$('#addInfo').html(msg);
										}
									});
								}
							});
						}
						else {

							var msg = '<div class="col-md-12"><div class="well">Coin cannot be placed out of the Map view. Repositioning it back to its original location.</div></div>';

							$('#addInfo').html(msg);

							var coinDataPop = '<p>' + gateway_id + ' - ' + nick_name + '</p><button class="btn btn-primary editCoin" data-gateway_id="' + gateway_id + '" data-device_id="' + device_id + '" data-nick_name="' + nick_name + '" data-coin_lat="' + coin_lat + '" data-coin_lng="' + coin_lng + '">Edit</button><button class="btn btn-danger deleteCoin" data-id="' + gateway_id + '" data-device_id="' + device_id + '">Delete</button>';


							editMarker = L.marker(location, { icon: icon })
								.addTo(map).on('click', function (e) {
									var popup = new L.Rrose({ offset: new L.Point(0, 10) })
										.setLatLng(location)
										.setContent(coinDataPop)
										.openOn(map);


									$('.editCoin').on('click', function (e) {
										e.preventDefault();
										map.closePopup();
										editCoin(gateway_id, device_id, nick_name, coin_lat, coin_lng, coin_location);

										$('.renameCoin').on('click', function () {
											var newNickname = $('#renameCoinInput').val();
											if (nick_name == newNickname) {
												return alert('The new nick name is same as the old one!');
											} else {
												renameCoinName(gateway_id, device_id, newNickname);
											}
										});
										$('.renameCoinLocation').on('click', function () {

											var newCoinLocation = $('#renameCoinLocationInput').val();
											if (coin_location == newCoinLocation) {
												return alert('The new coin location is same as the old one!');
											} else {
												renameCoinLocationfun(gateway_id, device_id, newCoinLocation);
											}
										});
									});

									$('.deleteCoin').on('click', function (e) {
										deleteCoinLocation(gateway_id, device_id, nick_name);
									});

								});
						}

						//ajax call to update coin location
					});
				});
			}

			function deleteCoinLocation(gateway_id, device_id, nick_name) {
				var deleteInfo = '<div class="col-md-12"><div class="well">Deleting the coin will only delete it from the location. The coin will still be associated with this gateway.<br>Are You sure ?<br><button class="btn btn-danger deleteThisCoin">Delete Coin from Map.</button></div></div>';
				$('#addInfo').html(deleteInfo);

				$('.deleteThisCoin').on('click', function () {
					var postData = {
						gateway_id: gateway_id,
						device_id: device_id
					}

					var uid = $('#sesval').data('uid');
					var apikey = $('#sesval').data('key');
					var did = '#' + device_id;


					if (uid !== '' && apikey !== '') {
						$.ajax({
							url: basePathUser + apiUrlDeleteCoinLocation,
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
								var msg = '<div class="col-md-12"><div class="well"><p>The coin location has been deleted.</p></div></div>';
								$('#addInfo').html(msg);
								$(did).remove();
								cl_html = '<li class="list-group-item newCoin" id="' + device_id + '" data-gateway_id="' + gateway_id + '" data-id="' + device_id + '">' + nick_name + '</li>';
								$('#coinList').append(cl_html);
								$('.leaflet-marker-pane').html('');
								added_coin_counter++;

							},
							error: function (errData, status, xhr) {
								var msg = '<div class="col-md-12"><div class="well"><p>There was an error while deleting the coin location.</p></div></div>';
								$('#addInfo').html(msg);
							}
						});
					}
				});

			}


			//function to search gateways in list of gateways
			function searchGateway() {
				var input, filter, ul, li, a, i;
				input = document.getElementById('searchGateway');
				filter = input.value.toUpperCase();
				ul = document.getElementById('gatewayList');
				li = ul.getElementsByTagName('li');


				for (i = 0; i < li.length; i++) {
					a = li[i].getElementsByTagName("a")[0];

					if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
						li[i].style.display = '';
					} else {
						li[i].style.display = "none";
					}
				}

			}


			//function to search coin in list of coins
			function searchCoin() {
				var input, filter, ul, li, a, i;

				input = document.getElementById('searchCoin');
				filter = input.value.toUpperCase();
				ul = document.getElementById('coinList');
				li = ul.getElementsByTagName('li');


				for (i = 0; i < li.length; i++) {
					a = li[i];

					if (a.innerHTML.toUpperCase().replace(/<A>|<\/A>/g, "").indexOf(filter) > -1) {
						li[i].style.display = '';
					} else {
						li[i].style.display = 'none';
					}
				}
			}



			function renameCoinName(gateway_id, device_id, new_nick_name) {
				map.closePopup();

				var uid = $('#sesval').data('uid');
				var apikey = $('#sesval').data('key');


				var postData = {
					gateway_id: gateway_id,
					device_id: device_id,
					nick_name: new_nick_name
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
							//handle success here
							var addedInfo = '<div class="col-md-12"><div class="well">Coin has been successfully renamed.</div></div>';
							$('#addInfo').html(addedInfo);
							//if(xhr.status == 200 && textStatus == 'success') {
							//	if(data.status == 'success') {
							//clear markers pane on the map
							//		$('.leaflet-marker-pane').html('');
							//display success message
							//	}
							//}
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
			}

			function renameCoinLocationfun(gateway_id, device_id, newCoinLocation) {
				map.closePopup();
				debugger;
				var uid = $('#sesval').data('uid');
				var apikey = $('#sesval').data('key');


				var postData = {
					gateway_id: gateway_id,
					device_id: device_id,
					coin_location: newCoinLocation
				}


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
							//handle success here
							var addedInfo = '<div class="col-md-12"><div class="well">Location has been successfully renamed.</div></div>';

							$('#addInfo').html(addedInfo);
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
			}
			function updateLocation() {
				var gateway_id = globalGatewayID
				var device_id = currentDeviceID;
				var coin_location = currentCoinLocation;
				var newCoinLocation = $('#renameCoinLocationInput').val();
				if (coin_location == newCoinLocation) {
					return alert('The new coin location is same as the old one!');
				} else {
					renameCoinLocationfun(gateway_id, device_id, newCoinLocation);
				}
			}
			$(".renameCoinLocation").click(function () {
				alert("Handler for .click() called.");
			});
		</script>


</body>

</html>