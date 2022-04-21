var coords = [];
var finalCoordinates = [];
var watchID = null;
var geoLoc;
var dis = 10;
var map, infoWindow;
var start;
var point;
var dest;

// var dis = $('#distance').val();
var polylineOptions = {
    strokeColor: "#C83939",
    strokeOpacity: 1,
    strokeWeight: 4
};
var colors = ["#00FF00", "#4682B4", "#FFFF00", "#FF00FF", "#00FFFF"];
var polylines = [];

$(document).ready(function () {
    getcurrent_location_static();
    $("#submit").hide();
});


function get_map_coordinates() {
    $.ajax({
        type: "POST",
        url: "assets/processor/add-project-map-assign-process.php",
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



// ////
// static location mapping functions 
//////
// function to get the current position of the project 
function getcurrent_location_static() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            lat = position.coords.latitude;
            lng = position.coords.longitude;
            //get the latitude ad longitude 
            $("#lat").val(lat);
            $('#lng').val(lng);
        }, function () {
            handleLocationError(true);
            console.log("Browser browser supports geolocation but cannot get position")
        });
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false);
        console.log("Browser doesn't support Geolocation")
    }
}

// function to handle errors 
function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    console.log("errors found");
}


////////
// Area Mapping 
////////
// instantiate area map 
function checkposition() {
    $("#start").hide();
    var optn = {
        enableHighAccuracy: true,
        timeout: Infinity,
        maximumAge: 0
    };
    if (navigator.geolocation) {
        watchID = navigator.geolocation.watchPosition(success_area, fail, optn);
    } else {
        $("#msg").html("Not supported");
    }
}

//functio to handle all the errors 
function fail(error) {
    var errorType = {
        0: "Unknown Error",
        1: "Permission denied by the user",
        2: "Position of the user not available",
        3: "Request timed out"
    };
    var errMsg = errorType[error.code];
    if (error.code == 0 || error.code == 2) {
        errMsg = errMsg + " - " + error.message;
    }
    alert(errMsg);
}

// getcoordinates for area map
function success_area(position) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;
    var pos = {
        lat,
        lng
    }

    finalCoordinates.push(pos);
    if (finalCoordinates.length < 2) {
        get_area_coords(finalCoordinates);
    } else {
        // check if the last coordinates and the first coordinates match 
        target = finalCoordinates[0];
        crd = finalCoordinates[finalCoordinates.length - 1];

        if (target.lat === crd.lat && target.lng === crd.lng) {
            $("#msg").html('Congratulation, you have completed the task ');
            navigator.geolocation.clearWatch(watchID);
            get_area_coords(finalCoordinates);
        }
    }
}

//function to create the form to be submitted 
function get_area_coords(coords) {
    for (var i = 0; i < coords.length; i++) {
        var lat = coords[i].lat;
        var lng = coords[i].lng;
        add_row_table("area", lat, lng);
    }
}
// ///////////
// waypoints 
/////////////
function check_position() {
    var optn = {
        enableHighAccuracy: true,
        timeout: Infinity,
        maximumAge: 0
    };
    if (navigator.geolocation) {
        watchID = navigator.geolocation.watchPosition(success_way, fail, optn);
    } else {
        $("#msg").html("Not supported");
    }
}


function success_way(position) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;
    var pos = {
        lat,
        lng
    }
    finalCoordinates.push(pos);
    target = finalCoordinates[0];
    crd = finalCoordinates[finalCoordinates.length - 1];
    if (finalCoordinates.length < 2) {
        getcoords(finalCoordinates);
    } else {
        var service = new google.maps.DistanceMatrixService();
        service.getDistanceMatrix({
            origins: [target],
            destinations: [crd],
            travelMode: 'DRIVING',
        }, function (response, status) {
            if (status == 'OK') {
                var origins = response.originAddresses;
                var destinations = response.destinationAddresses;
                for (var i = 0; i < origins.length; i++) {
                    var results = response.rows[i].elements;
                    for (var j = 0; j < results.length; j++) {
                        var element = results[j];
                        var distance = element.distance.value;
                        var remaining = (dis - distance) / 1000;
                        if (distance == dis) {
                            $("#msg").html('Congratulation, you reach the target');
                            navigator.geolocation.clearWatch(watchID);
                            getcoords(finalCoordinates);
                        } else {
                            $("#msg").html("You are remaining with" + remaining + "Km");
                        }
                    }
                }
            }
        });

    }
}

//function to create the form to be submitted 
function getcoords(coords) {
    var container = $('<div />');
    for (var i = 0; i < coords.length; i++) {
        var lat = coords[i].lat;
        var lat = coords[i].lng;
        console.log(lat);
        add_row_table("waypoint", lat, lng);
    }
    $('#submit').show();
}


///////
// adding rows from data 
//////

// function to add new rowfor financiers
function add_row_table(mtype, lat, lng) {
    $("#removeTr").remove(); //new change
    var table_id = "#" + mtype + "_table_body tr";
    $rowno = $(table_id).length;
    $rowno = $rowno + 1;
    $(table_id).after(
        '<tr id="row' +
        $rowno +
        '">' +
        "<td></td>" +
        "<td>" +
        '<input type="text" name="lat[]" id="hidrow' + $rowno + '"  class="form-control" readonly value="' + lat + '" style="width:85%; float:right" required />' +
        "</td>" +
        "<td>" +
        '<input type="text" name="lng[]" id="hidrow' + $rowno + '"  class="form-control" readonly value="' + lng + '" style="width:85%; float:right" required />' +
        "</td>" +
        "</tr>"
    );
    numbering_table();
}

// auto numbering table rows on delete and add new for financier table
function numbering_table() {
    $("#assign_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx);
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
            url: "general-settings/selected-items/fetch-team-item.php",
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
        url: "process.php",
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
    downloadUrl('markers/stationary-markers.php?projid=' + projid + "&state=" + stid, null, function (data) {
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
    downloadUrl('markers/stationary-markers.php?projid=' + projid + "&state=" + stid, null, function (data) {
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
    downloadUrl('markers/stationary-markers.php?projid=' + projid + "&state=" + stid, null, function (data) {
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


