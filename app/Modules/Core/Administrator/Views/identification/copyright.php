<?php $keys = new \Custom\Config\Key();?>
<p class="mb-5 text-center copyright">
    © <?php echo date('Y').' - '. (date('Y') + 1); ?> 
    <a href="<?php echo $keys->PublicSite ?? '';?>" target="_blank" class="link text-body-secondary">
        <strong class="mx-2"> <?php echo $themes->main->name;?> </strong>
    </a> 
</p>