<?php $this->extend('Layout\index'); ?>

<!-- NAVBARSUB -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom">
        <div> <?php echo $titleView;?> </div>
    </div>
<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Mail\js/js_mail');?>
    <?php echo view('Mail\js/js_example');?>
    <?php echo view('Components\js/summernote');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<?php $this->include('\Mail\mail/form');?>

<div class="row" id="mailMainContainerExample">
    <div class="col-sm-10" id="mailFormContainerExample"> </div>
    <div class="col-sm-2">
        <div id="submitButtons" class="form-nav text-center d-flex flex-column">
            <div  id="sendButton" class="d-flex"></div>
            <div  id="saveButton" class="d-flex"></div>
            <!-- <div class="mb-1" id="mailSendContainer"></div>
            <div class="mb-1" id="mailSaveContainer"></div>
            <div class="mb-1" id="mailOutput"></div>
            <div class="mb-1" id="mailGotolist"></div> -->
            
        </div>
    </div>
</div>
<!-- <div class="row my-5 justify-content-center" id="mailOutputContainerExample">
</div> -->

<script text="javascript">

    $(document).ready(function() 
    {
        const formContainerExample = $('#mailFormContainerExample');
        // const sendContainer = $('#mailSendContainer');
        // const saveContainer = $('#mailSaveContainer');

        $.get("<?php echo base_url('mail/example/' . $type);?>", function(result) {
            result = JSON.parse(result);
            if(result.error) {
                $('.modal-dialog','#modal').addClass('modal-xl');
                $('.modal-title','#modal').html('Erreur au niveau des paramètres d\'entrées');
                $('.modal-body','#modal').html(result.error);
                $('#modal').modal('show');
            } else {
                $(formContainerExample).html(result.form);
                $('#submitButtons').html(result.button_send + result.button_save); 
                $('#mailSend, #mailSave').addClass('w-100 mb-1').css({'width': '100%'});
                $('#mailEmodel').on('change', function() {
                    mail_module_emodel_init(this, result.emodels);
                }); // important
                mail_module_view_init(); // important 
            }        
        });
    });
</script>

<?php $this->endSection(); ?>