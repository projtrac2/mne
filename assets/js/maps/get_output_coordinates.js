const url1 = "assets/processor/dashboard-processor";
let map, infoWindow;
var coords = [];
var finalCoordinates = [];
var watchID = null;
var geoLoc;
var dis = 10;
var start;
var point;
var dest;
var lat = parseFloat($("#lat").val());
var long = parseFloat($("#long").val());


$(document).ready(function () {
	google.maps.event.trigger(map, "resize");
	var lats = parseFloat($("#lat").val());
	var longs = parseFloat($("#long").val());

	var center = new google.maps.LatLng(lats, longs);


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

	map = new google.maps.Map(document.getElementById("map"), {
		center: center,
		zoom: 12,
		mapTypeControlOptions: {
			mapTypeIds: ["styled_one_point_map"],
		},
	});

	//Associate the styled map with the MapTypeId and set it to display.
	map.mapTypes.set("styled_one_point_map", styledMapType);
	map.setMapTypeId("styled_one_point_map");

});

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
}

// get level 3
function ecosystem() {
	var level2 = $("#projlga").val();
	if (level2 != "") {
		$.ajax({
			type: "post",
			url: url1,
			data: { get_level3: "get_level3", level2: level2 },
			dataType: "html",
			success: function (response) {
				$("#projloc").html(response);
			},
		});
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
	}
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

const get_mapping_coordinates = function (indid, mapping_type) {
	//var mapping_type = 3;
	if (mapping_type == 1) {
		static_markers();
	} else if (mapping_type == 2) {
		waypoint_markers();
	} else if (mapping_type == 3) {
		area_markers();
	} else {
		console.log("No other mapping type shall come with this ");
	}
}

const get_coordinates = function () {
	let indid = $("#indicator").val();
	if (indid) {
		$.ajax({
			type: "post",
			url: url1,
			data: { get_sector_mapping_type: indid },
			dataType: "json",
			success: function (response) {
				if (response != "") {
					get_mapping_coordinates(indid, response);
				} else {
					console.log("The indicator does not require mapping");
				}
			}
		});
	} else {
		console.log("please select indicator type")
	}
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

function doNothing() { }

var static_markers = function () {
	google.maps.event.trigger(map, "resize");
	var latit = parseFloat($("#lat").val());
	var longit = parseFloat($("#long").val());
	var center = new google.maps.LatLng(latit, longit);

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

			{
				elementType: "geometry",
				stylers: [{ color: "#f5f5f5" }],
			},
			{
				elementType: "labels.icon",
				stylers: [{ visibility: "off" }],
			},
			{
				elementType: "labels.text.fill",
				stylers: [{ color: "#616161" }],
			},
			{
				elementType: "labels.text.stroke",
				stylers: [{ color: "#f5f5f5" }],
			},
			{
				featureType: "administrative.land_parcel",
				elementType: "labels.text.fill",
				stylers: [{ color: "#bdbdbd" }],
			},
			{
				featureType: "poi",
				elementType: "geometry",
				stylers: [{ color: "#eeeeee" }],
			},
			{
				featureType: "poi",
				elementType: "labels.text.fill",
				stylers: [{ color: "#757575" }],
			},
			{
				featureType: "poi.park",
				elementType: "geometry",
				stylers: [{ color: "#e5e5e5" }],
			},
			{
				featureType: "poi.park",
				elementType: "labels.text.fill",
				stylers: [{ color: "#9e9e9e" }],
			},
			{
				featureType: "road",
				elementType: "geometry",
				stylers: [{ color: "#ffffff" }],
			},
			{
				featureType: "road.arterial",
				elementType: "labels.text.fill",
				stylers: [{ color: "#757575" }],
			},
			{
				featureType: "road.highway",
				elementType: "geometry",
				stylers: [{ color: "#dadada" }],
			},
			{
				featureType: "road.highway",
				elementType: "labels.text.fill",
				stylers: [{ color: "#616161" }],
			},
			{
				featureType: "road.local",
				elementType: "labels.text.fill",
				stylers: [{ color: "#9e9e9e" }],
			},
			{
				featureType: "transit.line",
				elementType: "geometry",
				stylers: [{ color: "#e5e5e5" }],
			},
			{
				featureType: "transit.station",
				elementType: "geometry",
				stylers: [{ color: "#eeeeee" }],
			},
			{
				featureType: "water",
				elementType: "geometry",
				stylers: [{ color: "#c9c9c9" }],
			},
			{
				featureType: "water",
				elementType: "labels.text.fill",
				stylers: [{ color: "#9e9e9e" }],
			},
		],
		{ name: "Projtrac M&E One Point Map" }
	);

	map = new google.maps.Map(document.getElementById("map"), {
		center: center,
		zoom: 12,
		mapTypeControlOptions: {
			mapTypeIds: ["styled_one_point_map"],
		},
	});

	//Associate the styled map with the MapTypeId and set it to display.
	map.mapTypes.set("styled_one_point_map", styledMapType);
	map.setMapTypeId("styled_one_point_map");

	var form_data = $(`#searchform`).serialize();
	var map_markers_url = `ajax/maps/markers?get_indicator_markers&${form_data}`;

	// Change this depending on the name of your PHP or XML file
	downloadUrl(map_markers_url, null, function (data) {
		var xml = data.responseXML;
		var markers = xml.documentElement.getElementsByTagName("marker");

		Array.prototype.forEach.call(markers, function (markerElem) {
			var id = markerElem.getAttribute("projid");
			var projname = markerElem.getAttribute("projname");
			var projstartdate = markerElem.getAttribute("projstartdate");
			var projenddate = markerElem.getAttribute("projenddate");
			var projdesc = markerElem.getAttribute("projdesc");
			if (projdesc == '') {
				projdesc = 'Testing now!';
			}
			var output = markerElem.getAttribute("projoutput");
			var point = new google.maps.LatLng(
				parseFloat(markerElem.getAttribute("lat")),
				parseFloat(markerElem.getAttribute("lng"))
			);

			var contentString =
				'<div id="content">' +
				'<div id="siteNotice">' +
				"</div>" +
				'<h3 id="firstHeading" class="firstHeading">Project Name: ' + projname +
				'</h3>' +
				'<h4 id="firstHeading" class="firstHeading">Output Name: ' + output +
				'</h4>' +
				'<div id="bodyContent">' +
				"<p><b>Project Description:</b> " + projdesc +
				"</p><p><b>Project Start Date:</b> " + projstartdate +
				"</p><p><b>Project End Date:</b> " + projenddate +
				"</p>" +
				"</div>" +
				"</div>";

			var marker = new google.maps.Marker({
				position: point,
				map: map,
				title: projname,
				icon: 'assets/js/maps/project-management.png',
			});

			var infowindow = new google.maps.InfoWindow({
				content: contentString,
				ariaLabel: "Uluru",
			});

			marker.addListener("click", () => {
				infowindow.open({
					anchor: marker,
					map,
				});
			});
		});
	});
}

const waypoint_markers = function () {
	google.maps.event.trigger(map, "resize");
	var latit = parseFloat($("#lat").val());
	var longit = parseFloat($("#long").val());
	var center = new google.maps.LatLng(latit, longit);

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
		{ name: "Projtrac M&E WayPoints Map" }
	);

	map = new google.maps.Map(document.getElementById("map"), {
		center: center,
		zoom: 12,
		mapTypeControlOptions: {
			mapTypeIds: ["styled_waypoint_map"],
		},
	});

	//Associate the styled map with the MapTypeId and set it to display.
	map.mapTypes.set("styled_waypoint_map", styledMapType);
	map.setMapTypeId("styled_waypoint_map");

	(directionsService = new google.maps.DirectionsService()),
		(directionsDisplay = new google.maps.DirectionsRenderer({
			map: map,
			suppressMarkers: true,
		}));

	infoWindow = new google.maps.InfoWindow();


	var form_data = $(`#searchform`).serialize();
	var map_markers_url = `ajax/maps/markers?get_indicator_markers&${form_data}`;

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

const area_markers = function () {
	google.maps.event.trigger(map, "resize");
	var latit = parseFloat($("#lat").val());
	var longit = parseFloat($("#long").val());
	var center = new google.maps.LatLng(latit, longit);

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
		{ name: "Projtrac M&E  Area Points Map" }
	);
	map = new google.maps.Map(document.getElementById("map"), {
		center: center,
		zoom: 12,
		mapTypeControlOptions: {
			mapTypeIds: ["styled_area_map"],
		},
	});

	//Associate the styled map with the MapTypeId and set it to display.
	map.mapTypes.set("styled_area_map", styledMapType);
	map.setMapTypeId("styled_area_map");

	(directionsService = new google.maps.DirectionsService()),
		(directionsDisplay = new google.maps.DirectionsRenderer({
			map: map,
			suppressMarkers: true,
		}));

	infoWindow = new google.maps.InfoWindow();


	var form_data = $(`#searchform`).serialize();
	var map_markers_url = `ajax/maps/markers?get_indicator_markers&${form_data}`;

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