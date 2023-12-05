const express = require('express');
const app = express();
var router = express.Router();
require('dotenv').config();

var AWS = require('aws-sdk');
const fs = require('fs');

var SOURCE_EMAIL = "info@sensegiz.com";

const SESConfig = {
  apiVersion: '2010-12-01',
  accessKeyId: process.env.AWS_SES_ACCESS_KEY_ID,
  secretAccessKey: process.env.AWS_SES_SECRET_ACCESS_KEY,
  region: process.env.AWS_SES_REGION
};


router.get('/sesattach', (req, res) => {

   	var data = fs.readFileSync("/var/www/html/sensegiz-dev/" + req.query.file);

	var ses_mail = "From:" + SOURCE_EMAIL + "\n";
	ses_mail += "To: " + req.query.to + "\n";
	// ses_mail += "Subject: " + req.query.subject + "\n";
	ses_mail += "Subject: SenseGiz Daily Report\n";
	ses_mail += "MIME-Version: 1.0\n";
	ses_mail += "Content-Type: multipart/mixed; boundary=\"NextPart\"\n\n";
	ses_mail += "--NextPart\n";
	ses_mail += "Content-Type: text/html\n\n";
	ses_mail += "Please find attached the daily report from SenseGiz.\n\n";
	ses_mail += "--NextPart\n";
	ses_mail += "Content-Type: application/octet-stream; name=\"1_DailyReport.pdf\"\n";
	ses_mail += "Content-Transfer-Encoding: base64\n";
	ses_mail += "Content-Disposition: attachment\n\n";
	ses_mail += data.toString("base64").replace(/([^\0]{76})/g, "$1\n") + "\n\n";
	ses_mail += "--NextPart--";

	var params = {
    		RawMessage: {Data: ses_mail},
		Destinations: [req.query.to],
    		Source: "" + SOURCE_EMAIL + ""
	};


    var publishTextPromise = new AWS.SES(SESConfig).sendRawEmail(params).promise();
    publishTextPromise.then(
        function (data) {
            res.end(JSON.stringify({ MessageID: data.MessageId }));
        }).catch(
            function (err) {
                res.end(JSON.stringify({ Error: err }));
            });

});

module.exports = router; 