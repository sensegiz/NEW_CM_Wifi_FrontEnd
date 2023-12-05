<?php 

include_once('page_header_user.php');

$status_msg  =  '';
$id  =   0;

?>

        <div class="content userpanel">

<?php 
    include './user_menu.php';
?>
<style>
@media only screen and (min-width: 564px) and (max-width:767px){

.col-sm-3 {
    width: 48%;
    
}
}
@media only screen and (min-width: 768px) and (max-width:1076px){

.col-sm-3 {
    width: 33%;
    
}
}

.dropdown-menu {
    width: 100% !important;
}

</style>

 <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
        	
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" type="text/css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

<link rel="stylesheet" href="../css/custom_style.css">

	    <div class="col-sm-10 col-10 mains"> 
<!--toggle sidebar button-->
           <p class="visible-xs">
            <button type="button" class="btn btn-default btn-sm" id="more" id="menu-toggle" data-toggle="offcanvas" style="margin-bottom:10px"><span  class="glyphicon glyphicon-chevron-left"></span> Gateways </button>
          </p>

            <div class="detail-content" style="background-color: #fff;">

                <div class="lp-det" style="margin-top:30px">
                    	
                        <span class='error-tab'></span>
                        
                        <div class="">
                        	<h3><b>Report Generation:-</b></h3> Based on Particular Gateway, Multiple COINs and Multiple Sensor Types -  
                            <h1><span id="gateway_name" style="font-size:20px"> </span></h1> <hr>

                            <div class="mine"></div>                
                            <div class="detail-content" style="background-color: #fff;">
                                <div class=" col-4 alertbar">
                                   
                                </div>
                             
                                <span class='error-tab'></span>
                                <div class="row coins" style="margin-left:20px">
                                	
                                    <div class="showclicktext"></div>
                                </div><br/>

                                <div class="row sensor-list" style="margin-left:20px">
                                
                                </div>
                                <br />
                                <div class="row " style="margin-left: 20px;">
                                    <div class="col-sm-3 duration-time">
                                    	
                                    </div>
                                    <div class="col-sm-3 excel-type">
                                    	
                                    </div>
                                </div>

                                <div class="row customrdate" id="customrdate" style="display:none; margin-left: 20px;">
                                
                                </div>

                                <div class="row download-btn" style="margin-left: 20px;">
                                    
                                </div>

                                <div class=" devicesTables"> 
                                    <div id='loader'><img src="../img/loader.gif"/> Loading Data</div>
                                </div>
                                                               
                            </div>
                        </div>
    
                </div>                
         
            </div>
        </div>

    </div>
  </div>

 
<?php
    include_once('page_footer_user.php');
?>
<script src="http://momentjs.com/downloads/moment.js"></script>

<link rel="stylesheet" href="../css/jquery-ui-timepicker-addon.css" type="text/css"/>
<script src="../js/jquery-ui-timepicker-addon.js" type="text/javascript"></script>

<script type="application/javascript">

var server_address = getBaseAddressUser();

	basePathUser      =   server_address+"/sensegiz-dev/user/";
	var reportscsvdownloaddata = 'reports-download-csv-data';
    getGatewaySettings();

	GetGatewayList();
 	/*
     * Function             : GetGatewayList()
     * Brief            : load the list of Gateways  
     * Input param          : Nil
     * Input/output param           : NA
     * Return           : NA
     */     
    function GetGatewayList() {

            $('#loader').show();

            var uid     =  $('#sesval').data('uid');
            var apikey  =  $('#sesval').data('key');
            if(uid!='' && apikey!=''){
                    $.ajax({
                            url: basePathUser + apiUrlGateways,
                            headers: {
                                    'uid':uid,
                                    'Api-Key':apikey
                            },                            
                            type: 'GET',                        
                            contentType: 'application/json; charset=utf-8',
                            dataType: 'json',
                            async: false,                           
                            beforeSend: function (xhr){                                
                                xhr.setRequestHeader("uid", uid);
                                xhr.setRequestHeader("Api-Key", apikey);                                
                            },
                            success: function(data, textStatus, xhr) {
                                $('#loader').hide();

                                var sc_html =   '';
                                var gw_li_html =   '';
                                if(data.records.length > 0){
                                    records = data.records;
                                    console.log('Gateways List:',records);
                                    $.each(records, function (index, value) {
                                        var gateway_id  = value.gateway_id;
                                        var status = value.status;
                                        
                    					if(status == 'Online'){
                    						status_html = '&nbsp&nbsp&nbsp<img src="../img/online.png"/>';
                    					}else{
                    						status_html = '&nbsp&nbsp&nbsp<img src="../img/offline.png"/>';
                    					}

                                        sc_html  =  '<tr><td>Click on any Gateway</td></tr>';

                                        gw_li_html += '<li><a href="javascript:;" class="gwlist" data-gateway="'+gateway_id+'">'+gateway_id+''+status_html+'</a></li>';
                                    });                        
                                }
                                else{
                                        sc_html  =  '<tr><td width="300">No Gateways Found</td></tr>';
                                }

                                $('.gateway-list-lfnav').html(gw_li_html);
                                $('.deviceSettingsTables').html(sc_html);

                            },
                            error: function(errData, status, error){
                                    if(errData.status==401){
                                        var resp = errData.responseJSON; 
                                        var description = resp.description; 
                                        $('.logout').click();
                                        alert(description);
                                        
                                    }
                            }                            
                        });
            }
    }

    
    // Get Coins List
    $(document).on( "click", ".gwlist",function(e){
        e.preventDefault();
                
        var gatewayId  =  $(this).data('gateway');
        $('.devicesTables').html('');
        $('.coin-list').html('');
        $('.sensor-list').html('');
        $('.duration-time').html('');
        $('.excel-type').html('');
        $('.download-btn').html('');
        $('.coins').html('');
        $('.dateTimeInputs').hide();

        localStorage.setItem("coins_filter", null);
        localStorage.setItem("sensors_filter", null);
        localStorage.setItem("gateway_id", gatewayId);
        localStorage.setItem("panel_list", null);       

        if(gatewayId!=''){
            $('.gateway-list-lfnav').find('li').find('a').removeClass('ancYellow');
            $(this).addClass('ancYellow');
                    
            //Remove old notifications        
           $('.detail-content').find('.fixfooter').find('.notifyline').html('');         
           $('.detail-content').find('.fixfootere').find('.notifyline').html('');   

            getReportDashboardCoin(gatewayId);      
                            
            // getDevices(gatewayId, 0, 0);
            // getAccStreamDevices(gatewayId, 0);

        }
    });




function getReportDashboardCoin(gatewayId) 
{
    var uid     =  $('#sesval').data('uid');
    var apikey  =  $('#sesval').data('key');

    document.getElementById("customrdate").style.display = "none";

    if(uid!='' && apikey!=''){
        $.ajax({
                url: basePathUser + apiUrlGetCoin + '/' + gatewayId,
                    headers: {
                              'uid':uid,
                              'Api-Key':apikey
                              },                            
                    type: 'GET',                        
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    async: false,                           
                    beforeSend: function (xhr){                                
                        xhr.setRequestHeader("uid", uid);
                        xhr.setRequestHeader("Api-Key", apikey);                                
                    },
                    success: function(data, textStatus, xhr) {

                        var gw_split = gatewayId.split(",");

                        var coin_li_html = '';
                        var i = 0;

                        var y = gw_split.length;
                        
                        var gateway_name = gatewayId;
                        $('#gateway_name').html(gateway_name);

                        for(i=0; i < y; i++) 
                        {
                            var j = data.records[gw_split[i]].length;
                            
                            if(data.records[gw_split[i]][0][0] == "no_coin"){
                                coin_li_html = '    There are no coins under this Gateway.';

                                $('.coins').html(coin_li_html);
                                return; 
                            }

                            
                            var q = 0;
                            var coin_html = '';

                            coin_html += '<div class="col-sm-12 coin"><h5><strong>Select COINs: <span style="color: red">*</span></strong></h5><select multiple name="txtcoin" id="txtcoin" class="coin-list"></select></div>';
                            $('.coins').html(coin_html);

                            $.each(data.records[gw_split[i]], function (index, value) {
                                var gd_id = data.records[gw_split[i]][q]['gd_id'];
                                var gateway_id = data.records[gw_split[i]][q]['gateway_id'];
                                var device_id = data.records[gw_split[i]][q]['device_id'];
                                var nick_name = data.records[gw_split[i]][q]['nick_name'];                                                      

                                coin_li_html += '<option value="'+device_id+'">'+nick_name+'</option>';
                                
                                q++;
                            });
                
                        }
                        $('.coin-list').html(coin_li_html);
                        
                        var sensor_li_html = '<h5 style="margin-left: 10px;"><strong>Select Sensor Type: <span style="color: red">*</span></strong></h5>' +
                                            '<div class="col-sm-3">' +
                                                '<div class="funkyradio"><div class="funkyradio-success">' +
                                                '<input type="hidden" value="'+gatewayId+'" id="txtGateway" name="txtGateway" />' +
                                                '<input type="checkbox" value="01" id="txtSensorType2" name="txtSensorType" />' +
                                                '<label for="txtSensorType2">Accelerometer</label>' +
                                                '</div></div>' +
                                            '</div>' +
                                            '<div class="col-sm-3">' +
                                                '<div class="funkyradio"><div class="funkyradio-success">' +
                                                '<input type="checkbox" value="03" id="txtSensorType3" name="txtSensorType" />' +
                                                '<label for="txtSensorType3">Gyroscope</label>' +
                                                '</div></div>' +
                                            '</div>' +
                                            '<div class="col-sm-3">' +
                                                '<div class="funkyradio"><div class="funkyradio-success">' +
                                                '<input type="checkbox" value="05" id="txtSensorType4" name="txtSensorType" />' +
                                                '<label for="txtSensorType4">Temperature</label>' +
                                                '</div></div>' +
                                            '</div>' +
                                            '<div class="col-sm-3">' +
                                                '<div class="funkyradio"><div class="funkyradio-success">' +
                                                '<input type="checkbox" value="07" id="txtSensorType5" name="txtSensorType" />' +
                                                '<label for="txtSensorType5">Humidity</label>' +
                                                '</div></div>' +
                                            '</div>' +
                                            '<div class="col-sm-3">' +
                                                '<div class="funkyradio"><div class="funkyradio-success">' +
                                                '<input type="checkbox" value="09" id="txtSensorType" name="txtSensorType" />' +
                                                '<label for="txtSensorType">Temperature Stream</label>' +
                                                '</div></div>' +
                                            '</div>' +
                                            '<div class="col-sm-3">' +
                                                '<div class="funkyradio"><div class="funkyradio-success">' +
                                                '<input type="checkbox" value="10" id="txtSensorType1" name="txtSensorType" />' +
                                                '<label for="txtSensorType1">Humidity Stream</label>' +
                                                '</div></div>' +
                                            '</div>';

                        $('.sensor-list').html(sensor_li_html); 
			
            			$('.coin-list').multiselect({
            					maxHeight: 130, 
            					includeSelectAllOption: true, 
            					numberDisplayed: 2,
            					  buttonWidth:'250px',
            					nonSelectedText: 'Select Multiple COINs'

            				});
				

                        var excelType_li_html = '<h5><strong>Export Format Type: <span style="color: red">*</span></strong></h5>' +
                        						'<select name="txtExcelType" id="txtExcelType" class="txtExcelType form-control"><option value="csv">CSV Format</option><option value="xls">XLS Excel Format</option></select><br>'+
									'';
                        $('.excel-type').html(excelType_li_html); 

                        var duration_li_html = '<h5><strong>Report Duration/Time: <span style="color: red">*</span></strong></h5>' +
                        						'<select name="txtDuration" id="txtDuration" class="duration-time form-control" onchange="customreportFun(this);"><option value="1">24 Hours</option><option value="7">7 Days</option><option value="30">30 Days</option><option value="4">Custom Report</option></select>';

                        var custom_rdate_html = '<small style="margin-left: 20px;">Max 15 days data can be downloadable.</small><div class="form-inlin"><div class="form-group col-sm-3"> <span> <b>From :<b> <input type="date" class="form-control" id="from" display="none"> </span></div><div class="form-group col-sm-3"> <span><b> To :</b> <input type="date" class="form-control" id="to" /> </span></div></div><br /><br />';
                        
                        $('.duration-time').html(duration_li_html);
                        $('.customrdate').html(custom_rdate_html);


                        var download_btn = '<div class="col-sm-3"><button type="button" id="download-csv" class="btn btn-primary btn-md btn-block download-csvbtn">Download</button></div>';
                        $('.download-btn').html(download_btn);

                    },
                    error: function(errData, status, error){
                                if(errData.status==401){
                                    var resp = errData.responseJSON; 
                                    var description = resp.description; 
                                    $('.logout').click();
                                    alert(description);
                                    
                                }
                            }
            });
        }
}

// Custom Report Hide and Show
function customreportFun(customReportVal)
{
    console.log(customReportVal);
    if(customReportVal){
        customReportOptionValue = document.getElementById("txtDuration").value;

        if(customReportOptionValue == '4'){
            document.getElementById("customrdate").style.display = "block";
        }
        else{
            document.getElementById("customrdate").style.display = "none";
        }
    }
    else{
        document.getElementById("customrdate").style.display = "none";
    }
    //$(function() {

        //$("#from").datepicker({ dateFormat: 'yy-mm-dd'}); 
       // $("#to").datepicker({ dateFormat: 'yy-mm-dd'});  
    //});
}


// Select All for Checkboxes
function toggle(source) {
  checkboxes = document.getElementsByName('txtcoin');
  for(var checkbox in checkboxes)
    checkbox.checked = source.checked;
}


$(document).on('click', '#download-csv', function(e)
{
	var uid = $('#sesval').data('uid');
    var apikey = $('#sesval').data('key');
	var date_format  =  $('#sesval').data('date_format');
	var temp_unit  =  $('#sesval').data('temp_unit');

	var gateway_id = $('#txtGateway').val();
    var selectedTimeDuration = $('#txtDuration').val();

    var excelType = $('#txtExcelType').val();

    var fromDate = $("#from").val();
    var toDate = $("#to").val();
    
    var days_diff = days_between(toDate, fromDate);
    if(days_diff > '15'){
    	alert("You can import max 15 Days Data. Please change the Date");
    	return;
    }else if(days_diff <= '0'){
    	alert("TO Date should be greater than FROM Date");
    	return;
    }

    // date = updated_on;
    var stillUtc = moment().utc(date_format).toDate();
    date = moment().local().format("yy-mm-dd");
    var stillUtc = moment().utc(date_format).toDate();


	var startTime = $('.filter').data('startTime');

	var zone_offset = moment().local().format('Z');

    var SelectedCoinsList = [];
	var SelectedSensorList = [];
    var c = '';
	
    $.each($(".coin-list option:selected"), function(){            
        c = $(this).val();  
        c = "" + c + "";        
        SelectedCoinsList.push(c);
    });

	$("input:checkbox[name=txtSensorType]:checked").each(function(){
	    SelectedSensorList.push($(this).val());
	});
	
	if(uid!= '' && apikey!= '' && SelectedCoinsList != '' && SelectedSensorList != '' && selectedTimeDuration != ''){
        
        /*var i=0;
        for(i=0; i < SelectedSensorList.length; i++){
            device_type = SelectedSensorList[i];

            var j=0;
            for(j=0; j < SelectedCoinsList.length; j++){
                device_id = SelectedCoinsList[j];
                window.open("http://15.207.105.226/sensegiz-dev/portal/user/reportscsvdownloaddata.php?gateway_id=" + gateway_id + "&device_id=" + device_id + "&device_type=" + device_type + "&date_format=" + date_format + "&temp_unit=" + temp_unit + "&selectedTimeDuration=" + selectedTimeDuration + "&zone_offset=" + encodeURIComponent(zone_offset),"_blank");
            }
        }*/

        if(excelType == 'csv'){
        	window.open(server_address+"/sensegiz-dev/portal/user/reportscsvdownloaddata.php?gateway_id=" + gateway_id + "&device_id=" + SelectedCoinsList + "&device_type=" + SelectedSensorList + "&date_format=" + date_format + "&temp_unit=" + temp_unit + "&fromDate=" + fromDate + "&toDate=" + toDate + "&selectedTimeDuration=" + selectedTimeDuration + "&zone_offset=" + encodeURIComponent(zone_offset),"_blank");
        }else if(excelType == 'xls'){
        	window.open(server_address+"/sensegiz-dev/portal/user/reportsexceldownloaddata.php?gateway_id=" + gateway_id + "&device_id=" + SelectedCoinsList + "&device_type=" + SelectedSensorList + "&date_format=" + date_format + "&temp_unit=" + temp_unit + "&fromDate=" + fromDate + "&toDate=" + toDate + "&selectedTimeDuration=" + selectedTimeDuration + "&zone_offset=" + encodeURIComponent(zone_offset),"_blank");
        }

		// window.open("http://15.207.105.226/sensegiz-dev/portal/user/reportscsvdownloaddata.php?gateway_id=" + gateway_id + "&device_id=" + device_id + "&nick_name=" + nick_name + "&device_type=" + device_type + "&device_type1=" + device_type1 + "&startTime=" + startTime + "&endTime=" + endTime + "&unit=" + unit + "&date_format=" + date_format + "&rms_values=" + rms_values  + "&zone_offset=" + encodeURIComponent(zone_offset),"_blank");
	}else{
        alert('All fields marked with * are mandatory');
    }

});

function days_between(toDate, fromDate) {

	const _MS_PER_DAY = 1000 * 60 * 60 * 24;
  	const diffInMs   = new Date(toDate) - new Date(fromDate);
	const diffInDays = Math.floor(diffInMs/_MS_PER_DAY);
	return diffInDays;
}

</script>


 