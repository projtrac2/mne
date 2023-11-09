const url1 = "ajax/maps/output";
let map;
let directionsService;
$(document).ready(function () {
    // get_coordinates();
    const lats = $("#lat").val();
    const longs = $("#long").val();
    var center = new google.maps.LatLng(lats, longs);
    map = new google.maps.Map(document.getElementById("map"), {
        center: center,
        zoom: 12,
        mapTypeControlOptions: {
            mapTypeIds: ["styled_one_point_map"],
        },
    });
});

const get_indicators = function () {
    let sector = $("#department").val();
    if (sector) {
        $.ajax({
            type: "post",
            url: url1,
            data: { get_sector: sector },
            dataType: "html",
            success: function (response) {
                $("#indicator").html(response);
            }
        });
    } else {
        $("#department").val(`<option value="" selected="selected">Select Department First</option>`);
    }
}

// get financial year to
function finyearfrom() {
    var fyfrom = $("#fyfrom").val();
    if (fyfrom != "") {
        $.ajax({
            type: "post",
            url: url1,
            data: { get_fyto: fyfrom },
            dataType: "html",
            success: function (response) {
                $("#fyto").html(response);
            },
        });
        get_coordinates();
    }
}


// get level 2
function conservancy() {
    var level1 = $("#projcommunity").val();
    if (level1 != "") {
        $.ajax({
            type: "post",
            url: url1,
            data: { get_level2: "get_level2", level1: level1 },
            dataType: "html",
            success: function (response) {
                $("#projlga").html(response);
            },
        });
        get_coordinates();
    }
}

const get_coordinates = () => {
    var fyfrom = $("#fyfrom").val();
    var fyto = $("#fyto").val();
    var indicator_id = $("#indicator").val();
    var subcounty_id = $("#projcommunity").val();
    var ward_id = $("#projlga").val();
    if (indicator_id != '') {
        $.ajax({
            type: "get",
            url: url1,
            data: {
                get_indicator_markers: 'get_indicator_markers',
                indicator_id: indicator_id,
                fyto: fyto,
                fyfrom: fyfrom,
                subcounty_id:subcounty_id,
                ward_id:ward_id,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    var markers = response.markers;
                    var mapping_type = response.indicator.indicator_mapping_type;
                    if (mapping_type == '1') {
                        static_markers(markers);
                    } else if (mapping_type == '2') {
                        area_markers(arkers);
                    } else if (mapping_type == '3') {
                        waypoint_markers(markers);
                    }
                }
            }
        });
    }else{
        error_alert("Please select an indicator ")
    }
}

const static_markers = (markers) => {
    markers.map(marker => {
        var lng = marker.lng;
        var lat = marker.lat;
        google.maps.event.trigger(map, "resize");
        var point = new google.maps.LatLng(
            parseFloat(lat),
            parseFloat(lng)
        );

        var mark = new google.maps.Marker({
            position: point,
            map: map,
            title: "projname",
            // icon: 'assets/js/maps/project-management.png',
        });

        console.log(mark);

        var styledMapType = new google.maps.StyledMapType(
            [
                {
                    "featureType": "administrative.province",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#452003"
                        },
                        {
                            "saturation": 5
                        },
                        {
                            "visibility": "on"
                        },
                        {
                            "weight": 5
                        }
                    ]
                },
                {
                    "featureType": "administrative.province",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#452003"
                        },
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    featureType: "poi.business",
                    stylers: [{ visibility: "off" }],
                },
                {
                    featureType: "transit",
                    elementType: "labels.icon",
                    stylers: [{ visibility: "off" }],
                },
            ],
            { name: "Projtrac M&E Map" }
        );

        //Associate the styled map with the MapTypeId and set it to display.
        map.mapTypes.set("styled_one_point_map", styledMapType);
        map.setMapTypeId("styled_one_point_map");
    });
}

const area_markers = (markers) => {
    google.maps.event.trigger(map, "resize");

    (directionsService = new google.maps.DirectionsService()),
        (directionsDisplay = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: true,
        }));

    infoWindow = new google.maps.InfoWindow();
    if (window.poly) window.poly.setMap(null);
    var pp = [], bounds = new google.maps.LatLngBounds();
    markers.map(marker => {
        var lng = marker.lng;
        var lat = marker.lat;
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
}


const waypoint_markers = (markers) => {
    (directionsService = new google.maps.DirectionsService()),
        (directionsDisplay = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: true,
        }));
    infoWindow = new google.maps.InfoWindow();
    var count = 0;

    markers.map(marker => {
        count++;
        var lng = marker.lng;
        var lat = marker.lat;
        if (count == 1) {
            start = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
            dest = point = start;
        } else if (count != markers.length && count != 1) {
            if (lng == "" || lat == "") {
                point = dest = start;
            } else {
                point = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
            }
        } else if ((count = markers.length)) {
            dest = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
        }
        calculateAndDisplayRoute(directionsService, start, point, dest);
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