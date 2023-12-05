<!DOCTYPE html>
<html>
<head>
	<title>Leaflet Quick Start Guide Example</title>
	<meta charset="utf-8" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
</head>
<body>
	<div id="map" style="width: 800px; height: 800px"></div>
	<div id="testTimer"></div>

	<script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
	<script>

	var lat = 46.566414;
  var lng =  2.4609375;
  var zoom =  5;
  
  var map = new L.Map('map');
  
  var server_address = getBaseAddressUser();
  var osmUrl= server_address+'/sensegiz-dev/portal/user/uploads/untitled.png';
  var osmAttrib='Map data &copy; OpenStreetMap contributors';
  var osm = new L.TileLayer(osmUrl, {minZoom: 3, maxZoom: 8, attribution: osmAttrib});
  map.addLayer(osm);

	    var coin = L.icon ({
        	iconUrl: 'uploads/marker.png',
	    iconSize:     [25, 25],
	    });

  
  map.setView(new L.LatLng(lat, lng), zoom);
  
  var theTimer = false;
  var theMarkerName = false;
  var thePopup = false;
  
  /* Marker 1 */
  var marker1 = new L.Marker([48.862312, 2.332317], {icon: coin});
  
  marker1.bindPopup('Time is <span id="time"></span>.');
  marker1.addTo(map);
  
  marker1.on('popupopen', function(e) {
     thePopup = e.popup;
     theMarkerName = 'Marker 1';
     refreshTime();
     theTimer = setInterval(refreshTime, 2000);
  });
  marker1.on('popupclose', function(e) {
     clearInterval(theTimer);
  });
  
  /* Marker 2 */
  var marker2 = new L.Marker([45.862312, 2.332317], {icon: coin});
  
  marker2.bindPopup('Time is <span id="time"></span>.');
  marker2.addTo(map);
  
  marker2.on('popupopen', function(e) {
     thePopup = e.popup;
     theMarkerName = 'Marker 2';
     refreshTime();
     theTimer = setInterval(refreshTime, 2000);
  });
  marker2.on('popupclose', function(e) {
     clearInterval(theTimer);
  });
  
  
  
  
  function refreshTime() {
      var d = new Date();
      thePopup.setContent(d.toLocaleTimeString() + ' for ' + theMarkerName);
      // alternatively, you can just update element time
      //document.getElementById("time").innerHTML = d.toLocaleTimeString() + ' for ' + theMarkerName;
      document.getElementById("testTimer").innerHTML = d.toLocaleTimeString() + ' for ' + theMarkerName;
  }


	</script>
</body>
</html>
