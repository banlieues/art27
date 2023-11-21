<?php $request = \Config\Services::request(); ?>

<?php 
    //on affiche le template si on appelle pas par Ajax
    if (!$request->isAJAX()) 
    {
        $this->extend('Layout\index');
        $this->section("body");
    }
?> 
<?php //debug($tags);?>
<h3>Tags Contacts</h3>
<div class="table-responsive">
    <table class="table table-striped table_bordered">
        <thead>
            <tr>
                <th>Tags</th>
                <th>Label</th>
                <th>Entité</th>
            </tr>
           
        </thead>
        <tbody>
            <?php foreach($tags_contact as $index=>$tag):?>
                <tr>
                    <th>#<?=strtoupper($tag["field_index"])?>#</th>
                    <td class="text-wrap"><?=$tag["label"]?></td>
                    <td><?=$tag["type"]?></td>
                </tr>
                    

            <?php endforeach?>
        </tbody>  
   </table>  

<h3>Tags Activité</h3>
    <table class="table table-striped table_bordered">
        <thead>
            <tr>
                <th>Tags</th>
                <th>Label</th>
                
                <th>Entité</th>
            </tr>
           
        </thead>
        <tbody>
            <?php foreach($tags_activity as $index=>$tag):?>
                <tr>
                    <th>#<?=strtoupper($tag["field_index"])?>#</th>
                    <td><?=$tag["label"]?></td>
                    <td><?=$tag["type"]?></td>
                </tr>
                    

            <?php endforeach?>
        </tbody>  
   </table>  
</div>   

   <h3>Tags Inscription</h3>
    <table class="table table-striped table_bordered">
        <thead>
            <tr>
                <th>Tags</th>
                <th>Label</th>
                
                <th>Entité</th>
            </tr>
           
        </thead>
        <tbody>
            <?php foreach($tags_registration as $index=>$tag):?>
                <tr>
                    <th>#<?=strtoupper($tag["field_index"])?>#</th>
                    <td><?=$tag["label"]?></td>
                    <td><?=$tag["type"]?></td>
                </tr>
                    

            <?php endforeach?>
        </tbody>  
   </table>     

<?php 
    //on affiche le template si on appelle pas par Ajax
    if (!$request->isAJAX()) 
    {
        $this->endSection();
    }
?>    