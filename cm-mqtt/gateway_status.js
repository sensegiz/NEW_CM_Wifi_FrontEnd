var express = require('express');
var router = express.Router();
var dbConnection = require('./db.js');
var moment = require('moment');

var ses = require('./ses.js');
var sesNode = require('./ses_node.js');

gateway_status();

// var coins_list1 = [];
function gateway_status()
{
	return new Promise((resolve, reject) => {

		dbConnection.query("SELECT * FROM user_gateways as ugt INNER JOIN users as u ON u.user_id = ugt.user_id WHERE (ugt.gateway_status_mail = 0) AND (ugt.last_ping_alert IS NOT NULL) AND (ugt.is_deleted = 0) AND (ugt.is_blacklisted= 'N') ORDER BY last_ping_alert ASC LIMIT 1", function (err, result) {
			if (err) {
				console.log(err);
				reject();
			}
			else {
				if(result.rowCount >= 1) {

					var ug_id = result.rows[0].ug_id;
					var user_id = result.rows[0].user_id;
					var emailId = result.rows[0].user_email;

					var gateway_id = result.rows[0].gateway_id;
					var gateway_nickname = result.rows[0].gateway_nick_name;
					// console.log('gateway_id:',gateway_id);
					var last_ping_alert = result.rows[0].last_ping_alert;
					

					var current_time = moment(Date.now()).format('YYYY-MM-DD HH:mm:ss');
	   				var start_date = moment(current_time, 'YYYY-MM-DD HH:mm:ss');
	   				var end_date = moment(last_ping_alert, 'YYYY-MM-DD HH:mm:ss');
					var duration = moment.duration(end_date.diff(start_date));

	   				var seconds = Math.abs(duration.asSeconds());       

					if(seconds >= 900){
						console.log('15 min');

						const updateGatewayMailStatus = "UPDATE user_gateways SET gateway_status_mail = '1', updated_on = DATE_TRUNC('second', NOW()) WHERE ug_id='"+ug_id+"'";
						dbConnection.query(updateGatewayMailStatus, (error, result) => {
							if(error) {
	                   			console.log(error); 
							}else{

								/*var list_coins = getGatewayCoins(gateway_id);
	   							console.log('func:',coins_list1);*/

								var emailsubject = "GATEWAY OFFLINE!!!";
										
								var emailmessage = '<html> <head>';
								emailmessage = emailmessage + '<title>Gateway is OFFLINE!</title>';
								emailmessage = emailmessage + '</head>';
								emailmessage = emailmessage + '<body aria-readonly="false" text-align="center">';
								emailmessage = emailmessage + '<div><h1>GATEWAY is OFFLINE!!!</h1>';

								emailmessage = emailmessage + '<p> Dear '+emailId+ ', <br /> <br />';
								// emailmessage = emailmessage + '<b>'+gateway_id+ '</b> has gone offline '+batteryLevel+' battery, you have to replace your battdery soon <br>';
								emailmessage = emailmessage + '<b> Gateway: '+gateway_id+ ' (' +gateway_nickname+ ')</b> has been detected offline <br /> ';
								// emailmessage = emailmessage + '<b> Registered COINs under this Gateway are: '+coins_list1;
								emailmessage = emailmessage + '<br /> <br /> Please check https://cm-testing.sensegiz.com/sensegiz-dev/portal/index.php</p> </div>';
								emailmessage = emailmessage + '- Team SenseGiz</p> </div>';
								emailmessage = emailmessage + '</body> </html>';

								sesNode.sesSend(emailmessage,emailId,emailsubject);  
								// clearArray(coins_list1); 
								gateway_status();

							} 
						
						});
					}
					else {
						// clearArray(coins_list1); 
						gateway_status();
					}
				}
				else {
					// clearArray(coins_list1); 
					gateway_status();
				}
			}
		});

	})

}


/*function getGatewayCoins(gateway_id){

	var gateway_coins_list = "SELECT nick_name, device_id FROM gateway_devices WHERE gateway_id = '"+gateway_id+"' AND is_blacklisted= 'N' AND is_deleted=0 ORDER BY gd_id ASC";
	dbConnection.query(gateway_coins_list, (err, res) => {			
  		if(err){
    			console.log(err.stack);
    			return;
  		}else{
  			
  			if(res.rowCount >= 1){
  				for(var i=0; i < res.rows.length; i++){
  					var coins_list = res.rows[i];
  					coins_list1.push(coins_list.nick_name);
  				}
  			}else{
  				coins_list1 = 'No COINs under this Gateway';
  			}
  			return coins_list1;
  		}
	});
}*/


/*function clearArray(coins_list1) {
  	while (coins_list1.length) {
	    coins_list1.pop();
	    // coins_list1.shift(); 
  	}
}*/