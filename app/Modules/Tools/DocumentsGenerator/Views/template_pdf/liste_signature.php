<h2><?=supprimer_numero($contacts[0]->titre)?> - <?=$contacts[0]->dates_ra?> à <?=$contacts[0]->lieu?></h2>


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
			<th></th>
		</tr>
	</thead>
	<tbody>

	<?php $i=1; foreach($contacts as $contact):
				
				if($contact->statutsuivi=="I"):
				?>
	<tr>
		<td align="left" width="3%" style="border:1px solid black"><?=$i;?> </td>		
		<td align="left" width="47%" height="50px" style="border:1px solid black"><?=$contact->nom_contact?> <?=$contact->prenom?></td>
		<td align="left" width="50%" height="50px" style="border:1px solid black"></td>
	</tr>
	<?php $i=$i+1; endif; endforeach;?>
	</tbody>
	<tr>
		<td align="left" width="3%" height="50px" style="border:1px solid black"><br><br><br></td>
		<td align="left" width="47%" height="50px" style="border:1px solid black"><br><br><br></td>
		<td align="left" width="50%" height="50px" style="border:1px solid black"><br><br><br></td>
	</tr>
	<tr>
		<td align="left" width="3%" height="50px" style="border:1px solid black"><br><br><br></td>
		<td align="left" width="47%" height="50px" style="border:1px solid black"><br><br><br></td>
		<td align="left" width="50%" height="50px" style="border:1px solid black"><br><br><br></td>
	</tr>
	<tr>
		<td align="left" width="3%" height="50px" style="border:1px solid black"><br><br><br></td>
		<td align="left" width="47%" height="50px" style="border:1px solid black"><br><br><br></td>
		<td align="left" width="50%" height="50px" style="border:1px solid black"><br><br><br></td>
	</tr>
</table>
<?php else:?>
<p>Il n'y a aucune personne inscrite à cette action.</p>
<?php endif;?>
