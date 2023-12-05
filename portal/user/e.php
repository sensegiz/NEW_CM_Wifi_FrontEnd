<?php 
include_once('page_header_user.php');
?>

 <div class="col-lg-10 pad0 ful_sec"> 
        <div class="row pad0">
        <div class="content userpanel">

<?php 
    include './user_menu.php';
?>
            <h1>Map Center</h1>
                <p id="">Visualise Coins</p>
            <div class="detail-content" style="background-color: #fff;">
                
                <div class="lp-det" style="margin-top:30px">
                        <span class='error'></span>
                        <span class='success'></span>

                        <div class="table-responsive add-location">
                            
                            <form action="" method="" id="emailNotifyForm">
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
					<p>* Only .jpeg .png .jpg file formats accepted.</p>
					<input type="file" id="file" name="file" />
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<h6 class="labh6">Gateway</h6>
					<select class="gateway-list"></select>
				</div>
				<div class="col-sm-4">
					<h6 class="labh6"></h6>
                                            <input type="button" id="addImage" value="Create New Location"/>
			                    <input type="button" id="addCoin" value="Assign Coins to Location."/>
				</div>
			</div>
                            </form>
                            
                        </div>

                <div class="row">
			<div class="col-sm-4">
				<h6 class="labh6">User Locations</h6>
				<button class="save addShow">Add New Location</button>
				<br><br>
			</div>
		</div>
    
        <p id="msg"></p>
        <input type="hidden" id="fn" value=""></p>
        <input type="hidden" id="apikey" value=""></p>

        <input type="hidden" id="uid" value=""></p>


<table class="table table-hover table-bordered table-lp ul-table" style="width: 50%;">
	<tbody>
		<tr class="active tb-hd">
			<th>Sl. No</th>
			<th>Location Name</th>
			<th>Location Description</th>
			<th>Location Image</th>
			<th>Monitor Location</th>
			<th>Add/Edit Coins</th>
			<th>Delete Location</th>
		<tr>
	</tbody>
	<tbody class="users gateways locationList" style="text-align: centre;">
	</tbody>
	
</table>
 
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
});

$('.addShow').on("click", function() {
	$('.add-location').show();
	$('.addShow').hide();
});

getLocationGateways();
getLocation();


$(".gateway-list").change(function () {
    var selectedValue = $(this).val();

    $("#gwid").val($(this).find("option:selected").attr("value"));
});


</script>