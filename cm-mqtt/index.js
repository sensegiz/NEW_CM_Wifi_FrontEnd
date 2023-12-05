var express = require('express');
var app = express();


// var mqtt = require('./mqtt.js');
// app.use('/', mqtt);


//var sns = require('./sns.js');
//app.use('/', sns);

var ses = require('./ses.js');
app.use('/', ses);

//var GenerateChart = require('./GenerateChart.js');
//app.use('/', GenerateChart);


var sesattach = require('./sesattach.js');
app.use('/', sesattach);

app.listen(3000);