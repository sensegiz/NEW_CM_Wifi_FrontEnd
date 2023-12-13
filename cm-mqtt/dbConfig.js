var express = require('express');
var router = express.Router();

const { Client } = require('pg');

var connection = new Client({
  user: 'postgres',
  host: 'localhost',
  database: 'iot_stream',
  password: 'CoinLive@AWS',
  port: 5432,
});

connection.connect();


module.exports = connection;
