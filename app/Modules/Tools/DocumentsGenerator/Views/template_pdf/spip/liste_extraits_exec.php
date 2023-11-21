<?php 
include_spip('inc/presentation');
include_spip('gestion_autorisation');
include_spip('fonctions_gestion_cemea');
?>
<div class="nettoyeur"></div>
    [(#ENV{pdf}|non)
    <?php gros_titre('Extrait de compte'); ?>
    ]
    [(#ENV{pdf}|oui)
    <?php gros_titre('Extrait de compte #ENV{extrait}'); ?> 
    ]
<?php
// On récupère la variable PDF pour déterminer si on est dans un PDF ou non
$pdf = '#ENV{pdf}';
// Si on est pas dans un PDF, on affiche le bouton pour télécharger le PDF
if ($pdf != 1) {
	echo icone_inline(_T('gestion:telecharger_pdf'), generer_url_ecrire('gestion_dompdf_exec', 'extrait=#ENV{extrait}&modele=liste_extraits'), 'doc-24.gif', '', 'right');
}
?>

[(#ENV{pdf}|non)
<form method="POST">
<input type="text" required name="extrait" value="#ENV{extrait}" size="8" maxlength="8"> <input type="submit">
</form>
]
#SET{totalgeneral,0}
<B_action>
<BOUCLE_action(auteurs_articles articles){extrait_de_compte LIKE %#ENV{extrait}%}{par id_activity}>
<BOUCLE_action2(articles){id_activity}{unique}{par id_activity}>
<h2>#IDACT - [(#TITRE|supprimer_numero)]</h2>
	#SET{total,0}
	<B_extrait>
	<table class="arial2" cellpadding="2" cellspacing="0" style="width: 100%; border: 0px;">
    	<tr>
       	 	<th align="left">Extrait</th>
		<th align="left">Somme</th>
        	<th align="left">Tiers</th>
        	<th align="left">Remarques gestion</th>
    	</tr>
		[(#REM) On calcule la pagination. Si PDF, pas de pagination. ]
		<BOUCLE_extrait(auteurs_articles articles auteurs){extrait_de_compte LIKE %#ENV{extrait}%}{id_activity}{pagination #ENV{pagination}}>
		[<p class="pagination">(#PAGINATION)</p>]
  		#SET{somme,(#HISTORIQUE_PAYEMENT|totalextrait{#EXTRAIT_DE_COMPTE,#ENV{extrait}})}
   		[(#SET{total, #GET{total}|plus{#GET{somme}}})]
   		[(#SET{totalgeneral, #GET{totalgeneral}|plus{#GET{somme}}})]
  
   		<tr class="valigntop">
       		<td>
			[(#HISTORIQUE_PAYEMENT|extraitsomme{#EXTRAIT_DE_COMPTE,#ENV{extrait}})]
		</td>
        		<td>#NOM #PRENOM</td>
        		<td>#REMARQUES_GESTION</td>
    		</tr>
		</BOUCLE_extrait>
	</table>
	<h4>Total action : [(#GET{total}|replace{\.,','})]&nbsp;€</h4>
</BOUCLE_action2>
</BOUCLE_action>
<h3><br>Total extrait : [(#GET{totalgeneral}|replace{\.,','})]&nbsp;€</h3>
</B_extrait>
</B_action2>
</B_action>
