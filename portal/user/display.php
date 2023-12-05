<?php
include_once 'page_header_map.php';
?>
        <h1>Map Center</h1>
        <p id="">Visualise Coins</p>
            <div class="detail-content" style="background-color: #fff;">
        <span class="error"></span>
                <span class="success"></span>
                <div id="image-map"></div>
            </div>


<div class="fixfooter">
    <h3>Recent updates: <span id="slideCtrl"><img id="imgCtrl" src="../img/arrow-down-wht.png" width="20px" height="20px"/></span></h3>
    <div class="ntfn-box">
        <p id="notifyBar"></p>
    </div>
        
    <!--<span id="notifyBar"></span>-->
</div>



<?php
include_once 'page_footer_user.php';
?>
<script>
    // create the slippy map
    var map = L.map('image-map', {
        minZoom:18,
    maxZoom: 20,
    center: [0, 0],
    zoom: 18,
    crs: L.CRS.Simple
    });

    var apiUrlGetCoin = "get-coin";

    var coin = L.icon ({
        iconUrl: 'uploads/marker.png',
    iconSize:     [25, 25],
    });

    var server_address = getBaseAddressUser();

    // dimensions of the image
    var w = 2000,
    h = 1500,
<<<<<<< HEAD
    url = 'https://cm-testing.sensegiz.com/sensegiz-dev/portal/user/uploads/untitled.png';
=======
    url = server_address+'/sensegiz-dev/portal/user/uploads/untitled.png';
>>>>>>> karan-dev

    // calculate the edges of the image, in coordinate space
    var southWest = map.unproject([0, h], map.getMaxZoom()-1);
    var northEast = map.unproject([w, 0], map.getMaxZoom()-1);
    var bounds = new L.LatLngBounds(southWest, northEast);

    // add the image overlay, so that it covers the entire map
    L.imageOverlay(url, bounds).addTo(map);

    // tell leaflet that the map is exactly as big as the image
    map.setMaxBounds(bounds);

	var markersLayer = L.featureGroup().addTo(map);

    map.on('load', getCoin());

    function getCoin() {
		                
    var uid     =  $('#sesval').data('uid');
    var apikey  =  $('#sesval').data('key');

        if(uid!='' && apikey!=''){
            $.ajax({
            	url: basePathUser + apiUrlGetCoin,
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
                               
                var sc_html =   '';                               
                if(data.records.length > 0){
	                records = data.records;
			i = 0;
                	$.each(records, function (index, value) {
      				var coin_name = records[i].coin_name;
				var coin_nick = records[i].coin_nick;
				var coin_id   = records[i].coin_id;
				var id        = records[i].id;
				var coin_lat  = records[i].coin_lat;
				var coin_lng  = records[i].coin_lng;
                                     
	
				var popUpForm = '<p>hello</p>';			

				var newMarker = new L.marker([coin_lat, coin_lng], {icon: coin}).addTo(markersLayer).on('click', function(e) {var popLocation = [coin_lat, coin_lng];        var popup       = L.popup().setLatLng(popLocation).setContent(popUpForm).openOn(map);});						

				i++;                      
	                });                        
                }else {
			$('.error').html('0 Coins found. Add coins to monitor them on the map.');
			 }

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

	markersLayer.on("click", function(event) {

	});
</script>






