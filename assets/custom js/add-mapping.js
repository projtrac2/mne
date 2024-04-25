

$(document).ready(function () {
    getcurrent_location_static();
    $("#submit").hide();
});


function get_map_coordinates() {
    $.ajax({
        type: "POST",
        url: "assets/processor/add-project-map-assign-process",
        data: "get_coordinates",
        dataType: "json",
        success: function (response) {
            if (response.msg = true) {
                $("#lat").val(response.latitude);
                $("#long").val(response.longitude);
            } else {
                alert("contact the administrator to give define coordinates ");
            }
        }
    });
} 






// /////////
// view more 
///////////
function more(itemId, stid) {
    if (itemId) {
        $("#itemId").remove();
        $(".text-danger").remove();
        $(".form-input")
            .removeClass("has-error")
            .removeClass("has-success");
        $(".div-result").addClass("div-hide");

        $.ajax({
            url: "general-settings/selected-items/fetch-team-item",
            type: "post",
            data: { itemId: itemId, stid: stid, more_info: "more_info" },
            dataType: "html",
            success: function (response) {
                $("#moreinfo").html(response);
            } // /success function
        }); // /ajax to fetch Project Main Menu  image
    } else {
        alert("error please refresh the page");
    }
}

//function to get the details of a particular project
function projectInfo(id) {
    $.ajax({
        type: "POST",
        url: "process",
        data: "data=" + id,
        dataType: "html",
        success: function (response) {
            $('.info').html(response);
        }
    });
}


// function to initialize the map
function static_map(projid, stid) {
    google.maps.event.trigger(map, "resize");
    var lat = parseFloat($("#lat").val());
    var long = parseFloat($("#long").val());

    var center = new google.maps.LatLng(lat, long);
    var map = new google.maps.Map(document.getElementById('map'), {
        center: center,
        zoom: 12
    });

    // Change this depending on the name of your PHP or XML file
    downloadUrl('markers/stationary-markers?projid=' + projid + "&state=" + stid, null, function (data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName('marker');

        Array.prototype.forEach.call(markers, function (markerElem) {
            var id = markerElem.getAttribute('projid');
            var point = new google.maps.LatLng(
                parseFloat(markerElem.getAttribute('lat')),
                parseFloat(markerElem.getAttribute('lng')));
            var marker = new google.maps.Marker({
                position: point,
                map: map,
                title: 'Hello World!'
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
    var map = new google.maps.Map(document.getElementById('map'), {
        center: center,
        zoom: 12
    });


    (directionsService = new google.maps.DirectionsService()),
        (directionsDisplay = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: true
        }));

    infoWindow = new google.maps.InfoWindow();

    // Change this depending on the name of your PHP or XML file
    downloadUrl('markers/stationary-markers?projid=' + projid + "&state=" + stid, null, function (data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName('marker');
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
            fillOpacity: 0.35
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
    var map = new google.maps.Map(document.getElementById('map'), {
        center: center,
        zoom: 12
    });

    (directionsService = new google.maps.DirectionsService()),
        (directionsDisplay = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: true
        }));
    infoWindow = new google.maps.InfoWindow();
    // Change this depending on the name of your PHP or XML file
    downloadUrl('markers/stationary-markers?projid=' + projid + "&state=" + stid, null, function (data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName('marker');
        var count = 0;

        Array.prototype.forEach.call(markers, function (markerElem) {
            count++;
            var id = markerElem.getAttribute('projid');
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
            } else if (count = markers.length) {
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
    var waypts = [{
        location: point,
        stopover: false
    }];

    directionsService.route({
        origin: start,
        destination: dest,
        waypoints: waypts,
        optimizeWaypoints: true,
        travelMode: google.maps.TravelMode.DRIVING
    },
        function (response, status) {

            if (status === google.maps.DirectionsStatus.OK) {
                var directionsDisplay = new google.maps.DirectionsRenderer({
                    map: map,
                    suppressMarkers: true
                });
                directionsDisplay.setOptions({
                    directions: response
                });

                var route = response.routes[0];
                renderDirectionsPolylines(response, map);
            } else {
                window.alert('Directions request failed due to ' + status);
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
                strokeColor: colors[i]
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
    var request = window.ActiveXObject ?
        new ActiveXObject('Microsoft.XMLHTTP') :
        new XMLHttpRequest;
    request.onreadystatechange = function () {
        if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
        }
    };

    request.open('POST', url, true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(body);
}

function doNothing() { }


