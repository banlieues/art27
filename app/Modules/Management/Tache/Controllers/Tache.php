<?php

namespace Tache\Controllers;

use Base\Controllers\BaseController;

use Tache\Models\TacheModel;
use Layout\Libraries\LayoutLibrary;

use Demande\Models\DemandeModel;


use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;

use Outlook\Libraries\Tr_outlook;

use Components\Libraries\ComponentOrderBy;
class Tache extends BaseController
{
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

        parent::__construct(__NAMESPACE__);

        $layout_l = new LayoutLibrary();

        $request=$this->request;

        $this->tacheModel = new TacheModel();

        $this->dataview=new DataViewConstructor();

        $this->tr_outlook=new Tr_outlook();

        $this->demandeModel=new DemandeModel();

        $this->context = 'tache';
        $this->datas->theme = $layout_l->getThemeByRef($this->context);
        $this->datas->context = $this->context;
        $this->viewpath = $this->module . "\Views"; 
        
        $this->componentOrderBy=new ComponentOrderBy();

        $this->autorisationManager = \Config\Services::autorisationModel();
    }

    public function index()
    {

        if(empty($this->request->getVar()))
        {
            header("Location:".base_url()."tache?mes_tache=1");
            exit();
        }

        
        if(session()->get("loggedUserRoleId")==2)
        {
            header("Location:".base_url("user"));
        }
         if(session()->get("loggedUserRoleId")!=1)
        {
            header("Location:".base_url("identification/logout"));
        }

        return redirect()->to(base_url('redirect/to/demande'));

      

        $this->datas->title = lang('Dashboard.title');
        $this->datas->subtitle = lang('Dashboard.subtitle');
        $this->datas->titleView = lang('Dashboard.title');

        return view($this->viewpath . '\index', (array) $this->datas);
    }

    public function liste_tache()
    {
        if(empty($this->request->getVar()))
        {
            header("Location:".base_url()."tache?mes_tache=1");
            exit();
        }
      
        $segment="tache";
    
   
        $orderBy=$this->componentOrderBy->getOrderBy("tache.date_tache",$this->request);


        $orderDirection=$this->componentOrderBy->getOrderDirection("DESC",$this->request);

        $fieldsOrder=
        [
            "delete"=>[null,false,"taches_d"],
            "date_tache"=>["Début",true],
            "echeance"=>["Echéance",true],
            "sujet"=>["Sujet",true],
            "note"=>["Commentaire",true],
           "type_tache"=>["Type",true],
            "type_tache_libre"=>["Tache libre",true],
           "statut_tache"=>["Statut",true],
           "tache.id_demande"=>["Demande liée",true],
           
           
           

        ];

        $this->datas->type_tache=$this->request->getVar("type_tache");

        $this->datas->mes_tache=$this->request->getVar("mes_tache");
        
        $this->datas->taches=$this->tacheModel->getListTache($this->request,$orderBy,$orderDirection);
        $this->datas->pager=$this->tacheModel->pager;
        $this->datas->nbTaches= $this->datas->pager->getTotal();
        $this->datas->fields= $this->tacheModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->itemSearch=$this->request->getVar("itemSearch");
        $this->datas->titleView="Liste des taches";
        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);
       // $this->datas->type_tache=$this->tacheModel->get_liste_type_tache();
        //$this->datas->type_tache=$this->tacheModel->get_liste_statut_tache();
        $this->datas->module=$this->module;


        return view($this->module . '\list-tache', (array) $this->datas );
    }

    public function form_tache($id_demande=0,$id_tache=0,$is_request_ajax=false)
    {

        $request = \Config\Services::request();
        if(!$is_request_ajax)
        {
            $is_request_ajax=$request->isAJAX();
        }
       
        $this->datas->dataview=$this->dataview;

       
        $this->datas->id_demande=$id_demande;
        $this->datas->id_tache=$id_tache;
        $this->datas->request_is_ajax=$is_request_ajax;

        if($id_demande>0)
        {
            $this->datas->id_user=$this->demandeModel->get_user_encharge($id_demande);
        }
        else
        {
            $this->datas->id_user=session()->get("loggedUserId");
        }

        if($id_tache>0)
        {
            $this->datas->tache=$this->tacheModel->getTache($id_tache);
            $this->datas->typeDataView="read";
            $this->datas->title ="Tâche";
            $this->datas->titleView = "Tâche";
    
        }
        else
        {
            $this->datas->tache=NULL;
            $this->datas->typeDataView="new_form";
            $this->datas->title ="Formulaire Tâche";
            $this->datas->titleView = "Créer une tâche";
    
        }
       
       //debug($this->datas->tache);

        return view($this->viewpath .'\form_tache', (array) $this->datas);

    }


   public function get_calendar(){

        $post=$this->request->getVar();

       //debug($post,true);

        if(!isset($post['email']))
        {
            $post['email']=session()->get('loggedUserMail');
        }

        $events=array();
        if(isset($post['email'])&&!empty($post['email'])&&filter_var($post['email'], FILTER_VALIDATE_EMAIL)):
            $email=$post['email'];
            $date_j=date("Y-m-d");
            $dateStar=date('Y-m-d',strtotime('-2 month',strtotime($date_j)));
            $dateEnd=date('Y-m-d',strtotime('+6 month',strtotime($date_j)));
            $where=array(
               'startDateTime'=>$dateStar.'T01:00:00',
               'endDateTime'=>$dateEnd.'T23:59:59'
            );
            //print_r($where); die();
            $response=$this->tr_outlook->get_calendar_view($email,$where);
            if(isset($response->value)):
            foreach($response->value as $r){
                if($r->sensitivity=='private'):
                    $e=array(
                        'title'=>'OCCUPE',
                        'start'=>  adjust_gmt_calendar($r->start->dateTime),
                        'end'=>adjust_gmt_calendar($r->end->dateTime)
                    );
                else : 
                    $e=array(
                        'title'=>$r->subject,
                        'description'=>$r->body->content,
                        'location'=>$r->location->displayName,
                        'attendees'=>'',
                        'organize'=>$r->organizer->emailAddress->name,
                        'start'=>  adjust_gmt_calendar($r->start->dateTime),
                        'end'=>adjust_gmt_calendar($r->end->dateTime)
                    );
                    foreach($r->attendees as $attendee):
                        if(empty($e['attendees'])): $pre=''; else :  $pre=',<br>'; endif;
                        $e['attendees'].=$pre.' '.$attendee->emailAddress->name.' ('.$attendee->status->response.')';
                    endforeach;
                endif;
                array_push($events, $e);
            }
            endif;
        endif;
        $data['events']=$events;
        $data["dataview"]=$this->dataview;
        return view($this->viewpath .'\full_calendar_outlook', $data);
//                  echo '<pre>';
//                  print_r($events);
    }
   

    public function set_tache()
	{
      
		$post=$this->request->getVar();

        //debugd($post);

		$data["id_demande"]=$post["id_demande"];

        if(empty(trim($post["sujet_tache"])))
        {
            $data["titre"]="Tâche sans titre";
        }
        else
        {
            $data["sujet"]=trim($post["sujet_tache"]);
        }
		
		$data["date_tache"]=$post["date_tache"].' '.$post["date_tache_h"].':00' ;
        $data["echeance"]=$post["echeance"];

       // debugd($post);
        //debugd($data);
        $data["date_rappel"]=$this->request->getVar("date_tache_rappel");

       

		$data["id_type_tache"]=$this->request->getVar("type_tache");
        $data["type_tache_libre"]=$this->request->getVar("type_tache_libre");
		$data["id_statut_tache"]=$this->request->getVar("statut_tache");
        
        if(isset($post["user_tache"]))
        {
            $data["id_user_tache"]=implode(",",$post["user_tache"]);
        }
        else
        {
            $data["id_user_tache"]=0;
        }


        $data["date_insert"]=date("Y-m-d H:i:s");
        $data["date_modification"]=date("Y-m-d H:i:s");
        $data["id_user"]=session()->get('loggedUserId');
        $data["id_user_create"]=session()->get('loggedUserId');

        if(isset($post["id_tache"]))
        {
            $id_tache=$post["id_tache"];
        }
        else
        {
            $id_tache=0;
        }
        
        if(isset($post["id_demande"]))
        {
            $id_demande=$post["id_demande"];

        }
        else
        {
            $id_demande=0;
        }

        if(!empty(trim($post["note_tache_direct"])))
        {
            $data["id_user_note"]=session()->get('loggedUserId');
            $data["date_user_note"]=date("Y-m-d H:i:s");
            $data["note"]=trim($post["note_tache_direct"]);
        }
        
        if(isset($post["is_prive"]))
        {
            $data["is_prive"]=$post["is_prive"];
        }
        else
        {
            $data["is_prive"]=0;
        }

        if(isset($post["is_rappel"]))
        {
            $data["rappel"]=$post["is_rappel"];
        }
        else
        {
            $data["rappel"]=0;
        }
		
        unset($data["id_demande"]);
     
        $id_tache=$this->tacheModel->set_tache($data,$id_demande,$id_tache);

        if (!$this->request->isAJAX())
        {
            return redirect()->to(base_url()."tache/form_tache/$id_demande/$id_tache")->with("success","La tâche a été enregistrée");
        }
       

       
       

	}

    public function setType()
    {
        if($this->request->getVar("id_tache"))
        {
            $this->tacheModel->setType($this->request->getVar("id_tache"),$this->request->getVar("type_tache_select"));

            echo view($this->module.'\form_tache_type', [
                "tache" => $this->tacheModel->getTache($this->request->getVar("id_tache"))
              
                
            ]);
        }
    }

    public function setStatut()
    {
        if($this->request->getVar("id_tache"))
        {
            $this->tacheModel->setType($this->request->getVar("id_tache"),$this->request->getVar("statut_tache_select"));

            echo view($this->module.'\form_tache_statut', [
                "tache" => $this->tacheModel->getTache($this->request->getVar("id_tache"))
              
                
            ]);
        }
    }

    public function setCommentaire()
    {
        if($this->request->getVar("id_tache"))
        {
            $this->tacheModel->setCommentaire($this->request->getVar("id_tache"),$this->request->getVar("note"));

            echo view($this->module.'\form_tache_commentaire', [
                "tache" => $this->tacheModel->getTache($this->request->getVar("id_tache"))
              
                
            ]);
        }
    }

}