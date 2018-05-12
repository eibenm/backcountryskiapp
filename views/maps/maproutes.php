<?php use yii\helpers\Json; ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $routename ?></title>
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
            .customControl {
                background-color: rgba(255,255,255,0.8);
                border: 1px solid black;
                cursor: pointer;
                text-align: center;
                font: 18px arial, sans-serif;
                padding: 0 6px;
            }
            .customControl:hover { background-color: rgba(255,255,255,1.0); }
        </style>
    </head>
    <body>
        <div id="map"></div>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3&key=<?= Yii::$app->constants->googleApiKey ?>"></script>
        <script type="text/javascript">

            var Ski = (function() {
               
                // Create object to make accessible to the 'Ski' namespace
                var SkiObject = {
                    gpsdata: <?= Json::encode($gpsdata) ?>
                };
                
                var map;
                var markersArray = new Array();
                var gpsdata = SkiObject.gpsdata;
                var markerBounds;
                
                /* Private Functions */
                function RemoveMarkersControl(controlDiv) {

                    controlDiv.style.padding = '6px';

                    // Control Exterior
                    var controlUI = document.createElement('div');
                    controlUI.className = 'customControl noscrollbar';
                    controlUI.title = 'Click to Add/Remove Markers';
                    controlDiv.appendChild(controlUI);

                    // Control Interior
                    var controlText = document.createElement('div');
                    controlText.innerHTML = '<b>Remove Markers</b>';
                    controlUI.appendChild(controlText);

                    google.maps.event.addDomListener(controlUI, 'click', function() {
                        if (!markersArray[0].getMap(map)) {
                            Ski.addMarkers();
                            controlText.innerHTML = '<b>Remove Markers</b>';
                        } else {
                            Ski.removeMarkers();
                            controlText.innerHTML = '<b>Add Markers</b>';
                        }
                    });
                }
                
                /* Public Functions */
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

                    if (SkiObject.gpsdata.length) {
                        Ski.addMarkers();
                    }
                    else {
                        map.setCenter(new google.maps.LatLng(45.678, -111.041));
                        map.setZoom(9);
                    }
                    
                    map.fitBounds(markerBounds);
                    
                    var markerControlDiv = document.createElement("div");
                    new RemoveMarkersControl(markerControlDiv, map);
                    markerControlDiv.index = 1;
                    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(markerControlDiv);
                };
                
                SkiObject.addMarkers = function() {
                    markersArray.length = 0;
                    markerBounds = new google.maps.LatLngBounds();
                    
                    for (var i = 0; i < gpsdata.length; i++) {
                        
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(gpsdata[i].lat,gpsdata[i].lon),
                            map: map
                        });
                        
                        markerBounds.extend(marker.getPosition());
                        markersArray.push(marker);
                        
                        var infowindow = new google.maps.InfoWindow();
                        
                        google.maps.event.addListener(marker, 'click', (function(marker, i) {
                            
                            return function() {
                                var contentString = '<table class="noscrollbar">';
                                Object.keys(gpsdata[i]).forEach(function(key) {
                                     contentString += '<tr><th align="right">' + key + ' &nbsp; - &nbsp;</th><td>' + gpsdata[i][key] + '</td></tr>';
                                });
                                contentString += '</table>';
                                infowindow.setContent(contentString);
                                infowindow.open(map, marker);
                            };
                            
                        })(marker, i));
                    }
                };
                
                SkiObject.removeMarkers = function() {
                    markersArray.forEach(function(marker) { 
                        marker.setMap(null);
                    });
                };
                
                return SkiObject;

            })();

            google.maps.event.addDomListener(window, 'load', Ski.init);

        </script>
    </body>
</html>