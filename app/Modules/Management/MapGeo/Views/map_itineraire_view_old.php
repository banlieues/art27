<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Itineraire</title>

    <style type="text/css">
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #floating-panel {
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
    </style>

  </head>
  <body>
    <div id="floating-panel">
      <select id="mode-transport">
        <option value="TRANSIT">En transport commun</option>
        <option value="DRIVING">En voiture</option>
        <option value="WALKING">En marchant</option>
        <option value="BICYCLING">En vélo</option>
      </select>
      &nbsp;
      <select id="select-origin">
        <option value="1">Adresse</option>
        <option value="2">Géolocalisation</option>        
      </select>
      <input type="text" id="your-address" placeholder="Entrer une adresse" size="35">
      <button id="load_origin">Afficher l'itinéraire</button>
    </div>
    <div id="map-carte" style="width:100%; height:450px"></div>
    <div id="panel"></div>

    <script>
      function initMap() {
        var directionsDisplay = new google.maps.DirectionsRenderer;
        var directionsService = new google.maps.DirectionsService;
        var geocoder = new google.maps.Geocoder();
        var map = new google.maps.Map(document.getElementById('map-carte'), {
	    zoom: 5,
	    mapTypeId: 'satellite'
	 
        });
        directionsDisplay.setMap(map);
        directionsDisplay.setPanel(panel);

        
        var origin = "<?=$destination;?>";
        calculateAndDisplayRoute(directionsService, directionsDisplay, origin);
        
        document.getElementById('load_origin').addEventListener('click', function(){
          var select = document.getElementById('select-origin').value;
          if(select == 1){
            var origin_new = document.getElementById('your-address').value;
            if(origin_new != ''){
                calculateAndDisplayRoute(directionsService, directionsDisplay, origin_new);
            }else{
              window.alert('Veuillez saisir une adresse valide !');
            }
          }

          if(select == 2){
            if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(function(position) {

                var origin_new = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
                calculateAndDisplayRoute(directionsService, directionsDisplay, origin_new);
		   

              },function() {
                  alert("Erreur autorisation: Veuillez verifier que vous autorisez la geolocalisation sur votre navigateur.")
              });
            }else{
                alert("votre navigateur ne prend pas en charge la geolocalisation");
            }
          }

        });

        document.getElementById('select-origin').addEventListener('change', function() {
              var select = document.getElementById('select-origin').value;
              if(select == 1){
                  document.getElementById('your-address').style.display = 'inline-block';
              }else{
                  document.getElementById('your-address').style.display = 'none';
              }
        });
        
        /*document.getElementById('mode-transport').addEventListener('change', function() {
          calculateAndDisplayRoute(directionsService, directionsDisplay, destination);
        });*/
      }

      function calculateAndDisplayRoute(directionsService, directionsDisplay, origin) {
        var selectedMode = document.getElementById('mode-transport').value;

        directionsService.route({
          origin: origin,  // Haight.
          destination: "<?= $destination;?>",  // Ocean Beach.
          // Note that Javascript allows us to access the constant
          // using square brackets and a string value as its
          // "property."
          travelMode: google.maps.TravelMode[selectedMode]
        }, function(response, status) {
          if (status == 'OK') {
	    //directionsDisplay.setOptions({preserveViewport: true});  
            directionsDisplay.setDirections(response);
	
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        }); 
        
      }
  
      function geocodeAddress(geocoder,address) {
        geocoder.geocode({"address": address}, function(results, status) {
          if (status === "OK") {
            return results[0].geometry.location;
          } else {
            alert("Geocode was not successful for the following reason: " + status);
          }
        });
      }
    </script>

    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAfIlffztCfgwK1KK511E90X58Bp8db9kk&callback=initMap">
    </script>

  </body>
</html>