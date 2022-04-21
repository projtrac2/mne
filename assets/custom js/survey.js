$(document).ready(function () {
  getcurrent_location_static();
});

// function to get the current position of the project
function getcurrent_location_static() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function (position) {
        lat = position.coords.latitude;
        lng = position.coords.longitude;
        $("#lat").val(lat);
        $("#lng").val(lng); 
      },
      function () {
        handleLocationError(true);
      }
    );
  } else {
    console.log("Location not found ");
    handleLocationError(false);
  }
}

// function to handle errors
function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  console.log("errors found");
}
