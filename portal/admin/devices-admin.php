<?php

//1 error shows
//0 supresses

//error_reporting(E_ALL);
//ini_set("display_errors", 0);
//ob_start();
include_once('page_header_admin.php');

session_start();

$status_msg  =  '';
$id  =   0;


?>

<style>
.content {
    height: 100%;
    margin-left: 218px;
    padding: 35px 20px;
}

/* Update Gateway Modal Starts */
.statusbar { 
  margin: 0 0 2px 0;
    cursor: default;
  letter-spacing: .3px;
}
.statusbar-inner{
  padding: 1px 5px 1px 12px;
  border: 1px solid #333;
  border-right-width: 1px;
  font-size: 12px;
}
.statusbar-icon{
   float: left;
  background: #bec5cb;
  color: #fff;
  opacity: 1;
     font-size: 14px;
  padding: 1px 15px 0;
  margin-right: 10px;
  text-shadow: 0 1px 2px rgba(0,0,0,.2)
}
.statusbar-neutral, .statusbar-neutral .statusbar-inner{
  color: #888;
  border-color: #bec5cb;
  background-color: #f4f7f9;
}
.statusbar-info, .statusbar-info .statusbar-inner{
  color: #071a2b;
  border-color: #54a9dc;
  background-color: #b9e5ff;
}
.statusbar-info .statusbar-icon{
  background: #54a9dc;
}
.statusbar-inner{
  color: #4F8A10;
  border-color: #65bf05;
  background-color: #DFF2BF;
}
/* Update Gateway Modal Ends */

</style>
<!--<link rel="stylesheet" href="css/jquery-ui.css">-->
<script src='https://kit.fontawesome.com/a076d05399.js'></script>
<link href="../css/developer.css" rel="stylesheet">

 <div class="col-lg-10 pad0 ful_sec"> 
        <div class="row pad0">
        <div class="content admin">
          <h1>Devices</h1>
                
            <div class="pull-right log-det">
                
                <?php //if(!empty( $_REQUEST['failure'])) {extract($_GET); echo "<span class='alert alert-custom alert-danger'>".$failure." <a class='alert-danger close-alert' onClick='alertClose(this);'>x</a></span>";}?>
            <span class="sprite user"></span>
            <p class="username"><?php if(isset($_SESSION['userName']) && $_SESSION['userName'] != ''){echo strtoupper($_SESSION['userName']);} ?> </p>
            <p><span class="logout"> LOGOUT</span></p>
            <!--<span class="sprite dwn-arr"></span>-->
            </div>
            <div class="detail-content" style="background-color: #fff;">

                <!-- -->
                <div class="lp-det" style="margin-top:30px">
                    
                        <span class='error-tab error'></span>
                        <div class="col-sm-4">
                            <h3 id="gatewayid"></h3>
                            <b>Erase(Gateway):</b> <span id="gatewayEraseData"></span>
                        </div>
                        <div class="col-sm-4">
                           <a href="#"> <p data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap"><i class='fas fa-edit' style='font-size:20px'></i>Change Gateway ID</p></a>
                        </div><br><br>
                        <div class="col-sm-2">
                        <a href="#"><p data-toggle="modal" data-target="#changeUserModel" data-whatever="@getbootstrap"><i class='fas fa-edit' style='font-size:20px'></i>Change User</p></a>
                        </div>
			<div class="col-sm-2">
      <a href="#"> <p data-toggle="modal" data-target="#changeMeshIdModel" data-whatever="@getbootstrap"><i class='fas fa-edit' style='font-size:20px'></i>Change Mesh Id</p></a>
                        </div>
                        <div class="col-sm-4" style="float:right">
                        <a href="#">  <p data-toggle="modal" data-target="#addUserDevicesModel" data-whatever="@getbootstrap"><i class='fas fa-edit' style='font-size:20px'></i>Add Coins</p></a>
                        </div>
                        <div class="table-responsive admin-devices">
                            <table class="table table-hover table-bordered table-lp">
                                <tr class="active tb-hd"> 
                                    <th>Sl. No</th>
                                    <th>Device Id</th>
                                    <th>Device Name</th>
                                    <th>Device Mac Address</th>
                                    <!--<th>User</th>-->
                                    <th>Added on</th>
                                    <th>Device Type</th>
                                    <th>Blacklist/ Whitelist</th>   
                                    <th>Firmware Date</th>
                                    <th>Firmware Type</th>
                                    <th>Firmware Version</th> 
                                    <th>Delete</th>
                                </tr>
                                <tbody class="users" id="adminDevicessList">
                                   
                               </tbody>

                            </table>
                            
                            <div id='loader'><img src="../img/loader.gif"/> Loading Data</div>
                        </div>
    </div>                
         
            </div>
        </div>

        </div>
      </div>

<!-- Gateway Recovery Modal Popup -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
 
      <!-- Gateway Update Form Starts -->
      <div id="gatewayForm">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="exampleModalLabel"><strong>Gateway Replacement/Change:</strong></h4><br>

          <div class="statusbar statusbar-info">
            <div class="statusbar-icon"><span class="glyphicon glyphicon-info-sign"></span></div><div class="statusbar-inner"> All the data of Old Gateway ID will moved to New Gateway ID. Once its changed to New gateway Id, old gateway Id cannot be accessed. Only BLE address can be changed but COINs MAC Address should be same as previous. </div>
          </div>
        </div>
        <div class="modal-body">
          <form id="gatewayRecovery" name="gatewayRecovery">
            <div class="form-group">
              <label for="txtoldGatewayName" class="col-form-label">Gateway Name:</label>
              <input type="text" class="form-control" id="txtoldGatewayName" name="txtoldGatewayName" value="<?php echo $_GET['id']; ?>" readonly>
            </div>
            <div class="form-group">
              <label for="txtNewGatewayName" class="col-form-label">New Gateway ID:</label>
              <input type="text" class="form-control" id="txtNewGatewayName" name="txtNewGatewayName" value="" onkeyup="gatewayValidate()">
              <span id="gatewayValidateMsg"></span> 
            </div>
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="chkConfGatewayUpdate" required="required" onclick="showOTPbox()">
              <label class="form-check-label" for="chkConfGatewayUpdate">I confirm to proceed with change.</label>
            </div>
            <!-- <div class="form-group" id="GatewayOTP" style="display: none;">
              <label for="txtNewGatewayName" class="col-form-label">Enter OTP:</label>
              <input type="text" class="form-control" id="txtGatewayOTP" name="txtGatewayOTP" value="" onkeyup="ValidateOTP()">
              <span id="ValidateGatewayOTPMsg"></span> 
            </div> -->
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="Submit" class="btn btn-primary btnupdateGateway" value="Update" id="btnupdateGateway" name="btnupdateGateway" />
        </div>
      </div>
      <!-- Gateway Update Form Starts -->
      
    </div>
  </div>
</div>
<!-- Change Gateway User Modal Popup -->
<div class="modal fade" id="changeUserModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <!-- Gateway Update Form Starts -->
      <div id="gatewayForm">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="exampleModalLabel"><strong>Move Gateway to other User</strong></h4><br>

          <div class="statusbar statusbar-info">
            <div class="statusbar-icon"><span class="glyphicon glyphicon-info-sign"></span></div><div class="statusbar-inner"> All the data of this Gateway ID will moved to New User account. Once its changed to New User account, in account it cannot be accessed.</div>
          </div>
        </div>
        <div class="modal-body">
          <form id="gatewayUserChange" name="gatewayUserChange">
            <div class="form-group">
              <label for="txtoldGatewayName" class="col-form-label">Gateway Name: <?php echo $_GET['id']; ?></label>
            </div>
            <div class="form-group">
              <label for="txtNewGatewayName" class="col-form-label">Select User:</label>
              <span id="getUsers_select">

              </span>
              <span id="gatewayUserValidateMsg"></span> 
            </div>
            <!-- <div class="form-check">
              <input type="checkbox" class="form-check-input" id="chkConfGatewayUpdate" required="required" onclick="showOTPbox()">
              <label class="form-check-label" for="chkConfGatewayUpdate">I confirm to proceed with change.</label>
            </div> -->
            <!-- <div class="form-group" id="GatewayOTP" style="display: none;">
              <label for="txtNewGatewayName" class="col-form-label">Enter OTP:</label>
              <input type="text" class="form-control" id="txtGatewayOTP" name="txtGatewayOTP" value="" onkeyup="ValidateOTP()">
              <span id="ValidateGatewayOTPMsg"></span> 
            </div> -->
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="Submit" class="btn btn-primary btnupdateGatewayUser" value="Update" id="btnChangeGatewayUser" name="btnChangeGatewayUser" />
        </div>
      </div>
      <!-- Gateway Update Form Starts -->

    </div>
  </div>
</div>
<!-- Gateway Recovery Modal Popup Ends -->
<div class="modal fade" id="addUserDevicesModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
<div id="gatewayForm">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="exampleModalLabel"><strong>Add Coin to User Account</strong></h4><br>

          
        </div>
        <div class="modal-body">
          <form id="gatewayUserChange" name="gatewayUserChange">
            <div class="form-group">
            <label for="txtNewGatewayName" class="col-form-label">Gateway ID:</label>
            <input class="form-control" type="text" id="new_gateway_id" name="new_gateway_id" maxlength="12"  value="<?php echo $_GET['id']; ?>" placeholder="Gateway id" style="margin-left:3px " disabled/>
            </div>
            <div class="form-group">
            <label class="col-form-label">Coin Mac ID:</label>
            <input class="form-control" type="text" id="coin_mac_id" name="coin_mac_id" maxlength="12" value="" placeholder="Coin mac id" style="margin-left:3px"/>
            </div>
            <div class="form-group">
            <label class="col-form-label">Coin Nick Name:</label>
            <input class="form-control" type="text" id="coin_nickname" name="coin_nickname"  value="" placeholder="Coin nick name" style="margin-left:3px"/>
            </div>
            <div class="form-group">
              <label class="col-form-label">Coin Placement:</label>
              <div>
                <input type="radio" id="coin_placement" name="coin_placement" value="door" checked>
                <label for="door">Door</label>
              </div>
              <div>
                <input type="radio" id="coin_placement" name="coin_placement" value="shutter">
                <label for="shutter">Shutter</label>
              </div>
              <div>
                <input type="radio" id="coin_placement" name="coin_placement" value="gate">
                <label for="gate">Gate</label>
              </div>
            </div>
            <input class="form-control" type="hidden" id="userIdForCoinAdd" name="userIdForCoinAdd"  value="<?php echo $_GET['uid']; ?>" placeholder="Coin nick name" style="margin-left:3px" hidden/>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="Submit" class="btn btn-primary btnAddDevices" value="Add Coins" id="btnAddGatewayUser" name="btnAddGatewayUser" />
        </div>
      </div>
      </div>
  </div>
</div>
<!-- Change Gateway mesh id Modal Popup -->
<div class="modal fade" id="changeMeshIdModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <!-- Gateway Update Form Starts -->
      <div id="gatewayForm">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="exampleModalLabel"><strong>Change Gateway Mesh Id</strong></h4><br>
        </div>
        <div class="modal-body">
          <form id="gatewayMeshIdChange" name="gatewayMeshIdChange">
	    <div class="form-group">
            	<label for="txtNewGatewayName"  class="col-form-label">Gateway ID:</label>
            	<input class="form-control" type="text" id="new_gateway_id" name="new_gateway_id" maxlength="12"  value="<?php echo $_GET['id']; ?>" placeholder="Gateway id" style="margin-left:3px " disabled/>
            </div>
            <div class="form-group">
            	<label class="col-form-label">Mesh ID:</label>
            	<input class="form-control" type="number" id="meshId" name="meshId" value="" placeholder="Enter mesh id" style="margin-left:3px"/>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="Submit" class="btn btn-primary btnupdateGatewayMeshId" value="Update" id="btnChangeGatewayMeshId" name="btnChangeGatewayMeshId" />
        </div>
      </div>
      <!-- Gateway Update Form Starts -->

    </div>
  </div>
</div>


<script>
  document.getElementById("btnupdateGateway").disabled = true;
  document.getElementById("chkConfGatewayUpdate").disabled = true;
  // document.getElementById("txtGatewayOTP").disabled = true;

  $('#exampleModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var recipient = button.data('whatever') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    modal.find('.modal-title').text('New message to ' + recipient)
    modal.find('.modal-body input').val(recipient)

  })


  function gatewayValidate(){
      var msg;  
      var oldGatewayId = document.getElementById('txtoldGatewayName').value;
      var NewGateway = document.getElementById('txtNewGatewayName').value;
      var NewGatewayLength = NewGateway.length;

      if((NewGatewayLength != '') && (NewGatewayLength == 12))
      {  
          var result = validateGateway(NewGateway);
      }  
      else
      {  
          document.getElementById("chkConfGatewayUpdate").disabled = true;
          document.getElementById("btnupdateGateway").disabled = true;
          // document.getElementById("txtGatewayOTP").disabled = true;
          
          $('#chkConfGatewayUpdate').prop('checked', false);
          $('#txtGatewayOTP').val("");

          msg = "Invalid Gateway ID. Gateway length Not Matching";  
          document.getElementById("gatewayValidateMsg").innerHTML = msg;
          
      }
  }

  function showOTPbox()
    {
      if($('#chkConfGatewayUpdate').is(':checked')) {
          $('#GatewayOTP').show();
          // document.getElementById("txtGatewayOTP").disabled = false;
            document.getElementById("btnupdateGateway").disabled = false;
      } else {
          document.getElementById("btnupdateGateway").disabled = true;
          $('#GatewayOTP').hide();
          // document.getElementById("txtGatewayOTP").disabled = true;
      }
  }

</script>
<!-- Gateway Recovery Modal Popup -->


<?php
    include_once('page_footer_admin.php');
?>

<script type="application/javascript">
getInvitedUsers();  
    //Get devices connected to the gateway               
    var gateway_id = getParameterByName('id');
    
    if(gateway_id!=''){
        $('#gatewayid').html('GATEWAY- '+gateway_id);
        getAdminDevices(gateway_id);

        /* Starts: Gateway Data Erase Command */
        validateGateway(gateway_id);
        var eraseGatewayData_html = '<button class="btn btn-small" data-gateway_id="'+gateway_id+'" onclick="eraseGatewayData(this);">Erase Data(Gateway)</button>';
        $('#gatewayEraseData').html(eraseGatewayData_html);
        /* Ends: Gateway Data Erase Command */

    } 

/* Modal will not close if we click outside */
$(document).ready(function(){
    $("#exampleModal").modal({
        show:false,
        backdrop:'static'
    });
});
$(document).ready(function(){
    $("#changeUserModel").modal({
        show:false,
        backdrop:'static'
    });
});
$(document).ready(function(){
    $("#addUserDevicesModel").modal({
        show:false,
        backdrop:'static'
    });
});
</script>