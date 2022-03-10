<!DOCTYPE HTML>
<html>
  <head>
      <meta charset="UTF-8">
      <title>Routing Service Simple | Longdo Map</title>
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
      </style>

      <script type="text/javascript" src="https://api.longdo.com/map/?key=b864325f9aa7e61adad0b649a726210a"></script>
      <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
      <script>

        function init() {
          map = new longdo.Map({
            placeholder: document.getElementById('map')
          });
          longdoMapRouting();
        }

        function longdoMapRouting() { 
          $.ajax({ 
                  url: "https://api.longdo.com/RouteService/json/route/guide?flon=100.54898053407669&flat=13.743080902938331&tlon=100.55885508656502&tlat=13.724314618267575&mode=t&type=25&locale=th&key=b864325f9aa7e61adad0b649a726210a", 
                  dataType: "jsonp", 
                  type: "GET", 
                  contentType: "application/json", 
                  data: {
                      key: "b864325f9aa7e61adad0b649a726210a",
                      flat: 100.54898,
                      flon: 13.74308,
                      tlat: 100.55885,
                      tlon: 13.72431
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