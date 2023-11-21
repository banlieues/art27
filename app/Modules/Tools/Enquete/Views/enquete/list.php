<?php $this->extend('Layout\tamo/index');?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Module Enquête - Formulaires
<?php $this->endSection(); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub_title'); ?>
    Formulaires
<?php $this->endSection(); ?>

<!-- INJECTED SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Enquete\js/js_enquete');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<div class="p-4">
    <table id="enqueteFormTable" class="table table-striped table-bordered">
        <thead> 
            <th class="text-right"> ID </th>
            <th> Nom </th>
            <th> Nb de questions </th>
        </thead>
        <tbody>
            <?php foreach($enquetes as $enquete):?>
                <tr role="button" onclick="js_get_enquete_details(<?php echo $enquete->id_enquete;?>)">
                    <td class="text-right"> <?php if(!empty($enquete->id_enquete)) echo $enquete->id_enquete;?> </td>
                    <td> <?php if(!empty($enquete->path_fr)) echo $enquete->path_fr;?> </td>
                    <td> <?php if(!empty($enquete->ids_question)) echo count($enquete->ids_question);?> </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<?php $this->endSection(); ?>
