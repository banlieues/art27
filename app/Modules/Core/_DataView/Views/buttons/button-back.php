<a class="btn btn-dark btn-sm ms-2" 
    href="<?php echo base_url($entityObject->entity->type . '?' . $get);?>"
    title="Aller à la liste des <?php echo mb_strtolower($entityObject->entity->labels, 'UTF-8');?>"
    >
    <?php echo $themes->goto->icon;?>
    Retour à la liste
    <?php echo $themes->{$entityObject->entity->ref}->icon;?>
</a>