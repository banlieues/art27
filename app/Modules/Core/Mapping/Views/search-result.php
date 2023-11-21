<div id="resultContainer" class="w-100 my-2" hidden>

    <hr>

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <button class="nav-link active text-body" type="button" 
                data-bs-toggle="tab" data-bs-target="#map-tab-pane" role="tab" aria-selected="true"
                >
                <b> CARTE </b>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link text-body" type="button" 
                data-bs-toggle="tab" data-bs-target="#gasap-tab-pane" role="tab"
                >
                <b> GASAPS </b>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link text-body" type="button" 
                data-bs-toggle="tab" data-bs-target="#farmer-tab-pane" role="tab"
                >
                <b> PRODS </b>
            </button>
        </li>
    </ul>

    <div class="tab-content border border-top-0">
        <div class="tab-pane fade show active p-4" id="map-tab-pane" role="tabpanel" tabindex="0"> 
            <div id="mapContainer" 
                class="osm_container" 
                style="height: 400px;" 
                url="<?php echo base_url('mapping/data/get');?>"
                form="mapSearchForm"
                > 
            </div>
        </div>
        <div class="tab-pane fade p-4" id="gasap-tab-pane" role="tabpanel" tabindex="0"> 
            <div id="gasap-results-title" class="justify-content-between align-items-center mb-2" hidden>
                <div class="gasap-nb"></div>
                <button type="button" class="btn btn-sm btn-dark ms-2" 
                    onclick="table_export_csv('gasap-results', 'mapsearch_groupes');"
                    title="Exporter en CSV"
                    >
                    <?php echo fontawesome('file-download');?>
                </button>
            </div>
            <div id="gasap-results">
            </div>
        </div>
        <div class="tab-pane fade p-4" id="farmer-tab-pane" role="tabpanel" tabindex="0"> 
            <div id="farmer-results-title" class="justify-content-between align-items-center mb-2" hidden>
                <div class="farmer-nb"></div>
                <button type="button" class="btn btn-sm btn-dark ms-2" 
                    onclick="table_export_csv('farmer-results', 'mapsearch_producteurs');"
                    title="Exporter en CSV"
                    >
                    <?php echo fontawesome('file-download');?>
                </button>
            </div>
            <div id="farmer-results">
            </div>
        </div>
    </div>

</div>