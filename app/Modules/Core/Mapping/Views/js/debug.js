
// --------------------------------
// OPENSTREETMAP.JS
// --------------------------------

// let locations;
let map;
let markers;
let markerIcon;

function map_get_geocodes(url, callback) {
    $.get(window.location.origin + '/' + url, (result) => {
        callback(JSON.parse(result));
    });
}

$(document).ready(function() {
    $('.osm_container').each(function() {

        map_view(this);

        const container_id = $(this).attr('id');
        const button = $('.osm-collapse-button[data-bs-target="#' + container_id + '"]');
        $('.osm_container').on('hidden.bs.collapse', function() {
            $(button).text('Montrer la carte');
        });
        $('.osm_container').on('shown.bs.collapse', function() {
            $(button).text('Masquer la carte');
        });
    });
});


function map_view(container)
{
    const container_id = $(container).attr('id');
    const url = $(container).attr('url');
    const latDefault = 50.855;
    const lonDefault = 4.375;
    $(container).css('z-index', 5);

    map_get_geocodes(url, (result) => {

        const layers = result.layers;

        map_init(container_id);

        for(const layer of layers) {

            layer_init(layer.color);
            markers_init(layer.color);
            markers_set(layer.locations);
            markers_view(layer.locations);
            layer_control(layer.ref);
        }

        map_customize();
    });    
}


function map_init(container_id)
{
    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        zoomControl: false,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

    
    const latLon = L.latLng(50.855, 4.375);
    
    map = L.map(container_id, {
        center: latLon,
        zoom: 10, 
        layers: [osm]
        zoomAnimation: false,
    });
}

function layer_init(color)
{
    markerIcon = L.divIcon({
        html: '<div class="fa-stack fa-3x text-' + color + '"><?php echo fontawesome('map-marker-alt');?></div>',
        className: 'border-0 bg-transparent',
    });
}

function markers_init(color)
{
    markers = L.markerClusterGroup({
        // maxClusterRadius: 20,
        iconCreateFunction: function (cluster) {
            var childCount = cluster.getChildCount();
            return new L.DivIcon({ 
                html: '<div><span><b>' + childCount + '</b></span></div>', 
                className: 'marker-cluster opacity-75 bg-' + color, 
                iconSize: new L.Point(40, 40) 
            });
        },
    });
}

function markers_set(locations)
{
    for(const location of locations) {
        if(location.lat && location.lon) {
            let marker = L.marker(
                new L.LatLng(location.lat, location.lon), 
                { 
                    title: location.gasap_label,
                    icon: markerIcon,
                }
            );
            marker.bindPopup('<a href="' + window.location.origin + '/'+location.entity+'/view'+location.entity+'/' + location.id_entity + '">' + location.nom_endroit + '</a><br>' + location.adresse_endroit + ' ' + location.localite);
            // map.addLayer(marker);
            markers.addLayer(marker);
        }
    }

    map.addLayer(markers);
}

function markers_view(locations)
{
    if(locations.length==1) { 
        if(locations[0].lat && locations[0].lon) {
            map.setView(new L.latLng(locations[0].lat, locations[0].lon), 10);
        } else {
            const undefinedIcon = L.divIcon({
                html: '<div class="fa-stack fa-3x"><?php echo fontawesome('question');?></div>',
                className: 'border-0 bg-transparent',
            });
            let marker = L.marker(
                map.getCenter(),
                { 
                    title: "Adresse non trouvée",
                    icon: undefinedIcon,
                }
            );
            markers.addLayer(marker);
        }
    } 
    else { map.fitBounds(markers.getBounds()); }
}

function layer_control(ref)
{
    const overlayMaps = {
        ref: markers
    };
    // const layerControl = L.control.layers(overlayMaps).addTo(map);
}

function map_customize()
{
    map.touchZoom.disable();
    map.doubleClickZoom.disable();
    map.scrollWheelZoom.disable();
    map.boxZoom.disable();
    map.keyboard.disable();
}

// function set_map(container, lat, lon, address)
// {
//     $(container).html('<div id="map" class="w-100 h-100"> </div>');
//     const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//         maxZoom: 18,
//         attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
//     }),
//     latLon = L.latLng(lat, lon);

//     map = L.map('map', {center: latLon, zoom: 12, layers: [tiles]});
//     let markers = L.markerClusterGroup();

//     let marker = L.marker(new L.LatLng(lat, lon), { title: address });
//     marker.bindPopup(address);
//     markers.addLayer(marker);
//     map.addLayer(markers);
// }

$(document).on('shown.bs.tab', '#eventSetupTabButton', function(){
    map.invalidateSize();
 });

