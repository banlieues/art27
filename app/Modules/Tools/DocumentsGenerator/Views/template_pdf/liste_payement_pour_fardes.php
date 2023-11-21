
	<h2>Liste de payement</h2>
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
<table class="arial2" cellpadding="2" cellspacing="0" style="width: 100%;">
    <tr>
        <th></th>
        <th></th>
        <th align=left>Nom</th>
        <th align=center>A percevoir</th>
        <th align=center>Perçu</th>
        <th align=center>Solde</th>
    </tr>

    <?php $i=1; foreach($contacts as $contact):
				
				if(!in_array($contact->statutsuivi,$statut)):
				?>
	

    <tr class="valigntop" style="border-bottom:1px solid black;">
	    <td align=left><?=$i?> </td>
        <td align=left><?=code_courtoisie($contact->codecourtoisie)?></td>
        <td align=left><?=$contact->nom_court_institution?> <?=$contact->nom_contact?> <?=$contact->prenom?></td>
        <td align=center><?= calculer_prix_a_payer(
                            $contact->prix,
                            $contact->prix_organisme,
                            $contact->prix_etudiant,
                            $contact->prix_special,
                            $contact->typepart,
                            $contact->demandeur_emploi
                       );?> €</td>
        <td align=center><?=calculer_payement($contact->historique_payement)?> €</td>
        <td align=center><?= calculer_solde(
                            $contact->prix,
                            $contact->prix_organisme,
                            $contact->prix_etudiant,
                            $contact->prix_special,
                            $contact->typepart,
                            $contact->demandeur_emploi,
                            $contact->historique_payement
                       );?> €</td>

    </tr>
    <?php $i=$i+1; endif; endforeach;?>
</table>
<?php endif;?>

