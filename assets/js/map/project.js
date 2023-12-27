const url1 = "ajax/maps/project";

let map;
let directionsService;
var polylineOptions = {
	strokeColor: '#C83939',
	strokeOpacity: 1,
	strokeWeight: 4
};
var colors = ["#FF0000", "#00FF00", "#0000FF", "#FFFF00", "#FF00FF", "#00FFFF"];
var polylines = [];
let infoWindow;

const latitude = $("#company_latitude").val();
const longitude = $("#company_longitude").val();


function initMap() {
	var center = new google.maps.LatLng(parseFloat(latitude), parseFloat(longitude));
	map = new google.maps.Map(document.getElementById("map"), {
		center: center,
		zoom: 12,
		mapTypeControlOptions: {
			mapTypeIds: ["styled_one_point_map"],
		},
	});
	get_coordinates();
}

const get_coordinates = () => {
	var projid = $("#projid").val();
	if (projid != '') {
		$.ajax({
			type: "get",
			url: url1,
			data: {
				get_project_markers: 'get_project_markers',
				projid: projid,
			},
			dataType: "json",
			success: function (response) {
				if (response.success) {
					var data = response.markers;
					var data_length = data.length;
					for (var i = 0; i < data_length; i++) {
						var output_details = data[i];
						var indicator = output_details.indicator_details;
						var markers = output_details.markers;
						var mapping_type = indicator.indicator_mapping_type;
						if (mapping_type == '1') {
							static_markers(markers, indicator);
						} else if (mapping_type == '2') {
							waypoint_markers(markers);
						} else if (mapping_type == '3') {
							area_markers(markers);
						}
					}
				}
			}
		});
	}
}

// function to handle errors
const handleLocationError = (browserHasGeolocation, pos) => {
	console.log("errors found");
}

const static_markers = (markers, indicator) => {
	infowindow = new google.maps.InfoWindow();
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
			title: indicator.indicator_name,
		});

		mark.setMap(map);

		let contentString =
			`<div id="content">
				<div id="siteNotice"></div>
				<h1 id="firstHeading" class="firstHeading">Details</h1>
				<div id="bodyContent">
					<p>Indicator: <b>${indicator.indicator_name}</b>
					<p>Site: <b>${marker.site}</b>
				</div>
			</div>`;


		// Add a click listener for each marker, and set up the info window.
		mark.addListener("click", () => {
			infowindow.setContent(contentString);
			infowindow.setOptions({ maxWidth: 400 });
			infowindow.open(map, mark);
		});

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