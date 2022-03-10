<?php 
  include('condb.php'); 
  // เริ่มใช้ session
  session_start();
  //select สาขาบน database
  $branch = "SELECT * FROM nps.branch where Addr2 = '1' and BranchName not like '%ONLINE%' ORDER BY CONVERT (BranchName USING tis620)" ;
  $result_branch = $con->query($branch);  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['clear'])) {
        # code...
        unset($_SESSION['codesale']);
        unset($_SESSION['SaleName']);
        unset($_SESSION['BranchCode']);
    }
    // ค้นหาเลือกสาขา
    if (!empty($_POST['branch'])) {
      $_SESSION['BranchCode'] = $_POST['branch'];
      $BranchCode = $_POST['branch'];

      //บันทึกชื่อสาขา
      $BranchCode2 = "SELECT * FROM nps.branch where BranchCode = '$BranchCode'";
      $result_BranchCode= $con->query($BranchCode2);

      if ($result_BranchCode->num_rows > 0) {
        while($row = $result_BranchCode->fetch_assoc()) {
          $_SESSION['BranchName'] = $row['BranchName'];
        }
      }
    }
    // บันทึกเซลล์
    if (!empty($_POST['sales'])) {
      $_SESSION['codesale'] = $_POST['sales'];
      $salesCode = $_POST['sales'];
      // $_SESSION['salesCode'];

      //บันทึกชื่อสาขา
      $salesCode2 = "SELECT * FROM nps.sales where SalesNo = '$salesCode'";
      $result_salesCode2= $con->query($salesCode2);

      if ($result_salesCode2->num_rows > 0) {
          while($row = $result_salesCode2->fetch_assoc()) {
              $_SESSION['SaleName'] = $row['SalesName'];
              // $_SESSION['SaleNo'] = $row['SalesNo'];
          }
      }
    }
      
  }
  if (empty($_SESSION['BranchCode'])) {
  # code...
  }else {
    $BC = $_SESSION['BranchCode'];
    //ค้นหาชื่อเซลล์แสดงบนดรอปดาว
    $BranchCode = $_SESSION['BranchCode'];
    $sales = "SELECT * FROM nps.sales where BranchCode = '$BranchCode' and Active = '1' AND SalesName not like 'สาขา%'  AND SalesName not like '%ซุป%'  AND SalesName not like '%ผจก%' AND SalesNo not in ('NPS03','NPS02')";
    $result_sales = $con->query($sales);
  }

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
  <title>Test Map Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
  <div id="container">
    <div class="container-fluid mt-5">
      <h3>Test Map Page</h3><br />
      <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <div class="row">
          <div class="col-sm-2">
            <!-- ฟังชั่นเลือกสาขา -->
              <select class="form-select" onchange="submit();" id="exampleFormControlSelect1" name="branch">
                <option value="">เลือกสาขา</option>
                <?php 
                  if ($result_branch->num_rows > 0) {                                        
                ?>
                <?php while($row = $result_branch->fetch_assoc()) { ?>
                <option value='<?php echo $row['BranchCode']; ?>' <?php if(empty($_SESSION['BranchCode'])){}else {
                  if ($_SESSION['BranchCode']==$row['BranchCode']) {
                    echo "selected";
                  }else{}
                  }  
                  ?>>
                  <?php echo $row['BranchName']; ?>
                </option>
                <?php } ?>
                <?php
                  }else {	}
                ?>
            </select>
          </div>
            <div class="col-sm-3">
                <!-- ฟังชั่นเลือกเซลล์ -->
                <select class="form-select" onchange="submit();" id="exampleFormControlSelect1" name="sales">
                    <option value="">เลือกเซลล์</option>
                    <?php 
                      if ($result_sales->num_rows > 0) {                                           
                    ?>
                    <?php while($row = $result_sales->fetch_assoc()) { ?>
                    <option value='<?php echo $row['SalesNo']; ?>' <?php if(empty($_SESSION['codesale'])){}else {
                        if ($_SESSION['codesale']==$row['SalesNo']) {
                          echo "selected";
                        }else{}
                      }  
                    ?>>

                        <?php echo $row['SalesName']; ?>
                    </option>
                    <?php } ?>
                    <?php         
                      }else {}
                    ?>
                </select>
            </div>
            <div class="col-sm-1">
              <!-- <input class="btn btn-primary" name="clear" value="รีโหลด" onclick="initMap()"/> -->
              <button type="button" id="submit" class="btn btn-success"onclick=" calculateAndDisplayRoute()">ค้นหา</button>
            </div>
          <!-- <a href="#" class="btn btn-info" onClick="refreshPage()">
            <span class="glyphicon glyphicon-refresh"></span> Refresh
          </a> -->
        </div>
      </form>
        <div id="map" style="width: 1800px; height: 600px;"></div>
        <div id="sidebar">
            <table class="table table-striped table-hover">
                <thead>
                    <th>ลำดับที่</th>
                    <th>ชื่อร้าน</th>
                    <th>ระยะทาง</th>
                    <th>เวลาโดยประมาน</th>
                </thead>
                <tbody class="table-active" id="directions-panel">
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>


<script>
// function myMap() {
//   var mapCanvas = document.getElementById("map");
//   var mapOptions = {
//     center: new google.maps.LatLng(13.75654681181717, 100.5018562951065), zoom: 15
//   };
//   var map = new google.maps.Map(mapCanvas, mapOptions);
// }

// function initMap() {
//   const directionsService = new google.maps.DirectionsService();
//   const directionsRenderer = new google.maps.DirectionsRenderer();
//   const map = new google.maps.Map(document.getElementById("map"), {
//     zoom: 7,
//     center: { lat: 41.85, lng: -87.65 },
//   });

//   directionsRenderer.setMap(map);

//   const onChangeHandler = function () {
//     calculateAndDisplayRoute(directionsService, directionsRenderer);
//   };

//   document.getElementById("start").addEventListener("change", onChangeHandler);
//   document.getElementById("end").addEventListener("change", onChangeHandler);
// }

// function calculateAndDisplayRoute(directionsService, directionsRenderer) {
//   directionsService
//     .route({
//       origin: {
//         query: document.getElementById("start").value,
//       },
//       destination: {
//         query: document.getElementById("end").value,
//       },
//       travelMode: google.maps.TravelMode.DRIVING,
//     })
//     .then((response) => {
//       console.log(JSON.stringify(response));
//       directionsRenderer.setDirections(response);
//     })
//     .catch((e) => window.alert("Directions request failed due to " + status));
// }

function initMap() {
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer();
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 6,
        center: {
            lat: 13.7563631,
            lng: 100.5017719
        },
    });

    directionsRenderer.setMap(map);
    document.getElementById("submit").addEventListener("click", () => {
        calculateAndDisplayRoute(directionsService, directionsRenderer);
    });
}

function calculateAndDisplayRoute(directionsService, directionsRenderer) {
    const waypts = [];
    const checkboxArray = document.getElementById("waypoints");
    // const checkboxArray = [];
    for (let i = 0; i < arr.length; i++) {
        // if (checkboxArray.options[i].selected) {
        // console.log(JSON.stringify(checkboxArray[i].value));

        // console.log(arr.length);

        if (i == 0) {
            // do your thing
            var origin = new google.maps.LatLng(arr[i].Latitude, arr[i].Longtitude);
            // console.log(JSON.stringify("origin = " + origin));
        } else if (i == arr.length - 1) {
            var destination = new google.maps.LatLng(arr[i].Latitude, arr[i].Longtitude);
            // console.log(JSON.stringify("destination = " + destination));
        } else {
            var test = new google.maps.LatLng(arr[i].Latitude, arr[i].Longtitude);
            waypts.push({
                location: test,
                stopover: true,
            });
            // console.log(JSON.stringify(i + " = " + test));
        }

        // if (i == arr.length) {
        //   // do your thing
        //   var destination = new google.maps.LatLng(arr[i].Latitude, arr[i].Longtitude);
        //   console.log(JSON.stringify("destination = " + destination));
        // }else{
        //   var origin = new google.maps.LatLng(arr[i].Latitude, arr[i].Longtitude);
        //   console.log(JSON.stringify("origin = " + origin));
        // }




        //   console.log(JSON.stringify(waypts));
        // }
    }
    // for (let i = 0; i < checkboxArray.length; i++) {
    //   if (checkboxArray.options[i].selected) {
    //     // console.log(JSON.stringify(checkboxArray[i].value));
    //     waypts.push({
    //       location: checkboxArray[i].value,
    //       stopover: true,
    //     });
    //     console.log(JSON.stringify(waypts));
    //   }
    // }

    directionsService
        .route({
            origin: origin,
            destination: destination,
            waypoints: waypts,
            optimizeWaypoints: true,
            travelMode: google.maps.TravelMode.DRIVING,
        })
        .then((response) => {
            // console.log(JSON.stringify(response));
            directionsRenderer.setDirections(response);

            const route = response.routes[0];
            const summaryPanel = document.getElementById("directions-panel");

            summaryPanel.innerHTML = "";
            var total_distance = 0;
            var total_duration = 0;
            // For each route, display summary information.
            for (let i = 0; i < route.legs.length; i++) {
                const routeSegment = i + 1;
                total_distance = total_distance += route.legs[i].distance.value;
                total_duration = total_duration += route.legs[i].duration.value;

                summaryPanel.innerHTML += "<tr><td>" + routeSegment + "</td><td>" + arr[i].CustName + "</td><td>" +
                    route.legs[i].distance.text + "</td><td>" + route.legs[i].duration.text + "</td><td></td></tr>";

                // summaryPanel.innerHTML +="<b>สถานที่ ที: " + routeSegment + "</b><br>";
                // summaryPanel.innerHTML += route.legs[i].distance.text + "<br><br>";
                // summaryPanel.innerHTML += route.legs[i].duration.text + "<br><br>ระยะทางทั้งหมด";
            }

            total_distance = total_distance / 1000;
            // total_duration= ((total_duration)/60)/60;

            let sec = parseInt(total_duration, 10); // convert value to number if it's string
            let hours = Math.floor(sec / 3600); // get hours
            let minutes = Math.floor((sec - (hours * 3600)) / 60); // get minutes
            let seconds = sec - (hours * 3600) - (minutes * 60); //  get seconds
            // add 0 if value < 10; Example: 2 => 02
            if (hours < 10) {
                hours = hours;
            }
            if (minutes < 10) {
                minutes = "0" + minutes;
            }
            if (seconds < 10) {
                seconds = "0" + seconds;
            }
            // console.log(hours+':'+minutes+':'+seconds); // Return is HH : MM : SS

            summaryPanel.innerHTML += "<tr><td colspan=" + 2 + " class=" + "text-center" + "> รวม </td><td>" + ((
                    total_distance)) + "</td><td>" + hours + " ชั่วโมง " + minutes + " นาที " + seconds +
                " วินาที" + "</td></tr>"

            // summaryPanel.innerHTML += ((total_distance)) + "กม.<br><br>ระยะเวลาทั้งหมด";
            // summaryPanel.innerHTML += (Math.floor(total_duration)) + " ชั่วโมง<br><br>";
            // console.log((total_distance)/1000);
            // console.log((total_duration)/600);


        })
        .catch((e) => window.alert("Directions request failed due to " + e));
}
</script>

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBu-916DdpKAjTmJNIgngS6HL_kDIKU0aU&callback=myMap"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_Nve6IbnUVnldOf8qFM1i5YI904clpNk&callback=initMap" async
    defer></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
</script>


<!--
To use this code on your website, get a free API key from Google.
Read more at: https://www.w3schools.com/graphics/google_maps_basic.asp
-->