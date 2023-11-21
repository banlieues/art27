<h2><?=supprimer_numero($contacts[0]->titre)?> - <?=$contacts[0]->dates_ra?> à <?=$contacts[0]->lieu?></h2>


<h2>Inscrits</h2>
<?php
	$is_trouve=FALSE;
	$statut=["I"];
	foreach($contacts as $contact)
	{
		if(in_array($contact->statutsuivi,$statut))
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
			<th align="center">N°</th>
			<th align="center">Sexe</th>
			<th align="center">Nom Prénom</th>
			<th align="center">Naissance</th>
			<th align="center">Age</th>
			<th align="center">CP - Ville</th>		
			<th align="center">Adh.</th>
			<th align="center">Alim.</th>
			<th align="center">Remarques</th>
			<th align="center">Adresse facturation</th>
			<th align="center">Employeur</th>
			<th align="center">Profession</th>
		</tr>
	</thead>
	<tbody>
	
	<?php $i=1; foreach($contacts as $contact):
				
				if(in_array($contact->statutsuivi,$statut)):
				?>
	<tr>
		<td align="center" width="3%" style="border:1px solid black"><?=$i?></td>
		<td align="center" width="3%" style="border:1px solid black"><?=code_courtoisie($contact->codecourtoisie)?></td>
		<td align="center" width="15%" style="border:1px solid black"><?=$contact->nom_contact?> <?=$contact->prenom?></td>
		<td align="center" width="10%" style="border:1px solid black"><?=convert_date_en_to_fr_with_h($contact->date_naissance,false)?></td>
		<td align="center" width="3%" style="border:1px solid black"><?=calcul_age($contact->date_naissance)?></td>		
		<td align="center" width="10%" style="border:1px solid black"><?=$contact->codepostal?> <?=$contact->localite?></td>
		<td align="center" width="3%" style="border:1px solid black"><?=$contact->adherent?></td>
		<td align="center" width="3%" style="border:1px solid black"><?=$contact->alimentation?></td>
		<td align="center" width="10%" style="border:1px solid black"><?=$contact->remarques_inscription?></td>
		<td align="center" width="10%" style="border:1px solid black"><?=$contact->adresse_facturation?></td>
		<td align="center" width="10%" style="border:1px solid black"><?=$contact->etude_etablissement ?></td>
		<td align="center" width="20%" style="border:1px solid black"><?=$contact->profession?></td>

	</tr>
	<?php $i=$i+1; endif; endforeach;?>

	</tbody>
</table>
<?php else:?>
<p>Il n'y a aucune personne inscrite à cette action.</p>
<?php endif;?>

<h2>Accompagants</h2>
<?php
	$is_trouve=FALSE;
	$statut=["B"];
	foreach($contacts as $contact)
	{
		if(in_array($contact->statutsuivi,$statut))
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
		<th align="center">N°</th>
			<th align="center">Sexe</th>
			<th align="center">Nom Prénom</th>
			<th align="center">Naissance</th>
			<th align="center">Age</th>
			<th align="center">CP - Ville</th>		
			<th align="center">Adh.</th>
			<th align="center">Alim.</th>
			<th align="center">Remarques</th>
			<th align="center">Adresse facturation</th>
			<th align="center">Employeur</th>
			<th align="center">Profession</th>
		</tr>
	</thead>
	<tbody>
	
	<?php $i=1; foreach($contacts as $contact):
				
				if(in_array($contact->statutsuivi,$statut)):
				?>
	<tr>
		<td align="center" width="3%" style="border:1px solid black"><?=$i?></td>
		<td align="center" width="3%" style="border:1px solid black"><?=code_courtoisie($contact->codecourtoisie)?></td>
		<td align="center" width="15%" style="border:1px solid black"><?=$contact->nom_contact?> <?=$contact->prenom?></td>
		<td align="center" width="10%" style="border:1px solid black"><?=convert_date_en_to_fr_with_h($contact->date_naissance,false)?></td>
		<td align="center" width="3%" style="border:1px solid black"><?=calcul_age($contact->date_naissance)?></td>		
		<td align="center" width="10%" style="border:1px solid black"><?=$contact->codepostal?> <?=$contact->localite?></td>
		<td align="center" width="3%" style="border:1px solid black"><?=$contact->adherent?></td>
		<td align="center" width="3%" style="border:1px solid black"><?=$contact->alimentation?></td>
		<td align="center" width="10%" style="border:1px solid black"><?=$contact->remarques_inscription?></td>
		<td align="center" width="10%" style="border:1px solid black"><?=$contact->adresse_facturation?></td>
		<td align="center" width="10%" style="border:1px solid black"><?=$contact->etude_etablissement ?></td>
		<td align="center" width="20%" style="border:1px solid black"><?=$contact->profession?></td>

	</tr>
	<?php $i=$i+1; endif; endforeach;?>

	</tbody>
</table>
<?php else:?>
<p>Il n'y a aucune personne accompagnante à cette action.</p>
<?php endif;?>
