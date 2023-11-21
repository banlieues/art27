<?php if(!empty($profile->adresse) && !empty($profile->localite)):?> 
    <?php echo fontawesome('map-marker-alt');?>
    <?php echo $profile->adresse;?> - <?php echo $profile->localite;?> <br>
<?php endif;?>
<?php if(!empty($profile->email)):?>
    <span class="me-2"> <?php echo fontawesome('paper-plane');?> </span>
    <?php echo $profile->email;?> <br>
<?php endif;?>
<?php if(!empty($profile->telephone)):?>
    <span class="me-2"> <?php echo fontawesome('phone-alt');?> </span>
    <?php echo $profile->telephone;?>
<?php endif;?>
