<?php $this->extend('Layout\index'); ?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Enquêtes - <?php echo $target->title;?>
<?php $this->endSection(); ?>

<!-- NAVBARSUB -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->enquete->color;?>">
        <div>
            Enquêtes - <?php echo $target->title;?>
            (<strong> <?php echo $nb_answers;?> </strong>)
        </div>
        <div> <?php echo view('DataView\buttons/pagination');?> </div>
        <div class="d-none"> <?php echo view('DataView\buttons/search', ['color' => $themes->enquete->color,]);?> </div>
        <div class="d-flex"> <?php echo view('Enquete\navigation');?> </div>
        <div class="d-flex">
            <?php echo view('DataView\buttons/export_csv', [
                'module' => 'Enquete',
                'model' => 'AnswerModel',
                'method' => 'AnswersGet',
                'filename' => 'liste_enquetes',
            ]);?>
        </div>
    </div>
<?php $this->endSection(); ?>

<!-- INJECTED SCRIPT FOOT -->
<?php $this->section('script_foot_injected');?>
    <?php echo view('Enquete\js/js_enquete');?>
<?php $this->endSection();?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section("body"); ?>

<?php echo view('Enquete\filter-alert');?>

<div class="banData">

    <?php echo $this->include('Enquete\answer-list-table');?>

</div>    

<?php $this->endSection(); ?>

