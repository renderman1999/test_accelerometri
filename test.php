<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
        html { height: 100% }
        body { height: 100%; margin: 0; padding: 0 }
        #map_canvas { height: 100% }
    </style>
    <script type="text/javascript"
            src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBTkEpABPM_vo1KfyNnYZoyTPBPuvO5rs0&sensor=true">
    </script>
    <script type="text/javascript">
        var map;
        function initialize() {
            var mapOptions = {
                center: new google.maps.LatLng(41.890160, 12.489662),
                zoom: 7,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map_canvas"),
                mapOptions);

            $.ajax({
                url:'map2xml.php',
                dataType: "json",
                success: function(data){
                    $.each(data, function(key, data) {
                        var icon = "include/img/ico_default.png";
                        var mylatlng = new google.maps.LatLng(data.Latitudine, data.Longitudine);
                        // Creating a marker and putting it on the map
                        var marker = new google.maps.Marker({
                            position: mylatlng,
                            title: data.Sigla,
                            icon: icon

                        });

                        marker.setMap(map);

                    });

                }
            });

        }
    </script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>

</head>
<body onload="initialize()">
<div id="map_canvas" style="width:100%; height:100%"></div>
</html>