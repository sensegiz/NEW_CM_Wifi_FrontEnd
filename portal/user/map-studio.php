<?php 
include_once('page_header_user1.php');
?>
<div class="content userpanel">
<style>

.active a label{
    background-color: #fff !important;
    color: #333;
}




</style>


        	


<!-- <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"/> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!-- <script type="text/javascript" src="js/bootstrap.min.js"></script> -->
 

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" type="text/css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>


<?php 
    include './user_menu.php';
?>
<div class="container">
			<div class="col-12 col-sm-12 mains">

			
			<h1>Map Center</h1>
				
                	<p id="">Visualise Coins</p>
			<hr>
            		<div class="detail-content" style="background-color: #fff;margin-top:0px">
                
                		<div class="lp-det"style="margin-top:0px">
                        		<span class='error'></span>
					<span class='loc-msg'></span>
                        		<span class='success-msg'></span>

                        		<div class=" add-location">
                            
                            			<form action="" method="" id="LocationForm">
                                        		<input type="hidden" id="edit_id" name="edit_id" value="0"/>
							<input type="hidden" id="gwid" value="" />
							<p class="alloi"></p>
                                			<h5 class="labh6">Add New Location</h5>
							<div class="row">
								<div class="col-sm-4">
			                				<h6 class="labh6">Name</h6>
                                        				<input type="text" name="location_name" id="location_name" class="validation input-new"/>
								</div>
								<div class="col-sm-4">
	                                				<h6 class="labh6">Description</h6>
                                        				<input type="text" name="loc_desc" id="location_description" class="validation input-new"/>
								</div>
								<div class="col-sm-4">
									<h6 class="labh6">Floor Plan</h6>
									<p style="margin-bottom:8px !important">* Only .jpeg .png .jpg file formats accepted.</p>
									<input type="file" id="file" name="file" />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<h6 class="labh6">Gateway</h6>																		
									<select multiple class="gateway-list"></select>
								</div> <br>
								<div class="col-sm-4">
									<h6 class="labh6"></h6>
                                            				<input type="button" id="addImage" value="Create New Location"/>			                    				
								</div>
							</div>
                            			</form>
                            
                        		</div>
					<br>
                			<div class="row">
						<div class="col-sm-4 locheads">
							<h6 class="labh6">User Locations</h6>
							<button class="save addShow">Add New Location</button>
							<br><br>
							
						</div>
					</div>
					<div class="container-fluid">
					<div class="row locations">
					</div>
    
        			<p id="msg"></p>
        			<input type="hidden" id="fn" value=""></p>
        			<input type="hidden" id="apikey" value=""></p>

        			<input type="hidden" id="uid" value=""></p>

 
                		</div>                         
            		</div>
        	</div>

        </div>
	
</div>

</div>

<?php
    include_once('page_footer_user.php');
?>



<script type="text/javascript">

$(document).ready(function(){
	$('#addCoin').hide();
	$('.add-location').hide();
	$('.gateway-list').multiselect({
			maxHeight: 150, 
			includeSelectAllOption: true, 
			numberDisplayed: 2
			
		});
});



$('.addShow').on("click", function() {
	$('.error').html('');
	$('#LocationForm')[0].reset();
	$('.add-location').show();
	$('.addShow').hide();
});

getLocationGateways();
getLocation();

</script>