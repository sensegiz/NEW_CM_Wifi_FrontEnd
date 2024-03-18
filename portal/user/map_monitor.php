<?php
    $file_name  = basename($_SERVER['REQUEST_URI']);

    $active1 =  $active2 = $active3 =  $active4 =  $active5 =  $active6 =  '';
    if($file_name=='gateways.php'){
        $active1  =  'activecl';
    }
    else if($file_name=='devices.php'){
        $active2  =  'activecl';
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
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/developer.css" rel="stylesheet">
<link href="../css/responsive.css" rel="stylesheet">
<link rel="stylesheet" href="../css/jquery-ui.css">
<link href="../css/intlTelInput.css" rel="stylesheet" type="text/css">


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="../js/mqttws31.js" type="text/javascript"></script>

<link rel="stylesheet" href="../public/leaflet/leaflet.css?<?php echo time(); ?>"></script>
<script src="../public/leaflet/leaflet.js?<?php echo time(); ?>"></script>
<link rel="stylesheet" href="../public/leaflet/leaflet.rrose.css"></script>
<script src="../public/leaflet/leaflet.rrose-src.js"></script>


<link rel="stylesheet" href="../public/leaflet/Control.FullScreen.css"></script>
<script src="../public/leaflet/Control.FullScreen.js"></script>


<style>
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

	footer{
		display:none;
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
	  overflow:hidden;
	  border-top: 1px solid #f1f1f1;
	}

	li:hover {
	//background-color: #e6e6e6;
	color: black;
	cursor: pointer;
	}

		
	/* For desktop: */
	.col-1 {width: 8.33%;}
	.col-2 {width: 16.66%;}
	.col-3 {width: 25%;}
	.col-4 {width: 33.33%;}
	.col-5 {width: 41.66%;}
	.col-6 {width: 50%;}
	.col-7 {width: 58.33%;}
	.col-8 {width: 66.66%;}
	.col-9 {width: 75%;}
	.col-10 {width: 83.33%;}
	.col-11 {width: 91.66%;}
	.col-12 {width: 100%;}

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

	
	.realAlert, .deviceLog, .success, .error {
		display:block;
   		margin:0 auto;
	}

	.pull-right {
    		float: right !important;
    		margin-top: 24px;
	}


</style>

</head>
<body>
<!--<div class="outer-div">-->
<div class="content userpanel">
    
	<?php 

	include './user_menu_map.php';
	include './mqtt.php';
	?>      
   

    
<?php 
    if(isset($_SESSION['userId']) && isset($_SESSION['apikey'])){
        echo '<input type="hidden"name="sesval" id="sesval" value="" data-uid="'.$_SESSION['userId'].'" data-key="'.$_SESSION['apikey'].'" data-date_format="'.$_SESSION['date_format'].'" data-temp_unit="'.$_SESSION['temp_unit'].'"/> ';
    }
?>


	<div class="row content"style="margin-top:85px">
    		<div class="col-md-6 col-xs-12 col-lg-6 col-sm-6" id="locationMap" ></div>
    		<div class="col-md-4 col-xs-12 col-lg-5 col-sm-4 productDetails">
    			<div class="row">
					<div style="text-align:center;margin-top:13px"><h1> Map Studio Monitor </h1></div><hr>		
					<span><button class="save realAlert">Click here for live Updates</button></span>
					<br/><br/><span class="success" style="text-align:center;"></span>
					<br/><span class="error" style="text-align:center;"></span>

			</div><br/><br/>
			<div class="row deviceLog">
								
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
<script src="../js/mqttws31.js" type="text/javascript"></script>

<script>

var server_address = getBaseAddressUser();
   	var basePathUser = server_address+'/sensegiz-dev/user/';
   	var apiUrlGetCoin = 'get-coin';
   	var apiUrlGetGatewayLocation = 'get-gateway-location';
	

	var location_id = localStorage.getItem("location_id");
	var location_name = localStorage.getItem("location_name");
	var location_image = localStorage.getItem("location_image");

	var date_format  =  $('#sesval').data('date_format');
	
	
	getGatewayLocation(location_id);

	// retrieve gateway list for this location, set in custom_user.js when user clicks "edit" location
	var gatewayId = localStorage.getItem("gatewayId");



    // create the map
    var map = L.map('locationMap', {
        minZoom: 1,
        maxZoom: 5,
        center: [0, 0],
        zoom: 1,
	fullscreenControl: true,
	fullscreenControlOptions: { 
		title:"Show me the fullscreen !",
		titleCancel:"Exit fullscreen mode"
	},
        crs: L.CRS.Simple
    });

    var alert = L.icon ({
        	iconUrl: 'uploads/alert.gif',
		iconSize: [40, 40],
	});




    // leaflet map overlay image(location) url
    url = server_address+'/sensegiz-dev/portal/user/user_uploads/'+location_image+'';

    // assign image boundries to map window bounds
    var bounds = map.getBounds();


    // add the image overlay, so that it covers the entire map
    L.imageOverlay(url, bounds).addTo(map);

    // tell leaflet that the map is exactly as big as the image
    map.setMaxBounds(bounds);

    //disable map drag for zoom level 0 so blank space is not displayed when user drags map
    map.dragging.disable();

    //toggle map dragging on zoom
	map.on('zoomend', function() {
		currentZoom = map.getZoom();

		if(currentZoom > 1 ) {
		    map.dragging.enable();

		} else {
		    map.dragging.disable();

		}
	});
	

		
	var markersLayer = L.featureGroup().addTo(map);

   
    	map.on('load', getCoin(gatewayId));
	

		var server_ip = getBaseIPUser();
//Using the HiveMQ public Broker, with a random client Id
// var client = new Messaging.Client("broker.mqttdashboard.com", 8000, "vkmyclientid_" + parseInt(Math.random() * 100, 10));
//var client = new Messaging.Client(server_ip, 8084, "vinkmyclientid_" + parseInt(Math.random() * 100, 10));
 var client = new Paho.MQTT.Client(server_ip, 8084, "vinkmyclientid_" + parseInt(Math.random() * 100, 10));
console.log("mqtt connect===",client);

 //Gets  called if the websocket/mqtt connection gets disconnected for any reason
 client.onConnectionLost = function (responseObject) {
     //Depending on your scenario you could implement a reconnect logic here
//     alert("connection lost: " + responseObject.errorMessage);
 };

 //Gets called whenever you receive a message for your subscriptions
 client.onMessageArrived = function (message) {
     var fulMsg = message.payloadString;

     var arrMsg = fulMsg.split(',');
	

//     if(arrMsg.lenght>2){
         var gwId        = arrMsg[0];
         var devId       = arrMsg[1];
         var devType     = arrMsg[2];
	 var devValue    = arrMsg[3];
         var devNickname = arrMsg[4];
         var dtTime      = arrMsg[5]; 
         var last_updated = '';
            var stillUtc        =  moment.utc(dtTime).toDate();                                                           
            last_updated        =  moment(stillUtc).local().format(date_format);

	if(devType!= '09' && devType!='10' && devType!='11' && devType!='12' && devType!='13'  && devType!='14' && devType!='15' && devType!='16' && devType!='17'){
		renderAlert(gwId, devId, devType, devValue, last_updated);
	}
	
	//getDevices(gwId);
 };

 //Connect Options
 var options = {
 	useSSL: true,
	userName: "<?php echo $username ?>",
	password: "<?php echo $password ?>",
     timeout: 3,
     onSuccess: function () {
         client.subscribe('myname');
 
     },
     onFailure: function (message) {
     }
 };

 client.connect(options);





      </script>


<script type="application/javascript">

	$(document).on( "click", ".realAlert",function(e){
                    e.preventDefault();

		getGatewayLocation(location_id);

		var gateway_id = localStorage.getItem("gatewayId");
	        var gw_split  = gateway_id.split(",");
			var uid     =  $('#sesval').data('uid');
		var q = gw_split.length;

                for(i=0; i < q; i++) {
                    var g_id = gw_split[i];			
                 //   getDevices(g_id);
			loadSubscriber(gateway_id);
                }    		
		

        });
       




</script>



</body>
</html>