<?php
//1 error shows
//0 supresses

//error_reporting(E_ALL);
//ini_set("display_errors", 0);
ob_start();
include_once('page_header.php');

session_start();

$status_msg  =  '';
$id  =   0;


?>
<link rel="stylesheet" href="css/jquery-ui.css">

 <div class="col-lg-10 pad0 ful_sec"> 
      	<div class="row pad0">
        <div class="content">
        	<h1>Users Information</h1>
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
                    <a href="../exportcsv.php?type=channels"><button class="export_channels">Export CSV with Channels</button></a>
                    <a href="../exportcsv.php?type=mail"><button class="export_mail">Export For Email</button></a>
                    <a href="../exportcsv.php?type=csv"><button class="export_but">Export CSV</button></a>
                    
                    
                        <span class='error-tab'></span>
                        <div class="table-responsive">
                           <table class="table table-hover table-bordered table-lp">
                               <tr class="active tb-hd">
                                   <th width="40">Sl No</th>
                                   <th>Name</th>
                                   <th>Email</th>
                                   <th>FB ID</th>  
                                   <th>Country</th>  
                                   <th>Mail Verified</th>
                                   <th>Joined On</th>
                                   <th>User ID</th>
                                   <th>Profile Pic</th>
                               </tr>
                               <tbody class="users users-info">

                               </tbody>

                           </table>
                        </div>
    </div>                
         
            </div>
        </div>

        </div>
      </div>
<?php
    include_once('page_footer.php');
?>

<script type="application/javascript">
    getUsersInformation();
            //To get Device Details
//            var challengeId = getParameterByName('ch');

//            if(challengeId!=''){                
//                //get graph data
//                getQuestions(challengeId);                 
//            }    
<!--//    loadEncourageMessages();-->
</script>