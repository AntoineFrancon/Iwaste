<script type="text/javascript">


var map;
var panel;
var initialize;
var calculate;
var directionsDisplay;
var directionsService;
var random_remplissage;
var urgence;
var selection;
var selection2;
var remplissage = new Array(nbPoub+1).join('0').split('').map(parseFloat);
var selectionCoche;
var remplissage_poubelle1;





<?php
            $reponse = $bdd->query('SELECT id, remplissage FROM poubelle');
            
            while ($donnees = $reponse->fetch())
            {
                if ($donnees['id']==1) {
                    $opts = array(
                    'http'=>array(
                    'method'=>"GET",
                    'header'=>"apikey: 3uCDfWUJL8ALQm1np72o7/QPP6ktt/qbTzSjQSbNcq/ZrnSTQWsrncaN+DWnweVG5Ul6MQ5h+z1ifqSS8J+poA=="
                      )
                    );

                    $context = stream_context_create($opts);

                    // Open the file using the HTTP headers set above
                    $file = file_get_contents("https://api.objenious.com/v1/devices/562949953422033/messages", false, $context);
                    $obj = json_decode($file, true);
                    $remplissage_1 = $obj['messages'][1]['payload'][0]['data']['temperature'];
                    if (empty($remplissage_1)) {
                        echo 'remplissage[' . ($donnees['id']-1) . '] = -1;' ;
                    } else {
                       echo 'remplissage[' . ($donnees['id']-1) . '] = ' . $remplissage_1 .';' ;
                    }
                    
            }
                else {
                    echo 'remplissage[' . ($donnees['id']-1) . '] = ' . $donnees['remplissage'] .';' ;
                }
            }
            $reponse->closeCursor(); 
?>




selectionCoche = function(num) {
    if(document.getElementById('toHide'+num.toString()).style.display=="none")
    {
        document.getElementById('toHide'+num.toString()).style.display="";
    }
    else
    {
        document.getElementById('toHide'+num.toString()).style.display="none";
    }
    return true;
}

selection = function() {
    var cboxString = "cbox";
    for (var i = 0; i < nbPoub; i++) {
        var num = i+1;
        var poubelle = document.getElementById(cboxString.concat(num.toString()));
        poubelle.checked = true;

    }
    initialize();
}

selection2 = function() {
    var cboxString = "cbox";
    for (var i = 0; i < nbPoub; i++) {
        var num = i+1;
        var poubelle = document.getElementById(cboxString.concat(num.toString()));
        poubelle.checked = false;

    }
    initialize();
};



var taux_critique = 12;

urgence = function(){
    var cboxString = "cbox";
    for (var i = 0; i < nbPoub; i++) {
        var num = i+1;
        var poubelle = document.getElementById(cboxString.concat(num.toString()));
        if (prediction_remplissage_list[i]<taux_critique) {
            poubelle.checked = true;
        } else {poubelle.checked = false;}
    };
    initialize();
};





initialize = function(){
    
  prediction();

  var location = new google.maps.LatLng(48.725227, 2.166109);
  var locations = new Array(nbPoub+1).join('0').split('').map(parseFloat);

        <?php
            $reponse = $bdd->query('SELECT id, latitude, longitude FROM poubelle');
            
            while ($donnees = $reponse->fetch())
            {
                echo 'locations[' . ($donnees['id']-1) . '] = new google.maps.LatLng(' . $donnees['latitude'] . ', ' . $donnees['longitude'] . ');' ;
            }
            $reponse->closeCursor(); 

                $reqTest = $bdd->prepare('UPDATE poubelle SET remplissage = :remplissage WHERE id = :id');
                $reqTest->execute(array(
                    'remplissage' => $remplissage_1,
                    'id' => 1
                    ));
                $reqTest2 = $bdd->prepare('INSERT INTO historique(num_poubelle, remplissage, date_remp) VALUES(:num_poubelle, :remplissage, NOW())');
                $reqTest2->execute(array(
                'num_poubelle' => 1,
                'remplissage' => $remplissage_1
                ));
        ?>



        var mapCanvas = document.getElementById('map');
        var mapOptions = {
            center: location,
            zoom: 13,
            panControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(mapCanvas, mapOptions);

        var markerImage = 'images/marker.png';
        var markerImageRed = 'images/marker_red.png';
        var markerImageOrange = 'images/marker_orange.png';
        var markerImageYellow = 'images/marker_yellow.png';
        var markerImageGreen = 'images/marker_green.png';
        var markerSelected = 'images/marker_selected.png';
        var markerOffline = 'images/connection_error.png';








        var images_test = new Array(nbPoub+1).join('0').split('').map(parseFloat);
        var markers_test = new Array(nbPoub+1).join('0').split('').map(parseFloat);
        var contentStrings_test = new Array(nbPoub+1).join('0').split('').map(parseFloat);
        var cboxString_test = "cbox";
        var num_test = 1;
        var poubelles_test = new Array(nbPoub+1).join('0').split('').map(parseFloat);

        
        infowindows_test = new google.maps.InfoWindow({ maxWidth: 300});

        for (var i = 0; i < nbPoub; i++) {
        
                num_test=i+1;
                poubelles_test[i] = document.getElementById(cboxString_test.concat(num_test.toString()));
                images_test[i] = markerImage;
                if (remplissage[i] == -1) {images_test[i] = markerOffline} else if (poubelles_test[i].checked==true) {images_test[i] = markerSelected} else if (remplissage[i] <= 40) {images_test[i] = markerImageGreen} else if (remplissage[i] <= 60) {images_test[i] = markerImageYellow} else if (remplissage[i] <= 80) {images_test[i] = markerImageOrange} else if (remplissage[i]>80) {images_test[i] = markerImageRed}
                
                if (remplissage[i] == -1) { 
                var commentaire = '<p><strong>Capteur non connecté</strong></p>';
                contentStrings_test[i] = '<div class="info-window">' +
                        '<h3>Poubelle ' + num_test.toString() + '</h3>' +
                        '<div class="info-content">' +
                        '<p>' + poubelles_test[i].value + '</p>' +
                        '<p><strong>Taux de remplissage : Offline</strong></p>' +
                        commentaire +
                            '<form action="info_bin.php" method="post" >'+
                                '<h6>'+
                                    '<input type="hidden" name="num_poubelle" value="' + num_test.toString() + '"/>'+
                                    '<input type="submit" style="background-color: #091D9E; font-weight: bold;  color: white; border-radius: 3px;" value="En savoir plus sur cette poubelle"  />'+
                                '</h6>'+
                            '</form>' +
                            '</div>' +
                        '</div>';
                } else {
                var commentaire = '<p><strong>A vider dans : ' + prediction_remplissage_list[i].toString() + ' heures</strong></p>';
                
                if (prediction_remplissage_list[i]<=0) {commentaire='<p style="color: red"><strong>A vider d\'urgence</strong></p>'} else if (prediction_remplissage_list[i]<=0) {commentaire='<p style="color: red"><strong>A vider d\'urgence</strong></p>'};
                
                contentStrings_test[i] = '<div class="info-window">' +
                        '<h3>Poubelle ' + num_test.toString() + '</h3>' +
                        '<div class="info-content">' +
                        '<p>' + poubelles_test[i].value + '</p>' +
                        '<p><strong>Taux de remplissage : ' + remplissage[i].toString() + '%</strong></p>' +
                        commentaire +
                            '<form action="info_bin.php" method="post" >'+
                                '<h6>'+
                                    '<input type="hidden" name="num_poubelle" value="' + num_test.toString() + '"/>'+
                                    '<input type="submit" style="background-color: #091D9E; font-weight: bold;  color: white; border-radius: 3px;" value="En savoir plus sur cette poubelle"  />'+
                                '</h6>'+
                            '</form>' +
                            '</div>' +
                        '</div>';
                }

                
                markers_test[i] = new google.maps.Marker({
                    position: locations[i],
                    map: map,
                    icon: images_test[i],
                    content: contentStrings_test[i],
                }); 

                

                google.maps.event.addListener(markers_test[i], 'click', function() { 
                infowindows_test.setContent(this.content);
                infowindows_test.open(this.getMap(), this); 
                }); 


        }

        var origin      = document.getElementById('origin').value; // Le point départ
        var destination = document.getElementById('destination').value; // Le point d'arrivé

        if (origin == "1 Rue Joliot Curie, 91190 Gif-sur-Yvette, France") {var location_start = new google.maps.LatLng(48.7100841, 2.16328329)} else if (origin == "Route de Saclay, 91128 Palaiseau") {var location_start = new google.maps.LatLng(48.7190252, 2.22132650)} else {var location_start = new google.maps.LatLng(48.6976847, 2.17648389)};
        if (destination == "1 Rue Joliot Curie, 91190 Gif-sur-Yvette, France") {var location_end = new google.maps.LatLng(48.7100841, 2.16328329)} else if (destination == "Route de Saclay, 91128 Palaiseau") {var location_end = new google.maps.LatLng(48.7190252, 2.22132650)} else {var location_end = new google.maps.LatLng(48.6976847, 2.17648389)};
        
        var marker_start = new google.maps.Marker({
                    position: location_start,
                    map: map,
                    icon: 'images/camion_poubelle_start.png',
                    content: 'Départ du trajet',
                });

        google.maps.event.addListener(marker_start, 'click', function() { 
                infowindows_test.setContent(this.content);
                infowindows_test.open(this.getMap(), this); 
                });

        var marker_end = new google.maps.Marker({
                    position: location_end,
                    map: map,
                    icon: 'images/camion_poubelle_end.png',
                    content: 'Arrivée du trajet',
                });

        google.maps.event.addListener(marker_end, 'click', function() { 
                infowindows_test.setContent(this.content);
                infowindows_test.open(this.getMap(), this); 
                });

        

        var styles = [{"elementType":"labels","stylers":[{"visibility":"off"},{"color":"#f49f53"}]},{"featureType":"landscape","stylers":[{"color":"#ddcec1"},{"lightness":-7}]},{"featureType":"road","stylers":[{"color":"#813033"},{"lightness":43}]},{"featureType":"poi.business","stylers":[{"color":"#645c20"},{"lightness":38}]},{"featureType":"water","stylers":[{"color":"#1994bf"},{"saturation":-69},{"gamma":0.99},{"lightness":43}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"#f19f53"},{"weight":1.3},{"visibility":"on"},{"lightness":16}]},{"featureType":"poi.business"},{"featureType":"poi.park","stylers":[{"color":"#645c20"},{"lightness":39}]},{"featureType":"poi.school","stylers":[{"color":"#d17942"},{"lightness":35}]},{},{"featureType":"poi.medical","elementType":"geometry.fill","stylers":[{"color":"#813033"},{"lightness":38},{"visibility":"off"}]},{},{},{},{},{},{},{},{},{},{},{},{"elementType":"labels"},{"featureType":"poi.sports_complex","stylers":[{"color":"#9e5916"},{"lightness":32}]},{},{"featureType":"poi.government","stylers":[{"color":"#9e5916"},{"lightness":46}]},{"featureType":"transit.station","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","stylers":[{"color":"#813033"},{"lightness":22}]},{"featureType":"transit","stylers":[{"lightness":38}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"color":"#f19f53"},{"lightness":-10}]},{},{},{}]

        map.set('styles', styles);
  
  
        directionsDisplay = new window.google.maps.DirectionsRenderer({map : map, suppressMarkers: false});
        directionsService = new window.google.maps.DirectionsService();


        
        document.roundtrip.elements["loc0"].value = document.getElementById('origin').value;

        var cboxString = "cbox";
        var j = 1;
            for (var i = 0; i < nbPoub; i++) {
                var num = i+1;
                var waypoints= document.getElementById(cboxString.concat(num.toString()));
                if (waypoints.checked == true) {
                    document.roundtrip.elements["loc"+j.toString()].value = waypoints.value;
                    j=j+1;
                    }
            }



};



calculate = function (){

            initialize();
            origin      = document.getElementById('origin').value; // Le point départ
            destination = document.getElementById('destination').value; // Le point d'arrivé

            directionsDisplay.setOptions( { suppressMarkers: true } );

            var stops = [origin];
            var cboxString = "cbox";
            
            for (var i = 0; i < nbPoub; i++) {
                var num = i+1;
                var waypoints= document.getElementById(cboxString.concat(num.toString()));
                if (waypoints.checked == true) {
                    stops = stops.concat(waypoints.value);
                    }
            }

            stops = stops.concat(destination);

            var batches = [];
            var itemsPerBatch = 10; // google API max = 10 - 1 start, 1 stop, and 8 waypoints
            var itemsCounter = 0;
            var wayptsExist = stops.length > 0;

            while (wayptsExist) {
                var subBatch = [];
                var subitemsCounter = 0;

                for (var j = itemsCounter; j < stops.length; j++) {
                    subitemsCounter++;
                    subBatch.push({
                        location: stops[j],
                        stopover: true
                    });
                    if (subitemsCounter == itemsPerBatch)
                        break;
                }

                itemsCounter += subitemsCounter;
                batches.push(subBatch);
                wayptsExist = itemsCounter < stops.length;
                // If it runs again there are still points. Minus 1 before continuing to
                // start up with end of previous tour leg
                itemsCounter--;
            }

            // now we should have a 2 dimensional array with a list of a list of waypoints
            var combinedResults;
            var unsortedResults = [{}]; // to hold the counter and the results themselves as they come back, to later sort
            var directionsResultsReturned = 0;

            for (var k = 0; k < batches.length; k++) {
                var lastIndex = batches[k].length - 1;
                var start = batches[k][0].location;
                var end = batches[k][lastIndex].location;

                // trim first and last entry from array
                var waypts = [];
                waypts = batches[k];
                waypts.splice(0, 1);
                waypts.splice(waypts.length - 1, 1);

                var request = {
                    origin: start,
                    destination: end,
                    waypoints: waypts,
                    travelMode: window.google.maps.TravelMode.DRIVING
                };
                (function (kk) {
                    directionsService.route(request, function (result, status) {
                        if (status == window.google.maps.DirectionsStatus.OK) {

                            var unsortedResult = { order: kk, result: result };
                            unsortedResults.push(unsortedResult);

                            directionsResultsReturned++;

                            if (directionsResultsReturned == batches.length) // we've received all the results. put to map
                            {
                                // sort the returned values into their correct order
                                unsortedResults.sort(function (a, b) { return parseFloat(a.order) - parseFloat(b.order); });
                                var count = 0;
                                for (var key in unsortedResults) {
                                    if (unsortedResults[key].result != null) {
                                        if (unsortedResults.hasOwnProperty(key)) {
                                            if (count == 0) // first results. new up the combinedResults object
                                                combinedResults = unsortedResults[key].result;
                                            else {
                                                // only building up legs, overview_path, and bounds in my consolidated object. This is not a complete
                                                // directionResults object, but enough to draw a path on the map, which is all I need
                                                combinedResults.routes[0].legs = combinedResults.routes[0].legs.concat(unsortedResults[key].result.routes[0].legs);
                                                combinedResults.routes[0].overview_path = combinedResults.routes[0].overview_path.concat(unsortedResults[key].result.routes[0].overview_path);

                                                combinedResults.routes[0].bounds = combinedResults.routes[0].bounds.extend(unsortedResults[key].result.routes[0].bounds.getNorthEast());
                                                combinedResults.routes[0].bounds = combinedResults.routes[0].bounds.extend(unsortedResults[key].result.routes[0].bounds.getSouthWest());
                                            }
                                            count++;
                                        }
                                    }
                                }
                                directionsDisplay.setDirections(combinedResults);
                                directionsDisplay.setPanel(document.getElementById('panel'));
                                var legs = combinedResults.routes[0].legs;
                                // alert(legs.length);
                                // for (var i=0; i < legs.length;i++){
                                //   var markerletter = "A".charCodeAt(0);
                                //   markerletter += i;
                                //   markerletter = String.fromCharCode(markerletter);
                                //   createMarker(directionsDisplay.getMap(),legs[i].start_location,"marker"+i,"some text for marker "+i+"<br>"+legs[i].start_address,markerletter);
                                // }
                                // var i=legs.length;
                                // var markerletter = "A".charCodeAt(0);
                                // markerletter += i;
                                // markerletter = String.fromCharCode(markerletter);
                                // createMarker(directionsDisplay.getMap(),legs[legs.length-1].end_location,"marker"+i,"some text for the "+i+"marker<br>"+legs[legs.length-1].end_address,markerletter);
                            }
                        }
                    });
                })(k);
            }

        
};




initialize();

</script>
