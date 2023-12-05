<?php
error_reporting(E_ALL);
ini_set("display_errors", 0);
ob_start();
include_once('page_header_admin.php');

session_start();

$status_msg  =  '';
$id  =   0;

$gatewayid = $_GET['gatewayid'];
$deviceid = $_GET['deviceid'];
$devicemacadd = $_GET['devicemacadd'];

?>

      <style>
.content {
    height: 100%;
    margin-left: 218px;
    padding: 35px 20px;
}

.detail-content {
    background-color: transparent;
    border: 0px solid #e4e4e4;
    margin-top: 60px;
    min-height: 300px;
    width: 100%;
}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<!-- Material Design Bootstrap -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">

<!-- JQuery -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" type="text/css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

<link rel="stylesheet" href="../css/custom_style.css">

 <div class="col-lg-10 pad0 ful_sec"> 
      	<div class="row pad0">
        <div class="content">
        	<h1>Device Info</h1>
            <div class="pull-right log-det">
                                
                <span class="sprite user"></span>
                <p class="username"><?php if(isset($_SESSION['userName']) && $_SESSION['userName'] != ''){echo strtoupper($_SESSION['userName']);} ?> </p>
                <p><span class="logout"> LOGOUT</span></p>
                <!--<span class="sprite dwn-arr"></span>-->
            </div>
            <div class="detail-content">
                <button type="button" class="btn btn-default btn-lg " id="getsensortype" style="background-color:#d4c8c8;" data-gateway_id='<?php echo $gatewayid; ?>'data-device_id='<?php echo $deviceid; ?>'onclick="getCurrentSensorType(this);">GET SENSOR TYPE</button>
                <h4><b>Gateway ID:</b> <?php echo $gatewayid; ?> <br /><b>Device ID: </b><?php echo $deviceid; ?> <br /> </h4>
                <div class="lp-det">

                    <div class="row">
                        <!-- Starts: Particular Date Data From COIN  -->
                        <div class="col-md-6 col-sm-6">
                          <!-- Card -->
                          <div class="card">
                            <!-- Card content -->
                            <div class="card-body">

                              <br />
                              <h4 class="card-title text-center"><b>Delete Particular Date Data From COIN: </b></h4>
                              <div class="container-fluid">
                                    <span class='error'></span>
                                    <span class='success'></span>

                                    <form method="post" id="DashboardSetupForm" class="form-horizontal">  
                         
                                        <input type="hidden" name="u_id" id="u_id" value="0" />
                                        <input type="hidden" name="admin_id" id="admin_id" value="<?php if(isset($_SESSION['adminId']) && $_SESSION['adminId']>0){echo strtoupper($_SESSION['adminId']);} ?>" />
                                        <input type="hidden" name="txtGatewayID" id="txtGatewayID" value="<?php echo $gatewayid; ?>" />
                                        <input type="hidden" name="txtDeviceID" id="txtDeviceID" value="<?php echo $deviceid; ?>" />
                                        <input type="hidden" name="txtDeviceMacAdd" id="txtDeviceMacAdd" value="<?php echo $devicemacadd ?>" />

                                        <div class="form-group col-md-6 col-sm-6">
                                            <label class="control-label" for="txtFromDate">From: <span style="color: red">*</span></label>
                                            <input type="date" class="form-control" id="txtFromDate" name="txtFromDate">
                                        </div>

                                        <div class="form-group col-md-6 col-sm-6">
                                            <label class="control-label" for="txtToDate">To: <span style="color: red">*</span></label>    
                                            <input type="date" class="form-control" id="txtToDate" name="txtToDate">
                                        </div>
                                        <br />
                                        <div class="col-sm-12">
                                            <h5 style="margin-left: 10px;"><strong>Select Sensor Type: <span style="color: red">*</span></strong></h5>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="funkyradio"><div class="funkyradio-success">
                                            <input type="hidden" value="'+gatewayId+'" id="txtGateway" name="txtGateway" />
                                            <input type="radio" value="01" id="txtSensorType1" name="txtSensorType" class="txtSensorType" />
                                            <label for="txtSensorType1">Accelerometer</label>
                                            </div></div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="funkyradio"><div class="funkyradio-success">
                                            <input type="radio" value="03" id="txtSensorType3" name="txtSensorType" class="txtSensorType"/>
                                            <label for="txtSensorType3">Gyroscope</label>
                                            </div></div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="funkyradio"><div class="funkyradio-success">
                                            <input type="radio" value="05" id="txtSensorType5" name="txtSensorType" class="txtSensorType"/>
                                            <label for="txtSensorType5">Temperature</label>
                                            </div></div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="funkyradio"><div class="funkyradio-success">
                                            <input type="radio" value="07" id="txtSensorType7" name="txtSensorType" class="txtSensorType"/>
                                            <label for="txtSensorType7">Humidity</label>
                                            </div></div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="funkyradio"><div class="funkyradio-success">
                                            <input type="radio" value="09" id="txtSensorType9" name="txtSensorType" class="txtSensorType"/>
                                            <label for="txtSensorType9">Temperature Stream</label>
                                            </div></div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="funkyradio"><div class="funkyradio-success">
                                            <input type="radio" value="10" id="txtSensorType10" name="txtSensorType" class="txtSensorType"/>
                                            <label for="txtSensorType10">Humidity Stream</label>
                                            </div></div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="funkyradio"><div class="funkyradio-success">
                                            <input type="radio" value="12" id="txtSensorType12" name="txtSensorType" class="txtSensorType"/>
                                            <label for="txtSensorType12">Accelerometer Stream</label>
                                            </div></div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="funkyradio"><div class="funkyradio-success">
                                            <input type="radio" value="allSensor" id="txtSensorTypeAll" name="txtSensorType" class="txtSensorType"/>
                                            <label for="txtSensorTypeAll">All Sensor Data</label>
                                            </div></div>
                                        </div>
                                        <br/>
                                        <div class="form-group col-md-12 col-sm-12">        
                                            <input type="button" class="btn btn-default btnDeviceDataDelToFrom" style="background-color:#d4c8c8;" value="Delete"/>
                                        </div> 
                                    </form>
                              </div>

                            </div>
                          </div>
                          <!-- Card -->
                        </div>
                        <!-- Ends: Particular Date Data From COIN  -->

                        <!-- Start: COIN Recovery Strategy -->
                        <div class="col-md-6 col-sm-6">
                          <!-- Card -->
                          <div class="card">
                            <!-- Card content -->
                            <div class="card-body">

                                <br />
                                <h4 class="card-title text-center"><b>Recovery Strategy: </b></h4>
                                <p class="text-center">Recover the Damaged COIN data with NEW COIN device. Update NEW COIN MAC Address for OLD COIN device.</p>
                                <div class="container-fluid">
                                    <h5><b>Device MAC Address: </b><?php echo $devicemacadd ?></h5><br />
                                    <span class='error'></span>
                                    <span class='success'></span>

                                    <form method="post" id="frmRecoverDevice">  
                         
                                        <input type="hidden" name="u_id" id="u_id" value="0" />
                                        <input type="hidden" name="admin_id" id="admin_id" value="<?php if(isset($_SESSION['adminId']) && $_SESSION['adminId']>0){echo strtoupper($_SESSION['adminId']);} ?>" />
                                        <input type="hidden" name="txtGatewayID" id="txtGatewayID" value="<?php echo $gatewayid; ?>" />
                                        <input type="hidden" name="txtDeviceID" id="txtDeviceID" value="<?php echo $deviceid; ?>" />
                                        <input type="hidden" name="txtDeviceMacAdd" id="txtDeviceMacAdd" value="<?php echo $devicemacadd ?>" />

                                        <div class="col-sm-8">
                                            <label for="txtMACaddress">Enter New COIN MAC Address:</label>
                                            <input type="text" id="txtMACaddress" name="txtMACaddress" class="form-control txtMACaddress" placeholder="Enter New COIN MAC Address" onkeyup="coinValidate()" />
                                            <span id="coinValidateMsg"></span> 
                                        </div>
                                        <div class="col-sm-8">
                                            <br />
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="chkConfcoinUpdate" required="required" onclick="showOTPbox()">
                                                <label class="form-check-label" for="chkConfcoinUpdate">I confirm to proceed with change.</label>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="form-group col-md-12 col-sm-12">        
                                            <input type="button" class="btn btn-default btn-lg btnRecoverCoinDevice" id="btnRecoverCoinDevice" style="background-color:#d4c8c8;" value="UPDATE" />
                                        </div> 
                                    </form>
                                </div>

                            </div>
                          </div>
                          <!-- Card -->
                        </div>
                        <!-- Ends: COIN Recovery Strategy -->

                    </div>
                  
                </div>
                
               
            </div>
        </div>

        </div>
      </div>


<script>

    document.getElementById("btnRecoverCoinDevice").disabled = true;
    document.getElementById("chkConfcoinUpdate").disabled = true;

    function coinValidate(){
        var msg;  
        var oldCoinMac = document.getElementById('txtDeviceMacAdd').value;
        var NewCoinMac = document.getElementById('txtMACaddress').value;
        var NewCoinMacLength = NewCoinMac.length;

        if((NewCoinMac != '') && (NewCoinMacLength == 12) && (oldCoinMac != ''))
        {  
            var result = validateCoin(NewCoinMac);
        }  
        else
        {  
            document.getElementById("chkConfcoinUpdate").disabled = true;
            document.getElementById("btnRecoverCoinDevice").disabled = true;
          
            $('#chkConfcoinUpdate').prop('checked', false);

            msg = "Invalid Length. MAC Address Length is Not Matching";  
            document.getElementById("coinValidateMsg").innerHTML = msg;
            
        }
    }

</script>

<?php
    include_once('page_footer_admin.php');
?>

