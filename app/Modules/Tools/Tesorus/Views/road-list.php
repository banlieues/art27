<?php $this->extend('Layout\index'); ?>

<!-- NAVBARSUB -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->tesorus->color;?>">
        <div> <?php echo $titleView;?> </div>
    </div>
<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Tesorus\js/js_tesorus');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>        
        
<table class="table">
    <tbody>
        <?php foreach($roads as $road):?>
            <tr>
                <td> 
                    <?php echo $road->title;?> 
                </td>
                <td>
                    <?php foreach(['list', 'radio', 'checkbox', 'tag'] as $tesorus_type):?>
                        <?php echo $road->{'button_' . $tesorus_type};?>
                    <?php endforeach;?>
                </td>
                <td>
                    <a role="button" class="btn btn-sm" 
                        href="<?php echo base_url('tesorus/road/edit/' . $road->ref);?>"
                        title="Editer le thesaurus"
                        > 
                        <?php echo fontawesome('edit');?> 
                    </a>
                </td>
            </tr> 
        <?php endforeach;?>
    </tbody>
</table>



<!-- <ul>
    <?php foreach($roads as $road):?>
        <li>
            <b> <?php echo $road->title;?> </b> :
            <button type="button" class="btn" onclick="road_view_modal(this, '<?php echo $road->ref;?>', 'list', 'xl');"> 
                Liste
            </button>
            -
            <button type="button" class="btn" onclick="road_view_modal(this, '<?php echo $road->ref;?>', 'collapse', 'xl');">
                Menu
            </button>
            -
            <button type="button" class="btn" onclick="road_view_modal(this, '<?php echo $road->ref;?>', 'checkbox', 'xl');"> 
                Checkbox
            </button>
            -
            <a role="button" class="btn" href="<?php echo base_url('road/edit/' . $road->ref);?>"> 
                Éditer 
            </a>
        </li>
    <?php endforeach;?>
</ul> -->

<?php $this->endSection(); ?>

