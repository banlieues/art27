<h2><?=supprimer_numero($contacts[0]->titre)?> - <?=$contacts[0]->dates_ra?> à <?=$contacts[0]->lieu?></h2>


<h2>Encadrants</h2>
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
<table class="arial2" cellpadding="2" cellspacing="0" style="width: 100%; border: 0px;">
	<thead>
		<tr>
			<th></th>
			<th></th>
			<th align="left">Nom</th>
			<th align="left">Prénom</th>
			<th align="left">Age</th>			
			<th align="left">Adresse</th>
			<th align="left">Téléphone</th>
			<th align="left">GSM</th>
		</tr>
	</thead>
	<tbody>

	<?php $i=1; foreach($contacts as $contact):
				
	if($contact->statutsuivi=="E"):
	?>
		<tr>
			<td align="left" width="3%" style="border:1px solid black"><?=$i;?> </td>
			<td align="left" width="4%" style="border:1px solid black"><?=code_courtoisie($contact->codecourtoisie)?></td>
			<td align="left" style="border:1px solid black"><?=$contact->nom_contact?></td>
			<td align="left" style="border:1px solid black"><?=$contact->prenom?></td>
			<td align="left" width="4%" style="border:1px solid black"><?=calcul_age($contact->date_naissance)?></td>		
			<td align="left" width="20%" style="border:1px solid black"><?=$contact->adresse_no?> <?=$contact->adresse?> <?=$contact->codepostal?> <?=$contact->localite?></td>
			<td align="left" style="border:1px solid black"><?=$contact->tel1?></td>
			<td align="left" style="border:1px solid black"><?=$contact->gsm1?></td>
		</tr>
	<?php $i=$i+1; endif; endforeach;?>
	</tbody>
</table>
<?php else:?>
	<p>Il n'y a aucun encadrant à cette action.</p>
<?php endif;?>

<h2>Inscrits</h2>
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
			<th align="left">Age</th>			
			<th align="left">Adresse</th>
			<th align="left">Téléphone</th>
			<th align="left">GSM</th>
		</tr>
	</thead>
	<tbody>
				<?php $i=1; foreach($contacts as $contact):
				
				if($contact->statutsuivi=="I"):
				?>
					<tr>
						<td align="left" width="3%" style="border:1px solid black"><?=$i;?> </td>
						<td align="left" width="4%" style="border:1px solid black"><?=code_courtoisie($contact->codecourtoisie)?></td>
						<td align="left" style="border:1px solid black"><?=$contact->nom_contact?></td>
						<td align="left" style="border:1px solid black"><?=$contact->prenom?></td>
						<td align="left" width="4%" style="border:1px solid black"><?=calcul_age($contact->date_naissance)?></td>		
						<td align="left" width="20%" style="border:1px solid black"><?=$contact->adresse_no?> <?=$contact->adresse?> <?=$contact->codepostal?> <?=$contact->localite?></td>
						<td align="left" style="border:1px solid black"><?=$contact->tel1?></td>
						<td align="left" style="border:1px solid black"><?=$contact->gsm1?></td>
					</tr>
				<?php $i=$i+1; endif; endforeach;?>
	</tbody>
</table>
<?php else:?>
<p>Il n'y a aucune personne inscrite à cette action.</p>
<?php endif;?>

<h2>Participants "Cemea"</h2>
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
<table class="arial2" cellpadding="2" cellspacing="0" style="width: 100%; border: 0px;">
	<thead>
		<tr>
			<th></th>
			<th></th>
			<th align="left">Nom</th>
			<th align="left">Prénom</th>
			<th align="left">Age</th>			
			<th align="left">Adresse</th>
			<th align="left">Téléphone</th>
			<th align="left">GSM</th>
		</tr>
	</thead>
	<tbody>
				<?php $i=1; foreach($contacts as $contact):
				
				if($contact->statutsuivi=="C"):
				?>
					<tr>
						<td align="left" width="3%" style="border:1px solid black"><?=$i;?> </td>
						<td align="left" width="4%" style="border:1px solid black"><?=code_courtoisie($contact->codecourtoisie)?></td>
						<td align="left" style="border:1px solid black"><?=$contact->nom_contact?></td>
						<td align="left" style="border:1px solid black"><?=$contact->prenom?></td>
						<td align="left" width="4%" style="border:1px solid black"><?=calcul_age($contact->date_naissance)?></td>		
						<td align="left" width="20%" style="border:1px solid black"><?=$contact->adresse_no?> <?=$contact->adresse?> <?=$contact->codepostal?> <?=$contact->localite?></td>
						<td align="left" style="border:1px solid black"><?=$contact->tel1?></td>
						<td align="left" style="border:1px solid black"><?=$contact->gsm1?></td>
					</tr>
				<?php $i=$i+1; endif; endforeach;?>
	</tbody>
</table>
<?php else:?>
<p>Il n'y a aucune personne identifié particulièrement en accord avec les CEMEA à cette action.</p>
<?php endif;?>

<h2>Accompagnants</h2>
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
<table class="arial2" cellpadding="2" cellspacing="0" style="width: 100%; border: 0px;">
	<thead>
		<tr>
			<th></th>
			<th></th>
			<th align="left">Nom</th>
			<th align="left">Prénom</th>
			<th align="left">Age</th>			
			<th align="left">Adresse</th>
			<th align="left">Téléphone</th>
			<th align="left">GSM</th>
		</tr>
	</thead>
	<tbody>
				<?php $i=1; foreach($contacts as $contact):
				
				if($contact->statutsuivi=="B"):
				?>
					<tr>
						<td align="left" width="3%" style="border:1px solid black"><?=$i;?> </td>
						<td align="left" width="4%" style="border:1px solid black"><?=code_courtoisie($contact->codecourtoisie)?></td>
						<td align="left" style="border:1px solid black"><?=$contact->nom_contact?></td>
						<td align="left" style="border:1px solid black"><?=$contact->prenom?></td>
						<td align="left" width="4%" style="border:1px solid black"><?=calcul_age($contact->date_naissance)?></td>		
						<td align="left" width="20%" style="border:1px solid black"><?=$contact->adresse_no?> <?=$contact->adresse?> <?=$contact->codepostal?> <?=$contact->localite?></td>
						<td align="left" style="border:1px solid black"><?=$contact->tel1?></td>
						<td align="left" style="border:1px solid black"><?=$contact->gsm1?></td>
					</tr>
				<?php $i=$i+1; endif; endforeach;?>
	</tbody>
</table>
<?php else:?>
<p>Il n'y a aucun accompagnant à cette action.</p>
<?php endif;?>


<h2>Personnes refusées</h2>
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
<table class="arial2" cellpadding="2" cellspacing="0" style="width: 100%; border: 0px;">
	<thead>
		<tr>
			<th></th>
			<th></th>
			<th align="left">Nom</th>
			<th align="left">Prénom</th>
			<th align="left">Age</th>			
			<th align="left">Adresse</th>
			<th align="left">Téléphone</th>
			<th align="left">GSM</th>
		</tr>
	</thead>
	<tbody>
		<?php $i=1; foreach($contacts as $contact):
				
				if($contact->statutsuivi=="R"):
				?>
					<tr>
						<td align="left" width="3%" style="border:1px solid black"><?=$i;?> </td>
						<td align="left" width="4%" style="border:1px solid black"><?=code_courtoisie($contact->codecourtoisie)?></td>
						<td align="left" style="border:1px solid black"><?=$contact->nom_contact?></td>
						<td align="left" style="border:1px solid black"><?=$contact->prenom?></td>
						<td align="left" width="4%" style="border:1px solid black"><?=calcul_age($contact->date_naissance)?></td>		
						<td align="left" width="20%" style="border:1px solid black"><?=$contact->adresse_no?> <?=$contact->adresse?> <?=$contact->codepostal?> <?=$contact->localite?></td>
						<td align="left" style="border:1px solid black"><?=$contact->tel1?></td>
						<td align="left" style="border:1px solid black"><?=$contact->gsm1?></td>
					</tr>
				<?php $i=$i+1; endif; endforeach;?>
	</tbody>
</table>
<?php else:?>
<p>Il n'y a aucune personne refusée à cette action.</p>
<?php endif?>

<h2>Places réservées</h2>
<?php
	$is_trouve=FALSE;
	foreach($contacts as $contact)
	{
		if($contact->statutsuivi=="X")
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
			<th align="left">Age</th>			
			<th align="left">Adresse</th>
			<th align="left">Téléphone</th>
			<th align="left">GSM</th>
		</tr>
	</thead>
	<tbody>	
	<?php $i=1; foreach($contacts as $contact):
				
				if($contact->statutsuivi=="X"):
				?>
					<tr>
						<td align="left" width="3%" style="border:1px solid black"><?=$i;?> </td>
						<td align="left" width="4%" style="border:1px solid black"><?=code_courtoisie($contact->codecourtoisie)?></td>
						<td align="left" style="border:1px solid black"><?=$contact->nom_contact?></td>
						<td align="left" style="border:1px solid black"><?=$contact->prenom?></td>
						<td align="left" width="4%" style="border:1px solid black"><?=calcul_age($contact->date_naissance)?></td>		
						<td align="left" width="20%" style="border:1px solid black"><?=$contact->adresse_no?> <?=$contact->adresse?> <?=$contact->codepostal?> <?=$contact->localite?></td>
						<td align="left" style="border:1px solid black"><?=$contact->tel1?></td>
						<td align="left" style="border:1px solid black"><?=$contact->gsm1?></td>
					</tr>
				<?php $i=$i+1; endif; endforeach;?>
	</tbody>
</table>
<?php else:?>
<p>Il n'y a aucune réservation à cette action.</p>
<?php endif;?>

<h2>Liste d'attente</h2>
<?php
	$is_trouve=FALSE;
	foreach($contacts as $contact)
	{
		if($contact->statutsuivi=="L")
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
			<th align="left">Age</th>			
			<th align="left">Adresse</th>
			<th align="left">Téléphone</th>
			<th align="left">GSM</th>
		</tr>
	</thead>
	<tbody>
<?php $i=1; foreach($contacts as $contact):
				
				if($contact->statutsuivi=="L"):
				?>
					<tr>
						<td align="left" width="3%" style="border:1px solid black"><?=$i;?> </td>
						<td align="left" width="4%" style="border:1px solid black"><?=code_courtoisie($contact->codecourtoisie)?></td>
						<td align="left" style="border:1px solid black"><?=$contact->nom_contact?></td>
						<td align="left" style="border:1px solid black"><?=$contact->prenom?></td>
						<td align="left" width="4%" style="border:1px solid black"><?=calcul_age($contact->date_naissance)?></td>		
						<td align="left" width="20%" style="border:1px solid black"><?=$contact->adresse_no?> <?=$contact->adresse?> <?=$contact->codepostal?> <?=$contact->localite?></td>
						<td align="left" style="border:1px solid black"><?=$contact->tel1?></td>
						<td align="left" style="border:1px solid black"><?=$contact->gsm1?></td>
					</tr>
				<?php $i=$i+1; endif; endforeach;?>
	</tbody>
</table>
<?php else:?>
<p>Il n'y a aucune personne en attente pour cette action.</p>
<?php endif?>

<h2>Personnes à traiter</h2>
<?php
	$is_trouve=FALSE;
	foreach($contacts as $contact)
	{
		if($contact->statutsuivi=="T")
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
			<th align="left">Age</th>			
			<th align="left">Adresse</th>
			<th align="left">Téléphone</th>
			<th align="left">GSM</th>
		</tr>
	</thead>
	<tbody>
<?php $i=1; foreach($contacts as $contact):
				
				if($contact->statutsuivi=="T"):
				?>
					<tr>
						<td align="left" width="3%" style="border:1px solid black"><?=$i;?> </td>
						<td align="left" width="4%" style="border:1px solid black"><?=code_courtoisie($contact->codecourtoisie)?></td>
						<td align="left" style="border:1px solid black"><?=$contact->nom_contact?></td>
						<td align="left" style="border:1px solid black"><?=$contact->prenom?></td>
						<td align="left" width="4%" style="border:1px solid black"><?=calcul_age($contact->date_naissance)?></td>		
						<td align="left" width="20%" style="border:1px solid black"><?=$contact->adresse_no?> <?=$contact->adresse?> <?=$contact->codepostal?> <?=$contact->localite?></td>
						<td align="left" style="border:1px solid black"><?=$contact->tel1?></td>
						<td align="left" style="border:1px solid black"><?=$contact->gsm1?></td>
					</tr>
				<?php $i=$i+1; endif; endforeach;?>
	</tbody>
</table>
<?php else:?>
<p>Il n'y a aucune personne à traiter à cette action.</p>
<?php endif;?>
