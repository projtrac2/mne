var coords = [];
var finalCoordinates = [];
var watchID = null;
var geoLoc;
var dis = 10;
var map, infoWindow;
var start;
var point;
var dest;

const polylineOptions = {
    strokeColor: "#C83939",
    strokeOpacity: 1,
    strokeWeight: 4,
};

const colors = ["#00FF00", "#4682B4", "#FFFF00", "#FF00FF", "#00FFFF"];
var polylines = [];


function finyearfrom() {
    var fyfrom = $("#fyfrom").val();
    if (fyfrom != "") {
        $.ajax({
            type: "post",
            url: "assets/processor/dashboard-processor",
            data: {
                get_fyto: fyfrom
            },
            dataType: "html",
            success: function(response) {
                $("#fyto").html(response);
            },
        });
    }
}

function get_projects() {
    var deptid = $("#department").val();
    if (deptid != "") {
        $.ajax({
            type: "post",
            url: "assets/processor/dashboard-processor",
            data: {
                get_dept_projects: deptid
            },
            dataType: "html",
            success: function(response) {
                $("#projid").html(response);
            },
        });
    }
}

// get outputs for a particular project 
function get_outputs(){
    var projid = $("#projid").val(); 
    // get_coordinates(projid);
    if(projid){
        $.ajax({
            type: "post",
            url: "assets/processor/dashboard-processor",
            data: {
                get_outputs:"get_outputs", 
                projid:projid, 
            },
            dataType: "json",
            success: function (response) {
                $("#fyto").html(response);
            }
        });
    }
}

// function to initialize map 
function initMap(){
    var center = new google.maps.LatLng(-1.292066,36.821946); 
    google.maps.event.trigger(map, "resize");
    map = new google.maps.Map(document.getElementById("map"), {
        center: center, 
        zoom: 13,
        mapTypeId: 'satellite'
    });
}
function get_output_coordinates(){
    let outputid = $("#outputs").val();
    let projid = $("#projid").val();

    if(outputid ){ 
        get_coordinates(projid, outputid); 
    }
}

// function to get map coordinates
function get_coordinates(projid=null, outputid=null){ 
    console.log(outputid); 
    if(projid){
        $.ajax({
            type: "get",
            url: "markers/add-map-data-manual",
            data: {
                get_markers:"get_markers", 
                projid:projid, 
                outputid:outputid,  
            },
            dataType: "json",
            success: function (response) {
                initMap();
                for(let i=0; i< response.length; i++){
                    let results = response[i];   
                    if(results.msg){ 
                        let markers = results.markers;
                        let  output_details = results.output_details; 
                        let mapping_type = output_details.mapping_type; 
                        if(mapping_type == 1){
                            static_coordinates(markers);
                        }else if(mapping_type === 2){
                            waypoint_coordinates(markers);
                        }else if(mapping_type === 3){
                            area_coordinates(markers);
                        } else{
                            console.log("Mapping type not defined"); 
                        }
                    }
                }
            }
        }); 
    }  
}

function static_coordinates(markers=[]){  
    Array.prototype.forEach.call(markers, function (markerElem) {
        let lat = markerElem[0]; 
        let lng = markerElem[1];           
        var point = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
        var marker = new google.maps.Marker({
            position: point,
            map: map,
            title: "Hello World!",
        });
    }); 
}

function area_coordinates(markers = [] ){ 
    (directionsService = new google.maps.DirectionsService()),
    (directionsDisplay = new google.maps.DirectionsRenderer({
        map: map,
        suppressMarkers: true,
    }));

    infoWindow = new google.maps.InfoWindow(); 
    if (window.poly) window.poly.setMap(null);
    var pp = [], 
    bounds = new google.maps.LatLngBounds();

    Array.prototype.forEach.call(markers, function (markerElem) {
        let lat = parseFloat(markerElem[0]);
        let lng = parseFloat(markerElem[1]);  
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
 
//
function waypoint_coordinates(markers=[]) {     
    (directionsService = new google.maps.DirectionsService()),
    (directionsDisplay = new google.maps.DirectionsRenderer({
        map: map,
        suppressMarkers: true,
    }));
    let count= 0; 
    infoWindow = new google.maps.InfoWindow();
    Array.prototype.forEach.call(markers, function (markerElem) {
        count++;
        let lat = markerElem[0]; 
        let lng = markerElem[1];
         
        if (count == 1) {
            start = new google.maps.LatLng(
            parseFloat(lat),
            parseFloat(lng)
            );
            dest = point = start;

        } else if (count != markers.length && count != 1) {
            point = new google.maps.LatLng(
                parseFloat(lat),
                parseFloat(lng)
            ); 
        } else if ((count = markers.length)) {
            dest = new google.maps.LatLng(
            parseFloat(lat),
            parseFloat(lng)
            );
        } 
        calculateAndDisplayRoute(directionsService, start, point, dest);
    }); 
}

// function to calculate the route between waypoints and the finish lane
function calculateAndDisplayRoute(directionsService, start, point, dest) {
    var waypts = [{
        location: point,
        stopover: false,
    },];

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
function renderDirectionsPolylines( ) {
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
 