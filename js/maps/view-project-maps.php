<?php
require('includes/head.php');
if ($permission) {
   try {
      if (isset($_GET['projid']) && !empty($_GET['projid'])) {
         $projid  = $_GET['projid'];
         $query_rsTPList = $db->prepare("SELECT projname, projcode, projmapping FROM tbl_projects WHERE projid=:projid");
         $query_rsTPList->execute(array(":projid" => $projid));
         $row_rsTPList = $query_rsTPList->fetch();
         $projname = $row_rsTPList['projname'];
         $projcode = $row_rsTPList['projcode'];
         $mapping = $row_rsTPList['projmapping'];

         $query_rsTP = $db->prepare("SELECT g.output, d.id, g.indicator FROM tbl_project_details d INNER JOIN tbl_progdetails g ON g.id = d.outputid WHERE projid=:projid");
         $query_rsTP->execute(array(":projid" => $projid));
         $count_rsTP = $query_rsTP->rowCount();
      } else {
         $url = "myprojects.php";
         $msg = 'Please select a project.';
         $results =
            "<script type=\"text/javascript\">
          swal({
            title: \"Error!\",
            text: \" $msg\",
            type: 'Error',
            timer: 5000,
            icon:'error',
            showConfirmButton: false
          });
            setTimeout(function(){
            window.location.href = '$url';
            }, 5000);
      </script>";
         echo $results;
         return;
      }
   } catch (PDOException $ex) {
      $result = flashMessage("An error occurred: " . $ex->getMessage());
   }
?>
   <!-- start body  -->
   <section class="content">
      <div class="container-fluid">
         <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
            <h4 class="contentheader">
               <?= $icon ?>
               <?= $pageTitle ?>
               <div class="btn-group" style="float:right">
                  <div class="btn-group" style="float:right">

                  </div>
               </div>
            </h4>
         </div>
         <div class="row clearfix">
            <div class="block-header">
               <?= $results; ?>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <div class="card">
                  <div class="body">
                     <?php
                     if ($mapping == 1) {
                     ?>
                        <div class="header">

                           <h4 class="card-title">Project Name: <?= $projname ?></h4> <br>
                           <h4 class="card-title">Project Code: <?= $projcode ?></h4>
                           <form action="" id="searchform" method="get">
                              <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                              <div class="form-group">
                                 <select name="output" id="indicator" class="form-control show-tick " onchange="get_coordinates()" data-live-search="true" style="border:#CCC thin solid; border-radius:5px;" data-live-search-style="startsWith">
                                    <option value="" selected="selected">Select Output</option>
                                    <?php
                                    while ($row_rsTP = $query_rsTP->fetch()) {
                                    ?>
                                       <option value="<?php echo $row_rsTP['indicator'] ?>"><?php echo ucfirst($row_rsTP['output']) ?></option>
                                    <?php
                                    }
                                    ?>
                                 </select>
                              </div>
                           </form>
                        </div>
                        <div class="body">
                           <style>
                              .mt-map-wrapper {
                                 width: 100%;
                                 padding-bottom: 41.6%;
                                 height: 0;
                                 overflow: hidden;
                                 position: relative;
                              }

                              .mt-map {
                                 width: 100%;
                                 height: 100%;
                                 left: 0;
                                 top: 0;
                                 position: absolute;
                              }
                           </style>

                           <div class="mt-map-wrapper">
                              <div class="mt-map propmap" id="map">
                                 <div style="height: 100%; width: 100%; position: relative; overflow: hidden; background-color: rgb(229, 227, 223);">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <input type="hidden" name="lat" id="lat" value=" -1.254337">
                        <input type="hidden" name="long" id="long" value=" 36.681660">
                        <!-- <script src="assets/js/maps/get_output_coordinates.js"></script> -->
                        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDiyrRpT1Rg7EUpZCUAKTtdw3jl70UzBAU"></script>
                     <?php
                     } else {
                     ?>
                        <div class="body">
                           <div class="header">
                              <h4 class="card-title">Project Name: <?= $projname ?></h4> <br>
                              <h4 class="card-title">Project Code: <?= $projcode ?></h4>
                           </div>
                           <div class="card-body" style="margin-top: 60px;">
                              <h1 class="text-warning text-center">Sorry this project does not require mapping</h1>
                           </div>
                        </div>
                     <?php
                     }
                     ?>
                  </div>
               </div>
            </div>
         </div>
   </section>
   <!-- end body  -->

<?php
} else {
   $results =  restriction();
   echo $results;
}

require('includes/footer.php');
?>

<script>
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


   $(document).ready(function() {
      google.maps.event.trigger(map, "resize");
      var lat = parseFloat($("#lat").val());
      var long = parseFloat($("#long").val());
      var center = new google.maps.LatLng(lat, long);
      map = new google.maps.Map(document.getElementById("map"), {
         center: center,
         zoom: 12,
      });
   });

   // get level 2
   function conservancy() {
      var level1 = $("#projcommunity").val();
      if (level1 != "") {
         $.ajax({
            type: "post",
            url: url1,
            data: {
               get_level2: "get_level2",
               level1: level1
            },
            dataType: "html",
            success: function(response) {
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
            data: {
               get_level3: "get_level3",
               level2: level2
            },
            dataType: "html",
            success: function(response) {
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

   const get_indicators = function() {
      let sector = $("#department").val();
      if (sector) {
         $.ajax({
            type: "post",
            url: url1,
            data: {
               get_sector: sector
            },
            dataType: "html",
            success: function(response) {
               $("#indicator").html(response);
            }
         });
      } else {
         $("#department").val(`<option value="" selected="selected">Select Department First</option>`);
      }
   }

   const get_mapping_coordinates = function(indid, mapping_type) {
      if (mapping_type == 1) {
         static_markers();
      } else if (mapping_type == "2") {
         waypoint_markers();
      } else if (mapping_type == "3") {
         area_markers();
      } else {
         console.log("No other mapping type shall come with this ");
      }
   }

   const get_coordinates = function() {
      let indid = $("#indicator").val();
      if (indid) {
         $.ajax({
            type: "post",
            url: url1,
            data: {
               get_sector_mapping_type: indid
            },
            dataType: "json",
            success: function(response) {
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
      var request = window.ActiveXObject ?
         new ActiveXObject("Microsoft.XMLHTTP") :
         new XMLHttpRequest();
      request.onreadystatechange = function() {
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

   const static_markers = function() {
      google.maps.event.trigger(map, "resize");
      var lat = parseFloat($("#lat").val());
      var long = parseFloat($("#long").val());
      var center = new google.maps.LatLng(lat, long);
      map = new google.maps.Map(document.getElementById("map"), {
         center: center,
         zoom: 12,
      });

      var form_data = $(`#searchform`).serialize();
      var map_markers_url = `ajax/maps/markers?get_project_indicator_markers&${form_data}`;


      // Change this depending on the name of your PHP or XML file
      downloadUrl(map_markers_url, null, function(data) {
         var xml = data.responseXML;
         var markers = xml.documentElement.getElementsByTagName("marker");

         Array.prototype.forEach.call(markers, function(markerElem) {
            var point = new google.maps.LatLng(
               parseFloat(markerElem.getAttribute("lat")),
               parseFloat(markerElem.getAttribute("lng"))
            );
            var marker = new google.maps.Marker({
               position: point,
               map: map,
            });
            console.log(marker);
         });
      });
   }

   const waypoint_markers = function() {
      console.log("Testing waypoints");

      google.maps.event.trigger(map, "resize");
      var lat = parseFloat($("#lat").val());
      var long = parseFloat($("#long").val());
      var center = new google.maps.LatLng(lat, long);
      map = new google.maps.Map(document.getElementById("map"), {
         center: center,
         zoom: 12,
      });

      var form_data = $(`#searchform`).serialize();
      var map_markers_url = `ajax/maps/markers?get_project_indicator_markers&${form_data}`;


      // Change this depending on the name of your PHP or XML file
      downloadUrl(map_markers_url, null, function(data) {
         var xml = data.responseXML;
         var markers = xml.documentElement.getElementsByTagName("marker");

         Array.prototype.forEach.call(markers, function(markerElem) {
            console.log(markerElem);
         });
      });
   }


   // function to initialize the map
   function waypoint_map() {
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
      downloadUrl(map_markers_url, null, function(data) {
         var xml = data.responseXML;
         var markers = xml.documentElement.getElementsByTagName("marker");
         var count = 0;

         Array.prototype.forEach.call(markers, function(markerElem) {
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
      var waypts = [{
         location: point,
         stopover: false,
      }, ];

      directionsService.route({
            origin: start,
            destination: dest,
            waypoints: waypts,
            optimizeWaypoints: true,
            travelMode: google.maps.TravelMode.DRIVING,
         },
         function(response, status) {
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
            google.maps.event.addListener(stepPolyline, "click", function(evt) {
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



   const area_markers = function() {
      google.maps.event.trigger(map, "resize");
      var lat = parseFloat($("#lat").val());
      var long = parseFloat($("#long").val());
      var center = new google.maps.LatLng(lat, long);
      map = new google.maps.Map(document.getElementById("map"), {
         center: center,
         zoom: 12,
      });

      (directionsService = new google.maps.DirectionsService()),
      (directionsDisplay = new google.maps.DirectionsRenderer({
         map: map,
         suppressMarkers: true,
      }));

      infoWindow = new google.maps.InfoWindow();

      var form_data = $(`#searchform`).serialize();
      var map_markers_url = `ajax/maps/markers?get_project_indicator_markers&${form_data}`;

      console.log(form_data);

      // Change this depending on the name of your PHP or XML file
      downloadUrl(map_markers_url, null, function(data) {
         var xml = data.responseXML;
         var markers = xml.documentElement.getElementsByTagName("marker");
         if (window.poly) window.poly.setMap(null);
         var pp = [],
            bounds = new google.maps.LatLngBounds();

         Array.prototype.forEach.call(markers, function(markerElem) {
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
</script>