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
label {
    display: inline-block;
    max-width: 100%;
    margin-bottom: 5px;
    font-weight: 700;
    margin-top: 10px;
	font-size:12px;
	margin-left:5px;
}
.form-control1{
	//margin-top:-29px;
	margin-left:4px;
	width:120px;
	 display: block;
	height: 34px;
   	 padding: 6px 12px;
   	 font-size: 14px;
    	line-height: 1.42857143;
    	color: #555;
	background-color:#fff;
	background-image: none;
	border: 1px solid#ccc;
	border-radius: 4px;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	box-shadow: inset 0 1px 1px
   	 rgba(0,0,0,.075);
   	 -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    	-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    	transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;

}
.modal-footer {

    padding: 6px;
    text-align: right;
    border-top: 1px solid 

    #e5e5e5;

}
</style>
<!--<link rel="stylesheet" href="css/jquery-ui.css">-->

<script src='https://kit.fontawesome.com/a076d05399.js'></script>

 <!--<div class="col-lg-10 pad0 ful_sec"> 
      	<div class="row pad0">-->
        <div class="content admin">
        	
            <div class="pull-right log-det">
                
                <?php //if(!empty( $_REQUEST['failure'])) {extract($_GET); echo "<span class='alert alert-custom alert-danger'>".$failure." <a class='alert-danger close-alert' onClick='alertClose(this);'>x</a></span>";}?>
            <span class="sprite user"></span>
            <p class="username"><?php if(isset($_SESSION['userName']) && $_SESSION['userName'] != ''){echo strtoupper($_SESSION['userName']);} ?> </p>
            <p><span class="logout"> LOGOUT</span></p>
            <!--<span class="sprite dwn-arr"></span>-->
            </div>
            <h1>Gateways</h1>    
            
            
            <div class="detail-content" style="background-color: #fff;">
                <br />
                <div style="float:left" class="form-inline">
                    <label>Search COIN's Gateway: </label>
                    <input type="text" class="form-control" id="searchbycoinmac" placeholder="Enter Coin Mac Id">
                    <button type="button" class="btn btn-primary searchCoin">Search</button>
                    
                </div>
                <div class="col-sm-4" style="float:right">
                            <a><p data-toggle="modal" data-target="#addUserDevicesModel" data-whatever="@getbootstrap"><i class='fas fa-edit' style='font-size:20px'></i>Add User Gateway</p></a>
                        </div>
                <br />
                <!-- -->
                <div class="lp-det" style="margin-top:30px">
                    
                        <span class='error-tab error'></span>
                        <h3 id="gatewayid"></h3>
                        <div class="table-responsive admin-gateways">
                            <table class="table table-hover table-bordered table-lp">
                                <tr class="active tb-hd"> 
                                    <th>Sl. No</th>
                                    <th>Gateway Id</th>
                                    <th>Gateway Version</th>
                                    <th>User Email</th>
                                    <th>Added on</th>
                                    <th>Blacklist/ Whitelist</th> 
                        				    <th>Edit Time Factor</th>  
                        				    <th>Delete</th>
                                    <th>Buzzer</th>
                                </tr>
                                <tbody class="users" id="adminGatewaysList">
                                   
                                </tbody>

                            </table>
                            
                            <div id='loader'><img src="../img/loader.gif"/> Loading Data</div>
                        </div>
                </div>                
         
            </div>
        </div>

        </div>
      </div>

      <div class="modal fade" id="addUserDevicesModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <!-- Gateway Update Form Starts -->
      <div id="gatewayForm">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="exampleModalLabel"><strong>Add Gateway and Coin to User Account</strong></h4><br>

          <!-- <div class="statusbar statusbar-info">
            <div class="statusbar-icon"><span class="glyphicon glyphicon-info-sign"></span></div><div class="statusbar-inner"> All the data of this Gateway ID will moved to New User account. Once its changed to New User account, in account it cannot be accessed.</div>
          </div> -->
        </div>
        <div class="modal-body">
          <form id="gatewayUserChange" name="gatewayUserChange">
            <div class="form-group">
            <label for="txtNewGatewayName" class="col-form-label">Gateway ID:</label>
            <input class="form-control" type="text" id="new_gateway_id" name="new_gateway_id" maxlength="12"  value="" placeholder="Gateway id" style="margin-left:3px"/>
            </div>
            <div class="form-check">
              <input type="hidden" class="form-check-input" id="onlyGatewayAdd" value="true">
              <!-- <label class="form-check-label" for="onlyGatewayAdd">Add Only Gateway</label> -->
            </div>
            <!-- <div class="form-group">
            <label class="col-form-label">Coin Mac ID:</label>
            <input class="form-control" type="text" id="coin_mac_id" name="coin_mac_id" maxlength="12" value="" placeholder="Coin mac id" style="margin-left:3px"/>
            </div>
            <div class="form-group">
            <label class="col-form-label">Coin Nick Name:</label>
            <input class="form-control" type="text" id="coin_nickname" name="coin_nickname"  value="" placeholder="Coin nick name" style="margin-left:3px"/>
            </div> -->
            <div class="form-group">
              <label for="txtNewGatewayName" class="col-form-label">Select User:</label>
              <span id="getUsers_select">

              </span>
              <span id="gatewayUserValidateMsg"></span> 
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="Submit" class="btn btn-primary btnAddDevices" value="Add Gateway" id="btnAddGatewayUser" name="btnAddGatewayUser" />
        </div>
      </div>
      <!-- Gateway Update Form Starts -->

    </div>
  </div>
</div>
<!-- Update Time Factor-->

<div class="modal fade" id="modalEditTimeFactor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
       
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
	 <h4 class="modal-title w-100 font-weight-bold">Update Time Factor for Calculations</h4>
      </div>


      <div class="modal-body ">
	<div class="container-fluid">
	         <div class="CoinList">
           <span class='successtf'></span>                    
          <!--<div id="coin" class="collapse">-->
          <form class="form-horizontal">
           <span class='errortf'></span>
          <input type="hidden" id="id" name="id" value="0" readonly/>
          <div class="form-group">
                    <div class="col-sm-4">
                  <input class="form-control" type="text" id="gateway" name="gateway"  value=""  disabled style="margin-left:3px"/>
                </div>
              </div>

        
         <div class="form-group">
                    <div class="col-sm-4">
  		<span class='errorloc'></span>
                 <label>Axial(X):</label> <input class="form-control1" onkeypress="return validateDecimalNumber(this, event);" name="xtime" id="xtime" type="text" value="" >
                </div>
		<div class="col-sm-4">
                  <label>Horizontal(Y):</label><input  class="form-control1" onkeypress="return validateDecimalNumber(this, event);" name="ytime" id="ytime" type="text" value="" >
                </div>
		<div class="col-sm-4">
                 <label>Vertical(Z):</label> <input class="form-control1" onkeypress="return validateDecimalNumber(this, event);" name="ztime" id="ztime" type="text" value="" >
                </div>
              </div>
	<div class="form-group">
		<div class="col-sm-4">
		<button type="button" class="btn btn-primary updatetimefactor" id="updatetimefactor"style="margin-left:3px">Update Time Factor</button>
		</div>
	   </div>
	</form>
	</div>
      </div>
      <div class="modal-footer d-flex justify-content-center">
        
 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php
    include_once('page_footer_admin.php');
?>

<script type="application/javascript">
    // getAdminGateways();
    getInvitedUsers();  

    var searchval = $('#searchbycoinmac').val();
    if (searchval === '') {
        searchval = 'all';
    }
    getAdminGateways(searchval);
    $(document).on("click", ".searchCoin", function(e) {
        e.preventDefault();
        var searchval = $('#searchbycoinmac').val();
        if (searchval === '') {
            searchval = 'all';
        }
        // debugger;
        getAdminGateways(searchval);

    });
    $(document).ready(function(){
    $("#addUserDevicesModel").modal({
        show:false,
        backdrop:'static'
    });
});
// $('#onlyGatewayAdd').change(function(){
// if ($('#onlyGatewayAdd').is(':checked')){
//    $('#coin_mac_id').attr('disabled', 'disabled');
//    $('#coin_nickname').attr('disabled', 'disabled');
// } else {
//  $('#coin_mac_id').removeAttr('disabled');
//  $('#coin_nickname').removeAttr('disabled');
//   console.log('unchecked');
// }
// });
</script>