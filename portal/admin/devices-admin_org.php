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
</style>
<!--<link rel="stylesheet" href="css/jquery-ui.css">-->

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
                        <h3 id="gatewayid"></h3>
                        <div class="table-responsive admin-devices">
                           <table class="table table-hover table-bordered table-lp">
                               <tr class="active tb-hd"> 
                                   <th>Sl. No</th>
                                   <th>Device Id</th>
                                   <th>Device Mac Address</th>
                                   <!--<th>User</th>-->
                                   <th>Added on</th>
                                   <th>Blacklist/ Whitelist</th>   
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
<?php
    include_once('page_footer_admin.php');
?>

<script type="application/javascript">
        //Get devices connected to the gateway               
            var gateway_id = getParameterByName('id');
            
            if(gateway_id!=''){
                $('#gatewayid').html('GATEWAY- '+gateway_id);
                getAdminDevices(gateway_id)
            } 
</script>