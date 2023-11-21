<?php

namespace Historique\Models;

use CodeIgniter\Model;

use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;

class HistoriqueModel extends Model
{
	protected $table="historique";
	protected $tableContact="contact";
	protected $primaryKey= 'id_historique';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object';

	public function __construct()
	{
		parent::__construct();
		
		//$this->fields=$fields->fields;

        $this->dataViewConstructor=new DataViewConstructor();
		

	}

	
    public function set_remarque_gestion_inscription($id_inscription,$type,$index,$new_value=NULL,$old_value=NULL,$by_system=NULL)
    {
      
       
        $new_remarques_gestion=NULL;

        $date_jour=date("d-m-Y à H:i:s");

        if(!is_null($by_system)&&$by_system=="system")
        {
            $user_actif="le système";
        }
        else
        {
        
            if(session()->loggedUserPrenom&&!empty(session()->loggedUserPrenom))
            {
                $user_actif=session()->loggedUserPrenom;
            }
            else
            {
                $user_actif=session()->loggedUserName;
            }
       
        }


        $builder=$this->db->table("inscriptions");
        

        $builder->where("id_inscription",$id_inscription);
        $builder->join($this->tableContact,"contacts.id_contact=inscriptions.id_contact","left");

        $builder->set("remarques_gestion,$this->tableContact.nom,$this->tableContact.prenom");
        $inscription=$builder->get()->getRow();

       

        if(!empty(trim($inscription->remarques_gestion)))
        {
            $new_remarques_gestion=$inscription->remarques_gestion." - ";
        }
        
        switch($type)
        {
            case "send":

                $new_remarques_gestion=$new_remarques_gestion." Le fichier $index a été envoyé le $date_jour par $user_actif";

                break;

            case "telecharge":

                $new_remarques_gestion=$new_remarques_gestion." Le fichier $index a été téléchargé le $date_jour par $user_actif";

                break;


            case "delete":

                $new_remarques_gestion=$new_remarques_gestion." Le fichier $index a été effacé le $date_jour par $user_actif";

                break;


            case "create":

                $new_remarques_gestion=$new_remarques_gestion." Le fichier $index a été créé le $date_jour par $user_actif";

                break;    

            case "index_bd":    
            case "index_db":
              
               /* echo $id_inscription;
                echo '<br>ZZZZ';
                echo $type;
                echo '<br>';
                echo $index;
                echo '<br>';
                echo $new_value;
                echo '<br>';
                echo $old_value;
                echo '<br>';
                die();*/
                //A partir de l'index et de la value, retrouver la valuer actuellement
                //je dois connaitre la valeur avant transformation

                    if(empty($new_value))
                    {
                        $info=$this->dataViewConstructor->getValueReadWithLabel($index,$new_value);
                        $new_remarques_gestion=$new_remarques_gestion." ".$info["label"]." a été effacé le $date_jour par $user_actif";
                    }
                    else
                    {
                        $info=$this->dataViewConstructor->getValueReadWithLabel($index,$new_value);
                        $new_remarques_gestion=$new_remarques_gestion." ".$info["label"]." a été modifé en ".$info["value"]. " le $date_jour par $user_actif";
                    }
                    
                   
                   
                    if(empty($old_value))
                    {
                        $new_remarques_gestion=$new_remarques_gestion.' (Avant:'.$info["label"].' était vide)';
                    }
                    else
                    {
                        $info_old=$this->dataViewConstructor->getValueReadWithLabel($index,$old_value);

                        $new_remarques_gestion=$new_remarques_gestion.' (Avant:'.$info["label"].' était '.$info_old["value"].')';
                    }

                
                break;

            default:
                return NULL;
            
        }
        
        //die("ici");
       // debug($id_inscription);
        //debug($new_remarques_gestion,true);
        $new_remarques_gestion=str_replace(["- -","--"],"-",$new_remarques_gestion);

        $builder_update=$this->db->table("inscriptions");
        $builder_update->where("id_inscription",$id_inscription);
        return $builder_update->update(["remarques_gestion"=>$new_remarques_gestion]);

        
    }


   
	

}
