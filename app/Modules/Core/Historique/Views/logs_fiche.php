<?php $request = \Config\Services::request(); ?>

<?php if (!$request->isAJAX()) 
    {?>
		<?php $this->extend("Layout\index"); ?>

		<?php $this->section("body"); ?>

<?php }?>

<div class="panel panel-default" style='padding: 5px !important; margin-top:10px '>
    <div class="panel-body">
	
	<?php if(count($historiques)>0): ?>
	<table class='table table-hover table-bordered table-striped'>
	    <thead>
		<tr>
		    <th>Date modification</th>
		    <th>Par</th>
		   
		    <th>Label</th>
		    <th>Ancienne valeur</th>
		    <th>Nouvelle valeur</th>
		</tr>
	    </thead>
	    <tbody>
		<?php foreach($historiques as $h):?>
		<tr>
		    <td><?php echo $h->date_modif;?></td>
		    <td><?php echo $h->nom;?> <?php echo $h->prenom;?></td>
		    <td><?php echo $h->label;?></td>
		    <td><?php echo $h->value_old;?></td>
		    <td><?php echo $h->value_new;?></td>
		</tr>
		
		<?php endforeach;?>
	    </tbody>
	</table>
	<?php else: ?>
	<div style='margin:20px; text-align:center'><h3>Pas de modifications enregistrées</h3></div>
	<?php endif;?>
    </div>
    
    
    
</div>
<?php if (!$request->isAJAX()) 
    {?>
<?php $this->endSection(); ?>
	
	<?php } ?>
