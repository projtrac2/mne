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
        add_row_table(lat, lng);
    }
}


// ///////////
// waypoints 
/////////////
function check_position() {
    $("#start").hide();
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
        add_row_table(lat, lng);
    }
    $('#submit').show();
}



///////
// adding rows from data 
//////

// function to add new rowfor financiers
function add_row_table(lat, lng) {
    $("#removeTr").remove(); //new change
    var table_id = "#mapping_table_body tr";
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
    $("#mapping_table_body tr").each(function (idx) {
        $(this)
            .children()
            .first()
            .html(idx);
    });
}