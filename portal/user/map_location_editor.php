<?php 
include_once('page_header_user1.php');
?>

<style>

.active a label{
    background-color: #fff !important;
    color: #333;
}

.input-group h6, .input-group input {
    float: none; /* if you had floats before? otherwise inline-block will behave differently */
    display: inline-block;
    vertical-align: middle;    
}

.multiselect {
    width:20em;
    height:110px;
    border:solid 1px #c0c0c0;
    overflow:auto;
}
 
.multiselect label {
    display:block;
}
 
.multiselect-on {
    color:#ffffff;
    background-color:#879690;
}

::after, ::before {

    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;

}
::before, ::after {

    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;

}
element {

}

.alert {

    padding: 22px;
  
    border: 1px solid transparent;
        border-top-color: transparent;
        border-right-color: transparent;
        border-bottom-color: transparent;
        border-left-color: transparent;
    border-radius: 4px;

}
@media only screen and (min-width: 200px) and (max-width:800px){
.navbar-fixed-top, .navbar-fixed-bottom {

 
    margin-top: -40px;

}
}
@media only screen and (min-width: 768px) and (max-width:900px){
.navbar-fixed-top, .navbar-fixed-bottom {

    position:static;
    right: 0;
    left: 0;
    z-index: 1030;
    -webkit-transform: translate3d(0,0,0);
    -o-transform: translate3d(0,0,0);
    transform: translate3d(0,0,0);
	margin-top:0px;
}
}
@media only screen and (min-width: 495px) and (max-width:767px){

.mains { 

  margin-top:14px;
}
}
@media only screen and (min-width: 200px) and (max-width:495px){

.mains { 

  margin-top:16px;
}
}
</style>

<!--<div class="col-lg-10 pad0 ful_sec"> 
        <div class="row pad0">-->
        	<div class="content userpanel">








<?php 
    include './user_menu_map.php';
?>
<div class="container">
			<div class="col-12 col-sm-12 mains">

	<h1>Map Center</h1>
             	<p id="">Visualise Coins</p><hr>
        	<div class="detail-content" style="background-color: #fff;">
                
               		<div class="lp-det" style="margin-top:30px">
                      		
				
				<div class="container-fluid">
  					<h1>Location Details</h1> <br/><br/>
					<span class='error'></span>
                       			<span class='success'></span> <br/>
  					<form class="form-horizontal" action="">
						
    						<div class="form-group">
      							<label class="control-label col-sm-2" for="location_name">Location Name:</label>
      							<div class="col-sm-4">
        							<input type="text" class="form-control" id="location_name" name="location_name">
      							</div>		
    						</div>
    						<div class="form-group">
      							<label class="control-label col-sm-2" for="location_desc">Location Description:</label>
      							<div class="col-sm-4">          
        							<input type="text" class="form-control" id="location_desc" name="location_desc">
      							</div>
    						</div>
						
						<div class="form-group">
      							<label class="control-label col-sm-2" for="location_gateways">Location Gateways:</label>																										
							<div class="multiselect col-sm-3"style="margin-bottom:17px;margin-left:15px">
    								
							</div>
							<div class="container-fluid">
							<div class="alert alert-info alert-dismissable col-sm-offset-1 col-sm-5" style="padding-top:3px;padding-bottom:3px">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>NOTE:</strong> Removing an already mapped gateway from this location, will remove all the coin locations mapped for that gateway.
								<br/>Please make sure you verify the gateways selected before submitting the changes.
							</div>
						</div> 
							<div class="container-fluid">								
						<div class="form-group">
      							<label class="control-label col-sm-2" for="location_plan">Location Plan:</label>
      							<div class="col-sm-4">          
        							<img src="" class="img-rounded locationPlan" alt="Location Plan" height="150px">
      							</div>
    						</div>
						<div class="form-group">        
      							<div class="col-sm-offset-2 col-sm-1"style="margin-bottom:8px">
        							<input type="button" class="btn btn-default editLocation" style="background-color:#d4c8c8;" value="Submit"/>			
      							</div>
							<div class="col-sm-1"></div>
							<div class="col-sm-4">
								<a href="map-studio.php"><input type="button" class="btn btn-default" style="background-color:#d4c8c8;" value="Go Back"/></a>			        							
      							</div>

    						</div>                            			
					</form>
                            
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
var server_address = getBaseAddressUser();
	var basePathUser = server_address+'/sensegiz-dev/user/';
   	
	var apiUrlGateways            = "gateways";
	var apieditUserLocation = "edit-user-location";

	var location_id = localStorage.getItem("location_id");
	var location_name = localStorage.getItem("location_name");
	var location_image = localStorage.getItem("location_image");
	var location_desc = localStorage.getItem("location_desc");

	getGatewayLocation(location_id);
		

	var loc_plan = server_address+'/sensegiz-dev/portal/user/user_uploads/'+location_image+'';
	var gatewayId = localStorage.getItem("gatewayId");
	var g_id = localStorage.getItem("g_id");

		
	var gid_split  = g_id.split(",");

	var q = gid_split.length;
	var gIDlist = [];
	
	 for(i=0; i < q; i++) {
		var g_id = gid_split[i];			
		gIDlist.push(g_id);
         }	
      
	getUserGateways(gatewayId, gIDlist);

$(document).ready(function(){
	
	$('#location_name').val(location_name);
	$('#location_desc').val(location_desc);	
	$(".img-rounded").attr('src', loc_plan);

	
	$(function() {
     		$(".multiselect").multiselect();
	});
		
		
});



jQuery.fn.multiselect = function() {
    $(this).each(function() {
        var checkboxes = $(this).find("input:checkbox");
        checkboxes.each(function() {
            var checkbox = $(this);
            // Highlight pre-selected checkboxes
            if (checkbox.prop("checked"))
                checkbox.parent().addClass("multiselect-on");
 
            // Highlight checkboxes that the user selects
            checkbox.click(function() {
                if (checkbox.prop("checked"))
                    checkbox.parent().addClass("multiselect-on");
                else
                    checkbox.parent().removeClass("multiselect-on");
            });
        });
    });
};



$(document).on('click', '.editLocation', function() {
	var uid     =  $('#sesval').data('uid');
        var apikey  =  $('#sesval').data('key');

	var location_name = $('#location_name').val();
        var location_description = $('#location_desc').val();

	var gateways = [];
	 
        $.each($(".multiselect input:checked"), function(){            
                    gateways.push($(this).val());
        });
       
	console.log('In submit edit Location');	

	var postdata = {
		  location_id:location_id,
                  location_name:location_name,
                  location_description:location_description,                  
                  gateway_id:gateways                  
        }

	var r = confirm("Please make sure you have verified the Gateway List. Click OK to proceed with the changes.");
	console.log(r);

	if(uid!='' && apikey!='' && r==true){
		$.ajax({
			url: basePathUser + apieditUserLocation,
                      	type: 'POST',                       
                        data: JSON.stringify(postdata),
                        contentType: 'application/json; charset=utf-8',
                        dataType: 'json',
                        async: false,                           
                      	beforeSend: function (xhr){                                                                                                                                                   
                		xhr.setRequestHeader("uid", uid);
                                xhr.setRequestHeader("Api-Key", apikey);                                  
                        },
                        success: function(data, textStatus, xhr) {
				if(xhr.status == 200 && textStatus == 'success') {
					if(data.status == 'success') {					
						$('.success').html('The location has been successfully updated!');
						setTimeout(function(){  $('.success').html(''); }, 5000);
					}
					if(data.status == 'gateway_exists') {
						$('.error').html('One of the newly selected Gateway is already assigned to another location!');
						setTimeout(function(){  $('.error').html(''); }, 5000);
					}
                            	}
			},
			error: function(errData, status, error){ 
				console.log('Error......');    
				console.log(errData);  
				                                        				                          	                           
                     	}
		});	
	}
});


function getUserGateways(gatewayId, gIDlist) 
{          

	var uid     =  $('#sesval').data('uid');
        var apikey  =  $('#sesval').data('key');
        if(uid!='' && apikey!=''){
        	$.ajax({
        		url: basePathUser + apiUrlGateways,
        		headers: {
                        	'uid':uid,
                                'Api-Key':apikey
                       	},                            
                      	type: 'GET',						
                    	contentType: 'application/json; charset=utf-8',
                     	dataType: 'json',
                       	async: false,                           
                   	beforeSend: function (xhr){                                
                                xhr.setRequestHeader("uid", uid);
                                xhr.setRequestHeader("Api-Key", apikey);                                
                     	},
                        success: function(data, textStatus, xhr) {				
                                var gw_li_html =   '';				
                                if(data.records.length > 0){
                                    records = data.records;
					
                                    $.each(records, function (index, value) {					
                                        var gateway_id  =  value.gateway_id;
					var ug_id = value.ug_id;                                       					
										
					if(jQuery.inArray(ug_id, gIDlist) != '-1'){
						gw_li_html += '<label><input type="checkbox" name="option[]" checked value="'+ug_id+'" /> '+gateway_id+'</label>';
					}else{
						gw_li_html += '<label><input type="checkbox" name="option[]" value="'+ug_id+'" /> '+gateway_id+'</label>';
					}
                                    });                        
                                }                                                              

                                $('.multiselect').html(gw_li_html);                  
			
                    	},
                        error: function(errData, status, error){
                                    if(errData.status==401){
                                        var resp = errData.responseJSON; 
                                        var description = resp.description; 
                                        $('.logout').click();
                                        alert(description);
                                        
                                    }
                        }                            
              	});
    	 }
}  


</script>