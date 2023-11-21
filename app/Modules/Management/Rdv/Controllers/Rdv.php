<?php

namespace Rdv\Controllers;

use Base\Controllers\BaseController;

use Rdv\Models\RdvModel;
use Layout\Libraries\LayoutLibrary;

use Demande\Models\DemandeModel;


use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;

use Outlook\Libraries\Myoutlook_lib;
use Outlook\Libraries\Tr_outlook;

use Components\Libraries\ComponentOrderBy;
class Rdv extends BaseController
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

        $this->rdvModel = new RdvModel();

        $this->dataview=new DataViewConstructor();

        $this->tr_outlook=new Tr_outlook();
        $this->myoutlook_lib=new Myoutlook_lib();

        $this->demandeModel=new DemandeModel();

        $this->context = 'rdv';
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
            header("Location:".base_url()."rdv?mes_rdv=1");
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

    public function liste_rdv()
    {
     
        if(empty($this->request->getVar()))
        {
            header("Location:".base_url()."rdv?mes_rdv=1");
            exit();
        }
      
       
        

   
        $orderBy=$this->componentOrderBy->getOrderBy("rdv.date_rdv_debut",$this->request);


        $orderDirection=$this->componentOrderBy->getOrderDirection("DESC",$this->request);

        $fieldsOrder=
        [
            "delete"=>[null,false,"rdvs_d"],
            "date_rdv_debut"=>["Début",true],
            "date_rdv_fin"=>["Fin",true],
            "titre"=>["Titre",true],
            "note"=>["Commentaire",true],
           "type_rdv"=>["Type",true],
           "statut_rdv"=>["Statut",true],
           "rdv.id_demande"=>["Demande liée",true],
           
           
           

        ];

        $this->datas->type_rdv=$this->request->getVar("type_rdv");

        $this->datas->mes_rdv=$this->request->getVar("mes_rdv");
        
        $this->datas->rdvs=$this->rdvModel->getListRdv($this->request,$orderBy,$orderDirection);
        $this->datas->pager=$this->rdvModel->pager;
        $this->datas->nbRdvs= $this->datas->pager->getTotal();
        $this->datas->fields= $this->rdvModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->itemSearch=$this->request->getVar("itemSearch");
        $this->datas->titleView="Liste des rdvs";
        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);
       // $this->datas->type_rdv=$this->rdvModel->get_liste_type_rdv();
        //$this->datas->type_rdv=$this->rdvModel->get_liste_statut_rdv();
        $this->datas->module=$this->module;


        return view($this->module . '\list-rdv', (array) $this->datas );
    }

    public function form_rdv($id_demande=0,$id_rdv=0,$is_request_ajax=false)
    {

        $request = \Config\Services::request();
        if(!$is_request_ajax)
        {
            $is_request_ajax=$request->isAJAX();
        }
       
        $this->datas->dataview=$this->dataview;

        $this->datas->calendar=$this->get_calendar();

        $this->datas->id_demande=$id_demande;
        $this->datas->id_rdv=$id_rdv;
        $this->datas->request_is_ajax=$is_request_ajax;

        if($id_demande>0)
        {
            $this->datas->id_user=$this->demandeModel->get_user_encharge($id_demande);
        }
        else
        {
            $this->datas->id_user=session()->get("loggedUserId");
        }

        if($id_rdv>0)
        {
            $this->datas->rdv=$this->rdvModel->getRdv($id_rdv);
            $this->datas->typeDataView="read";
            $this->datas->title ="Rendez-vous";
            $this->datas->titleView = "Rendez-vous";
    
        }
        else
        {
            $this->datas->rdv=NULL;
            $this->datas->typeDataView="new_form";
            $this->datas->title ="Formulaire Rendez-vous";
            $this->datas->titleView = "Créer un rendez-vous";
    
        }
       
       //debug($this->datas->rdv);

        return view($this->viewpath .'\form_rdv', (array) $this->datas);

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
   

    public function set_rdv()
	{
      
		$post=$this->request->getVar();

        //debugd($post);

		$data["id_demande"]=$post["id_demande"];

        if(empty(trim($post["titre_rdv"])))
        {
            $data["titre"]="RDV sans titre";
        }
        else
        {
            $data["titre"]=trim($post["titre_rdv"]);
        }
		
		$data["date_rdv_debut"]=$post["date_rdv_debut"].' '.$post["date_rdv_debut_h"].':00' ;
		$data["date_rdv_fin"]=$post["date_rdv_fin"].' '.$post["date_rdv_fin_h"].':00' ;
		$data["temp_avant"]=$post["temp_avant_rdv"];
		$data["temp_apres"]=$post["temp_apres_rdv"];

        if(isset($post["type_rdv"])):
		    $data["id_type_rdv"]=$post["type_rdv"];
        else:
            $data["id_type_rdv"]=0;
        endif;

        if(isset($post["statut_rdv"])):
		    $data["id_statut_rdv"]=$post["statut_rdv"];
        else:
            $data["id_statut_rdv"]=0;
        endif;


        if(isset($post["user_rdv"]))
        {
            $data["id_user_rdv"]=implode(",",$post["user_rdv"]);
        }
        else
        {
            $data["id_user_rdv"]=0;
        }
		
		$data["lieu"]=trim($post["lieu_rdv"]);
        $data["date_insert"]=date("Y-m-d H:i:s");
        $data["date_modification"]=date("Y-m-d H:i:s");
        $data["id_user"]=session()->get('loggedUserId');
        $data["id_user_create"]=session()->get('loggedUserId');
        $id_rdv=$post["id_rdv"];
        $id_demande=$post["id_demande"];

        if(!empty(trim($post["note_rdv"])))
        {
            $data["id_user_note"]=session()->get('loggedUserId');
            $data["date_user_note"]=date("Y-m-d H:i:s");
            $data["note"]=trim($post["note_rdv"]);
        }
        
        if(isset($post["is_prive_rdv"]))
        {
            $data["is_prive"]=$post["is_prive_rdv"];
        }
        else
        {
            $data["is_prive"]=0;
        }
		
       
        $id_rdv=$this->rdvModel->set_rdv($data,$id_demande,$id_rdv);

        $this->myoutlook_lib->update_calendar_outlook($id_rdv);

        if (!$this->request->isAJAX())
        {
            return redirect()->to(base_url()."rdv/form_rdv/$id_demande/$id_rdv")->with("success","Le rendez-vous a été enregistré");
        }
       

       
       

	}

    public function setType()
    {
        if($this->request->getVar("id_rdv"))
        {
            $this->rdvModel->setType($this->request->getVar("id_rdv"),$this->request->getVar("type_rdv_select"));

            echo view($this->module.'\form_rdv_type', [
                "rdv" => $this->rdvModel->getRdv($this->request->getVar("id_rdv"))
              
                
            ]);
        }
    }

    public function setStatut()
    {
        if($this->request->getVar("id_rdv"))
        {
            $this->rdvModel->setType($this->request->getVar("id_rdv"),$this->request->getVar("statut_rdv_select"));

            echo view($this->module.'\form_rdv_statut', [
                "rdv" => $this->rdvModel->getRdv($this->request->getVar("id_rdv"))
              
                
            ]);
        }
    }

    public function setCommentaire()
    {
        if($this->request->getVar("id_rdv"))
        {
            $this->rdvModel->setCommentaire($this->request->getVar("id_rdv"),$this->request->getVar("note"));

            echo view($this->module.'\form_rdv_commentaire', [
                "rdv" => $this->rdvModel->getRdv($this->request->getVar("id_rdv"))
              
                
            ]);
        }
    }


    public function deleteRdv()
    {
        if($this->request->getPost("idDelete")&&$this->request->getPost("idDelete")>0)
        {
            $rdv=$this->rdvModel->getRdv($this->request->getPost("idDelete"));

           
                $name_rdv=$rdv->titre;
                
                
                //$rdvA=$this->ban_crud_model->read_data('rdv','rdv.id_rdv_outlook as id_outlook, user_accounts.email as organize_mail', 'rdv.id_rdv='.$id_rdv, $left);

            $this->myoutlook_lib->delete_event_calendar($this->request->getPost("idDelete"));

           $is_delete=$this->rdvModel->delete_rdv($this->request->getPost("idDelete"));

          


           if($is_delete)
            {
                return redirect()->to($this->request->getPost("uriReturn"))->with("success","Le rendez-vous $name_rdv du". convert_date_en_to_fr_with_h($rdv->date_rdv_debut)." a été supprimé de la base de donnée");

            }
            else
            {
                return redirect()->to($this->request->getPost("uriReturn"))->with("danger","Impossible d'effacer le rendez-vous $name_rdv du ".convert_date_en_to_fr_with_h($rdv->date_rdv_debut));
            }
        }
        else
        {
            return redirect()->to($this->request->getPost("uriReturn"))->with("danger","Impossible d'effacer le rendez-vous $name_rdv du ".convert_date_en_to_fr_with_h($rdv->date_rdv_debut));
        }


    }

}