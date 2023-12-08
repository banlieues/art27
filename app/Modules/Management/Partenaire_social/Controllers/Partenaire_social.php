<?php

namespace Partenaire_social\Controllers;

use Base\Controllers\BaseController;

use Layout\Libraries\LayoutLibrary;

use Partenaire_social\Models\Partenaire_socialModel;
use Barcode\Models\List_BarCodeModel;

use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;

use Components\Libraries\ComponentOrderBy;
use Components\Libraries\BarCodeGeneratorLibrary;

use Dompdf\Dompdf;
use Dompdf\Options;


class Partenaire_social extends BaseController
{

    public  $mois_ligne=[
        "1janvier"=>"Janvier",
        "2fevrier"=>"Février",
        "3mars"=>"Mars",
        "4avril"=>"Avril",
        "5mai"=>"Mai",
        "6juin"=>"Juin",
        "7juillet"=>"Juillet",
        "8aout"=>"Août",
        "9septembre"=>"Septembre",
        "10octobre"=>"Octobre",
        "11novembre"=>"Novembre",
        "12decembre"=>"Décembre"

    ];

    public function __construct()
    {
        if(session()->get("loggedUserRoleId")==2)
        {
            header("Location:" . base_url("user"));
        }
            if(session()->get("loggedUserRoleId")!=1)
        {
            header("Location:" . base_url("identification/logout"));
        }


        $this->partenaire_socialModel = new Partenaire_socialModel();
        $this->list_BarCodeModel=new List_BarCodeModel();
        $this->context = "partenaire_social";

        $request=$this->request;

        $this->dataView=new DataViewConstructor();
        $this->componentOrderBy=new ComponentOrderBy();
      

        parent::__construct(__NAMESPACE__);

        $layout_l = new LayoutLibrary();


        $this->datas->theme = $layout_l->getThemeByRef($this->context);
        $this->datas->context = $this->context;
        $this->datas->mois_ligne=$this->mois_ligne;
        $this->viewpath = $this->module . "\Views\/";  
        $this->autorisationManager = \Config\Services::autorisationModel();

    }



    public function listPartenaire_social()
    {
      
   

        $orderBy=$this->componentOrderBy->getOrderBy("id_partenaire_social",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("ASC",$this->request);

        $fieldsOrder=
        [
            
            "delete"=>[null,false,"partenaire_socials_culturel_d"],
            
            "nom_partenaire_social"=>["Nom",true],
            "statut_partenaire_social"=>["Statut",true],
            "commentaire"=>[null,false],
            "adresse_partenaire_social"=>["Adresse postal",true],
            "code_postal"=>["Localité",true],
            "telephone_partenaire_social"=>["Téléphone",true],
            "gsm_partenaire_social"=>["Gsm",true],
            "mail_partenaire_social"=>["Mail",true],
            "web_partenaire_social"=>["Web",true],

        ];


        
        $this->datas->partenaire_socials_culturel=$this->partenaire_socialModel->getListPartenaire_social($this->request,$orderBy,$orderDirection);
        $this->datas->pager=$this->partenaire_socialModel->pager;
        $this->datas->nbPartenaire_socials_culturel= $this->datas->pager->getTotal();
        $this->datas->itemSearch=$this->request->getVar("itemSearch");
        $this->datas->fields= $this->partenaire_socialModel->getFields();
        $this->datas->context= $this->context;
       
        $this->datas->titleView="Liste des partenaire_socials_culturel";
        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);

        return view($this->viewpath.'list-partenaire_social', (array) $this->datas );
    }

    public function fiche($id_partenaire_social=NULL)
    {


      /*  if(!$this->autorisationManager->is_gestion_entity_autorise("partenaire_socials_culturel"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

        $dataView=new DataViewConstructor();

        //ETAPE I : On récupére les élements qui constituent la fiche
        $components=$dataView->getComponents("partenaire_social");

        if(empty($components))
        {
            return "Aucune page définie pour les partenaire_socials_culturel";
        }

        //ETAPE 2 : on récupérer les données du partenaire_social en cours
        $partenaire_social=$this->partenaire_socialModel->partenaire_social($id_partenaire_social);
        $this->datas->info_log=date_log($partenaire_social->created_at,$partenaire_social->updated_at,$partenaire_social->createur,$partenaire_social->updateur);

        $partenaire_social_convention=$this->partenaire_socialModel->partenaire_social_convention($id_partenaire_social);
     
        
       // $user=$this->inscriptionModel->get_user_by_id_partenaire_social($id_partenaire_social);

        if(!empty($partenaire_social)){

                $titleView=  "<i>Partenaire social : $partenaire_social->nom_partenaire_social</i>"; 
           
        }else{
            $titleView="Fiche non trouvée";
        }

        $this->datas->partenaire_social = $partenaire_social;
        $this->datas->partenaire_social_convention=$partenaire_social_convention;
        $this->datas->fields = $this->partenaire_socialModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->titleView=$titleView;
        $this->datas->dataView=$dataView;
        $this->datas->typeDataView="read";
        $this->datas->validation=NULL;
        $this->datas->id_partenaire_social=$id_partenaire_social;
        $this->datas->components=$components;
        $this->datas->viewpath=$this->viewpath;
        $this->datas->tab="fiche";

        return view($this->viewpath.'view-partenaire_social-fiche', (array) $this->datas);
    }
    

    public function modif($id_partenaire_social=NULL)
    {


      /*  if(!$this->autorisationManager->is_gestion_entity_autorise("partenaire_socials_culturel"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

        $dataView=new DataViewConstructor();

        //ETAPE I : On récupére les élements qui constituent la fiche
        $components=$dataView->getComponents("partenaire_social");

        if(empty($components))
        {
            return "Aucune page définie pour le partenaire social";
        }

        //ETAPE 2 : on récupérer les données du partenaire_social en cours
        $partenaire_social=$this->partenaire_socialModel->partenaire_social($id_partenaire_social);
        $partenaire_social_convention=$this->partenaire_socialModel->partenaire_social_convention($id_partenaire_social);

        $this->datas->info_log=date_log($partenaire_social->created_at,$partenaire_social->updated_at,$partenaire_social->createur,$partenaire_social->updateur);

        
       // $user=$this->inscriptionModel->get_user_by_id_partenaire_social($id_partenaire_social);

       if(!empty($partenaire_social)){

            $titleView=  "<i>Partenaire social : $partenaire_social->nom_partenaire_social </i>"; 

   
        }else{
            $titleView="Fiche non trouvée";
        }

        $this->datas->partenaire_social = $partenaire_social;
        $this->datas->partenaire_social_convention=$partenaire_social_convention;
        $this->datas->fields = $this->partenaire_socialModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->titleView=$titleView;
        $this->datas->dataView=$dataView;
        $this->datas->typeDataView="update";
        $this->datas->validation=NULL;
        $this->datas->id_partenaire_social=$id_partenaire_social;
        $this->datas->components=$components;
        $this->datas->viewpath=$this->viewpath;
        $this->datas->tab="fiche";
       
        //debug( $this->datas->demande);


       
      
        return view($this->viewpath.'view-partenaire_social-form_update', (array) $this->datas);
    }
    

    public function new()
    {


      /*  if(!$this->autorisationManager->is_gestion_entity_autorise("partenaire_socials_culturel"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

        $dataView=new DataViewConstructor();

        //ETAPE I : On récupére les élements qui constituent la fiche
        $partenaire_social_component=$dataView->getOneComponent("partenaire_social","partenaire_social");
       // $partenaire_social_caracteristique_component=$dataView->getOneComponent("partenaire_social","partenaire_social");
      
       
       // $user=$this->inscriptionModel->get_user_by_id_partenaire_social($id_partenaire_social);

      
        $titleView="Ajouter un partenaire social";

        $this->datas->partenaire_social = null;
        $this->datas->fields = $this->partenaire_socialModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->titleView=$titleView;
        $this->datas->dataView=$dataView;
        $this->datas->typeDataView="create";
        $this->datas->validation=NULL;
        $this->datas->id_partenaire_social=0;
        $this->datas->viewpath=$this->viewpath;
        $this->datas->components=$dataView->getComponents("partenaire_social");
        $this->datas->tab="fiche";

     
        $this->datas->partenaire_social_component=$partenaire_social_component;
       // $this->datas->partenaire_social_caracteristique_component=$partenaire_social_caracteristique_component;
       
        return view($this->viewpath.'view-partenaire_social-form-insert', (array) $this->datas);
    }




    public function save()
    {
       //list of indexes of form
        $session = \Config\Services::session();
        $indexes=$this->request->getVar("indexesForm");

        //debugd($this->request->getVar());

        $dataView=new DataViewConstructor();
        $rules=$dataView->getRules($indexes,$this->partenaire_socialModel->getFields());
    
        if (!$this->validate($rules)&&!empty($rules)) 
        {
            if($this->request->getVar('typeDataView')=="create")
            {
                echo $this->formPartenaire_social(NULL,$this->validator);
            } 
            else 
            {
                echo $this->formPartenaire_social($this->request->getVar('id_partenaire_social'),$this->validator);
            }          
        } 
        else 
        {
            
                $id_partenaire_social_save= $this->partenaire_socialModel->saveDataDelivrePartenaire_social($this->request->getVar("indexesForm"),$this->request->getVar(),$this->request->getVar("id_entity"));
              


            //treatment of data  
         /*       $id_partenaire_social_save=$dataView->saveData(
                $indexes,
                $this->request->getVar(),
                $this->partenaire_socialModel->getFields(),
                $this->partenaire_socialModel->getTable(),
                $this->request->getVar("id_partenaire_social")
            );  */
            
            if($this->request->getVar('typeDataView')=="create")
            {
                //$id_partenaire_social=$this->partenaire_socialModel->insertData($data);
                $message= 'La fiche de partenaire_social a été créée';
            } 
            else 
            {
                //$id_partenaire_social=$this->partenaire_socialModel->updateData($data,$this->request->getVar("id_partenaire_social"));
                $message= 'La fiche a été modifiée';
            }
            
            return redirect()->to(base_url()."/partenaire_social/fiche/$id_partenaire_social_save")->with("success",$message);
        }
    }


  

    public function save_modelisation()
    {
        $posts=$this->request->getVar();
        
        $dataView=new DataViewConstructor();
        $dataGeneratorModel = new DataViewConstructorModel();
        $entityParams=$dataGeneratorModel->getOneEntities("partenaire_social");


        $dataView->setComponents("partenaire_social",$posts);
        
        $message= 'Le modèle de la fiche '.$entityParams->label.' a été enregistré';

        return redirect()->to(base_url()."/modelisation")->with("success",$message);
       

    }

    public function get_remarque_by_partenaire_social($id_partenaire_social)
    {

        $this->datas->remarques=$this->partenaire_socialModel->get_remarque_by_partenaire_social($id_partenaire_social);
        $this->datas->titleView="Remarques";
        return view($this->viewpath.'get_remarque_by_partenaire_social', (array) $this->datas);
    }

    
    public function get_commentaire_by_art27_partenaire_social($id_partenaire_social)
    {
        $this->datas->commentaires=$this->partenaire_socialModel->get_commentaire_by_art27_partenaire_social($id_partenaire_social);
        return view($this->viewpath.'get_commentaire_by_art27_partenaire_social', (array) $this->datas);
    }

    public function convention_barcode($id_partenaire_social,$annee_select)
    {


        $partenaire_social=$this->partenaire_socialModel->partenaire_social($id_partenaire_social);
        $has_convention=$this->partenaire_socialModel->has_convention($id_partenaire_social,$annee_select);
         
       // $user=$this->inscriptionModel->get_user_by_id_partenaire_social($id_partenaire_social);

       if(!empty($partenaire_social))
       {

        $titleView=  "<i>Partenaire social : $partenaire_social->nom_partenaire_social </i>"; 
   
        }else
        {
            $titleView="Fiche non trouvée";
            
        }
     

        if($has_convention)
        {
            $convention=$this->partenaire_socialModel->convention($id_partenaire_social,$annee_select);
           
            $this->datas->info_log=date_log($convention->created_at,$convention->updated_at,$convention->createur,$convention->updateur);

           
        }
        else
        {
            $convention=NULL;
           
        }



        $this->datas->titleView="Conventions $annee_select";
        $this->datas->annee_select=$annee_select;
        $this->datas->id_partenaire_social=$id_partenaire_social;
        $this->datas->partenaire_social=$partenaire_social;
        $this->datas->id_partenaire_social=$id_partenaire_social;
        $this->datas->convention=$convention;
        $this->datas->viewpath=$this->viewpath;
        $this->datas->context= $this->context;
        $this->datas->titleView=$titleView;
        $this->datas->tab="convention";
        $this->datas->typeDataView="read";

        

        return view($this->viewpath.'convention_barcode', (array) $this->datas);
       
    }

    public function convention_barcode_modif($id_partenaire_social,$annee_select)
    {
        $partenaire_social=$this->partenaire_socialModel->partenaire_social($id_partenaire_social);
        $has_convention=$this->partenaire_socialModel->has_convention($id_partenaire_social,$annee_select);
         
       // $user=$this->inscriptionModel->get_user_by_id_partenaire_social($id_partenaire_social);

        if(!empty($partenaire_social))
        {
            $titleView=  "<i>Partenaire social : $partenaire_social->nom_partenaire_social </i>"; 
        }
        else
        {
            $titleView="Fiche non trouvée";
        }
     

        if($has_convention)
        {
            $convention=$this->partenaire_socialModel->convention($id_partenaire_social,$annee_select);
        }
        else
        {
            $convention=NULL;
        }



        $this->datas->titleView="Conventions $annee_select";
        $this->datas->annee_select=$annee_select;
        $this->datas->id_partenaire_social=$id_partenaire_social;
        $this->datas->partenaire_social=$partenaire_social;
        $this->datas->id_partenaire_social=$id_partenaire_social;
        $this->datas->convention=$convention;
        $this->datas->viewpath=$this->viewpath;
        $this->datas->context= $this->context;
        $this->datas->titleView=$titleView;
        $this->datas->tab="convention";
        $this->datas->typeDataView="update";

        
        return view($this->viewpath.'convention_barcode_modif', (array) $this->datas);
       
    }



    

    public function save_convention()
    {

       $this->partenaire_socialModel->save_convention($this->request->getVar());

       return redirect()->to(base_url()."partenaire_social/convention_barcode/".$this->request->getVar("id_partenaire_social")."/".$this->request->getVar("annee"))->with("success","Les modificiations");
        
    }

    public function barcode_list($id_partenaire_social,$annee_select=0,$mois_select=0)
    {
        $partenaire_social=$this->partenaire_socialModel->partenaire_social($id_partenaire_social);
        $has_convention=$this->partenaire_socialModel->has_convention($id_partenaire_social,$annee_select);
         
       // $user=$this->inscriptionModel->get_user_by_id_partenaire_social($id_partenaire_social);

       if(!empty($partenaire_social))
       {

        $titleView=  "<i>Partenaire social : $partenaire_social->nom_partenaire_social </i>"; 
   
        }else
        {
            $titleView="Fiche non trouvée";
            
        }
     

        if($has_convention)
        {
            $convention=$this->partenaire_socialModel->convention($id_partenaire_social,$annee_select);
           
            $this->datas->info_log=date_log($convention->created_at,$convention->updated_at,$convention->createur,$convention->updateur);

            //debugd($this->info_log);
        }
        else
        {
            $convention=NULL;
           
        }

        

        $orderBy=$this->componentOrderBy->getOrderBy("id_partenaire_social",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("ASC",$this->request);

        $fieldsOrder=
        [
            
           // "delete"=>[null,false,"partenaire_socials_culturel_d"],
            
            ""=>["<button>Imprimer</button>",false],
            "label_mois"=>["Mois concerné",true],
            "num_code"=>["Référence",TRUE],
            "barcode"=>["Code Barre",false],
            "statut"=>["Statut",true],
            "commentaire"=>["Commentaire",true],
            "created_at"=>["Créé à",true],
            "user_create"=>["Créé par",true],
            
            "Partenaire culturel"=>["Partenaire culturel",false],
            "Ticket"=>["Ticket scanné",false],

        ];



        $this->datas->list_BarCodes=$this->list_BarCodeModel->getListPartenaire_social($id_partenaire_social,$annee_select,$mois_select,$this->request,$orderBy,$orderDirection);
        $this->datas->titleView="Conventions $annee_select";
        $this->datas->annee_select=$annee_select;
        $this->datas->id_partenaire_social=$id_partenaire_social;
        $this->datas->partenaire_social=$partenaire_social;
        $this->datas->id_partenaire_social=$id_partenaire_social;
        $this->datas->convention=$convention;
        $this->datas->viewpath=$this->viewpath;
        $this->datas->context= $this->context;
        $this->datas->titleView=$titleView;
        $this->datas->tab="convention";
        $this->datas->typeDataView="list";
        $this->datas->pager=$this->list_BarCodeModel->pager;
        $this->datas->nbBarcodes= $this->datas->pager->getTotal();
        $this->datas->itemSearch=$this->request->getVar("itemSearch");
        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);
        $this->datas->module=$this->module;
        $this->datas->statuts=$this->list_BarCodeModel->get_statut();

        

        return view($this->viewpath.'convention_barcode_list', (array) $this->datas);
       
    }


    
    public function barcode_generator()
    {

        $barCodeGenerator=new BarCodeGeneratorLibrary();

        if(!$this->request->getVar("nb_produire"))
        {
            return redirect()->to(base_url()."partenaire_social/convention_barcode/".$this->request->getVar("id_partenaire_social")."/".$this->request->getVar("annee_select"))->with("danger","Vous n'avez pas indiqué de nombre de codes barres à produire pour le mois de ".$this->request->getVar("mois_select")." !");
        }

        if($this->request->getVar("id_partenaire_social"))
        {
            //$this->datas->partenaire=$this->barcodemodel->get_partenaire_social($this->request->getVar("id_partenaire_social"));

            $images=[];
            $id_barcodes=[];
           
            if($this->request->getVar($id_barcodes))
            {
                $id_barcodes=$this->request->getVar($id_barcodes);

                die("toto");
               
            }

           
            //on produit les barres codes

            if(empty($id_barcodes))//cas où les barcodes n'existent pas au départ
            {
                for($i=0;$i<$this->request->getVar("nb_produire");$i++)
                {
                    //on inszre en base de données les barres codes produits
                    $db = \Config\Database::connect();
                    $data["created_at"]=date("Y-m-d H:i:s");
                    $data["created_by"]=session()->get("loggedUserId");
                    $data["statut"]=1;
                    $data["id_partenaire_social"]=$this->request->getVar("id_partenaire_social");
                    $data["mois"]=$this->request->getVar("mois_select_sql");
                    $data["annee"]=$this->request->getVar("annee_select");
                

                    $builder=$db->table("convention_barcode");
                    $builder->insert($data);

                    $id_barcode=$db->insertId();

                    $id_barcodes[]=$id_barcode;

                    $num_code=$id_barcode.$this->request->getVar("id_partenaire_social")."01234567890";

                    $image=$barCodeGenerator->generate($num_code);
                    $images[$num_code]=$image;

                    $data_update["num_code"]=$num_code;
                    $data_update["barcode"]=$image;
                    $builder=$db->table("convention_barcode");
                    $builder->where("id_convention_barcode",$id_barcode);
                    $builder->update($data_update);
                    

                }
           

                //On met à jour les statisitiques de production de barcodes
            
                //On récupere les donnees de la convention
                $builder=$db->table("convention");
                $builder->where("annee",$this->request->getVar("annee_select"));
                $builder->where("id_partenaire_social",$this->request->getVar("id_partenaire_social"));
                $convention=$builder->get()->getRow();

                $index_mois_produit=$this->request->getVar("mois_select_sql")."_produit";
                $data_stat[$index_mois_produit]=$convention->$index_mois_produit+$this->request->getVar("nb_produire");
                $data_stat["updated_by"]=date("Y-m-d H:i:s");
                $data_stat["updated_at"]=session()->get("loggedUserId");

                $builder=$db->table("convention");
                $builder->where("annee",$this->request->getVar("annee_select"));
                $builder->where("id_partenaire_social",$this->request->getVar("id_partenaire_social"));

                $builder->update($data_stat);

                $nb_produire=$this->request->getVar("nb_produire");

            }
            else
            {
                $db = \Config\Database::connect();
                $builder=$db->table("convention_barcode");
                $builder->whereIn($id_barcodes);
                $baraffiches=$builder->get()->getResult();

                foreach($getResult as $code)
                {
                    $index_i=$code->num_code;
                    $images[$index_i]=$code->barcode;

                }
                $nb_produire=count($images);
            }
         
            //donnes pour la vue
            $id_partenaire_social=$this->request->getVar("id_partenaire_social");
            $partenaire_social=$this->partenaire_socialModel->partenaire_social($id_partenaire_social);

            $this->datas->images=$images;
            $this->datas->annee_select=$this->request->getVar("annee_select");
            $this->datas->mois_select=$this->request->getVar("mois_select");
            $this->datas->mois_select_sql=$this->request->getVar("mois_select_sql");
            $this->datas->a_partir_de=$this->request->getVar("a_partir_de");
            $this->datas->id_partenaire_social=$id_partenaire_social;
            $this->datas->number_barre=$this->request->getVar("number_barre");
            $this->datas->id_barcodes=$id_barcodes;

            $this->datas->nb_produire=$nb_produire;

            $this->datas->titleView="Planche de code barres pour $partenaire_social->nom_partenaire_social du mois ".$this->datas->mois_select." ".$this->datas->annee_select;
            $this->datas->partenaire_social=$partenaire_social;
            $this->datas->viewpath=$this->viewpath;
            $this->datas->context= $this->context;
            $this->datas->tab="convention";
            $this->datas->typeDataView="print";

            return view("Partenaire_social\convention_barcode_planche",(array) $this->datas);
        }
        else
        {
            $this->datas->partenaire=null;

            echo "Pas de barcode renseigné";
        }

        
            //$this->test_pdf();
    
        
    }


    
 
    

    public function test_pdf()
    {
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml("voici un test");
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->set_option('defaultMediaType', 'all');
        $dompdf->set_option('isFontSubsettingEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->render();
        $dompdf->stream();
    }
    


    public function setStatut($id_convention_barcode) 
    {
        if(!$this->autorisationManager->is_autorise("partenaire_social_u"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }
        $statut=$this->request->getVar("statut");


  
        $this->list_BarCodeModel->setStatut($id_convention_barcode,$statut);

        


        echo view($this->module.'\form_barcode_statut', [
            "barcode" => $this->list_BarCodeModel->getBarCode($id_convention_barcode),
            "statuts"=>$this->list_BarCodeModel->get_statut(),
          
            
        ]);

   


        //echo json_encode($data);

       
    }


    public function setCommentaire()
    {
      
        if($this->request->getVar("id_convention_barcode"))
        {
            $this->list_BarCodeModel->setCommentaire($this->request->getVar("id_convention_barcode"),$this->request->getVar("commentaire"));


            echo view($this->module.'\form_barcode_commentaire', [
                "barcode" => $this->list_BarCodeModel->getBarCode($this->request->getVar("id_convention_barcode"))

                
            ]);
        }
    }

    public function test_imagick()
    {
        //header('Content-type: image/jpeg');
       $imagick =\Config\Services::image('imagick');

       //debug(APPPATH.'Barcodes/test.jpg');
        
       //$imagick->readImage(APPPATH.'Barcodes/test.jpg');


      $tot= $imagick->withFile(APPPATH.'Barcodes/test.jpg')->fit(100, 100, 'center');
debugd($tot);
       //$imagick->readImage("https://www.banlieues.be/wp-content/uploads/2021/02/Banlieues-ASBL.png");

        

        //$imagick->thumbnailImage(100, 0);

       // $image = new Imagick(APPPATH.'/Barcodes/test.jpg');

        // Si 0 est fourni comme paramètre de hauteur ou de largeur,
        // les proportions seront conservées
        

       // echo $image;
    }

    

}
