const express = require('express');
const app = express();
var router = express.Router();
require('dotenv').config();

var AWS = require('aws-sdk');

router.get('/sns', (req, res) => {

    console.log("Message = " + req.query.message);
    console.log("Number = " + req.query.number);
    console.log("Subject = " + req.query.subject);
    var params = {
        Message: req.query.message,
        PhoneNumber: '+' + req.query.number,
        MessageAttributes: {
            'AWS.SNS.SMS.SenderID': {
                'DataType': 'String',
                'StringValue': 'SNSGIZ' //req.query.subject
            }
        }
    };

    var publishTextPromise = new AWS.SNS({ apiVersion: '2010-12-01' }).publish(params).promise();
    publishTextPromise.then(
        function (data) {
            res.end(JSON.stringify({ MessageID: data.MessageId }));
        }).catch(
            function (err) {
                res.end(JSON.stringify({ Error: err }));
            });

});


module.exports = router; 