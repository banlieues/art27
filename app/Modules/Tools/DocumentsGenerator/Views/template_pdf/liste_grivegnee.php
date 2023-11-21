	<h2><center>LISTE DES PERSONNES HEBERGEES</center></h2>
	<p><b>Nom de l'organisation :</b> CEMEA
	<br><b>Nom du responsable : </b>
	<br><b>GSM : </b>
	<br><b>Séjour : </b> <?=supprimer_numero($contacts[0]->titre)?>
	<br><b>Date : </b> <?=$contacts[0]->dates_ra?>
	<br><br>Je certifie avoir pris connaissance et avoir communiqué au groupe la procédure en cas d'incendie<br>(telle que reprise ci-dessous).</p>

	<?php
	$is_trouve=FALSE;
	$statut=["C","E","I","B","M"];
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
	
<?php	if($is_trouve): ?>
<table class="arial2" cellpadding="2" cellspacing="0" style="width: 100%; border: 0px;">
	<thead>
		<tr>
			<th align="center">Nom</th>			
			<th align="center">Prénom</th>
			<th align="center">Date de naissance</th>
			<th align="center">Pays de résidence</th>
			<th align="center">GSM</th>
		</tr>
	</thead>
	<tbody>
	
	<?php $i=1; foreach($contacts as $contact):
				
				if(in_array($contact->statutsuivi,$statut)):
				?>

	<tr>
		<td align="left" width="20%" style="border:1px solid black"><?=$contact->nom_contact?></td>
		<td align="left" width="20%" style="border:1px solid black"><?=$contact->prenom?></td>
		<td align="left" width="20%" style="border:1px solid black">
			<?=convert_date_en_to_fr_with_h($contact->date_naissance,false)?>
		</td>		
		<td align="left" width="20%" style="border:1px solid black">
		<?php if(!empty($contact->pays)): echo $contact->pays; else: echo "Belgique"; endif;?>
			
		</td>
		<td align="center" width="20%" style="border:1px solid black"><?=$contact->gsm1?></td>
	</tr>
	<?php $i=$i+1; endif; endforeach;?>

	</tbody>
</table>
<?php else:?>
<p>Il n'y a aucune personne inscrite à cette action.</p>
<?php endif;?>
