const url1 = "ajax/maps/output";



let map;
let directionsService;
var polylineOptions = {
	strokeColor: '#C83939',
	strokeOpacity: 1,
	strokeWeight: 4
};

var colors = ["#FF0000", "#00FF00", "#0000FF", "#FFFF00", "#FF00FF", "#00FFFF"];
var polylines = [];
let infoWindow, infowindow, styledMapType;
const latitude = $("#company_latitude").val();
const longitude = $("#company_longitude").val();

function initMap(lat, lng) {
	var center = new google.maps.LatLng(parseFloat(latitude), parseFloat(longitude));
	styledMapType = styledMapType = new google.maps.StyledMapType(
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
		], { name: "Projtrac M&E Map" });

	return new google.maps.Map(document.getElementById("map"), {
		center: center,
		zoom: 12,
		mapTypeControlOptions: {
			mapTypeIds: ["styled_one_point_map"],
		},
	});
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
	}
	get_coordinates();
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
	}
	get_coordinates();
}

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

const get_coordinates = () => {
	var indicator_id = $("#indicator").val();
	var fyfrom = $("#fyfrom").val();
	var fyto = $("#fyto").val();
	var projcommunity = $("#projcommunity").val();
	var projlga = $("#projlga").val();
	map = null;
	map = initMap();

	if (indicator_id != '') {
		$.ajax({
			type: "get",
			url: url1,
			data: {
				get_indicator_markers: 'get_indicator_markers',
				indicator_id: indicator_id,
				fyfrom: fyfrom,
				fyto: fyto,
				projcommunity: projcommunity,
				projlga: projlga,
			},
			dataType: "json",
			success: function (response) {
				if (response.success) {
					var indicator = response.indicator;
					var mapping_type = indicator.indicator_mapping_type;
					var markers_array = response.markers;
					if (mapping_type == '1') {
						static_markers(markers_array, indicator);
					} else if (mapping_type == '2') {
						waypoint_markers(markers_array, indicator);
					} else if (mapping_type == '3') {
						area_markers(markers_array, indicator);
					} else {
						error_alert("Sorry please try again");
					}
				}
			}
		});
	} else {
		error_alert("Please select indicator");
	}
}

// function to handle errors
const handleLocationError = (browserHasGeolocation, infoWindow, pos) => {
	error_alert("errors found");
}

const static_markers = (data, indicator) => {
	infowindow = new google.maps.InfoWindow();
	data_length = data.length;

	if (data_length > 0) {
		for (var i = 0; i < data_length; i++) {
			var marker_data = data[i];
			var site = marker_data.site;
			var project_name = marker_data.project_name;

			var markers = marker_data.markers;
			var project_details = marker_data.project_details;
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

				mark.setMap(map);

				// Add a click listener for each marker, and set up the info window.
				mark.addListener("click", () => {
					infowindow.setContent(
						`<div id="content">
						<div id="siteNotice"></div>
						<h1 id="firstHeading" class="firstHeading">Details</h1>
						<div id="bodyContent">
							<p>Project: <b>${project_name}</b>
							<p>Indicator: <b>${indicator.indicator_name}</b>
							<p>Site: <b>${site}</b>
						</div>
					</div>`);
					infowindow.setOptions({ maxWidth: 400 });
					infowindow.open(map, mark);
				});
			});
		}
	}
	//Associate the styled map with the MapTypeId and set it to display.
	map.mapTypes.set("styled_one_point_map", styledMapType);
	map.setMapTypeId("styled_one_point_map");
}

const area_markers = (markers, indicator) => {
	(directionsService = new google.maps.DirectionsService()),
		(directionsDisplay = new google.maps.DirectionsRenderer({
			map: map,
			suppressMarkers: true,
		}));
	var markers_length = markers.length;

	infowindow = new google.maps.InfoWindow();

	if (window.poly) window.poly.setMap(null);

	for (var i = 0; i < markers_length; i++) {
		var marker_array = markers[i];
		var marker = marker_array.markers;
		var site = marker_array.site;

		var pp = [];

		marker.map(mark => {
			var lng = mark.lng;
			var lat = mark.lat;
			pp.push(new google.maps.LatLng(lat, lng));
		});

		window.poly = new google.maps.Polygon({
			paths: pp,
			strokeColor: '#ff0000',
			strokeOpacity: 0.8,
			strokeWeight: 3,
			fillColor: '#ff0000',
			fillOpacity: 0.35,
		});

		window.poly.setMap(map);

		var content =
			`<div id="content">
			<div id="siteNotice"></div>
			<h1 id="firstHeading" class="firstHeading">Details</h1>
			<div id="bodyContent">
				<p>Indicator: <b>${indicator.indicator_name}</b>
				<p>Site: <b>${site}</b>
			</div>
		</div>`;
		createInfoWindow(window.poly, content);
	}

	//Associate the styled map with the MapTypeId and set it to display.
	map.mapTypes.set("styled_one_point_map", styledMapType);
	map.setMapTypeId("styled_one_point_map");
}

const waypoint_markers = (data, indicator) => {
	var data_length = data.length;
	infowindow = new google.maps.InfoWindow();

	for (var i = 0; i < data_length; i++) {
		var point = data[i];
		var markers = point.markers;
		var markers_length = markers.length;
		var project_name = point.project_name;

		var poly;
		var polyOptions = {
			strokeColor: '#ff0000',
			strokeOpacity: 1.0,
			strokeWeight: 5
		}

		poly = new google.maps.Polyline(polyOptions);
		poly.setMap(map);
		var path = poly.getPath();

		for (var j = 0; j < markers_length; j++) {
			var coordinates = markers[j];
			path.push(new google.maps.LatLng(parseFloat(coordinates.lat), parseFloat(coordinates.lng)));
		}

		var content =
			`<div id="content">
			<div id="siteNotice"></div>
			<h1 id="firstHeading" class="firstHeading">Details</h1>
			<div id="bodyContent">
				<p>Project: <b>${project_name}</b>
				<p>Indicator: <b>${indicator.indicator_name}</b>
			</div>
		</div>`;
		createInfoWindow(poly, content);
	}

	//Associate the styled map with the MapTypeId and set it to display.
	map.mapTypes.set("styled_one_point_map", styledMapType);
	map.setMapTypeId("styled_one_point_map");
}

function createInfoWindow(poly, content) {
	google.maps.event.addListener(poly, 'click', function (event) {
		infowindow.setContent(content);
		infowindow.setPosition(event.latLng);
		infowindow.open(map);
	});
}