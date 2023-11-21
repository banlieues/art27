<h2><?=supprimer_numero($contacts[0]->titre)?> - <?=$contacts[0]->dates_ra?> à <?=$contacts[0]->lieu?></h2>


<h2>Inscrits</h2>
<?php
	$is_trouve=FALSE;
	$statut=["I","E","B"];
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
			<th></th>
			<th></th>
			<th></th>
			<th align="left">Age</th>			
			<th align="left">Statut</th>
			<th align="left">Adhérent</th>
			<th align="left">Menu</th>
			<th align="left">Remarque</th>
			<th align="left">Adresse facturation</th>
			<th align="left">Etablissement</th>
			<th align="left">Profession</th>
		</tr>
	</thead>
	<tbody>
	
	<?php $i=1; foreach($contacts as $contact):
				
				if(in_array($contact->statutsuivi,$statut)):
				?>
	<tr>
		
		<td align="left" width="3%" style="border:1px solid black"><?=$i;?> </td>
		<td align="left" width="4%" style="border:1px solid black"><?=code_courtoisie($contact->codecourtoisie)?></td>
		<td align="left" width="25%" style="border:1px solid black"><?=$contact->nom_contact?> <?=$contact->prenom?></td>
		<td align="left" width="4%" style="border:1px solid black"><?=calcul_age($contact->date_naissance)?></td>		
		<td align="left" style="border:1px solid black"><?=$contact->statutsuivi?></td>
		<td align="center" width="3%" style="border:1px solid black"><?=$contact->adherent?></td>
		<td align="left"  style="border:1px solid black"><?=$contact->alimentation?></td>
		<td align="left"  style="border:1px solid black"><?=$contact->remarques_inscription?></td>
		<td align="left" style="border:1px solid black"><?=$contact->adresse_facturation?></td>
		<td align="left" style="border:1px solid black"><?=$contact->etude_etablissement ?></td>
		<td align="left" style="border:1px solid black"><?=$contact->profession?></td>

	</tr>
	<?php $i=$i+1; endif; endforeach;?>

	</tbody>
</table>
<?php else:?>
<p>Il n'y a aucune personne inscrite à cette action.</p>
<?php endif;?>
