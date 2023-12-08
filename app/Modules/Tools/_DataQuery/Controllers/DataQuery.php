<?php

namespace DataQuery\Controllers;

use Base\Controllers\BaseController;
use Layout\Libraries\LayoutLibrary;

use DataQuery\Libraries\DataQueryConstructor;
use DataQuery\Models\DataQueryModel;
use DataQuery\Models\DataQueryListModel;

use Components\Libraries\ComponentOrderBy;

class DataQuery extends BaseController 
{
  /*  protected $context;
    protected $dataQueryModel;
    protected $dataQueryConstructor;
    protected $path;
    protected $descriptor;*/
   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        if(session()->get("loggedUserRoleId")!=1)
        {
             header("Location:".base_url("identification/logout"));
        }
            
        $this->autorisationManager = \Config\Services::autorisationModel();

        $this->dataQueryModel = new DataQueryModel();
        $this->dataQueryListModel = new DataQueryListModel();
        $this->dataQueryConstructor = new DataQueryConstructor();
        $this->descriptor=$this->dataQueryModel->getDescriptor();
        $this->componentOrderBy=new ComponentOrderBy();



        $this->context = "queries";
    
        $layout_l = new LayoutLibrary();

        $this->path="DataQuery\Views\/";

      

       // debug( $this->datas->theme);
    }

	public function index($id_requete=0,$id_requete_provisoire=0)
    {
        
        if(!$this->autorisationManager->is_autorise("requete_r"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }

        //debug($this->descriptor);
        $fields_selected=[];
        $group_by=[];
        $order_by=[];

        

        if($id_requete>0)
        {

            $requete=$this->dataQueryListModel->info_requete($id_requete);
                //debugd($requete);
            $this->datas->nom_requete=$requete->nom;
        }

        if($id_requete_provisoire>0)
        {   


            $queryString = $this->dataQueryModel->get_uri_provisoire($id_requete_provisoire);

            // Utilisez la fonction parse_url pour extraire les paramètres de la chaîne de requête
            $urlParts = parse_url($queryString);

            if(isset($urlParts['query']))
            {
                $query = $urlParts['query'];
            }
            else
            {
                $query=$queryString;
            }
                
            // Utilisez la fonction parse_str pour convertir la chaîne de requête en tableau PHP
            parse_str($query, $getget);

            //On inject les résultats dans getVarVar
            if(!empty($getget))
            {
                foreach($getget as $key=>$value)
                {
                    $getVar[$key]=$value;
                }
            }   
            
            $getVarForUri=$getVar;
            unset($getVarForUri["page"]);
            $uri=http_build_query($getVar);

            $id_requete_provisoire=$this->dataQueryModel->insert_provisoire($uri);
          
        }
       

        elseif($id_requete>0)
        {
                

                $queryString = $requete->uri;

                // Utilisez la fonction parse_url pour extraire les paramètres de la chaîne de requête
                $urlParts = parse_url($queryString);

                if(isset($urlParts['query']))
                {
                    $query = $urlParts['query'];
                }
                else
                {
                    $query=$queryString;
                }
                    
                // Utilisez la fonction parse_str pour convertir la chaîne de requête en tableau PHP
                parse_str($query, $getget);

                //On inject les résultats dans getVarVar
                if(!empty($getget))
                {
                    foreach($getget as $key=>$value)
                    {
                        $getVar[$key]=$value;
                    }
                }   
                
                $getVarForUri=$getVar;
                unset($getVarForUri["page"]);
                $uri=http_build_query($getVar);

                $id_requete_provisoire=$id_requete_provisoire=$this->dataQueryModel->insert_provisoire($uri);


        }
        else
        {
            $getVar=null;
            $id_requete_provisoire=$id_requete_provisoire=$this->dataQueryModel->insert_provisoire(null);
        }

        if(!empty($getVar))
        {
           
            if(isset($getVar["number"]))
            {
                $number=$getVar["number"];
            }
            else
            {
                $number=0;
            }

           
                
            if($number==0){ $number=1;} 
            if(isset($getVar["fields_select"])){$fields_selected=$getVar["fields_select"];}
            if(isset($getVar["group_by"])){$group_by=$getVar["group_by"];}
            if(isset($getVar["order_by"])){$order_by=$getVar["order_by"];}

            //verif if error
         
        }
        else
        {
            $getVar=NULL;
            $number=1;
        }

        $nb_tour=$number+1;

        //debug($getVar);
        $entities=$this->dataQueryModel->getEntities();
        
        $fields=[];


        foreach($entities as $entity)
        {
            
            $fields_for_entities=$this->dataQueryModel->getFieldsFromModelisation($entity->type);
            
            if(!empty($fields_for_entities))
            {
                
                $fields[$entity->type]=$fields_for_entities;

             
            }

        }


        if($id_requete>0)
        {
            $requete=$this->dataQueryListModel->info_requete($id_requete);
                //debugd($requete);
                $this->datas->nom_requete=$requete->nom;
        }

        $this->datas->id_requete=$id_requete;
        $this->datas->id_requete_provisoire=$id_requete_provisoire;
        $this->datas->title ="Système de requête";
        $this->datas->subtitle = "Système de requête";
        $this->datas->titleView= "Système de requête";
        $this->datas->context =$this->context;
        $this->datas->entities=$entities;
        $this->datas->fields=$fields;
        $this->datas->dataQueryConstructor=$this->dataQueryConstructor;
        $this->datas->path=$this->path;
        $this->datas->getVar=$getVar;
        $this->datas->number=$number;
        $this->datas->nb_tour=$nb_tour;
        $this->datas->fields_selected=$fields_selected;
        $this->datas->group_by=$group_by;
        $this->datas->order_by=$order_by;
        $this->datas->fields_selected=$fields_selected;
        

   
        return view($this->path."query_index", 
    
            (array) $this->datas
           
        );
       
    }

   
        public function execute($id_requete=null,$id_requete_provisoire=0,$is_new_requete=0)
        {

            

            if(!$this->autorisationManager->is_autorise("requete_r"))
            {
                header("Location:".base_url("autorisation/no_autorisation"));
            }
    
            $this->datas->id_requete=$id_requete;
            $this->datas->id_requete_provisoire=$id_requete_provisoire;
            $this->datas->is_new_requete=$is_new_requete;
        
            $page=$this->request->getVar("page");

           
    
            if($id_requete>0)
            {
                $requete=$this->dataQueryListModel->info_requete($id_requete);
                    //debugd($requete);
                $this->datas->nom_requete=$requete->nom;
            }

            if($is_new_requete)
            {   
                if($page>0)
                { 
                    
                    $getVar=$this->request->getVar();
                
                    $queryString = $this->dataQueryModel->get_uri_provisoire($id_requete_provisoire);
    
                    // Utilisez la fonction parse_url pour extraire les paramètres de la chaîne de requête
                    $urlParts = parse_url($queryString);
    
                    if(isset($urlParts['query']))
                    {
                        $query = $urlParts['query'];
                    }
                    else
                    {
                        $query=$queryString;
                    }
                        
                    // Utilisez la fonction parse_str pour convertir la chaîne de requête en tableau PHP
                    parse_str($query, $getget);
    
                    //On inject les résultats dans getVarVar
                    if(!empty($getget))
                    {
                        foreach($getget as $key=>$value)
                        {
                            $getVar[$key]=$value;
                        }
                    }   
                    
                    $getVarForUri=$getVar;
                    unset($getVarForUri["page"]);
                    $uri=http_build_query($getVar);

                } 
                
                else
                {
                    $getVar=$this->request->getVar();
                    $getVarForUri=$getVar;
                    unset($getVarForUri["page"]);
                    $uri=http_build_query($getVar);
                    $this->datas->id_requete_provisoire=$id_requete_provisoire=$this->dataQueryModel->insert_provisoire($uri,$id_requete_provisoire);

                }

            }

            elseif($id_requete>0)
            {
                    
                    $getVar=$this->request->getVar();
                
                    $queryString = $requete->uri;
    
                    // Utilisez la fonction parse_url pour extraire les paramètres de la chaîne de requête
                    $urlParts = parse_url($queryString);
    
                    if(isset($urlParts['query']))
                    {
                        $query = $urlParts['query'];
                    }
                    else
                    {
                        $query=$queryString;
                    }
                        
                    // Utilisez la fonction parse_str pour convertir la chaîne de requête en tableau PHP
                    parse_str($query, $getget);
    
                    //On inject les résultats dans getVarVar
                    if(!empty($getget))
                    {
                        foreach($getget as $key=>$value)
                        {
                            $getVar[$key]=$value;
                        }
                    }   
                    
                    $getVarForUri=$getVar;
                    unset($getVarForUri["page"]);
                    $uri=http_build_query($getVar);
    
            }
            else
            {
                $getVar=null;
            }
            
       


        //debug($this->request->getVar());
        if(!empty($getVar))
        {
            //on reconstruit uri
            $getVarForUri=$getVar;
            unset($getVarForUri["page"]);
            $uri=http_build_query($getVar);
            
            //$getVar=$this->request->getVar();
            //$number=$getVar["number"];
            //if($number==0){ $number=1;} 
            if(isset($getVar["fields_select"])){$fields_selected=$getVar["fields_select"];}
            if(isset($getVar["group_by"])){$group_by=$getVar["group_by"];}

            //verif if error
            $get_error=$this->dataQueryConstructor->get_error($getVar);


            if(empty($get_error))
            {
                   
                    $execute=$this->dataQueryModel->executeQuery($getVar);
                    //debugd($execute);
                    $pager=$this->dataQueryModel->pager;
                    $query=$execute["query"];
                    $results=$execute["results"];
                    $labels=$execute["labels"];
                    session()->set("urlLastQuery",current_url(true));


                    $this->datas->title ="Système de requête";
                    $this->datas->subtitle = "Système de requête";
                    $this->datas->titleView= "Système de requête";
                    $this->datas->context =$this->context;
                    $this->datas->query =$query;
                    $this->datas->results =$results;
                    $this->datas->getVar =$getVar;
                    $this->datas->labels =$labels;
                    $this->datas->urlRequete =str_replace("queries/execute","queries/index",current_url(true));
                    $this->datas->urlExport =str_replace("queries/execute","queries/export_csv",current_url(true));
                    $this->datas->pager =$pager;
                    $this->datas->path =$this->path;
                    $this->datas->uri=$uri;

                    return view($this->path."query_results", 
                      
                        (array) $this->datas
                        
                    );
            }
            else
            {
                //session()->setFlashdata('error', implode("<br>",$get_error));
                return redirect()->to(base_url()."queries/index/$id_requete/$id_requete_provisoire")->with("danger", implode("<br>",$get_error));
            }
        }
        else
        {
            //session()->setFlashdata('error', $this->dataQueryConstructor->error_message(1));
            return redirect()->to(base_url()."queries/index/$id_requete/$id_requete_provisoire")->with("danger", $this->dataQueryConstructor->error_message(1));
        }
    }

    public function export_csv($id_requete=0,$id_requete_provisoire=0)
    {   
        if(!$this->autorisationManager->is_autorise("requete_r"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }

        
        $getVar=$this->request->getVar();



        if($id_requete_provisoire>0)
        {  
            $queryString = $this->dataQueryModel->get_uri_provisoire($id_requete_provisoire);

            // Utilisez la fonction parse_url pour extraire les paramètres de la chaîne de requête
            $urlParts = parse_url($queryString);

            if(isset($urlParts['query']))
            {
                $query = $urlParts['query'];
            }
            else
            {
                $query=$queryString;
            }
                
            // Utilisez la fonction parse_str pour convertir la chaîne de requête en tableau PHP
            parse_str($query, $getget);

            //On inject les résultats dans getVarVar
            if(!empty($getget))
            {
                foreach($getget as $key=>$value)
                {
                    $getVar[$key]=$value;
                }
            }   
            
            $getVarForUri=$getVar;
            unset($getVarForUri["page"]);
            $uri=http_build_query($getVar);
        }
        else
        {

            $requete=$this->dataQueryListModel->info_requete($id_requete);

            $queryString =$requete->uri;
          

            // Utilisez la fonction parse_url pour extraire les paramètres de la chaîne de requête
            $urlParts = parse_url($queryString);

            if(isset($urlParts['query']))
            {
                $query = $urlParts['query'];
            }
            else
            {
                $query=$queryString;
            }
                
            // Utilisez la fonction parse_str pour convertir la chaîne de requête en tableau PHP
            parse_str($query, $getget);

            //On inject les résultats dans getVarVar
            if(!empty($getget))
            {
                foreach($getget as $key=>$value)
                {
                    $getVar[$key]=$value;
                }
            }   
            
            $getVarForUri=$getVar;
            unset($getVarForUri["page"]);
            $uri=http_build_query($getVar);
        }




        //connaite nombre total
        $execute=$this->dataQueryModel->executeQuery($getVar);


        $pager=$this->dataQueryModel->pager;
        $nb_total=$pager->getTotal();
 
        //J'ouvre un fichier temporaire
        $fileCsv=fopen('php://output','w');

        $labels = $execute["labels"];
        foreach($labels as $key=>$value) :
            $labels[$key] = mb_convert_encoding($value, 'windows-1252', 'UTF-8');
        endforeach;

        fputcsv($fileCsv,$labels,";");

        for($i=0;$i<$nb_total;$i=$i+1000)
        {
            $execute=$this->dataQueryModel->executeQuery($getVar,$i);
            $results=$execute["results"];

            foreach($results as $result)
            {
                $line = (array) $result;
                foreach($line as $key=>$value) :
                    $line[$key] = mb_convert_encoding($value, 'windows-1252', 'UTF-8');
                endforeach;

                fputcsv($fileCsv,$line,";");
            }

            gc_collect_cycles();
            
        }
        fclose($fileCsv);

        $filename=date('Y_m_d_H_i_s')."_crm.csv";
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.ms-excel;charset=utf-8');
        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment;filename='.$filename);
        header("Pragma: no-cache"); 
        header("Expires: 0");

    }


    //Ajax request for conditions
    public function get_list_select_field($entity)
	{
	    echo $this->dataQueryConstructor->get_list_select_field($entity);
	}

    public function get_input($champ)
	{
	    $html["input"]= $this->dataQueryConstructor->get_input($champ);
	    $html["operateur"]=$this->dataQueryConstructor->get_operateur($champ);
	    echo json_encode($html);
	}

 
    public function save_query()
    {
        if(!$this->autorisationManager->is_autorise("requete_r"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }
        $getVar=$this->request->getVar();

        //debugd($getVar);
        $id_requete=$this->dataQueryModel->save_query($getVar);

        if(!is_null($id_requete))
        {
            $requete=$this->dataQueryListModel->info_requete($id_requete);

            return redirect()->to(base_url()."queries/execute/$id_requete?$requete->uri")->with("success","La requête a été enregistrée");
        }
        else
        {
            return redirect()->back()->with("danger","Impossible d'enregistrer cette requête!");

        }
        
    }

    public function update_query($id_requete)
    {
        if(!$this->autorisationManager->is_autorise("requete_r"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }
        $getVar=$this->request->getVar();

        //debugd($getVar);
        $this->dataQueryModel->update_query($getVar,$id_requete);

        if(!is_null($id_requete))
        {
            $requete=$this->dataQueryListModel->info_requete($id_requete);

            return redirect()->to(base_url()."queries/execute/$id_requete?$requete->uri")->with("success","La requête a été enregistrée");
        }
        else
        {
            return redirect()->back()->with("danger","Impossible d'enregistrer cette requête!");

        }
        
    }

   public function list()
   {

        if(!$this->autorisationManager->is_autorise("requete_r"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }

        $orderBy=$this->componentOrderBy->getOrderBy("date_create",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("DESC",$this->request);

        $fieldsOrder=
        [
            
            
            
            "delete"=>[null,false,"contact_d"],
            "id_requete"=>["Id",true],
            "date_create"=>["Date de création",true],
           
            
            "nom"=>["Nom",true],
            "is_dasboard"=>["Tableau de bord?",TRUE],
            //"query"=>["Requête",true],
            "btn"=>[null,false]
            
            
            

        ];

        $this->datas->lists=$this->dataQueryListModel->list_queries($this->request,$orderBy,$orderDirection);
        $this->datas->pager=$this->dataQueryListModel->pager;
        $this->datas->nbRequete= $this->datas->pager->getTotal();
        $this->datas->itemSearch=$this->request->getVar("itemSearch");
        $this->datas->titleView="Liste des requêtes enregistrée";
        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);
        $this->datas->path =$this->path;

        


        
        return view($this->path."query_list",  (array) $this->datas);

   }



   public function is_dasboard($id_requete) 
   {
      /* if(!$this->autorisationManager->is_autorise("document_u"))
       {
           header("Location:".base_url("autorisation/no_autorisation"));
       }*/
       $is_dasboard=$this->request->getVar("is_dasboard");

 
       $this->dataQueryListModel->set_is_dasboard($id_requete,$is_dasboard);

       $is_dasboard=$this->dataQueryListModel->is_dasboard($id_requete);


      echo view($this->path.'\is_dasboard', [
        "is_dasboard" => $is_dasboard,
        "id_requete"=>$id_requete
        
    ]);

  


     

      
   }

    


   

    
}