<?php $this->extend('Layout\index'); ?>

<?php $this->section('script_foot_injected');?>
    <?php echo view('Components\js/brugis-tamo');?>
    <?php echo view('DemandeWeb\js/js_demande');?>
<?php $this->endSection();?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->demande_web->color;?>">
        <div class="d-flex justify-content-center">
            <div class="h5 mb-0"> <?php echo $titleView;?> </div>
            <button type="button"
                class="btn btn-sm btn-link link-<?php echo $themes->demande_web->color;?> py-1"
                data-bs-toggle="collapse"
                data-bs-target="#demandeInfo"
                title="Prévisualiser les données provenant du formulaire"
                >
                <?php echo fontawesome('eye');?>
            </button>
        </div>
        <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-sm btn-outline-danger ms-2" 
                onclick="deposit_delete_modal(this, <?php echo $id_deposit;?>);"
                title="Retirer la demande du dépôt"
                > 
                <?php echo fontawesome('trash-alt');?>
            </button>
            <button type="submit"
                class="btn btn-sm btn-<?php echo $themes->demande_web->color;?> ms-2" form="depositForm"
                title="Créer une nouvelle demande"
                >
                <?php echo fontawesome('plus');?>
                Nouvelle demande
            </button>
            <a role="button" 
                class="btn btn-sm btn-outline-secondary ms-2" 
                onclick="set_worker('off', <?php echo $id_deposit;?>);"
                href="<?php echo base_url("demande/web");?>"
                >
                Annuler
            </button>
            <a role="button" 
                class="btn btn-sm btn-<?php echo $themes->demande_web->color;?> ms-2" 
                onclick="set_worker('off', <?php echo $id_deposit;?>);"
                href="<?php echo base_url("demande/web");?>"
                title="Retourner à la liste des demandes web"
                >
                <?php echo fontawesome('turn-up');?>
                <?php echo $themes->demande_web->icon;?>
            </a>
        </div>
    </div>
<?php $this->endSection(); ?>

<?php $this->section("body"); ?>

<form id="depositForm" method="post" action="<?php echo base_url('demande/web/deposit/to/demande');?>">

    <input type="hidden" name="id_deposit" value="<?php echo $id_deposit;?>"/>

    <div class="collapse bg-light border rounded mb-4" id="demandeInfo">
        <table class="table table-sm">
        <?php foreach($infos as $ref=>$info):?>
            <tr>
                <td class="px-4 text-nowrap">
                    <small> <?php echo $info->title;?> </small>
                </td>
                <td class="px-4">
                    <?php if(is_array($info->label)):?>
                        <?php if($ref=='urls_file'):?>
                            <ul class="pl-0">
                                <?php foreach($info->label as $label):?>
                                    <li class="list-unstyled">
                                        <a class="text-body" href="<?php echo $label;?>" target="_blank"> <small> <strong> -  <?php echo pathinfo($label)['basename'];?> </strong> </small> </a>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>
                    <?php else:?>
                        <small> <strong> <?php echo $info->label;?> </strong> </small>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
        </table>
    </div>
    <div id="depositPerson" class="border rounded mb-4">
        <?php echo view('DemandeWeb\deposit-info-profil');?>
    </div>

    <div id="depositBuilding">
        <?php if(!empty($buildings)):?>
            <?php echo view('DemandeWeb\deposit-info-building-accomp');?>
        <?php endif;?>
    </div>

    <div id="depositDemande" class="border rounded mb-4">
        <?php echo view('DemandeWeb\deposit-info-demande');?>
    </div>

</form>

<?php $this->endSection(); ?>

