<?php

namespace Messagerie\Controllers;

use Base\Controllers\BaseController;
use Layout\Libraries\LayoutLibrary;
use Messagerie\Models\MessagerieModel;
use Components\Libraries\ComponentOrderBy;
use DataView\Libraries\DataViewConstructor;

class Messagerie extends BaseController {

	public function __construct()
	{
		if(session('loggedUserRoleId')==2)
        {
            header("Location:" . base_url("user"));
        }

        if(session()->get("loggedUserRoleId")!=1)
        {
            header("Location:" . base_url("identification/logout"));
        }

		parent::__construct(__NAMESPACE__);
	   
		$this->messagerieModel = new MessagerieModel();
        $this->context = "messagerie";

        $request=$this->request;
        $this->componentOrderBy=new ComponentOrderBy();
        $layout_l = new LayoutLibrary();

        $this->datas->theme = $layout_l->getThemeByRef($this->context);
        $this->datas->context = $this->context;  

		$this->dataview=new DataViewConstructor();

            
	}
        
	public function index()
	{
		$orderBy=$this->componentOrderBy->getOrderBy("vu,date_created",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("ASC",$this->request);

		$fieldsOrder=
        [
			"vu"=>[null,true],
			"entity"=>["Lien à",true],
            "date_created"=>["Date de création",true],
            "id"=>["N°",true],
            "nom"=>["De",true],
            "subject"=>["Objet",true],
            "content"=>["Texte de la note",true],
           
           
        ];

		$id_destinataire=session()->get("loggedUserId");
		$messages=$this->messagerieModel->getListMessagerie($id_destinataire,$this->request,$orderBy,$orderDirection);
		
		//debug($messages);

		$this->datas->messages=$messages;
		$this->datas->pager=$this->messagerieModel->pager;
		$this->datas->nbMessages= $this->datas->pager->getTotal();
        $this->datas->context= $this->context;
        $this->datas->itemSearch=$this->request->getVar("itemSearch");
        $this->datas->titleView="Liste des messages";
        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);

		return view("Messagerie".'\list_messages', (array) $this->datas );


	
	}

	public function count_non_lu()
	{
		$id_destinataire=session()->get("loggedUserId");
		echo $this->messagerieModel->count_non_lu($id_destinataire);
		
	}

	public function get_non_lu()
	{
		$id_destinataire=session()->get("loggedUserId");
		$this->datas->messages= $this->messagerieModel->get_non_lu($id_destinataire);
		
		return view("Messagerie\liste_messagerie_non_lu",(array) $this->datas);
	}

	public function message_view($id_messagerie)
	{
		$this->datas->message=$this->messagerieModel->message_view($id_messagerie);
		//$this->messagerieModel->set_message_lu($id_messagerie,session()->get("loggedUserId"));
		return view("Messagerie\message_view_note",(array) $this->datas);
	}


	public function get_note_of_entity($entity,$id_entity,$is_request_ajax=false)
	{
		$request = \Config\Services::request();
        if(!$is_request_ajax)
        {
            $is_request_ajax=$request->isAJAX();
        }
		
		$this->datas->request_is_ajax=$is_request_ajax;
		$this->datas->messages= $this->messagerieModel->get_note_of_entity($entity,$id_entity);

		return view("Messagerie\liste_note_entity",(array) $this->datas);
	}


	public function is_non_lu_entity($entity,$id_entity,$is_request_ajax=false)
	{
		$id_destinataire=session()->get("loggedUserId");
		$messages=$this->messagerieModel->get_note_of_entity_no_lu($entity,$id_entity,$id_destinataire);
		if(empty($messages))
		{
			return NULL;
		}
		else
		{
			$request = \Config\Services::request();
			if(!$is_request_ajax)
			{
				$is_request_ajax=$request->isAJAX();
			}
			$this->datas->request_is_ajax=$is_request_ajax;
			$this->datas->messages=$messages;
			return view("Messagerie\liste_note_entity",(array) $this->datas);
		}
	}

	public function get_note_of_entity_no_lu($entity,$id_entity,$is_request_ajax=false)
	{
		$request = \Config\Services::request();
        if(!$is_request_ajax)
        {
            $is_request_ajax=$request->isAJAX();
        }

		$this->datas->request_is_ajax=$is_request_ajax;
		$id_destinataire=session()->get("loggedUserId");
		$this->datas->messages= $this->messagerieModel->get_note_of_entity_no_lu($entity,$id_entity,$id_destinataire);

		return view("Messagerie\liste_note_entity",(array) $this->datas);
	}

	public function form_note($entity,$id_entity)
	{
		
       

		return view("Messagerie\/form_note",
			[
				"entity"=>$entity,
				"id_entity"=>$id_entity,
				"dataview"=>$this->dataview,

			]
		
		);

	}

	public function set_lu_entity()
	{
			if($this->request->getVar("id_messages_lu"))
			{
				foreach($this->request->getVar("id_messages_lu") as $id_messagerie)
				{
					$this->messagerieModel->set_message_lu($id_messagerie,session()->get("loggedUserId"));
				}
	
			}

			return;
	}

	public function set_note()
	{
		$post=$this->request->getVar();

		return $this->messagerieModel->set_note($post);

	}

	public function set_note_no_ajax()
	{
		$post=$this->request->getVar();

		$this->messagerieModel->set_note($post);

		$entity=$post["entity"];
		$id_entity=$post["id_entity"];

		if($entity!="rdv"||$entity!="tache")
		{
			$uri="$entity/fiche/$id_entity";
		}

		return redirect()->to(base_url($uri))->with("success","La note a été ajoutée");

	}


	public function get_container_note($entity,$id_entity)
	{
		$data["entity"]=$entity;
		$data["id_entity"]=$id_entity;
		$data["notes_non_lues"]=$this->is_non_lu_entity($entity,$id_entity,$is_request_ajax=true);

		return view("Messagerie\container_note",$data);
	}
	
}