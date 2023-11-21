
<?php if($typeDataView=="read"||$typeDataView=="list"||$typeDataView=="update"):?>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link <?php if($tab=="fiche"):?>active<?php else:?>text-<?=$themes->partenaire_culturel->color?><?php endif;?>" aria-current="page" href="<?=base_url()?>partenaire_culturel/fiche/<?=$partenaire_culturel->id_partenaire_culturel?>">
        <?=$themes->partenaire_culturel->icon?> Fiche
        </a>
    </li>
     
        <li class="nav-item">
            <a class="nav-link <?php if($tab=="gestion"):?>active<?php else:?>text-<?=$themes->partenaire_culturel->color?><?php endif;?>" href="<?=base_url()?>ticket/listTicket/<?=$partenaire_culturel->id_partenaire_culturel?>/<?=date("Y")?>">
                <i class="fas fa-user-plus"></i> Gestion tickets</a>
        </li>
        
    
   
</ul>
<?php endif?>