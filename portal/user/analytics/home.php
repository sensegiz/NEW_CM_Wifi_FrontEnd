<?php 
include_once('page_header_user.php');
?>


<div class="col-lg-10 pad0 ful_sec"> 
      	<div class="row pad0">
        <div class="content userpanel">
<?php 
    include './user_menu.php';
?>
            <h1>Coins</h1>
	    <div class="mine"></div>
                <!--<h3 id="">Devices</h3>-->
            <div class="detail-content" style="background-color: #fff; z-index:0">

                <!-- -->
                
                <div class="lp-det" style="margin-top:30px">
                    
                        <span class='error-tab'></span>
							<div class="row">
					<div class="col-md-6 deviceImg"></div>

					<div class="col-md-6 gwData" style="z-index:0">
						<p>Click on any gateway to view Analytics</p>
					</div>
				</div>
<br/>
				<div class="coinId">
				</div>

				 <div class="row colCode">
				</div>


                        	<div class="row gwCoin"></div>
                            
                            <div id='loader'><img src="../img/loader.gif"/> Loading Data</div>
                        
                  
                </div>                
         
            </div>
        </div>

        </div>
      </div>

<?php
    include_once('page_footer_user.php');
?>

<script type="text/javascript">

var server_address = getBaseAddressUser();
basePathUser      =   server_address+"/sensegiz-dev/user/";
        
var apiUrlGateways            = "gateways";
var apiUrlHelpDevices             = "help-devices";
var apiUrlHelpGetCoinSensor         = 'help-get-device-sensor';

	/*
	 * Function 			: helpGetGateways()
	 * Brief 			: load the list of Gateways	 
	 * Input param 			: Nil
	 * Input/output param           : NA
	 * Return			: NA
	 */		
	function helpGetGateways() {
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
                                    $.each(records, function (index, value) {
                                        var gateway_id  = value.gateway_id;
                                        var date        = '';
					var added_on    = value.added_on;
					var updated_on  = value.data_received_on;
					var is_blacklisted = value.is_blacklisted;
					var is_active = value.active;

                                        if(value.updated_on!=''){
            //                               date      =  added_on +'Z'; 
                                           date         = updated_on;
                                           var stillUtc = moment.utc(date).toDate();
                                           date         = moment(stillUtc).local().format('YYYY-MM-DD HH:mm:ss');
                                        }

		                      sc_html  =  '<tr><td>Click on any Gateways</td></tr>';

                                        gw_li_html += '<li><a href="javascript:;" class="acGw" data-gateway="'+gateway_id+'"  data-is_active="'+is_active+'" data-is_blacklisted="'+is_blacklisted+'" data-added_on="'+added_on+'" data-updated_on="'+date+'">'+gateway_id+'</a></li>';
                                    });                        
                                }
                                else{
                                        sc_html  =  '<tr><td width="300">No Gateways Found</td></tr>';
                                }
                                $('#gatewaysList').html(sc_html); 

                                $('.gateway-list-lfnav').html(gw_li_html);
                    
                    
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


helpGetGateways();
</script>