<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style>
	html, body {
		width: 100%;
		height: 100%;
		margin: 0;
		padding: 50px;
		padding-left: 50px;
		padding-right: 50px;
	}
	.pied_page {
		position: absolute;
		bottom: 30;
	}
	</style>
	</head>
	<body>


	
	<h2><?=supprimer_numero($contacts[0]->titre)?> - <?=$contacts[0]->dates_ra?> à <?=$contacts[0]->lieu?></h2>

	<?php //gros_titre('[(#TITRE|supprimer_numero)] - #DATES_RA à #LIEU*'); 
	
	?>
	

<h2>Equipe d'encadrement</h2>
<?php
	$is_trouve=FALSE;
	foreach($contacts as $contact)
	{
		if($contact->statutsuivi=="E")
		{
			$is_trouve=TRUE;
		}
		if($is_trouve)
			continue;
	}


?>
<?php if($is_trouve):?>
<table class="arial2" cellpadding="2" cellspacing="0" style="width: 25%; border: 0px;">
	<thead>
		<tr>
			<th align="left">Nom</th>
			<th align="left">Prénom</th>			
		</tr>
	</thead>
	<tbody>
	
	<?php foreach($contacts as $contact):
		if($contact->statutsuivi=="E"):
		?>
		<tr>		
			<td align="left"><?=$contact->nom_contact?></td>
			<td align="left"><?=$contact->prenom?></td>
		</tr>
	<?php endif; endforeach;?>
	</tbody>
</table>
<?php else:?>
	<p>Il n'y a pas d'encadrants pour cette action.</p>
<?php endif;?>



<?php
	$is_trouve=FALSE;
	foreach($contacts as $contact)
	{
		if($contact->statutsuivi=="M")
		{
			$is_trouve=TRUE;
		}
		if($is_trouve)
			continue;
	}


?>

<?php if($is_trouve):?>
<h2>Equipe d'animation</h2>

	<table class="arial2" cellpadding="2" cellspacing="0" style="width: 25%; border: 0px;">
		<thead>
			<tr>
				<th align="left">Nom</th>
				<th align="left">Prénom</th>			
			</tr>
		</thead>
		<tbody>
		<?php foreach($contacts as $contact):?>
			<?php if($contact->statutsuivi=="M"):?>
				<tr>		
					<td align="left"><?=$contact->nom_contact?></td>
					<td align="left"><?=$contact->prenomt?></td>
				</tr>
			<?php endif;?>
		<?php endforeach;?>
		</tbody>
	</table>

<?php endif;?>	

<h2>Participants</h2>
<?php
	$is_trouve=FALSE;
	foreach($contacts as $contact)
	{
		if($contact->statutsuivi=="I")
		{
			$is_trouve=TRUE;
		}
		if($is_trouve)
			continue;
	}


?>
<?php if($is_trouve):?>
		<table class="arial2" cellpadding="2" cellspacing="0" style="width: 100%; border: 0px;">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th align="left">Nom</th>
					<th align="left">Prénom</th>			
					<th align="left">Adresse</th>
					<th align="center">Absences/Remarques</th>
				</tr>
			</thead>
			<tbody>
			
			<?php $i=1; foreach($contacts as $contact):
				
				if($contact->statutsuivi=="I"):
				?>

			<tr>
				<td align="left" width="3%" style="border:1px solid black"><?=$i;?> </td>
				<td align="left" width="4%" style="border:1px solid black"><?=code_courtoisie($contact->codecourtoisie)?></td>		
				<td align="left" width="15%" style="border:1px solid black"><?=$contact->nom_contact?></td>
				<td align="left" width="15%" style="border:1px solid black"><?=$contact->prenom?></td>		
				<td align="left" width="20%"style="border:1px solid black"><?=$contact->adresse_no?> <?=$contact->adresse?> <?=$contact->codepostal?> <?=$contact->localite?></td>
				<td align="left" style="border:1px solid black"><?=$contact->remarques_inscription?></td>
			</tr>
			
		<?php $i=$i+1; endif; endforeach;?>
			</tbody>
		</table>
<?php else:?>
	<p>Il n'y a aucune personne inscrite à cette action.</p>
<?php endif;?>


<?php
	$is_trouve=FALSE;
	foreach($contacts as $contact)
	{
		if($contact->statutsuivi=="C")
		{
			$is_trouve=TRUE;
		}
		if($is_trouve)
			continue;
	}


?>
<?php if($is_trouve):?>
		<p><b>Personnes identifiées CEMEA</b></p>


		<table class="arial2" cellpadding="2" cellspacing="0" style="width: 100%; border: 0px;">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th align="left">Nom</th>
					<th align="left">Prénom</th>			
					<th align="left">Adresse</th>
					<th align="center">Absences/Remarques</th>
				</tr>
			</thead>
			<tbody>
			
			<?php $i=1; foreach($contacts as $contact):
				
				if($contact->statutsuivi=="C"):
				?>

			<tr>
				<td align="left" width="3%" style="border:1px solid black"><?=$i;?> </td>
				<td align="left" width="4%" style="border:1px solid black"><?=code_courtoisie($contact->codecourtoisie)?></td>		
				<td align="left" width="15%" style="border:1px solid black"><?=$contact->nom_contact?></td>
				<td align="left" width="15%" style="border:1px solid black"><?=$contact->prenom?></td>		
				<td align="left" width="20%"style="border:1px solid black"><?=$contact->adresse_no?> <?=$contact->adresse?> <?=$contact->codepostal?> <?=$contact->localite?></td>
				<td align="left" style="border:1px solid black"><?=$contact->remarques_inscription?></td>
			</tr>
			
		<?php $i=$i+1; endif; endforeach;?>
			</tbody>
		</table>

<?php endif;?>



<?php
	$is_trouve=FALSE;
	foreach($contacts as $contact)
	{
		if($contact->statutsuivi=="R")
		{
			$is_trouve=TRUE;
		}
		if($is_trouve)
			continue;
	}


?>

<?php if($is_trouve):?>
		<p><b>Personnes refusées</b></p>

		<table class="arial2" cellpadding="2" cellspacing="0" style="width: 100%; border: 0px;">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th align="left">Nom</th>
					<th align="left">Prénom</th>			
					<th align="left">Adresse</th>
					<th align="center">Absences/Remarques</th>
				</tr>
			</thead>
			<tbody>
			
			<?php $i=1; foreach($contacts as $contact):
				
				if($contact->statutsuivi=="R"):
				?>

			<tr>
				<td align="left" width="3%" style="border:1px solid black"><?=$i;?> </td>
				<td align="left" width="4%" style="border:1px solid black"><?=code_courtoisie($contact->codecourtoisie)?></td>		
				<td align="left" width="15%" style="border:1px solid black"><?=$contact->nom_contact?></td>
				<td align="left" width="15%" style="border:1px solid black"><?=$contact->prenom?></td>		
				<td align="left" width="20%"style="border:1px solid black"><?=$contact->adresse_no?> <?=$contact->adresse?> <?=$contact->codepostal?> <?=$contact->localite?></td>
				<td align="left" style="border:1px solid black"><?=$contact->remarques_inscription?></td>
			</tr>
			
		<?php $i=$i+1; endif; endforeach;?>
			</tbody>
		</table>
<?php endif;?>		


<?php
	$is_trouve=FALSE;
	foreach($contacts as $contact)
	{
		if($contact->statutsuivi=="B")
		{
			$is_trouve=TRUE;
		}
		if($is_trouve)
			continue;
	}


?>
<?php if($is_trouve):?>
		<p><b>Accompagnants</b></p>

		<table class="arial2" cellpadding="2" cellspacing="0" style="width: 100%; border: 0px;">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th align="left">Nom</th>
					<th align="left">Prénom</th>			
					<th align="left">Adresse</th>
					<th align="center">Absences/Remarques</th>
				</tr>
			</thead>
			<tbody>
			
			<?php $i=1; foreach($contacts as $contact):
				
				if($contact->statutsuivi=="B"):
				?>

			<tr>
				<td align="left" width="3%" style="border:1px solid black"><?=$i;?> </td>
				<td align="left" width="4%" style="border:1px solid black"><?=code_courtoisie($contact->codecourtoisie)?></td>		
				<td align="left" width="15%" style="border:1px solid black"><?=$contact->nom_contact?></td>
				<td align="left" width="15%" style="border:1px solid black"><?=$contact->prenom?></td>		
				<td align="left" width="20%"style="border:1px solid black"><?=$contact->adresse_no?> <?=$contact->adresse?> <?=$contact->codepostal?> <?=$contact->localite?></td>
				<td align="left" style="border:1px solid black"><?=$contact->remarques_inscription?></td>
			</tr>
			
		<?php $i=$i+1; endif; endforeach;?>
			</tbody>
		</table>
<?php endif;?>

</body></html>
