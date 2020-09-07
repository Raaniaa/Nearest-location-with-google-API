<html><head></head>
<body>
<style>  html,
  body,
  #map {
    height: 100%;
    margin: 0;
    padding: 0;
  }
</style>
<div id="map"></div>
<script src="https://kit.fontawesome.com/d7b241e829.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<!-- Replace the value of the key parameter with your own API key. -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBYG5g2aJ9TjMlbYk7E_VuFYKSvHC1Ee6Y&libraries=places&callback=initMap&v=weekly" async defer></script>
<script>var map;
var service;
var infowindow;
var pos;
var request;
var place;

function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: {
      lat: -34.397,
      lng: 190.644
    },
    zoom: 6
  });
  infoWindow = new google.maps.InfoWindow;

  getLocation();
  // getNearByPlaces();
  // callback();
}

function getLocation() {
  // Try HTML5 geolocation.
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      console.log("getLocation:" + pos.lat + "," + pos.lng);
      var marker = new google.maps.Marker({
        position: pos,
        map: map,
        icon: "http://maps.google.com/mapfiles/ms/micons/blue.png"
      })
      infoWindow.setPosition(pos);
      infoWindow.setContent('Location found.');
      infoWindow.open(map);
      map.setCenter(pos);
      getNearByPlaces(pos);
    }, function() {
      console.log("calling handleLocationError(true)");
      handleLocationError(true, infoWindow, map.getCenter());
    });
  } else {
    // Browser doesn't support Geolocation
    console.log("calling handleLocationError(false)")
    handleLocationError(false, infoWindow, map.getCenter());
  }

  infowindow = new google.maps.InfoWindow();
}

function getNearByPlaces(pos) {
  console.log("getNearByPlaces:" + pos.lat + "," + pos.lng);
  request = {
    location: pos,
    radius: '100',
    query: 'pharmacy  '
  };
  service = new google.maps.places.PlacesService(map);
  service.textSearch(request, callback);
 // service.nearbySearch(request, callback);

}

function callback(results, status) {
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    console.log("callback received " + results.length + " results");
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0; i < results.length; i++) {
      console.log(JSON.stringify(results[i]));
      place = results[i];
      var mark = createMarker(results[i]);
      bounds.extend(mark.getPosition());
    }
    map.fitBounds(bounds);
  } else console.log("callback.status=" + status);
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(browserHasGeolocation ?
    'Error: The Geolocation service failed.' :
    'Error: Your browser doesn\'t support geolocation.');
  infoWindow.open(map);
}

function createMarker(place) {
  var marker = new google.maps.Marker({
    map: map,
    position: place.geometry.location,
    icon: "http://maps.google.com/mapfiles/ms/micons/red.png"
  });

  google.maps.event.addListener(marker, 'click', function() {
    infowindow.setContent(place.name);
    infowindow.open(map, this);
  });
  return marker;
}

</script>
</body>
</html>