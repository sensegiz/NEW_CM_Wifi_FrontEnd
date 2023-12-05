<?php 

include_once('page_header_user.php');

?>

 <!--<div class="col-lg-10 pad0 ful_sec"> 
      	<div class="row pad0">-->
        <div class="content userpanel">

<?php 
    include './user_menu.php';
?>
<style>
.table{
	width:50%;
}

@media only screen and (min-width: 200px) and (max-width:900px){
.table{
	width:100%;
}
}
</style>
<div class="col-sm-10 col-10 mains">
         
        
            <h1>Add Mobile Numbers</h1><hr>
                <p id="">(Add mobile numbers to get notifications)</p>
            <div class="detail-content" style="background-color: #fff;">
                
                <div class="lp-det" style="margin-top:30px">
                    
                        <span class='error'></span>
                        <span class='success'></span>
                        
				<div class="alert alert-info alert-dismissable col-sm-12">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>NOTE:</strong> Please make sure that you have selected the correct country code to add the Mobile Number! Do not enter the country code in the text field provided!
					
				</div>

                            
                        <div class="table-responsive ">
                            <form action="" method="" id="phoneNotifyForm">
                                <h6 class="labh6">Mobile Number</h6>
                                        <input type="hidden" id="edit_id" name="edit_id" value="0"/>
                                        
                                        <input type="text" name="phone_notify" id="phone_notify" placeholder="Mobile Number" onkeypress="return isNumberKey(event);" class="validation input-new"/>
                                        <span class="log-last">
                                            <input type="button" class="save addPhone" value="Save"/></a>
                                        </span>
                                
                            </form>
                            <hr/>
                            
                            <h3>Mobile Numbers List</h3>
                            
                            <div class="emailList">
                                                                                 
                                <table class="table table-hover table-bordered table-lp" >   
                                    <tbody>                                     
                                        <tr class="active tb-hd">        
                                            <th>Sl. No</th>        
                                            <th>Mobile Number</th>        
                                            <th>Edit</th> 
                                            <th>Delete</th> 
                                        </tr>    
                                    </tbody>
                                    <tbody class="users gateways notifyPhoneList" style="text-align: center;">

                                   </tbody>
                                                     
                                </table>
				</div>
                                <div id='loader'><img src="../img/loader.gif"/> Loading Data</div>
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
 <script src="../js/intlTelInput.js"></script>
 <script src="../js/utils.js"></script>

<script type="application/javascript">
    $("#phone_notify").intlTelInput();
    
    //
    getNotificationPhoneNumbers();
</script>