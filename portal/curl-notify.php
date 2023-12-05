<?php include_once('../src/config/config.php');
?>
<script src="js/mqttws31.js" type="text/javascript"></script>
<script type="text/javascript">
var server_ip = '<?php echo SERVER_IP; ?>';

//function loadSubscriber() {
// connect the client
//    client.connect({onSuccess:onConnect});
console.log('I m 1');

// Create a client instance
var host = server_ip;
var port = 1883;
var clientId = "subsense" + parseInt(Math.random() * 100, 10);
client = new Paho.MQTT.Client(host, port, clientId);

// set callback handlers
client.onConnectionLost = onConnectionLost;
client.onMessageArrived = onMessageArrived;

//// connect the client
client.connect({
    onSuccess: onConnect
});

// called when the client connects
function onConnect() {
    // Once a connection has been made, make a subscription and send a message.
    console.log("onConnect");
    client.subscribe("/World");

    message = new Paho.MQTT.Message("Hello");
    message.destinationName = "/World";
    client.send(message);
}

// called when the client loses its connection
function onConnectionLost(responseObject) {
    if (responseObject.errorCode !== 0) {
        console.log("onConnectionLost:" + responseObject.errorMessage);
    }
}

// called when a message arrives
function onMessageArrived(message) {
    console.log("onMessageArrived:" + message.payloadString);
}

//  }
</script>

<?php
//            $url = "http://localhost/sensegiz/portal/notifications.php";
//            $dataArray = array("data"=>"curl ex");
//            
//          $postData = http_build_query($dataArray, '', '&');
//           $ch = curl_init();  
//           curl_setopt($ch,CURLOPT_URL,$url);
//           curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//           
//           curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3600);
//           
//           curl_setopt($ch,CURLOPT_HEADER, false); 
//           curl_setopt($ch, CURLOPT_POST, count($postData));               
//           curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
//
//           $output=curl_exec($ch);
//
//           curl_close($ch);
//           print_r($output);
//           print_r('curl executed');
?>