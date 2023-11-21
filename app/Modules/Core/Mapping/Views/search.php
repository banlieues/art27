<?php $this->extend('Layout\index');?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Recherche géographique
<?php $this->endSection(); ?>

<!-- TITLE -->
<?php $this->section('subtitle'); ?>
    Recherche géographique
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body');?>

<form id="mapSearchForm" class="row align-items-end mt-4 mb-2">
    <!-- <div class="col-sm-3">
        <label class="col-form-label"> Type de lieux</label>
        <select class="form-select bootstrap-select" multiple name="location_type[]">
            <option value="gasap"> Gasaps</option>
            <option value="farmer"> Prods </option>
        </select>
    </div> -->
    <label class="col-sm-2 col-form-label"> Adresse du lieu central </label>
    <div class="col-sm-4">
        <input type="text" class="form-control" name="address" placeholder="Bruxelles"
            onkeydown="if(event.keyCode == 13) {$(this).closest('form').find('button[type=\'button\'][form=\'mapSearchForm\']').click();}"
        />
    </div>
    <label class="col-sm-2 col-form-label"> Distance (km)</label>
    <div class="col-sm-2">
        <input type="number" class="form-control" name="radius"
            onkeydown="if(event.keyCode == 13) {$(this).closest('form').find('button[type=\'button\'][form=\'mapSearchForm\']').click();}"
        />
    </div>
    <div class="col-sm-2">
        <button type="button" class="btn btn-primary w-100" 
            onclick="osm_search(this);"
            form="mapSearchForm"
            container_id="mapContainer"
            > 
            <?php echo fontawesome('search');?>
            Rechercher
        </button>
    </div>
    
</form>


<?php echo view('Mapping\search-result');?>

<?php $this->endSection();?>