<!DOCTYPE HTML>
<html>
  <head>
      <meta charset="UTF-8">
      <title>Nearby Search Simple | Longdo Map</title>
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
          width: 100%;
        }
      </style>

      <script type="text/javascript" src="https://api.longdo.com/map/?key=b864325f9aa7e61adad0b649a726210a"></script>
      <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
      <script>

        function init() {
          map = new longdo.Map({
            placeholder: document.getElementById('map')            
          });
          searchNearby();
        }

        function searchNearby() { 
          $.ajax({ 
            url: "https://api.longdo.com/POIService/json/search?", 
            dataType: "jsonp", 
            type: "GET", 
            contentType: "application/json", 
            data: {
                key: "b864325f9aa7e61adad0b649a726210a",
                lon: 1.54898,
                lat: 3.74308,
            },
            success: function (results)
            {
              console.log(results);
            },
            error: function (response)
            {
              console.log(response);
            }
          });
        }
      </script>
  </head>
  <body onload="init();">
      <div id="map"></div>
  </body>
</html>