<?php

namespace DocumentsGenerator\Controllers;

use Base\Controllers\BaseController;

use DocumentsGenerator\Models\DocumentsGeneratorModel;

use DataQuery\Models\DataQueryModel;

use Dompdf\Dompdf;
use Dompdf\Options;

use DataView\Models\DataViewConstructorModel;

class DocumentsGenerator extends BaseController
{
    protected $context;
    protected $path;
    protected $documentsGeneratorModel;
    protected $request;

    public function __construct()
    {
        if(session()->get("loggedUserRoleId")!=1)
        {
            header("Location:".base_url("identification/logout"));
        }

        parent::__construct(__NAMESPACE__);

        $this->context = "documentsgenerator";
        $this->datas->context = $this->context;
        $this->path = "DocumentsGenerator\Views\/";
        $this->documentsGeneratorModel = new DocumentsGeneratorModel();
        $this->dataQuery = new DataQueryModel();

        $request=$this->request;
        // $this->Dompdf = new Dompdf();
        // $this->Cpdf = new Cpdf();
        // $this->load->library('PhpWord');
    }

    public function index()
    {
        return view($this->path . 'index');
    }

    public function list($id_activity=NULL,$id_contact=NULL,$id_inscription=NULL)
    {
        //For test
       // $id_activity=5592;
        //$id_contact=31344;

        if(is_null($id_activity))
            return view($this->path . 'index');

        $documents_create=array();
        $documentsInscriptions=$this->documentsGeneratorModel->getDocumentsInscription($id_inscription);
        //debug($documentsInscriptions);
        if(!empty($documentsInscriptions))
        {
            foreach($documentsInscriptions as $documentsInscription)
            {
                array_push($documents_create,$documentsInscription->id_document_template);
            }
        }

        return view($this->path . 'listDocuments',[
            "documents"=>$this->documentsGeneratorModel->getList($id_activity,$id_contact),
            "id_contact"=>$id_contact,
            "id_activity"=>$id_activity,
            "id_inscription"=>$id_inscription,
            "context"=>$this->context,
            "titleView"=>"Liste des documents à télécharger",
            "documents_create"=>$documents_create
        ]);


    }

    public function getFile($id_template_document,$id_activity,$id_contact,$id_inscription)
    {
        //on recupere le nom du fichier
        $name_file=$this->documentsGeneratorModel->getNameFile($id_template_document,$id_activity,$id_contact,$id_inscription);
        if(!empty($name_file))
        {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($name_file) . '"');
        /*    header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($name_file));*/
    
            set_time_limit(0);
           
            readfile(APPPATH."Documents/Documents_by_crm/".$name_file);
            exit;
        }
        else
        {
            return "Error. Pas de fichier trouvé";
        }
        //debug(APPPATH."/Documents/Documents_by_crm/".$name_file,true);
       
    }

    /***
     * 
     * Tags possibles
     * 
     * #PRENOM
     * #NOM
     * #NOM_FORMATION
     * #LIEU_TITRE
     * #ADRESSE_FENETRE
     * #ADRESSE_ENFANT
     * #ADRESSE_FACTURE
     * #REMARQUES
     * #DATE_RA
     * #LIEU_TITRE
     * #SOLDE
     * #DATE_ANNULATION
     * #REFERENCE
     * 
     * #PIED_SJ
     * 
     */

    public function getPdf($id_document_template,$id_activity,$id_contact,$id_inscription)
    {
        $template_id = $id_document_template;

        if (isset($template_id) && !empty($template_id) && $template_id > 0)
        {
            $document_template = $this->documentsGeneratorModel->getDocument($template_id,$id_activity,$id_contact);
            //debug($document_template,TRUE);
            if (!empty($document_template))
            {
                $html = '<style>table {width:100%; border-collapse: collapse;} td {border: 1px solid #EEE; padding: 10px 5px;}</style>';
                $filename =$id_inscription.'-'.slugify_name_file($document_template->label);
                $html = $document_template->content;

                //On recupéere les variables de profil
                //$profil=$this->documentsGeneratorModel->getOneProfilRegistration($template_id,$id_activity,$id_contact);

                $params["ou_et_##1"]="AND";
                $params["ou_et_##1"] = "AND";
                $params["par_ouvert_##1"] = "0";
                $params["entity_##1"] = "registration";
                $params["champ_##1"] = "id_activity";
                $params["operateur_##1"] = "egal";
                $params["##1##_value"] = $id_activity;
                $params["par_ferme_##1"] = "0";
                $params["ou_et_##2"] = "AND";
                $params["par_ouvert_##2"] = 0;
                $params["entity_##2"] = "registration";
                $params["champ_##2"] = "id_contact";
                $params["operateur_##2"] = "egal";
                $params["##2##_value"] = $id_contact;
                $params["par_ferme_##2"] = 0;
                $params["number"] = 2;
               
                $profil_brut=$this->dataQuery->executeQuery($params);


                $profil=$profil_brut["results"][0];

                $indexes=$this->dataQuery->getAllIndexes();
                
               // debug($profil,true);



               
               //$html=str_replace("#ADRESSE_FENETRE",$adresse_fenetre,$html); 

               

               
             
               

                foreach($indexes as $index)
                {
                        $tag="#".strtoupper($index)."#";
                        //echo $tag; die();
                        //si commence par date, on formate, a faire

                        $index_remplace=$profil->$index;

                        if(preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/',$index_remplace))
                        {
                            $index_remplace=convert_date_en_to_fr_with_h($index_remplace, FALSE);
                        }
                        elseif(preg_match('/\d{4}-\d{2}-\d{2}/',$index_remplace))
                        {
                            $index_remplace=convert_date_en_to_fr_with_h($index_remplace, FALSE);
                        }

                        
                         $html=str_replace($tag,$index_remplace,$html);
                     
                    
                }

                //inserer saut de page
                $html=str_replace("#INSERER_SAUT_PAGE#",'<div style="page-break-before: always;"></div>',$html);

                //adresse fenêtre
                $adresse_fenetre=code_courtoisie($profil->codecourtoisie)." $profil->prenom $profil->nom_contact<br> $profil->adresse <br> $profil->codepostal $profil->localite";
                $html=str_replace("#ADRESSE_FENETRE#",$adresse_fenetre,$html);

                //adresse fenêtre enfant
                $adresse_enfant="Aux parents de $profil->prenom $profil->nom_contact<br> $profil->adresse <br> $profil->codepostal $profil->localite";
                $html=str_replace("#ADRESSE_ENFANT#",$adresse_enfant,$html);

                //adresse fenêtre enfant
                $adresse_facture=$profil->nom_court_institution."<br>".code_courtoisie($profil->codecourtoisie)." $profil->prenom $profil->nom_contact<br> $profil->adresse <br> $profil->codepostal $profil->localite";
                $html=str_replace("#ADRESSE_FACTURE#",$adresse_facture,$html);

                //adresse date_annulation

                $date_annulation=convert_date_en_to_fr_with_h((echeance($profil->date_suivi, true)),false);
                $html=str_replace("#DATE_ANNULATION#",$date_annulation,$html);
       


                //solde
                $solde=calculer_solde(
                    $profil->prix,
                    $profil->prix_organisme,
                    $profil->prix_etudiant,
                    $profil->prix_special,
                    $profil->typepart,
                    $profil->demandeur_emploi,
                    $profil->historique_payement,
                    $is_html=TRUE);
                
                    $html=str_replace("#SOLDE#",$solde,$html);    


                $html=str_replace("#LOGO_CEMEA#",$this->documentsGeneratorModel->getDocumentById(72),$html);
                $html=str_replace("#PIED_SJ#",$this->documentsGeneratorModel->getDocumentById(2),$html);
                $html=str_replace("#PIED_EP#",$this->documentsGeneratorModel->getDocumentById(1),$html);
                $html=str_replace("#PIED_BX#",$this->documentsGeneratorModel->getDocumentById(25),$html);



                // prix
                if (!empty($profil->prix_special)) $prix = $profil->prix_special;
                elseif ($profil->prix_special == '0') $prix = $profil->prix_special;
                elseif ($profil->typepart == 'I') $prix = $profil->prix_organisme;
                elseif ($profil->demandeur_emploi == 'oui') $prix = $profil->prix_etudiant;
                else $prix = $profil->prix;
                $html=str_replace("#PRIX#",$prix."€",$html);  

              //  die();
                
              

                // NEED A BETTER WAY TO FIX THAT
                require_once APPPATH.'ThirdParty/dompdf/lib/Cpdf.php';

                $options = new Options();
                $options->setIsHtml5ParserEnabled(true);
                $dompdf = new Dompdf($options);

                // $dompdf = new Dompdf();
                $dompdf->loadHtml($html, 'UTF-8');
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->set_option('defaultMediaType', 'all');
                $dompdf->render();
                file_put_contents(APPPATH."/Documents/Documents_by_crm/$filename.pdf", $dompdf->output());    
                
                
                $data_document["id_document_template"]=$id_document_template;
                $data_document["id_activity"]=$id_activity;
                $data_document["id_contact"]=$id_contact;
                $data_document["id_inscription"]=$id_inscription;
                $data_document["filename"]="$filename.pdf";

                $this->documentsGeneratorModel->insertInscriptionsDocument($data_document);  

                $dompdf->stream($filename.".pdf", ["Attachment" => TRUE]);
                 //$attachment = $dompdf->output();

                
                exit();
            }
            else
            {
                exit();
            }
        }
	}

    public function getListTag()
    {
        $this->dataGeneratorModel = new DataViewConstructorModel();


        return view($this->path."getListTag",
        [
            "tags_contact"=>$this->dataGeneratorModel->getDescriptorIndexByType("contact"),
            "tags_activity"=>$this->dataGeneratorModel->getDescriptorIndexByType("activities"),
            "tags_registration"=>$this->dataGeneratorModel->getDescriptorIndexByType("registration"),

            "titleView"=>"Liste des tags",
            "context"=>"tag_list"
            
        ]
        );

    }

 

}