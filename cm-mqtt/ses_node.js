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

module.exports = {
    sesSend: function(Message,To,Subject, CcAddresses=[]) {
		if(!Array.isArray(CcAddresses) || !CcAddresses) {
			CcAddresses=[]
		}
		CcAddresses = CcAddresses.filter(x => (""+x).includes('@'))
		var params = {
		  Source: 'info@sensegiz.com',
		  Destination: {
		    ToAddresses: [
				To
			],
		   CcAddresses: CcAddresses
		},
		  Message: {
		    Body: {
		      Html: {
		        Charset: "UTF-8",
		        Data: Message
		      }
		    },
		    Subject: {
		      Charset: 'UTF-8',
		      Data: Subject
		    }
		  }
		};

	    var publishTextPromise = new AWS.SES(SESConfig).sendEmail(params).promise();
	    publishTextPromise.then(
	        function (data) {
			console.log("email id:");
			console.log(data.MessageId);
	            //res.end(JSON.stringify({ MessageID: data.MessageId }));
	        }).catch(
	            function (err) {
			console.log(err);
	            });
	} 
};
