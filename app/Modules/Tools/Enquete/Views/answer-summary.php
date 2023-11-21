<?php $this->extend('Layout\index'); ?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Enquêtes - <?php echo $target->title;?>
<?php $this->endSection(); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->enquete->color;?>">
        <div>
            Enquêtes - <?php echo $target->title;?>
            (<strong> <?php echo $nb_answers;?> </strong>)
        </div>
        <div>
            <?php echo view('DataView\buttons/pagination');?>
        </div>
        <div hidden>
            <?php echo view('DataView\buttons/search', ['color' => $themes->enquete->color,]);?>
        </div>
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

<div class="mb-2">
    <button class="form-control w-auto" data-bs-toggle="collapse" data-bs-target="#filterColumns" role="button" aria-expanded="false">
        - Masquer/Afficher les colonnes -
    </button>
    <div class="collapse collapse-click-outside" id="filterColumns">
        <div class="col-auto">
            <div class="card card-body check-all-group position-absolute" style="z-index: 999;">
                <div class="overflow-y-auto" style="max-height: 200px">
                    <div class="form-check border-bottom mb-2">
                        <input type="checkbox"
                            class="form-check-input check-all-input"
                        />
                        <label class="form-check-label"> Tout sélectionner </label>
                    </div>
                    <?php foreach($columns as $key=>$column):?>
                        <div class="form-check position-relative">
                            <input type="checkbox"
                                class="form-check-input filter-input check-all-target" 
                                data-column="<?php echo $key;?>"
                            />
                            <label class="form-check-label">
                                <?php echo $column[0];?>
                                <?php foreach($questions as $question):?>
                                    <?php if($key==$question->name_question):?>
                                        - <?php echo $question->question_fr;?>
                                        <?php break;?>
                                    <?php endif;?>
                                <?php endforeach;?>
                            </label>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="banData">

    <?php echo $this->include('Enquete\answer-summary-table');?>

</div>    

<?php $this->endSection(); ?>

