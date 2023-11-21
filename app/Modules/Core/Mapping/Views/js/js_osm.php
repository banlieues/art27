<script>

// --------------------------------
// OPENSTREETMAP.JS
// --------------------------------

let map;
let mapBounds = new L.LatLngBounds();
let layerControl;
let layerGroup = {};
let layerGroupTitle = {};
let markersInside = [];
// let markerIcon;
let markerIds = {};
let markerInside;
let baseLayerGroup = [];

const mapConfig = {
    center_lat : 50.855,
    center_lon : 4.375,
    zoom : 10,
}

function base_layer_config(ref) {
    const base_layers = {
        osm : {
            attribution : '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            title : 'OpenStreetMap',
            uri : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        },
        satellite : {
            attribution : '&copy; <a href="https://www.mapbox.com/copyright/" target="_blank">MapBox</a>',
            title : 'Satellite',
            uri : 'https://api.mapbox.com/v4/mapbox.satellite/{z}/{x}/{y}{@2x}.jpg90?access_token=pk.eyJ1IjoidGFtaGF1IiwiYSI6ImNsaW5qdXd1OTAwMG0zanJ4d2Q1MWt2eXoifQ.YMGv1aOypxSlNHgJsh9bsw',
        },
    };

    return base_layers[ref];

};

function osm_search(button)
{
    waiting_start(button);

    const formdata = new FormData( $('form#mapSearchForm')[0] );
    const resultContainer = $('#resultContainer');
    // const mapPane = $('#map-tab-pane');
    const mapContainer = $('#mapContainer', resultContainer);
    const gasapResultsTitle = $('#gasap-results-title', resultContainer);
    const gasapResultsContainer = $('#gasap-results', resultContainer);
    const farmerResultsTitle = $('#farmer-results-title', resultContainer);
    const farmerResultsContainer = $('#farmer-results', resultContainer);

    spinner_mask(mapContainer, "Connexion à Open Street Map");
    spinner_mask(gasapResultsContainer, "Connexion à Open Street Map");
    spinner_mask(farmerResultsContainer, "Connexion à Open Street Map");

    $(gasapResultsTitle, farmerResultsTitle).removeClass('d-flex').hide();

    $.ajax({
        url: "<?php echo base_url('mapping/territory/validation');?>",
        data: formdata,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function(data) {

            data = JSON.parse(data);

            if(data.error) {
                $('#flashContainer').html(data.error);
                $('#resultContainer').fadeOut();
                waiting_end(button);
            } else {

                $('#flashContainer').html('');
                $(resultContainer).fadeOut(function() { 
                    $(resultContainer).attr('hidden', false); 
                    $('[data-bs-target="#map-tab-pane"]').click();
                    $(resultContainer).fadeIn(function() {
                        osm_view(mapContainer, function(result) {
                            if(result.circle && result.circle.lat && result.circle.lon) {
                                if(result.nb_gasaps>0) {  
                                    $('.gasap-nb', resultContainer).html("La recherche a trouvé <strong>" + result.nb_gasaps + "</strong> groupes.");
                                    $(gasapResultsContainer).html(result.gasap_view);
                                    $(gasapResultsTitle).show().addClass('d-flex');
                                } else {
                                    $(gasapResultsContainer).html('Pas de groupes trouvés dans ce périmètre.');
                                }
                                
                                if(result.nb_farmers>0) {
                                    $('.farmer-nb', resultContainer).html("La recherche a trouvé <strong>" + result.nb_farmers + "</strong> producteurs.");
                                    $(farmerResultsContainer).html(result.farmer_view);
                                    $(farmerResultsTitle).show().addClass('d-flex');
                                } else {
                                    $(farmerResultsContainer).html('Pas de producteurs trouvés dans ce périmètre.');
                                }
                            } else {
                                $(mapContainer).html('<div class="text-center m-5"> Pas d\'adresse trouvée pour le point central... </div>');
                                $(gasapResultsContainer).html('');
                                $(farmerResultsContainer).html('');
                            }

                        });
                        waiting_end(button);
                    });
                });
            }
        },
    });
}

$(document).ready(function() 
{
    $('.osm_container:visible').each(function() {
        osm_view(this);    
    });
    
    $('.osm_container.collapse').each(function() 
    {
        const container_id = $(this).attr('id');
        const button = $('.osm-collapse-button[data-bs-target="#' + container_id + '"]');
        $('.osm_container').on('hidden.bs.collapse', function() {
            $(button).attr('title', 'Montrer la carte');
        });
        $('.osm_container').on('shown.bs.collapse', function() {
            $(button).attr('title', 'Masquer la carte');
            osm_view(this);
        });
    });
});


function osm_view(container, callback=null)
{
    const form_id = $(container).attr('form');
    const formdata = new FormData($('#' + form_id)[0]);

    $(container).css('z-index', 5);
    spinner_mask(container, "Connexion à Open Street Map");

    $.ajax({
        url : $(container).attr('url'),
        data: formdata,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function(result) {
            result = JSON.parse(result);
            map_set_markers(result, function() {
                map_init(container, function() {
                    if(Object.keys(layerGroup).length>0) map_set_layers(result);
                    else map_set_layer_undefined();

                    if(callback) callback(result);
                });
            });
        }, 
    });
}

function map_set_markers(result, callback)
{
    for(const location of result.locations) {
        if(typeof location.layers=='undefined' || location.layers.length==0) { 
            location.layers = ['']; 
        }
        for(const layer of location.layers) {
            let layer_ref = layer;
            if(location.ref) layer_ref = location.ref + '_' + layer;
            if(location.lat && location.lon) {
                if(typeof layerGroup[layer_ref]=='undefined') {
                    layerGroup[layer_ref] = L.featureGroup();
                    layerGroupTitle[layer_ref] = location.ref + ' ' + layer + ' <span style="color:' + location.color + '">' + location.icon + '</span>';
                }
                if(typeof location.icon == 'undefined') {
                    location.icon = '<?php echo fontawesome('location-dot');?>';
                    location.anchor = [0, 20];
                }
                if(typeof location.color == 'undefined') {
                    location.color = 'body';
                }
                if(typeof location.anchor == 'undefined') {
                    location.anchor = [0, 0];
                }

                let marker = L.marker(
                    new L.LatLng(location.lat, location.lon), 
                    { 
                        id_entity: location.id_entity,
                        entity: location.entity,
                        icon: L.divIcon({
                            html: '<div class="fa-stack fa-2x" style="color:' + location.color + '">' + location.icon + '</div>',
                            className: 'border-0 bg-transparent',
                            iconAnchor: location.anchor,
                        }),
                    }
                );
                if(location.marker_html) marker.bindPopup(location.marker_html);
                marker.addTo(layerGroup[layer_ref]);
            }
        }
    }

    callback();
}

function map_init(container, callback)
{
    const container_id = $(container).attr('id');

    if(map) map.remove();
    map = L.map(container_id, {
        center: L.latLng(mapConfig.center_lat, mapConfig.center_lon),
        zoom: mapConfig.zoom, 
        // maxZoom: 20, 
        layers: base_layer_get('osm'),        
        // layers: L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        //     zoomControl : false,
        //     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        // }),
        // zoomAnimation: false,
    });

    map.touchZoom.disable();
    map.doubleClickZoom.disable();
    map.scrollWheelZoom.disable();
    map.boxZoom.disable();
    map.keyboard.disable();

    callback();
}

function base_layer_get(layer_ref)
{
    const layer_config = base_layer_config(layer_ref);
    const base_layer = L.tileLayer(layer_config.uri, {
        attribution : layer_config.attribution || null,
        crossOrigin : true,
        zoomControl : false,
        minZoom : 1,
    });

    return base_layer;
}

function map_set_layers(result)
{
    for(const layer_ref in layerGroup) {
        layerGroup[layer_ref].addTo(map);
        //Fit bounds
        mapBounds.extend(layerGroup[layer_ref].getBounds());

        // if(layer.isClusterGroup) mapBounds.extend(layerGroup.getBounds());
        // else map.fitBounds(layerGroup.getBounds());

        const defaultBaseLayer = {'OpenStreetMap' : base_layer_get('osm')};
        if(!layerControl) layerControl = L.control.layers(defaultBaseLayer, null, { 
            sortLayers: true, 
            collapsed: false,
        }).addTo(map);

        // BaseLayer
        if(result.base_layers) {
            for(const layer_ref of result.base_layers) {
                layerControl.addBaseLayer(base_layer_get(layer_ref), base_layer_config(layer_ref).title);
            }
        } else {
            layerControl.remove();
        }

        // Overlays
        if(Object.keys(layerGroup).length>1) {
            layerControl.addOverlay(layerGroup[layer_ref], layerGroupTitle[layer_ref]);
        } else {
            const geocodes = layerGroup[layer_ref].getLayers()[0].getLatLng();
            map.setView([geocodes.lat, geocodes.lng], result.zoom);
        }
    }

    if(Object.keys(layerGroup).length>1) if(mapBounds.isValid()) map.fitBounds(mapBounds);

    if(result.circle && result.circle.radius && result.circle.lat && result.circle.lon) {

        const mapcenter = new L.LatLng(result.circle.lat, result.circle.lon);
        const bounds = mapcenter.toBounds(result.circle.radius + 1000);
        map.fitBounds(bounds);
        L.circle([result.circle.lat, result.circle.lon], {radius: result.circle.radius}).addTo(map);
    }
}

function map_set_layer_undefined()
{
    const undefinedIcon = L.divIcon({
        html: '<div class="fa-stack fa-3x text-dark"><?php echo fontawesome('question');?></div>',
        className: 'border-0 bg-transparent',
    });
    let marker = L.marker(
        map.getCenter(),
        { 
            title: "Adresse non trouvée",
            icon: undefinedIcon,
        }
    );
    marker.addTo(map);

}

</script>
