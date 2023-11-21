<?php $request = \Config\Services::request(); ?>

<?php 
    //on affiche le template si on appelle pas par Ajax
    if (!$request->isAJAX()) 
    {
        $this->extend('templates/index');
        $this->section("body");
    }
?> 
<div class="">
<?php 
    if (!$request->isAJAX()) 
    {?>
    <h3><?=$titleView?></h3>
    <?php }?>
    <?=trim(nl2br($remarques->remarque_by_partenaire_culturel))?>
</div>


<?php 
    //on affiche le template si on appelle pas par Ajax
    if (!$request->isAJAX()) 
    {
        $this->endSection();
    }
?>    