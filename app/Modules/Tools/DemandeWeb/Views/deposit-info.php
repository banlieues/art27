<form id="depositForm" method="post" action="<?php echo base_url('demande/web/deposit/to/demande');?>">

    <input type="hidden" name="id_deposit" value="<?php echo $id_deposit;?>"/>

    <button type="button" class="btn btn-sm btn-<?php echo $themes->demande_web->color;?> mb-4" data-bs-toggle="collapse" data-bs-target="#demandeInfo">
        <?php echo fontawesome('eye');?> Prévisualiser les données provenant du formulaire
    </button>
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
            <?php echo view('Components\js/brugis-tamo');?>
        <?php endif;?>
    </div>

    <div id="depositDemande" class="border rounded mb-4">
        <?php echo view('DemandeWeb\deposit-info-demande');?>
    </div>

</form>
