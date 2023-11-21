<?php $this->extend('Layout\index'); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->mailing->color;?>">
        <div> 
            <?php echo $titleView;?>
            (<strong> <?php echo $nb_templates;?> </strong>) 
            <?php if(!empty($itemSearch)):?>
                <div class="d-inline small" title="Filtre : <?php echo $itemSearch;?>"><?php echo fontawesome('filter-warning');?></div>
            <?php endif;?>
        </div>
        <div>
            <?php echo view('DataView\buttons/pagination');?>
        </div>
        <div> 
            <?php echo view('DataView\buttons/search', ['color' => $themes->mailing->color,]);?>
        </div>
        <div class="d-flex">
            <div>
                <?php echo view('DataView\buttons/export_csv', [
                    'module' => 'Mailing',
                    'model' => 'TemplateModel',
                    'method' => 'TemplatesGet',
                    'filename' => 'liste_templates',
                ]);?>
            </div>
            <?php if($Autorisation->is_autorise('email_template_c')):?>
                <a role="button"
                    class="btn btn-sm btn-<?php echo $themes->mailing->color;?> ms-1" 
                    href="<?php echo base_url('mailing/template/view');?>"
                    title="Nouveau modèle d'email"
                    onclick="waiting_start(this);"
                    >
                    <?php echo $themes->add->icon;?> 
                    <?php echo $themes->mailing->icon;?> 
                </a>
            <?php endif;?>
        </div>
    </div>
<?php $this->endSection(); ?>

<?php $this->section('script_foot_injected');?>
<?php $this->endSection();?>

<?php $this->section("body"); ?>

<div class="banData">

    <?php echo view('Mailing\template-list-table');?>

</div>    

<?php $this->endSection(); ?>

