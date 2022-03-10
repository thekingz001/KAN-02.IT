<?php 
include('condb.php'); 
$sql = "
  select
   * 
  from 
    customer 
  WHERE 
    AmphurCode
  in 
    ('70190',
    '71000') 
  and 
    ShopTypeCode 
  in 
    ('FS','FS01','FS02','MOC','MON','MR','MR01','MR02','MR03','NEWC1','NEWC3','PP03','PP07','PP20') 
  and 
    SalesNo = 'NPT05';
";

  $images = mysqli_query($con, $sql);
  echo "<script>
  var arr = [];
  </script>";
  
  foreach ($images as $test) {
    // echo json_encode($test) ;
    // echo "<script type='text/javascript'>alert('".$test."');</script>";
    // echo "<script>console.log(" . json_encode($test) . ");</script>";
    echo "<script>
      arr.push(" . json_encode($test) . ");
    </script>";
  }

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Directions Service</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
  </head>
  <body>
    <div id="floating-panel">
      <b>Start: </b>
      <select id="start">
        <option value="chicago, il">Chicago</option>
        <option value="st louis, mo">St Louis</option>
        <option value="joplin, mo">Joplin, MO</option>
        <option value="oklahoma city, ok">Oklahoma City</option>
        <option value="amarillo, tx">Amarillo</option>
        <option value="gallup, nm">Gallup, NM</option>
        <option value="flagstaff, az">Flagstaff, AZ</option>
        <option value="winona, az">Winona</option>
        <option value="kingman, az">Kingman</option>
        <option value="barstow, ca">Barstow</option>
        <option value="san bernardino, ca">San Bernardino</option>
        <option value="los angeles, ca">Los Angeles</option>
        <option value="กรุงเทพ">test start</option>
      </select>
      <b>End: </b>
      <select id="end">
        <option value="chicago, il">Chicago</option>
        <option value="st louis, mo">St Louis</option>
        <option value="joplin, mo">Joplin, MO</option>
        <option value="oklahoma city, ok">Oklahoma City</option>
        <option value="amarillo, tx">Amarillo</option>
        <option value="gallup, nm">Gallup, NM</option>
        <option value="flagstaff, az">Flagstaff, AZ</option>
        <option value="winona, az">Winona</option>
        <option value="kingman, az">Kingman</option>
        <option value="barstow, ca">Barstow</option>
        <option value="san bernardino, ca">San Bernardino</option>
        <option value="los angeles, ca">Los Angeles</option>
        <option value="ubonratchatani, ubon">test end</option>
      </select>
    </div>
      <div id="map" style="width: 1000px; height: 1000px;"></div>
  </body>
</html>


<script>
function initMap() {
  const directionsService = new google.maps.DirectionsService();
  const directionsRenderer = new google.maps.DirectionsRenderer();
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 7,
    center: { lat: 41.85, lng: -87.65 },
  });

  directionsRenderer.setMap(map);

  const onChangeHandler = function () {
    calculateAndDisplayRoute(directionsService, directionsRenderer);
  };

  document.getElementById("start").addEventListener("change", onChangeHandler);
  document.getElementById("end").addEventListener("change", onChangeHandler);
}

function calculateAndDisplayRoute(directionsService, directionsRenderer) {
  directionsService
    .route({
      origin: {
        query: document.getElementById("start").value,
      },
      destination: {
        query: document.getElementById("end").value,
      },
      travelMode: google.maps.TravelMode.DRIVING,
    })
    .then((response) => {
      // console.log(JSON.stringify(response.routes));
      const route = response.routes[0];
      for (let i = 0; i < route.legs.length; i++) {
        // console.log(i);
        console.log(JSON.stringify(route.legs[i].distance.text));
        console.log(JSON.stringify(route.legs[i].duration.text));
      }

      directionsRenderer.setDirections(response);
    })
    .catch((status) => window.alert("Directions request failed due to " + status));
}
</script>

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBu-916DdpKAjTmJNIgngS6HL_kDIKU0aU&callback=myMap"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_Nve6IbnUVnldOf8qFM1i5YI904clpNk&callback=initMap" async defer></script>
<!--
To use this code on your website, get a free API key from Google.
Read more at: https://www.w3schools.com/graphics/google_maps_basic.asp
-->
