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


            <h1>Add Emails</h1><hr>
                <p id="">(Add email id's to get notifications)</p>
            <div class="detail-content" style="background-color: #fff;">
                
                <div class="lp-det" style="margin-top:30px">
                    
                       
                        
                      
                            <div class="alert alert-info alert-dismissable col-sm-8">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>NOTE:</strong> Please note that the notification emails might go into your Spam folder!
					
				</div>	
			
			  <div class="table-responsive"><br>
 					<span class='error'></span>
                        <span class='success'></span>
                            <form action="" method="" id="emailNotifyForm">
                                <h6 class="labh6">Email</h6>
                                        <input type="hidden" id="edit_id" name="edit_id" value="0"/>
                                        
                                        <input type="text" name="email_notify" id="email_notify" class="validation input-new"/>
                                        <span class="log-last">
                                            <input type="button" class="save addMails" value="Save"/></a>
                                        </span>
                                
                            </form>
                            <hr/>
                            
                            <h3>Emails List</h3>
                            
                            <div class="emailList">
                                                                                  
                                <table class="table table-hover table-bordered table-lp">   
                                    <tbody>                                     
                                        <tr class="active tb-hd">        
                                            <th>Sl. No</th>        
                                            <th>Email Id</th>        
                                            <th>Edit</th> 
                                            <th>Delete</th> 
                                        </tr>    
                                    </tbody>
                                    <tbody class="users gateways notifyEmailList" style="text-align: center;">
<!--                                        <tr>
                                            <td>1</td>
                                            <td>vinay@cumulations.com</td>
                                            <td><span class="editUser" data-email="" data-phone="" data-uid=""><img src="../img/edit.png" width="16" height="16"/></span></td>                                     
                                        </tr>-->
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

<script type="application/javascript">
    getNotificationEmailIds();
</script>