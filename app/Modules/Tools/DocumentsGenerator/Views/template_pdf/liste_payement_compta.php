<h2><?=supprimer_numero($contacts[0]->titre)?> - <?=$contacts[0]->dates_ra?> à <?=$contacts[0]->lieu?></h2>
<?php
	$is_trouve=FALSE;
	$statut=["E"];
	foreach($contacts as $contact)
	{
		if(!in_array($contact->statutsuivi,$statut))
		{
			$is_trouve=TRUE;
		}
		if($is_trouve)
			continue;
	}


?>	
<?php if($is_trouve):?>
<table width=100%>
    <tr>
        <th></th>
        <th></th>
        <th>Nom</th>
        <th>A perçevoir</th>
        <th>Liste de payement</th>
        <th>Extrait compte</th>
        <th>Solde</th>
        <th>Adresse facturation</th>
        <th>Statut</th>
        <th>Remarques gestion</th>
    </tr>
 
    <?php $i=1; foreach($contacts as $contact):
				
				if(!in_array($contact->statutsuivi,$statut)):
				?>
    <tr>
	<td align=left valign=top style="border-bottom:1px solid black;"><?=$i?></td>
	<td align=left valign=top style="border-bottom:1px solid black;"><?=code_courtoisie($contact->codecourtoisie)?></td>
	<td align=left valign=top style="border-bottom:1px solid black;"><?=$contact->nom_court_institution?> <?=$contact->nom_contact?> <?=$contact->prenom?></td>
        <td align=left valign=top style="border-bottom:1px solid black;"><?= calculer_prix_a_payer(
                            $contact->prix,
                            $contact->prix_organisme,
                            $contact->prix_etudiant,
                            $contact->prix_special,
                            $contact->typepart,
                            $contact->demandeur_emploi
                       );?> €</td>
	<td align=left valign=top style="border-bottom:1px solid black;"><?=$contact->historique_payement?></td>
	<td align=left valign=top style="border-bottom:1px solid black;"><?=$contact->extrait_de_compte?></td>
	<td align=left valign=top style="border-bottom:1px solid black;"><?= calculer_solde(
                            $contact->prix,
                            $contact->prix_organisme,
                            $contact->prix_etudiant,
                            $contact->prix_special,
                            $contact->typepart,
                            $contact->demandeur_emploi,
                            $contact->historique_payement
                       );?> €</b></td>
	<td align=left valign=top style="border-bottom:1px solid black;"><?=$contact->adresse_facturation?></td>
	<td align=left valign=top style="border-bottom:1px solid black;"><?=$contact->statutsuivi?></td>
	<td align=left valign=top style="border-bottom:1px solid black;"><?=$contact->remarques_gestion?></td>
    </tr>
    <?php $i=$i+1; endif; endforeach;?>
</table>
<?php endif;?>
