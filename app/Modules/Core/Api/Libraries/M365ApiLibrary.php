<?php                                                                                   

/**
 * @description : Library to access API 365
 */


/***
 * Dans la base de données, pour chaque consiller leur usr_id de MS
 * ON va créer un drive pour rae, où seules le eprsonnel du rae peut accèder
 * Le système va lui au moment de l'accès au répertoire va vérifier si user_id peut y accèder
 * 
 *
 * Pour SAE, prévoir le mécanisme suivant:
 *          - Lors de la création d'un participant, le système va aussi créer un répertoire dans le drive et enregistrer le lien et id du dossier mS (afin de faire face au fait qu'un consiller le nom du répertoire)
 *          - Dans la fiche, le lien du répertoire sera disponible, en cliqunt sur ce lien les conseillers au drive et uploader dans le dossier, les fichiers du participant
 *          - Nom du repertoire sera NOM+PRENOM+id_personne
 
*/

/**
 * Pour chaque fonctionnalité, il est nécessaire de demander l'autorisation (par exemple, mail.read)
 * 1. On ajoute l'autorisation dans les autorisations de l'application (aller dans azure activte directory/inscription d'application dans menu latéral puis api autorise)
 * 2. L'administrateur doit donner son consentement
 * 3. On va dans application d'entreprise
 * 4. Choisir l'application concernée
 * 5. Dans l'application concernée, dans menu latéral, sécurité>autorisation
 * 
 * https://docs.microsoft.com/en-us/graph/api/drive-list?view=graph-rest-1.0&tabs=http
 * 
 * On peut explorer les graph sur https://developer.microsoft.com/en-us/graph/graph-explorer
 * Attention, par exemple, comme pour voir all item in my one dirve, il faut parfois activer les permissions dans l'onglet modify permission de graph explorer
 * 
 * On peut s'inspire de
 * https://github.com/OfficeDev/office-content/blob/master/rest-api/php/getting-started-Office-365-APIs-php.md
 * 
 */

namespace Api\Libraries;

use Api\Libraries\BaseApiLibrary;

Class M365ApiLibrary extends BaseApiLibrary
{

    protected $developers_url = 'https://developers.myoperator.co/';
    protected $token = '85db044d-c0f3-45a1-85e7-f3e4a8b47ba8';
    protected $client_id = 'c821e9a7-44c8-4ebc-a1f2-3efdd0567578'; //id de l'application azure application en cours - Ok
    protected $client_secret = '6te7Q~rMxq7T5q6hyn8o0sbk1kTWSSzxvUKqx'; //id de secret dans certification et secret d'azure application en cours - OK
    protected $scope = 'https%3A%2F%2Fgraph.microsoft.com%2F.default';
    protected $grant_type = 'client_credentials';
    protected $tenant = '1ed6344c-54e2-4ecb-82bd-f1e44435e3fd';	//pass partout, on le retrouve en allant sur tableau de bord azure propriété, en franàacis tenant = locataire
    protected $graph = 'https://graph.microsoft.com/v1.0/';
    
  
    //fonction pour avoir access token
    protected function getAccessToken()
    {
        $params['url'] = 'https://login.microsoftonline.com/'.$this->tenant.'/oauth2/v2.0/token';
        $params['header'] = array('Content-Type: application/x-www-form-urlencoded');
        $params['post'] = "client_id=".$this->client_id."&scope=".$this->scope."&client_secret=".urlencode($this->client_secret)."&grant_type=".$this->grant_type;

        //envoi de params via curl
        $response = $this->curl($params);
        return $response->access_token;
    }

    
}
