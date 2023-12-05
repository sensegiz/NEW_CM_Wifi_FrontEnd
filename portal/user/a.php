<?php
	include_once 'page_header_map.php';
	include './user_menu.php';
?>

<h1>Map Center</h1>
<p id="">Visualise Coins</p>

<p id="gateway"></p>

<div class="detail-content" style="background-color: #fff;">
    <span class="error"></span>
    <span class="success"></span>
    <div id="image-map"></div>
    <div class="afterAdd">
        <div class="jumbotron">
            <h1>Success</h1>
            <p>All Coins added to location<br />Click to go back to the Home Page</p>
            <p><a class="btn btn-primary btn-lg" href="e.php" role="button">Map Center</a></p>
        </div>
    </div>
</div>
</div>

<?php
include_once 'page_footer_user.php';
?>

<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css">
</script>
<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


<script>
$('.afterAdd').hide();

var location_id = localStorage.getItem("locationId");
var location_name = localStorage.getItem("locationName");
var location_image = localStorage.getItem("locationImage");
console.log(location_id);
console.log(location_name);
console.log(location_image);

// create the map
var map = L.map('image-map', {
    minZoom: 18,
    maxZoom: 20,
    center: [0, 0],
    zoom: 18,
    crs: L.CRS.Simple
});

var coin = L.icon({
    iconUrl: 'uploads/marker.png',
    iconSize: [25, 25],
});

// dimensions of the image
var w = 2000,
    h = 1500,

    var server_address = getBaseAddressUser();
// leaflet map overlay image(location) url
url = server_address + '/sensegiz-dev/portal/user/user_uploads/' + location_image + '';

// calculate the edges of the image, in coordinate space
var southWest = map.unproject([0, h], map.getMaxZoom() - 1);
var northEast = map.unproject([w, 0], map.getMaxZoom() - 1);
var bounds = new L.LatLngBounds(southWest, northEast);

// add the image overlay, so that it covers the entire map
L.imageOverlay(url, bounds).addTo(map);

// tell leaflet that the map is exactly as big as the image
map.setMaxBounds(bounds);

var newMarker;

map.on('click', function(e) {

    console.log(count);
    console.log(coinNum);

    if (newMarker) {
        map.removeLayer(newMarker);
        console.log('hi');
    }

    var gatewayId = localStorage.getItem("gatewayId");
    console.log(gatewayId);
    getCoin(gatewayId);

    newMarker = new L.marker(e.latlng, {
        icon: coin
    }).addTo(map);
    console.log('marker added');

    lat = e.latlng.lat;
    lng = e.latlng.lng;

    var popUpForm =
        '<div style="display:block;"><span class="addError"></span><form><input type="hidden" id="c_id" name="coin_id" value="0"/><input type="hidden" id="edit_id" name="edit_id" value="0"/>select a coin<select type="number" class="coin_id" name="id"></select><br><br><input type="button" class="addCoin" value="Add Coin"></form></div>';

    $('#coin_lat').val(lat);
    $('#coin_lng').val(lng);


    newLatLng = newMarker.getLatLng().lat + ' ' + newMarker.getLatLng().lng;

    var popLocation = e.latlng;
    var popup = L.popup().setLatLng(popLocation).setContent(popUpForm).openOn(map);

    getGatewayLocation(location_id);

    addGetCoin();

    $(".coin_id").change(function() {
        var selectedValue = $(this).val();
        console.log("lol");
        console.log(selectedValue);
        $("#c_id").val(selectedValue);
    });

});

var count = 0;
var coinNum = 0;
</script>