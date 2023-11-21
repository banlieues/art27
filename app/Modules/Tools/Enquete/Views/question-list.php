<?php $this->extend('Layout\index');?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Module Enquête - Questions
<?php $this->endSection(); ?>

<!-- NAVBARSUB -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->enquete->color;?>">
        <div>
            <?php echo $titleView;?>
            (<strong> <?php echo $nb_questions;?> </strong>)
            <?php if(!empty($itemSearch)):?>
                <div class="d-inline small" title="Filtre : <?php echo $itemSearch;?>">
                    <?php echo fontawesome('filter-warning');?>
                </div>
            <?php endif;?>
        </div>
        <div> <?php echo view('DataView\buttons/pagination');?> </div>
        <div> <?php echo view('DataView\buttons/search', ['color' => $themes->enquete->color,]);?> </div>
        <div class="d-flex">
            <?php //echo view('DataView\buttons/export_csv', [
            //     'module' => 'Enquete',
            //     'model' => 'EnqueteModel',
            //     'method' => 'QuestionsGet',
            //     'filename' => 'liste_questions',
            // ]);?>
            <?php if($Autorisation->is_autorise('enquete_form_c')):?>
                <button type="button"
                    class="btn btn-sm btn-<?php echo $themes->enquete->color;?> ms-1"
                    onclick="question_new_modal(this);"
                    title="Ajouter une nouvelle question"
                    > 
                    <?php echo fontawesome('plus');?>
                    Question
                </button>
            <?php endif;?>
        </div>
    </div>
<?php $this->endSection(); ?>

<!-- INJECTED SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Enquete\js/js_enquete');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<div class="banData">

    <?php echo $this->include('Enquete\question-list-table');?>

</div> 

<?php $this->endSection(); ?>
