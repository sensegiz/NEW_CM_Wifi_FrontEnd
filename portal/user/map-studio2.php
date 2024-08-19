
<?php 
include_once('page_header_user1.php');
?>

<div class="content userpanel">
<style>

.active a label{
    background-color: #fff !important;
    color: #333;
}

#map{
		width: 750px;
		height: 600px;
 }

</style>

<!-- <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"/> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!-- <script type="text/javascript" src="js/bootstrap.min.js"></script> -->
 

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" type="text/css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

<script src="../public/leaflet/leaflet.js?<?php echo time(); ?>"></script>
<link rel="stylesheet" href="../public/leaflet/leaflet.rrose.css"></script>
<script src="../public/leaflet/leaflet.rrose-src.js"></script>


<link rel="stylesheet" href="../public/leaflet/Control.FullScreen.css"></script>
<script src="../public/leaflet/Control.FullScreen.js"></script>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

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
								<!-- <div class="col-sm-4">
									<h6 class="labh6">Floor Plan</h6>
									<p style="margin-bottom:8px !important">* Only .jpeg .png .jpg file formats accepted.</p>
									<input type="file" id="file" name="file" />
								</div> -->
							</div>
							<div class="row">
								<div class="col-sm-4">
									<h6 class="labh6">Gateway</h6>																		
									<select multiple class="gateway-list"></select>
								</div> <br>
								<div class="col-sm-4">
									<h6 class="labh6"></h6>
                                    <input type="button" id="addMap" value="Create New Location"/>			                    				
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
<div id="map" style="height: 500px;"></div>
 <!-- Input fields for Latitude and Longitude -->
 <div>
        <label for="lat">Latitude:</label>
        <input type="text" id="lat" value="51.505">
        <label for="lng">Longitude:</label>
        <input type="text" id="lng" value="-0.09">
        <button onclick="updateMap()">Load Location</button>
    </div>
</div>




<?php
    include_once('page_footer_user.php');
?>



<script type="text/javascript">

 console.log("call script");
 
 var map = L.map('map').setView([15.8316, 74.5021], 13);

// Add a tile layer to the map (e.g., OpenStreetMap)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Add a marker to the map
var marker = L.marker([15.8316, 74.5021],13).addTo(map)
	.bindPopup('A pretty CSS3 popup.<br> Easily customizable.')
	.openPopup();

	function updateMap() {
            var lat = document.getElementById('lat').value;
            var lng = document.getElementById('lng').value;
            
            // Update the map view
            map.setView([lat, lng], 13);

            // Move the marker to the new location
            marker.setLatLng([lat, lng]);

            // Update the popup text
            marker.getPopup().setContent('New location: ' + lat + ', ' + lng).openOn(map);

			localStorage.setItem('latitude', lat);
            localStorage.setItem('longitude', lng);
        }

		map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            // Update the lat/lng input fields
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;

            // Move the marker to the clicked location
            marker.setLatLng([lat, lng]);

            // Update the popup text
            marker.getPopup().setContent('Clicked location: ' + lat + ', ' + lng).openOn(map);
        });

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

