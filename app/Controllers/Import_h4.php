<?php

namespace App\Controllers;
use Components\Libraries\Hash;
use DataQuery\Models\DataQueryModel;

class Import_h4 extends BaseController
{

    public function __construct()
    {
        $this->db=db_connect();

    }

    public function mdp_encodage()
    {
        echo $this->decrypt("SiJ/UBx646w6Qel4syp1Kg==");die();
        //On crée le champs password_old
        if (!$this->db->fieldExists('password_old', 'user_accounts')) 
        {
            $this->db->query("ALTER TABLE `user_accounts` ADD `password_old` VARCHAR(255) NOT NULL AFTER `password`; ");
            $this->db->query("UPDATE user_accounts SET password_old=password");
        }

        $builder=$this->db->table("user_accounts");
        $builder=$builder->select("id,password,password_old");
        $builder->where("id<>",2);
    
        $users=$builder->get()->getResult();

        //echo $password = Hash::make("Azerty1@noos66");
       foreach($users as $user)
        {
            if (strpos($user->password, "$2y$10") !== FALSE) 
            {
                
            }
            else
            {
                $data["password"]=Hash::make($this->decrypt($user->password_old));
                $builder=$this->db->table("user_accounts");
                $builder->where("id",$user->id);
                
                $builder->update($data);

            }
          

        }
    }


    private function decrypt( $data, $key="banlieuesconquerantdelalumiere" ) {
	    $salt = 'cH!swe!retReGu7W6bEDRup7usuDUh9THeD2CHeGE*ewr4n39=E@rAsp7c-Ph@pH';
	    $iv_size = openssl_cipher_iv_length( "AES-256-CBC" );
	    $hash = hash( 'sha256', $salt . $key . $salt );
	    $iv = substr( $hash, strlen( $hash ) - $iv_size );
	    $key = substr( $hash, 0, 32 );
	    $decrypted = openssl_decrypt( base64_decode( $data ), "AES-256-CBC", $key, OPENSSL_RAW_DATA, $iv );
	    $decrypted = rtrim( $decrypted, "\0" );

	    return $decrypted;
	}


    public function traduire_query()
    {

            $dataQuery=new DataQueryModel();

            $queries=$dataQuery->list_queries();

                    debug($queries);



               /* $query_depart="
                
                @@array[ou_et]=0@@array[entity]=demande@@array[champ]=date_demande@@array[operateur]=superieur@@array[choice]=01/01/2020@@array[par_ouvert]=0@@array[par_ferme]=0@@begin@@array[ou_et]=AND@@array[entity]=demande@@array[champ]=date_demande@@array[operateur]=inferieur@@array[choice]=01/01/2021@@array[par_ouvert]=0@@array[par_ferme]=0@@begin@@array[ou_et]=AND@@array[entity]=demande_detail@@array[champ]=demande_id_prime@@array[operateur]=contient@@array[choice_is_array]=4@@array[par_ouvert]=0@@array[par_ferme]=0
                
                ";*/

            foreach($queries as $query_sql)
            {
                $query_depart=$query_sql->string_where;
                $query_explode=explode("[ou_et]",$query_depart);

                debug($query_explode);

                $uri=NULL;

                foreach($query_explode as $key=>$element)
                {
                        //$part=NULL;
                        if($key>0)
                        {
                            $query_explode[$key]="ou_et_##$key". $query_explode[$key];
                            $query_explode[$key]=str_replace("@@array","&",$query_explode[$key]);
                            
                            $query_explode[$key]=str_replace("[choice_is_array]","##$key##_valueDD",$query_explode[$key]);
                            $query_explode[$key]=str_replace("[choice]","##$key##_value",$query_explode[$key]);

                            $query_explode[$key]=str_replace("]","_##".$key,$query_explode[$key]);
                            $query_explode[$key]=str_replace("[",NULL,$query_explode[$key]);
                            $query_explode[$key]=str_replace("valueDD","value[]",$query_explode[$key]);
                            $query_explode[$key]=str_replace("@@begin&",NULL,$query_explode[$key]);

                        

                        }
                        else
                        {
                            unset( $query_explode[$key]);
                        }
                }


                $query_final=implode("&",$query_explode);
                $query_final=trim($query_final);
                $query_final=$query_final."&number=".count($query_explode);

                $query_encode=urlencode($query_final);

                $query_encode=str_replace("%3D","=",$query_encode);
                $query_encode=str_replace("%26","&",$query_encode);

                $query_encode=$query_encode."&fields_select%5B%5D=nom_personne";

                $query_encode=str_replace("demande_detail","demande_caracteristique",$query_encode);

                //on ajoute les fields
                $fields_treat=explode(",",$query_sql->field);
                $fields_treat=implode("&fields_select[]=",$fields_treat);

                $query_encode=$query_encode."&".$fields_treat;

                $builder=$this->db->table("user_requete");
                $builder->where("id_requete",$query_sql->id_requete);
                $data["uri"]=$query_encode;
                $builder->update($data);


            }


        }

       /* public function get_index_possible()
        {
            $dataQuery=new DataQueryModel();
            $queries=$dataQuery->list_queries();

            $f=[];

            //debug($queries);

            foreach($queries as $query)
            {
                $fields_treat=explode(",",$query->field);

                foreach($fields_treat as $field)
                {
                    array_push($f,$field);
                }
            }

            $f=array_unique($f);
            $manquant=[];

           $descriptor=$dataQuery->getDescriptorIndexByFields();

           foreach($f as $index)
           {
                if(!isset($descriptor[$index]))
                {
                        array_push($manquant,$index);
                }
           }

           debug($manquant);
        }*/


      /*  public function is_modelisation()
        {
            $dataQuery=new DataQueryModel();

            $fields=[

                "encodage_date",
	"encodage_user_create",
	"encodage_moyen_contact",
	"encodage_origine_default",
	"encodage_demande_type",
	"encodage_rel_personne_bien",
	"encodage_contact_duree_default",
	"encodage_contact_premier_default",
	"encodage_langue",
	"encodage_cp",
	"encodage_nombre",
        "count_encodage",
                "id_demande_id",
                "date_demande",
                
                "id_demande_statut",
                
                "utilisateur_createur",
                "utilisateur_demande",
                "demande_pole",
                "date_attribution",
                "date_assignation",
                "utilisateur_demande_2",
                "nom_demande",
                "descriptif_demande",
                "demande_moyen_contact",
                "demande_contact_duree_default",
                "demande_contact_premier_default",
                "demande_origine_default",
                    "objet_note_interne",
                "note_interne",
                "demande_date_cloture",
                    "count_demande",
                    "id_type_demande",
                    "id_type_suivi_accompagnement",
                    "id_type_info_conseil",
                       "demande_id_visite",
                         "demande_id_prime",
                     "demande_id_type_accompagnement",
                     
                     "demande_id_thematique_principal",
                     "demande_id_thematique_secondaire",
                     "demande_id_th_acoustique",
                     "demande_id_th_energie",
                      "demande_id_ths_isolation",
                     "demande_id_ths_energie_renouvelable",
                     "demande_id_ths_peb",
                     "demande_id_th_logement",
                     "demande_id_ths_aide_locative",
                     "demande_id_ths_aide_achat",
                     "demande_id_ths_aide_juridique",
                     "demande_id_ths_insalubrite",
                     "demande_id_ths_logement_inoccupe",
                     "demande_id_th_patrimoine",
                     "demande_id_ths_petit_patrimoine",
                     "demande_id_th_renovation",
                     "demande_id_th_urbanisme",
                       "demande_id_ths_batiment_durable",
                       
                     "demande_th_regularisation",
                     "demande_nb_coproprio",
                     "demande_id_th_ag",
                       "demande_id_ths_petit_patrimoine_acc",
                     //"demande_id_th_visite",
                     "demande_id_type_projet",
                     "demande_id_objet_pvb",
                     "demande_date_pvb",
                     "demande_id_credit_pvb",
                     "demande_id_revenu",
                     "demande_id_objet_permis",
                     "demande_id_raison_permis",
                     //"demande_id_patrimoine",
                       
                     "demande_id_geste",
                     "demande_id_intervention",
                       "demande_id_intervention_non",
                     "demande_id_travaux",
                       "demande_id_accompagnement",
                       "id_personne",
    "is_professionel_pro",
    "prenom_personne",
    "nom_personne",
	"utilisateur_createur_personne",
    "langue_personne",
    "civilite_personne",
	"email_personne",
	    
		"telephone_personne",
	
	"localite_personne",
	"pays_personne",
	"adresse_personne",
	
    "organisme_nom",
		//"organisme_service",
		//"fonction_organisme",
		//"type_organisme",
	     //"web_personne_pro",
	     //"lien_homegrade_pro",
	     //"remarque_pro",
	     //"is_point_pro",
	     //"is_vip_pro",
	"rel_personne_bien"
	,
	"date_insert_personne",
	"nb_demande_ouverte",
   "count_personne",
   "id_bien",
	"adresse_fr_bien",
	"adresse_nl_bien",
	"adresse_fr_cp",
	"adresse_bt",
	"utilisateur_createur_bien",
	//"coordonnee_bien",
	"id_type_bien_coche",
	"etage_logement_bien",
	"id_nombre_bien",
	"id_chauffage_bien",
	"nombre_habitant",
	"surface_chauffee",
	"nombre_facade",
	"id_type_chauffage",
       "id_type_cuisiniere",
       "id_type_four",
       "id_type_eau",
       "consommation_gaz",
       "consommation_electricite",
       "annee",
       "id_certificat_peb",
       "edrlr_bien",
       "zru_bien",
       "contrat_bien",
       "ppas_bien",
       "reglement_bien",
       "zone_protection_bien",
       "monument_bien",
       "zone_zichee_bien",
   "date_insert_bien",
        "count_bien",
           
	"id_rdv",
	"user_rdv_multi",
	"type_rdv",
	"titre_rdv",
	"statut_rdv",
	"date_rdv_debut",
	"date_rdv_fin",
	"duree_rdv",
	"temp_avant_rdv",
	"temp_apres_rdv",
	"lieu_rdv",
	"is_prive_rdv",
	"note_rdv_direct",
         "count_rdv",
         "id_tache",
	"user_tache",
	"type_tache",
	"type_tache_libre",
	"sujet_tache",
	"statut_tache",
	"date_tache",
	"echeance",
	"is_rappel",
	"date_tache_rappel",   

	"note_tache_direct",
	"is_prive",
         "count_tache",

         "id_email",
	"email_created_date",
	"email_received_date",
	"email_send_date",
	"email_sender",
	"email_subject",
	"email_to",
	"email_cc",
	"email_bcc",
             "count_email",

            ];

            $manquant=[];

            $descriptor=$dataQuery->getDescriptorIndexByFields();
 
            foreach($fields as $index)
            {
                $builder=$this->db->table("ban_fields");
                $builder->where("field_index",$index);
                $data["is_modelisation"]=1;
                $data["is_requete"]=1;
                $builder->update($data);
            }
 
           



        }*/
}