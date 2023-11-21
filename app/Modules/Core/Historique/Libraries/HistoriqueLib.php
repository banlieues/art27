<?php 

namespace Historique\Libraries;

use Historique\Models\HistoriqueModel;
use Components\Models\BanCrudModel;


class HistoriqueLib
{
	public function __construct()
    {
        $this->ban_crud_model=new BanCrudModel();

    }
   	
	public function get_statut($id)
	{
        $view;
		//$view="<small>".$this->fh_dao->get_dao("demande","id_demande_statut",$id,$is_priory=TRUE,$request=NULL,$is_groupe=FALSE,$width_idirect=NULL,$descriptor=NULL,$is_all_span=TRUE);

		//$view.="</small>";
		return $view;

		
	}
	
        
	public function set_historique_general($id_type_demandes=array(),$id_demande=0,$id_demandeur=0,$id_bien=0,$id_message,$id_rdv=0,$id_tache=0)
	{
	    
	    //Recuperation du demandeur
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
		$dd=$this->ban_crud_model->read_data("contact","nom_contact as nom ,prenom_contact as prenom","id_contact=$id_demandeur");
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
      
}