var coords = [];
var finalCoordinates = [];
var watchID = null;
var geoLoc;
var dis = 10;
var map, infoWindow;
var start;
var point;
var dest;

$(document).ready(function () {
  google.maps.event.trigger(map, "resize");
  var lat = parseFloat($("#lat").val());
  var long = parseFloat($("#long").val());

  var center = new google.maps.LatLng(lat, long);
  map = new google.maps.Map(document.getElementById("map"), {
    center: center,
    zoom: 9,
  });

  $("#output").change(function (e) {
    e.preventDefault();
    var opid = $(this).val();
    if (opid != "") {
      static_map(opid);
    }
  });
});

// var dis = $('#distance').val();
var polylineOptions = {
  strokeColor: "#C83939",
  strokeOpacity: 1,
  strokeWeight: 4,
};
var colors = ["#00FF00", "#4682B4", "#FFFF00", "#FF00FF", "#00FFFF"];
var polylines = [];

// function to handle errors
function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  console.log("errors found");
}

// function to initialize the map
function static_map(opid) {
  google.maps.event.trigger(map, "resize");
  var lat = parseFloat($("#lat").val());
  var long = parseFloat($("#long").val());

  var center = new google.maps.LatLng(lat, long);
  var map = new google.maps.Map(document.getElementById("map"), {
    center: center,
    zoom: 9,
  });

  var map_markers_url = "markers/markers.php?project_markers=1&opid=" + opid;

  // Change this depending on the name of your PHP or XML file
  downloadUrl(map_markers_url, null, function (data) {
    var xml = data.responseXML;
    var markers = xml.documentElement.getElementsByTagName("marker");

    Array.prototype.forEach.call(markers, function (markerElem) {
      var id = markerElem.getAttribute("projid");
      var projname = markerElem.getAttribute("projname");
      var point = new google.maps.LatLng(
        parseFloat(markerElem.getAttribute("lat")),
        parseFloat(markerElem.getAttribute("lng"))
      );

      var marker = new google.maps.Marker({
        position: point,
        map: map,
        title: projname,
      });
    });
  });
}

// function to initialize the map
function area_map(projid, stid) {
  google.maps.event.trigger(map, "resize");
  var lat = parseFloat($("#lat").val());
  var long = parseFloat($("#long").val());

  var center = new google.maps.LatLng(lat, long);
  var map = new google.maps.Map(document.getElementById("map"), {
    center: center,
    zoom: 12,
  });

  (directionsService = new google.maps.DirectionsService()),
    (directionsDisplay = new google.maps.DirectionsRenderer({
      map: map,
      suppressMarkers: true,
    }));

  infoWindow = new google.maps.InfoWindow();
  var map_markers_url = "markers/markers?map_id=" + map_id;

  // Change this depending on the name of your PHP or XML file
  downloadUrl(map_markers_url, null, function (data) {
    var xml = data.responseXML;
    var markers = xml.documentElement.getElementsByTagName("marker");
    if (window.poly) window.poly.setMap(null);
    var pp = [],
      bounds = new google.maps.LatLngBounds();

    Array.prototype.forEach.call(markers, function (markerElem) {
      var lat = parseFloat(markerElem.getAttribute("lat"));
      var lng = parseFloat(markerElem.getAttribute("lng"));
      pp.push(new google.maps.LatLng(lat, lng));
    });

    window.poly = new google.maps.Polygon({
      paths: pp,
      strokeColor: "#FF0000",
      strokeOpacity: 0.8,
      strokeWeight: 3,
      fillColor: "#FF0000",
      fillOpacity: 0.35,
    });
    window.poly.setMap(map);
    map.fitBounds(bounds);
  });
}

// function to initialize the map
function waypoint_map(projid, stid) {
  var lat = parseFloat($("#lat").val());
  var long = parseFloat($("#long").val());

  var center = new google.maps.LatLng(lat, long);
  var map = new google.maps.Map(document.getElementById("map"), {
    center: center,
    zoom: 12,
  });

  (directionsService = new google.maps.DirectionsService()),
    (directionsDisplay = new google.maps.DirectionsRenderer({
      map: map,
      suppressMarkers: true,
    }));
  infoWindow = new google.maps.InfoWindow();
  var map_markers_url = "markers/markers?map_id=" + map_id;

  // Change this depending on the name of your PHP or XML file
  downloadUrl(map_markers_url, null, function (data) {
    var xml = data.responseXML;
    var markers = xml.documentElement.getElementsByTagName("marker");
    var count = 0;

    Array.prototype.forEach.call(markers, function (markerElem) {
      count++;
      var id = markerElem.getAttribute("projid");
      if (count == 1) {
        start = new google.maps.LatLng(
          parseFloat(markerElem.getAttribute("lat")),
          parseFloat(markerElem.getAttribute("lng"))
        );
        dest = point = start;
      } else if (count != markers.length && count != 1) {
        if (
          markerElem.getAttribute("latpoint") == "" ||
          markerElem.getAttribute("lngpoint") == ""
        ) {
          point = dest = start;
        } else {
          point = new google.maps.LatLng(
            parseFloat(markerElem.getAttribute("lat")),
            parseFloat(markerElem.getAttribute("lng"))
          );
        }
      } else if ((count = markers.length)) {
        dest = new google.maps.LatLng(
          parseFloat(markerElem.getAttribute("lat")),
          parseFloat(markerElem.getAttribute("lng"))
        );
      }
      calculateAndDisplayRoute(directionsService, start, point, dest);
    });
  });
}

// function to calculate the route between waypoints and the finish lane
function calculateAndDisplayRoute(directionsService, start, point, dest) {
  var waypts = [
    {
      location: point,
      stopover: false,
    },
  ];

  directionsService.route(
    {
      origin: start,
      destination: dest,
      waypoints: waypts,
      optimizeWaypoints: true,
      travelMode: google.maps.TravelMode.DRIVING,
    },
    function (response, status) {
      if (status === google.maps.DirectionsStatus.OK) {
        var directionsDisplay = new google.maps.DirectionsRenderer({
          map: map,
          suppressMarkers: true,
        });
        directionsDisplay.setOptions({
          directions: response,
        });

        var route = response.routes[0];
        renderDirectionsPolylines(response, map);
      } else {
        window.alert("Directions request failed due to " + status);
      }
    }
  );
}

// function to render waypoints
function renderDirectionsPolylines(response) {
  var bounds = new google.maps.LatLngBounds();
  for (var i = 0; i < polylines.length; i++) {
    polylines[i].setMap(null);
  }

  var legs = response.routes[0].legs;
  for (i = 0; i < legs.length; i++) {
    var steps = legs[i].steps;
    for (j = 0; j < steps.length; j++) {
      var nextSegment = steps[j].path;
      var stepPolyline = new google.maps.Polyline(polylineOptions);
      stepPolyline.setOptions({
        strokeColor: colors[i],
      });
      for (k = 0; k < nextSegment.length; k++) {
        stepPolyline.getPath().push(nextSegment[k]);
        bounds.extend(nextSegment[k]);
      }
      polylines.push(stepPolyline);
      stepPolyline.setMap(map);
      // route click listeners, different one on each step
      google.maps.event.addListener(stepPolyline, "click", function (evt) {
        infowindow.setContent(
          "you clicked on the route<br>" + evt.latLng.toUrlValue(6)
        );
        infowindow.setPosition(evt.latLng);
        infowindow.open(map);
      });
    }
  }
  map.fitBounds(bounds);
}

//function  to fetch data from the database;
function downloadUrl(url, body, callback) {
  var request = window.ActiveXObject
    ? new ActiveXObject("Microsoft.XMLHTTP")
    : new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.readyState == 4) {
      request.onreadystatechange = doNothing;
      callback(request, request.status);
    }
  };

  request.open("POST", url, true);
  request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  request.send(body);
}

function doNothing() {}
