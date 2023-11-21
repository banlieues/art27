<?php $this->extend('Layout\index'); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->mailing->color;?>">
        <div>
            <?php echo $titleView;?>
        </div>
        <div class="d-flex">
            <a class="btn btn-dark btn-sm ms-1" 
                href="<?php echo base_url('mailing/templates?' . $get);?>"
                title="Aller à la liste des modèles"
                onclick="waiting_start(this);"
                >
                <?php echo $themes->goto->icon;?>
                Retour à la liste des modèles
            </a>
            <button id="save-form-button" type="submit" class="btn btn-success btn-sm ms-1"
                form="templateUpdateForm"
                onclick="waiting_start(this);"
                title="Enregistrer les modifications"
                >
                <?php echo $themes->save->icon;?> 
            </button>
            <a id="cancel-form-button" class="btn btn-outline-secondary btn-sm ms-1" 
                href="<?php echo current_url();?>"
                onclick="waiting_start(this);"
                title="Annuler les modifications"
                >
                <?php echo $themes->cancel->icon;?> 
            </a>
            <button type="button" class="btn btn-sm btn-primary ms-1"
                onclick="send_test_modal(this, <?php echo $id_template;?>)"
                > 
                <?php echo $themes->mailing->icon;?> Envoi d'un email test 
            </button>
        </div>
    </div>
<?php $this->endSection(); ?>

<!-- INJECTED SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php //echo view('Mailing\js/js_bootstrap');?>
    <?php echo view('Components\js/summernote');?>
    <?php echo view('Mailing\js/js_mailing');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<form id="templateUpdateForm" method="post" action="<?php echo base_url('mailing/template/view/' . $id_template);?>">
    <div class="row">
        <div class="col-10">
            <div class="card mb-4">
                <div id="templateManagement" class="card-header"> Gestion </div>
                <div class="card-body">
                    <?php echo $controls->ref;?>
                    <?php echo $controls->label;?>
                    <?php echo $controls->is_activated;?>
                    <?php echo $controls->subject_ref;?>
                </div>
            </div>
            <div class="card mb-4">
                <div id="templateFR" class="card-header"> Français </div>
                <div class="card-body">
                    <?php echo $controls->subject_fr;?>
                    <?php echo $controls->hello_fr;?>
                    <?php echo $controls->content_fr;?>
                    <?php echo $controls->greetings_fr;?>
                </div>
            </div>
            <div class="card mb-4">
                <div id="templateNL" class="card-header"> Néerlandais </div>
                <div class="card-body">
                    <?php echo $controls->subject_nl;?>
                    <?php echo $controls->hello_nl;?>
                    <?php echo $controls->content_nl;?>
                    <?php echo $controls->greetings_nl;?>
                </div>
            </div>
            <!-- <div class="card mb-4">
                <div class="card-header"> Anglais </div>
                <?php // echo $controls->subject_en;?>
                <?php // echo $controls->hello_en;?>
                <?php // echo $controls->content_en;?>
                <?php // echo $controls->greetings_en;?>
            </div> -->
        </div>
        <div class="col-2">
            <div class="sticky_button">
                <div class="bg-light border rounded p-2 mb-2">
                    <div class="my-2">
                        <a class="text-body" href="#templateManagement"> <small> <?php echo t("Gestion", $namespace);?> </small> </a>
                    </div>
                    <div class="my-2">
                        <a class="text-body" href="#templateFR"> <small> <?php echo t("Français", $namespace);?> </small> </a>
                    </div>
                    <div class="mb-2">
                        <a class="text-body" href="#templateNL"> <small> <?php echo t("Néerlandais", $namespace);?> </small> </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php $this->endSection(); ?>
