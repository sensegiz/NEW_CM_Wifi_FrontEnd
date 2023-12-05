const express = require('express');
const app = express();
var router = express.Router();
require('dotenv').config();

var AWS = require('aws-sdk');

const SESConfig = {
  apiVersion: '2010-12-01',
  accessKeyId: process.env.AWS_SES_ACCESS_KEY_ID,
  secretAccessKey: process.env.AWS_SES_SECRET_ACCESS_KEY,
  region: process.env.AWS_SES_REGION
};

router.get('/ses', (req, res) => {

    console.log("Message = " + req.query.message);
    console.log("To = " + req.query.to);
    console.log("Subject = " + req.query.subject);

	var params = {
	  Source: 'info@sensegiz.com',
	  Destination: {
	    ToAddresses: [
			req.query.to
		]
	},
	  Message: {
	    Body: {
	      Html: {
	        Charset: "UTF-8",
	        Data: req.query.message
	      }
	    },
	    Subject: {
	      Charset: 'UTF-8',
	      Data: req.query.subject
	    }
	  }
	};

    var publishTextPromise = new AWS.SES(SESConfig).sendEmail(params).promise();
    publishTextPromise.then(
        function (data) {
console.log(data.MessageId);
            res.end(JSON.stringify({ MessageID: data.MessageId }));
        }).catch(
            function (err) {
console.log(err);
                res.end(JSON.stringify({ Error: err }));
            });

});

module.exports = router; 

//http://44.232.193.106:3000/ses/?message=nodejs&to=prmalakoti@gmail.com&subject=test-api-nodejs
