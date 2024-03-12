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
let infoWindow, infowindow, styledMapType;

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


	styledMapType = new google.maps.StyledMapType(
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
							waypoint_markers(markers, indicator);
						} else if (mapping_type == '3') {
							area_markers(markers, indicator);
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
	var markers_length = markers.length;
	for (var i = 0; i < markers_length; i++) {
		var marker_array = markers[i];
		var marker = marker_array.markers;
		var site = marker_array.site;


		marker.map(marks => {
			var lng = marks.lng;
			var lat = marks.lat;
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
					<p>Site: <b>${site}</b>
				</div>
			</div>`;

			// Add a click listener for each marker, and set up the info window.
			mark.addListener("click", () => {
				infowindow.setContent(contentString);
				infowindow.setOptions({ maxWidth: 400 });
				infowindow.open(map, mark);
			});
		});
	}

	//Associate the styled map with the MapTypeId and set it to display.
	map.mapTypes.set("styled_one_point_map", styledMapType);
	map.setMapTypeId("styled_one_point_map");

	return;
}


const randomRgbColor = () => {
	let r = Math.floor(Math.random() * 256); // Random between 0-255
	let g = Math.floor(Math.random() * 256); // Random between 0-255
	let b = Math.floor(Math.random() * 256); // Random between 0-255
	return 'rgb(' + r + ',' + g + ',' + b + ')';
};


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
		var project_details = point.project_details;
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

