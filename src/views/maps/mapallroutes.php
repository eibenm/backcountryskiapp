<?php use yii\helpers\Json; ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>All Routes</title>
        <link rel="shortcut icon" href="<?= Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
        <style type="text/css">
            html { height: 100% }
            body { height: 100%; margin: 0; padding: 0 }
            #map { height: 100% }
            .noscrollbar {
                line-height: 1.35;
                overflow: hidden;
                white-space: nowrap;
            }
            #sidebar {
                background-color: rgba(255,255,255,0.7);
                position: absolute;
                padding: 10px;
                top: 50px;
                right: 6px;
                z-index: 100;
                border-radius: 4px;
                border: 1px #8A8A8A solid;
                height: 500px;
                overflow-y: scroll;
            }
        </style>
    </head>
    <body>
        <div id="map">
            <div id="sidebar">
                <button id="select-all-areas">Select All</button>
                <?php foreach ($gpsdataArray as $area => $gpsdata): ?>
                <p>
                    <input type="checkbox" name="checkbox" id="<?= $area ?>" class="area-checkbox" checked>
                    <label for="<?= $area ?>"><?= $area ?></label>
                </p>
                <?php endforeach; ?>
            </div>
        </div>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3&key=<?= Yii::$app->constants->googleApiKey ?>"></script>
        <script type="text/javascript">

            var Ski = (function() {

                var SkiObject = {
                    gpsdata: <?= Json::encode($gpsdataArray) ?>
                };

                var map;
                var markersArray = new Array();
                var boundsArray = new Array();
                var gpsdata = SkiObject.gpsdata;

                SkiObject.init = function() {
                    var mapOptions = {
                        noClear: true,
                        panControl: true,
                        mapTypeControlOptions: {
                            mapTypeIds: [
                                google.maps.MapTypeId.ROADMAP,
                                google.maps.MapTypeId.SATELLITE,
                                google.maps.MapTypeId.HYBRID,
                                google.maps.MapTypeId.TERRAIN
                            ],
                            style: google.maps.MapTypeControlStyle.DEFAULT
                        },
                        mapTypeId: google.maps.MapTypeId.TERRAIN
                    };
                    map = new google.maps.Map(document.getElementById('map'), mapOptions);
                    map.setCenter(new google.maps.LatLng(45.678, -111.041));
                    map.setZoom(9);
                    map.fitBounds(addMarkers());
                };

                var addMarkers = function() {
                    // Markers
                    var markerBounds = new google.maps.LatLngBounds();
                    var infowindow = new google.maps.InfoWindow();
                    for (var area in gpsdata) {
                        var areaArray = gpsdata[area];
                        for (var index in areaArray.gps) {
                            var waypoint = areaArray.gps[index];
                            var marker = new google.maps.Marker({
                                position: new google.maps.LatLng(waypoint.lat,waypoint.lon),
                                map: map
                            });
                            marker.area = area;
                            markerBounds.extend(marker.getPosition());
                            markersArray.push(marker);
                            var content = '<table class="noscrollbar">';
                            content += '<tr><th align="right">Area &nbsp; - &nbsp;</th><td>' + area + '</td></tr>';
                            for (var key in waypoint) { content += '<tr><th align="right">' + key + ' &nbsp; - &nbsp;</th><td>' + waypoint[key] + '</td></tr>'; }
                            content += '</table>';
                            google.maps.event.addListener(marker,'click', (function(marker, content, infowindow) { 
                                return function() {
                                    infowindow.setContent(content);
                                    infowindow.open(map,marker);
                                 };
                            })(marker,content,infowindow));
                        }
                        // Bounds
                        var bounds = areaArray.bounds;
                        var rectangle = new google.maps.Rectangle({
                            bounds: new google.maps.LatLngBounds(
                                new google.maps.LatLng(bounds.southwest.lat, bounds.southwest.lon),
                                new google.maps.LatLng(bounds.northeast.lat, bounds.northeast.lon)
                            ),
                            fillColor: 'transparent',
                            map: map,
                            strokeColor: 'black',
                            strokeWeight: 1
                        });
                        rectangle.area = area;
                        boundsArray.push(rectangle);
                    }
                    return markerBounds;
                };

                SkiObject.filterMarkers = function(area, visible) {
                    markersArray.filter(function(marker) { return marker.area === area; }).forEach(function(marker) { marker.setVisible(visible); });
                    boundsArray.filter(function(rectangle) { return rectangle.area === area; }).forEach(function(rectangle) { rectangle.setVisible(visible); });
                };

                SkiObject.visibleMarkers = function(visible) {
                    markersArray.forEach(function(marker) {  marker.setVisible(visible); });
                    boundsArray.forEach(function(rectangle) { rectangle.setVisible(visible); });
                };

                return SkiObject;

            })();

            google.maps.event.addDomListener(window, 'load', Ski.init);

        </script>

        <script type="text/javascript">

            var allChecked = true;
            var areaCheckboxes = document.getElementsByClassName('area-checkbox');

            for (var i = 0; i < areaCheckboxes.length; i++) {
                var input = areaCheckboxes[i];
                input.addEventListener('click', function() {
                   if (this.checked) Ski.filterMarkers(this.id, true);
                   if (!this.checked) Ski.filterMarkers(this.id, false);
                });
            }

            document.getElementById('select-all-areas').addEventListener('click', function() {
                allChecked = !allChecked;
                Ski.visibleMarkers(allChecked);
                for (var i = 0; i < areaCheckboxes.length; i++) {
                    areaCheckboxes[i].checked = allChecked;
                }
            });

        </script>
    </body>
</html>
