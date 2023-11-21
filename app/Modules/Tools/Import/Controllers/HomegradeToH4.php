<?php

namespace Import\Controllers;

use Base\Controllers\BaseController;

use CodeIgniter\Database\RawSql; //necessaire pour passer current_timestamp

use Components\Libraries\Hash;
use DataQuery\Models\DataQueryListModel;

class HomegradeToH4 extends BaseController
{
    public $db=NULL;
    public $db_old=NULL;

    public $name_db="h4";
    public $name_db_old="h4_model";

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        $this->db = \Config\Database::connect();
        //$this->db_old=\Config\Database::connect("old");
    }

 /*   public function Import_jer()
    {
        //on efface les tables dont nous n'avons pas besoin
        $forge = \Config\Database::forge("old");

       

        $tables=$this->db->listTables();
        //on copie les tables ban
        foreach($tables as $table)
        {
            if(strpos($table,"ban_")===0)
            {
                $this->copy_table_to_old($table);
            }
        }

        $tables_copy=[
            "bien_geocode",
            "config_cropper_settings",
            "list_price_origin",
            "list_role",
         
            "list_type_personne",
            "list_user_genders",
            "list_user_roles",
            "settings_cropper",
            "mt_template",
            "crm_list_categorie_profil_contact"
        ];
        //je copie bien_geocode
        foreach($tables_copy as $table_copy)
        {
            $this->copy_table_to_old($table_copy);
        }
        

        //je copie config_cropper_settings


        //On s'occupe de transofmer personne en contact
        $this->transform_personne_to_contact();
    
      

        //J'efface les tables inutlies
        $olds=  [ 
                    "bien_no_corriger",
                    "demande_caracteristique_original_2",
                    "demande_caracteristique_original",
                    "em_emodel_by_lang_old",
                    "en_answer_old",
                    "rp_file_old",
                    "rp_report_old",
                    "rp_report_section_old",
                    "rp_section_old",
                    "rp_section_tag_old",
                    "rp_tag_old",
                    "ban_components_activities_old",
                    "list_template_document_statut",
                    "answer_enquete",
                    "demande_bien",
                    "demande_personne",
                    "personne_bien_no_good",
                    "crm_case",
                    "crm_contact",
                    "crm_email",
                    "crm_email_text",
                    "question_enquete",
                    "zefficy_action",
                    "zefficy_bien",
                    "zefficy_demande",
                    "zefficy_liaison",
                    "zefficy_mail",
                    "zefficy_mail_liaison",
                    "zefficy_personne",
                    "zzz_tmp_user_efficy",
                    "z_suite_accounts",
                    "z_suite_accounts_cases",
                    "z_suite_accounts_contacts",
                    "z_suite_cases",
                    "z_suite_cases_cstm",
                    "z_suite_contacts",
                    "z_suite_contacts_cases",
                    "z_suite_documents",
                    "z_suite_documents_accounts",
                    "z_suite_documents_cases",
                    "z_suite_documents_contacts",
                    "z_suite_email",
                    "z_suite_emails_email_addr_rel",
                    "z_suite_emails_text",
                    "z_suite_email_addresses",
                    "z_suite_email_addr_bean_rel"


        ];

        $forge = \Config\Database::forge("old");
        foreach($olds as $old)
        {
            
                $forge->dropTable($old,true);
            
        }

        //Modificaiton diverses liés à des champs manquants ou mal nommé
        if(!$this->db_old->fieldExists("id_demande_caracteristique","demande_caracteristique"))
        {
            $forge->modifyColumn('demande_caracteristique', ["id"=>["name"=>"id_demande_caracteristique",'type'=>"INT","constraint"=>11,"auto_increment"=>true]]);

        }

        if(!$this->db_old->fieldExists("id_demande_statut","demande"))
        {
            $forge->modifyColumn('demande', ["Id_demande_statut"=>["name"=>"id_demande_statut",'type'=>"INT"]]);

        }
        
        if(!$this->db_old->fieldExists("id_contact","encodage"))
        {
            $forge->modifyColumn('encodage', ["id_personne"=>["name"=>"id_contact",'type'=>"INT"]]);

            $forge->addColumn('encodage', ["id_contact_profil"=>['type'=>"INT","constraint"=>11,"after"=>"id_contact"]]);
            $forge->addKey("id_contact_profil");
            $forge->processIndexes('encodage');
            $this->db_old->query("UPDATE encodage SET id_contact_profil=id_contact");

        }

        if(!$this->db_old->fieldExists("id_contact","personne_bien"))
        {
            $forge->modifyColumn('personne_bien', ["id_personne"=>["name"=>"id_contact",'type'=>"INT"]]);

            $forge->addColumn('personne_bien', ["id_contact_profil"=>['type'=>"INT","constraint"=>11,"after"=>"id_contact"]]);
            $forge->addKey("id_contact_profil");
            $forge->processIndexes('personne_bien');
            $this->db_old->query("UPDATE personne_bien SET id_contact_profil=id_contact");

        }

        if(!$this->db_old->fieldExists("is_actif","liste_localite"))
        {
            $forge->addColumn('liste_localite', ["is_actif"=>['type'=>"INT","constraint"=>11,"null"=>false,"default"=>1]]);
        }

        if(!$this->db_old->fieldExists("is_actif","liste_pays"))
        {
            $forge->addColumn('liste_pays', ["is_actif"=>['type'=>"INT","constraint"=>11,"null"=>false,"default"=>1]]);
        }

        //On s'occupe de document_upload
        if(!$this->db_old->tableExists("document_upload"))
        {
            
            $forge->renameTable("email_demande_depots","document_upload");   
        }

       

        if($this->db_old->tableExists("document_upload")&&!$this->db_old->tableExists("document_upload_lien"))
        {
           
            $this->copy_table_to_old("document_upload_lien",false);

            
            $forge->addKey("id_document");
    
            $forge->processIndexes('document_upload_lien');

            $forge->addKey("id_demande");
    
            $forge->processIndexes('document_upload_lien');
            
        }

       

        if($this->db_old->tableExists("document_upload")&&$this->db_old->tableExists("document_upload_lien"))
        {
           
            $document_upload=$this->db_old->query("select * from document_upload_lien")->getResult();

            if(empty($document_upload))
            {
                $this->db_old->query("INSERT INTO 
                    document_upload_lien
                    (id,id_document,id_message,id_demande,id_user,date_created,date_echeance)
                     SELECT id, id,id_message,id_demande,id_user,date_created, date_echeance FROM document_upload
                    ");
            }
            else
            {
                echo "<li> document upload pas vide </li>";
            }
            
        }



        $this->rapport_table_new();
        $this->rapport_table_old();

      


    }*/

    //a ecxecuter sur le serveur en production?
    public function create_index()
    {

        $forge=\Config\Database::forge();

        if(!$this->db->fieldExists("mime","document_upload"))
        {
            $forge->addColumn('document_upload', ["mime"=>['type'=>"VARCHAR","constraint"=>255,"null"=>true]]);

            
        }

       

       if(!$this->db->fieldExists("id_message_old","document_upload"))
        {
            $forge->modifyColumn('document_upload', ["id_message"=>["name"=>"id_message_old",'type'=>"INT"]]);

            
        }

        if(!$this->db->fieldExists("id_demande_old","document_upload"))
        {
            $forge->modifyColumn('document_upload', ["id_demande"=>["name"=>"id_demande_old",'type'=>"INT"]]);

            
        }

       $this->set_table_h();


       $this->create_index_demande_caracteristique();

       $this->modify_index();

       echo "done";
    }


    public function modify_index()
    {
        $forge=\Config\Database::forge();
        $forge->modifyColumn('bien', ["adresse_fr_cp"=>["name"=>"adresse_fr_cp",'type'=>"INT"]]);
        $forge->modifyColumn('bien', ["adresse_nl_cp"=>["name"=>"adresse_nl_cp",'type'=>"INT"]]);
        $forge->modifyColumn('liste_localite', ["cp"=>["name"=>"cp",'type'=>"INT"]]);

        $changes_bien=["id_type_chauffage",
                "id_type_cuisiniere",
                "id_type_four",
        "id_type_eau"];

        foreach($changes_bien as $change_bien)
        {
            $forge->modifyColumn('bien', [$change_bien=>["name"=>$change_bien,'type'=>"INT"]]);
        }
    }

    public function set_table_h()
    {
        $forge=\Config\Database::forge();




        $tables_historique=["demande_h","contact_h","bien_h","rdv_h","tache_h"];

        foreach($tables_historique as $table_h)
        {
            $keys = $this->db->getIndexData($table_h);
            $keys_present=[];
            $is_process=false;

            foreach($keys as $key)
            {
                array_push($keys_present,$key->name);
            }

            if(!in_array("index",$keys_present))
            {
                $forge->addKey("index");
                $is_process=true;
            }
            if(!in_array("id_user",$keys_present))
            {
                $forge->addKey("id_user");
                $is_process=true;
            }
            if(!in_array("id_entity",$keys_present))
            {
                $forge->addKey("id_entity");
                $is_process=true;
            }
            if(!in_array("key_primary",$keys_present))
            {
                $forge->addKey("key_primary");
                $is_process=true;
            }
         
            if($is_process)
            {
                $forge->processIndexes($table_h);
            }
        }
    }

    public function create_index_demande_caracteristique()
    {
        $forge=\Config\Database::forge();




        $tables_historique=["demande_caracteristique"];

        foreach($tables_historique as $table_h)
        {
            $keys = $this->db->getIndexData($table_h);
            $keys_present=[];
            $is_process=false;

            foreach($keys as $key)
            {
                array_push($keys_present,$key->name);
            }

            //récuperer tous les champs
            $fields = $this->db->getFieldNames($table_h);

            foreach ($fields as $field) 
            {
                if(!in_array($field,$keys_present))
                {
                    $forge->addKey($field);
                    $is_process=true;
                }
            }

        
         
            if($is_process)
            {
                $forge->processIndexes($table_h);
            }
        }
    }

    public function copy_table_to_old($table,$with_data=true)
    {
        //$tables_old=$this->db_old->listTables();

        //j'appelle la forge
        $forge = \Config\Database::forge("old");


        if($this->db_old->tableExists($table))
        {
            $forge->dropTable($table);
        }

        echo "<li>$table</li>";

        //connaitre les champs

        if($this->db->tableExists($table))
        {
            $metadatas=$this->db->getFieldData($table);
            $fields = $this->fields_metadata_to_fields_forge($metadatas);
            
            //connaite les index
            $keys = $this->db->getIndexData($table);
            $primary_key=$this->traque_primary_key($keys);
            $index_keys=$this->traque_index_key($keys);
        

            //je forge
            $forge->addField($fields);
            $forge->addPrimaryKey($primary_key);
            if(!empty($index_keys))
            {
                foreach($index_keys as $index_key)
                {
                    $forge->addKey($index_key);
                }
            }
            //$forge->addKey($indexes);
            $forge->createTable($table);

            //on crée la table avec la forge
            echo "<li>J'ai crée la table $table</li>";

            //On cope les données
            if($with_data)
            {
                $this->db_old->query("INSERT INTO $this->name_db_old.$table SELECT * FROM $this->name_db.$table");
            }
        }

    }

    //on s'occupe ici des contacts, les tables personnes deviennent des tables contacts
    public function transform_personne_to_contact()
    {
        $forge = \Config\Database::forge("old");
        //1. On créé la table contact
        if($this->db_old->tableExists("personne"))
        {
            //effacer table

            $tables_contact=["contact","contact_profil","contact_h","contact_tache","contact_rdv"];

            foreach($tables_contact as $table)
            {
                $forge->dropTable($table,true);
            }
            


            $forge->renameTable("personne","contact");

           
        }

        if($this->db_old->fieldExists("id_personne","contact"))
        {
            $forge->modifyColumn('contact', ["id_personne"=>["name"=>"id_contact",'type'=>"INT","constraint"=>11,"auto_increment"=>true]]);
        }

        if($this->db_old->fieldExists("nom","contact"))
        {
            $forge->modifyColumn('contact', ["nom"=>["name"=>"nom_contact",'type'=>"VARCHAR","constraint"=>255]]);
        }

        if($this->db_old->fieldExists("prenom","contact"))
        {
            $forge->modifyColumn('contact', ["prenom"=>["name"=>"prenom_contact",'type'=>"VARCHAR","constraint"=>255]]);
        }
        

        

        //2. ON crrée la table contact_profil

        if(!$this->db_old->tableExists("contact_profil"))
        {
            
            //connaitre les champs
            $metadatas=$this->db_old->getFieldData("contact");
            $fields = $this->fields_metadata_to_fields_forge($metadatas);
            
            //connaite les index
            $keys = $this->db_old->getIndexData("contact");
            $primary_key=$this->traque_primary_key($keys);
            $index_keys=$this->traque_index_key($keys);
        

            //je forge
            $forge->addField($fields);
            $forge->addPrimaryKey($primary_key);
            if(!empty($index_keys))
            {
                foreach($index_keys as $index_key)
                {
                    $forge->addKey($index_key);
                }
            }
            //$forge->addKey($indexes);
            $forge->createTable("contact_profil");

            //on crée la table avec la forge
            echo "<li>J'ai crée la table contact_profil</li>";

            //On cope les données

            $this->db_old->query("INSERT INTO contact_profil SELECT * FROM contact");

            //on crée un id_contact_profil
       
            $forge->modifyColumn('contact_profil', ["id_contact"=>["name"=>"id_contact_profil",'type'=>"INT","constraint"=>11,"auto_increment"=>true]]);
  
        
            //On ajouter la colonne id_contact
        
            $forge->addColumn('contact_profil', ["id_contact"=>['type'=>"INT","constraint"=>11,"after"=>"id_contact_profil"]]);
            $forge->addKey("id_contact");
    
            $forge->processIndexes('contact_profil');
            $this->db_old->query("UPDATE contact_profil SET id_contact=id_contact_profil");

           /* $field_delete_contact_profil=["nom_contact","prenom_contact","id_langue","id_civilite"];

            $forge->dropColumn('contact_profil', $field_delete_contact_profil);*/

           /* $field_delete_contact=[
                "is_profesionnel"
            ];

            $forge->dropColumn('contactl', $field_delete_contact);*/



        }

        //ajoute le champ contact_profil_categorie

        if(!$this->db_old->fieldExists("contact_profil_categorie","contact_profil"))
        {
            $forge->addColumn("contact_profil",["contact_profil_categorie"=>["type"=>"TEXT",'null' => true]]);

        }
       


        //on supprime les colonnes en trop
        $fields_contact=$this->get_name_field($this->db->getFieldData("contact"));
        $fields_contact_old=$this->get_name_field($this->db_old->getFieldData("contact"));

        echo "<h1>A effacer</h1>";
        foreach($fields_contact_old as $field_contact_old)
        {
            if(!in_array($field_contact_old,$fields_contact))
            {
                echo "<li>je dois effacer $field_contact_old";
                $forge->dropColumn("contact",$field_contact_old);
            }

        }

        echo "<h1>N'est pas dans old</h1>";
        foreach($fields_contact as $field_contact)
        {
            if(!in_array($field_contact,$fields_contact_old))
            {
                echo "<li> $field_contact n'est pas dans old";
            }

        }
        

        //on change le nom pour personne_rdv
        if($this->db_old->tableExists("personne_rdv"))
        {
            $forge->renameTable("personne_rdv","contact_rdv");
            $forge->modifyColumn('contact_rdv', ["id_personne_rdv"=>["name"=>"id_contact_rdv",'type'=>"INT","constraint"=>11,"auto_increment"=>true]]);
            $forge->modifyColumn('contact_rdv', ["id_personne"=>["name"=>"id_contact",'type'=>"INT","constraint"=>11]]);

            $forge->addColumn('contact_rdv', ["id_contact_profil"=>['type'=>"INT","constraint"=>11,"after"=>"id_contact"]]);
            $forge->addKey("id_contact_profil");
            $forge->processIndexes('contact_rdv');
            $this->db_old->query("UPDATE contact_rdv SET id_contact_profil=id_contact");
        }


        //on change le nom pour personne_tache
        if($this->db_old->tableExists("personne_tache"))
        {
            $forge->renameTable("personne_tache","contact_tache");
            $forge->modifyColumn('contact_tache', ["id_personne_tache"=>["name"=>"id_contact_tache",'type'=>"INT","constraint"=>11,"auto_increment"=>true]]);
            $forge->modifyColumn('contact_tache', ["id_personne"=>["name"=>"id_contact",'type'=>"INT","constraint"=>11]]);

            $forge->addColumn('contact_tache', ["id_contact_profil"=>['type'=>"INT","constraint"=>11,"after"=>"id_contact"]]);
            $forge->addKey("id_contact_profil");
            $forge->processIndexes('contact_tache');
            $this->db_old->query("UPDATE contact_tache SET id_contact_profil=id_contact");
        }
       
        if($this->db_old->tableExists("personne_h"))
        {
            $forge->renameTable("personne_h","contact_h");

        }


        $this->nettoye_champ_contact();

    } 
    
    public function nettoye_champ_contact()
    {
        $forge = \Config\Database::forge();

        $fields_contact=[
            "id_contact",
            "id_type_personne",
            "nom_contact",
            "prenom_contact",
            "id_langue",
            "id_civilite",
            "date_insert",
            "date_modification",
            "id_user",
            "id_user_create"
        ];


        $fields_a_supprimer_contact_profil=
        [
            "nom_contact",
            "prenom_contact",
            "id_langue",
            "id_civilite",
            "is_anonyme",
            "ref_anonyme",
            "ref_contact",
            "is_delete",
            "orig",
            "personne_id_crm"

        ];

        //On s'occupe de contact

        $fields = $this->db->getFieldNames('contact');
        foreach ($fields as $field) 
        {
            if(!in_array($field,$fields_contact))
            {
                $forge->dropColumn('contact', $field);
            }

          
        }

        //On s'occuper de contact_profil
        foreach( $fields_a_supprimer_contact_profil as $field)
        {
            if($this->db->fieldExists($field,"contact_profil"))
            {
                $forge->dropColumn('contact_profil', $field);
            }
        }
        
      
       
    }

    //function utilitaire qui sort le nom des champs d'une table
    public function get_name_field($fields)
    {
        $fields_name=[];

        foreach($fields as $field)
        {
            array_push($fields_name,$field->name);
        }

        return $fields_name;
    }

    //fonction utilitaire pour la forge, permet de convertir metadata d'une table existante en champ à crée pour une forge

    public function fields_metadata_to_fields_forge($fields_metadata)
    {
        $fields_forge=null;

       //debug($fields_metadata);
        foreach($fields_metadata as $field)
        {
            unset($metadata_forge);
           // echo "<h1>$field->type est $field->name </h1>";
            $name_field=$field->name;
           

            $metadata_forge["type"]=strtoupper($field->type);

            if($metadata_forge["type"]=="ENUM")
            {
                $metadata_forge["type"]="VARCHAR";
                $metadata_forge["constraint"]=10;
            }

            if(isset($field->max_length)&&!empty($field->max_length)) 
            {
                $metadata_forge["constraint"]=$field->max_length;
            }

            if(isset($field->nullable)&&!empty($field->nullable)) 
            {
                $metadata_forge["null"]=$field->nullable;
            }

            if(isset($field->default)&&!empty($field->default)) 
            {
                if($field->type=="timestamp"||$field->type=="datetime"||$field->type=="date")
                {
                    $metadata_forge["default"]=new RawSql($field->default);
                 
                }
                else
                {
                    $metadata_forge["default"]=$field->default;
                }
                

            }

            if(isset($field->primary_key)&&$field->primary_key==1) 
            {
                $metadata_forge["auto_increment"]=true;
            }

            $fields_forge[$name_field]=$metadata_forge;   
            
        }

        //debug($fields_forge);
        return $fields_forge;
    }


    //trouve la clé primair de la table à copier
    public function traque_primary_key($keys)
    {
        foreach($keys as $type=>$key)
        {
            if($type=="PRIMARY")
            {
                return $key->fields;
            }
        }

    }


    //trouve les index à créer pour la table à copier
    public function traque_index_key($keys)
    {
        foreach($keys as $key)
        {
            if($key->type=="INDEX")
            {
                return $key->fields;
            }
        }

    }



    //deux foncitons qui affichant les tables qui manquant

    public function rapport_table_new()
    {
        $tables=$this->db->listTables();
        $tables_old=$this->db_old->listTables();

        echo "<h1>Table du new model qui n'existe pas dans old</h1>";
        foreach($tables as $table)
        {
            if(!in_array($table,$tables_old))
            {
                echo "<li>$table</li>";
            }
        }
    }

    public function rapport_table_old()
    {
        $tables=$this->db->listTables();
        $tables_old=$this->db_old->listTables();


        echo "<h1>Table de old qui n'existe pas dans new model</h1>";
        foreach($tables_old as $table_o)
        {
            if(!in_array($table_o,$tables))
            {
                echo "<li>$table_o</li>";
            }
        }



    }

    //rapport complet

    public function rapport_total()
    {
        $tables=$this->db->listTables();
        $tables_old=$this->db_old->listTables();

       
        foreach($tables as $table)
        {
            $is_missing_field=[];
            $is_missing_table=FALSE;
         
            if(!in_array($table,$tables_old))
            {
                $is_missing_table=TRUE;
            }
            else
            {
               
                $fields=$this->get_name_field($this->db->getFieldData($table));
                $fields_old=$this->get_name_field($this->db_old->getFieldData($table));

               
                foreach($fields as $field)
                {
                    if(!in_array($field,$fields_old))
                    {
                        array_push($is_missing_field,$field);
                     
                    }
                }
                
            }

            if($is_missing_table){echo "<h3 style='color:red'>- La table $table n'existe pas dans old </h3>";};
            if(!empty($is_missing_field))
            {
                echo "<h3>La table $table existe dans old mais</h3>";
                echo "<ul>";
                foreach($is_missing_field as $missing_field)
                {
                    
                    echo "<li>$missing_field</li>";

                }
                echo "</ul>";
            }

        }

       

    }

    public function traduire_query()
    {
            $forge = \Config\Database::forge();

           
            if (!$this->db->fieldExists("uri","user_requete"))    
                $forge->addColumn('user_requete', ["uri"=>['type'=>"mediumtext","after"=>"string_where"]]);

            $dataQuery=new DataQueryListModel();

            $queries=$dataQuery->list_queries_all_import();

       // debugd(count($queries));

    //     $Calculator = new \Calculator\Libraries\ImportLibrary();
    //     $Calculator->ImportCalculator();



               /* $query_depart="
                
                @@array[ou_et]=0@@array[entity]=demande@@array[champ]=date_demande@@array[operateur]=superieur@@array[choice]=01/01/2020@@array[par_ouvert]=0@@array[par_ferme]=0@@begin@@array[ou_et]=AND@@array[entity]=demande@@array[champ]=date_demande@@array[operateur]=inferieur@@array[choice]=01/01/2021@@array[par_ouvert]=0@@array[par_ferme]=0@@begin@@array[ou_et]=AND@@array[entity]=demande_detail@@array[champ]=demande_id_prime@@array[operateur]=contient@@array[choice_is_array]=4@@array[par_ouvert]=0@@array[par_ferme]=0
                
                ";*/

            foreach($queries as $query_sql)
            {
                $query_depart=$query_sql->string_where;
                $query_explode=explode("[ou_et]",$query_depart);

               // debug($query_explode);

                //$uri=NULL;

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
                //echo "<li>".$query_sql->id_requete;
                //debug($data["uri"]);
                $builder->update($data);


            }


        }

        public function mdp_encodage()
        {
            $forge = \Config\Database::forge();


            //web
            $this->db->query("UPDATE users SET is_actif=is_new");
            $this->db->query("UPDATE users SET is_actif=1 WHERE users.is_salle = 1");
            //On élimine les champs en trop
            $fields=[
                    "id_organisme",
                    "is_new",
                    "pseudo",
                    "presentation_p",
                    "telephone_p",
                    "mail_p",
                    "adresse_p",
                    "cp_p",
                    "fonction_p",
                    "reset_token",
                    "valid_date",
                    "web_formation",
                    "mdp_old",
                    "date_creation",
                    "id_vignette",
                    "reset_date",
                    "date_inscription",
                    "valid_token",
                    "id_statut",
                    "id_contexte",
                    "code_activation"


            ];

            foreach($fields as $field)
            {
                if ($this->db->fieldExists($field,"users")) 
                {
                    $forge->dropColumn('users', $field);
                }
            }





            //On modifie le nom des champs
            if ($this->db->fieldExists("id_user","users")) 
                $forge->modifyColumn('users', ["id_user"=>["name"=>"id",'type'=>"INT"]]);

            if ($this->db->fieldExists("login","users")) 
                $forge->modifyColumn('users', ["login"=>["name"=>"username",'type'=>"VARCHAR","constraint"=>255]]);

            if ($this->db->fieldExists("mdp","users")) 
                $forge->modifyColumn('users', ["mdp"=>["name"=>"password",'type'=>"VARCHAR","constraint"=>255]]);

           /* if ($this->db->fieldExists("is_actif","users")) 
                $forge->modifyColumn('users', ["is_actif"=>["name"=>"actived",'type'=>"INT"]]);*/

            if ($this->db->fieldExists("mail","users")) 
                $forge->modifyColumn('users', ["mail"=>["name"=>"email",'type'=>"VARCHAR","constraint"=>255]]);
            

            if (!$this->db->fieldExists("created_at","users"))    
                $forge->addColumn('users', ["created_at"=>['type'=>"DATETIME","after"=>"email"]]);
            
            if (!$this->db->fieldExists("updated_at","users"))    
                $forge->addColumn('users', ["updated_at"=>['type'=>"DATETIME","after"=>"created_at"]]);

          /*  if (!$this->db->fieldExists("actived","users"))
                $forge->addColumn('users', ["actived"=>['type'=>"INT","after"=>"updated_at","default"=>1]]);

                $this->db->query("UPDATE users SET actived=is_actif");*/

            if (!$this->db->fieldExists("valided","users"))    
                $forge->addColumn('users', ["valided"=>['type'=>"INT","after"=>"actived","default"=>1]]);

            if (!$this->db->fieldExists("role_id","users"))
                $forge->addColumn('users', ["role_id"=>['type'=>"INT","after"=>"valided","default"=>1]]);

            if (!$this->db->fieldExists("source","users"))
                $forge->addColumn('users', ["source"=>['type'=>"VARCHAR","constraint"=>255,"after"=>"role_id","default"=>"CRM"]]);

            if (!$this->db->fieldExists("created_by","users"))
                $forge->addColumn('users', ["created_by"=>['type'=>"INT","after"=>"source"]]);
            
            if (!$this->db->fieldExists("updated_by","users"))
                $forge->addColumn('users', ["updated_by"=>['type'=>"INT","after"=>"created_by"]]);

            if (!$this->db->fieldExists("token","users"))
                $forge->addColumn('users', ["token"=>['type'=>"VARCHAR","constraint"=>64,"after"=>"id_user_back_up"]]);
            
            if (!$this->db->fieldExists("is_new","users"))
                $forge->addColumn('users', ["is_new"=>['type'=>"INT","after"=>"token","default"=>1]]);
            
            
            if (!$this->db->fieldExists("avatar","users"))
                $forge->addColumn('users', ["avatar"=>['type'=>"VARCHAR","constraint"=>255,"after"=>"is_new","default"=>"default.png"]]);
            
            if (!$this->db->fieldExists("phone","users"))
                $forge->addColumn('users', ["phone"=>['type'=>"VARCHAR","constraint"=>255,"after"=>"avatar"]]);
            
            
            if (!$this->db->fieldExists("website","users"))
                $forge->addColumn('users', ["website"=>['type'=>"VARCHAR","constraint"=>255,"after"=>"phone"]]);
            
        



            echo $this->decrypt("SiJ/UBx646w6Qel4syp1Kg==");

            //debugd($this->decrypt("Vhz9AlqBYjWUaqN+rv9pNQ=="));
            
            //On crée le champs password_old
            if (!$this->db->fieldExists('password_old', 'users')) 
            {
                $this->db->query("ALTER TABLE `users` ADD `password_old` VARCHAR(255) NOT NULL AFTER `password`; ");
                $this->db->query("UPDATE users SET password_old=password");
            }
    
            $builder=$this->db->table("users");
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
                    $builder=$this->db->table("users");
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

}