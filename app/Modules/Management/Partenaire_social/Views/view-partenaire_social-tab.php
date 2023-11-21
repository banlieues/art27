
<?php if($typeDataView=="read"||$typeDataView=="print"||$typeDataView=="list"):?>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link <?php if($tab=="fiche"):?>active<?php else:?>text-<?=$themes->partenaire_social->color?><?php endif;?>" aria-current="page" href="<?=base_url()?>partenaire_social/fiche/<?=$partenaire_social->id_partenaire_social?>">
        <?=$themes->partenaire_social->icon?> Fiche
        </a>
    </li>
     
        <li class="nav-item">
            <a class="nav-link <?php if($tab=="convention"):?>active<?php else:?>text-<?=$themes->partenaire_social->color?><?php endif;?>" href="<?=base_url()?>partenaire_social/convention_barcode/<?=$partenaire_social->id_partenaire_social?>/<?=date("Y")?>">
                <i class="fas fa-user-plus"></i> Gestion Convention</a>
        </li>
    
   
</ul>
<?php endif?>