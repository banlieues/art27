<div class="card card-googlemaps flex-fill mb-2">
    <div class="loading text-center my-5" style="width: 100%;">
        <i class="fas fa-circle-notch fa-spin"></i> <br>
        Chargement de la carte
    </div>
    <div class="card-body p-0" style="display: none;">
        <div 
            id="google_map"
            class="mb-2" 
            style="width: 100%; height: 400px;"
            url="<?php echo base_url("mapping/data/$entity_ref/$id");?>"
            >
        </div>
        <div id="itineraryCalcul" class="d-none mx-2 mb-2 alert alert-sm alert-primary p-2"></div>
        <div id="itineraryForm" class="d-none mx-0 mb-2 justify-content-between align-items-center">
            <div class="col-auto">
                <select class="form-select form-select-sm"
                    id="transportSelect" 
                    onchange="$('#itinerarySubmit, #myLocalisation').attr('disabled', false);"
                    >
                    <option disabled selected> - Mode de transport - </option>
                    <option value="TRANSIT">Transport en commun</option>
                    <option value="DRIVING">Voiture</option>
                    <option value="WALKING">Marche</option>
                    <option value="BICYCLING">Vélo</option>
                </select>
            </div>
            <div class="col">
                <div class="input-group input-group-sm">
                    <input type="text" 
                        class="form-control"
                        id="itineraryInput"
                        placeholder="Adresse de départ"
                        list="startPlaces"
                        style="min-width: 100px;"
                    />
                    <datalist id="startPlaces">
                        <option value="Homegrade"/>
                    </datalist>
                    <button type="button" 
                        id="itinerarySubmit" 
                        class="btn btn-sm btn-primary" 
                        title="Calcul de l'itinéraire depuis l'adresse encodée" 
                        disabled
                        > 
                        <?php echo fontawesome('route');?>
                    </button>
                </div>
            </div>
            <div class="col-auto geolocation-col" style="display: none;">
                OU
            </div>
            <div class="col-auto geolocation-col" style="display: none;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width: 100px;">
                        Ma localisation
                    </span>
                    <button type="button" 
                        id="myLocalisation" 
                        class="btn btn-sm btn-primary" 
                        title="Calcul de l'itinéraire depuis ma localisation" 
                        disabled
                        > 
                        <?php echo fontawesome('location-crosshairs');?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>