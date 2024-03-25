////var basePathAdmin       = "http://localhost/sensegiz/admin/";
//var basePathAdmin       = "https://cm-testing.sensegiz.com/sensegiz-dev/portal/sensegiz-dev/admin/";
////var basePathAdmin       = "http://40.83.125.24/sensegiz/admin/";

basePathAdmin = getBasePath();

var apiUrlSendInvite = "sendinvite";

var apiUrlInvitedUsers = "invited-users";

var apiUrlAdminGateways = "gateways";

var apiUrlBlacklist = "blacklist-gateways";

var apiUrlAdminDevices = "devices";
var apiUrlDevicesBlacklist = "blacklist-devices";

var apiUrlRemoveGateways = "remove-gateways";
var apiUrlRemoveDevices = "remove-devices";

var apiUrlHardDeleteGateways = "hard-delete-gateways";
var apiUrlHardDeleteDevices = "hard-delete-devices";

var apiUrlAddUser = "add-user";

var apiUrlSendOTP = "send-otp";
var apiUrlRestoreGateway = "restore-gateway";
var apiUrlRestoreDevice = "restore-device";

var apiUpdateTimeFactor = "time-factor";

var apiUpdateGatewayID = "update-gateway-id-recover";
var apiGatewayDetail = "gateway-detail";

var apiUrlsoftDelDeviceToFrom = "soft-delete-device-data-to-from";

var apiCoinDetail = "coin-detail";
var apiUrlRecoverCoinUpdate = "update-coin-device-mac-address";

var apiUrlDeviceSizeTypeSeT = 'set-coin-type-size';

var apiEraseGatewayData = 'erase-gateway-data';

var apiRequestSensorType = 'request-sensor-type';

var apiUrlUpdateGatewayUser = "update-gateway-user";

var apiCheckUser = "check-gateway-user";

var apiDeleteUser = "delete-gateway-user";
var apiUpdateCoinOfflineTime = "update-coin-offline-time";
var apiUpdateUser1 = "update-email-gateway-user";

var apiUpdateUser_email = "update-email-gateway-userN"



function getBaseIPUser() {
    var baseurl = getBaseAddressUser();
    //   var baseurl = window.location.origin;
    var basePath = baseurl;

    return basePath;
}
function getBaseAddressUser() {
    // var baseurl  = getBaseAddressUser();
    var baseurl = window.location.origin;
    var basePath = baseurl;

    return basePath;
}

/*
 * Function 			: Click event on Send Invite
 * Brief 			: events which should happen while clicking on Send Invite button on users page	
 * Input param 			: event
 * Input/output param   	: NA
 * Return			: NA
 */
$(document).on("click", ".sendInvite", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');

    var user_email = $('#user_email').val();
    var user_phone = $('#user_phone').val();

    if (user_email == '') {
        $('.error').html('*Please enter your email id.');
        $('#user_email').focus();
        return false;
    }
    else {
        var regExp = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
        if (!regExp.test(user_email)) {
            $('.error').html('*Invalid email id.');
            $('#user_email').focus();
            return false;
        }
    }

    var admin_id = $('#admin_id').val();
    var u_id = $('#u_id').val();


    var succ_msg = 'Invite sent';
    if (u_id > 0) {
        succ_msg = 'User details updated';
    }

    var postdata = {
        user_email: user_email,
        user_phone: user_phone,
        admin_id: admin_id,
        u_id: u_id
    }
    $.ajax({
        url: basePathAdmin + apiUrlSendInvite,
        type: "POST",
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');

            $('.save_but').val('Please wait..');
        },
        success: function (data, textStatus, xhr) {
            $('.save_but').val('Send');
            if (xhr.status == 200 && textStatus == 'success') {
                if (data.status == 'success') {

                    $('#user_email').removeAttr('disabled');


                    $('#u_id').val(0);
                    $('#user_email').val('');
                    $('#user_phone').val('');
                    $('.success').html(succ_msg);
                    $('#message').val('');
                    getInvitedUsers();
                }
            } else {
                $('.error').html('Something went wrong');
            }
        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
        }
    });
});

/*
 * Function 			: getInvitedUsers()
 * Brief 			: load the list of Users in Users page	 
 * Input param 			: Nil
 * Input/output param           : NA
 * Return			: NA
 */
function getInvitedUsers() {
    $.get(basePathAdmin + apiUrlInvitedUsers, function (data) {
        var sc_html = '';
        var gatewayUserChange_html = '';
        if (data.records.length > 0) {
            records = data.records;
            var gatewayUserChange_html1 = [];
            $.each(records, function (index, value) {

                var email = value.user_email;
                var phone = value.user_phone;
                var uid = value.user_id;
                var coinofflinetime = value.coinofflinetime
                var date = timestampToDateTime(value.added_on);

                sc_html += '<tr>'
                    + '<td>' + (index + 1) + '</td>'
                    + '<td>' + email + '</td>'
                    + '<td>' + phone + '</td>'
                    + '<td>' + date + '</td>'
                    + '<td><input class = "updateCoinOfflineTimeText" placeholder="' + coinofflinetime + '" data-uid="' + uid + '  type="number"/> &nbsp; <button data-uid="' + uid + '" class = "updateCoinOfflineTime" type="submit">Submit</button></td>'
                    + '<td><span class="editUser" data-email="' + email + '" data-phone="' + phone + '" data-uid="' + uid + '"><img src="../img/edit.png" width="16" height="16"/></span></td>'
                   
                    + '<td><span class="updateUser" data-email="' + email + '" data-uid="' + uid + '"><img src="../img/edit.png" width="16" height="14"/></span></td>'
                    + '<td><span class="deleteUser" data-uid="' + uid + '"><img src="../img/delete-icon-png-19.jpg" width="16" height="14"/></span></td>'
                    //+'<td>a</td>'
                    + '</tr>';
                gatewayUserChange_html = '<option value=' + uid + '>' + email + '</option>';
                gatewayUserChange_html1.push(gatewayUserChange_html);
            });
        }
        else {
            sc_html = '<tr><td width="300">No Users Found</td></tr>';
            gatewayUserChange_html1 = '<option> No User </option>';
        }
        var getUsers_select = '<select name="txtgatewayUserChange" id="txtgatewayUserChange" class="form-select"><option>Select User</option>' + gatewayUserChange_html1 + '</select>';
        $('#invitedUsers').html(sc_html);
        $('#getUsers_select').html(getUsers_select);

        // Event listener for the button click
        $('.updateCoinOfflineTime').click(function () {
            // Find the input field associated with the clicked button
            var uid = $(this).data('uid');
            var $textField = $(this).siblings('.updateCoinOfflineTimeText');

            // Get the value from the input field
            var value = $textField.val();

            // Do whatever you need to do with the value here
            console.log("Value from text field:", uid);
            updateCoinOfflineTime(uid, value);

        });

    });
}

function updateCoinOfflineTime(uid,value) {
  
    var postdata = {
      u_id:uid,
      coinofflinetime: value,
    };
    
    console.log("----post data---> ",postdata);
  
    $.ajax({
      url: basePathAdmin + apiUpdateCoinOfflineTime,
      type: 'POST',     
      data: JSON.stringify(postdata),        
      contentType: 'application/json; charset=utf-8',
      dataType: 'text',
      async: false,                           
      beforeSend: function (xhr){                                
          xhr.setRequestHeader("admin", 'ADMIN');
      },
      success: function(data, textStatus, xhr) {
          console.log(data);
          
      }
  });
  }

/*
 * Function 			: getAdminGateways()
 * Brief 			: load the list of Gateways with user details	 
 * Input param 			: Nil
 * Input/output param           : NA
 * Return			: NA
 */
function getAdminGateways(searchval) {
    $('#loader').show();
    // var getFirmwareDetail = getAllFirmwares();
    // console.log('getFirmwareDetail',getFirmwareDetail);
    var postdata = {
        coin_mac: searchval,
    }
    $.ajax({
        url: basePathAdmin + apiUrlAdminGateways + '/' + searchval,
        type: 'GET',
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {
            $('#loader').hide();

            var sc_html = '';
            if (data.status == 'success') {
                if (data.records.length > 0) {
                    records = data.records;
                    $.each(records, function (index, value) {
                        var gateway_id = value.gateway_id;
                        var user_email = value.user_email;
                        var time_factor = value.time_factor;
                        var time_factor_x = value.time_factor_x;
                        var time_factor_y = value.time_factor_y;
                        var time_factor_z = value.time_factor_z;
                        var gateway_status = value.status;
                        var gateway_version = value.gateway_version;
                        var user_id = value.user_id;
                        var last_ping_alert = value.last_ping_alert;
                        var date = '';

                        if (gateway_status == 'Online') {
                            gateway_type_html = '&nbsp<i class="fa fa-wifi" aria-hidden="true" style="color:#05fb22;"></i>';
                        } else {
                            gateway_type_html = '&nbsp<i class="fa fa-wifi" aria-hidden="true" style="color: red;"></i>';
                        }

                        if (value.added_on != '') {
                            date = value.added_on;
                            var stillUtc = moment.utc(date).toDate();
                            date = moment(stillUtc).local().format('DD-MM-YYYY HH:mm:ss');
                        }

                        if (value.last_ping_alert != '') {
                            last_ping_alert = value.last_ping_alert;
                            var ping_stillUtc = moment.utc(last_ping_alert).toDate();
                            last_ping_alert = moment(ping_stillUtc).local().format('DD-MM-YYYY HH:mm:ss');
                        }

                        var is_blacklisted = value.is_blacklisted;
                        var bl_btn = '';
                        if (is_blacklisted == 'Y') {
                            bl_btn = '<button class="whitelist blbtn updateBlackList" data-status="N" data-gateway="' + gateway_id + '">Whitelist</button>';
                        }
                        else if (is_blacklisted == 'N') {
                            bl_btn = '<button class="blacklist blbtn updateBlackList" data-status="Y" data-gateway="' + gateway_id + '">Blacklist</button>';
                        }

                        var is_deleted = value.is_deleted;
                        var restore_btn = '';

                        if (is_deleted == 2) {
                            restore_btn = '<button type="button" class="btn btn-secondary restore-gateway" data-gateway="' + gateway_id + '">Restore</button>';
                        }
                        else if (is_deleted == 0) {
                            restore_btn = '<span class="glyphicon glyphicon-trash harddelGateway" data-gateway="' + gateway_id + '"></span>';
                        }

                        sc_html += '<tr>'
                            + '<td>' + (index + 1) + '</td>'
                            + '<td>' + gateway_type_html + ' &nbsp;<a href="devices-admin.php?id=' + gateway_id + '&uid=' + user_id + '">' + gateway_id + '<br /><span style="color: black;">' + last_ping_alert + '</span></a></td>'
                            + '<td >' + gateway_version + '</td>'
                            + '<td>' + user_email + '</td>'
                            + '<td>' + date + '</td>'
                            + '<td>' + bl_btn + '<span class="done grn"></span></td>'
                            + '<td><a href="#"> <span class="editTimeFactor" data-target="#modalEditTimeFactor" data-toggle="modal" data-gateway="' + gateway_id + '" data-xtime="' + time_factor_x + '" data-ytime="' + time_factor_y + '" data-ztime="' + time_factor_z + '"><span class="glyphicon glyphicon-edit"></span></span> </a></td>'
                            + '<td>' + restore_btn + '</td>'
                            + '</tr>';

                    });
                }
                else {
                    sc_html = '<tr><td width="300">No Gateways Found</td></tr>';
                }
                $('#adminGatewaysList').html(sc_html);
            }

        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
        }

    });
}


$(document).on("click", ".editUser", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');

    var user_email = $(this).data('email');
    var user_phone = $(this).data('phone');
    var uid = $(this).data('uid');

    $('#user_email').val(user_email);
    $('#user_phone').val(user_phone);
    $('#u_id').val(uid);

    $('#user_email').attr('disabled', 'disabled');

});
/*
* Function 			: Click event on BLACKLIST
* Brief 			: events which should happen while clicking on 'Blacklist' button in Gateways page
* Detail 			: events which should happen while clicking on 'Blacklist' button in Gateways page
* Input param 			: event
* Input/output param   	: NA
* Return			: NA
*/
$(document).on("click", ".updateBlackList", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');


    var gateway_id = $(this).data('gateway');
    var isBlacklisted = $(this).data('status');

    $this = $(this);

    var succ_msg = 'BLACKLISTED';

    var postdata = {
        gateway_id: gateway_id,
        blacklist_status: isBlacklisted
    }
    //                    return;
    var msg = '';
    if (isBlacklisted == 'Y') {
        msg = 'Are you sure to Blacklist?';
    }
    else if (isBlacklisted == 'N') {
        msg = 'Are you sure to Whitelist?';
    }
    var r = confirm(msg);

    if (r == true) {
        $.ajax({
            url: basePathAdmin + apiUrlBlacklist,
            type: 'POST',
            data: JSON.stringify(postdata),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("admin", 'ADMIN');

                $this.parent().find('.done').html('wait..');
            },
            success: function (data, textStatus, xhr) {

                if (xhr.status == 200 && textStatus == 'success') {
                    var msg = '';
                    if (isBlacklisted == 'Y') {
                        $this.removeClass('blacklist');
                        $this.addClass('whitelist');
                        $this.data('status', 'N');
                        $this.text('Whitelist');

                        $this.parent().find('.done').html('Blacklisted');
                    }
                    else if (isBlacklisted == 'N') {
                        $this.removeClass('whitelist');
                        $this.addClass('blacklist');
                        $this.data('status', 'Y');
                        $this.text('Blacklist');

                        $this.parent().find('.done').html('Whitelisted');
                    }

                    setTimeout(function () { $this.parent().find('.done').html(''); }, 5000);
                }
            },
            error: function (errData, status, error) {
                var resp = errData.responseJSON;
                $('.error').html(resp.description);
            }
        });
    } else {
        return false;
    }

});

/*
 * Function 			: getAdminDevices()
 * Brief 			: load the list of Devices	 
 * Input param 			: Nil
 * Input/output param           : NA
 * Return			: NA
 */
function getAdminDevices(gateway_id) {
    $('#loader').show();

    $.ajax({
        url: basePathAdmin + apiUrlAdminDevices + '/' + gateway_id,
        type: 'GET',
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {
            $('#loader').hide();

            var sc_html = '';
            if (data.status == 'success') {
                if (data.records.length > 0) {
                    records = data.records;
                    $.each(records, function (index, value) {
                        var device_id = value.device_id;
                        var gateway_id = value.gateway_id;
                        var device_mac = value.device_mac_address;
                        var coin_nick_name = value.nick_name;
                        var coin_type = value.coin_type;
                        var firmware_release_Date = value.firmware_release_date;
                        console.log('firmware_release_Date==', firmware_release_Date);
                        firmware_release_Date = new Date(firmware_release_Date).toISOString().slice(0, 10);
                        var firmware_type = value.firmware_type;
                        var firmware_version = value.firmware_version ? value.firmware_version : "-";
                        //                                            var user_email  =  value.user_email;
                        var date = '';
                        if (value.added_on != '') {
                            date = value.added_on;
                            var stillUtc = moment.utc(date).toDate();
                            date = moment(stillUtc).local().format('DD-MM-YYYY HH:mm:ss');
                        }

                        var is_blacklisted = value.is_blacklisted;
                        var bl_btn = '';
                        if (is_blacklisted == 'Y') {
                            bl_btn = '<button class="whitelist blbtn updateDeviceBlackList" data-status="N" data-gateway="' + gateway_id + '" data-device="' + device_id + '">Whitelist</button>';
                        }
                        else if (is_blacklisted == 'N') {
                            bl_btn = '<button class="blacklist blbtn updateDeviceBlackList" data-status="Y" data-gateway="' + gateway_id + '" data-device="' + device_id + '">Blacklist</button>';
                        }

                        var is_deleted = value.is_deleted;
                        var restore_btn = '';

                        if (is_deleted == 2) {
                            restore_btn = '<button type="button" class="btn btn-secondary restore-device" data-gateway="' + gateway_id + '" data-device="' + device_id + '">Restore</button>';
                        }
                        else if (is_deleted == 0) {
                            restore_btn = '<span class="glyphicon glyphicon-trash harddelDevice" data-gateway="' + gateway_id + '" data-device="' + device_id + '"></span>';
                        }


                        var val_checked;
                        var dType_option_html;
                        if (coin_type == '0') {
                            val_checked = 'selected';
                            dType_option_html = '<option value="0" ' + val_checked + ' data-deviceid="' + device_id + '" data-devicemac="' + device_mac + '" data-value="0">Not Assigned</option><option value="1" data-deviceid="' + device_id + '" data-devicemac="' + device_mac + '" data-value="1">Coin Pro</option><option value="2" data-deviceid="' + device_id + '" data-devicemac="' + device_mac + '" data-value="2">Coin Cell</option>';
                        } else if (coin_type == '1') {
                            val_checked = 'selected';
                            dType_option_html = '<option value="0" data-deviceid="' + device_id + '" data-devicemac="' + device_mac + '" data-value="0">Not Assigned</option><option value="1" ' + val_checked + ' data-deviceid="' + device_id + '" data-devicemac="' + device_mac + '" data-value="1">Coin Pro</option><option value="2" data-deviceid="' + device_id + '" data-devicemac="' + device_mac + '" data-value="2">Coin Cell</option>';
                        } else {
                            val_checked = 'selected';
                            dType_option_html = '<option value="0" data-deviceid="' + device_id + '" data-devicemac="' + device_mac + '" data-value="0">Not Assigned</option><option value="1" data-deviceid="' + device_id + '" data-devicemac="' + device_mac + '" data-value="1">Coin Pro</option><option value="2" ' + val_checked + ' data-deviceid="' + device_id + '" data-devicemac="' + device_mac + '" data-value="2">Coin Cell</option>';
                        }

                        sc_html += '<tr>'
                            + '<td>' + (index + 1) + '</td>'
                            + '<td>' + device_id + '</td>'
                            + '<td>' + coin_nick_name + '</td>'
                            + '<td><a href="device-info.php?gatewayid=' + gateway_id + '&deviceid=' + device_id + '&devicemacadd=' + device_mac + '">' + device_mac + '</a></td>'
                            + '<td>' + date + '</td>'
                            + '<td><select name="txtDeviceType" id="txtDeviceType" onchange="changeDeviceType(this);">' + dType_option_html + '</select><span class="dTypeDone grn"></span></td>'
                            + '<td>' + bl_btn + '<span class="done grn"></span></td>'
                            + '<td>' + firmware_release_Date + '</td>'
                            + '<td>' + firmware_type + '</td>'
                            + '<td>' + firmware_version + '</td>'
                            + '<td>' + restore_btn + '</td>'
                            + '</tr>';
                    });
                }
                else {
                    sc_html = '<tr><td width="300">No Devices Found</td></tr>';
                }
                $('#adminDevicessList').html(sc_html);
            }

        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
        }

    });
}



/*
 * Function             : onchange event on txtDeviceType
 * Brief                : events which should happen while clicking on 'txtDeviceType' select in Devices page
 * Detail               : events which should happen while clicking on 'txtDeviceType' select in Devices page
 * Input param          : event
 * Input/output param   : NA
 * Return               : NA
*/
function changeDeviceType(deviceTypeData) {
    var device_type_size = $(deviceTypeData).find(':selected').attr('data-value');
    var device_id = $(deviceTypeData).find(':selected').attr('data-deviceid');
    var device_mac_address = $(deviceTypeData).find(':selected').attr('data-devicemac');


    postdata = {
        device_type_size: device_type_size,
        device_id: device_id,
        device_mac_address: device_mac_address,
        gateway_id: gateway_id
    }

    if (device_type_size != '' && device_id != '' && device_mac_address != '' && gateway_id != '') {

        $.ajax({
            url: basePathAdmin + apiUrlDeviceSizeTypeSeT,
            type: 'POST',
            data: JSON.stringify(postdata),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("admin", 'ADMIN');
                $(deviceTypeData).parent().find('.dTypeDone').html('wait..');
            },
            success: function (data, textStatus, xhr) {
                if (xhr.status == 200 && textStatus == 'success') {
                    var msg = '';
                    $(deviceTypeData).parent().find('.dTypeDone').html('SET');
                } else {
                    $(deviceTypeData).parent().find('.dTypeDone').html('Not Set');
                }
                setTimeout(function () { $(deviceTypeData).parent().find('.dTypeDone').html(''); }, 5000);
            },
            error: function (errData, status, error) {
                var resp = errData.responseJSON;
                $('.error').html(resp.description);
            }
        });
    }

}



/*
* Function 			: Click event on BLACKLIST
* Brief 			: events which should happen while clicking on 'Blacklist' button in Devices page
* Detail 			: events which should happen while clicking on 'Blacklist' button in Devices page
* Input param 			: event
* Input/output param   	: NA
* Return			: NA
*/
$(document).on("click", ".updateDeviceBlackList", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');


    var device_id = $(this).data('device');
    var isBlacklisted = $(this).data('status');
    var gateway_id = $(this).data('gateway');

    $this = $(this);

    var succ_msg = 'BLACKLISTED';

    var postdata = {
        device_id: device_id,
        gateway_id: gateway_id,
        blacklist_status: isBlacklisted
    }
    //                    return;
    var msg = '';
    if (isBlacklisted == 'Y') {
        msg = 'Are you sure to Blacklist?';
    }
    else if (isBlacklisted == 'N') {
        msg = 'Are you sure to Whitelist?';
    }
    var r = confirm(msg);

    if (r == true) {
        $.ajax({
            url: basePathAdmin + apiUrlDevicesBlacklist,
            type: 'POST',
            data: JSON.stringify(postdata),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("admin", 'ADMIN');
                $this.parent().find('.done').html('wait..');
            },
            success: function (data, textStatus, xhr) {

                if (xhr.status == 200 && textStatus == 'success') {
                    var msg = '';
                    if (isBlacklisted == 'Y') {
                        $this.removeClass('blacklist');
                        $this.addClass('whitelist');
                        $this.data('status', 'N');
                        $this.text('Whitelist');

                        $this.parent().find('.done').html('Blacklisted');
                    }
                    else if (isBlacklisted == 'N') {
                        $this.removeClass('whitelist');
                        $this.addClass('blacklist');
                        $this.data('status', 'Y');
                        $this.text('Blacklist');

                        $this.parent().find('.done').html('Whitelisted');
                    }

                    setTimeout(function () { $this.parent().find('.done').html(''); }, 5000);
                }
            },
            error: function (errData, status, error) {
                var resp = errData.responseJSON;
                $('.error').html(resp.description);
            }
        });
    } else {
        return false;
    }

});

/*
* Function 			: Click event on Remove
* Brief 			: events which should happen while clicking on 'Delete' button in Gateways page
* Detail 			: events which should happen while clicking on 'Delete' button in Gateways page
* Input param 			: event
* Input/output param   	: NA
* Return			: NA
*/
$(document).on("click", ".delGateway", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');


    var gateway_id = $(this).data('gateway');

    $this = $(this);

    var succ_msg = 'BLACKLISTED';

    var postdata = {
        gateway_id: gateway_id
    }

    var r = confirm("Are you sure to Remove Gateway?");

    if (r == true) {
        $.ajax({
            url: basePathAdmin + apiUrlRemoveGateways,
            type: 'POST',
            data: JSON.stringify(postdata),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("admin", 'ADMIN');

                //                                $this.parent().find('.done').html('wait..');                                                                                              
            },
            success: function (data, textStatus, xhr) {

                if (xhr.status == 200 && textStatus == 'success') {
                    var searchval;
                    if (searchval === '') {
                        searchval = 'all';
                    }
                    getAdminGateways(searchval);
                }
            },
            error: function (errData, status, error) {
                var resp = errData.responseJSON;
                $('.error').html(resp.description);
            }
        });
    } else {
        return false;
    }

});


/*
* Function 			: Click event on Remove
* Brief 			: events which should happen while clicking on 'Delete' icon in Devices page
* Detail 			: events which should happen while clicking on 'Delete' icon in Devices page
* Input param 			: event
* Input/output param   	: NA
* Return			: NA
*/
$(document).on("click", ".delDevice", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');


    var device_id = $(this).data('device');
    var gateway_id = $(this).data('gateway');


    $this = $(this);

    var postdata = {
        device_id: device_id,
        gateway_id: gateway_id
    }

    var r = confirm("Are you sure to Remove Device?");

    if (r == true) {
        $.ajax({
            url: basePathAdmin + apiUrlRemoveDevices,
            type: 'POST',
            data: JSON.stringify(postdata),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("admin", 'ADMIN');
            },
            success: function (data, textStatus, xhr) {

                if (xhr.status == 200 && textStatus == 'success') {
                    getAdminDevices(gateway_id);
                }
            },
            error: function (errData, status, error) {
                var resp = errData.responseJSON;
                $('.error').html(resp.description);
            }
        });
    } else {
        return false;
    }

});


/*
     * Function 			: Click event on Remove
     * Brief 			: events which should happen while clicking on 'Hard Delete' button in Gateways page
     * Detail 			: events which should happen while clicking on 'Hard Delete' button in Gateways page
     * Input param 			: event
     * Input/output param   	: NA
     * Return			: NA
     */

$(document).on("click", ".harddelGateway", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');


    var gateway_id = $(this).data('gateway');

    $this = $(this);

    var succ_msg = 'BLACKLISTED';

    var postdata = {
        gateway_id: gateway_id
    }

    //send OTP
    $.ajax({
        url: basePathAdmin + apiUrlSendOTP,
        type: 'POST',
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {

            if (xhr.status == 200 && textStatus == 'success') {
                console.log("Success");
                //Verify OTP
                var otp = prompt("OTP has been sent to the admin. Please confirm the OTP", 0);

                if (otp == null || otp == '' || otp == 0) {
                    alert("Please enter the OTP.");
                    otp = prompt("Please enter the OTP :", 0);

                }
                console.log(otp);
                var deldata = {
                    gateway_id: gateway_id,
                    otp: otp
                }

                $.ajax({
                    url: basePathAdmin + apiUrlHardDeleteGateways,
                    type: 'POST',
                    data: JSON.stringify(deldata),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    async: false,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("admin", 'ADMIN');

                    },
                    success: function (data, textStatus, xhr) {
                        if (xhr.status == 200 && textStatus == 'success') {
                            if (data.status == 'success') {
                                var searchval;
                                if (searchval === '') {
                                    searchval = 'all';
                                }
                                getAdminGateways(searchval);
                            }
                            if (data.status == 'invalid_otp') {
                                $('.error').html("Invalid OTP.");
                                setTimeout(function () { $('.error').html(''); }, 5000);

                            }
                        }
                    },
                    error: function (errData, status, error) {

                        var resp = errData.responseJSON;
                        $('.error').html(resp.description);
                    }
                });

            }
        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
        }
    });

});



/*
* Function 			: Click event on Remove
* Brief 			: events which should happen while clicking on 'Hard Delete' icon in Devices page
* Detail 			: events which should happen while clicking on 'Hard Delete' icon in Devices page
* Input param 			: event
* Input/output param   	: NA
* Return			: NA
*/

$(document).on("click", ".harddelDevice", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');

    var device_id = $(this).data('device');
    var gateway_id = $(this).data('gateway');

    $this = $(this);

    var postdata = {
        device_id: device_id,
        gateway_id: gateway_id
    }

    //send OTP
    $.ajax({
        url: basePathAdmin + apiUrlSendOTP,
        type: 'POST',
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {

            if (xhr.status == 200 && textStatus == 'success') {
                console.log("Success");
                //Verify OTP
                var otp = prompt("OTP has been sent to the admin. Please confirm the OTP", 0);

                if (otp == null || otp == '' || otp == 0) {
                    alert("Please enter the OTP.");
                    otp = prompt("Please enter the OTP :", 0);

                }

                var deldata = {
                    gateway_id: gateway_id,
                    device_id: device_id,
                    otp: otp
                }

                $.ajax({
                    url: basePathAdmin + apiUrlHardDeleteDevices,
                    type: 'POST',
                    data: JSON.stringify(deldata),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    async: false,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("admin", 'ADMIN');
                    },
                    success: function (data, textStatus, xhr) {

                        if (xhr.status == 200 && textStatus == 'success') {
                            if (data.status == 'success') {
                                getAdminDevices(gateway_id);
                            }

                            if (data.status == 'invalid_otp') {
                                $('.error').html("Invalid OTP.");
                                setTimeout(function () { $('.error').html(''); }, 5000);

                            }
                        }
                    },
                    error: function (errData, status, error) {
                        var resp = errData.responseJSON;
                        $('.error').html(resp.description);
                    }
                });
            }

        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
        }
    });

});



$(document).on("click", ".addUser", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');

    var user_email = $('#user_email').val();
    var user_phone = $('#user_phone').val();
    var user_pass = $('#user_pass').val();
    var user_cpass = $('#user_cpass').val();


    if (user_email == '') {
        $('.error').html('*Please enter your email id.');
        $('#user_email').focus();
        return false;
    }
    else {
        var regExp = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
        if (!regExp.test(user_email)) {
            $('.error').html('*Invalid email id.');
            $('#user_email').focus();
            return false;
        }
    }

    if (user_pass == '') {
        $('.error').html('*Please enter your Password.');
        $('#user_pass').focus();
        return false;
    }

    if (user_cpass == '') {
        $('.error').html('*Please re-confirm your Password.');
        $('#user_cpass').focus();
        return false;
    }

    if (user_cpass != user_pass) {
        $('.error').html('*Passwords do not match. Please check!');
        $('#user_pass').focus();
        return false;
    }


    var admin_id = $('#admin_id').val();
    var u_id = $('#u_id').val();

    var postdata = {
        user_email: user_email,
        user_phone: user_phone,
        admin_id: admin_id,
        u_id: u_id,
        user_pass: user_pass
    }

    $.ajax({
        url: basePathAdmin + apiUrlAddUser,
        type: 'POST',
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
            $('.save_but').val('Please wait..');
        },
        success: function (data, textStatus, xhr) {

            if (xhr.status == 200 && textStatus == 'success') {
                if (data.status == 'success') {
                    $('#user_email').removeAttr('disabled');
                    $('#u_id').val(0);
                    $('#user_email').val('');
                    $('#user_phone').val('');

                    $('.success').html('The user has been successfully added!');
                    $('#message').val('');
                    $('.save_but').val('Add User');
                    $('#user_pass').val('');
                    $('#user_cpass').val('');

                    getInvitedUsers();
                }
            } else {
                $('.error').html('Something went wrong');
            }
        },
        error: function (errData, status, error) {
            console.log(error);
            console.log(errData);
            var resp = errData.responseJSON;
            $('.error').html(resp);
            $('.save_but').val('Add User');

        }
    });

});


$(document).on("click", ".restore-gateway", function (e) {

    var gateway_id = $(this).data('gateway');

    $this = $(this);

    var postdata = {
        gateway_id: gateway_id
    }


    $.ajax({
        url: basePathAdmin + apiUrlRestoreGateway,
        type: "POST",
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {

            if (xhr.status == 200 && textStatus == 'success') {
                if (data.status == 'success') {
                    var searchval;
                    if (searchval === '') {
                        searchval = 'all';
                    }
                    getAdminGateways(searchval);
                }
            }
        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);

        }
    });

});


$(document).on("click", ".restore-device", function (e) {

    var device_id = $(this).data('device');
    var gateway_id = $(this).data('gateway');

    $this = $(this);

    var postdata = {
        device_id: device_id,
        gateway_id: gateway_id
    }


    $.ajax({
        url: basePathAdmin + apiUrlRestoreDevice,
        type: "POST",
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {

            if (xhr.status == 200 && textStatus == 'success') {
                if (data.status == 'success') {
                    getAdminDevices();
                }
            }
        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);

        }
    });

});

$(document).on("click", ".editTimeFactor", function (e) {

    e.preventDefault();
    $('.success,.error').html('');

    var gateway_id = $(this).data('gateway');
    var xtime = $(this).data('xtime');
    var ytime = $(this).data('ytime');
    var ztime = $(this).data('ztime');

    $('#gateway').val(gateway_id);
    $('#xtime').val(xtime);
    $('#ytime').val(ytime);
    $('#ztime').val(ztime);

});


$(document).on("click", ".updatetimefactor", function (e) {

    var gateway_id = $('#gateway').val();

    var timefactor = $('#timefactor').val();

    var xtime = $('#xtime').val();
    var ytime = $('#ytime').val();
    var ztime = $('#ztime').val();


    if (xtime == '' || ytime == '' || ztime == '') {
        $('.errortf').html('*Time Factors cannot be empty.');
        $('#location').focus();
        return false;

    }

    var postdata = {
        gateway_id: gateway_id,
        x_time_factor: xtime,
        y_time_factor: ytime,
        z_time_factor: ztime
    }

    $.ajax({
        url: basePathAdmin + apiUpdateTimeFactor,
        type: "POST",
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {

            if (xhr.status == 200 && textStatus == 'success') {
                if (data.status == 'success') {
                    $('.errortf').html('');
                    $('.successtf').html("The time factor has been updated!");
                    setTimeout(function () { $('.successtf').html(''); }, 5000);
                    var searchval;
                    if (searchval === '') {
                        searchval = 'all';
                    }
                    getAdminGateways(searchval);
                }
            }
        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);

        }
    });


});



/* Get Gateway info */
function validateGateway(gatewayId) {
    // $('#loader').show();

    $.ajax({
        url: basePathAdmin + apiGatewayDetail + '/' + gatewayId,
        type: 'GET',
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {
            var sc_html = '';

            if (data.status == 'success') {
                if (data.records.length > 0) {
                    result = data.records;
                    /*$.each(records, function (index, value) {
                        var gateway_id  =  value.gateway_id;
                        var port = value.port;
                        var user_id  =  value.user_id;
                        var time_factor = value.time_factor;
                        var time_factor_x = value.time_factor_x;
                        var time_factor_y = value.time_factor_y;
                        var time_factor_z = value.time_factor_z;
                        var gateway_status = value.status;
                        var gateway_type = value.gateway_type;
                        var date        =  '';
                        
                    });*/

                    sc_html = 'The gateway ID is already exists in Database.';
                    $('#gatewayValidateMsg').html(sc_html);
                    document.getElementById("btnupdateGateway").disabled = true;
                    document.getElementById("chkConfGatewayUpdate").disabled = true;
                }
                else {
                    sc_html = 'You can proceed';
                    $('#gatewayValidateMsg').html(sc_html);
                    document.getElementById("btnupdateGateway").disabled = false;
                    document.getElementById("chkConfGatewayUpdate").disabled = false;
                }
            }

        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
        }

    });
}

$(document).on("click", ".btnupdateGatewayUser", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');

    var gateway_id = $('#txtoldGatewayName').val();
    var new_user_id = $('#txtgatewayUserChange').val();
    var postdata = {
        gateway_id: gateway_id,
        user_id: new_user_id
    }

    $.ajax({
        url: basePathAdmin + apiUrlSendOTP,
        type: 'POST',
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {

            if (xhr.status == 200 && textStatus == 'success') {
                console.log("Success");
                var otp = prompt("OTP has been sent to the admin. Please confirm the OTP", 0);

                if (otp == null || otp == '' || otp == 0) {
                    alert("Please enter the OTP.");
                    otp = prompt("Please enter the OTP :", 0);
                }

                var changeUserData = {
                    gateway_id: gateway_id,
                    user_id: new_user_id,
                    otp: otp
                }

                $.ajax({
                    url: basePathAdmin + apiUrlUpdateGatewayUser,
                    type: 'POST',
                    data: JSON.stringify(changeUserData),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    async: false,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("admin", 'ADMIN');
                    },
                    success: function (data, textStatus, xhr) {
                        console.log('data', data);
                        if (xhr.status == 200 && textStatus == 'success') {
                            if (data.status == 'success') {
                                alert('Updated Successfully');
                                window.location.href = "gateways-admin.php";
                                // return false;
                            }

                            if (data.status == 'invalid_otp') {
                                $('.error').html("Invalid OTP.");
                                setTimeout(function () { $('.error').html(''); }, 5000);

                            }
                        }
                    },
                    error: function (errData, status, error) {
                        var resp = errData.responseJSON;
                        $('.error').html(resp.description);
                    }
                });
            }

        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
        }
    });

});


$(document).on("click", ".btnupdateGatewayMeshId", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');

    var gatewayId = $('#new_gateway_id').val();
    var meshId = $('#meshId').val();
    var postdata = {
        gatewayId: gatewayId,
        meshId: meshId
    }
    console.log("postdata  mesh==", postdata)
    $.ajax({
        url: "https://cm.sensegiz.com:9000/setGatewayMeshId",
        type: 'POST',
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {
            console.log('data', data);
            if (xhr.status == 200 && textStatus == 'success') {
                if (data.status == 'success') {
                    alert('Mesh Id Updated Successfully');
                    window.location.href = "gateways-admin.php";
                    // return false;
                }
            }
        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
        }
    });


});



$(document).on("click", ".btnAddDevices", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');
    var apiPathForDevices;
    var baseUrlPath = getBaseAddressUser();
    var onlyGateway = $('#onlyGatewayAdd').val();
    var user_id;
    var gateway_id = $('#new_gateway_id').val();
    var coin_mac_id = $('#coin_mac_id').val();
    var coin_nickname = $('#coin_nickname').val();
    var postdata;
    if (onlyGateway == "true") {
        user_id = $('#txtgatewayUserChange').val();
        postdata = {
            user_id: user_id,
            gateway_id: gateway_id
        }
        apiPathForDevices = 'gateways';
    } else {
        user_id = $('#userIdForCoinAdd').val();
        postdata = {
            user_id: user_id,
            gateway_id: gateway_id,
            device_mac_address: coin_mac_id,
            nick_name: coin_nickname
        }
        apiPathForDevices = 'devices';
    }

    $.ajax({
        url: baseUrlPath + '/sensegiz-dev/app/' + apiPathForDevices,
        type: 'POST',
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {

            if (xhr.status == 200 && textStatus == 'success') {
                alert('Devices Added Successfully');
                window.location.href = "gateways-admin.php";
            }

        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
        }
    });

});


// Update Gateway: Recovery Strategy
$(document).on("click", ".btnupdateGateway", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');

    var old_gateway_id = $('#txtoldGatewayName').val();
    var new_gateway_id = $('#txtNewGatewayName').val();

    var postdata = {
        old_gateway_id: old_gateway_id,
        new_gateway_id: new_gateway_id
    }

    $.ajax({
        url: basePathAdmin + apiUpdateGatewayID,
        type: "POST",
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
            $('.btnupdateGateway').val('Please wait..');
        },
        success: function (data, textStatus, xhr) {
            if (xhr.status == 200 && textStatus == 'success') {
                if (data.status == 'success') {
                    alert('Gateway Updated Successfully.!');
                    location.href = "gateways-admin.php";
                }
            } else {
                $('.error').html('Something went wrong');
            }
        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
        }
    });

});


// Soft Delete Device Data between two dates
$(document).on("click", ".btnDeviceDataDelToFrom", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');

    $this = $(this);
    var gateway_id = $('#txtGatewayID').val();
    var device_id = $('#txtDeviceID').val();
    var sensor_type = document.querySelector('input[name = "txtSensorType"]:checked').value;
    var txtDeviceMacAdd = $('#txtDeviceMacAdd').val();
    var txtFromDate = $('#txtFromDate').val();
    var txtToDate = $('#txtToDate').val();

    if (txtFromDate == '' || txtToDate == '') {
        alert('Fields are Mandatory');
        return false;
    }

    var days_diff = days_between(txtToDate, txtFromDate);
    if (days_diff > '15') {
        alert("You can import max 31 Days Data. Please change the Date");
        return;
    } else if (days_diff <= '0') {
        alert("TO Date should be greater than FROM Date");
        return;
    }

    if (sensor_type == '') {
        alert('Fields are Mandatory');
        return;
    }

    var postdata = {
        device_id: device_id,
        gateway_id: gateway_id,
        sensor_type: sensor_type,
        txtFromDate: txtFromDate,
        txtToDate: txtToDate
    }

    //send OTP
    $.ajax({
        url: basePathAdmin + apiUrlSendOTP,
        type: 'POST',
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {

            if (xhr.status == 200 && textStatus == 'success') {
                //Verify OTP
                var otp = prompt("OTP has been sent to the admin. Please confirm the OTP", 0);

                if (otp == null || otp == '' || otp == 0) {
                    alert("Please enter the OTP.");
                    otp = prompt("Please enter the OTP :", 0);
                }

                var deldata = {
                    gateway_id: gateway_id,
                    device_id: device_id,
                    sensor_type: sensor_type,
                    otp: otp,
                    txtFromDate: txtFromDate,
                    txtToDate: txtToDate
                }

                $.ajax({
                    url: basePathAdmin + apiUrlsoftDelDeviceToFrom,
                    type: 'POST',
                    data: JSON.stringify(deldata),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    async: false,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("admin", 'ADMIN');
                    },
                    success: function (data, textStatus, xhr) {
                        console.log('data', data);
                        if (xhr.status == 200 && textStatus == 'success') {
                            if (data.status == 'success') {
                                alert('Delete Operation Successfully');
                                location.reload();
                                return false;
                            }

                            if (data.status == 'invalid_otp') {
                                $('.error').html("Invalid OTP.");
                                setTimeout(function () { $('.error').html(''); }, 5000);

                            }
                        }
                    },
                    error: function (errData, status, error) {
                        var resp = errData.responseJSON;
                        $('.error').html(resp.description);
                    }
                });
            }

        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
        }
    });

});



function validateCoin(coinMacAddr) {
    // $('#loader').show();
    console.log('coinMacAddr:', coinMacAddr);

    $.ajax({
        url: basePathAdmin + apiCoinDetail + '/' + coinMacAddr,
        type: 'GET',
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {
            var sc_html = '';

            if (data.status == 'success') {
                if (data.records.length > 0) {
                    result = data.records;

                    sc_html = 'COIN is already exists in Database.';
                    $('#coinValidateMsg').html(sc_html);
                    document.getElementById("chkConfcoinUpdate").disabled = true;
                    document.getElementById("btnRecoverCoinDevice").disabled = true;
                }
                else {
                    sc_html = 'You can proceed';
                    $('#coinValidateMsg').html(sc_html);
                    document.getElementById("chkConfcoinUpdate").disabled = false;
                    document.getElementById("btnRecoverCoinDevice").disabled = false;
                }
            }

        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
        }

    });
}



// Recover the COIN in case of damage with new COIN
$(document).on("click", ".btnRecoverCoinDevice", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');

    $this = $(this);
    var gateway_id = $('#txtGatewayID').val();
    var device_id = $('#txtDeviceID').val();
    var new_coindevice_mac = $('#txtMACaddress').val();
    var old_coindevice_mac = $('#txtDeviceMacAdd').val();

    var postdata = {
        device_id: device_id,
        gateway_id: gateway_id,
        new_coindevice_mac: new_coindevice_mac,
        old_coindevice_mac: old_coindevice_mac
    }

    //send OTP
    $.ajax({
        url: basePathAdmin + apiUrlSendOTP,
        type: 'POST',
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {

            if (xhr.status == 200 && textStatus == 'success') {
                console.log("Success");
                var otp = prompt("OTP has been sent to the admin. Please confirm the OTP", 0);

                if (otp == null || otp == '' || otp == 0) {
                    alert("Please enter the OTP.");
                    otp = prompt("Please enter the OTP :", 0);
                }

                var updateCoindata = {
                    gateway_id: gateway_id,
                    device_id: device_id,
                    new_coindevice_mac: new_coindevice_mac,
                    old_coindevice_mac: old_coindevice_mac,
                    otp: otp
                }
                console.log('updateCoindata', updateCoindata);

                $.ajax({
                    url: basePathAdmin + apiUrlRecoverCoinUpdate,
                    type: 'POST',
                    data: JSON.stringify(updateCoindata),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    async: false,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("admin", 'ADMIN');
                    },
                    success: function (data, textStatus, xhr) {
                        console.log('data', data);
                        if (xhr.status == 200 && textStatus == 'success') {
                            if (data.status == 'success') {
                                alert('Updated Successfully');
                                window.location.href = "gateways-admin.php";
                                // return false;
                            }

                            if (data.status == 'invalid_otp') {
                                $('.error').html("Invalid OTP.");
                                setTimeout(function () { $('.error').html(''); }, 5000);

                            }
                        }
                    },
                    error: function (errData, status, error) {
                        var resp = errData.responseJSON;
                        $('.error').html(resp.description);
                    }
                });
            }

        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
        }
    });

});



function days_between(txtToDate, txtFromDate) {

    const _MS_PER_DAY = 1000 * 60 * 60 * 24;
    const diffInMs = new Date(txtToDate) - new Date(txtFromDate);
    const diffInDays = Math.floor(diffInMs / _MS_PER_DAY);
    return diffInDays;
}


/*
 * Function             : eraseGatewayData()
 * Brief                : Erase Gateway Data
 * Input param          : GatewayID
 * Input/output param   : NA
 * Return               : GatewayVersion
*/
function eraseGatewayData(data) {
    var gateway_id = $(data).data('gateway_id');
    console.log('gateway_id fun', gateway_id);

    postdata = {
        gateway_id: gateway_id
    }

    $.ajax({
        url: basePathAdmin + apiEraseGatewayData,
        type: 'POST',
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {

            if (xhr.status == 200 && textStatus == 'success') {
                console.log('data:', data);

                if (data.status == 'success') {
                    result = data.status;
                }
                else {
                    result = 'Fail';
                }
            }
            else {
                result = 'Fail';
            }

        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
        }

    });

    return result;
}


/*
 * Function             : getCurrentSensorType()
 * Brief                : Get Sensor type on click event
 * Input param          : GatewayID,device_id
 * Input/output param   : NA
 * Return               : SensorType
*/
function getCurrentSensorType(data) {
    var gateway_id = $(data).data('gateway_id');
    var device_id = $(data).data('device_id');
    console.log('gateway_id fun', gateway_id);
    // debugger;
    postdata = {
        gateway_id: gateway_id,
        device_id: device_id
    }

    $.ajax({
        url: basePathAdmin + apiRequestSensorType,
        type: 'POST',
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {

            if (xhr.status == 200 && textStatus == 'success') {
                console.log('data:', data);

                if (data.status == 'success') {
                    result = data.status;
                    alert("Request sent successfully!");
                }
                else {
                    result = 'Fail';
                }
            }
            else {
                result = 'Fail';
            }

        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
        }

    });

    return result;
}



/* Delete User */
$(document).on("click", ".deleteUser", function (e) {
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');

    var uid = $(this).data('uid');


    $.ajax({
        url: basePathAdmin + apiCheckUser + '/' + uid,
        type: 'GET',
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {
            console.log(data, textStatus);
            if (xhr.status == 200 && textStatus == 'success') {

                if (data.status == 'success') {
                    result = data.status;

                    if (data.records == 0) {

                        var r = confirm(" PLEASE CONFIRM YOU WANT TO DELETE THIS USER?");
                        if (r == true) {
                            deleteUser(uid);
                        }
                    } else {
                        alert(" USER HAS A GATEWAY.! YOU CAN'T DELETE THIS USER");
                    }
                }
                else {
                    result = 'Fail';
                }
            }
            else {
                result = 'Fail';
            }

        },
        error: function (errData, status, error) {
            var resp = errData.responseJSON;
            $('.error').html(resp.description);
        }


    });

});


function deleteUser(uid) {

    $.ajax({
        url: basePathAdmin + apiDeleteUser + '/' + uid,
        type: 'GET',

        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {
            console.log(data);
            getInvitedUsers();
            alert("USER DELETED SUCCESSFULLY");

        }



    });

    return;
}

$(document).on("click", "#cancel", function (e) {
    $('#form-email').hide();
    document.getElementById("mm").style.display = "none";
    $('#mm').hide();
    $('#nn').hide();




});



var u_id;
/* Update User */
$(document).on("click", ".updateUser", function (e) {
    $('#form-email').show();
    var user_emailo = $(this).data('email');
    u_id = $(this).data('uid');
    console.log(user_emailo);
    $("#u_email").val(user_emailo);
    $('#u_email').attr('disabled', 'disabled');
    e.preventDefault();
    $('.success').html('');
    $('.error,.error-tab').html('');
    $('#user_email_new').keypress(function () {

        $('#mm').hide();
        $('#nn').hide();
    });

});





$(document).on("click", "#save_but_updateUser", function (e) {
    console.log("hii");
    // $('#form-email').show();
    var user_email = $('#user_email_new').val();
    console.log(user_email);
    console.log(u_id);

    if (user_email == "" || user_email == undefined) {
        // alert("PLEASE ENTER USERNAME");
        // $('#mm').show();
        $('#nn').hide();
        $("#mm").effect("shake", { direction: "right", times: 6, distance: 10 }, 20);

        return
    }
    postdata = {
        user_email: user_email,
        u_id: u_id
    };
    console.log('postdata==', postdata);
    $.ajax({
        url: basePathAdmin + apiUpdateUser1,
        type: 'POST',
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {
            console.log(data);
            if (data.records == 0) {
                UpdateUser_email();
            }

            else {
                // alert("USER NAME ALREADY EXISTS!!");
                // $('#form-email').show();
                $('#mm').hide();

                $("#nn").effect("shake", { direction: "right", times: 6, distance: 10 }, 20);

                // $('#nn').show();
            }

        }
    });

});




function UpdateUser_email() {
    var user_email = $('#user_email_new').val();
    console.log("user_email==", user_email);

    console.log(user_email);
    console.log(u_id);

    postdata = {
        user_email: user_email,
        u_id: u_id
    };
    $.ajax({
        url: basePathAdmin + apiUpdateUser_email,
        type: 'POST',
        data: JSON.stringify(postdata),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("admin", 'ADMIN');
        },
        success: function (data, textStatus, xhr) {
            console.log(data);
            $('#mm').hide();
            $('#nn').hide();
            alert("UPDATED SUCCESSFULLY");
            getInvitedUsers();
            $('#form-email').hide();

        }



    });

    return;
}