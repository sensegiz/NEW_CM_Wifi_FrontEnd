var { FFT } = require("../portal/js/fft/dsp.js");
var moment = require("moment");
const { Client } = require("pg");

var connection = require("./dbConfig.js");

var GATEWAY_ID;
var DEVICE_ID;
var RECEVIED_ON;
var DEVICE_TYPE;
var ID_ARR = [];

getData();


function getData() {
  try {
    connection.query(
      "SELECT DISTINCT gateway_id FROM fft_basevalue WHERE action = 0 AND is_deleted =0",
      async function (err, res) {
        if (err) {
          console.log(err);
        } else {
          var dataCount = res.rows.length;
          var data = res.rows;
          if (dataCount >= 1) {
            for (let gateway of data) {
              GATEWAY_ID = gateway["gateway_id"];
              await connection.query(
                "SELECT DISTINCT device_id FROM fft_basevalue where gateway_id ='" +
                  GATEWAY_ID +
                  "' AND action = 0 AND is_deleted =0",
                async function (err, res) {
                  if (err) {
                    console.log(err);
                  } else {
                    var coin_data = res.rows;
                    var coin_count = res.rows.length;
                    if (coin_count >= 1) {
                      for (let coin of coin_data) {
                        DEVICE_ID = coin["device_id"];                      
                        await getAxisWise();
                      }
                    } else {
                      getData();
                    }
                  }
                }
              );
            }
          } else {
            getData();
          }
        }
      }
    );
  } catch (error) {
    throw error;
  }
}

function getAxisWise() {
  try {
    const selectDATAQuery =
      "SELECT DISTINCT device_type FROM fft_basevalue where gateway_id ='" +
      GATEWAY_ID +
      "' AND device_id = '" +
      DEVICE_ID +
      "' AND action = 0 AND is_deleted =0 AND device_type !='28'";
    connection.query(selectDATAQuery, async function (err1, res2) {
      if (err1) {
        console.log(err1);
      } else {
        var d_type_data = res2.rows;
        var d_type_count = res2.rows.length;
        if (d_type_count >= 1) {
          for (let device_type of d_type_data) {
            
             await getProcessingData(device_type["device_type"]);
          }
       
        } else {
          getData();
          
        }
      }
    });
  } catch (error) {
    throw error;
  }
}

function getProcessingData(device_type) {
  
    return new Promise((resolve, reject) => {
  DEVICE_TYPE = device_type;
    // console.log("DEVICE_TYPE :" + DEVICE_TYPE);

  const selectDATAQuery =
    "SELECT * FROM fft_basevalue where gateway_id ='" +
    GATEWAY_ID +
    "' AND device_id = '" +
    DEVICE_ID +
    "' AND action = 0 AND is_deleted =0 AND device_type='" +
    DEVICE_TYPE +
    "' ORDER BY ffb_id ASC LIMIT 1024";
  try {
    connection.query(selectDATAQuery, async function (err1, res2) {
      if (err1) {
        console.log(err1);
      } else {
        var acc_data = res2.rows;
        var acc_count = res2.rows.length;
        console.log(acc_count);
        if (acc_count >= 1024) {
          await process15MinData(acc_data);
          resolve();
        } else {
          console.log("Data is less than 256");
          // getData();
        }
      }
    });
  } catch (error) {
    throw error;
  }
  
  });
}


 async function process15MinData(data) {
  var signal = [];
  var samplingRate;
   data.forEach( (value, index) => {
    var cart = {};
    var ids;
    var device_value = value["device_value"];
    if (index === 0) {
      RECEVIED_ON = value["updated_on"];
    }
    ids = value["ffb_id"].toString();
    samplingRate =  getFrequencyValue(value["frequency_level"]);

    cart = device_value;
    signal.push(cart);
    ID_ARR.push(ids);
  });

  var signalCount_org = signal.length;
  var nearestPow =  nearestPow2(signalCount_org);
  signalDiffCount = signalCount_org - nearestPow;
  signalCount = signalCount_org - signalDiffCount;

  signal = signal.slice(signalDiffCount, signalCount_org);

  var bufferSize = nearestPow;
  await _FFT(signal, bufferSize, samplingRate);
}
 async function _FFT(signal, bufferSize, samplingRate) {
  /* Calculate the Average of an signal input values */
  var totalSum = 0;

  var signal = signal.map(Number);
  for (var i in signal) {
    totalSum += signal[i];
  }
  var signalCnt = signal.length;
  var average = totalSum / signalCnt;

   substractfrominput(signal, average);

  var fft = new FFT(bufferSize, samplingRate);

  fft.forward(newSignal);
  var spectrum = fft.spectrum;
  //   console.log(spectrum, samplingRate);
   await plotFFT(spectrum, samplingRate);
}

 async function plotFFT(spectrum, samplingRate) {
  var data1 = [];
  var frequencyIncrement = parseFloat(samplingRate) / 2 / spectrum.length;

  for (var j = 0; j < spectrum.length; j++) {
    data1.push([frequencyIncrement * j, spectrum[j]]);
    var val1 = frequencyIncrement * j;
    spectrum[j] = Number(spectrum[j].toFixed(4));

     await insertFFTData(val1, spectrum[j], samplingRate);
  }
  const queryText =
    "UPDATE fft_basevalue SET action = 7 WHERE ffb_id IN (" +
    ID_ARR +
    ") AND is_deleted =0";

  connection.query(queryText,  (err, res) => {
    if (err) {
      console.log(err.stack);
    } else {
      if (res) {
        console.log("Action Updated");
         getData();
      }
    }
  });
  // console.log("ABC " + data1);
}
function getFrequencyValue(frequencyVal) {
  if (frequencyVal == "06") {
    samplingRate = "416";
  } else if (frequencyVal == "02") {
    samplingRate = "26";
  } else if (frequencyVal == "03") {
    samplingRate = "52";
  } else if (frequencyVal == "04") {
    samplingRate = "104";
  } else if (frequencyVal == "05") {
    samplingRate = "208";
  } else if (frequencyVal == "07") {
    samplingRate = "833";
  } else if (frequencyVal == "08") {
    samplingRate = "1660";
  } else if (frequencyVal == "09") {
    samplingRate = "3330";
  } else if (frequencyVal == "0A") {
    samplingRate = "6660";
  }
  return samplingRate;
}
function nearestPow2(signalCount) {
  var nearestPow = 1 << (31 - Math.clz32(signalCount));
  return nearestPow;
}
function substractfrominput(signal, average) {
  newSignal = [];
  var substractedInput = 0;
  for (var i in signal) {
    substractedInput = signal[i] - average;
    newSignal.push(substractedInput);
  }
  return substractedInput;
}

 function insertFFTData(spectrum_val, fft_value, frequency) {
  var received_on = moment(RECEVIED_ON).format("YYYY-MM-DD HH:mm:ss");
  var updated_on = moment().format("YYYY-MM-DD HH:mm:ss");

  const insertDevices =
    "INSERT INTO fft_value_api(frequency_value, fft_value, gateway_id,device_id,received_on,frequency,updated_on,device_type) VALUES('" +
    spectrum_val +
    "','" +
    fft_value +
    "','" +
    GATEWAY_ID +
    "','" +
    DEVICE_ID +
    "','" +
    received_on +
    "','" +
    frequency +
    "','" +
    updated_on +
    "','" +
    DEVICE_TYPE +
    "')";

  connection.query(insertDevices, (err, res) => {
    if (err) {
      console.log(err);
    } else {
      if (res) {
        console.log("Done Inserted "+DEVICE_TYPE);
      }
    }
  });
}
// module.exports = router;
