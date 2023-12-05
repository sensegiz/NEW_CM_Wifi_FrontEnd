<?php 
include_once('page_header_user1.php');
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script src="../js/paging.js"></script>


<style>
.paging-nav {
  text-align: right;
  padding-top: 2px;
}

 
.paging-nav a {
  margin: auto 1px;
  text-decoration: none;
  display: inline-block;
  padding: 1px 7px;
  background: #91b9e6;
  color: white;
  border-radius: 3px;
}

 
.paging-nav .selected-page {
  background: #187ed5;
  font-weight: bold;

}

</style>

<!--<div class="col-lg-10 pad0 ful_sec"> 
        <div class="row pad0">-->
        <div class="content userpanel">

<?php 
    include './user_menu.php';
?>
	<div class="container">
	<div class="col-12 col-sm-12 mains">
            <h1>Map Monitor</h1>
                <p id="">Event Logs</p>
<hr>
            <div class="detail-content" style="background-color: #fff;">
                
                <div class="table-responsive devicesTables">
                           <table id="logtable" class="table table-hover table-bordered table-lp">
				<thead>
                               <tr class="active tb-hd">                                                          
                                   <th>Sl. No</th>
                                   <th>Gateway ID</th>
                                   <th>Device ID</th>
                                   <th>Sensor</th>
                                   <th>Value</th>
                                   <th>Log Description</th>
                                   <th>Date/Time</th>                                                                                            
                               </tr>
                           	</thead>   
                           
                               <tbody class="users logs" id="eventLogs">
                                                                      
                               </tbody>
                           </table>				

                            
                            <div id='loader'><img src="../img/loader.gif"/> Loading Data</div>
                </div>

        <p id="msg"></p>
        <input type="hidden" id="fn" value=""></p>
        <input type="hidden" id="apikey" value=""></p>

        <input type="hidden" id="uid" value=""></p>

 
                                   
            </div>
     </div>

   </div>

</div>



<?php
    include_once('page_footer_user.php');
?>

<script type="text/javascript">

var server_address = getBaseAddressUser();
	var basePathUser = server_address+'/sensegiz-dev/user/';
   	var apiUrlGetEventLogs = 'get-event-logs';
	

	var location_id = localStorage.getItem("location_id");
	var location_name = localStorage.getItem("location_name");
	var location_image = localStorage.getItem("location_image");
	
		

	getEventLogs(location_id);
	

$(document).ready(function() {
	$('#logtable').paging({limit:15});
});



</script>