
basePathUser = getBasePathUser();

basePathApp = getBasePathApp();


var apiUrlGateways = "gateways";
var apiUrlDevices = "devices";
var apiUrlSetThreshold = "threshold";
var apiUrlGetCurrentValue = "get-currentvalue";

var apiUrlGetGatewaySettings = "gateway-settings";

var apiUrlUpdateSMSNotification = 'sms-notification';
var apiUrlUpdateEmailNotification = 'email-notification';

var apiUrlNotificationEmailIds = 'notification-email-ids';

var apiUrlNotificationPhone = 'notification-phone';

var apiUrlSetStream = 'rate_value';

var apiUrlAddLocation = 'create-location';

var apiUrlDeleteUserLocation = 'delete-user-location';

var apiUrlGetCoin = 'get-coin';

var apiUrlLocationLatLong = 'get-user-location-lat-long';

var apiUrlAddGetCoin = 'add-get-coin';

var apiUrlGetLocation = 'get-location';

var apiUrlGetGatewayLocation = 'get-gateway-location';

var urlAddLocationCoin = 'a.php';

var apiRenderAlert = 'render-alert';

var apiUrlAddCoin = 'add-coin';

var apiUrlGetCoin = 'get-coin';

var apiUrlEventAddLog = 'event-add-log';
var apiUrlGetEventLogs = 'get-event-logs';

var apiUrlGetDeviceSettings = "device-settings";

var subscribersArr = [];

var apiUpdateRequestAction = 'update-request-action';

var apiUrlAccStreamDevices = 'get-acc-stream-devices';
var apiUrlPredStreamDevices = 'get-pred-stream-devices';

var apiUrlGetGeneralSettings = 'get-general-settings';
var apiUpdateGeneralSettings = 'update-general-settings';

var apiUrlSetDetection = 'set-detection';

var apiUrlDevicesLowBattery = 'devices-low-battery';

var apiGatewayDetail = "gateway-detail";

var autohandle1 = null;

var predective_m = false;

var predective_deviceID;


function getBasePathUser() {

    var baseurl = window.location.origin;//http://sensegiz.com

    var basePath = baseurl + "/sensegiz-dev/user/";//Staging or Dev

    return basePath;
}

function getBasePathApp() {

    var baseurl = window.location.origin;//http://sensegiz.com

    var basePath = baseurl + "/app/";//Staging or Dev

    return basePath;

}
function getBaseIPUser() {
    var baseurl = window.location.host;
    //   var baseurl = window.location.origin;
    var basePath = baseurl;

    return basePath;
}

function getBaseAddressUser() {
    // var baseurl  = window.location.host;
    var baseurl = window.location.origin;
    var basePath = baseurl;

    return basePath;
}

/*
 * Function 			: getGateways()
 * Brief 			: load the list of Gateways	 
 * Input param 			: Nil
 * Input/output param           : NA
 * Return			: NA
 */
function getGateways() {
    $('#loader').show();

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    var date_format = $('#sesval').data('date_format');

    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlGateways,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                $('#loader').hide();

                var sc_html = '';
                var gw_li_html = '';
                var status_html = '';
                if (data.records.length > 0) {
                    records = data.records;
                    $.each(records, function (index, value) {
                        gateway_id = value.gateway_id;
                        status = value.status;

                        // var res_gw_nickname = getGateway_nickname(gateway_id, uid, apikey);
                        var res_gw_nickname = value.gateway_nick_name;
                        // var gateway_name;
                        if (res_gw_nickname == '' || res_gw_nickname == null || res_gw_nickname == 'null') {
                            gateway_name = gateway_id;
                        } else {
                            gateway_name = res_gw_nickname;
                        }

                        var date = '';
                        if (value.updated_on != '') {
                            //                               date      =  value.added_on +'Z'; 
                            date = value.updated_on;
                            var stillUtc = moment.utc(date).toDate();
                            date = moment(stillUtc).local().format(date_format);
                        }

                        if (status == 'Online') {
                            status_html = '&nbsp&nbsp&nbsp<img src="../img/online.png"/>';
                        } else {
                            status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';

                        }
                        sc_html = '<p>Click on any Gateway to view dashboard</p>';


                        gw_li_html += '<li><a href="javascript:;" class="eachGateway" data-gateway="' + gateway_id + '">' + gateway_name + '' + status_html + '</a>  <span class="date-new">Last updated:</span><span class="date-new" data-localtime3434-format="d-M-yyyy HH:mm:ss">' + date + '</span> </li>';    //gateway_id
                    });
                }
                else {
                    sc_html = '<tr><td width="300">No Gateways Found</td></tr>';
                }

                if (data.records.length == 1) {
                    // sc_html = '';

                }

                $('.showclicktext').html(sc_html);

                $('.gateway-list-lfnav').html(gw_li_html);


                if (data.records.length == 1) {
                    // $('.eachGateway').click();

                }

                setTimeout(() => {
                    loadSubscriber(uid.toString());
                }, 1000);

            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);

                }
            }
        });
    }
}

/*
 * Function 			: getDevices(gatewayId)
 * Brief 			: load the list of Devices for a gateway	 
 * Input param 			: gatewayId
 * Input/output param           : NA
 * Return			: NA
 */
function getDevices(gatewayId, coins, sensors) {
    $('#loader').show();
    //Remove sticky notfn box


    //new
    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlDevices + '/' + gatewayId + '/' + coins + '/' + sensors,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                $('#loader').hide();
                var sc_html = '';
                var acc_html = '';
                var gyro_html = '';
                var temp_html = '';
                var hum_html = '';
                var stream_html = '';
                var other_html = '';
                var acc_stream_html = '';
                var tempstream_html = '';
                var humidstream_html = '';

                records = data.records;
                var accelerometer = records.accelerometer;
                var gyrosensor = records.gyrosensor;
                var temperature = records.temperature;
                var humidity = records.humidity;
                var stream = records.stream;
                var other = records.other;
                var accStream = records.accStream;
                var tempstream = records.tempstream;
                var humidstream = records.humidstream;

                $.ajax({
                    url: basePathUser + apiUrlGetDeviceSettings + '/' + gatewayId,
                    headers: {
                        'uid': uid,
                        'Api-Key': apikey
                    },
                    type: 'GET',
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    async: false,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("uid", uid);
                        xhr.setRequestHeader("Api-Key", apikey);
                    },
                    success: function (data, textStatus, xhr) {
                        records = data.records;
                        var acc_settings = [];
                        var gyr_settings = [];
                        var temp_settings = [];
                        var humid_settings = [];
                        var stream_settings = [];
                        var generalsettings = '';

                        var acc = '';
                        var gyro = ''; var temp = ''; var humid = ''; var strm = '';
                        var tempstrm = ''; var humidstrm = '';

                        $.each(records, function (index, value) {
                            var device_id = value.device_id;
                            var settings = value.settings;
                            generalsettings = value.generalsettings;


                            if (settings.length > 0) {
                                $.each(settings, function (indexSett, valueSett) {

                                    var sensor_type = valueSett.device_sensor;
                                    var sensor_active = valueSett.sensor_active;


                                    if (sensor_type == "Accelerometer") {
                                        acc_settings.push(valueSett);

                                    }
                                    if (sensor_type == "Gyroscope") {
                                        gyr_settings.push(valueSett);

                                    }
                                    if (sensor_type == "Temperature") {
                                        temp_settings.push(valueSett);

                                    }
                                    if (sensor_type == "Humidity") {
                                        humid_settings.push(valueSett);

                                    }
                                    if (sensor_type == "Temperature Stream") {
                                        stream_settings.push(valueSett);

                                    }
                                    if (sensor_type == "Humidity Stream") {
                                        stream_settings.push(valueSett);

                                    }


                                });
                            }
                        });

                        if (generalsettings.length > 0) {
                            $.each(generalsettings, function (indexSett, valueSett) {

                                acc = valueSett.accelerometer;
                                gyro = valueSett.gyroscope;
                                temp = valueSett.temperature;
                                humid = valueSett.humidity;
                                strm = valueSett.stream;
                                accstream = valueSett.accelerometerstream;
                                tempstrm = valueSett.temperaturestream;
                                humidstrm = valueSett.humiditystream;

                            });
                        }

                        if (accelerometer.length > 0 && acc == 'Y') {
                            acc_html = getFormattedDevicesData(accelerometer, 'Accelerometer', acc_settings, 0);
                        }
                        if (gyrosensor.length > 0 && gyro == 'Y') {
                            gyro_html = getFormattedDevicesData(gyrosensor, 'Gyroscope', gyr_settings, 0);
                        }

                        if (temperature.length > 0 && temp == 'Y') {
                            temp_html = getFormattedDevicesData(temperature, 'Temperature', temp_settings, 0);
                        }

                        if (humidity.length > 0 && humid == 'Y') {
                            hum_html = getFormattedDevicesData(humidity, 'Humidity', humid_settings, 0);
                        }

                        /*if(stream.length>0 && strm == 'Y'){
                                stream_html = getFormattedDevicesData(stream, 'Stream', stream_settings , 1);
                                 }*/
                        if (tempstream.length > 0 && tempstrm == 'Y') {
                            tempstream_html = getFormattedDevicesData(tempstream, 'Temperature Stream', stream_settings, 1);
                        }
                        if (humidstream.length > 0 && humidstrm == 'Y') {
                            humidstream_html = getFormattedDevicesData(humidstream, 'Humidity Stream', stream_settings, 2);
                        }

                        if (accStream.length > 0) {
                            acc_stream_html = getFormattedDevicesData(accStream, 'Accelerometer Stream', 0, 2);
                        }

                        if (accelerometer.length == 0 && gyrosensor.length == 0 && temperature.length == 0 && humidity.length == 0 && stream.length == 0 && other.length != 0 && accStream.length != 0) {
                            sc_html = '<h3>No data found</h3>';
                        }

                        info_html = '<p style="color:green;text-align: right;"> <i> For details on how to set the thresholds, please refer the Guide section. </i> </p>';

                        sc_html += '' + acc_html + '' + gyro_html + '' + temp_html + '' + hum_html + '' + tempstream_html + '' + humidstream_html;
                        if (sc_html != '') {
                            //sc_html += '<p style="color:red;"> <i> *Accelerometer Stream is a customized feature and might not be available for your device. Please contact SenseGiz representative for more details (support@sensegiz.com). </i> </p>';
                            sc_html = '' + info_html + '' + sc_html;

                        }
                        $('.devicesTables').html(sc_html);

                        loadSubscriber(uid.toString());

                        moment().format();


                    },
                    error: function (errData, status, error) {
                        if (errData.status == 401) {
                            var resp = errData.responseJSON;
                            var description = resp.description;
                            $('.logout').click();
                            alert(description);
                        }
                    }
                });






            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);
                }
            }
        });
    }
}



//format and return data
function getFormattedDevicesData(dataArr, tableName, dev_settings, diff) {

    var deviceIds = [];
    var senDeviceIds = [];
    var date_format = $('#sesval').data('date_format');
    var temp_unit = $('#sesval').data('temp_unit');

    if (tableName == 'Accelerometer') { var cbutton = 'p1'; var unit = 'g'; }
    if (tableName == 'Gyroscope') { var cbutton = 'p3'; var unit = 'DPS'; }
    if (tableName == 'Temperature') { var cbutton = 'p5'; var unit = temp_unit; }
    if (tableName == 'Humidity') { var cbutton = 'p7'; var unit = '%RH'; }



    if (dev_settings != 0) {
        $.each(dev_settings, function (sindex, svalue) {
            senDeviceIds.push(svalue.device_id);

        });
    }



    if (diff == 0) {

        var data_html = '<button class="accordion ' + cbutton + '">' + tableName + ' (' + unit + ')</button><div class="panel d' + cbutton + '"><div class="table-responsive"><table class="table table-hover table-bordered table-lp">'
            + '    <tr class="active tb-hd">'
            + '        <th>Sl. No</th>'
            + '        <th>Nick Name</th>'
            + '        <th>Location</th>'
            + '        <th>Threshold</th>'
            + '        <th>Value</th>'
            + '        <th>Get Current Value</th>'
            + '        <th>Last Updated</th>'
            + '        <th>Set Threshold</th>'
            + '    </tr>'
            + '    <tbody class="users gateways" style="text-align:center;">';

        $.each(dataArr, function (index, value) {

            var device_id = value.device_id;
            var sensor_status = value.sensor_status;


            var gateway_id = value.gateway_id;
            var active = value.active;

            if (active == 'Y') {
                if (sensor_status == 'Y') {
                    coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/online.png"/>';
                } else {
                    coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';
                }
            } else {
                coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';
            }

            var device_type = value.device_type;
            var device_value = value.device_value;

            var nick_name = value.nick_name;

            var coin_location = value.coin_location;
            if (coin_location == null || coin_location == 'null' || coin_location == '') {
                coin_location = '';
            }

            var current_value = value.get_current_value;
            var d_id = value.d_id;
            var dis_dev_type = '';
            var threshold_value = value.threshold_value;
            var realHexThreshold = value.threshold_value;
            if ((device_type == '05' || device_type == '06') && temp_unit == 'Fahrenheit') {
                if (device_value != null && device_value != 'null' && device_value != '') {
                    device_value = (device_value * 1.8) + 32;
                    device_value = parseFloat(device_value).toFixed(3);
                }


            }

            //convert hex val to dec
            if (device_type == null || device_type == 'null' || device_type == '') {
                dis_dev_type = 'No data';
            }

            if (device_type == '01' || device_type == '03' || device_type == '05' || device_type == '07') {
                dis_dev_type = 'low';
            } else
                if (device_type == '02' || device_type == '04' || device_type == '06' || device_type == '08') {
                    dis_dev_type = 'high';
                } else
                    if (device_type == '09') {
                        dis_dev_type = 'temperature';
                    } else
                        if (device_type == '10') {
                            dis_dev_type = 'humidity';
                        } else
                            if (device_type == '12') {
                                dis_dev_type = 'accelerometer';
                            }
                            else {
                                dis_dev_type = device_type;
                            }


            //convert hex val to dec
            if (threshold_value != null && threshold_value != '') {
                console.log("_________threshold_value______________", threshold_value);

                var thresholdJsonData = {
                    "CA": "2",
                    "CB": "3",
                    "CC": "4",
                    "CD": "5",
                    "CF": "6",
                    "CF": "7",
                    "D0": "8",
                    "D1": "9",
                };

                if (thresholdJsonData.hasOwnProperty(realHexThreshold)) {
                    // Access the value associated with the key "age"
                    threshold_value = thresholdJsonData[threshold_value];
                    console.log("The threshold_value is: " + threshold_value);
                } else {

                    threshold_value = hexToDec(threshold_value);
                    console.log("The threshold_value does not exist in the JSON data.", threshold_value);
                }
                if (device_type == '01' || device_type == '02') {
                    if (threshold_value == 1)
                        threshold_value = 0.001;
                    else if (threshold_value == 2)
                        threshold_value = 0.1;
                    else
                        threshold_value = threshold_value / 8;
                }
                if (device_type == '03' || device_type == '04') {
                    if (thresholdJsonData.hasOwnProperty(realHexThreshold)) {

                    } else {
                        threshold_value = threshold_value * 10;
                    }
                }
                if (device_type == '05' || device_type == '06') {
                    if (threshold_value > 126) {
                        threshold_value = threshold_value - 126;
                        threshold_value = -threshold_value;
                    }
                    if (threshold_value == 126) {
                        threshold_value = 0;
                    }

                    if (temp_unit == 'Fahrenheit') {
                        threshold_value = (threshold_value * 1.8) + 32;
                    }
                }


            }
            else {
                threshold_value = '';
            }

            var rate_value = value.rate_value;
            var format = value.format;

            //convert hex val to dec


            if (rate_value != null && rate_value != '') {
                rate_value = rate_value;
            } else {
                rate_value = '';
            }

            var date = '';
            if (value.added_on != '' && value.added_on != null) {
                date = value.added_on;
                var stillUtc = moment.utc(date).toDate();
                date = moment(stillUtc).local().format(date_format);
            }

            var last_updated_on = '';
            if (value.updated_on != '' && value.added_on != null) {
                last_updated_on = value.updated_on;
                var stillUtc = moment.utc(last_updated_on).toDate();
                last_updated_on = moment(stillUtc).local().format(date_format);
                var now = moment().format(date_format);
                //var duration  = moment.duration(now.diff(last_updated_on));
                //var hours = duration.asHours();

                //var diff = now.diff(now, last_updated_on);

                var ms = moment(now, "DD-MM-YYYY HH:mm:ss").diff(moment(last_updated_on, "DD-MM-YYYY HH:mm:ss"));
                var d = moment.duration(ms);
                var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");

            }
            var a = s.split(':');
            var hour = a[0];
            var minute = a[1];
            var second = a[2];

            data_html += '<td>' + (index + 1) + '</td>'
                + '<td>' + nick_name + '' + coin_status_html + '</a></td>'
                + '<td>' + coin_location + ' </a></td>'
                + '<td>' + dis_dev_type + '</td>'
                + '<td>' + device_value + '</td>';


            if (d_id != null && d_id != '') {
                if (jQuery.inArray(device_id, senDeviceIds) != '-1') {
                    $.each(dev_settings, function (sindex, svalue) {
                        var sen_dev_id = svalue.device_id;
                        var sen_active = svalue.sensor_active;

                        if (device_id == sen_dev_id && sen_active == 'Y') {
                            if (device_type == '01' || device_type == '02') {
                                data_html += '<td><button class="getCurrentValue" type="button" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">GET</button><span class="done grn"></span></td></td>';
                                data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';
                                accVal = getAccelerometerValues(threshold_value);
                                data_html += '<td><select class="accVal' + device_id + device_type + ' ">';
                                data_html += accVal;
                                data_html += '</select><button class="setTh" type="button"  data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                            }
                            else if (device_type == '03' || device_type == '04') {
                                data_html += '<td><button class="getCurrentValue" type="button" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">GET</button><span class="done grn"></span></td></td>';
                                data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';
                                data_html += '<td><input type="text" onkeypress="return validateNumber(event);" value="' + threshold_value + '" class="thinpt" maxlength="4"/><button class="setTh" type="button" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                            }
                            else if (device_type == '05' || device_type == '06') {
                                data_html += '<td><button class="getCurrentValue" type="button" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">GET</button><span class="done grn"></span></td></td>';
                                data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';
                                data_html += '<td><input type="text" onkeypress="return validateNegativeNumber(this, event);" value="' + threshold_value + '" class="thinpt" maxlength="5"/><button class="setTh" type="button" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
                            }
                            else if (device_type == '07' || device_type == '08') {
                                data_html += '<td><button class="getCurrentValue" type="button" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">GET</button><span class="done grn"></span></td></td>';
                                data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';
                                data_html += '<td><input type="text" onkeypress="return validateNumber(event);" value="' + threshold_value + '" class="thinpt" maxlength="3"/><button class="setTh" type="button" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
                            }

                        }
                        if (device_id == sen_dev_id && sen_active == 'N') {
                            if (device_type == '01' || device_type == '02') {
                                data_html += '<td><button class="getCurrentValue" type="button" disabled style="background-color: #757E79;" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">GET</button><span class="done grn"></span></td></td>';
                                data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';
                                accVal = getAccelerometerValues(threshold_value);
                                data_html += '<td><select class="accVal' + device_id + device_type + ' ">';
                                data_html += accVal;
                                data_html += '</select><button class="setTh" type="button" disabled style="background-color: #757E79;"  data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                            }
                            else if (device_type == '03' || device_type == '04') {
                                data_html += '<td><button class="getCurrentValue" type="button" disabled style="background-color: #757E79;" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">GET</button><span class="done grn"></span></td></td>';
                                data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';
                                data_html += '<td><input type="text" onkeypress="return validateNumber(event);" value="' + threshold_value + '" class="thinpt" maxlength="4"/><button class="setTh" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                            }
                            else if (device_type == '05' || device_type == '06') {
                                data_html += '<td><button class="getCurrentValue" type="button" disabled style="background-color: #757E79;" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">GET</button><span class="done grn"></span></td></td>';
                                data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';
                                data_html += '<td><input type="text" onkeypress="return validateNegativeNumber(this, event);" value="' + threshold_value + '" class="thinpt" maxlength="5"/><button class="setTh" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
                            }
                            else if (device_type == '07' || device_type == '08') {
                                data_html += '<td><button class="getCurrentValue" type="button" disabled style="background-color: #757E79;" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">GET</button><span class="done grn"></span></td></td>';
                                data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';
                                data_html += '<td><input type="text" onkeypress="return validateNumber(event);" value="' + threshold_value + '" class="thinpt" maxlength="3"/><button class="setTh" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
                            }

                        }
                    });


                } else {
                    if (device_type == '01' || device_type == '02') {
                        data_html += '<td><button class="getCurrentValue" type="button" disabled style="background-color: #757E79;" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">GET</button><span class="done grn"></span></td></td>';
                        data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';
                        accVal = getAccelerometerValues(threshold_value);
                        data_html += '<td><select class="accVal' + device_id + device_type + ' ">';
                        data_html += accVal;
                        data_html += '</select><button class="setTh" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                    }
                    if (device_type == '03' || device_type == '04') {
                        data_html += '<td><button class="getCurrentValue" type="button" disabled style="background-color: #757E79;" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">GET</button><span class="done grn"></span></td></td>';
                        data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';
                        data_html += '<td><input type="text" onkeypress="return validateNumber(event);" value="' + threshold_value + '" class="thinpt" maxlength="4"/><button class="setTh" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                    }
                    if (device_type == '05' || device_type == '06') {
                        data_html += '<td><button class="getCurrentValue" type="button" disabled style="background-color: #757E79;" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">GET</button><span class="done grn"></span></td></td>';
                        data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';
                        data_html += '<td><input type="text" onkeypress="return validateNegativeNumber(this, event);" value="' + threshold_value + '" class="thinpt" maxlength="5"/><button class="setTh" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
                    }
                    if (device_type == '07' || device_type == '08') {
                        data_html += '<td><button class="getCurrentValue" type="button" disabled style="background-color: #757E79;" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">GET</button><span class="done grn"></span></td></td>';
                        data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';
                        data_html += '<td><input type="text" onkeypress="return validateNumber(event);" value="' + threshold_value + '" class="thinpt" maxlength="3"/><button class="setTh" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
                    }

                }

            }
            else {
                data_html += '<td></td>';
                data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';
                data_html += '<td></td>';
            }

            data_html += '</tr>';



        });


        data_html += '</tbody>'
            + '</table></div></div>';

        return data_html;


    } else if (diff == 1) {


        var data_html = '<button class="accordion p9">' + tableName + ' (' + temp_unit + ')</button><div class="panel dp9"><div class="table-responsive"><table class="table table-hover table-bordered table-lp">'
            + '    <tr class="active tb-hd">'
            + '        <th>Sl. No</th>'
            + '        <th>Nick Name</th>'
            + '        <th>Location</th>'
            + '        <th>Sensor Name</th>'
            + '        <th>Value</th>'
            + '        <th>Last Updated</th>'
            + '	  <th>Set Stream</th>'
            + '    </tr>'
            + '    <tbody class="users gateways" style="text-align:center;">';
        $.each(dataArr, function (index, value) {
            var device_id = value.device_id;
            var gateway_id = value.gateway_id;
            var device_type = value.device_type;
            var device_value = value.device_value;

            var nick_name = value.nick_name;

            var coin_location = value.coin_location;
            if (coin_location == null || coin_location == 'null' || coin_location == '') {
                coin_location = '';
            }

            var current_value = value.get_current_value;

            var coin_status_html = '';
            var active = value.active;
            var temp_stream_sensor_status = value.temp_stream_sensor_status;
            // var humid_stream_sensor_status = value.humid_stream_sensor_status;

            if (active == 'Y') {
                if (temp_stream_sensor_status == 'Y') {
                    coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/online.png"/>';
                } else {
                    coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';
                }
            } else {
                coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';
            }


            var d_id = value.d_id;
            var dis_dev_type = '';

            if (device_type == '09' && temp_unit == 'Fahrenheit') {
                if (device_value != null && device_value != 'null' && device_value != '') {
                    device_value = (device_value * 1.8) + 32;
                    device_value = parseFloat(device_value).toFixed(3);
                }

            }

            //convert hex val to dec
            if (device_type == null || device_type == 'null' || device_type == '') {
                dis_dev_type = 'No data';
            }

            if (device_type == '01' || device_type == '03' || device_type == '05' || device_type == '07') {
                dis_dev_type = 'low';
            } else
                if (device_type == '02' || device_type == '04' || device_type == '06' || device_type == '08') {
                    dis_dev_type = 'high';
                } else
                    if (device_type == '09') {
                        dis_dev_type = 'temperature';
                    } else
                        if (device_type == '10') {
                            dis_dev_type = 'humidity';
                        } else
                            if (device_type == '12') {
                                dis_dev_type = 'accelerometer';
                            }
                            else {
                                dis_dev_type = device_type;
                            }

            var threshold_value = value.threshold_value;
            //convert hex val to dec
            if (threshold_value != null && threshold_value != '') {
                threshold_value = hexToDec(threshold_value);
            }
            else {
                threshold_value = '';
            }

            var rate_value = value.rate_value;
            var format = value.format;
            format = hexToDec(format);

            if (format == 21) {
                var dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="21" selected>Seconds</option><option value="22">Minutes</option><option value="33">Hours</option></select>';
            }
            else if (format == 22) {
                var dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="21">Seconds</option><option value="22" selected>Minutes</option><option value="33">Hours</option></select>';
            }
            else if (format == 33) {
                var dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="21">Seconds</option><option value="22">Minutes</option><option value="33" selected>Hours</option></select>';
            }
            else {
                var dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="21" selected>Seconds</option><option value="22">Minutes</option><option value="33">Hours</option></select>';
            }

            //convert hex val to dec

            if (rate_value != null && rate_value != '') {
                rate_value = hexToDec(rate_value);
            } else {
                rate_value = '';
            }

            var date = '';
            if (value.added_on != '' && value.added_on != null) {
                date = value.added_on;
                var stillUtc = moment.utc(date).toDate();
                date = moment(stillUtc).local().format(date_format);
            }

            var last_updated_on = '';
            if (value.updated_on != '' && value.added_on != null) {
                last_updated_on = value.updated_on;
                var stillUtc = moment.utc(last_updated_on).toDate();
                last_updated_on = moment(stillUtc).local().format(date_format);
                var now = moment().format(date_format);
                //var duration  = moment.duration(now.diff(last_updated_on));
                //var hours = duration.asHours();

                //var diff = now.diff(now, last_updated_on);

                var ms = moment(now, "DD-MM-YYYY HH:mm:ss").diff(moment(last_updated_on, "DD-MM-YYYY HH:mm:ss"));
                var d = moment.duration(ms);
                var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");

            }
            var a = s.split(':');
            var hour = a[0];
            var minute = a[1];
            var second = a[2];
            data_html += '<td>' + (index + 1) + '</td>'
                + '<td>' + nick_name + ' ' + coin_status_html + '</a></td>'
                + '<td>' + coin_location + ' </a></td>'
                + '<td>' + dis_dev_type + '</td>'
                + '<td>' + device_value + '</td>';

            data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';

            if (d_id != null && d_id != '' && dis_dev_type == 'temperature' || dis_dev_type == 'humidity') {

                if (jQuery.inArray(device_id, senDeviceIds) != '-1') {




                    $.each(dev_settings, function (sindex, svalue) {
                        var sen_dev_id = svalue.device_id;
                        var sen_active = svalue.sensor_active;
                        var sensor_type = svalue.device_sensor;

                        var occur = $.grep(senDeviceIds, function (elem) {
                            return elem == sen_dev_id;
                        }).length;




                        if (occur > 1) {
                            if (sensor_type == 'Temperature Stream' && dis_dev_type == 'temperature') {
                                if (device_id == sen_dev_id && sen_active == 'Y') {

                                    data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                                }
                                else if (device_id == sen_dev_id && sen_active == 'N') {

                                    data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
                                }



                            }
                            else if (sensor_type == 'Humidity Stream' && dis_dev_type == 'humidity') {
                                if (device_id == sen_dev_id && sen_active == 'Y') {

                                    data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';


                                }
                                else if (device_id == sen_dev_id && sen_active == 'N') {

                                    data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
                                }

                            }

                        } else {
                            if (device_id == sen_dev_id) {
                                if (sensor_type == 'Temperature Stream' && dis_dev_type == 'temperature') {
                                    if (sen_active == 'Y') {
                                        data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                                    }
                                    else {
                                        data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                                    }
                                }
                                else if (sensor_type == 'Humidity Stream' && dis_dev_type == 'humidity') {
                                    if (sen_active == 'Y') {
                                        data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                                    }
                                    else {
                                        data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                                    }

                                }
                                else {
                                    data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                                }

                            }

                        }
                    });


                }
                else {

                    data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
                }



            } else {
                data_html += '<td></td>';
            }
            data_html += '</tr>';
        });

        data_html += '</tbody>'
            + '</table></div></div>';

        return data_html;


    } else if (diff == 2) {


        var data_html = '<button class="accordion p10">' + tableName + ' (%RH)</button><div class="panel dp10"><div class="table-responsive"><table class="table table-hover table-bordered table-lp">'
            + '    <tr class="active tb-hd">'
            + '        <th>Sl. No</th>'
            + '        <th>Nick Name</th>'
            + '        <th>Location</th>'
            + '        <th>Sensor Name</th>'
            + '        <th>Value</th>'
            + '        <th>Last Updated</th>'
            + '	  <th>Set Stream</th>'
            + '    </tr>'
            + '    <tbody class="users gateways" style="text-align:center;">';
        $.each(dataArr, function (index, value) {
            var device_id = value.device_id;
            var gateway_id = value.gateway_id;
            var device_type = value.device_type;
            var device_value = value.device_value;

            var nick_name = value.nick_name;

            var coin_location = value.coin_location;
            if (coin_location == null || coin_location == 'null' || coin_location == '') {
                coin_location = '';
            }

            var current_value = value.get_current_value;
            // debugger;
            var coin_status_html = '';
            var active = value.active;
            // var temp_stream_sensor_status = value.temp_stream_sensor_status;
            var humid_stream_sensor_status = value.humid_stream_sensor_status;

            if (active == 'Y') {
                if (humid_stream_sensor_status == 'Y') {
                    coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/online.png"/>';
                } else {
                    coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';
                }
            } else {
                coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';
            }

            var d_id = value.d_id;
            var dis_dev_type = '';

            if (device_type == '09' && temp_unit == 'Fahrenheit') {
                if (device_value != null && device_value != 'null' && device_value != '') {
                    device_value = (device_value * 1.8) + 32;
                    device_value = parseFloat(device_value).toFixed(3);
                }

            }

            //convert hex val to dec
            if (device_type == null || device_type == 'null' || device_type == '') {
                dis_dev_type = 'No data';
            }

            if (device_type == '01' || device_type == '03' || device_type == '05' || device_type == '07') {
                dis_dev_type = 'low';
            } else
                if (device_type == '02' || device_type == '04' || device_type == '06' || device_type == '08') {
                    dis_dev_type = 'high';
                } else
                    if (device_type == '09') {
                        dis_dev_type = 'temperature';
                    } else
                        if (device_type == '10') {
                            dis_dev_type = 'humidity';
                        } else
                            if (device_type == '12') {
                                dis_dev_type = 'accelerometer';
                            }
                            else {
                                dis_dev_type = device_type;
                            }

            var threshold_value = value.threshold_value;
            //convert hex val to dec
            if (threshold_value != null && threshold_value != '') {
                threshold_value = hexToDec(threshold_value);
            }
            else {
                threshold_value = '';
            }

            var rate_value = value.rate_value;
            var format = value.format;
            format = hexToDec(format);

            if (format == 21) {
                var dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="21" selected>Seconds</option><option value="22">Minutes</option><option value="33">Hours</option></select>';
            }
            else if (format == 22) {
                var dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="21">Seconds</option><option value="22" selected>Minutes</option><option value="33">Hours</option></select>';
            }
            else if (format == 33) {
                var dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="21">Seconds</option><option value="22">Minutes</option><option value="33" selected>Hours</option></select>';
            }
            else {
                var dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="21" selected>Seconds</option><option value="22">Minutes</option><option value="33">Hours</option></select>';
            }

            //convert hex val to dec

            if (rate_value != null && rate_value != '') {
                rate_value = hexToDec(rate_value);
            } else {
                rate_value = '';
            }

            var date = '';
            if (value.added_on != '' && value.added_on != null) {
                date = value.added_on;
                var stillUtc = moment.utc(date).toDate();
                date = moment(stillUtc).local().format(date_format);
            }

            var last_updated_on = '';
            if (value.updated_on != '' && value.added_on != null) {
                last_updated_on = value.updated_on;
                var stillUtc = moment.utc(last_updated_on).toDate();
                last_updated_on = moment(stillUtc).local().format(date_format);
                var now = moment().format(date_format);
                //var duration  = moment.duration(now.diff(last_updated_on));
                //var hours = duration.asHours();

                //var diff = now.diff(now, last_updated_on);

                var ms = moment(now, "DD-MM-YYYY HH:mm:ss").diff(moment(last_updated_on, "DD-MM-YYYY HH:mm:ss"));
                var d = moment.duration(ms);
                var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");

            }
            var a = s.split(':');
            var hour = a[0];
            var minute = a[1];
            var second = a[2];
            data_html += '<td>' + (index + 1) + '</td>'
                + '<td>' + nick_name + ' ' + coin_status_html + '</a></td>'
                + '<td>' + coin_location + ' </a></td>'
                + '<td>' + dis_dev_type + '</td>'
                + '<td>' + device_value + '</td>';

            data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';

            if (d_id != null && d_id != '' && dis_dev_type == 'temperature' || dis_dev_type == 'humidity') {

                if (jQuery.inArray(device_id, senDeviceIds) != '-1') {




                    $.each(dev_settings, function (sindex, svalue) {
                        var sen_dev_id = svalue.device_id;
                        var sen_active = svalue.sensor_active;
                        var sensor_type = svalue.device_sensor;

                        var occur = $.grep(senDeviceIds, function (elem) {
                            return elem == sen_dev_id;
                        }).length;




                        if (occur > 1) {
                            if (sensor_type == 'Temperature Stream' && dis_dev_type == 'temperature') {
                                if (device_id == sen_dev_id && sen_active == 'Y') {

                                    data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                                }
                                else if (device_id == sen_dev_id && sen_active == 'N') {

                                    data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
                                }



                            }
                            else if (sensor_type == 'Humidity Stream' && dis_dev_type == 'humidity') {
                                if (device_id == sen_dev_id && sen_active == 'Y') {

                                    data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';


                                }
                                else if (device_id == sen_dev_id && sen_active == 'N') {

                                    data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
                                }

                            }

                        } else {
                            if (device_id == sen_dev_id) {
                                if (sensor_type == 'Temperature Stream' && dis_dev_type == 'temperature') {
                                    if (sen_active == 'Y') {
                                        data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                                    }
                                    else {
                                        data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                                    }
                                }
                                else if (sensor_type == 'Humidity Stream' && dis_dev_type == 'humidity') {
                                    if (sen_active == 'Y') {
                                        data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                                    }
                                    else {
                                        data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                                    }

                                }
                                else {
                                    data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                                }

                            }

                        }
                    });


                }
                else {

                    data_html += '<td><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
                }



            } else {
                data_html += '<td></td>';
            }
            data_html += '</tr>';
        });

        data_html += '</tbody>'
            + '</table></div></div>';

        return data_html;


    }
    else {
        var data_html = '<div class="table-responsive"><table class="table table-hover table-bordered table-lp">'
            + '   <tr><th colspan="10" style="font-size: 15px;">' + tableName + '</th></tr>'
            + '    <tr class="active tb-hd">'
            + '        <th>Sl. No</th>'
            + '        <th>Nick Name</th>'
            + '        <th>Location</th>'
            + '        <th>Sensor Name</th>'
            + '        <th>Value</th>'
            + '        <th>Last Updated</th>'
            + '    </tr>'
            + '    <tbody class="users gateways" style="text-align:center;">';
        $.each(dataArr, function (index, value) {
            var device_id = value.device_id;
            var gateway_id = value.gateway_id;
            var device_type = value.device_type;
            var device_value = value.device_value;
            var nick_name = value.nick_name;
            var d_id = value.d_id;
            var dis_dev_type = '';

            var coin_location = value.coin_location;
            if (coin_location == null || coin_location == 'null' || coin_location == '') {
                coin_location = '';
            }


            //$dval = hexToDec(device_value) - 65536;

            //var finalvalue = dataconvert($dval);
            //finalvalue = finalvalue * 0.000244;

            if (device_type == null || device_type == '') {
                dis_dev_type = 'No data';
            }

            if (device_type == '12') {
                dis_dev_type = 'Accelerometer X-axis';
            } else
                if (device_type == '14') {
                    dis_dev_type = 'Accelerometer Y-axis';
                } else
                    if (device_type == '15') {
                        dis_dev_type = 'Accelerometer Z-axis';
                    }

            var last_updated_on = '';
            if (value.updated_on != '' && value.added_on != null) {
                last_updated_on = value.updated_on;
                var stillUtc = moment.utc(last_updated_on).toDate();
                last_updated_on = moment(stillUtc).local().format(date_format);
                var now = moment().format(date_format);

                var ms = moment(now, "DD-MM-YYYY HH:mm:ss").diff(moment(last_updated_on, "DD-MM-YYYY HH:mm:ss"));
                var d = moment.duration(ms);
                var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");

            }
            var a = s.split(':');
            var hour = a[0];
            var minute = a[1];
            var second = a[2];
            data_html += '<td>' + (index + 1) + '</td>'
                + '<td>' + nick_name + '</a></td>'
                + '<td>' + coin_location + ' </a></td>'
                + '<td>' + dis_dev_type + '</td>'
                + '<td>' + device_value + '</td>';

            data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';

            data_html += '</tr>';
        });

        data_html += '</tbody>'
            + '</table></div>';

        return data_html;

    }


}


/*
     * Function 			: getAccStreamDevices(gatewayId)
     * Brief 			: load the list of Devices for a gateway	 
     * Input param 			: gatewayId
     * Input/output param           : NA
     * Return			: NA
     */
function getAccStreamDevices(gatewayId, coins) {
    $('#loader').show();
    //Remove sticky notfn box


    //new
    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlAccStreamDevices + '/' + gatewayId + '/' + coins,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                $('#loader').hide();
                var sc_html = '';

                var other_html = '';
                var acc_stream_html = '';

                records = data.records;

                var other = records.other;
                var accStream = records.accStream;

                $.ajax({
                    url: basePathUser + apiUrlGetDeviceSettings + '/' + gatewayId,
                    headers: {
                        'uid': uid,
                        'Api-Key': apikey
                    },
                    type: 'GET',
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    async: false,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("uid", uid);
                        xhr.setRequestHeader("Api-Key", apikey);
                    },
                    success: function (data, textStatus, xhr) {
                        records = data.records;

                        var stream_settings = [];
                        var generalsettings = '';
                        var accstrm = '';
                        var accstrmPM = '';
                        $.each(records, function (index, value) {
                            var device_id = value.device_id;
                            var settings = value.settings;
                            generalsettings = value.generalsettings;
                            var senStatus = 'N';
                            if (settings.length > 0) {
                                $.each(settings, function (indexSett, valueSett) {

                                    var sensor_type = valueSett.device_sensor;
                                    var sensor_active = valueSett.sensor_active;
                                    if (sensor_type == "Accelerometer Stream" && sensor_active == 'Y') {
                                        stream_settings.push(valueSett);
                                        console.log("ACC STREAM")
                                    }/*else if(sensor_type == "Predictive Maintenance" && sensor_active == 'Y'){
                                            if(sensor_active == 'Y'){
                                                predective_m = true;
                                                predective_deviceID = device_id;
                                                stream_settings.push(valueSett);      
                                                accstrmPM = 'Y';
                                            }else{
                                                stream_settings.push(valueSett);
                                            }
                                            
                                        }*/

                                    // if(sensor_type == "Accelerometer Stream"){		
                                    // 	stream_settings.push(valueSett);

                                    // }

                                });
                            }
                        });


                        if (generalsettings.length > 0) {
                            $.each(generalsettings, function (indexSett, valueSett) {

                                accstrm = valueSett.accelerometerstream;

                            });
                        }

                        // console.log("Acc STream");
                        // console.log(accstrm);

                        if (accStream.length > 0 && accstrm == 'Y') {
                            acc_stream_html = getFormattedAccStreamData(accStream, 'Accelerometer Stream', stream_settings);
                            // acc_stream_html += getFormattedSpectrumStreamData(accStream, 'Spectrum Stream', stream_settings);
                        }

                        if (acc_stream_html != '') {
                            acc_stream_html += '<p style="color:red;"> <i> *Accelerometer Stream is a customized feature and might not be available for your device. Please contact SenseGiz representative for more details (support@sensegiz.com). </i> </p>';
                        }
                        $('.accStreamTable').html(acc_stream_html);

                        loadSubscriber(uid.toString());

                        moment().format();


                    },
                    error: function (errData, status, error) {
                        if (errData.status == 401) {
                            var resp = errData.responseJSON;
                            var description = resp.description;
                            $('.logout').click();
                            alert(description);
                        }
                    }
                });

            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);
                }
            }
        });
    }
}


//format and return data
function getFormattedAccStreamData(dataArr, tableName, dev_settings) {
    var date_format = $('#sesval').data('date_format');

    var deviceIds = [];
    var senDeviceIds = [];
    if (dev_settings != 0) {
        $.each(dev_settings, function (sindex, svalue) {
            senDeviceIds.push(svalue.device_id);

        });
    }



    var data_html = '<button class="accordion p12">' + tableName + ' (g)</button><div class="panel dp12"><div class="table-responsive"><table class="table table-hover table-bordered table-lp">'
        + '    <tr class="active tb-hd">'
        + '        <th>Sl. No</th>'
        + '        <th>Nick Name</th>'
        + '        <th>Location</th>'
        + '        <th>Sensor Name</th>'
        + '        <th>Value</th>'
        + '        <th>Last Updated</th>'
        + '        <th>Set Detection Period</th>'
        + '        <th>Set Stream</th>'
        + '    </tr>'
        + '    <tbody class="users gateways" style="text-align:center;">';
    $.each(dataArr, function (index, value) {
        var device_id = value.device_id;
        var gateway_id = value.gateway_id;
        var device_type = value.device_type;
        var device_value = value.device_value;
        var nick_name = value.nick_name;
        var d_id = value.d_id;
        var dis_dev_type = '';

        var coin_location = value.coin_location;
        if (coin_location == null || coin_location == 'null' || coin_location == '') {
            coin_location = '';
        }

        var coin_status_html = '';
        var active = value.active;

        var accelerometer_stream_sensor_status = value.accelerometer_stream_sensor_status;

        if (active == 'Y') {
            if (accelerometer_stream_sensor_status == 'Y') {
                coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/online.png"/>';
            } else {
                coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';
            }
        } else {
            coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';
        }

        var rate_value = value.rate_value;
        var format = value.format;
        format = hexToDec(format);


        if (rate_value != null) {
            rate_value = hexToDec(rate_value);
        } else {
            rate_value = '';
        }


        if (format == '21') {
            var dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="22">Minutes</option><option value="33">Hours</option></select>';
            var det_dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="21" selected>Seconds</option><option value="22">Minutes</option></select>';

        }
        else if (format == '22') {
            var dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="22" selected>Minutes</option><option value="33">Hours</option></select>';
            var det_dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="21" >Seconds</option><option value="22" selected>Minutes</option></select>';

        }
        else if (format == '33') {
            var dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="22">Minutes</option><option value="33" selected>Hours</option></select>';
        }
        else {
            var dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="22">Minutes</option><option value="33">Hours</option></select>';
            var det_dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="21" selected>Seconds</option><option value="22">Minutes</option></select>';

        }




        if (device_type == null || device_type == '') {
            dis_dev_type = 'No data';
        }

        if (device_type == '12') {
            dis_dev_type = 'Accelerometer X-axis';
        } else
            if (device_type == '14') {
                dis_dev_type = 'Accelerometer Y-axis';
            } else
                if (device_type == '15') {
                    dis_dev_type = 'Accelerometer Z-axis';
                } else
                    if (device_type == '28') {
                        dis_dev_type = 'Aggregate';
                    }

        var last_updated_on = '';
        if (value.updated_on != '' && value.added_on != null) {
            last_updated_on = value.updated_on;
            var stillUtc = moment.utc(last_updated_on).toDate();
            last_updated_on = moment(stillUtc).local().format(date_format);
            var now = moment().format(date_format);

            var ms = moment(now, "DD-MM-YYYY HH:mm:ss").diff(moment(last_updated_on, "DD-MM-YYYY HH:mm:ss"));
            var d = moment.duration(ms);
            var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");

        }
        var a = s.split(':');
        var hour = a[0];
        var minute = a[1];
        var second = a[2];

        data_html += '<td>' + (index + 1) + '</td>'
            + '<td>' + nick_name + ' ' + coin_status_html + '</a></td>'
            + '<td>' + coin_location + ' </a></td>'
            + '<td>' + dis_dev_type + '</td>'
            + '<td>' + device_value + '</td>';

        data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';

        if (jQuery.inArray(device_id, senDeviceIds) != '-1') {
            $.each(dev_settings, function (sindex, svalue) {
                console.log("In if");
                var sen_dev_id = svalue.device_id;
                var sen_active = svalue.sensor_active;
                console.log('Sen id:' + sen_dev_id + 'sen_active:' + sen_active + 'device_id==' + device_id);
                if (device_id == sen_dev_id && sen_active == 'Y') {

                    if (device_type == '12') {
                        data_html += '<td style="border-right:0;border-bottom:0"></td><td rowspan="4"><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
                    }
                    if (device_type == '14') {

                        data_html += '<td rowspan="3"style="border-top:0;border-right:1px solid #ddd;"><div style="margin-top:-31px"><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + det_dropdown_html + '<button class="setdetection" type="button" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></div></td>';

                    }
                }
                if (device_id == sen_dev_id && sen_active == 'N') {
                    if (device_type == '12') {
                        data_html += '<td style="border-right:0;border-bottom:0"></td><td rowspan="4"><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
                    }
                    if (device_type == '14') {

                        data_html += '<td rowspan="3" style="border-top:0;border-right:1px solid #ddd;><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + det_dropdown_html + '<button class="setdetection" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';

                    }
                }

            });


        } else {
            if (device_type == '12') {
                data_html += '<td style="border-right:0;border-bottom:0"></td><td rowspan="4"><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
            }
            if (device_type == '14') {

                data_html += '<td rowspan="3" style="border-top:0;border-right:1px solid #ddd;><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + det_dropdown_html + '<button class="setdetection" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
            }
        }


        data_html += '</tr>';
    });

    data_html += '</tbody>'
        + '</table></div></div>';

    return data_html;




}



/*
* Function 			: getPredStreamDevices(gatewayId)
* Brief 			: load Predictive the list of Devices for a gateway	 
* Input param 		: gatewayId
* Input/output param: NA
* Return			: NA
*/
function getPredStreamDevices(gatewayId, coins) {
    $('#loader').show();
    //Remove sticky notfn box

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlPredStreamDevices + '/' + gatewayId + '/' + coins,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                $('#loader').hide();
                var pred_stream_html = '';
                records = data.records;
                var predStream = records.predStream;
                console.log('predStream===', predStream);

                $.ajax({
                    url: basePathUser + apiUrlGetDeviceSettings + '/' + gatewayId,
                    headers: {
                        'uid': uid,
                        'Api-Key': apikey
                    },
                    type: 'GET',
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    async: false,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("uid", uid);
                        xhr.setRequestHeader("Api-Key", apikey);
                    },
                    success: function (data, textStatus, xhr) {
                        records = data.records;

                        var stream_settings = [];
                        var generalsettings = '';
                        var accstrm = '';
                        var accstrmPM = '';
                        $.each(records, function (index, value) {
                            var device_id = value.device_id;
                            var settings = value.settings;
                            generalsettings = value.generalsettings;
                            if (settings.length > 0) {
                                $.each(settings, function (indexSett, valueSett) {

                                    var sensor_type = valueSett.device_sensor;
                                    var sensor_active = valueSett.sensor_active;

                                    if (sensor_type == "Predictive Maintenance" && sensor_active == 'Y') {
                                        stream_settings.push(valueSett);
                                        console.log("Predictive STREAM");
                                    }

                                });
                            }
                        });


                        if (generalsettings.length > 0) {
                            console.log('IF generalsettings.length', generalsettings);
                            $.each(generalsettings, function (indexSett, valueSett) {
                                predstrm = valueSett.predictivestream;
                            });
                        }

                        if (predStream.length > 0 && predstrm == 'Y') {
                            console.log('If predStream.length==', predStream.length);
                            pred_stream_html = getFormattedSpectrumStreamData(predStream, 'Spectrum Stream', stream_settings);
                        }

                        $('.predStreamTable').html(pred_stream_html);

                        loadSubscriber(uid.toString());

                        moment().format();

                    },
                    error: function (errData, status, error) {
                        if (errData.status == 401) {
                            var resp = errData.responseJSON;
                            var description = resp.description;
                            $('.logout').click();
                            alert(description);
                        }
                    }
                });

            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);
                }
            }
        });
    }
}

/* Spectrum (Predictive Maintainance - 51, 52, 53) */
function getFormattedSpectrumStreamData(dataArr, tableName, dev_settings) {
    console.log('Pred getFormattedSpectrumStreamData dataArr==' + dataArr + 'dev_settings===' + dev_settings);
    var date_format = $('#sesval').data('date_format');

    var deviceIds = [];
    var senDeviceIds = [];
    if (dev_settings != 0) {
        $.each(dev_settings, function (sindex, svalue) {
            senDeviceIds.push(svalue.device_id);
        });
    }
    console.log('senDeviceIds====', senDeviceIds);
    var prevDevice_id = 0;

    var data_html = '<button class="accordion p12">' + tableName + ' (g)</button><div class="panel dp12"><div class="table-responsive"><table class="table table-hover table-bordered table-lp">'
        + '    <tr class="active tb-hd">'
        + '        <th>Sl. No</th>'
        + '        <th>Nick Name</th>'
        + '        <th>Location</th>'
        + '        <th>Sensor Name</th>'
        + '        <th>Value</th>'
        + '        <th>Last Updated</th>'
        + '        <th>Set Stream</th>'
        + '    </tr>'
        + '    <tbody class="users gateways" style="text-align:center;">';

    console.log('dataArr===', dataArr);

    $.each(dataArr, function (index, value) {
        var device_id = value.device_id;
        var gateway_id = value.gateway_id;
        var device_type = value.device_type;
        var device_value = value.device_value;
        // device_value = parseInt(device_value).toFixed(5);
        var nick_name = value.nick_name;
        var d_id = value.d_id;
        var dis_dev_type = '';

        var coin_location = value.coin_location;
        if (coin_location == null || coin_location == 'null' || coin_location == '') {
            coin_location = '';
        }

        var coin_status_html = '';
        var active = value.active;

        var accelerometer_stream_sensor_status = value.accelerometer_stream_sensor_status;

        if (active == 'Y') {
            if (accelerometer_stream_sensor_status == 'Y') {
                coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/online.png"/>';
            } else {
                coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';
            }
        } else {
            coin_status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';
        }

        var rate_value = value.rate_value;
        var format = value.format;
        format = hexToDec(format);

        if (rate_value != null) {
            rate_value = hexToDec(rate_value);
        } else {
            rate_value = '';
        }

        console.log('format==', format);
        if (format == '21') {
            var dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="33">Hours</option></select>';
        }
        else if (format == '22') {
            var dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="33">Hours</option></select>';
        }
        else if (format == '33') {
            var dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="33" selected>Hours</option></select>';
        }
        else {
            var dropdown_html = '<select class="thinpt1" value"' + format + '" ><option value="33">Hours</option></select>';
        }

        if (device_type == null || device_type == '') {
            dis_dev_type = 'No data';
        }

        if (device_type == '51') {
            dis_dev_type = 'Accelerometer X-axis';
        } else if (device_type == '52') {
            dis_dev_type = 'Accelerometer Y-axis';
        } else if (device_type == '53') {
            dis_dev_type = 'Accelerometer Z-axis';
        } else if (device_type == '66') {
            dis_dev_type = 'Aggregate';
        }

        var last_updated_on = '';
        if (value.updated_on != '' && value.added_on != null) {
            last_updated_on = value.updated_on;
            var stillUtc = moment.utc(last_updated_on).toDate();
            last_updated_on = moment(stillUtc).local().format(date_format);
            var now = moment().format(date_format);

            var ms = moment(now, "DD-MM-YYYY HH:mm:ss").diff(moment(last_updated_on, "DD-MM-YYYY HH:mm:ss"));
            var d = moment.duration(ms);
            var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");

        }
        var a = s.split(':');
        var hour = a[0];
        var minute = a[1];
        var second = a[2];

        console.log("prevDevice_id==" + prevDevice_id + "device_id==" + device_id);
        if (prevDevice_id == device_id) {
            // data_html += '<td > </td>'
            // +'<td > </td>'
            // + '<td > </td>'
            // +'<td>'+dis_dev_type+'</td>'
            // +'<td></td>';
            // data_html  += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">'+last_updated_on+'</span></td>';  
        } else {
            data_html += '<td rowspan="4">' + (index + 1) + '</td>'
                + '<td rowspan="4">' + nick_name + ' ' + coin_status_html + '</a></td>'
                + '<td rowspan="4">' + coin_location + ' </a></td>'

            prevDevice_id = device_id;
        }

        data_html += '<td >' + dis_dev_type + '</td>'
        data_html += '<td>' + device_value + '</td>';
        data_html += '<td><span data-localtime-format="d-M-yyyy HH:mm:ss">' + last_updated_on + '</span></td>';

        if (jQuery.inArray(device_id, senDeviceIds) != '-1') {
            $.each(dev_settings, function (sindex, svalue) {
                console.log("In if predictive");
                var sen_dev_id = svalue.device_id;
                var sen_active = svalue.sensor_active;

                if (device_id == sen_dev_id && sen_active == 'Y') {
                    console.log('If device_id == sen_dev_id & sen_active == Y', device_id + '==' + sen_dev_id + 'sen_active == ' + sen_active);
                    if (device_type == '51') {
                        data_html += '<td rowspan="4"style="border-top:0;border-right:1px solid #ddd;"><div style="margin-top:-31px"><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></div></td>';
                    }

                }
                if (device_id == sen_dev_id && sen_active == 'N') {
                    console.log('If device_id == sen_dev_id & sen_active == N', device_id + '==' + sen_dev_id + 'sen_active == ' + sen_active);
                    if (device_type == '51') {
                        data_html += '<td rowspan="4"><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
                    }

                }
            });

        } else {
            if (device_type == '51') {
                data_html += '<td rowspan="4"><input type="text" onkeypress="return validateNumber(event);" placeholder="' + rate_value + '" class="thinpt" maxlength="3"  />' + dropdown_html + '<button class="setstream" type="button" disabled style="background-color: #757E79;" data-devicevalue="' + device_value + '" data-device="' + device_id + '" data-devicetype="' + device_type + '" data-gateway="' + gateway_id + '">SET</button><span class="done grn"></span></td>';
            }

        }

        data_html += '</tr>';
    });

    data_html += '</tbody>'
        + '</table></div></div>';

    return data_html;

}
/* Ends Spectrum */


function dataconvert(dval) {
    var binsize = 15;
    var size = 4;
    var axis_data = dval;
    var b = [];
    var bval = [];
    var sum = 0;
    var d = [];
    var val = [];


    if ((dval & 0x8000) == 0x8000) {

        var i = 0;
        axis_data = (~axis_data) + 1;

        while (binsize != 0) {
            b.push(axis_data & 0x1);
            axis_data = axis_data >> 1;
            binsize--;

            bval.push(b[i] * Math.pow(2, i));
            sum = sum + bval[i];
            i++;

        }

        return (-1 * sum);


    }
    else {
        var i = 0;

        while (size != 0) {
            d.push(axis_data & 0xF);
            axis_data = axis_data >> 4;
            size--;

        }

        val.push(d[0]);
        val.push(d[1] * 16);
        val.push(d[2] * 256);
        val.push(d[3] * 4096);

        for (i = 0; i < 4; i++) {
            sum = sum + val[i];
        }

        return (sum);

    }

}


/*
* Function 			: Click event on SET THRESHOLD
* Brief 			: events which should happen while clicking on SET button in Devices page
* Detail 			: events which should happen while clicking on SET button in Devices page
* Input param 			: event
* Input/output param   	: NA
* Return			: NA
*/
$(document).on("click", ".setTh", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');



    var device = $(this).data('device');
    var devicetype = $(this).data('devicetype');
    var devicevalue = $(this).data('devicevalue');
    var temp_unit = $('#sesval').data('temp_unit');

    var gateway = $(this).data('gateway');
    if (devicetype == '01' || devicetype == '02') {
        var threshold = $('.accVal' + device + devicetype + ' :selected').val();

    } else {
        var threshold = $(this).parent().find('input').val().trim();
    }

    if (threshold == '') {
        $(this).parent().find('input').focus();
        return false;
    }
    else {
        if (devicetype == '01' || devicetype == '02') {
            if (threshold < 0.001 || threshold > 15.875) {
                return alert('The range for Accelerometer threshold value is 0.001g to 15.875g');
            }
        }
        if (devicetype == '03' || devicetype == '04') {
            if (threshold < 2 || threshold > 1990) {
                return alert('The range for Gyroscope threshold value is 2DPS to 1990DPS');
            }
        }
        if (devicetype == '05' || devicetype == '06') {
            if (temp_unit == 'Fahrenheit') {
                if (threshold < -102.2 || threshold > 255.2) {
                    return alert('The range for Temperature threshold value is -102.2 to 255.2 degree Fahrenheit');
                }
                threshold = Math.round((threshold - 32) / 1.8);
            }
            else {
                if (threshold < -55 || threshold > 124) { // Changes : Now Temperature -39 to should support upto -55
                    return alert('The range for Temperature threshold value is -54 to 124 degree Celcius');
                }
            }
        }
        if (devicetype == '07' || devicetype == '08') {
            if (threshold < 2 || threshold > 99) {
                return alert('The range for Humidity threshold value is 2% RH to 99% RH');
            }
        }
    }
    if (devicetype == '03' || devicetype == '04') {
        console.log("_______threshold_________", threshold);
        var thresholdJsonData2 = {
            "2": "2020",
            "3": "2030",
            "4": "2040",
            "5": "2050",
            "6": "2060",
            "7": "2070",
            "8": "2080",
            "9": "2090"
        };
        if (thresholdJsonData2.hasOwnProperty(threshold)) {
            // Access the value associated with the key "age" threshold_value = thresholdJsonData[threshold_value]; console.log("The threshold_value is: " + threshold_value); 
            threshold = thresholdJsonData2[threshold]
            console.log("_______threshold_________", threshold);
        }
        if (threshold % 10 != 0) {
            console.log("_______threshold_________", threshold);
            return alert('The value for Gyroscope should be a multiple of 10 ranging from 2DPS to 1990DPS');
        }
    }
    $this = $(this);

    var succ_msg = 'DONE';

    var postdata = {
        device_id: device,
        device_type: devicetype,
        device_value: devicevalue,
        gateway_id: gateway,
        threshold: threshold
    }

    console.log("post data --->   ",postdata)



    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlSetThreshold,
            type: 'POST',
            //                            data: postdata,
            data: JSON.stringify(postdata),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                //                                $('#addChannels').val('Saving..');
                $this.parent().find('.done').html('wait..');

                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                //                                $('#addChannels').val('Save');
                if (xhr.status == 200 && textStatus == 'success') {
                    if (data.status == 'success') {
                        $this.parent().find('.done').html('DONE');

                        setTimeout(function () { $this.parent().find('.done').html(''); }, 500);

                        info_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> SET Threshold request has been sent and is in progress. Please wait for its response and then make the next GET/SET request..</div>';
                        $('.infobar').html(info_html);

                        setTimeout(function () { $('.infobar').html(''); }, 10000);

                    }
                    if (data.status == 'pending_request') {
                        setTimeout(function () { $this.parent().find('.done').html(''); }, 500);
                        alert_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> GET/SET request is already pending. Please try after sometime!</div>';
                        $('.alertbar').html(alert_html);
                        setTimeout(function () { $('.alertbar').html(''); }, 5000);
                    }

                } else {
                    setTimeout(function () { $this.parent().find('.done').html('FAILED'); }, 500);
                }
            },
            error: function (errData, status, error) {
                //                                    var resp = errData.responseJSON; 
                //                                    $('.error').html(resp.description);
                setTimeout(function () { $this.parent().find('.done').html('FAILED'); }, 500);

                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);
                }

            }
        });
    }

});


//click event to set stream

$(document).on("click", ".setstream", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');


    var device = $(this).data('device');
    var devicetype = $(this).data('devicetype');
    var gateway = $(this).data('gateway');
    var rate_value = $(this).parent().find('input').val();
    var format = $(this).parent().find('select').val();
    var predective_sensor = predective_m;
    var predective_device_id = predective_deviceID;


    if (rate_value == '') {

        $(this).parent().find('.done').html('field blank');
        return false;
    }

    if (format == '21' && (rate_value > 59 || rate_value < 20)) {
        return alert('You can set values between 20 and 59 for seconds.');

    }

    if (devicetype == '12') {
        // debugger;
        if (format == '22' && (rate_value > 59 || rate_value < 1) && (device != predective_device_id)) {
            return alert('You can set values between 1 and 59 for minutes.');
        }
        /*if(format == '22' && (rate_value < 40) && (device == predective_device_id && predective_sensor == true)){
            return alert('You can set values above 40 for minutes because Predictive Maintenance is active.');
        }*/
    } else if (devicetype == '51') {
        if (format == '22' && (rate_value < 40) && (device == predective_device_id && predective_sensor == true)) {
            return alert('You can set values above 40 for minutes because Predictive Maintenance is active.');
        }
    } else {
        if (format == '22' && (rate_value > 59 || rate_value < 1)) {
            return alert('You can set values between 1 and 59 for minutes.');
        }
    }
    if (format == '33' && (rate_value > 96 || rate_value < 1)) {
        return alert('You can set values between 1 and 96 for hours.');
    }

    $this = $(this);

    var succ_msg = 'DONE';

    var postdata = {
        device_id: device,
        device_type: devicetype,
        format: format,
        gateway_id: gateway,
        rate_value: rate_value
    }
    console.log('postdata===', postdata);

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    if (uid != '' && apikey != '') {

        $.ajax({
            url: basePathUser + apiUrlSetStream,
            type: 'POST',
            data: JSON.stringify(postdata),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                $this.parent().find('.done').html('wait..');

                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                if (xhr.status == 200 && textStatus == 'success') {
                    if (data.status == 'success') {
                        $this.parent().find('.done').html('DONE');

                        setTimeout(function () { $this.parent().find('.done').html(''); }, 500);

                        info_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> SET Stream request has been sent and is in progress. Please wait for its response and then make the next GET/SET request..</div>';
                        $('.infobar').html(info_html);
                        setTimeout(function () { $('.infobar').html(''); }, 10000);
                    }
                    if (data.status == 'pending_request') {
                        setTimeout(function () { $this.parent().find('.done').html(''); }, 500);
                        alert_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> GET/SET request is already pending. Please try after sometime!</div>';
                        $('.alertbar').html(alert_html);
                        setTimeout(function () { $('.alertbar').html(''); }, 5000);

                    }
                } else {
                    setTimeout(function () { $this.parent().find('.done').html('FAILED'); }, 500);
                }
            },
            error: function (errData, status, error) {
                //                                    var resp = errData.responseJSON; 
                //                                    $('.error').html(resp.description);
                setTimeout(function () { $this.parent().find('.done').html('FAILED'); }, 500);
            }
        });

    }

});



/*
* Function 			: Click event on GET Current Value
* Brief 			: events which should happen while clicking on GET button in Devices page
* Detail 			: events which should happen while clicking on GET button in Devices page
* Input param 			: event
* Input/output param   	: NA
* Return			: NA
*/
$(document).on("click", ".getCurrentValue", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');


    var device = $(this).data('device');
    var devicetype = $(this).data('devicetype');
    var gateway = $(this).data('gateway');


    $this = $(this);

    var succ_msg = 'DONE';

    var postdata = {
        device_id: device,
        device_type: devicetype,
        gateway_id: gateway
    }
    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    if (uid != '' && apikey != '') {

        $.ajax({
            url: basePathUser + apiUrlGetCurrentValue,
            type: 'POST',
            //                            data: postdata,
            data: JSON.stringify(postdata),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                $this.parent().find('.done').html('wait..');

                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                //                                $('#addChannels').val('Save');
                if (xhr.status == 200 && textStatus == 'success') {
                    if (data.status == 'success') {

                        $this.parent().find('.done').html('Request Sent');

                        setTimeout(function () { $this.parent().find('.done').html(''); }, 500);

                        info_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> GET request has been sent and is in progress. Please wait for its response and then make the next GET/SET request.</div>';
                        $('.infobar').html(info_html);

                    }
                    if (data.status == 'pending_request') {
                        setTimeout(function () { $this.parent().find('.done').html(''); }, 500);

                        alert_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> GET/SET request is already pending. Please try after sometime!</div>';
                        $('.alertbar').html(alert_html);
                        setTimeout(function () { $('.alertbar').html(''); }, 5000);

                    }


                } else {
                    setTimeout(function () { $this.parent().find('.done').html('Request Failed'); }, 500);
                }
            },
            error: function (errData, status, error) {
                //                                    var resp = errData.responseJSON; 
                //                                    $('.error').html(resp.description);
                setTimeout(function () { $this.parent().find('.done').html('FAILED'); }, 5000);
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);
                }
            }
        });

    }
});

/*
* Function 			: Click event on GET Current Value
* Brief 			: events which should happen while clicking on GET button in Devices page
* Detail 			: events which should happen while clicking on GET button in Devices page
* Input param 			: event
* Input/output param   	: NA
* Return			: NA
*/
$(document).on("click", ".eachGateway", function (e) {
    e.preventDefault();

    var gatewayId = $(this).data('gateway');
    $('.devicesTables').html('');
    $('.accStreamTable').html('');
    $('.predStreamTable').html('');
    $('.coin-list').html('');
    $('.sensor-list').html('');
    $('.coins').html('');
    localStorage.setItem("coins_filter", null);
    localStorage.setItem("sensors_filter", null);
    localStorage.setItem("gateway_id", gatewayId);
    localStorage.setItem("panel_list", null);

    if (gatewayId != '') {
        $('.gateway-list-lfnav').find('li').find('a').removeClass('ancYellow');
        $(this).addClass('ancYellow');

        //Remove old notifications        
        $('.detail-content').find('.fixfooter').find('.notifyline').html('');
        //$('.detail-content').find('.fixfootere').find('.notifyline').html('');         

        getDashboardCoin(gatewayId);

        getDevices(gatewayId, 0, 0);
        getAccStreamDevices(gatewayId, 0);
        getPredStreamDevices(gatewayId, 0);

        clearInterval(autohandle1);

        autohandle1 = setInterval(function () { refreshDashboard(); }, 60000);

    }
});

//Example 2 =>http://www.hivemq.com/blog/build-javascript-mqtt-web-application
//        loadSubscriber();
function loadSubscriber(sub_gatewayid) {
    console.log("___________Load Subscribe______", sub_gatewayid);

    $('.success').html('Connected to the gateway');


    //  client.connect(options);

    //Unsubscribe old all topics
    // client.unsubscribe('#');


    var gwstr = sub_gatewayid.toString();
    client.subscribe(gwstr);


    // if(subscribersArr.length>0){
    //     $.each(subscribersArr,function(indSub,subVal){
    // var gwunSubStr = subVal.toString();
    //   if(gwstr!=gwunSubStr){

    //        client.unsubscribe(gwunSubStr); 
    //       }

    //   });

    subscribersArr = [];
    // }

    subscribersArr.push(sub_gatewayid);

}


/*
 * Function 			: getGatewaySettings()
 * Brief 			: load the list of Gateway Settings	 
 * Input param 			: Nil
 * Input/output param           : NA
 * Return			: NA
 */
function getGatewaySettings() {
    $('#loader').show();

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlGetGatewaySettings,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                $('#loader').hide();

                var sc_html = '';
                var gw_li_html = '';
                if (data.records.length > 0) {
                    records = data.records;
                    $.each(records, function (index, value) {
                        var gateway_id = value.gateway_id;
                        var settings = value.settings;
                        var res_gw_nickname = value.gateway_nick_name;
                        // var res_gw_nickname = getGateway_nickname(gateway_id, uid, apikey);
                        var gateway_name;
                        if (res_gw_nickname == '' || res_gw_nickname == null || res_gw_nickname == 'null') {
                            gateway_name = gateway_id;
                        } else {
                            gateway_name = res_gw_nickname;
                        }

                        sc_html += '<table class="table table-hover table-bordered table-lp" style="text-align:center;">'
                            + '<tbody>'
                            + '<tr>'
                            + '<th colspan="8" style="font-size: 15px;">Gateway - ' + gateway_name + '</th>' //gateway_id
                            + '</tr>'
                            + '<tr class="active tb-hd">'
                            + '<th width="40%">Sensor Type</th>'
                            + '<th>SMS</th>'
                            + '<th>EMAIL</th>'
                            + '</tr>'
                            + '</tbody>'
                            + '<tbody class="users gateways">';

                        if (settings.length > 0) {
                            var arrSenTypes = ['Accelerometer', 'Gyrosensor', 'Temperature', 'Humidity'];

                            $.each(settings, function (indexSett, valueSett) {
                                var sensor_type = valueSett.sensor_type;
                                var sms_alert = valueSett.sms_alert;
                                var email_alert = valueSett.email_alert;

                                var smsChecked = "";
                                var emailChecked = "";

                                if (sms_alert == 'Y') {
                                    smsChecked = "checked='checked'";
                                }
                                if (email_alert == 'Y') {
                                    emailChecked = "checked='checked'";
                                }
                                sc_html += '<tr>'
                                    + '<td>' + sensor_type + '</td>'
                                    + '<td><input type="checkbox" ' + smsChecked + ' name="sms" class="sms" value="' + sms_alert + '" data-sensor-type="' + sensor_type + '" data-gateway-id="' + gateway_id + '"/><span class="done"></span></td>'
                                    + '<td><input type="checkbox" ' + emailChecked + ' name="email" class="email" value="' + email_alert + '" data-sensor-type="' + sensor_type + '" data-gateway-id="' + gateway_id + '"/><span class="done"></span></td>'
                                    + '</tr>';


                                var removeItem = sensor_type;

                                arrSenTypes = jQuery.grep(arrSenTypes, function (value) {
                                    return value != removeItem;
                                });

                            });

                            if (arrSenTypes.length > 0) {
                                $.each(arrSenTypes, function (indexRem, valueRem) {
                                    sc_html += '<tr>'
                                        + '<td>' + valueRem + '</td>'
                                        + '<td><input type="checkbox" name="sms" class="sms" value="N" data-sensor-type="' + valueRem + '" data-gateway-id="' + gateway_id + '"/><span class="done"></span></td>'
                                        + '<td><input type="checkbox" name="email" class="email" value="N" data-sensor-type="' + valueRem + '" data-gateway-id="' + gateway_id + '"/><span class="done"></span></td>'
                                        + '</tr>';
                                });
                            }
                        }
                        else {
                            sc_html += '<tr>'
                                + '<td>Accelerometer</td>'
                                + '<td><input type="checkbox" name="sms" class="sms" value="N" data-sensor-type="Accelerometer" data-gateway-id="' + gateway_id + '"/><span class="done"></span></td>'
                                + '<td><input type="checkbox" name="email" class="email" value="N" data-sensor-type="Accelerometer" data-gateway-id="' + gateway_id + '"/><span class="done"></span></td>'
                                + '</tr>'
                                + '<tr>'
                                + '<td>Gyro Sensor</td>'
                                + '<td><input type="checkbox" name="sms" class="sms" value="N" data-sensor-type="Gyrosensor" data-gateway-id="' + gateway_id + '"/><span class="done"></span></td>'
                                + '<td><input type="checkbox" name="email" class="email" value="N" data-sensor-type="Gyrosensor" data-gateway-id="' + gateway_id + '"/><span class="done"></span></td>'
                                + '</tr>'
                                + '<tr>'
                                + '<td>Temperature</td>'
                                + '<td><input type="checkbox" name="sms" class="sms" value="N" data-sensor-type="Temperature" data-gateway-id="' + gateway_id + '"/><span class="done"></span></td>'
                                + '<td><input type="checkbox" name="email" class="email" value="N" data-sensor-type="Temperature" data-gateway-id="' + gateway_id + '"/><span class="done"></span></td>'
                                + '</tr>'
                                + '<tr>'
                                + '<td>Humidity</td>'
                                + '<td><input type="checkbox" name="sms" class="sms" value="N" data-sensor-type="Humidity" data-gateway-id="' + gateway_id + '"/><span class="done"></span></td>'
                                + '<td><input type="checkbox" name="email" class="email" value="N" data-sensor-type="Humidity" data-gateway-id="' + gateway_id + '"/><span class="done"></span></td>'
                                + '</tr>'
                        }

                        sc_html += '</tbody>'
                            + '</table>';


                    });
                }
                else {
                    sc_html = '<table><tr><td width="300">No Data Found</td></tr></table>';
                }
                $('.gatewaySettingsTables').html(sc_html);
                //                                        
            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);

                }
            }
        });
    }
}

//Update SMS Notification Settings
$(document).on("change", ".sms", function (e) {
    e.preventDefault();

    var gateway_id = $(this).data('gateway-id');
    var sensor_type = $(this).data('sensor-type');
    var valsms = 'N';
    if (this.checked) {
        valsms = 'Y';
    }


    $this = $(this);

    var succ_msg = 'DONE';

    var postdata = {
        sensor_type: sensor_type,
        gateway_id: gateway_id,
        sms_alert: valsms
    }


    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlUpdateSMSNotification,
            type: 'POST',
            //                            data: postdata,
            data: JSON.stringify(postdata),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                //                                $('#addChannels').val('Saving..');
                $this.parent().find('.done').html('wait..');

                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                //                                $('#addChannels').val('Save');
                if (xhr.status == 200 && textStatus == 'success') {
                    if (data.status == 'success') {

                        $this.parent().find('.done').html('Saved');

                        setTimeout(function () { $this.parent().find('.done').html(''); }, 5000);
                    }
                } else {
                    setTimeout(function () { $this.parent().find('.done').html('Failed'); }, 5000);
                }
            },
            error: function (errData, status, error) {
                //                                    var resp = errData.responseJSON; 
                //                                    $('.error').html(resp.description);
                setTimeout(function () { $this.parent().find('.done').html('Failed'); }, 5000);

                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);
                }

            }
        });
    }

});

//Update EMAIL Notification Settings
$(document).on("change", ".email", function (e) {
    e.preventDefault();

    var gateway_id = $(this).data('gateway-id');
    var sensor_type = $(this).data('sensor-type');
    var valemail = 'N';
    if (this.checked) {
        valemail = 'Y';
    }


    $this = $(this);

    var succ_msg = 'DONE';

    var postdata = {
        sensor_type: sensor_type,
        gateway_id: gateway_id,
        email_alert: valemail
    }



    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlUpdateEmailNotification,
            type: 'POST',
            //                            data: postdata,
            data: JSON.stringify(postdata),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                //                                $('#addChannels').val('Saving..');
                $this.parent().find('.done').html('wait..');

                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                //                                $('#addChannels').val('Save');
                if (xhr.status == 200 && textStatus == 'success') {
                    if (data.status == 'success') {

                        $this.parent().find('.done').html('Saved');

                        setTimeout(function () { $this.parent().find('.done').html(''); }, 5000);
                    }
                } else {
                    setTimeout(function () { $this.parent().find('.done').html('Failed'); }, 5000);
                }
            },
            error: function (errData, status, error) {
                //                                    var resp = errData.responseJSON; 
                //                                    $('.error').html(resp.description);
                setTimeout(function () { $this.parent().find('.done').html('Failed'); }, 5000);

                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);
                }

            }
        });
    }

});


//Add Notification EMAIL Ids
$(document).on("click", ".addMails", function (e) {
    e.preventDefault();
    $('.error,.success').html('')

    var email_notify = $('#email_notify').val();
    var edit_id = $('#edit_id').val();

    if (email_notify == '') {
        $('.error').html('*Please enter email id.');
        $('#email_notify').focus();
        return false;
    }
    else {
        var regExp = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
        if (!regExp.test(email_notify)) {
            $('.error').html('*Invalid email id.');
            $('#email_notify').focus();
            return false;
        }
    }

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    var postdata = {
        notification_email: email_notify,
        user_id: uid,
        id: edit_id
    }


    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlNotificationEmailIds,
            type: 'POST',
            data: JSON.stringify(postdata),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                $('.addMails').val('Saving..');
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                $('.addMails').val('Save');
                if (xhr.status == 200 && textStatus == 'success') {
                    if (data.status == 'success') {
                        $('.success').html('Email saved successfully');
                        $('#email_notify').val('');
                        $('#edit_id').val('');
                        //Load email ids list
                        getNotificationEmailIds();
                        setTimeout(function () { $('.success').html(''); }, 5000);
                    }
                }
            },
            error: function (errData, status, error) {
                $('.addMails').val('Save');
                var resp = errData.responseJSON;
                $('.error').html(resp.description);
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);
                }

            }
        });
    }

});

/*
 * Function 			: getNotificationEmailIds()
 * Brief 			: load the list of Users in Users page	 
 * Input param 			: Nil
 * Input/output param           : NA
 * Return			: NA
 */
function getNotificationEmailIds() {

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlNotificationEmailIds,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {


                var sc_html = '';

                if (data.records.length > 0) {
                    records = data.records;
                    var slCount = 1;

                    $.each(records, function (index, value) {
                        var notification_email = value.notification_email;
                        var edId = value.id;

                        sc_html += '<tr>'
                            + '<td>' + slCount + '</td>'
                            + '<td>' + notification_email + '</td>'
                            + '<td><span class="editNotifyEmail" data-id="' + edId + '" data-email="' + notification_email + '" data-toggle="collapse" data-target="#demo"><img src="../img/edit.png" width="16" height="16"/></span></td>'
                            + '<td><span class="glyphicon glyphicon-trash delNotifyEmail" data-id="' + edId + '" data-email="' + notification_email + '"></span></td>'
                            + '</tr>';
                        slCount++;
                    });
                }
                else {
                    sc_html = '<tr><td width="300">No Data Found</td></tr>';
                }
                $('.notifyEmailList').html(sc_html);

            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);

                }
            }
        });
    }
}



//Edit Notification EMAIL Id
$(document).on("click", ".editNotifyEmail", function (e) {
    e.preventDefault();
    $('.success,.error').html('');

    var notif_email = $(this).data('email');
    var id = $(this).data('id');

    $('#edit_id').val(id);
    $('#email_notify').val(notif_email);
});

//Delete Notification EMAIL Id
$(document).on("click", ".delNotifyEmail", function (e) {
    e.preventDefault();
    $('.success,.error').html('');

    var id = $(this).data('id');

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');

    var r = confirm("Are you sure to delete email?");

    if (uid != '' && apikey != '' && r == true) {
        $.ajax({
            url: basePathUser + apiUrlNotificationEmailIds + '/' + id,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'DELETE',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {

                if (xhr.status == 200 && textStatus == 'success') {
                    if (data.status == 'success') {
                        $('.success').html('Email deleted successfully');
                        //Load email ids list
                        getNotificationEmailIds();

                        setTimeout(function () { $('.success').html(''); }, 5000);
                    }
                }
            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);
                }
            }
        });
    }
});

//Add Mobile Number
$(document).on("click", ".addPhone", function (e) {
    e.preventDefault();
    $('.error,.success').html('')

    var phone_notify = $('#phone_notify').val();
    var edit_id = $('#edit_id').val();

    if (phone_notify == '') {
        $('.error').html('*Please enter mobile number.');
        $('#phone_notify').focus();
        return false;
    }

    var countryData = $("#phone_notify").intlTelInput("getSelectedCountryData");
    var countryCode = countryData.dialCode;
    //remove spaces between phone no
    phone_notify = '+' + countryCode + '' + phone_notify.replace(/\s+/g, '');

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    var postdata = {
        notification_phone: phone_notify,
        user_id: uid,
        id: edit_id
    }


    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlNotificationPhone,
            type: 'POST',
            data: JSON.stringify(postdata),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                $('.addPhone').val('Saving..');
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                $('.addPhone').val('Save');
                if (xhr.status == 200 && textStatus == 'success') {
                    if (data.status == 'success') {
                        $('.success').html('Mobile number saved successfully');
                        $('#phone_notify').val('');
                        $('#edit_id').val('');
                        //Load email ids list
                        getNotificationPhoneNumbers();
                        setTimeout(function () { $('.success').html(''); }, 5000);
                    }
                }
            },
            error: function (errData, status, error) {
                $('.addPhone').val('Save');
                var resp = errData.responseJSON;
                $('.error').html(resp.description);
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);
                }

            }
        });
    }

});

//Get Notify Phone list
function getNotificationPhoneNumbers() {

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlNotificationPhone,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {


                var sc_html = '';

                if (data.records.length > 0) {
                    records = data.records;
                    var slCount = 1;

                    $.each(records, function (index, value) {
                        var notification_phone = value.notification_phone;
                        var edId = value.id;

                        sc_html += '<tr>'
                            + '<td>' + slCount + '</td>'
                            + '<td>' + notification_phone + '</td>'
                            + '<td><span class="editNotifyPhone" data-id="' + edId + '" data-phone="' + notification_phone + '" data-toggle="collapse" data-target="#demo1"><img src="../img/edit.png" width="16" height="16"/></span></td>'
                            + '<td><span class="glyphicon glyphicon-trash delNotifyPhone" data-id="' + edId + '" data-phone="' + notification_phone + '"></span></td>'
                            + '</tr>';
                        slCount++;
                    });
                }
                else {
                    sc_html = '<tr><td width="300">No Data Found</td></tr>';
                }
                $('.notifyPhoneList').html(sc_html);

            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);

                }
            }
        });
    }
}

//Edit Notification Phone Number
$(document).on("click", ".editNotifyPhone", function (e) {
    e.preventDefault();
    $('.success,.error').html('');

    var notif_phone = $(this).data('phone');
    var id = $(this).data('id');

    $('#edit_id').val(id);
    //                $('#phone_notify').val(notif_phone);

    $("#phone_notify").intlTelInput("setNumber", notif_phone);

});

//Delete Notification Phone Number
$(document).on("click", ".delNotifyPhone", function (e) {
    e.preventDefault();
    $('.success,.error').html('');

    var id = $(this).data('id');

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');

    var r = confirm("Are you sure to delete number?");

    if (uid != '' && apikey != '' && r == true) {
        $.ajax({
            url: basePathUser + apiUrlNotificationPhone + '/' + id,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'DELETE',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {

                if (xhr.status == 200 && textStatus == 'success') {
                    if (data.status == 'success') {
                        $('.success').html('Number deleted successfully');
                        //Load Phone numbers list
                        getNotificationPhoneNumbers();

                        setTimeout(function () { $('.success').html(''); }, 5000);
                    }
                }
            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);
                }
            }
        });
    }
});

//NTFN box toggle
$(document).on("click", "#slideCtrl", function (e) {


    var imgSrc = $('#imgCtrl').attr('src');

    if (imgSrc == '../img/arrow-down-wht.png') {
        $('#imgCtrl').attr('src', '../img/arrow-up-wht.png');
    }
    else {
        $('#imgCtrl').attr('src', '../img/arrow-down-wht.png');
    }
    $('.ntfn-box').slideToggle();
});




/*
 * Function 			: getNotificationEmailIds()
 * Brief 			: load the list of Users in Users page	 
 * Input param 			: Nil
 * Input/output param           : NA
 * Return			: NA
 */
function getCoin() {

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlGetCoin,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {


                var popUp = '';

                if (data.records.length > 0) {
                    records = data.records;
                    var slCount = 1;

                    $.each(records, function (index, value) {
                        var coin_name = value.coin_name;
                        var coin_nick = value.coin_nick;
                        var coin_id = value.coin_id;
                        var id = value.id;
                        var coin_lat = value.coin_lat;
                        var coin_lng = value.coin_lng;

                        sc_html += '<tr>'
                            + '<td>' + slCount + '</td>'
                            + '<td>' + coin_name + '</td>'
                            + '<td><span class="" data-id="' + id + '" data-email="' + coin_nick + '" ><img src="../img/edit.png" width="16" height="16"/></span></td>'
                            + '<td><span class="glyphicon glyphicon-trash delNotifyEmail" data-id="' + id + '" data-email="' + nick_lat + '"></span></td>'
                            + '<td><span class="glyphicon glyphicon-trash delNotifyEmail" data-id="' + id + '" data-email="' + nick_lng + '"></span></td>'
                            + '</tr>';
                        slCount++;
                    });
                }
                else {
                    sc_html = '<tr><td width="300">No Data Found</td></tr>';
                }
                $('.notifyEmailList').html(sc_html);
                if (newMarker) {
                    map.removeLayer(newMarker);

                }


            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);

                }
            }
        });
    }
}


function getLocationGateways() {
    $('#loader').show();

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlGateways,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                $('#loader').hide();
                var sc_html = '';
                var gw_li_html = '';
                var gw_asi = '';
                var i = 0;
                if (data.records.length > 0) {
                    records = data.records;
                    $.each(records, function (index, value) {
                        gateway_id = value.gateway_id;
                        g_id = value.ug_id;
                        var res_gw_nickname = value.gateway_nick_name;
                        // var res_gw_nickname = getGateway_nickname(gateway_id, uid, apikey);
                        var gateway_name;
                        if (res_gw_nickname == '' || res_gw_nickname == null || res_gw_nickname == 'null') {
                            gateway_name = gateway_id;
                        } else {
                            gateway_name = res_gw_nickname;
                        }

                        sc_html = '<tr><td>Click on any Gateways</td></tr>';

                        gw_li_html += '<option value="' + g_id + '">' + gateway_name + '</option>'; //gateway_id
                        if (i == 0) {
                            gw_asi += '' + g_id + '';
                            i++;
                        }
                    });
                }
                else {
                    sc_html = '<tr><td width="300">No Gateways Found</td></tr>';
                }
                $('#gatewaysList').html(sc_html);

                $('.gateway-list').html(gw_li_html);
                $('#gwid').val(gw_asi);
            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);

                }
            }
        });
    }
}


function addGetCoin() {

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');

    var gatewayId = localStorage.getItem("g_id");
    var gid = localStorage.getItem("gatewayId");

    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlAddGetCoin + '/' + gid,
            headers: {
                'uid': uid,
                'Api-Key': apikey,
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                var records = data.records;
                var sc_html = '';
                var gw_html = '';
                var gw_asi = '';
                var i = 0;
                var a = 0;
                var gateway_id = [];
                var coinCount = 0;

                // split multiple gateways into an array
                var gw_split = gid.split(",");

                var q = gw_split.length;

                for (i = 0; i < q; i++) {
                    var gateway_id = gw_split[i];
                    gw_html += '<option value="' + i + '" data-value="' + gateway_id + '">' + gateway_id + '</option>';
                    var j = data.records[gw_split[i]].length;
                }

                $('.gw_id').html(gw_html);
                $('.coi_id').html(sc_html);

                i = 0;
                var j = data.records[gw_split[i]].length;
                var sc_html = '';

                for (q = 0; q < j; q++) {
                    if (j == 1 || records[gw_split[i]][q]['coin_lat'] == 'no') {

                        sc_html += '<option disabled="disabled" style="background-color:green; color: white">No Coins here</option>';
                    } else if (records[gw_split[i]][q]['coin_lat'] == '' && records[gw_split[i]][q]['coin_lng'] == '') {

                        var nick_name = records[gw_split[i]][q]['nick_name'];
                        var device_id = records[gw_split[i]][q]['device_id'];
                        sc_html += '<option value="' + device_id + '">' + nick_name + '</option>';
                    } else {

                        var nick_name = records[gw_split[i]][q]['nick_name'];
                        var device_id = records[gw_split[i]][q]['device_id'];
                        sc_html += '<option disabled="disabled" style="background-color:green; color: white" value="' + device_id + '">' + nick_name + ' - already added.</option>';
                    }
                }

                if ($('.coi_id').html('')) {
                    $('#coinList').html(sc_html);
                }

                $('#thisGw').on("click", function () {

                    var i = $('#thisGw').data('gw');
                    var j = data.records[gw_split[1]].length;

                    var sc_html = '';
                    for (q = 0; q < j; q++) {
                        if (j == 1 || records[gw_split[i]][q]['coin_lat'] == 'no') {

                            sc_html += '<option disabled="disabled" style="background-color:green; color: white">No Coins here</option>';
                        } else if (records[gw_split[i]][q]['coin_lat'] == '' && records[gw_split[i]][q]['coin_lng'] == '') {

                            var nick_name = records[gw_split[i]][q]['nick_name'];
                            var device_id = records[gw_split[i]][q]['device_id'];
                            sc_html += '<option value="' + device_id + '">' + nick_name + '</option>';
                        } else {

                            var nick_name = records[gw_split[i]][q]['nick_name'];
                            var device_id = records[gw_split[i]][q]['device_id'];
                            sc_html += '<option disabled="disabled" style="background-color:green; color: white" value="' + device_id + '">' + nick_name + ' - already added.</option>';
                        }
                    }
                    $('#coinList').html(sc_html);
                });
            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);
                }
            }
        });
    }
}



function getLocation() {

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlGetLocation,
            headers: {
                'uid': uid,
                'Api-Key': apikey,
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {

                var dp_html = '';
                var slCount = 1;
                if (data.records.length > 0) {
                    records = data.records;
                    dp_html = '<div class="table-responsive"><table class="table table-hover table-bordered table-lp ul-table" style="width: 100%;">'
                        + '<tbody style="text-align: center;">'
                        + '<tr class="active tb-hd">'
                        + '<th>Sl. No</th>'
                        + '<th>Location Name</th>'
                        + '<th>Location Description</th>'
                        + '<th>Location Image</th>'
                        + '<th>Add/Edit Coins</th>'
                        + '<th>Monitor Location</th>'
                        + '<th>Event Logs</th>'
                        + '<th>Delete Location</th>'
                        + '<th>Edit Location</th>'
                        + '<tr></tbody>'
                        + '<tbody class="users gateways locationList" style="text-align: center;">';

                    $.each(records, function (index, value) {
                        var location_name = value.location_name;
                        var location_description = value.location_description;
                        var location_image = value.location_image;
                        var location_id = value.id;



                        dp_html += '<tr>'
                            + '<td>' + slCount + '</td>'
                            + '<td>' + location_name + '</td>'
                            + '<td>' + location_description + '</td>'
                            + '<td>' + location_image + '</td>'
                            + '<td><button class="addCoinLocation" value="' + location_name + '|' + location_id + '|' + location_image + '">Add</button></td>'
                            + '<td><button class="monitorLocation" value="' + location_name + '|' + location_id + '|' + location_image + '">Monitor</button></td>'
                            + '<td><button class="viewEventLogs" value="' + location_name + '|' + location_id + '|' + location_image + '">View</button></td>'
                            + '<td><span class="glyphicon glyphicon-trash delUserLocation" data-id="' + location_id + '" data-location_image="' + location_image + '"></span></td>'
                            + '<td><span class="glyphicon glyphicon-pencil editUserLocation" data-location_id="' + location_id + '" data-location_name="' + location_name + '" data-location_image="' + location_image + '" data-location_desc="' + location_description + '"></span></td>'
                            + '</tr>';
                        slCount++;

                    });
                } else {
                    $('.success-msg').hide();
                    $('.loc-msg').html('');
                    $('.error').html('No Locations found.');
                    $('.ul-table').hide();
                }
                dp_html += '</tbody></table></div>';
                $('.locations').html(dp_html);


            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);

                }
            }
        });
    }
}

/*

function to get gateway id assigned to location from location id 
*/

function getGatewayLocation(locationId) {
    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');


    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlGetGatewayLocation + '/' + locationId,
            headers: {
                'uid': uid,
                'Api-Key': apikey,
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                var sc_html = '';
                var gw_li_html = '';
                var g_id = [];
                var gateway_id = [];
                if (data.records.length > 0) {
                    records = data.records;
                    $.each(records, function (index, value) {
                        g_id.push(value.ug_id);
                        gateway_id.push(value.gateway_id);
                    });
                }
                else {
                    sc_html = '<tr><td width="300">No Gateways Found</td></tr>';
                }
                localStorage.setItem("gatewayId", gateway_id);
                localStorage.setItem("g_id", g_id);


            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);

                }
            }
        });
    }
}


$(document).on("click", ".monitorLocation", function (e) {
    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('apikey');

    var lid = $(this).val();


    var buff = lid.split("|");

    var location_name = buff[0];
    var location_id = buff[1];
    var l_img = buff[2];

    var location_image = l_img.trim();

    localStorage.setItem("location_name", location_name);
    localStorage.setItem("location_id", location_id);
    localStorage.setItem("location_image", location_image);

    window.location = "map_monitor2.php";

});

$(document).on("click", ".addCoinLocation", function (e) {
    e.preventDefault;

    var lid = $(this).val();


    var buff = lid.split("|");

    var location_name = buff[0];
    var location_id = buff[1];
    var l_image = buff[2];

    var location_image = l_image.trim();

    localStorage.setItem("location_name", location_name);
    localStorage.setItem("location_id", location_id);
    localStorage.setItem("location_image", location_image);

    window.location = "map_editor2.php";

});

$(document).on("click", ".viewEventLogs", function (e) {
    e.preventDefault;

    var lid = $(this).val();


    var buff = lid.split("|");

    var location_name = buff[0];
    var location_id = buff[1];
    var l_image = buff[2];

    var location_image = l_image.trim();

    localStorage.setItem("location_name", location_name);
    localStorage.setItem("location_id", location_id);
    localStorage.setItem("location_image", location_image);

    window.location = "eventLog.php";

});


function renderAlert(gwId, devId, devType, devValue, last_updated) {
    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    var temp_unit = $('#sesval').data('temp_unit');

    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiRenderAlert + '/' + gwId + '/' + devId,
            headers: {
                'uid': uid,
                'Api-Key': apikey,
            },
            type: 'GET',
            contentType: 'application/json; chatset=utf-8',
            datType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                if (data.records.length > 0) {
                    records = data.records;
                    $.each(records, function (index, value) {

                        var lol = '';
                        var devNickname = value.nick_name;
                        var devLat = value.coin_lat;
                        var devLng = value.coin_lng;


                        var popLocation = [];

                        if (devLat != '') {


                            if (devType == '01' || devType == '02') {
                                lol = 'Accelerometer';

                                var coinDataPop = '<div><p id="resp"></p><p>' + devNickname + ' : Accelerometer Threshold<br/>crossed on ' + last_updated + '</p><button class="btn btn-primary addLog" data-gateway="' + gwId + '" data-device_id="' + devId + '" data-device_type="' + devType + '" data-nick_name="' + devNickname + '"data-device_value="' + devValue + '" data-updated_on="' + last_updated + '">Log This Event.</button></br><button class="btn btn-primary rotateCamera" data-gateway="' + gwId + '" data-device_id="' + devId + '" data-device_type="' + devType + '" data-nick_name="' + devNickname + '"data-device_value="' + devValue + '" data-updated_on="' + last_updated + '">Rotate Camera.</button></div>';
                                var alertMarker = new L.marker([devLat, devLng], { icon: alert })
                                    .addTo(markersLayer).on('click', function (e) {
                                        map.removeLayer(alertMarker);
                                        var popLocation = [devLat, devLng];
                                        var popup = new L.Rrose({ offset: new L.Point(0, 10) }).setLatLng(popLocation).setContent(coinDataPop).openOn(map)
                                    });

                            }

                            if (devType == '03' || devType == '04') {
                                lol = 'Gyroscope';

                                var coinDataPop = '<div><p id="resp"></p><p>' + devNickname + ' : Gyroscope Threshold<br/>crossed on ' + last_updated + '</p><button class="btn btn-primary addLog" data-gateway="' + gwId + '" data-device_id="' + devId + '" data-device_type="' + devType + '" data-nick_name="' + devNickname + '"data-device_value="' + devValue + '" data-updated_on="' + last_updated + '">Log This Event.</button></div>';
                                var alertMarker = new L.marker([devLat, devLng], { icon: alert })
                                    .addTo(markersLayer).on('click', function (e) {
                                        map.removeLayer(alertMarker);
                                        var popLocation = [devLat, devLng];
                                        var popop = new L.Rrose({ offset: new L.Point(0, 10) }).setLatLng(popLocation).setContent(coinDataPop).openOn(map)
                                    });

                            }

                            if (devType == '05' || devType == '06') {
                                if (temp_unit == 'Fahrenheit') {
                                    devValue = (devValue * 1.8) + 32;
                                    devValue = parseFloat(devValue).toFixed(3);
                                }
                                lol = 'Temperature';
                                var coinDataPop = '<div><p id="resp"></p><p>' + devNickname + ' : Temperature Threshold<br/>crossed on ' + last_updated + '</p><hr><button class="btn btn-primary addLog" data-gateway="' + gwId + '" data-device_id="' + devId + '" data-device_type="' + devType + '" data-nick_name="' + devNickname + '"data-device_value="' + devValue + '" data-updated_on="' + last_updated + '">Log This Event.</button></div>';
                                var alertMarker = new L.marker([devLat, devLng], { icon: alert }).addTo(markersLayer).on('click', function (e) {
                                    map.removeLayer(alertMarker);
                                    var popLocation = [devLat, devLng];
                                    var popup = new L.Rrose({ offset: new L.Point(0, 10) }).setLatLng(popLocation).setContent(coinDataPop).openOn(map)
                                });

                            }

                            if (devType == '07' || devType == '08') {
                                lol = 'Humidity';
                                var coinDataPop = '<div><p id="resp"></p><p>' + devNickname + ' : Humidity Threshold<br/>crossed on ' + last_updated + '</p><hr><button class="btn btn-primary addLog" data-gateway="' + gwId + '" data-device_id="' + devId + '" data-device_type="' + devType + '" data-nick_name="' + devNickname + '"data-device_value="' + devValue + '" data-updated_on="' + last_updated + '">Log This Event.</button></div>';
                                var alertMarker = new L.marker([devLat, devLng], { icon: alert }).addTo(markersLayer).on('click', function (e) {
                                    map.removeLayer(alertMarker);
                                    var popLocation = [devLat, devLng];
                                    var popup = new L.Rrose({ offset: new L.Point(0, 10) }).setLatLng(popLocation).setContent(coinDataPop).openOn(map)
                                });

                            }

                        }

                    });
                }
            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    alert(description);
                }
            }
        });
    }

}


function clickEmergency(gwId, devId, devType, devValue, last_updated) {
    console.log("emergency==", gwId, devId, devType, devValue, last_updated)
}


$(document).on("click", ".addCoin", function (e) {
    e.preventDefault();


    var edit_id = $('#edit_id').val();
    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    var gateway_id = $('.gw_id option:selected').text();
    var coin_id = $('.coi_id option:selected').val();
    var nick_name = $('.coi_id option:selected').text();


    if (gateway_id == '') {
        $('.addError').html('*Select a Gateway');
        $('.gw_id').focus();
        return false;
    }


    if (coin_id == undefined || coin_id == '') {
        $('.addError').html('*Select a Coin');
        $('.coi_id').focus();
        return false;
    }

    postData = {
        coin_id: coin_id,
        id: edit_id,
        coin_lat: lat,
        coin_lng: lng,
        gateway_id: gateway_id
    }

    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlAddCoin,
            type: 'POST',
            data: JSON.stringify(postData),
            contentType: 'application/json; charset="urf-8"',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                $('.addCoin').val('Adding Coin.');
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },

            success: function (data, textStatus, xhr) {
                var added_coin = '';
                var added_coin_array = [];
                $('.addCoin').val('Add Coin');
                if (xhr.status == 200 && textStatus == 'success') {
                    if (data.status == 'success') {
                        count++;

                        if (count == coinNum) {
                            $('.success').html('Coin Successfully added to location.');
                            setTimeout(function () { $('.success').html(''); }, 5000);
                            $('#image-map').hide();
                            $('.afterAdd').show();
                        } else {
                            $('.success').html('Coin Successfully added to location.');
                            setTimeout(function () { $('.success').html(''); }, 5000);
                            var gatewayId = localStorage.getItem("gatewayId");
                            getCoin(gatewayId);
                            map.closePopup();
                        }
                    }
                }
            },
            failure: function (errData, textStatus, xhr) {
                $('.addCoin').val('Add Coin');
                var resp = errData.responseJSON;
                $('.error').html(resp.description);
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);
                }
            }
        });

    }
});


function getStoredMapLatLong(location_id,monitor = false){

    console.log("getStoredMapLatLong--->  ",location_id);

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');

    if (uid != '' && apikey != '') {

        $.ajax({
            url: basePathUser + apiUrlLocationLatLong + '/' + location_id,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                console.log("getStoredMapLatLong---> response --> ", data);

                if (data.records && data.records.length > 0) {
                    var lat = parseFloat(data.records[0].latitude);
                    var lng = parseFloat(data.records[0].longitude);

                    if (!isNaN(lat) && !isNaN(lng)) {
                        // Center the map to the location
                        map.setView([lat, lng], 30);
                        // map.setZoom(12); 

                        if(!monitor){
                            L.marker([lat, lng]).addTo(map).on('click', function (e) {
                                var popup = new L.Rrose({ offset: new L.Point(0, 10) })
                                    .setLatLng([lat, lng])
                                    .setContent(popUpForm)
                                    .openOn(map);
                            });
                        }
                        // Add the marker to the map
                        

                        console.log("Map centered at:", lat, lng);
                    } else {
                        console.error("Invalid latitude or longitude values.");
                    }
                } else {
                    console.error("No records found in the response.");
                }
            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);

                }
            }
        });

    }
 
}

function getCoin(gatewayId) {
    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');


    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlGetCoin + '/' + gatewayId,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {


                var gw_split = gatewayId.split(",");

                var sc_html = '';
                var gw_html = '';
                var gw_asi = '';
                var i = 0;
                var count = gw_split.length;

                var y = gw_split.length;

                for (i = 0; i < y; i++) {
                    var j = data.records[gw_split[i]].length;
                    var q = 0;

                    $.each(data.records[gw_split[i]], function (index, value) {
                        var gateway_id = data.records[gw_split[i]][q]['gateway_id'];
                        var nick_name = data.records[gw_split[i]][q]['nick_name'];
                        var device_id = data.records[gw_split[i]][q]['device_id'];
                        var nick_name = data.records[gw_split[i]][q]['nick_name'];
                        var id = data.records[gw_split[i]][q]['id'];
                        var active = data.records[gw_split[i]][q]['active'];
                        var coin_lat = data.records[gw_split[i]][q]['coin_lat'];
                        var coin_lng = data.records[gw_split[i]][q]['coin_lng'];
                        var coin_location = data.records[gw_split[i]][q]['coin_location'];
                        var gateway_nick_name = getGateway_nickname(gateway_id, uid, apikey);
                        var latest_temp_stream = data.records[gw_split[i]][q]['latest_temp_stream'];
                        var latest_humid_stream = data.records[gw_split[i]][q]['latest_humid_stream'];
                        if (gateway_nick_name == '' || gateway_nick_name == null) {
                            gateway_nick_name = gateway_id;
                        }


                        if (coin_lat != '' && coin_lng != '') {
                            var iconUrl = active === 'Y' ? 'uploads/markergreen.png' : 'uploads/markerred.png';
                            var icon = L.icon({
                                iconUrl: iconUrl,
                                iconSize: [25, 25],
                            });


                            var popLocation = [coin_lat, coin_lng];
                            if (coin_location != '' && coin_location != null) {
                                var popUpForm = '<p id="coinData">' + gateway_nick_name + ' ; ' + nick_name + ' ; ' + coin_location + '; <br> Latest temperature stream : ' + latest_temp_stream + '<br> Latest humidity stream : ' + latest_humid_stream + ' </p>';
                            } else {
                                var popUpForm = '<p id="coinData">' + gateway_nick_name + ' ; ' + nick_name + ' ; <br> Latest temperature stream : ' + latest_temp_stream + ' <br> Latest humidity stream : ' + latest_humid_stream + '</p>';
                            }

                            var getCoinMarker = L.marker([coin_lat, coin_lng], { icon: icon })
                                .addTo(map).on('click', function (e) {

                                    var popup = new L.Rrose({ offset: new L.Point(0, 10) }).setLatLng(popLocation).setContent(popUpForm).openOn(map);
                                });
                        }
                        q++;
                    });
                }

            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);

                }
            }
        });
    }
}




$('#addImage').on('click', function () {
    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');


    $('.error').html('');
    $('.loc-msg').html('');


    var location_name = $('#location_name').val();
    var location_description = $('#location_description').val();
    var location_image = $('#location_image').val();
    var file_name = $('#file').val();

    var file_data = $('#file').prop('files')[0];

    var file_extn = file_name.substr(file_name.lastIndexOf('.') + 1).toUpperCase();
    var file_n = file_name.substring(file_name.lastIndexOf('\\') + 1, file_name.lastIndexOf('.'));
    file_n = file_n + '_' + uid;

    var new_file_name = file_n + '.' + file_extn.toLowerCase();


    if (file_extn != 'JPG' && file_extn != 'JPEG' && file_extn != 'PNG') {
        $('.error').html('Only jpeg .png .jpg file formats are accepted.');
        $('#file').focus();
        return false;
    }

    var edit_id = $('#edit_id').val();
    var l_image = '';

    var gateways = [];
    $.each($(".gateway-list option:selected"), function () {
        gateways.push($(this).val());
    });


    if (location_name == '') {
        $('.error').html('*Please enter a location name.');
        $('#location_name').focus();
        return false;
    }

    if (location_description == '') {
        $('.error').html('*Please enter a description for the location');
        $('#location_description').focus();
        return false;
    }

    if (file_name == '') {
        $('.error').html('*Please select a floor plan for the location');
        $('#file').focus();
        return false;
    }

    if (gateways == "") {
        $('.error').html('*Please assign a gateway to the location');
        return false;
    }

    var form_data = new FormData();
    form_data.append('file', file_data, new_file_name);

    $('.loc-msg').html('Creating New User Location. This may take a minute or two.');


    $.ajax({
        url: 'upload.php', // point to server-side PHP script 
        dataType: 'text', // what to expect back from the PHP script
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',

        success: function (response) {
            var fn = response.split(":");


            var res = response.split('.', 1)[0];
            if (res == "File already exists") {
                $('.loc-msg').html('');
                $('.error').html('*File name already exists! Please choose a different name for the file.');
                return false;

            }
            else {
                var l_image = fn[1];
                var postdata = {
                    location_name: location_name,
                    location_description: location_description,
                    location_image: l_image,
                    gateway_id: gateways,
                    user_id: uid,
                    id: edit_id
                }

                $('#apikey').html(apikey);
                $('#uid').html(uid);

                aadLocation(postdata);

            }

        },
        error: function (response) {
            $('.error').html(response); // display error response from the PHP script
        }
    });

});

$('#addMap').on('click', function () {
    
    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');


    $('.error').html('');
    $('.loc-msg').html('');

    
    var location_name = $('#location_name').val();
    var location_description = $('#location_description').val();
    var location_image = $('#location_image').val();
    
    console.log("Hello")

  

    var edit_id = $('#edit_id').val();
  

    var gateways = [];
    $.each($(".gateway-list option:selected"), function () {
        gateways.push($(this).val());
    });


    if (location_name == '') {
        alert("*Please enter a location name.");
        $('.error').html('*Please enter a location name.');
        $('#location_name').focus();
        return false;
    }

    if (location_description == '') {
        alert("*Please enter a description for the location");
        $('.error').html('*Please enter a description for the location');
        $('#location_description').focus();
        return false;
    }

   

    if (gateways == "") {
        alert("*Please assign a gateway to the location");
        $('.error').html('*Please assign a gateway to the location');
        return false;
    }

    var storedLat = localStorage.getItem('latitude');
    var storedLng = localStorage.getItem('longitude');

    if(storedLat === null && storedLat === undefined && storedLat.trim() === '' &&
    storedLng === null && storedLng === undefined && storedLng.trim() === ''){
        alert("*Please Select Lat & Long from below Map");
        return false;
    }
    

    console.log("gateways--->",gateways);
    
    


    
    //     url: 'upload.php', // point to server-side PHP script 
    //     dataType: 'text', // what to expect back from the PHP script
    //     cache: false,
    //     contentType: false,
    //     processData: false,
    //     data: form_data,
    //     type: 'post',

    //     success: function (response) {
    //         var fn = response.split(":");


    //         var res = response.split('.', 1)[0];
    //         if (res == "File already exists") {
    //             $('.loc-msg').html('');
    //             $('.error').html('*File name already exists! Please choose a different name for the file.');
    //             return false;

    //         }
    //         else {
    //             var l_image = fn[1];
                var postdata = {
                    location_name: location_name,
                    location_description: location_description,
                    location_image: "Null",
                    gateway_id: gateways,
                    user_id: uid,
                    id: edit_id,
                    latitude:storedLat,
                    longitude:storedLng
                }

    //             $('#apikey').html(apikey);
    //             $('#uid').html(uid);

                 aadLocation(postdata);

    //         }

    //     },
    //     error: function (response) {
    //         $('.error').html(response); // display error response from the PHP script
    //     }
    // });

});



function aadLocation(postdata) {
    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');

    console.log("post Data---> ",postdata)
    $.ajax({

        url: basePathUser + apiUrlAddLocation,
        type: 'POST',
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            $('.addLocation').val('Adding Location');
            xhr.setRequestHeader("uid", uid);
            xhr.setRequestHeader("lat", postdata.latitude);
            xhr.setRequestHeader("long", postdata.longitude);
            xhr.setRequestHeader("Api-Key", apikey);
        },
        success: function (data, textStatus, xhr) {
            $('.addLocation').val('Save');

            if (xhr.status == 200 && textStatus == 'success') {
                if (data.status == 'success') {
                    $('.error').html('');
                    $('.loc-msg').html('Location Created Successfully. Add Coins to Map.');
                    setTimeout(function () { $('.loc-msg').html(''); }, 5000);
                    $('.add-location').hide();
                    $('.addShow').show();
                    getLocation();

                }
                if (data.status == 'no_coin_gateway') {
                    $('.loc-msg').html('');
                    $('.error').html('One of the Gateway selected has no coins under it. Please add coins to the Gateway before creating location.');

                }

            }
        },
        error: function (errData, status, error) {
            $('.addLocation').val('Add Location');
            var resp = errData.responseJSON;
            if (resp.description == 'GATEWAY_EXISTS') {
                $('.loc-msg').html('');
                $('.error').html('Gateway is already assigned to a location!');
            } else {
                $('.loc-msg').html('');
                $('.error').html(resp.description);
            }
            if (errData.status == 401) {
                var resp = errData.responseJSON;
                var description = resp.description;
                $('.logout').click();
                alert(description);
            }
        }
    });

}




//Delete User Locations
$(document).on("click", ".delUserLocation", function (e) {
    e.preventDefault();
    $('.loc-msg,.error').html('');

    var id = $(this).data('id');
    var file_name = $(this).data('location_image').trim();


    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');

    var delr = confirm("Are you sure you want to delete the location?");

    if (uid != '' && apikey != '' && delr == true) {
        $.ajax({
            url: basePathUser + apiUrlDeleteUserLocation + '/' + id,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'DELETE',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {

                if (xhr.status == 200 && textStatus == 'success') {
                    if (data.status == 'success') {
                        $.ajax({
                            url: 'delete_location.php?file_name=' + file_name,
                            dataType: 'text',
                            cache: false,
                            contentType: false,
                            processData: false,
                            type: 'post',

                            success: function (response) {
                                $('.error').html('');

                                $('.loc-msg').html('User Location deleted successfully');
                                setTimeout(function () { $('.loc-msg').html(''); }, 5000);
                                getLocation();


                            },
                            error: function (response) {
                                $('.error').html(response);
                            }
                        });


                    }
                }
            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);
                }
            }
        });
    }
});


$(document).on('click', '.rotateCamera', function () {
    var g_id = $(this).data('gateway');
    var d_id = $(this).data('device_id');
    var d_type = $(this).data('device_type');
    var nick_name = $(this).data('nick_name');
    var d_value = $(this).data('device_value');
    var updated_on = $(this).data('updated_on');
    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');

    console.log("rotate camera==", g_id, "d_id=", d_id, "d_type==", d_type, "nick_name==", nick_name, "d_value==", d_value, "updated_on==", updated_on);
})


$(document).on('click', '.addLog', function () {

    map.closePopup();


    var g_id = $(this).data('gateway');
    var d_id = $(this).data('device_id');
    var d_type = $(this).data('device_type');
    var nick_name = $(this).data('nick_name');
    var d_value = $(this).data('device_value');
    var updated_on = $(this).data('updated_on');
    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');


    var dev_type;

    if (d_type == '01')
        dev_type = 'Accelerometer Low';
    if (d_type == '02')
        dev_type = 'Accelerometer High';
    if (d_type == '03')
        dev_type = 'Gyroscope Low';
    if (d_type == '04')
        dev_type = 'Gyroscope High';
    if (d_type == '05')
        dev_type = 'Temperature Low';
    if (d_type == '06')
        dev_type = 'Temperature High';
    if (d_type == '07')
        dev_type = 'Humidity Low';
    if (d_type == '08')
        dev_type = 'Humidity High';

    /*var dvalue = hexToDec(d_value);

    if(d_type == '01' || d_type == '02')
        dvalue = dvalue / 10;
    if(d_type == '03' || d_type == '04')
        dvalue = dvalue * 10;
    if(d_type == '05' || d_type == '06')
    {
        if(dvalue > 126){
            dvalue = dvalue - 126;
            dvalue = -dvalue;
        }
        if(dvalue == 126){
            dvalue = 0;
        }
    }*/


    var log_html = '<div style="text-align:center;">'
        + '<h4> <u>Create Event Log:</u> </h4>'
        + '<p>Gateway: ' + g_id + '</p><hr>'
        + '<p>Coin: ' + nick_name + '</p><hr>'
        + '<p>Sensor: ' + dev_type + '</p><hr>'
        + '<p>Value: ' + d_value + '</p><hr>'
        + 'Log Details: <input type="text" id="log_description" name="log-text">'
        + '<button class="btn btn-primary eventSubmitLog">Save Log</button>';
    +'</div>';

    $('.deviceLog').html(log_html);




    $('.eventSubmitLog').on('click', function (e) {

        var log_description = $('#log_description').val();

        if (log_description == '') {
            $('.error').html('Please log the event with appropriate description.');
            return false;
        }


        var postData = {
            gateway_id: g_id,
            device_id: d_id,
            device_type: d_type,
            device_value: d_value,
            log_description: log_description,
            updated_on: updated_on
        }

        if (uid != '' && apikey != '') {
            $.ajax({
                url: basePathUser + apiUrlEventAddLog,
                headers: {
                    'uid': 'uid',
                    'Api-Key': 'apikey'
                },
                data: JSON.stringify(postData),
                type: 'POST',
                contentType: 'application/json; charset=utf-8;',
                datType: 'json',
                async: false,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("uid", uid);
                    xhr.setRequestHeader("Api-Key", apikey);
                },
                success: function (data, textStatus, xhr) {

                    if (xhr.status == 200 && textStatus == 'success') {
                        if (data.status == 'success') {
                            $('.deviceLog').html('<div class="row" style="text-align:center; color:green;">Event has been successfully logged.</div>');
                            setTimeout(function () { $('.deviceLog').html(''); }, 1000);
                            $('.error').html('');


                        }
                    }
                },
                error: function (errData, status, error) {
                    if (errData.status == 401) {
                        var resp = errData.responseJSON;
                        var description = resp.description;
                        alert(description);
                    }
                }
            });
        }
    });
});


function getEventLogs(locationId) {
    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    var date_format = $('#sesval').data('date_format');


    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlGetEventLogs + '/' + locationId,
            headers: {
                'uid': uid,
                'Api-Key': apikey,
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                $('#loader').hide();

                var sc_html = '';
                var log_li_html = '';
                if (data.records.length > 0) {
                    records = data.records;
                    $.each(records, function (index, value) {
                        gateway_id = value.gateway_id;
                        device_id = value.device_id;
                        device_type = value.device_type;
                        device_value = value.device_value;
                        log_desc = value.log_description;
                        var updated_on = value.updated_on;

                        var dev_type;

                        if (device_type == '01')
                            dev_type = 'Accelerometer Low';
                        if (device_type == '02')
                            dev_type = 'Accelerometer High';
                        if (device_type == '03')
                            dev_type = 'Gyroscope Low';
                        if (device_type == '04')
                            dev_type = 'Gyroscope High';
                        if (device_type == '05')
                            dev_type = 'Temperature Low';
                        if (device_type == '06')
                            dev_type = 'Temperature High';
                        if (device_type == '07')
                            dev_type = 'Humidity Low';
                        if (device_type == '08')
                            dev_type = 'Humidity High';


                        if (updated_on != '') {
                            var stillUtc = moment.utc(updated_on).toDate();
                            updated_on = moment(stillUtc).local().format(date_format);
                        }


                        log_li_html += '<tr><td>' + (index + 1) + '</td>'
                            + '<td>' + gateway_id + '</td>'
                            + '<td>' + device_id + '</td>'
                            + '<td>' + dev_type + '</td>'
                            + '<td>' + device_value + '</td>'
                            + '<td>' + log_desc + '</td>'
                            + '<td>' + updated_on + '</td></tr>';

                    });
                }
                else {
                    log_li_html += '<tr><td width="300">No Logs Found</td></tr>';
                }
                $('#eventLogs').html(log_li_html);


            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);

                }
            }
        });
    }

}




function updatedRequestActionTaken(gateway_id, device_id, device_type) {

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');

    var postdata = {
        gateway_id: gateway_id,
        device_id: device_id,
        device_type: device_type
    }

    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUpdateRequestAction,
            headers: {
                'uid': uid,
                'Api-Key': apikey,
            },
            type: 'POST',
            data: JSON.stringify(postdata),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                if (xhr.status == 200 && textStatus == 'success') {
                    if (data.status == 'response_received') {
                        $('.infobar').html('');

                    }

                }


            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    alert(description);

                }
            }
        });
    }

}


//Edit User Locations
$(document).on("click", ".editUserLocation", function (e) {

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');


    var location_name = $(this).data('location_name');
    var location_id = $(this).data('location_id');
    var l_image = $(this).data('location_image');
    var location_image = l_image.trim();

    var location_desc = $(this).data('location_desc');


    localStorage.setItem("location_name", location_name);
    localStorage.setItem("location_id", location_id);
    localStorage.setItem("location_image", location_image);
    localStorage.setItem("location_desc", location_desc);


    window.location = "map_location_editor.php";


});


$(document).on("click", ".dashboardfilter", function (e) {

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');

    var gatewayId = $(this).data('gateway');
    $('.error-tab').html('');

    var sensors = [];
    var coins = [];
    var c = '';

    $.each($(".coin-list option:selected"), function () {
        c = $(this).val();
        c = "'" + c + "'";
        coins.push(c);
    });


    $.each($(".sensor-list option:selected"), function () {
        sensors.push($(this).val());
    });


    if (coins.length == 0 && sensors.length == 0) {
        $('.error-tab').html('*Please select a coin or a sensor or both.');
        return;

    }

    if (!sensors.includes("12,14,15,28")) {
        $('.accStreamTable').html('');
    }

    if (!sensors.includes("51,52,53,54")) {
        $('.predStreamTable').html('');
    }

    if (coins.length != 0 && sensors.length == 0) {
        getAccStreamDevices(gatewayId, coins);
    }

    if (coins.length != 0 && sensors.length == 0) {
        getPredStreamDevices(gatewayId, coins);
    }


    if (coins.length == 0) {
        coins.push(0);
    }

    if (sensors.length == 0) {
        sensors.push(0);
    }

    localStorage.setItem("coins_filter", coins);
    localStorage.setItem("sensors_filter", sensors);

    getDevices(gatewayId, coins, sensors);


    if (sensors.includes("12,14,15,28")) {
        getAccStreamDevices(gatewayId, coins);
    }

    if (sensors.includes("51,52,53,54")) {
        getPredStreamDevices(gatewayId, coins);
    }

    var plist = localStorage.getItem("panel_list");
    if (plist != "null" && plist != '') {
        var arr = plist.split(',');
        var i;
        for (i = 0; i < arr.length; i++) {
            $('.' + arr[i] + '').addClass("active1");
            $('.d' + arr[i] + '').css("display", "block");
        }
    }


});


function getDashboardCoin(gatewayId) {
    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');


    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlGetCoin + '/' + gatewayId,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                gatewayId = gatewayId.toString();
                var gw_split = gatewayId.split(",");

                var coin_li_html = '';
                var i = 0;

                var y = gw_split.length;


                for (i = 0; i < y; i++) {
                    var j = data.records[gw_split[i]].length;


                    if (data.records[gw_split[i]][0][0] == "no_coin") {
                        coin_li_html = '    There are no coins under this Gateway.';

                        $('.coins').html(coin_li_html);
                        return;

                    }
                    //var coin_html='<div class="col-sm-3 coin" ><h6 class="labh6">Coins</h6><select multiple class="coin-list"></select></div><div class="col-sm-5 sensor"><h6 class="labh6">Sensors</h6><select multiple class="sensor-list"></select></div><br/><br/><button class="dashboardfilter" data-gateway="'+gatewayId+'" style="margin-left:70px">Apply Filter</button>';
                    var coin_html = '<div class="col-sm-3 coin" ><h6 class="labh6">Coins</h6><select multiple class="coin-list"></select></div><br/><br/><button class="dashboardfilter" data-gateway="' + gatewayId + '" style="margin-left:70px">Apply Filter</button>';

                    $('.coins').html(coin_html);


                    var q = 0;

                    $.each(data.records[gw_split[i]], function (index, value) {
                        var gd_id = data.records[gw_split[i]][q]['gd_id'];
                        var gateway_id = data.records[gw_split[i]][q]['gateway_id'];
                        var device_id = data.records[gw_split[i]][q]['device_id'];
                        var nick_name = data.records[gw_split[i]][q]['nick_name'];

                        coin_li_html += '<option value="' + device_id + '">' + nick_name + '</option>';


                        q++;
                    });

                }
                $('.coin-list').html(coin_li_html);
                //var sensor_li_html = '<option value="01,02">Accelerometer</option><option value="03,04">Gyroscope</option><option value="05,06">Temperature</option><option value="07,08">Humidity</option><option value="09">Temperature Stream</option><option value="10">Humidity Stream</option><option value="12,14,15,28">Accelerometer Stream</option>';

                //$('.sensor-list').html(sensor_li_html); 


                $('.coin-list').multiselect({
                    maxHeight: 130,
                    includeSelectAllOption: true,
                    numberDisplayed: 2

                });

                //$('.sensor-list').multiselect({
                //	maxHeight: 130, 
                //	includeSelectAllOption: true, 
                //	numberDisplayed: 2

                //});

            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);

                }
            }
        });
    }
}



function getAccelerometerValues(threshold_value) {
    var accVal_html = '';

    // Add the 0.001 option
    if (threshold_value == 0.001) {
        accVal_html += '<option value="0.001" selected>0.001</option>';
    } else {
        accVal_html += '<option value="0.001">0.001</option>';
    }
    
    // Add the new options: 0.024, 0.041, 0.061, and 0.081
    var additionalOptions = [0.024, 0.041, 0.061, 0.081];
    additionalOptions.forEach(function (value) {
        if (threshold_value == value) {
            accVal_html += '<option value="' + value + '" selected>' + value + '</option>';
        } else {
            accVal_html += '<option value="' + value + '">' + value + '</option>';
        }
    });
    
    // Add the 0.1 option
    if (threshold_value == 0.1) {
        accVal_html += '<option value="0.1" selected>0.1</option>';
    } else {
        accVal_html += '<option value="0.1">0.1</option>';
    }
    
    // Generate the remaining options from 0.375 to 15.875
    for (i = 0.375; i <= 15.875; i = i + 0.125) {
        if (threshold_value == i) {
            accVal_html += '<option value="' + i + '" selected>' + i + '</option>';
        } else {
            accVal_html += '<option value="' + i + '">' + i + '</option>';
        }
    }
    
    return accVal_html;
}

function getGeneralSettings() {
    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    var lang_html = '';
    var rms_html = '';
    var tu_html = '';

    var sen_html = '';
    var dr_html = '';
    var sendreport = '';

    var endtime = starttime = sendtime = '';

    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlGetGeneralSettings,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {

                if (data.records.length > 0) {
                    records = data.records;
                    $.each(records, function (index, value) {

                        var date_format = value.date_format;
                        var rms_values = value.rms_values;
                        var temp_unit = value.temp_unit;
                        var generalsettings = value.generalsettings;
                        var dailyreportsettings = value.dailyreportsettings;
                        var mail_restriction_limit = value.mail_restriction_limit;
                        var mail_restriction = value.mail_alert_restriction;
                        var mail_restriction_interval = value.mail_restriction_interval;
                        var starttime = value.starttime;
                        var endtime = value.endtime;
                        // Mail restriction
                        if (mail_restriction == "Y") {
                            mail_alert_restriction_html = '<option value="N">Disable</option><option value="Y" selected>Enable</option>';
                        } else {
                            mail_alert_restriction_html = '<option value="N" selected>Disable</option><option value="Y">Enable</option>';
                        }
                        $('.mail_restriction').html(mail_alert_restriction_html);
                        $('.txtlimitMail').val(mail_restriction_limit);
                        $('.txtMailTimeInt').val(mail_restriction_interval);
                        $('.start_time').val(starttime);
                        $('.end_time').val(endtime);
                        // document.getElementById('start_time').value = starttime;
                        // document.getElementById('end_time').value = endtime;

                        if (date_format == "DD-MM-YYYY HH:mm:ss") {
                            date_html = '<option value="DD-MM-YYYY HH:mm:ss" selected>DD-MM-YYYY HH:mm:ss</option><option value="MM-DD-YYYY HH:mm:ss">MM-DD-YYYY HH:mm:ss</option><option value="YYYY-DD-MM HH:mm:ss">YYYY-DD-MM HH:mm:ss</option><option value="YYYY-MM-DD HH:mm:ss">YYYY-MM-DD HH:mm:ss</option>';
                        }
                        else if (date_format == "MM-DD-YYYY HH:mm:ss") {

                            date_html = '<option value="DD-MM-YYYY HH:mm:ss" >DD-MM-YYYY HH:mm:ss</option><option value="MM-DD-YYYY HH:mm:ss" selected>MM-DD-YYYY HH:mm:ss</option><option value="YYYY-DD-MM HH:mm:ss">YYYY-DD-MM HH:mm:ss</option><option value="YYYY-MM-DD HH:mm:ss">YYYY-MM-DD HH:mm:ss</option>';
                        }
                        else if (date_format == "YYYY-DD-MM HH:mm:ss") {

                            date_html = '<option value="DD-MM-YYYY HH:mm:ss" >DD-MM-YYYY HH:mm:ss</option><option value="MM-DD-YYYY HH:mm:ss">MM-DD-YYYY HH:mm:ss</option><option value="YYYY-DD-MM HH:mm:ss" selected>YYYY-DD-MM HH:mm:ss</option><option value="YYYY-MM-DD HH:mm:ss">YYYY-MM-DD HH:mm:ss</option>';
                        }
                        else {
                            date_html = '<option value="DD-MM-YYYY HH:mm:ss">DD-MM-YYYY HH:mm:ss</option><option value="MM-DD-YYYY HH:mm:ss" >MM-DD-YYYY HH:mm:ss</option><option value="YYYY-DD-MM HH:mm:ss">YYYY-DD-MM HH:mm:ss</option><option value="YYYY-MM-DD HH:mm:ss" selected>YYYY-MM-DD HH:mm:ss</option>';
                        }

                        $('.date_format').html(date_html);

                        if (rms_values == 1) {
                            rms_html = '<option value=1 selected>RMS Velocity and RMS Displacement</option><option value=0>Velocity and Displacement</option>';
                        } else {
                            rms_html = '<option value=1>RMS Velocity and RMS Displacement</option><option value=0 selected>Velocity and Displacement</option>';
                        }
                        $('.rms_values').html(rms_html);

                        if (temp_unit == 'Fahrenheit') {

                            tu_html = '<option value="Celsius" >Celsius</option><option value="Fahrenheit" selected>Fahrenheit</option>';
                        } else {
                            tu_html = '<option value="Celsius" selected>Celsius</option><option value="Fahrenheit" >Fahrenheit</option>';
                        }

                        $('.temp_unit').html(tu_html);

                        var accChecked = gyroChecked = tempChecked = humidChecked = strmChecked = accstrmChecked = tempstrmChecked = humidstrmChecked = spectrumstrmChecked = '';


                        if (generalsettings.length > 0) {
                            $.each(generalsettings, function (indexSett, valueSett) {

                                acc = valueSett.accelerometer;
                                gyro = valueSett.gyroscope;
                                temp = valueSett.temperature;
                                humid = valueSett.humidity;
                                strm = valueSett.stream;
                                accstream = valueSett.accelerometerstream;
                                tempstrm = valueSett.temperaturestream;
                                humidstrm = valueSett.humiditystream;
                                spectrumstream = valueSett.predictivestream;

                                if (acc == 'Y') { accChecked = "checked='checked'"; }
                                if (gyro == 'Y') { gyroChecked = "checked='checked'"; }
                                if (temp == 'Y') { tempChecked = "checked='checked'"; }
                                if (humid == 'Y') { humidChecked = "checked='checked'"; }
                                if (strm == 'Y') { strmChecked = "checked='checked'"; }
                                if (accstream == 'Y') { accstrmChecked = "checked='checked'"; }
                                if (tempstrm == 'Y') { tempstrmChecked = "checked='checked'"; }
                                if (humidstrm == 'Y') { humidstrmChecked = "checked='checked'"; }
                                if (spectrumstream == 'Y') { spectrumstrmChecked = "checked='checked'"; }

                            });
                        }


                        sen_html = '<tbody><tr><td>Accelerometer<input type="checkbox" ' + accChecked + '  name="sensors" value="acc" class="a"/> </td>'
                            + '<td> Gyroscope<input type="checkbox" ' + gyroChecked + ' name="sensors" value="gyro"class="a" /></td> </tr>'
                            + '<tr><td> Temperature <input type="checkbox" ' + tempChecked + ' name="sensors" value="temp" class="a"/></td>'
                            + '<td>Humidity <input type="checkbox" ' + humidChecked + ' name="sensors" value="humid" class="a"/> </td> '
                            + '<tr><td>Temperature Stream <input type="checkbox" ' + tempstrmChecked + ' name="sensors" value="tempstream" class="a"/></td>'
                            + '<td> Humidity Stream <input type="checkbox" ' + humidstrmChecked + ' name="sensors" value="humidstream" class="a"/></td></tr>'
                            + '<tr><td> Accelerometer Stream<input type="checkbox" ' + accstrmChecked + ' name="sensors" value="accstream" class="a"/> </td>'
                            + '<td> Spectrum Stream<input type="checkbox" ' + spectrumstrmChecked + ' name="sensors" value="spectrumstream" class="a"/> </td></tr>'
                            + ' <td colspan="4"><button type="button" class="btn btn-primary sensors" >Update sensors</button></tr></td>'
                            + '</tbody>';

                        $('.checkboxes').html(sen_html);

                        var draccChecked = drgyroChecked = drtempChecked = drhumidChecked = drtempstrmChecked = drhumidstrmChecked = '';


                        if (dailyreportsettings.length > 0) {
                            $.each(dailyreportsettings, function (indexSett, valueSett) {

                                dracc = valueSett.accelerometer;
                                drgyro = valueSett.gyroscope;
                                drtemp = valueSett.temperature;
                                drhumid = valueSett.humidity;
                                drtempstrm = valueSett.temperaturestream;
                                drhumidstrm = valueSett.humiditystream;
                                sendreport = valueSett.send_report;
                                starttime = valueSett.report_start_time;
                                endtime = valueSett.report_end_time;
                                sendtime = valueSett.report_send_time;

                                if (dracc == 'Y') { draccChecked = "checked='checked'"; }
                                if (drgyro == 'Y') { drgyroChecked = "checked='checked'"; }
                                if (drtemp == 'Y') { drtempChecked = "checked='checked'"; }
                                if (drhumid == 'Y') { drhumidChecked = "checked='checked'"; }
                                if (drtempstrm == 'Y') { drtempstrmChecked = "checked='checked'"; }
                                if (drhumidstrm == 'Y') { drhumidstrmChecked = "checked='checked'"; }


                            });
                        }

                        if (sendreport == 'Y') {
                            drbtn = '<button class="btn btn-xs btn-primary active" id="on">ON</button>'
                                + '<button class="btn btn-xs btn-default" id="off">OFF</button>';

                        } else {
                            drbtn = '<button class="btn btn-xs btn-default" id="on">ON</button>'
                                + '<button class="btn btn-xs btn-primary active" id="off">OFF</button>';

                        }
                        $('.toggle').html(drbtn);

                        dr_html = '<tbody><tr style="border: none;">'
                            + '<td style="border-top: none;"><input type="checkbox" ' + draccChecked + ' value="Accelerometer"><span style="margin-left: 10px;" >Accelerometer</span> </label></td>'
                            + '<td style="border-top: none;"><input type="checkbox" ' + drgyroChecked + ' value="Gyroscope"><span style="margin-left: 10px;" >Gyroscope</span></label></td>'
                            + '</tr><tr style="border: none;">'
                            + '<td style="border-top: none;"> <input type="checkbox" ' + drtempChecked + ' value="Temperature"><span style="margin-left: 10px;" >Temperature  </span></label></td>'
                            + '<td style="border-top: none;"><input type="checkbox" ' + drhumidChecked + ' value="Humidity"><span style="margin-left: 10px;" >Humidity </span> </label></td>'
                            + '</tr><tr style="border: none;">'
                            + '<td style="border-top: none;"> <input type="checkbox" ' + drtempstrmChecked + ' value="TemperatureStream"><span style="margin-left: 10px;" >TemperatureStream </span></label></td>'
                            + '<td style="border-top: none;"> <input type="checkbox" ' + drhumidstrmChecked + ' value="HumidityStream"><span style="margin-left: 10px;" >HumidityStream </span> </label></td>'
                            + '</tr></tbody>';

                        $('.dailyreportcheckbox').html(dr_html);


                        if (endtime != '' && starttime != '') {
                            var days = moment(endtime).diff(moment(starttime), 'days');

                            $(".daily_report").val(days).attr("selected", "selected");

                            var pick = moment(starttime).utc().format();
                            var local = moment.utc(pick).local().format('HH:mm');
                            $('.picker').val(local);
                        }

                        if (sendtime != '') {

                            var spick = moment(sendtime).utc().format();
                            var slocal = moment.utc(spick).local().format('HH:mm');
                            $('.schpicker').val(slocal);
                        }


                    });
                }
            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);

                }
            }
        });
    }
}


function updateGeneralSettings(date_format, rms_values, temp_unit) {

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');

    console.log(date_format);
    var postdata = {
        date_format: date_format,
        rms_values: rms_values,
        temp_unit: temp_unit
    }

    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUpdateGeneralSettings,
            headers: {
                'uid': uid,
                'Api-Key': apikey,
            },
            type: 'POST',
            data: JSON.stringify(postdata),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                if (xhr.status == 200 && textStatus == 'success') {
                    if (date_format != '') {
                        info_html = 'The preferred date format has been updated!';

                        $('.success').html(info_html);
                        setTimeout(function () { $('.success').html(''); }, 5000);
                    }
                    if (rms_values != '') {
                        info_html = 'The preferred RMS setting has been updated!';

                        $('.success').html(info_html);
                        setTimeout(function () { $('.success').html(''); }, 5000);
                    }
                    if (temp_unit != '') {
                        info_html = 'The unit for temperature has been updated!';

                        $('.success').html(info_html);
                        setTimeout(function () { $('.success').html(''); }, 5000);
                    }

                }


            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    alert(description);

                }
            }
        });
    }

}


function refreshDashboard() {

    var gwId = localStorage.getItem("gateway_id");

    var coins = localStorage.getItem("coins_filter");
    var sensors = localStorage.getItem("sensors_filter");
    var plist = localStorage.getItem("panel_list");


    if (coins == "null" && sensors == "null") {
        getDevices(gwId, 0, 0);
        getAccStreamDevices(gwId, 0);
        getPredStreamDevices(gwId, 0);
    }
    if (coins != "null" && sensors == "null") {
        getDevices(gwId, coins, 0);
        getAccStreamDevices(gwId, coins);
        getPredStreamDevices(gwId, 0);
    }
    if (coins != "null" && sensors != "null") {
        getDevices(gwId, coins, sensors);
    }
    if (coins != "null" && sensors.includes("12,14,15,28")) {
        getAccStreamDevices(gwId, coins);
        getPredStreamDevices(gwId, 0);
    }
    if (coins == "null" && sensors != "null") {
        getDevices(gwId, 0, sensors);
    }
    if (coins == "null" && sensors.includes("12,14,15,28")) {
        getAccStreamDevices(gwId, 0);
        getPredStreamDevices(gwId, 0);
    }

    if (plist != "null" && plist != '') {
        var arr = plist.split(',');
        var i;
        for (i = 0; i < arr.length; i++) {
            $('.' + arr[i] + '').addClass("active1");
            $('.d' + arr[i] + '').css("display", "block");
        }
    }

}

$(document).on("click", ".setdetection", function (e) {

    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');


    var device = $(this).data('device');
    var gateway = $(this).data('gateway');
    var rate_value = $(this).parent().find('input').val();
    var format = $(this).parent().find('select').val();

    if (rate_value == '') {

        $(this).parent().find('.done').html('field blank');
        return false;
    }

    if (format == '21' && (rate_value > 15 || rate_value < 5)) {
        return alert('Detection Period - You can set 5, 10 or 15 for seconds.');

    }
    if (format == '22' && (rate_value > 10 || rate_value < 1)) {
        return alert('Detection Period - You can set values between 1 and 10 for minutes.');

    }

    if (format == '21') {
        if (rate_value % 5 != 0) {
            return alert('The value for Detection Period should be a multiple of 5 ranging from 5 to 15');
        }
    }

    $this = $(this);


    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');


    var succ_msg = 'DONE';

    var devicetype = '14';

    var postdata = {
        device_id: device,
        device_type: devicetype,
        format: format,
        gateway_id: gateway,
        rate_value: rate_value
    }

    if (uid != '' && apikey != '') {

        $.ajax({
            url: basePathUser + apiUrlSetDetection,
            type: 'POST',
            data: JSON.stringify(postdata),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                $this.parent().find('.done').html('wait..');

                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                if (xhr.status == 200 && textStatus == 'success') {
                    if (data.status == 'success') {
                        $this.parent().find('.done').html('DONE');

                        setTimeout(function () { $this.parent().find('.done').html(''); }, 500);

                        info_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> SET Detection Period request has been sent and is in progress. Please wait for its response and then make the next request.</div>';
                        $('.infobar').html(info_html);
                        setTimeout(function () { $('.infobar').html(''); }, 10000);
                    }
                    if (data.status == 'pending_request') {
                        setTimeout(function () { $this.parent().find('.done').html(''); }, 500);
                        alert_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong> GET/SET/Detection request is already pending. Please try after sometime!</div>';
                        $('.alertbar').html(alert_html);
                        setTimeout(function () { $('.alertbar').html(''); }, 5000);

                    }
                    if (data.status == 'detection_reject') {
                        console.log("Reject");
                        setTimeout(function () { $this.parent().find('.done').html(''); }, 500);
                        alert_html = '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Info!</strong>  1) Detection Period rate should be less than the stream rate. 2) If detection Period is set in Minutes, make sure the stream is 10 times the detection Period. NOTE: You can consider setting the stream rate first. </div>';
                        $('.alertbar').html(alert_html);
                        setTimeout(function () { $('.alertbar').html(''); }, 5000);

                    }
                } else {
                    setTimeout(function () { $this.parent().find('.done').html('FAILED'); }, 500);
                }
            },
            error: function (errData, status, error) {
                //                                    var resp = errData.responseJSON; 
                //                                    $('.error').html(resp.description);
                setTimeout(function () { $this.parent().find('.done').html('FAILED'); }, 500);
            }
        });

    }

});


/*
 * Function             : devicesLowBattery()
 * Brief                : Load Devices with Low Battery in last 7 days of a pertcular user  
 * Input param          : Nil
 * Input/output param   : NA
 * Return               : NA
*/
function devicesLowBattery() {
    // $('#loader').show();

    var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
    var date_format = $('#sesval').data('date_format');

    if (uid != '' && apikey != '') {
        $.ajax({
            url: basePathUser + apiUrlDevicesLowBattery,
            headers: {
                'uid': uid,
                'Api-Key': apikey
            },
            type: 'GET',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("uid", uid);
                xhr.setRequestHeader("Api-Key", apikey);
            },
            success: function (data, textStatus, xhr) {
                // $('#loader').hide(); 

                var sc_html = '';
                var device_low_battery_html = '';
                var iClass = '';
                var last_seen_words = '';
                var gateway_id_name = '';
                var device_coin_name = '';

                if (data.records.length > 0) {
                    /*records = data.records;*/
                    console.log(data.records);
                    device_low_battery_html += '<table class="table table-striped " style="width:100% !important">'
                        + '<tbody>'
                        + '<thead>'
                        + '<th></th>'
                        + '<th><h5><b>Gateway ID:</b></h5></th>'
                        + '<th><h5><b>Coin Name:</b></h5></th>'
                        + '<th><h5><b>Last Seen:</b></h5></th>'
                        + '</thead>'
                        + '</tbody>'
                        + '<tbody>';
                    i = 0;
                    for (i = 0; i < data.records.length; i++) {
                        // $.each(records, function (index, value) {

                        gateway_id_name = data.records[i].gateway_id;
                        device_coin_name = data.records[i].nick_name;

                        var date = '';
                        if (data.records[i].updated_on != '') {
                            date = data.records[i].updated_on;
                            var stillUtc = moment.utc(date).toDate();
                            date = moment(stillUtc).local().format(date_format);
                        }

                        var now = moment().format(date_format);
                        ms = moment(now, date_format).diff(moment(date, date_format));
                        d = moment.duration(ms);
                        s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");

                        a = s.split(':');
                        hour = a[0];
                        minute = a[1];
                        second = a[2];

                        if (hour == 0 && minute == 0 && second < 60) {
                            last_seen_words = second + '<span>  &nbsp;seconds ago</span>';
                        }
                        else if (hour == 0 && minute > 0) {
                            last_seen_words = minute + '<span> &nbsp; minutes ago</span>';
                        }
                        else if (hour != 0) {
                            if (hour > 23) {
                                day = Math.floor(hour / 24);
                                if (day < 29) {
                                    last_seen_words = day + '<span> &nbsp;days ago</span>';
                                }
                                else {
                                    month = Math.floor(day / 30);
                                    if (month > 11) {
                                        year = Math.floor(month / 12);
                                        last_seen_words = year + '<span> &nbsp; years ago</span>';
                                    }
                                    else {
                                        last_seen_words = month + '<span> &nbsp; months ago</span>';
                                    }
                                }
                            }
                            else {
                                last_seen_words = hour + '<span> &nbsp; hours ago &nbsp;</span>';
                            }
                        }

                        device_low_battery_html += '<tr><td><div class="col-1 battery"><div><img src="../img/low_battery.png" width="30"></div></div></td>' +
                            '<td>' + gateway_id_name + '</td>' +
                            '<td>' + device_coin_name + '</td>' +
                            '<td>' + last_seen_words + '</td></tr>' +
                            '<br>';
                        // });
                    } //For Loop Ends Here   
                    device_low_battery_html += '</tbody></table>';
                }

                else {
                    device_low_battery_html += '<tr><td width="300">No Low Battery COINs Found. </td></tr>';
                }

                $('.CoinLowbatteryStatus').html(device_low_battery_html);

            },
            error: function (errData, status, error) {
                if (errData.status == 401) {
                    var resp = errData.responseJSON;
                    var description = resp.description;
                    $('.logout').click();
                    alert(description);

                }
            }
        });
    }
}


/* Get Gateway Nickname */
function getGateway_nickname(gateway_id, uid, apikey) {

    var gatewayId = gateway_id;
    var res_gw_nickname;

    $.ajax({
        url: basePathUser + apiGatewayDetail + '/' + gatewayId,
        type: 'GET',
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("uid", uid);
            xhr.setRequestHeader("Api-Key", apikey);
        },
        success: function (data, textStatus, xhr) {

            if (data.status == 'success') {
                if (data.records.length > 0) {
                    result = data.records;
                    var gateway_nick_name = result[0].gateway_nick_name;
                    if (gateway_nick_name != '') {
                        res_gw_nickname = gateway_nick_name;
                    } else if (gateway_nick_name === null || gateway_nick_name === 'null') {
                        res_gw_nickname = '';
                    } else {
                        res_gw_nickname = '';
                    }

                }
                else {
                    res_gw_nickname = '';
                }
            }

        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
            return;
        }

    });
    return res_gw_nickname;

}