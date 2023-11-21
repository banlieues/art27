<?php

function supprimer_numero($texte) {
	return preg_replace(
	",^[[:space:]]*([0-9]+)([.)]|".chr(194).'?'.chr(176).")[[:space:]]+,S",
	"", $texte);
}

// Calcule la date déchéance de payement
function echeance($date, $plus = false) {
	if ($plus) return date('Y-m-d H:i:s',strtotime($date)+(3600*24*15));
	else return date('Y-m-d H:i:s',strtotime($date)-(3600*24*15));
}

// Calcule la date déchéance de début d'activité
function echeance_activite($date, $plus = false) {
	if ($plus) return date('Y-m-d H:i:s',strtotime($date)+(3600*24*31));
	else return date('Y-m-d H:i:s',strtotime($date)-(3600*24*31));
}

// Calcule l'age en fonction de la date de naissance
function age($date) {
    /*
    *   Cette adaptation est obligatoire si on utilise un format de date "Français", 
    *   strtotime ne supporte que le format d-m-Y et pas d/m/Y.
    */
    $date = str_replace('/', '-', $date);
	return (int) ((time() - strtotime($date)) / 3600 / 24 / 365);
}

// Calcule l'age au début de l'activité en fonction de la date de naissance (Jean-Paul)
function agedebut($date,$datedebut) {
    /*
    *   Cette adaptation est obligatoire si on utilise un format de date "Français", 
    *   strtotime ne supporte que le format d-m-Y et pas d/m/Y.
    */
    $date = str_replace('/', '-', $date);
    $datedebut = str_replace('/', '-', $datedebut);
	return (int) ((strtotime($datedebut) - strtotime($date)) / 3600 / 24 / 365);
}

// Retourne le montant totale payé par la personne. 
function calculer_payement ($str) {
	$str = explode(';', $str);
    $sum=array_sum($str);
    if(is_numeric($sum)): return $sum ; else: return 0; endif;
	
}

function calculer_solde($prix,$prix_organisme,$prix_etudiant,$prix_special,$type_part,$demandeur_emploi,$historique_payement,$is_html=TRUE)
{
    $sum_deja_payer=calculer_payement($historique_payement);
    //return $sum_deja_payer;
    //On calcule le prix à payper
    $prix_a_payer=calculer_prix_a_payer($prix,$prix_organisme,$prix_etudiant,$prix_special,$type_part,$demandeur_emploi);
    
    //return "$prix_a_payer vs $sum_deja_payer";

    $solde=$prix_a_payer-$sum_deja_payer;

    if($is_html):
        if($solde>0): $color="danger"; else: $color="success"; endif;           
        return "<span class='text-$color'>$solde</span>";
    endif;
    return $solde;

}


function calculer_prix_a_payer($prix,$prix_organisme,$prix_etudiant,$prix_special,$type_part,$demandeur_emploi)
{
    $prix_a_paye=trim($prix);
    if($type_part=="S"){ $prix_a_paye=trim($prix);}
    if($type_part=="I"){ $prix_a_paye=trim($prix_organisme);}
    if($demandeur_emploi=="oui"){ $prix_a_paye=trim($prix_etudiant);}

   if(!empty(trim($prix_special))){$prix_a_paye=$prix_special;}
    if(trim($prix_special==='0')){$prix_a_paye=$prix_special;}

    

    if(is_numeric($prix_a_paye)): return $prix_a_paye; else: return 0; endif;
    
}


// Cette fonction calcule l'intervale entre une date et maintenant
// La class php DateTime est présente dans PHP 5.2.
// La methode DateTime::diff demande PHP 5.3 !
function calculer_jour ($date1) {
	/* Création des deux objet dateTime */
	$d1 = new DateTime($date1);
	$d2 = new DateTime('now');

	/* Comparaison */
	$diff = $d1->diff($d2); 

	/* On retourne le nombre de jours total => http://be1.php.net/manual/fr/dateinterval.format.php */
	return $diff->format('%a');
}




/*
*   Fonction qui renvoie le code courtoisie en fonction du sexe de la personne.
*/
function code_courtoisie($sexe) {
    if ($sexe == 'Masculin') return 'Monsieur';
    elseif ($sexe == 'Féminin') return 'Madame';
    else return '';
}

/*
*   str_replace version SPIP
*/
function spip_replace($subject, $search, $replace) {
	return str_replace($search, $replace, $subject);
}

/*
*   html_entity_decode
*/
function decode_entities($str) {
	return html_entity_decode($str);
}

/*Converti la chaine du champ Ndiffusion en tableau utilisable...*/
function Ndiffusion2Array($liste) {
	$tab = explode(',', $liste);
	$chn = array();
	for ($k = 0, $g = 1; isset($tab[$k]); $k = ++$g, $g++)
		if ($tab[$g]) {
			$chn[$tab[$k]] = $tab[$g];
		}
	return $chn;
}

/*
*   Fonction qui transforme une chaine "payement" ou extrait de compte en ul>li
*/
function explode_payement($str, $sigle = '') {
    // On explode la chaine pour avoir un tableau
    $element = explode(';', $str);
    // Début de la chaine ul
    $output = '<ol>';
    // On ajoute les éléments
    foreach ($element as $key => $value) {
        // On évite de créer des li vide
        if (!empty($value)) $output .= '<li>'.$value.$sigle.'</li>';
    }
    // On ferme le ul
    $output .= '</ol>';

    return $output;
}

/*
*   Fonction qui transforme une chaine "groupe" en ul>li (Jean-Paul)
*/
function explode_groupe($str, $sigle = '') {
    // On explode la chaine pour avoir un tableau
    $element = explode(',', $str);
    $tableau_groupe = array 	(key  => FANIM,
    			    	key2 => SANTMENT,
    			    	key3 => ANIMBX,
    			    	key4 => BTA,
    			    	key5 => ECO,
    			    	key6 => EXTRA,
    			    	key7 => FQUAL,
    			    	key8 => GENRE,
    			    	key9 => HTA,
    			    	key10 => JEUX,
    			    	key11 => PART,
    			    	key12 => PETENF,
    			    	key13 => BTF,
    			    	key14 => ANIMPDQ,
    			    	key15 => ANIM,
    				key16 => FCOORD,);
    $tableau_groupe_humain = array 	(key  => 'Formation d\'animateurs',
    			    		key2 => 'Santé Mentale',
    			    		key3 => 'Animation Bruxelles',
    			    		key4 => 'Animation Brabant',
    			    		key5 => 'Ecole',
    			    		key6 => 'Extrascolaire',
    			    		key7 => 'Formations qualifiantes',
    			    		key8 => 'Genre',
    			    		key9 => 'Hainaut',
    			    		key10 => 'Jeux',
    			    		key11 => 'Participation',
    			    		key12 => 'Petite Enfance',
    			    		key13 => 'Formation Brabant',
    			    		key14 => 'Animation Plaines de Quartier',
    			    		key15 => 'Animation',
    					key16 => 'Formation de coordinateurs',);
    $element_converti = spip_replace($element,$tableau_groupe,$tableau_groupe_humain);
    // Début de la chaine ul
    $output = '<ol>';
    // On ajoute les éléments
    foreach ($element_converti as $key => $value) {
        // On évite de créer des li vide
        if (!empty($value)) $output .= '<li>'.$value.$sigle.'</li>';
    }
    // On ferme le ul
    $output .= '</ol>';

    return $output;
}

/*
*   Fonction qui affiche des extraits sélectionnés et le montant correspondant (Jean-Paul)
*/
function extraitsomme($somme,$extrait,$extraitchoisi) {
    // On explode la chaine pour avoir un tableau
    $elementsomme = explode(';', $somme);
    $elementextrait = explode(';', $extrait);
    $tableau_combine = array_combine($elementsomme, $elementextrait);
    $tableau_filtre = array_filter($tableau_combine, function ($element) use ($extraitchoisi) { return ($element == $extraitchoisi); } );
    // On ajoute les éléments
    foreach ($tableau_filtre as $valeursomme => $valeurextrait){
        // On évite de créer des li vide
        if (!empty($valeurextrait)) $output=$valeurextrait.'<td>'.$valeursomme.'&nbsp;€</td>';
    }

    return $output;
}

/*
*   Fonction qui calcule la somme des extraits sélectionnés (Jean-Paul)
*/
function totalextrait($somme,$extrait,$extraitchoisi) {
    // On explode la chaine pour avoir un tableau
    $elementsomme = explode(';', $somme);
    $elementsomme = str_replace(',','.',$elementsomme);
    $elementextrait = explode(';', $extrait);
    $tableau_combine = array_combine($elementsomme, $elementextrait);
    $tableau_filtre = array_filter($tableau_combine, function ($element) use ($extraitchoisi) { return ($element == $extraitchoisi); } );
    $tableau_filtre_flip = array_flip($tableau_filtre);
    $output = array_sum($tableau_filtre_flip);

    return $output;
}

/*
*   Nettoyage des BR dans les articles SPIP
*
*   Dieu seul sais pourquoi mais SPIP ajoute parfois des <p><br /></p> dans les *   articles, cela créer des lignes fântome atroce.
*/
function spip_fix_br($str) {
    // Si c'est aussi moche c'est parce qu'il faut garder cette syntaxe pour que le replace fonctionne.
    return str_replace('<p>
<br /></p>', '', $str);
}

/*
*   Cette fonction va convertir le code du statut suivi en quelques choses lisible pour un humain.
*   #SET{vtab, #ARRAY{T,A traiter,A,Annulé,C,Cemea,E,Encadrant,D,Désisté,I,Inscrit,L,Liste d'attente,R,Refusé,X,Réservé,B,Accompagnant,F,Facture}}]
*/
function statut_suivi($statut) {
    switch ($statut){
        case 'T':
            return 'A traiter';
            break;
        case 'A':
            return 'Annulé';
            break;
        case 'C':
            return 'Cemea';
            break;
        case 'E':
            return 'Encadrant';
            break;
        case 'D':
            return 'Désisté';
            break;
        case 'I':
            return 'Inscrit';
            break;
        case 'L':
            return "Liste d'attente";
            break;
        case 'R':
            return 'Refusé';
            break;
        case 'X':
            return 'Réservé';
            break;
        case 'F':
            return 'Facture';
            break;
        case 'B':
            return 'Accompagnant';
            break;
        case 'M':
            return 'Animateur';
            break;
        default:
            return 'Statut suivi inconnu !';
    }
}

/*
*   Cette fonction va convertir le code du ONE en quelques choses lisible pour un humain.
*   #SET{vtab, #ARRAY{T,A traiter,A,Annulé,C,Cemea,E,Encadrant,D,Désisté,I,Inscrit,L,Liste d'attente,R,Refusé,X,Réservé,B,Accompagnant,F,Facture}}]
*/
function statut_ONE($statut) {
$code = array("MA 10","MA 11","MA12","MA 1","MA 21","MA 22","MA 23","MA24","MA 25","MA 2","MA 3","MA 4","MA 5","MA 6","MA 7","MA 8","MA 9","MA 99","F 10","F 11","F 1","F 20","F 22","F 23","F 24","F 25","F 2","F 3","F 4","F 5","F 6","F 7","F 8","F 9","F 99",",");
$lisible   = array("SASPE","Service de garde d'enfants malades","Crèche parentale","Crèche","Halte garderie","Acceuil extrascolaire","Ecole de devoirs","Centres de vacances","Service d'acceuil spécilaisé","Prégardiennat","MCAE","Maison d'enfants","Acceuil conventionné à domicile","Acceuil autonome à domicile","Service d'acceuillantes conventionnés","Lieu de rencontre parents enfants","Halte-garderie","Autre","Assistant-e social","Parent en crèche parentale","Acceuillant-e","Acceuillant-e ou animateur-trice","Coordinateur-trice","Coordinateur-trice","Coordinateur-trice ATL","Responsable de gestion","Acceuillant-e","Acceuillant-e","Assistant-e social","Infirmier-e","Directeur-trice","Directeur-trice","Directeur-trice","Acceuillant-e","Autre"," en ");

$newstatut = str_ireplace($code, $lisible, $statut);
            return $newstatut;
}

/*
*   Cette fonction va convertir un statut payment.
*/
function statut_payement($statut) {
    switch ($statut) {
        case 1:
            return 'En attente';
            break;
        case 2:
            return 'Acompte payé';
            break;
        case 3:
            return 'Payement terminé';
            break;
        case 4:
            return 'A rembourser';
            break;
        case 5:
            return 'Facture';
            break;
        case 6:
            return 'Payement annulé';
            break;
        default:
            return 'Statut du payement inconnu';
    }
}

/*
*   Cette fonction va convertir un nombre en lettres.
*/
function nombre2lettre($nombre) {
    switch ($nombre) {
        case 10:
            return 'dix';
            break;
        case 20:
            return 'vingt';
            break;
        case 30:
            return 'trente';
            break;
        case 40:
            return 'quarante';
            break;
        case 50:
            return 'cinquante';
            break;
        case 60:
            return 'soixante';
            break;
        case 70:
            return 'septante';
            break;
        case 80:
            return 'quatre-vingt';
            break;
        case 90:
            return 'nonante';
            break;
        case 100:
            return 'cent';
            break;
        case 110:
            return 'cent-dix';
            break;
        case 120:
            return 'cent-vingt';
            break;
        case 130:
            return 'cent-trente';
            break;
        case 140:
            return 'cent-quarante';
            break;
        case 150:
            return 'cent-cinquante';
            break;
        case 160:
            return 'cent-soixante';
            break;
        case 170:
            return 'cent-septante';
            break;
        case 180:
            return 'cent-quatre-vingt';
            break;
        case 190:
            return 'cent-nonante';
            break;
        case 200:
            return 'deux-cent';
            break;
        default:
            return 'Chiffre inconnu';
    }
}

function sql_hex($val, $serveur='', $option=true)
{
	$f = sql_serveur('hex', $serveur,  $option==='continue' OR $option===false);
	if (!is_string($f) OR !$f) return false;
	return $f($val);
}

function sql_quote($val, $serveur='', $type='')
{
    return "'$val'";
	$f = sql_serveur('quote', $serveur, true);
	if (!is_string($f) OR !$f) $f = '_q';
	return $f($val, $type);
}

function propre($value)
{
    return $value;
}

function affdate($date)
{
    return $date;
}


?>
