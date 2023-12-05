<?php 

include_once('page_header_user1.php');

?>

 <!--<div class="col-lg-10 pad0 ful_sec"> 
      	<div class="row pad0">-->
        <div class="content userpanel">

<?php 
    include './user_menu.php';
?>
<style>
.table{
	//width:50%;
}
.success {

    color: green;
    position: relative;
    bottom: 10px;
    font-size: 14px !important;
    font-weight: bold;
    float: left;

}
.error {

    color: red;
    position: relative;
    left: 8px;
    bottom: 1px;
    font-size: 12px;
    float:left;

}

#back2Top {
    width: 40px;
    line-height: 40px;
    overflow: hidden;
    z-index: 999;
    display: none;
    cursor: pointer;
    -moz-transform: rotate(270deg);
    -webkit-transform: rotate(270deg);
    -o-transform: rotate(270deg);
    -ms-transform: rotate(270deg);
    transform: rotate(270deg);
    position: fixed;
    bottom: 29px;
    right: 8;
	margin-right:8px;
    //background-color: #DDD;
    color: #555;
    text-align: center;
    font-size: 30px;
    text-decoration: none;
}
#back2Top:hover {
    background-color: #DDF;
    color: #000;
}
ul.b {
    list-style-type: square;
    margin-top: -22px;
    margin-left: 25px;
}

@media only screen and (min-width: 200px) and (max-width:900px){
.table{
	width:100%;
}

}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<a id="back2Top" title="Back to top" href="#">&#10148;</a>
<div class="container">
<div class="col-sm-12 col-12 mains">


            <h1>Add Emails/Phone Numbers</h1><hr>
                <p id="">(Add email id's/phone no's  to get notifications)</p><br>
            
                    <div class="alert alert-info alert-dismissable infos">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>NOTE</strong>
			<ul class="b">
  				<li>Please note that the notification emails might go into your Spam folder.</li>
  				<li>Please make sure that you have selected the correct country code to add the Mobile Number.</li>
 				 
				</ul> 
				 
                    </div>
               
 
			   
                        <span class='success'></span><br>
			<span class='error'></span><br>
		 
                <div class="col-sm-4"style="margin-bottom:20px">
			
		<button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#demo" id="arrowicon" href="javascript:void(0)" rel="me">Email ID <i class="fa fa-angle-down"></i></button>
  			<div id="demo" class="collapse">


     
                            <form action="" method="" id="emailNotifyForm">
                                <h6 class="labh6">Email</h6>
                                        <input type="hidden" id="edit_id" name="edit_id" value="0"/>
                                        
                                        <input type="text" name="email_notify" id="email_notify" class="validation input-new"/>
                                        <span class="log-last">
                                            <input type="button" class="save addMails" value="Save" data-toggle="collapse" data-target="#demo"/></span>
                                
                            </form>


</div>
                    
     </div>                  
              

<div class="col-sm-5">

    <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#demo1" id="arrowicon1" href="javascript:void(0)" rel="me">Mobile Number <i class="fa fa-angle-down"></i></button>
  <div id="demo1" class="collapse">
                  
                            <form action="" method="" id="phoneNotifyForm">
                                <h6 class="labh6">Mobile Number</h6>
                                        <input type="hidden" id="edit_id" name="edit_id" value="0"/>
                                        
                                        <input type="text" name="phone_notify" id="phone_notify" placeholder="Mobile Number"  onkeypress="return isNumberKey(event);" class="validation input-new"/>
                                        <span class="log-last">
                                            <input type="button" class="save addPhone" value="Save" data-toggle="collapse" data-target="#demo1"/>
                                        </span>
                                
                            </form>
                         
</div>
</div>
</div>
</div>



                            <!--<div class="col-sm-2 col-2"></div>-->
				<div class="container">
                            <div class="col-sm-12 col-12 emailList">
                                              <h3>Emails List</h3>                                       
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
                
                            
                             <!--<div class="col-sm-2 col-2"></div>-->
					<div class="container">
                            <div class="col-sm-12 col-12 emailList">
                                    <h3>Mobile Numbers List</h3>                                             
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
<?php
    include_once('page_footer_user.php');
?>
 <script src="../js/intlTelInput.js"></script>
 <script src="../js/utils.js"></script> 
<script type="application/javascript">
    getNotificationEmailIds();
</script>
<script type="application/javascript">
    $("#phone_notify").intlTelInput();
    
    //
    getNotificationPhoneNumbers();
</script>
<script>
$('#arrowicon').click(function() {
  $(this).find('i').toggleClass('fa fa-angle-down fa fa-angle-up')
});

$('#arrowicon1').click(function() {
  $(this).find('i').toggleClass('fa fa-angle-down fa fa-angle-up')
});



/*Scroll to top when arrow up clicked BEGIN*/
$(window).scroll(function() {
    var height = $(window).scrollTop();
    if (height > 150) {
        $('#back2Top').fadeIn();
    } else {
        $('#back2Top').fadeOut();
    }
});
$(document).ready(function() {
    $("#back2Top").click(function(event) {
        event.preventDefault();
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    });

});

</script> 