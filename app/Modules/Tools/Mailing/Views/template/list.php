<?php $this->extend('Layout\index'); ?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Modèle d'emails
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<div class="wrapper">
    <table id="templatesTable" class="table datatable table-striped table-bordered w-100">
        <thead>
            <tr>
                <th scope="col"> </th>
                <th scope="col"> Date de mise à jour </th>
                <th scope="col"> Référence </th>
                <th scope="col"> Label </th>
                <th scope="col" class="border-left text-center">  </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($templates as $tp) :?>
                <tr>
                    <td class="text-center">
                        <button type="button" class="btn text-danger py-0" onclick="template_delete_modal(this, <?php echo $tp->id_template;?>);">
                            <?php echo $themes->delete->icon;?>
                        </button>
                    </td>
                    <td> <?php if(isset($tp->update_datehtml)) echo $tp->update_datehtml;?> </td>
                    <td> <?php if(isset($tp->ref)) echo $tp->ref;?> </td>
                    <td> <?php if(isset($tp->label)) echo $tp->label;?></td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center">
                            <a role="button" class="btn text-dark py-0" title="Afficher ou modifier le modèle d'email"
                                href="<?php echo base_url('mailing/template/view/' . $tp->id_template);?>"
                                > 
                                <?php echo $themes->info->icon;?> 
                            </a>
                            <!-- <button type="button" class="btn py-0" title="Envoi d'un email test"
                                onclick="send_test_modal(this, <?php echo $tp->id_template;?>)"
                                > 
                                <?php echo fontawesome('paper-plane-test');?> 
                            </button> -->
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script type="text/javascript">
    $(document).ready(function() {
        $('#templatesTable').DataTable({
            "pageLength": 100,
            dom: "<'row justify-content-between align-items-center'<'col-2 mt-3'l><'col-4'p><'col-2 mb-3'i><'col-3 mt-3'f>>",
            "infoCallback": function( settings, start, end, max, total, pre ) {
                return start + " à " + end + " sur " + total;
            },
            "scrollX" : true,
            "order" : [[ 1, 'desc' ]],
            "language": {
                "url": "<?php echo base_url('public/datatables/languages/fr_FR.json');?>"
            },
            stateSave: true,
        });
        $.fn.DataTable.ext.pager.numbers_length = 5;
    });
    </script>
</div>

<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Mailing\js/js_mailing');?>
<?php $this->endSection(); ?>

