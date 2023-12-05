<?php
//1 error shows
//0 supresses

//error_reporting(E_ALL);
//ini_set("display_errors", 0);
//ob_start();
include_once('page_header_user.php');

//session_start();

$status_msg  =  '';
$id  =   0;


?>
<link rel="stylesheet" href="css/jquery-ui.css">

 <div class="col-lg-10 pad0 ful_sec"> 
      	<div class="row pad0">
        <div class="content">
        	
            <div class="pull-right log-det">
                
                <?php //if(!empty( $_REQUEST['failure'])) {extract($_GET); echo "<span class='alert alert-custom alert-danger'>".$failure." <a class='alert-danger close-alert' onClick='alertClose(this);'>x</a></span>";}?>
            <span class="sprite user"></span>
            <p class="username"><?php if(isset($_SESSION['userName']) && $_SESSION['userName'] != ''){echo strtoupper($_SESSION['userName']);} ?> </p>
            <p><span class="logout"> LOGOUT</span></p>
            <!--<span class="sprite dwn-arr"></span>-->
            </div>
            <h1>Devices</h1>
            <div class="detail-content" style="background-color: #fff;">

                <!-- -->
                <div class="lp-det" style="">
                    
                        <span class='error-tab'></span>
                        <!--<h3 id="gatewayid"></h3>-->
                        <div class="table-responsive">
                           <table class="table table-hover table-bordered table-lp">
                               <tr class="active tb-hd"> 
                                   <th>Sl. No</th>
                                   <th>Device ID</th>
                                   <th>Type</th>
                                   <th>Value</th>
                                   <th>Current Value</th>
                                   <!--<th>Status</th>-->
                                   <!--<th>Status</th>--> 
                                   <th>Added on</th> 
                                   <th>Last Updated</th>
                                   <th>Threshold</th>
                               </tr>
                               <tbody class="users" id="devicesList">
<!--                                   <tr>
                                       <td><a href="#">AB:12:34:56</a></td>
                                       <td>Temperature</td>
                                       <td>CONNECTED</td>
                                       <td>14/09/2016 23:10:01</td>
                                       
                                   </tr>
                                   <tr>
                                       <td><a href="#">23:65:AB:34</a></td>
                                       <td>Sunlight</td>
                                       <td>Not CONNECTED</td>
                                       <td>12/09/2016 10:10:01</td>
                                       
                                   </tr>-->
                                   
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
    include_once('page_footer_user.php');
?>

<script type="application/javascript">
        //Get devices connected to the gateway               
            var gateway_id = getParameterByName('id');
            
            if(gateway_id!=''){
                $('#gatewayid').html('GATEWAY- '+gateway_id);
                getDevices(gateway_id)
            } 
</script>