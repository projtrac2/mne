let map;
let markersArray = [];
let polyline = null;
let coordinates = [];

function initMap() { 
  map = new google.maps.Map(document.getElementById('map'), {
    center: { lat: 0.633948, lng: 35.048561 },
    zoom: 13,
    mapTypeId: 'satellite'
  });

  // map onclick listener 
  map.addListener('click', function (e) { 
    addMarker(e.latLng);
    drawPolyline();
  });

  // Create the search box and link it to the UI element.
  const input = document.getElementById("pac-input");
  const searchBox = new google.maps.places.SearchBox(input);

  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
  // Bias the SearchBox results towards current map's viewport.
  map.addListener("bounds_changed", () => {
    searchBox.setBounds(map.getBounds());
  });

  let markers = [];

  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener("places_changed", () => {
    const places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    markers.forEach((marker) => {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    const bounds = new google.maps.LatLngBounds();

    places.forEach((place) => {
      if (!place.geometry || !place.geometry.location) {
        console.log("Returned place contains no geometry");
        return;
      }

      const icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25),
      };

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);
  });
}

// define function to add marker at given lat & lng
function addMarker(latLng) {
  let marker = new google.maps.Marker({
    map: map,
    position: latLng,
    draggable: true
  });

  // add listener to redraw the polyline when markers position change
  marker.addListener('position_changed', function () {
    drawPolyline();
  });

  //store the marker object drawn in global array
  markersArray.push(marker);

  let coord = JSON.stringify(latLng);
  let coordinates = JSON.parse(coord); 
  let coordinates_inputs = `
  <input type="hidden" name="lat[]" value="${coordinates.lat}">
  <input type="hidden" name="lng[]" value="${coordinates.lng}">`;
  $("#coordinates").append(coordinates_inputs);
}

// define function to draw polyline that connect markers' position
function drawPolyline() {
  let markersPositionArray = [];

  markersArray.forEach(function (e) {
    markersPositionArray.push(e.getPosition());
  });

  if (polyline !== null) {
    polyline.setMap(null);
  }

  polyline = new google.maps.Polyline({
    map: map,
    path: markersPositionArray,
    strokeOpacity: 0.4
  });
}

$(document).keypress(
    function(event){
      if (event.which == '13') {
        event.preventDefault();
      }
  });
  