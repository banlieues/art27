<script>

// --------------------------------
// GOOGLEMAPS.JS
// --------------------------------

let map = {};
let markers = [];
let panorama;

const mapConfig = {
    center_lat : 50.855,
    center_lon : 4.375,
    zoom : 10,
};

const keys = {
    googlemaps : "AIzaSyAfIlffztCfgwK1KK511E90X58Bp8db9kk",
}

$(document).ready(function() {
    if ("geolocation" in navigator) {
        $('.geolocation-col').show();
    }
});

function initMap() {
    const map_id = $('#google_map')[0];
    map = new google.maps.Map(map_id, {
        center: { lat: mapConfig.center_lat, lng: mapConfig.center_lon },
        zoom: mapConfig.zoom,
        // streetViewControl: false,
        // zoomControl: false,
        options: {
            styles: [
                {
                    "featureType": "poi",
                    "stylers": [
                        {
                            "visibility": "off",
                        },
                    ],
                },
            ],
        }
    });
    markersGet(map_id, function(result) {
        $('.loading', '.card-googlemaps').fadeOut(function() { $('.card-body', '.card-googlemaps').fadeIn(); });
        if(markers.length>0) {
            if(typeof result.zoom != 'undefined') {
                map.setZoom(result.zoom);
            }
            if(typeof result.default_layer != 'undefined') {
                map.setMapTypeId(result.default_layer);
            }
            if(markers.length==1) {
                const marker = markers[0];
                map.panTo(marker.getPosition());
                if(result.street_view_location && result.street_view_location==true) {
                    createStreetViewControl(map, marker, function(button) {
                        $(button).on("click", toggleStreetView);
                    });
                }

                if(result.itinerary && result.itinerary==true) {

                    $('#itineraryForm').removeClass('d-none').addClass('row');

                    $('#itinerarySubmit').on('click', function() {
                        if($("#itineraryInput").val().trim().length > 0) {
                            calculateAndDisplayRoute($('#itineraryInput').val(), marker.getPosition());
                        }
                    });

                    $('#myLocalisation').on('click', function() {
                        calculateAndDisplayRouteGeolocation(marker.getPosition());
                    });
                }
            }
        }

    });
}

function calculateAndDisplayRouteGeolocation(destination)
{
    navigator.geolocation.getCurrentPosition(
        // success
        function(position) {
            const myPosition = new google.maps.LatLng(position.coords.latitude, position.coords.longitude); 
            calculateAndDisplayRoute(myPosition, destination);
        }, 
        // error
        function() {
            alert("Erreur autorisation : Veuillez verifier que vous autorisez la geolocalisation sur votre navigateur.")
        }
    );
}

function calculateAndDisplayRoute(origin, destination) {
    $('#itineraryCalcul').addClass('d-none');

    const directionsRenderer = new google.maps.DirectionsRenderer();
    const directionsService = new google.maps.DirectionsService();
    directionsRenderer.setMap(map);

    const transportMode = $('#transportSelect').val();
    directionsService.route({
        origin: origin,
        destination: destination,
        travelMode: google.maps.TravelMode[transportMode],
        unitSystem: google.maps.UnitSystem.METRIC,
    }, function(response, status) {
        if (status == 'OK') {
            //directionsDisplay.setOptions({preserveViewport: true});  
            directionsRenderer.setDirections(response);
            const distance = response.routes[0].legs[0].distance.text;
            const duration = response.routes[0].legs[0].duration.text;
            $('#itineraryCalcul').text('La distance du trajet est de ' + distance + ' et sa durée est estimée à ' + duration).removeClass('d-none');
        } else {
            window.alert("Impossible d'afficher l'itinéraire car " + status);
        }
    });
}


function createStreetViewControl(map, marker, callback) {

    panorama = map.getStreetView();
    panorama.setPosition(marker.getPosition());
    panorama.setPov({
        heading: 0,
        pitch: 0,
    });

    let controlButton = document.createElement("button");

    // Set CSS for the control.
    controlButton.id = "streetview_toggle_button";
    controlButton.style.backgroundColor = "#fff";
    controlButton.style.border = "2px solid #fff";
    controlButton.style.borderRadius = "3px";
    controlButton.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
    controlButton.style.color = "rgb(25,25,25)";
    controlButton.style.cursor = "pointer";
    controlButton.style.fontFamily = "Roboto,Arial,sans-serif";
    controlButton.style.fontSize = "20px";
    controlButton.style.lineHeight = "38px";
    controlButton.style.margin = "10px";
    controlButton.style.padding = "0 8px";
    controlButton.style.textAlign = "center";
    controlButton.title = "Basculer en mode Street View directement à l'adresse";
    controlButton.type = "button";
    $(controlButton).attr('target', "streetview");
    $(controlButton).html('<?php echo fontawesome('street-view');?>');

    // Create the DIV to hold the control.
    const controlDiv = document.createElement("div");
    // Append the control to the DIV.
    controlDiv.appendChild(controlButton);

    map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);

    callback(controlButton);
}

function toggleStreetView() {
  const toggle = panorama.getVisible();

  if (toggle == false) {
    panorama.setVisible(true);
  } else {
    panorama.setVisible(false);
  }
}

function markersGet(map_id, callback)
{
    const url = $(map_id).attr('url');
    $.get(url, function(result) {
        result = JSON.parse(result);
        if(result.locations && result.locations.length>0) {
            let marker_i = 0;
            for(let location of result.locations) {
                const marker = new google.maps.Marker({
                    position: new google.maps.LatLng(location.lat, location.lon),
                    map: map,
                    zoom: location.zoom,
                });

                const infowindow = new google.maps.InfoWindow({
                    content: location.marker_html,
                });

                marker.addListener("click", () => {
                    infowindow.open(marker.get("map"), marker);
                });

                markers[marker_i] = marker;
                marker_i++;
            }
        } else {

        }

        callback(result);
    });
}

window.initMap = initMap;

</script>

<script
    src='https://maps.googleapis.com/maps/api/js?key=AIzaSyAfIlffztCfgwK1KK511E90X58Bp8db9kk&callback=initMap&libraries=geometry&language=fr';
></script>
