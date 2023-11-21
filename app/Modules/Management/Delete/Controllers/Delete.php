<?php

namespace Delete\Controllers;


use Base\Controllers\BaseController;
use Delete\Models\DeleteModel;
use DataView\Models\DataViewConstructorModel;
use Modelisation\Models\DeleteModel as ModelisationDeleteModel;

class Delete extends BaseController
{

    protected $dataViewConstructorModel;


    protected $context;

    public function __construct()
    {
        if(session()->get("loggedUserRoleId")!=1)
       {
            header("Location:".base_url("identification/logout"));
       }
        
        $this->deleteModele = new DeleteModel();
        $this->dataViewConstructorModel=new DataViewConstructorModel();
    }

    public function deleteFieldIndex()
    {
        $ModelisationDeleteModel = new ModelisationDeleteModel();
        if($this->request->getPost("idDelete")&&!empty($this->request->getPost("idDelete")))
        {
            $is_delete=$ModelisationDeleteModel->FieldDelete($this->request->getPost("idDelete"));
            if($is_delete)
            {
                return redirect()->to($this->request->getPost("uriReturn"))->with("success","Le champ a été supprimé CRM");

            }
            else
            {
                return redirect()->to($this->request->getPost("uriReturn"))->with("danger","Impossible d'effacer ce champ");
            }
        }
        else
        {
            return redirect()->to($this->request->getPost("uriReturn"))->with("danger","Impossible d'effacer ce champ");
        }
    }

    public function deletePartenaire()
    {
        if($this->request->getPost("idDelete")&&$this->request->getPost("idDelete")>0)
        {
            $partenaire=$this->deleteModele->getPartenaire($this->request->getPost("idDelete"));

           
                $name_partenaire=$partenaire->nom_partenaire;
            
          
           $is_delete=$this->deleteModele->deletePartenaire($this->request->getPost("idDelete"));

           if($is_delete)
            {
                return redirect()->to($this->request->getPost("uriReturn"))->with("success","$name_partenaire a été supprimé CRM");

            }
            else
            {
                return redirect()->to($this->request->getPost("uriReturn"))->with("danger","Impossible d'effacer $name_partenaire");
            }
        }
        else
        {
            return redirect()->to($this->request->getPost("uriReturn"))->with("danger","Impossible d'effacer $name_partenaire");
        }

    }

    

    public function deleteEmail()
    {
        if($this->request->getPost("idDelete")&&$this->request->getPost("idDelete")>0)
        {
            $message=$this->deleteModele->getEmail($this->request->getPost("idDelete"));

            
          
            $is_delete=$this->deleteModele->deleteEmail($this->request->getPost("idDelete"));

           if($is_delete)
            {
                return redirect()->to($this->request->getPost("uriReturn"))->with("success","Le message n°$message->id_primary - $message->subject a été supprimé du CRM");

            }
            else
            {
                return redirect()->to($this->request->getPost("uriReturn"))->with("danger","Impossible d'effacer le message n°$message->id_requete - $message->subject");
            }
        }
        else
        {
            return redirect()->to($this->request->getPost("uriReturn"))->with("danger","Impossible d'effacer le message n°$message->id_requete - $message->subject");
        }

    }

    public function deleteQuery()
    {
        if($this->request->getPost("idDelete")&&$this->request->getPost("idDelete")>0)
        {
            $query=$this->deleteModele->getQuery($this->request->getPost("idDelete"));

            
          
            $is_delete=$this->deleteModele->deleteQuery($this->request->getPost("idDelete"));

           if($is_delete)
            {
                return redirect()->to($this->request->getPost("uriReturn"))->with("success","La requête n°$query->id_requete - $query->nom a été supprimée CRM");

            }
            else
            {
                return redirect()->to($this->request->getPost("uriReturn"))->with("danger","Impossible d'effacer la requête n°$query->id_requete - $query->nom");
            }
        }
        else
        {
            return redirect()->to($this->request->getPost("uriReturn"))->with("danger","Impossible d'effacer la requête n°$query->id_requete - $query->nom");
        }

    }

    public function deleteInjectedForm()
    {
        if($this->request->getPost("idDelete")&&$this->request->getPost("idDelete")>0)
        {
            $injectedForm=$this->deleteModele->getInjectedForm($this->request->getPost("idDelete"));

            
          
            $is_delete=$this->deleteModele->deleteInjectedForm($this->request->getPost("idDelete"));

           if($is_delete)
            {
                return redirect()->to($this->request->getPost("uriReturn"))->with("success","Le modèle de formulaire $injectedForm->title a été supprimé CRM");

            }
            else
            {
                return redirect()->to($this->request->getPost("uriReturn"))->with("danger","Impossible d'effacer le modèle de formulaire $injectedForm->title");
            }
        }
        else
        {
            return redirect()->to($this->request->getPost("uriReturn"))->with("danger","Impossible d'effacer le modèle de formulaire $injectedForm->title");
        }

    }
	
    

    
}