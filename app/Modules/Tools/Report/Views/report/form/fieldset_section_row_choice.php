<div class="row">
    <div class="col-6 text-center border-right">
        <button type="button" class="btn btn-sm btn-secondary mb-4" onclick="js_set_section_version_modal('old', <?php echo $id_section;?>);"> Garder la version initiale </button>
        <div> <?php echo $html_content_old;?> </div>
    </div>
    <div class="col-6 text-center">
        <button type="button" class="btn btn-sm btn-secondary mb-4" onclick="js_set_section_version_modal('new', <?php echo $id_section;?>);"> Changer pour la version actuelle </button>
        <div> <?php echo $html_content_new;?> </div>
    </div>
</div>

