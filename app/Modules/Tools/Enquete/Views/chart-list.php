<?php $this->extend('Layout\index');?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Module Enquêtes - <?php echo $target->title;?>
<?php $this->endSection(); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom">
        <div> Enquêtes - <?php echo $target->title;?> </div>
        <div class="d-flex"> <?php echo view('Enquete\navigation');?> </div>
    </div>
<?php $this->endSection(); ?>

<!-- INJECTED SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <script src="<?php echo base_url('node_modules/jspdf/dist/jspdf.umd.min.js'); ?>"></script>
    <?php echo view('Enquete\js/js_chart');?>
    <?php echo view('Enquete\js/js_enquete');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<?php echo view('Enquete\filter-alert');?>

<div class="mb-4">
    <div class="row">
        <div class="col-auto">
            <button class="form-control w-auto" data-bs-toggle="collapse" data-bs-target="#filterCharts" role="button" aria-expanded="false">
                - Sélectionner les graphiques -
            </button>
        </div>
        <div class="col-auto">
            <a role="button"
                href="javascript:js_download_canvas_summary('chart')"
                id="downloadCharts"
                class="btn"
                title="Télécharger l'ensemble des graphiques sélectionnés"
                >
                <?php echo fontawesome('download');?>
            </a>
        </div>
    </div>
    <div class="row collapse collapse-click-outside filter-collapse" id="filterCharts">
        <div class="col-auto">
            <div class="card card-body check-all-group position-absolute" style="z-index: 999;">
                <div class="overflow-y-auto" style="max-height: 200px">
                    <div class="form-check border-bottom mb-2">
                        <input type="checkbox"
                            class="form-check-input check-all-input"
                        />
                        <label class="form-check-label"> Tout sélectionner </label>
                    </div>
                    <?php foreach($questions as $q):?>
                        <div class="form-check position-relative">
                            <input type="checkbox"
                                class="form-check-input filter-input check-all-target"
                                value="<?php echo $q->id_question;?>"
                                <?php if(session('filter_chart') && in_array($q->id_question, session('filter_chart'))):?>
                                    checked
                                <?php endif;?>
                                />
                            <label class="form-check-label"> <?php echo $q->num_question;?> - <?php echo $q->question_fr;?> </label>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</div>
    
<div id="charts" class="row"> 
    <script>    
        let canvas = new Array();
    </script>

    <?php foreach($questions as $q):?>
        <div class="col-4 chart-container mb-3" 
            id="container-<?php echo $q->id_question;?>"
            <?php if(session('filter_chart') && !in_array($q->id_question, session('filter_chart'))):?>
                hidden
            <?php endif;?>
            >
            <div class="card card-body h-100">
                <button type="button" class="btn btn-sm" title="Agrandir le graphique"
                    id_question=<?php echo $q->id_question;?> 
                    onclick="js_show_canvas_modal(this, 'chart');"
                    > 
                    <?php echo fontawesome('search');?>
                </button>
                <canvas 
                    class="canvas" 
                    id="chart-<?php echo $q->id_question;?>" 
                    id_question=<?php echo $q->id_question?> 
                    style="max-height: 200px; display: none;"
                    >
                </canvas>                           
            </div>
        </div>     
    <?php endforeach;?>
</div>

<?php $this->endSection(); ?>


