<?php $ldashboard = \Config\Services::ldashboard();?>
<div style="margin-top:50px; text-align:center">
    <h3>Aucune table trouvée</h3>
    <a class="btn btn-default" href="<?php echo base_url();?>/dashboard/ajouter/<?php echo $id_user;?>/<?php echo $id_onglet;?>">
    Ajouter une table à l'onglet <?php echo $ldashboard->get_name_onglet($id_onglet);?>
    </a>
    
</div>

