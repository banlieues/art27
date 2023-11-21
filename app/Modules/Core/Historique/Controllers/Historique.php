<?php 

namespace Historique\Controllers;

use Historique\Models\HistoriqueModel;
use Components\Models\BanCrudModel;
use Layout\Libraries\LayoutLibrary;

use Base\Controllers\BaseController;


class Historique extends BaseController 
{
    public function __construct()
    {
		parent::__construct(__NAMESPACE__);

      $this->ban_crud_model=new BanCrudModel();

       /* $layout_l = new LayoutLibrary();
		$this->datas->theme = $layout_l->getThemeByRef("historique");
        $this->datas->context = "historique";*/
    }

   	      
	public function set_historique_general($id_type_demandes=array(),$id_demande=0,$id_demandeur=0,$id_bien=0,$id_message,$id_rdv=0,$id_tache=0)
	{
	    
	    //Recuperation du demandeur
		if(!is_array($id_type_demandes))
		{
			$id_type_demandes=[$id_type_demandes];
		}
	    sort($id_type_demandes);
	    $demande=NULL;
	    if($id_demande>0&&!is_null($id_demande)): 
		$left=array();
		$left_condition["liste_demande_type"]="liste_demande_type.id=demande.id_type_demande";
		array_push($left,$left_condition);
		$ds=$this->ban_crud_model->read_data("demande","number,liste_demande_type.label","demande.id_demande=$id_demande",$left);
		if(isset($ds[0]->number)): 
		    $demande.=$id_demande." ".$ds[0]->label;
		endif;
	    endif;
	    
	    $demandeur=NULL;
	    
	    if($id_demandeur>0&&!is_null($id_demandeur)):
		$dd=$this->ban_crud_model->read_data("contact","nom_contact as nom,prenom_contact as prenom","id_contact=$id_demandeur");
		if(isset($dd[0]->nom)):
		    $demandeur.=$dd[0]->nom." ".$dd[0]->prenom;
		endif;
	    endif;
	    
	    $bien=NULL;
	    if($id_bien>0&&!is_null($id_bien)):
		$dd=$this->ban_crud_model->read_data("bien","adresse_fr","id_bien=$id_bien");
		if(isset($dd[0]->adresse_fr)):
		    $bien.=$dd[0]->adresse_fr;
		endif;
	    endif;
	    
	    
	     $message=NULL;
	    if($id_message>0&&!is_null($id_message)):
		$dd=$this->ban_crud_model->read_data("email_outlook","subject,sender_mail","id_primary=$id_message");
		if(isset($dd[0]->subject)):
		    $message.='"'.$dd[0]->subject. '" envoyé par '.$dd[0]->sender_mail;
		endif;
	    endif;
	    
	      $tache=NULL;
	    if($id_tache>0&&!is_null($id_tache)): 
		$left=array();
		$left_condition["id_type_tache"]="liste_tache_type.id=demande.id_type_tache";
		array_push($left,$left_condition);
		$ds=$this->ban_crud_model->read_data("demande","id_type_tache,liste_type_tache.label,type_tache_libre,sujet","id_tache=$id_tache",$left);
		if(isset($ds[0]->label)): 
		    if($ds[0]->id_type_tache===6):
			$tache.=$ds[0]->type_tache_libre." dont le sujet est ".$ds[0]->sujet;
		    else:	
			$tache.=$ds[0]->label;
		    endif;
		endif;
	    endif;
	    
	     $rdv=NULL;
	    if($id_rdv>0&&!is_null($id_rdv)): 
		$left=array();
		$left_condition["id_type_rdv"]="liste_rdv_type.id=demande.id_type_rdv";
		array_push($left,$left_condition);
		$ds=$this->ban_crud_model->read_data("demande","id_type_rdv,liste_type_rdv.label,titre","id_rdv=$id_rdv",$left);
		if(isset($ds[0]->label)): 
		    
				
			$rdv.=$ds[0]->label." dont le titre est ".$ds[0]->titre;
		  
		endif;
	    endif;
	  
	    $data["id_demande"]=$id_demande;
	    $data["id_demandeur"]=$id_demandeur;
	    $data["id_bien"]=$id_bien;
	    $data["id_message"]=$id_message;
	    $data["id_rdv"]=$id_rdv;
	    $data["id_tache"]=$id_tache;
	    $data["id_user"]=session()->get('loggedUserId');
	    $user_accounts=$this->ban_crud_model->read_data("user_accounts","nom,prenom","user_accounts.id=".session()->get('loggedUserId'));
	    $data["user"]=$user_accounts[0]->nom." ".$user_accounts[0]->prenom;
	    
	    $data["interface"]=session()->get("interface");
	    
	    foreach($id_type_demandes as $id_type_demande):
	    //statut: 1 - création d'une demande
	    switch ($id_type_demande) :
		case 1:
		    $comment="Création de la demande n°$demande";
		    break;
		case 2:
		    $comment="Création du bien $bien";
		    break;
		case 3:
		    $comment="Ajout du bien $bien à la demande n°$demande" ;
		    break;
		
		case 4:
		    $comment="Création du demandeur $demandeur";
		    break;
		case 5:
		    $comment="Ajout du demandeur $demandeur à la demande n°$demande" ;
		    break;
		
		case 6:
		    $comment="Ajout du message $message à la demande n°$demande" ;
		    break;
		
		case 7:
		    $comment="Création d'une tâche $tache" ;
		    break;
		
		case 8:
		    $comment="Ajout d'une tâche $tache à la demande n°$demande" ;
		    break;
		
		case 9:
		    $comment="Création d'un rdv $rdv" ;
		    break;
		
		case 10:
		    $comment="Ajout d'un rdv $rdv à la demande n°$demande" ;
		    break;
		
		case 11:
		    $comment="Message $message à partir de la demande n°$demande" ;
		    break;
		case 12:
		    $comment="Réponse au $message à partir de la demande n°$demande" ;
		    break;
		
		case 12:
		    $comment="Transfert du $message à partir de la demande n°$demande" ;
		    break;
		
		case 13:
		    $comment="Détachement du $message de la demande n°$demande" ;
		    break;
		
		case 14:
		    $comment="Ajout d'un document dans la boîte de dépôt de la demande n°$demande" ;
		    break;
		
		default:
		    $comment=NULL;
	    endswitch;
	    
	   
	    $data["comment"]=$comment;
	    $data["id_type"]=$id_type_demande;
	    
	    $this->ban_crud_model->insert_data($data,"historique_general");
	endforeach;
	
	}

	public function get_historique_demande($id_entity)
	{
	    //die($id_entity);
	    //récuperer mail, action, tache, note, messagerie interne$
	   
	    
	    //1. Récupere les mails de la demande
	    
	    $data=array();
	    
	    //sql mail
	    $left=array();
	    $left_condition["email_outlook"]="email_outlook.id_primary=email_outlook_lien.id_email";
	    array_push($left,$left_condition);
	    $where='id_demande='.$id_entity;
	    $mails=$this->ban_crud_model->read_data("email_outlook_lien","*",$where,$left,"created_datetime");
	    
	     //sql messagerie interne
	    unset($left_condition);
	    $left=array();
	    $left_condition["email_outlook"]="email_outlook.id_primary=email_outlook_lien.id_email";
	    array_push($left,$left_condition);
	    $where='id_entity='.$id_entity. " AND entity='demande' AND display=1 ";
	    $messagerie=$this->ban_crud_model->read_data("fh_messagerie","*",$where,NULL,"date_created");
	    
	     unset($left_condition);
	 
	     $left=array();
	     $left_condition["demande_rdv"]="demande_rdv.id_rdv=rdv.id_rdv";
	    $left_condition["liste_rdv_type"]="liste_rdv_type.id=rdv.id_type_rdv";
	     $left_condition["liste_rdv_statut"]="liste_rdv_statut.id=rdv.id_statut_rdv";
	    array_push($left,$left_condition);
	    $where='demande_rdv.id_demande='.$id_entity;
	    $rdvs=$this->ban_crud_model->read_data("rdv","*,liste_rdv_type.label as type,liste_rdv_statut.label as statut",$where,$left);
	    
	     unset($left_condition);
	      $left=array();
	     $left_condition["demande_tache"]="demande_tache.id_tache=tache.id_tache";
	 $left_condition["liste_tache_type"]="liste_tache_type.id=tache.id_type_tache";
	     $left_condition["liste_tache_statut"]="liste_tache_statut.id=tache.id_statut_tache";
	     array_push($left,$left_condition);
	    $where='demande_tache.id_demande='.$id_entity;
	    $taches=$this->ban_crud_model->read_data("tache","*,liste_tache_type.label as type,liste_tache_statut.label as statut",$where,$left);
	    
	   // print_r($mails);
	 
	    if(isset($rdvs[0]->id_rdv)):
		foreach($rdvs as $rdv):
		    $d["date"]=$rdv->date_insert;
		    $d["header"]=" RDV du ".convert_date_en_to_fr_with_h($rdv->date_rdv_debut);
		    $d["title"]=" <b><i class='fa fa-address-book'></i> $rdv->titre</b>";
		    $d["corps"]="<b>De </b>".convert_date_en_to_fr_with_h($rdv->date_rdv_debut)." <b>à</b> ". convert_date_en_to_fr_with_h($rdv->date_rdv_debut);
		    $d["color"]="lightblue";
		    
		     $d["corps"].= "<br> <b>Statut: </b>$rdv->statut" ;
		    
		    
		     $d["corps"].="<br> <b>Type: </b>$rdv->type" ;
		   $d["corps"].="<br> <b>Lieu: </b>$rdv->lieu" ;
		   $d["corps"].="<br> <b>Note: </b>".$rdv->note ;
		   
		   $left_condition_rp=array();
		   $left_rp["contact"]='contact.id_contact=contact_rdv.id_contact';
		   array_push($left_condition_rp,$left_rp);
		   $where="id_rdv IN($rdv->id_rdv)";
		   $contacts=$this->ban_crud_model->read_data("contact_rdv","group_concat(concat(contact.prenom_contact,' ',contact.nom_contact)) as p",$where,$left_condition_rp);
		  if(isset($contacts[0]->p)):
		      $d["corps"].="<br> <b>Avec: </b>".$contacts[0]->p ;
		  endif;
		    
		     array_push($data,$d);
		endforeach;
	    endif;
	    
	    if(isset($taches[0]->id_tache)):
		foreach($taches as $tache):
		    $d["date"]=$tache->date_insert;
		    $d["header"]=" Tâche du ".convert_date_en_to_fr_with_h($tache->date_tache);
		    $d["title"]=" <b><i class='fa fa-paperclip'></i> $tache->sujet</b>";
		    $d["color"]="MediumPurple";
		    $d["corps"]="<b>Date: </b>".convert_date_en_to_fr_with_h($tache->date_tache);

		     $d["corps"]= "<br> <b>Statut: </b>$tache->statut" ;
		    
		    
		     $d["corps"].="<br> <b>Type: </b>$tache->type" ;
		 
		   $d["corps"].="<br> <b>Note: </b>".$tache->note;
		    $left_condition_rp1=array();
		   $left_rp1["contact"]='contact.id_contact=contact_tache.id_contact';
		   array_push($left_condition_rp1,$left_rp1);
		   $where="id_tache IN($tache->id_tache)";
		   $contacts=$this->ban_crud_model->read_data("contact_tache","group_concat(concat(contact.prenom,' ',contact.nom)) as p",$where,$left_condition_rp1);
		  if(isset($contacts[0]->p)):
		      $d["corps"].="<br> <b>Avec: </b>".$contacts[0]->p ;
		  endif;
		     array_push($data,$d);
		endforeach;
	    endif;
	    
	    if(isset($mails[0]->id_email)):
		foreach($mails as $email):
		    $d["date"]=$email->created_datetime;
		    $d["header"]=" Email du ".convert_date_en_to_fr_with_h($email->created_datetime);
		    $d["title"]=" <b><i class='fa fa-envelope'></i> $email->subject</b>";
		    $d["corps"]="<b>De</b> $email->sender_mail <b>à</b> $email->to_mail";
		    $d["color"]="steelblue";
		    if(!empty($email->cc_mail)):
		     $d["corps"].= " <b>CC</b>$email->cc_mail" ;
		    endif;
		    if(!empty($email->bcc_mail)):
		     $d["corps"].=" <b>BCC</b>$email->bcc_mail" ;
		    endif;
		     $d["corps"].= "<br><br>";
		     $d["corps"].= "$email->body_content";
		     array_push($data,$d);
		endforeach;
	    endif;
	 
	    //2. Récupere les messages internes de la demande
	     if(isset($messagerie[0]->id)):
		foreach($messagerie as $messagerie):
		    $d["color"]="cadetblue  ";
		    $d["date"]=$messagerie->date_created;
		    $d["header"]="Note interne du ".convert_date_en_to_fr_with_h($messagerie->date_created);
		    $d["title"]=" <b><i class='fa fa-sticky-note-o'></i> $messagerie->subject</b>";
		    $d["corps"]=$messagerie->content;
		  
		     array_push($data,$d);
		endforeach;
	    endif;
	    
	     //3. Récupere les rdv
	    
	    
	    
	     //4. Récupere les tâches
	    
	
	    
	   
	    
	    //On evoye le résultat à la vue
	    $data_view["datas"]=$data;
	    
	    
	    
	    
	   //$data["historiques"]=$this->ban_crud_model->read_data("historique_general","*",'id_demande='.$id_entity);
	   return view("Historique\historique_general",$data_view);
	    
	}
      
	public function get_logs_fiche($entity,$id)
	{
		$db=db_connect();
		
		if($entity=="demande")
		{
			$builder_rel_bien=$this->db->table("personne_bien");
			$builder_rel_bien->where("id_demande",$id);
			$values_rel_bien=$builder_rel_bien->get()->getResult();

			$id_biens=[];
			$id_contacts=[];
			$id_personne_biens=[];
			if(!empty($values_rel_bien))
			{
				foreach($values_rel_bien as $rel_bien)
				{
					if(!in_array($rel_bien->id_personne_bien,$id_personne_biens))
					{
						array_push($id_personne_biens,$rel_bien->id_personne_bien);
					}
					if(!in_array($rel_bien->id_bien,$id_biens))
					{
						array_push($id_biens,$rel_bien->id_bien);
					}
					if(!in_array($rel_bien->id_contact,$id_contacts))
					{
						array_push($id_contacts,$rel_bien->id_contact);
					}
				}
			}
		}


		$table=$entity."_h";
		$builder=$db->table($table);

		$builder->select("
            $table.index,
            $table.value_old,
            $table.value_new,
            $table.date_modification,
            DATE_FORMAT($table.date_modification,'%d/%m/%Y <b>à</b> %H:%i') as date_modif,
            user_accounts.nom,user_accounts.prenom,
            $table.key_primary,
            $table.id_entity,
            ban_fields.label as label,
        ");

		$where["id_entity"]=$id;
		$builder->where($where);

		$builder->join("user_accounts","$table.id_user=user_accounts.id","left");	
		$builder->join("ban_fields","ban_fields.field_index=$table.index","left");
		
		//On fait union s'il s'agit id_demande
		if($entity=="demande")
		{
			if(!empty($id_contacts))
			{
				foreach($id_contacts as $id_contact)
				{
					$builder_contact=$db->table("contact_h");
					$builder_contact->select("
						contact_h.index,
						contact_h.value_old,
						contact_h.value_new,
                        contact_h.date_modification,
						DATE_FORMAT(contact_h.date_modification,'%d/%m/%Y <b>à</b> %H:%i') as date_modif,
						user_accounts.nom,user_accounts.prenom,
						contact_h.key_primary,
						contact_h.id_entity,
                        ban_fields.label as label,
                    ");
					$builder_contact->where(["id_entity"=>$id_contact]);

					$builder_contact->join("user_accounts","contact_h.id_user=user_accounts.id","left");	
					$builder_contact->join("ban_fields","ban_fields.field_index=contact_h.index","left");	

					$builder->union($builder_contact);
				}
			}

			if(!empty($id_biens))
			{
				foreach($id_biens as $id_bien)
				{
					$builder_bien=$db->table("bien_h");
					$builder_bien->select("
						bien_h.index,
						bien_h.value_old,
						bien_h.value_new,
                        bien_h.date_modification,
						DATE_FORMAT(bien_h.date_modification,'%d/%m/%Y <b>à</b> %H:%i') as date_modif,
						user_accounts.nom,user_accounts.prenom,
						bien_h.key_primary,
						bien_h.id_entity,
                        ban_fields.label as label,
                    ");
					$builder_bien->where(["id_entity"=>$id_bien]);

					$builder_bien->join("user_accounts","bien_h.id_user=user_accounts.id","left");	
					$builder_bien->join("ban_fields","ban_fields.field_index=bien_h.index","left");	

					$builder->union($builder_bien);
				}
			}

			if(!empty($id_personne_biens))
			{
				foreach($id_personne_biens as $id_personne_bien)
				{
					$builder_personne_bien=$db->table("personne_bien_h");
					$builder_personne_bien->select("
						personne_bien_h.index,
						personne_bien_h.value_old,
						personne_bien_h.value_new,
						personne_bien_h.date_modification,
						DATE_FORMAT(personne_bien_h.date_modification,'%d/%m/%Y <b>à</b> %H:%i') as date_modif,
						user_accounts.nom,user_accounts.prenom,
						personne_bien_h.key_primary,
						personne_bien_h.id_entity,
                        ban_fields.label as label,
                    ");
					$builder_personne_bien->where(["id_entity"=>$id_personne_bien]);

					$builder_personne_bien->join("user_accounts","personne_bien_h.id_user=user_accounts.id","left");	
					$builder_personne_bien->join("ban_fields","ban_fields.field_index=personne_bien_h.index","left");	

					$builder->union($builder_personne_bien);
				}
			}
		}

        $historiques = $this->db->table('(' . $builder->getCompiledSelect(). ') a')->orderBy("date_modification DESC")->get()->getResult();

		$this->datas->historiques=$historiques;
		$this->datas->entity=$entity;
		
		return view("Historique\logs_fiche",(array) $this->datas);
	}
      
}