<div class="container">
    <h4 class='text-center'>Bienvenue sur la nouvelle BD du STICS</h4>  
    
    <div class="panel panel-info panel_stic">
	<div class="panel-body">
	    <div class='row'>
		<div class='col-md-4'><h4>Formations sur mesure</h4></div>
		<div class='col-md-8 text-right'><label>Pour l'année:</label> <select><option>2020</option></select></div>
		<div class='load_ajax'>
		    <div style="margin: 50px 0px" class='text-center'><i class='fa fa-spin fa-spinner fa-4x'></i></div>
		</div>
	    </div>
	    
	</div>
    </div>
    
    <div class="panel panel-info panel_stic"> 
	<div class="panel-body">
	  <h4>Futures actions FSM</h4>  
	</div>
	<div style="padding:5px" class='load_ajax'>
	    <div style="display:none" class='load_ajax'>
		    <div style="margin: 50px 0px" class='text-center'><i class='fa fa-spin fa-spinner fa-4x'></i></div>
	    </div>
	   
	    <table class="table table-bordered table-condensed table-striped table-hover datatable_stics">
		<thead>
		    <tr>
			<th>Numéros</th>
			<th>Intitulé</th>
			<th>Client</th>
			<th>Personne de contact</th>
			<th>Prestataires</th>
			<th>Notes</th>
			<th>Statut</th>
		    </tr>
		</thead>
		<tbody>
		    <?php foreach($fsms as $fsm):?>
		    <tr>
			<td><?php echo $fsm->ref2;?> <?php echo ajoutoo($fsm->num);?> </td>
			<td><?php echo $fsm->intitule;?></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		    </tr>
		    <?php endforeach;?>
		</tbody>
		
	    </table>
	     <?php //print_r($fsms);?>
	</div>
    </div>
    
    <div class="panel panel-info panel_stic">
	<div class="panel-body">
	 <h4>Prestations programmées</h4>   
	</div>
	<div class='load_ajax'>
	    <div class='load_ajax'>
		    <div style="margin: 50px 0px" class='text-center'><i class='fa fa-spin fa-spinner fa-4x'></i></div>
	    </div>
	</div>
    </div>
</div>

