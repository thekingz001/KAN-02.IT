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
<!DOCTYPE HTML>
<html>
  <head>
      <meta charset="UTF-8">
      <title>Routing Map Sample | Longdo Map</title>
      <style type="text/css">
        html{
          height:100%; 
        }
        body{ 
          margin:0px;
          height:100%; 
        }
        #map {
          height: 100%;
        }
        #result {
          position: absolute;
          top: 0;
          bottom: 0;
          right: 0;
          width: 1px;
          height: 80%;
          margin: auto;
          border: 4px solid #dddddd;
          background: #ffffff;
          overflow: auto;
          z-index: 2;
        }
      </style>

      <script type="text/javascript" src="https://api.longdo.com/map/?key=b864325f9aa7e61adad0b649a726210a"></script>
      <script>

        // console.log(JSON.stringify(arr));
        // console.log(javaScriptVar);

        function init() {
          map = new longdo.Map({
            placeholder: document.getElementById('map')
          });
          map.Route.placeholder(document.getElementById('result'));
          for (let i = 0; i < arr.length; i++) {
          console.log(arr[i]);
          // console.log(javaScriptVar);
          // console.log(i);

          map.Route.add(new longdo.Marker({ lon: arr[i]["Longtitude"], lat: arr[i]["Latitude"] },
            { 
                title: arr[i]["CustName"], 
                detail: i 
            }
          ));
          }
          map.Event.bind('guideComplete', function(overlay) {
            console.log(map.Route.distance(true));
            console.log(map.Route.interval(true));
          });
          map.Route.search();
        }        
      </script>
  </head>
  <body onload="init();">
      <div id="map"></div>
      <div id="result"></div>
  </body>
</html>